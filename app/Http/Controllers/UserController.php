<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\User_tbl;
use App\Models\Course_tbl;
use App\Models\Module;             // <-- Added for Module CRUD
use App\Models\Quiz;               // <-- Added for Quiz operations
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // <-- Added for PDF file deletion
use App\Mail\ResetPasswordMail;
use App\Models\Facility;
use App\Models\LandingSection;
use App\Models\CarouselSlide;
use App\Models\HandsOnEvaluation;

class UserController extends Controller
{

    public function about_page()
    {

        return view("about");
    }

    public function SignupPage(){
        return view("student.sign_up");
    }

    public function Login(){
        return view("student.login");
    }

    public function SignUp(Request $request)
    {
        // dd($request->all());

        try {
            $request->validate(rules: [
                "firstname" => "required",
                "lastname" => "required",
                "email" => ["required", Rule::unique("user_tbls", "email")],
                "username" => "required",
                "password" => "required|confirmed",
                "role" => "in:student",
            ]);

            $user = User_tbl::create([
                "firstname" => $request->firstname,
                "lastname"  => $request->lastname,
                "email"     => $request->email,
                "username"  => $request->username,
                "password"  => bcrypt($request->password),
                "role"      => $request->role,
            ]);

            $user->sendEmailVerificationNotification();

            return redirect()->route("Login")->with('status', 'Account created! Please check your email to verify your account.');

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function LoginUser(Request $request)
    {
        // 1. Validate inputs and format rules
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please provide a valid email format.',
            'password.required' => 'Please enter your password.',
        ]);

        // 2. Extract "Remember Me" checkbox status
        $remember = $request->boolean('remember');

        // 3. Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session ID to prevent Session Fixation attacks
            $request->session()->regenerate();

            return redirect()->route('handle');
        }

        // 4. Failed login: Send back to login form with error feedback & preserve typed email
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function handle()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Safety guard if unauthenticated user hits this route directly
        if (!$user) {
            return redirect()->route('Login');
        }

        // Check for required password reset (e.g. first-time trainer login)
        if ($user->must_reset_password) {
            return redirect()->route('first.reset');
        }

        // Role-based routing
        return match ($user->role) {
            'student' => redirect()->route('homepage'),
            'trainer' => redirect()->route('teacher'),
            'admin'   => redirect()->route('admin1'),
            default   => $this->logoutInvalidUser(),
        };
    }

    /**
     * Helper to log out users with invalid/missing roles safely.
     */
    private function logoutInvalidUser()
    {
        Auth::logout();
        return redirect()->route('Login')->withErrors([
            'email' => 'Your account is not assigned to a valid user role.',
        ]);
    }

        public function homepage()
        {
            $userId = Auth::id();
        
            // ── Enrollments ───────────────────────────────────────────────────────────
            $enrollments = \App\Models\Enrollment_tbl::with('course')
                            ->where('user_id', $userId)
                            ->get();
        
            $selectedId = request('course_id');
            $enrollment = $selectedId
                ? $enrollments->firstWhere('course_id', $selectedId)
                : $enrollments->where('status', 'active')->first() ?? $enrollments->first();
        
            // ── Deadlines ─────────────────────────────────────────────────────────────
            $courseIds = $enrollments->pluck('course_id');
            $upcomingDeadlines = \App\Models\Deadline_tbl::whereIn('course_id', $courseIds)
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->addDays(30))
                ->count();
        
            // ── Announcements ─────────────────────────────────────────────────────────
            $announcements = \App\Models\Announcement::active()->latest()->take(5)->get();
        
            // ── Modules / Quizzes / Results ───────────────────────────────────────────
            if ($enrollment) {
                $modules = \App\Models\Module::where('course_id', $enrollment->course_id)
                            ->active()
                            ->ordered()
                            ->get();

                $unitNumbers = $modules->pluck('unit_number')->unique()->sort()->values();

                $completedModuleIds = \App\Models\ModuleCompletion::where('user_id', $userId)
                                        ->whereIn('module_id', $modules->pluck('id'))
                                        ->pluck('module_id')
                                        ->toArray();

                $quizzes = \App\Models\Quiz::where('course_id', $enrollment->course_id)->get();

                $postTest = $quizzes->firstWhere('quiz_type', 'post_test');

                $quizResults = \App\Models\QuizResult::where('user_id', $userId)
                                ->whereIn('quiz_id', $quizzes->pluck('id'))
                                ->get();
            } else {
                $modules             = collect();
                $unitNumbers         = collect();
                $completedModuleIds  = [];
                $quizzes             = collect();
                $quizResults         = collect();
            }
        
            // ── Personal Analytics ────────────────────────────────────────────────────
        
            // 1. Stat cards
            $totalEnrolled  = $enrollments->count();
            $totalCompleted = $enrollments->where('status', 'completed')->count();
            $avgProgress    = $totalEnrolled > 0 ? round($enrollments->avg('progress')) : 0;
        
            // Total quizzes taken across all enrolled courses
            $allCourseIds   = $enrollments->pluck('course_id');
            $allQuizIds     = \App\Models\Quiz::whereIn('course_id', $allCourseIds)->pluck('id');
            $quizzesTaken   = \App\Models\QuizResult::where('user_id', $userId)
                                ->whereIn('quiz_id', $allQuizIds)
                                ->count();
            $quizzesPassed  = \App\Models\QuizResult::where('user_id', $userId)
                                ->whereIn('quiz_id', $allQuizIds)
                                ->where('status', 'passed')
                                ->count();
        
            // 2. Weekly progress — real daily progress percentages this week (Mon–Sun)
            $dailyProgressThisWeek = \App\Models\DailyProgress::where('user_id', $userId)
                ->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
                ->pluck('progress_percent', 'date');

            $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $weeklyData = [];
            foreach (range(0, 6) as $i) {
                $date = now()->startOfWeek()->addDays($i)->toDateString();
                $weeklyData[] = $dailyProgressThisWeek[$date] ?? 0;
            }
        
            // 3. Donut chart — completion breakdown across all enrollments
            $notStarted  = $enrollments->where('progress', 0)->count();
            $inProgress  = $enrollments->where('progress', '>', 0)->where('status', '!=', 'completed')->count();
            $donutData   = [$totalCompleted, $inProgress, $notStarted];
        
            // 4. Recent activity — last 5 quiz results with quiz title
            $recentActivity = \App\Models\QuizResult::with('quiz')
                                ->where('user_id', $userId)
                                ->latest()
                                ->take(5)
                                ->get();
        
            return view('student.homepage', compact(
                'enrollments',
                'enrollment',
                'upcomingDeadlines',
                'announcements',
                'modules',
                'quizzes',
                'quizResults',
                // analytics
                'totalEnrolled',
                'totalCompleted',
                'avgProgress',
                'quizzesTaken',
                'quizzesPassed',
                'weekDays',
                'weeklyData',
                'donutData',
                'recentActivity'
            ));
        }

public function landingPage()
{
    return view('admin.landingpage', [
        'hero' => LandingSection::where('section_key', 'hero')->first(),
        'about' => LandingSection::where('section_key', 'about')->first(),
        'announcement' => LandingSection::where('section_key', 'announcement')->first(),
        'slides' => CarouselSlide::orderBy('sort_order')->get(),
    ]);
}

public function updateLandingSection(Request $request, $key)
{
    $section = LandingSection::where('section_key', $key)->firstOrFail();

    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'body'  => 'nullable|string',
        'image' => 'nullable|image|max:4096',
    ]);

    $data = [
        'title' => $validated['title'] ?? $section->title,
        'body'  => $validated['body'] ?? $section->body,
    ];

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $data['image_path'] = 'images/' . $filename;
    }

    $section->update($data);

    return response()->json([
        'success' => true,
        'message' => ucfirst($key) . ' section updated successfully!',
        'section' => $section,
    ]);
}

public function storeCarouselSlide(Request $request)
{
    $validated = $request->validate([
        'title'   => 'nullable|string|max:255',
        'caption' => 'nullable|string|max:255',
        'image'   => 'required|image|max:4096',
    ]);

    $file = $request->file('image');
    $filename = 'slide_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('images'), $filename);

    $maxOrder = CarouselSlide::max('sort_order') ?? 0;

    $slide = CarouselSlide::create([
        'title'      => $validated['title'] ?? null,
        'caption'    => $validated['caption'] ?? null,
        'image_path' => 'images/' . $filename,
        'sort_order' => $maxOrder + 1,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Slide added successfully!',
        'slide'   => $slide,
    ]);
}

public function updateCarouselSlide(Request $request, $id)
{
    $slide = CarouselSlide::findOrFail($id);

    $validated = $request->validate([
        'title'   => 'nullable|string|max:255',
        'caption' => 'nullable|string|max:255',
        'image'   => 'nullable|image|max:4096',
    ]);

    $data = [
        'title'   => $validated['title'] ?? $slide->title,
        'caption' => $validated['caption'] ?? $slide->caption,
    ];

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = 'slide_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $data['image_path'] = 'images/' . $filename;
    }

    $slide->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Slide updated successfully!',
        'slide'   => $slide,
    ]);
}

public function destroyCarouselSlide($id)
{
    $slide = CarouselSlide::find($id);

    if (!$slide) {
        return response()->json([
            'success' => false,
            'message' => 'Slide not found.',
        ], 404);
    }

    $slide->delete();

    return response()->json([
        'success' => true,
        'message' => 'Slide deleted successfully!',
    ]);
}

public function admin1(Request $request)
{
    $currentView = $request->input('view', 'overview');

    // Eager-load relationships & counts, then paginate 9 items per page
    $courses = \App\Models\Course_tbl::with(['facility', 'trainer'])
        ->withCount(['enrollments', 'modules', 'quizzes'])
        ->paginate(9);

    // All courses list (used for multi-select dropdowns in modals)
    $allCourses = \App\Models\Course_tbl::all(); 

    $trainers      = \App\Models\User_tbl::where('role', 'trainer')->get();
    $trainees      = \App\Models\User_tbl::where('role', 'student')->paginate(10, ['*'], 'trainee_page');
    $trainersList  = \App\Models\User_tbl::where('role', 'trainer')->paginate(10, ['*'], 'trainer_page');
    $announcements = \App\Models\Announcement::latest()->paginate(10, ['*'], 'announcement_page');
    $registrations = \App\Models\Registration::latest()->paginate(10, ['*'], 'reg_page');
    $facilities    = \App\Models\Facility::with('courses')->get();

    // ── 1. FETCH CERTIFICATES FOR TABLE & STATS ──────────────────────────────
    $certificates  = \App\Models\Certificate::with(['user', 'course'])->latest()->get();

    // ── 2. FETCH ENROLLED TRAINEES FOR THE ISSUE MODAL DROPDOWN ─────────────
       $eligibleTrainees = \DB::table('enrollment_tbls')
        ->join('user_tbls', 'user_tbls.id', '=', 'enrollment_tbls.user_id')
        ->join('course_tbls', 'course_tbls.id', '=', 'enrollment_tbls.course_id')
        ->where('enrollment_tbls.status', 'completed')
        ->select(
            'user_tbls.id',
            'user_tbls.firstname',
            'user_tbls.lastname',
            'course_tbls.id as course_id',
            'course_tbls.title as course_title'
        )
        ->orderBy('user_tbls.lastname', 'asc')
        ->get();

    // --- CHART DATA AGGREGATIONS ---

    // 1. Courses grouped by Sector
    $sectorData = \App\Models\Course_tbl::select('sector', \DB::raw('COUNT(*) as total'))
        ->whereNotNull('sector')
        ->groupBy('sector')
        ->pluck('total', 'sector')
        ->toArray();

    $sectorLabels = array_keys($sectorData);
    $sectorCounts = array_values($sectorData);

    // 2. Capacity: Slots per Course Title
    $slotData = \App\Models\Course_tbl::pluck('slots', 'title')->toArray();

    $courseTitles = array_keys($slotData);
    $courseSlots  = array_values($slotData);

    // 3. Trainees Enrolled Per Course (For Overview Bar Graph)
    $coursesWithTrainees = \App\Models\Course_tbl::withCount('enrollments')->get();
    $traineeCourseLabels = $coursesWithTrainees->pluck('title')->toArray();
    $traineeCourseCounts = $coursesWithTrainees->pluck('enrollments_count')->toArray();

    // 4. Monthly Enrollment Trends (For Overview Line Chart - Last 4 Months)
    $months = collect(range(3, 0))->map(function ($i) {
        return now()->subMonths($i)->format('M');
    })->toArray();

    $monthNumbers = collect(range(3, 0))->map(function ($i) {
        return (int) now()->subMonths($i)->format('m');
    })->toArray();

    $colors = ['#c19a6b', '#6b9e7c', '#f4d03f', '#004d26', '#854f0b'];
    $topCourses = \App\Models\Course_tbl::take(5)->get();
    $overviewCourseDatasets = [];

    foreach ($topCourses as $index => $course) {
        $monthlyCounts = [];
        foreach ($monthNumbers as $m) {
            $count = \DB::table('enrollment_tbls')
                ->where('course_id', $course->id)
                ->whereMonth('created_at', $m)
                ->whereYear('created_at', date('Y'))
                ->count();
            $monthlyCounts[] = $count;
        }

        $overviewCourseDatasets[] = [
            'label'       => $course->title,
            'data'        => $monthlyCounts,
            'borderColor' => $colors[$index % count($colors)],
            'tension'     => 0.3,
            'fill'        => false,
        ];
    }

    return view('admin.admin1', compact(
        'courses',
        'allCourses',
        'trainers',
        'trainees',
        'trainersList',
        'announcements',
        'registrations',
        'facilities',
        'certificates',     // <-- Added
        'eligibleTrainees', // <-- Added
        'sectorLabels',
        'sectorCounts',
        'courseTitles',
        'courseSlots',
        'currentView',
        'traineeCourseLabels',
        'traineeCourseCounts',
        'months',
        'overviewCourseDatasets'
    ));
}



public function updateUser(Request $request)
{
    // 1. Validation (Allows 'Pending', 'Active', 'Inactive')
    $request->validate([
        'id'        => 'nullable|integer',
        'email'     => 'required_without:id|nullable|email',
        'name'      => 'nullable|string|max:255',
        'status'    => 'nullable|string|max:50',
        'contact'   => 'nullable|string|max:255',
        'id_number' => 'nullable|string|max:255',
        'role'      => 'nullable|string|max:255',
        'remarks'   => 'nullable|string',
    ]);

    // 2. Locate user record
    $user = User_tbl::find($request->id) 
         ?? User_tbl::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'success' => false, 
            'message' => 'User record not found.'
        ], 404);
    }

    // 3. Name Parsing (Supports multi-word first names cleanly)
    if ($request->filled('name')) {
        $nameParts = preg_split('/\s+/', trim($request->name));
        if (count($nameParts) > 1) {
            $user->lastname  = array_pop($nameParts);
            $user->firstname = implode(' ', $nameParts);
        } else {
            $user->firstname = $nameParts[0] ?? '';
            $user->lastname  = ''; 
        }
    }

    // 4. Role-Based Status & Safe Field Updates
    $targetRole = strtolower($request->role ?? $user->role ?? 'student');
    $isTrainee  = in_array($targetRole, ['student', 'trainee', 'trainees']);

    if ($request->has('status')) {
        // Fallback to 'Pending' for Trainees, 'Active' for Trainers/Admins if empty
        $user->status = $request->status ?: ($isTrainee ? 'Pending' : 'Active');
    }

    if ($request->has('remarks'))   $user->remarks   = $request->remarks ?: null;
    if ($request->has('contact'))   $user->contact   = $request->contact ?: null;
    if ($request->has('id_number')) $user->id_number = $request->id_number ?: null;
    if ($request->filled('role'))   $user->role      = strtolower($request->role);

    $user->save();

    // 5. Fetch assigned course title if user is a trainer
    $courseTitle = 'No course assigned';
    if (strtolower($user->role) === 'trainer' && class_exists(Course_tbl::class)) {
        $course = Course_tbl::where('trainer_id', $user->id)->first();
        if ($course) {
            $courseTitle = $course->title;
        }
    }

    return response()->json([
        'success'      => true,
        'message'      => 'User profile updated successfully!',
        'user'         => [
            'id'           => $user->id,
            'firstname'    => $user->firstname,
            'lastname'     => $user->lastname,
            'fullname'     => trim($user->firstname . ' ' . $user->lastname),
            'email'        => $user->email,
            'status'       => $user->status,
            'contact'      => $user->contact,
            'id_number'    => $user->id_number,
            'remarks'      => $user->remarks,
            'role'         => $user->role,
            'course_title' => $courseTitle,
        ]
    ]);
}

public function deleteUser(Request $request)
{
    $request->validate([
        'id' => 'required'
    ]);

    $user = User_tbl::find($request->id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found in database.'
        ], 404);
    }

    // Unassign trainer from courses before deleting to avoid foreign key errors
    if (class_exists(Course_tbl::class)) {
        Course_tbl::where('trainer_id', $user->id)->update(['trainer_id' => null]);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully.',
        'deleted_id' => $user->id
    ]);
}

        public function saveFacility(Request $request)
{
    $request->validate([
        'id'           => 'nullable|integer',
        'name'         => 'required|string|max:255',
        'address'      => 'required|string|max:255',
        'course_ids'   => 'nullable|array',
        'course_ids.*' => 'nullable|integer',
    ]);

    try {
        $facility = \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $facility = null;
            if (!empty($request->id) && $request->id != 0) {
                $facility = \App\Models\Facility::find($request->id);
            }

            if ($facility) {
                $facility->update([
                    'name'    => $request->name,
                    'address' => $request->address,
                ]);
            } else {
                $facility = \App\Models\Facility::create([
                    'name'    => $request->name,
                    'address' => $request->address,
                ]);
            }

            \App\Models\Course_tbl::where('facility_id', $facility->id)
                ->update(['facility_id' => null]);

            if (!empty($request->course_ids)) {
                \App\Models\Course_tbl::whereIn('id', $request->course_ids)
                    ->update(['facility_id' => $facility->id]);
            }

            return $facility;
        });

        return response()->json([
            'success'  => true,
            'message'  => 'Facility details saved successfully!',
            'facility' => $facility
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error saving facility: ' . $e->getMessage()
        ], 500);
    }
}



public function deleteFacility(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:facilities,id',
    ]);

    try {
        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // 1. Unassign linked courses so foreign key constraints don't break
            \App\Models\Course_tbl::where('facility_id', $request->id)
                ->update(['facility_id' => null]);

            // 2. Delete the facility record
            \App\Models\Facility::where('id', $request->id)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Facility deleted successfully!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting facility: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            // 1. Check if the ID corresponds to a Course
            $course = Course_tbl::find($id);

            if ($course) {
                // Delete linked records first to prevent foreign key errors
                DB::table('enrollment_tbls')->where('course_id', $id)->delete();
                
                $course->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Course deleted successfully!'
                ]);
            }

            // 2. Fallback: Check if the ID corresponds to a User
            $user = User_tbl::find($id);

            if ($user) {
                $user->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Record not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeCourse(Request $request)
{
    try {
        $request->validate([
            'course_code' => 'required|string',
            'training_id' => 'required|string|max:100',
            'title'       => 'required|string',
            'duration'    => 'required|integer',
            'slots'       => 'required|integer',
            'sector'      => 'nullable|string',
        ]);

        Course_tbl::create([
            'course_code' => $request->course_code,
            'training_id' => $request->training_id,
            'title'       => $request->title,
            'duration'    => $request->duration,
            'slots'       => $request->slots,
            'sector'      => $request->sector ?? 'General', // Prevents SQL 1364 error
            'description' => $request->description ?? null,
            'objectives'  => $request->objectives ?? null,
            'schedule'    => $request->schedule ?? null,
            'location'    => $request->location ?? null,
            'thumbnail'   => $request->thumbnail ?? null,
            'status'      => $request->status ?? 'active',
            'trainer_id'  => $request->trainer_id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create course: ' . $e->getMessage()
        ], 500);
    }
}


    public function updateCourse(Request $request, $id)
{
    $request->validate([
        'course_code' => 'required|string|max:50',
        'training_id'  => 'required|string|max:100',
        'title'       => 'required|string|max:255',
        'duration'    => 'required|string',
        'slots'       => 'required|integer',
        'status'      => 'required|string|in:active,inactive', // <-- Added status validation
    ]);

    $course = Course_tbl::findOrFail($id);
    $course->update([
        'course_code' => $request->course_code,
        'training_id' => $request->training_id,
        'title'       => $request->title,
        'duration'    => $request->duration,
        'slots'       => $request->slots,
        'status'      => $request->status, // <-- Added status database update
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Course updated successfully!'
    ]);
}

public function assignTrainer(Request $request, $courseId)
{
    $request->validate([
        'trainer_id' => 'required|exists:user_tbls,id',
    ]);

    $course = Course_tbl::findOrFail($courseId);
    $course->update([
        'trainer_id' => $request->trainer_id,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Trainer assigned successfully!',
        'trainer' => User_tbl::find($request->trainer_id),
    ]);
}

public function removeTrainer($courseId)
{
    $course = Course_tbl::findOrFail($courseId);
    $course->update([
        'trainer_id' => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Trainer removed successfully!',
    ]);
}
    public function teacher()
    {
        // ── Existing stat cards ───────────────────────────────────────────────
        $trainer = Auth::user();
        $course = Course_tbl::where('trainer_id', $trainer->id)->first();

        // ── Attendance status for today ────────────────────────────────────────
        $today = now()->toDateString();
        $attendanceTakenToday = false;

        if ($course) {
            $activeEnrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
                ->where('status', 'active')
                ->pluck('id');

            $attendanceTakenToday = \App\Models\Attendance::whereIn('enrollment_id', $activeEnrollmentIds)
                ->where('date', $today)
                ->exists();
        }

        // ── Weekly average progress (real data) ──────────────────────────────
        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = now()->endOfWeek(\Carbon\Carbon::SUNDAY);

        $weeklyProgressData = [];

        if ($course) {
            $enrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
                ->where('status', 'active')
                ->pluck('id');

            for ($day = $startOfWeek->copy(); $day->lte($endOfWeek); $day->addDay()) {
                $dateStr = $day->toDateString();

                if ($day->gt(now())) {
                    $weeklyProgressData[] = null;
                    continue;
                }

                $avg = \App\Models\DailyProgress::whereIn('enrollment_id', $enrollmentIds)
                    ->where('date', $dateStr)
                    ->avg('progress_percent');

                $weeklyProgressData[] = $avg !== null ? round($avg) : 0;
            }
        } else {
            $weeklyProgressData = array_fill(0, 7, null);
        }

        $totalTrainees = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->count()
        : 0;

        $monthlyEnrollment = $course
            ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
                ->whereMonth('enrolled_at', now()->month)
                ->whereYear('enrolled_at', now()->year)
                ->count()
            : 0;

        $totalInCourse = $course ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count() : 0;
        $completedInCourse = $course ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->where('status', 'completed')->count() : 0;

        $completionRate = $totalInCourse > 0
            ? round($completedInCourse / $totalInCourse * 100) . '%'
            : '0%';

        $urgentAssessments = $course
            ? \App\Models\Deadline_tbl::where('course_id', $course->id)
                ->where('due_date', '<=', now()->addDays(3))
                ->where('due_date', '>=', now())
                ->count()
            : 0;

        // ── Low performing trainees ───────────────────────────────────────────
        $lowPerforming = $course
            ? \App\Models\Enrollment_tbl::with(['user', 'course'])
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->where('progress', '<', 50)
                ->orderBy('progress', 'asc')
                ->take(5)
                ->get()
            : collect();

        // ── Progress distribution for donut chart ────────────────────────
        $allEnrollments = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->get()
        : collect();

        $progressDistribution = [
            $allEnrollments->where('status', 'completed')->count(),
            $allEnrollments->where('status', '!=', 'completed')->where('progress', '>=', 50)->count(),
            $allEnrollments->where('status', '!=', 'completed')->where('progress', '>', 0)->where('progress', '<', 50)->count(),
            $allEnrollments->where('progress', 0)->where('status', '!=', 'completed')->count(),
        ];

        return view("trainer.teacher", compact(
            'totalTrainees',
            'monthlyEnrollment',
            'completionRate',
            'urgentAssessments',
            'lowPerforming',
            'progressDistribution',
            'attendanceTakenToday',
            'weeklyProgressData'
        ));
    }


    //forgotpassword

    public function ForgotPassword(){
         return view("student.forgotpassword");
    }

    //Email

    public function SendResetLink(Request $request)
{
    $request->validate(['email' => 'required|email']);

    // Check if email exists in user_tbls
    $user = User_tbl::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'No account found with that email address.');
    }

    // Generate token
    $token = Str::random(64);

    // Save to password_reset_tokens table
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'token'      => $token,
            'created_at' => now(),
        ]
    );

    // Build reset link
    $resetLink = route('ResetPasswordPage', ['token' => $token]) . '?email=' . urlencode($request->email);

    // Send email
    Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

    return back()->with('status', 'Reset link sent! Please check your email.');
}

public function ResetPasswordPage(Request $request, $token)
{
    return view('student.reset_password', [
        'token' => $token,
        'email' => $request->email,
    ]);
}

public function ResetPassword(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'token'    => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    // Check token
    $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

    if (!$record) {
        return back()->with('error', 'Invalid or expired reset link.');
    }

    // Update password
    User_tbl::where('email', $request->email)->update([
        'password' => bcrypt($request->password),
    ]);

    // Delete token
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return redirect()->route('Login')->with('status', 'Password reset successfully! Please login.');


    }

        public function adminlogin()
    {

        return view("admin.adminlogin");
    }
    
            public function trainees()
    {

        return view("admin.trainees");
    }

    public function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('Login');
    }

    //bocita

    public function learner(){
    $enrollments = \App\Models\Enrollment_tbl::with(['user', 'course'])->get();

    $totalRegistered   = \App\Models\User_tbl::where('role', 'student')->count();
    $currentlyEnrolled = \App\Models\Enrollment_tbl::where('status', 'active')->count();
    $graduates         = \App\Models\Enrollment_tbl::where('status', 'completed')->count();
    $urgentAssessments = 0; // we'll update this later when we build assessments

    return view("trainer.learner", compact(
        'enrollments',
        'totalRegistered',
        'currentlyEnrolled',
        'graduates',
        'urgentAssessments'
    ));
    }

    public function courses()
    {
        $trainer = Auth::user();
        $course = Course_tbl::where('trainer_id', $trainer->id)->first();
        $totalStudents = $course
            ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count()
            : 0;

        return view('trainer.courses', compact('course', 'totalStudents'));
    }

    public function assessment()
    {
        $trainer = Auth::user();
        $course  = Course_tbl::where('trainer_id', $trainer->id)->first();

        if (!$course) {
            return view('trainer.assessment', [
                'course'       => null,
                'results'      => collect(),
                'totalTaken'   => 0,
                'avgScore'     => 0,
                'passingRate'  => 0,
                'topPerformers'=> collect(),
                'quizzes'      => collect(),
            ]);
        }

        // Kunin lahat ng quiz results ng course
        $results = \App\Models\QuizResult::with(['user', 'quiz'])
            ->whereHas('quiz', fn($q) => $q->where('course_id', $course->id))
            ->orderByDesc('created_at')
            ->get();

        // Stats
        $totalTaken  = $results->count();
        $avgScore    = $totalTaken > 0 ? round($results->avg('percentage')) : 0;
        $passed      = $results->where('status', 'passed')->count();
        $passingRate = $totalTaken > 0 ? round(($passed / $totalTaken) * 100) : 0;

        // Top performers — pinaka-mataas na average per student
        $topPerformers = \App\Models\QuizResult::with('user')
            ->whereHas('quiz', fn($q) => $q->where('course_id', $course->id))
            ->select('user_id', \DB::raw('ROUND(AVG(percentage)) as avg_score'), \DB::raw('COUNT(*) as total_taken'))
            ->groupBy('user_id')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        // Quizzes ng course
        $quizzes = \App\Models\Quiz::where('course_id', $course->id)->get();

        return view('trainer.assessment', compact(
            'course',
            'results',
            'totalTaken',
            'avgScore',
            'passingRate',
            'topPerformers',
            'quizzes'
        ));
    }

      public function certificates()
    {

        return view("trainer.certificates");
    }

     public function reports()
    {

        return view("trainer.reports");
    }

       public function settings()
    {

        return view("trainer.settings");
    }
        //ctudent leaner
    public function allCourses(){
        $courses = \App\Models\Course_tbl::where('status', 'active')->get();

        $activeEnrollmentCount = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
                                    ->where('status', 'active')
                                    ->count();

        $enrolledCourseIds = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
                                    ->pluck('course_id')
                                    ->toArray();

        $atLimit = $activeEnrollmentCount >= 2;

        return view("student.all_courses", compact('courses', 'atLimit', 'enrolledCourseIds'));
    }

    public function courseDetail($id){
        $course = \App\Models\Course_tbl::findOrFail($id);

        $activeEnrollmentCount = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
                                    ->where('status', 'active')
                                    ->count();

        $enrolled = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
                        ->where('course_id', $id)
                        ->first();

        $atLimit = $activeEnrollmentCount >= 2 && !$enrolled;

        return view("student.course_detail", compact('course', 'atLimit'));
    }

    public function enroll(Request $request, $id)
{
    $course = \App\Models\Course_tbl::findOrFail($id);

    // Check if student already has 2 active enrollments
    $activeEnrollments = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
        ->where('status', 'active')
        ->count();

    if ($activeEnrollments >= 2) {
        return back()->with(
            'error',
            'You can only be enrolled in 2 courses at a time. Please complete or drop a course first.'
        );
    }


    // Check if the student is already enrolled in this course
    $existing = \App\Models\Enrollment_tbl::where('user_id', Auth::id())
        ->where('course_id', $id)
        ->first();

    if ($existing) {
        return redirect()
            ->route('homepage')
            ->with(
                'error',
                'You are already enrolled in this course!'
            );
    }

    // Check available slots
    if ($course->available_slots <= 0) {
        return back()->with(
            'error',
            'Sorry, no more slots are available for this course!'
        );
    }

    // Create enrollment
    \App\Models\Enrollment_tbl::create([
        'user_id'     => Auth::id(),
        'course_id'   => $id,
        'status'      => 'active',
        'progress'    => 0,
        'enrolled_at' => now(),
    ]);

    // Decrease available slots by 1
    $course->decrement('available_slots');

    // Successfully enrolled → go directly to homepage
    return redirect()
        ->route('homepage')
        ->with(
            'success',
            'You have successfully enrolled in ' . $course->title . '!'
        );
}

    public function studentAnnouncements()
    {
        $announcements = \App\Models\Announcement::where('is_active', 1)
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->whereIn('audience', ['general', 'student'])
            ->latest()
            ->get();

        return view('student.announcements', compact('announcements'));
    }

//contact
public function contact(){
    return view("student.contact");
}

    public function sendContact(Request $request){
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        // For now just redirect with success message
        // Later we can add email sending feature
        return back()->with('success', 'Your message has been sent! We will contact you within 24 hours.');
    }

//landingpage contact
        public function landingContact(){
        return view("landing_contact");
    }

    public function landingContactSend(Request $request){
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        return back()->with('success', 'Your message has been sent! We will contact you within 24 hours.');
    }

    public function landingAbout(){
    $totalStudents = \App\Models\User_tbl::where('role', 'student')->count();
    $totalCourses  = \App\Models\Course_tbl::where('status', 'active')->count();
    $totalTrainers = \App\Models\User_tbl::where('role', 'trainer')->count();

    return view("landing_about", compact(
        'totalStudents',
        'totalCourses',
        'totalTrainers'
    ));
    }
    //about
    public function index(){
        $courses = \App\Models\Course_tbl::where('status', 'active')->get();
        $totalStudents = \App\Models\User_tbl::where('role', 'student')->count();
        $totalCourses  = \App\Models\Course_tbl::where('status', 'active')->count();
        $totalTrainers = \App\Models\User_tbl::where('role', 'trainer')->count();

        $heroSection         = LandingSection::where('section_key', 'hero')->first();
        $aboutSection        = LandingSection::where('section_key', 'about')->first();
        $announcementSection = LandingSection::where('section_key', 'announcement')->first();
        $carouselSlides      = CarouselSlide::orderBy('sort_order')->get();

        return view("index", compact(
            'courses',
            'totalStudents',
            'totalCourses',
            'totalTrainers',
            'heroSection',
            'aboutSection',
            'announcementSection',
            'carouselSlides'
            ));
    }

    public function landingCourseDetail($id){
        $course = \App\Models\Course_tbl::findOrFail($id);
        return view("landing_course_detail", compact('course'));
    }

    public function about(){
    $totalStudents = \App\Models\User_tbl::where('role', 'student')->count();
    $totalCourses  = \App\Models\Course_tbl::where('status', 'active')->count();
    $totalTrainers = \App\Models\User_tbl::where('role', 'trainer')->count();

    return view("student.about", compact(
        'totalStudents',
        'totalCourses',
        'totalTrainers'
    ));
    }

    //dashboard
public function dashboard()
{
    $userId = Auth::id();

    // My enrollments with course info
    $enrollments = \App\Models\Enrollment_tbl::with('course')
                    ->where('user_id', $userId)
                    ->get();

    // Stats
    $totalEnrolled   = $enrollments->count();
    $totalCompleted  = $enrollments->where('status', 'completed')->count();
    $avgProgress     = $totalEnrolled > 0
                        ? round($enrollments->avg('progress'))
                        : 0;

    // Upcoming deadlines (next 30 days)
    $deadlines = \App\Models\Deadline_tbl::where('due_date', '>=', now())
                    ->where('due_date', '<=', now()->addDays(30))
                    ->orderBy('due_date', 'asc')
                    ->get();

    $upcomingDeadlines = $deadlines->count();

    return view('student.dashboard', compact(
        'enrollments',
        'totalEnrolled',
        'totalCompleted',
        'avgProgress',
        'upcomingDeadlines',
        'deadlines'
    ));
}

// ── COURSE CONTENT (Modules & Quizzes) ────────────────────────────────────

    public function getCourseContent($courseId)
{
    $modules = \App\Models\Module::where('course_id', $courseId)
                                 ->orderBy('order')
                                 ->get([
                                     'id', 
                                     'title', 
                                     'description', 
                                     'file_path', 
                                     'file_type', 
                                     'file_size', 
                                     'order', 
                                     'is_active'
                                 ]);

    $quizzes = \App\Models\Quiz::where('course_id', $courseId)
                               ->with('module')
                               ->get();

    return response()->json([
        'modules' => $modules,
        'quizzes' => $quizzes,
    ]);
}
    // ── MODULES ───────────────────────────────────────────────────────────────

    public function viewModuleFile($id, $filename = null)
{
    $module = \App\Models\Module::findOrFail($id);

    if (!$module->file_path || !Storage::disk('public')->exists($module->file_path)) {
        abort(404, 'File not found');
    }

    $fullPath = Storage::disk('public')->path($module->file_path);
    
    // Sanitize module title for HTTP header compatibility
    $safeTitle = Str::slug($module->title) ?: 'module';
    $mimeType = Storage::disk('public')->mimeType($module->file_path) ?? 'application/pdf';

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $safeTitle . '.pdf"'
    ]);
}

   public function storeModule(Request $request)
{
    $request->validate([
        'course_id'   => 'required|exists:course_tbls,id',
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'unit_number' => 'nullable|integer|min:1',
        'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
    ]);

    $order = \App\Models\Module::where('course_id', $request->course_id)->max('order') + 1;

    $filePath = null;
    $fileType = null;
    $fileSize = null;

    if ($request->hasFile('file')) {
        $file     = $request->file('file');
        $filePath = $file->store('modules', 'public');
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
    }

    $module = \App\Models\Module::create([
        'course_id'   => $request->course_id,
        'title'       => $request->title,
        'description' => $request->description,
        'order'       => $order,
        'unit_number' => $request->unit_number ?? 1,
        'is_active'   => true,
        'file_path'   => $filePath,
        'file_type'   => $fileType,
        'file_size'   => $fileSize,
    ]);

    return response()->json(['success' => true, 'module' => $module]);
}

    public function destroyModule($id)
{
    try {
        // Use find() instead of findOrFail() to avoid throwing ModelNotFoundException
        $module = Module::find($id);

        // If record is already deleted from DB, return success to let JS update the UI
        if (!$module) {
            // Clean up any stray quiz references just in case
            DB::table('quizzes')->where('module_id', $id)->update(['module_id' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Module was already removed.'
            ], 200);
        }

        $filePath = $module->file_path;

        DB::transaction(function () use ($module, $id) {
            try {
                DB::table('quizzes')->where('module_id', $id)->update(['module_id' => null]);
            } catch (\Exception $e) {
                DB::table('quizzes')->where('module_id', $id)->delete();
            }

            $module->delete();
        });

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return response()->json([
            'success' => true,
            'message' => 'Module deleted successfully!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete module: ' . $e->getMessage()
        ], 500);
    }
}
    // ── QUIZZES ───────────────────────────────────────────────────────────────

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'course_id'     => 'required|exists:course_tbls,id',
            'module_id'     => 'nullable|exists:modules,id',
            'title'         => 'required|string|max:255',
            'passing_score' => 'required|integer|min:1|max:100',
            'time_limit'    => 'required|integer|min:1',
        ]);

        $quiz = \App\Models\Quiz::create($request->only(
            'course_id', 'module_id', 'title', 'passing_score', 'time_limit'
        ));

        $quiz->load('module');

        return response()->json(['success' => true, 'quiz' => $quiz]);
    }
    

    
public function editQuiz($id)
{
    $quiz = \App\Models\Quiz::with([
        'questions',
        'module'
    ])->findOrFail($id);

    return response()->json([
        'success' => true,
        'quiz' => $quiz
    ]);
}

public function updateQuiz(Request $request, $id)
{
    $quiz = Quiz::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'course_id' => 'sometimes|required|exists:course_tbls,id',
        'module_id' => 'nullable|exists:modules,id',
        'passing_score' => 'required|integer|min:1|max:100',
        'time_limit' => 'required|integer|min:1',
        'instructions' => 'nullable|string',

        'questions' => 'required|array|min:1',
        'questions.*.text' => 'required|string',
        'questions.*.type' => 'required|in:multiple_choice,checkboxes,short_answer,paragraph',
        'questions.*.options' => 'nullable|array',
        'questions.*.options.*' => 'nullable|string',
        'questions.*.correctAnswers' => 'nullable|array',
        'questions.*.correctAnswers.*' => 'nullable',
        'questions.*.points' => 'nullable|integer|min:0',
        'questions.*.feedback' => 'nullable|string',
        'questions.*.required' => 'nullable|boolean',
    ]);

    DB::transaction(function () use ($validated, $quiz) {

        $quiz->title = $validated['title'];

        if (array_key_exists('course_id', $validated)) {
            $quiz->course_id = $validated['course_id'];
        }

        $quiz->module_id = $validated['module_id'] ?? null;
        $quiz->passing_score = $validated['passing_score'];
        $quiz->time_limit = $validated['time_limit'];

        if (\Illuminate\Support\Facades\Schema::hasColumn(
            $quiz->getTable(),
            'instructions'
        )) {
            $quiz->instructions = $validated['instructions'] ?? null;
        }

        $quiz->save();

        // Replace existing questions with the submitted questions.
        $quiz->questions()->delete();

        foreach ($validated['questions'] as $index => $data) {

            $options = array_values(array_filter(
                array_map(
                    fn ($value) => is_scalar($value) ? (string) $value : '',
                    $data['options'] ?? []
                ),
                fn ($value) => $value !== ''
            ));

            // Normalize correct answers to strings.
            $correctAnswers = array_values(array_filter(
                array_map(
                    fn ($value) => is_scalar($value) ? (string) $value : '',
                    $data['correctAnswers'] ?? []
                ),
                fn ($value) => $value !== ''
            ));

            $question = new QuizQuestion();

            $question->quiz_id = $quiz->id;
            $question->question = $data['text'];
            $question->question_type = $data['type'];
            $question->options = $options;
            $question->correct_answers = $correctAnswers;
            $question->points = $data['points'] ?? 1;
            $question->feedback = $data['feedback'] ?? null;
            $question->required = $data['required'] ?? false;
            $question->order = $index + 1;

            // Preserve compatibility with existing multiple-choice fields.
            $question->choice_a = $options[0] ?? null;
            $question->choice_b = $options[1] ?? null;
            $question->choice_c = $options[2] ?? null;
            $question->choice_d = $options[3] ?? null;
            $question->correct_answer = $correctAnswers[0] ?? null;

            $question->save();
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Quiz updated successfully.',
        'quiz' => $quiz->fresh(['questions', 'module']),
    ]);
}




    public function destroyQuiz($id)
    {
        $quiz = \App\Models\Quiz::findOrFail($id);
        $quiz->delete();
        return response()->json(['success' => true]);
    }

    // ── QUIZ QUESTIONS (Admin) ────────────────────────────────────────────────

public function getQuizQuestions($quizId)
{
    $questions = \App\Models\QuizQuestion::where('quiz_id', $quizId)
                    ->orderBy('order')
                    ->get();
    return response()->json(['success' => true, 'questions' => $questions]);
}

public function storeQuizQuestion(Request $request)
{
    $request->validate([
        'quiz_id'        => 'required|exists:quizzes,id',
        'question'       => 'required|string',
        'choice_a'       => 'required|string',
        'choice_b'       => 'required|string',
        'choice_c'       => 'required|string',
        'choice_d'       => 'required|string',
        'correct_answer' => 'required|in:a,b,c,d',
    ]);

    $order = \App\Models\QuizQuestion::where('quiz_id', $request->quiz_id)->max('order') + 1;

    $question = \App\Models\QuizQuestion::create([
        'quiz_id'        => $request->quiz_id,
        'question'       => $request->question,
        'choice_a'       => $request->choice_a,
        'choice_b'       => $request->choice_b,
        'choice_c'       => $request->choice_c,
        'choice_d'       => $request->choice_d,
        'correct_answer' => $request->correct_answer,
        'order'          => $order,
    ]);

    return response()->json(['success' => true, 'question' => $question]);
}

public function destroyQuizQuestion($id)
{
    $question = \App\Models\QuizQuestion::findOrFail($id);
    $question->delete();
    return response()->json(['success' => true]);
}

// ── QUIZ TAKING (Student) ─────────────────────────────────────────────────

public function getQuizForStudent($quizId)
{
    $userId = \Auth::id();

    // Check if already taken
    $existing = \App\Models\QuizResult::where('quiz_id', $quizId)
                    ->where('user_id', $userId)
                    ->first();

    $quiz = \App\Models\Quiz::with('questions')->findOrFail($quizId);

    return response()->json([
        'success'  => true,
        'quiz'     => $quiz,
        'taken'    => $existing ? true : false,
        'result'   => $existing,
    ]);
}

    public function submitQuiz(Request $request)
    {
    $request->validate([
        'quiz_id' => 'required|exists:quizzes,id',
        'answers' => 'required|array',
    ]);

    $userId = \Auth::id();
    $quiz   = \App\Models\Quiz::with('questions')->findOrFail($request->quiz_id);

    // Check if already taken
    $existing = \App\Models\QuizResult::where('quiz_id', $request->quiz_id)
                    ->where('user_id', $userId)
                    ->first();
    if ($existing) {
        return response()->json(['success' => false, 'message' => 'Already taken.']);
    }

    // Score
    $score = 0;
    $total = $quiz->questions->count();

    foreach ($quiz->questions as $q) {
        $answer = $request->answers[$q->id] ?? null;
        if ($answer && strtolower($answer) === $q->correct_answer) {
            $score++;
        }
    }

    $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
    $status     = $percentage >= $quiz->passing_score ? 'passed' : 'failed';

    $result = \App\Models\QuizResult::create([
        'quiz_id'     => $request->quiz_id,
        'user_id'     => $userId,
        'score'       => $score,
        'total_items' => $total,
        'percentage'  => $percentage,
        'status'      => $status,
    ]);

        // I-update ang enrollment progress
    $enrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('course_id', $quiz->course_id)
        ->first();

    if ($enrollment) {
        // Kunin lahat ng quizzes ng course
        $totalQuizzes = \App\Models\Quiz::where('course_id', $quiz->course_id)->count();
        
        // Kunin kung ilan na ang natapos ng student
        $completedQuizzes = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', \App\Models\Quiz::where('course_id', $quiz->course_id)->pluck('id'))
            ->count();

        // I-compute ang progress percentage
        $progress = $totalQuizzes > 0
            ? round(($completedQuizzes / $totalQuizzes) * 100)
            : 0;

        $enrollment->progress = $progress;


// Check if all modules are completed
$moduleIds = \App\Models\Module::where('course_id', $quiz->course_id)
    ->active()
    ->pluck('id');

$completedModules = \App\Models\ModuleCompletion::where('user_id', $userId)
    ->whereIn('module_id', $moduleIds)
    ->count();

$allModulesCompleted = $moduleIds->count() > 0
    && $completedModules >= $moduleIds->count();

// Check if Post-Test is passed
$postTest = \App\Models\Quiz::where('course_id', $quiz->course_id)
    ->where('quiz_type', 'post_test')
    ->first();

$postTestPassed = $postTest
    && \App\Models\QuizResult::where('user_id', $userId)
        ->where('quiz_id', $postTest->id)
        ->where('status', 'passed')
        ->exists();

// Check if Hands-On Test is passed
$handsOnPassed = \App\Models\HandsOnEvaluation::where('user_id', $userId)
    ->where('course_id', $quiz->course_id)
    ->where('result', 'passed')
    ->exists();

// Final completion requirement
if ($allModulesCompleted && $postTestPassed && $handsOnPassed) {
    $enrollment->status = 'completed';
    $enrollment->completed_at = now();
}

        $enrollment->save();
    }

    return response()->json([
        'success'    => true,
        'score'      => $score,
        'total'      => $total,
        'percentage' => $percentage,
        'status'     => $status,
        'passing'    => $quiz->passing_score,
        'progress'   => $progress ?? 0, 
    ]);
    }


public function trainerStudents()
{
    $trainer = Auth::user();

    // Kunin ang course na naka-assign sa trainer
    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    // Kung walang course, i-return agad ng empty
    if (!$course) {
        return view('trainer.students', [
            'course'   => null,
            'students' => collect(),
        ]);
    }

    // Kunin lahat ng students enrolled sa course ng trainer
    // via enrollment_tbls JOIN user_tbls
    $students = User_tbl::join('enrollment_tbls', 'user_tbls.id', '=', 'enrollment_tbls.user_id')
        ->where('enrollment_tbls.course_id', $course->id)
        ->where('user_tbls.role', 'student')   // i-adjust kung iba ang role name mo
        ->select(
            'user_tbls.id',
            'user_tbls.firstname',
            'user_tbls.lastname',
            'user_tbls.email',
            'enrollment_tbls.status',
            'enrollment_tbls.progress',
            'enrollment_tbls.enrolled_at',
        )
        ->orderBy('enrollment_tbls.enrolled_at', 'desc')
        ->get();

    $handsOnEvaluations = HandsOnEvaluation::where('course_id', $course->id)
    ->get()
    ->keyBy('user_id');

    return view('trainer.students', compact(
    'course',
    'students',
    'handsOnEvaluations'
    ));
    }

    public function attendance()
{
    $trainer = Auth::user();
    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    if (!$course) {
        return view('trainer.attendance', [
            'course'   => null,
            'students' => collect(),
            'today'    => now()->format('l, F j, Y'),
        ]);
    }
    $inSessionToday = app(\App\Services\CourseScheduleService::class)->isInSessionToday($course);

    $today = now()->toDateString();

    // Everyone actively enrolled in the trainer's course
    $students = User_tbl::join('enrollment_tbls', 'user_tbls.id', '=', 'enrollment_tbls.user_id')
        ->where('enrollment_tbls.course_id', $course->id)
        ->where('enrollment_tbls.status', 'active')
        ->select(
            'user_tbls.id as user_id',
            'user_tbls.firstname',
            'user_tbls.lastname',
            'enrollment_tbls.id as enrollment_id'
        )
        ->orderBy('user_tbls.lastname')
        ->get();

    // Today's attendance, if the trainer already submitted (or auto-fill ran)
    $existing = \App\Models\Attendance::where('date', $today)
        ->whereIn('enrollment_id', $students->pluck('enrollment_id'))
        ->pluck('status', 'enrollment_id');

    return view('trainer.attendance', [
        'course'   => $course,
        'students' => $students,
        'existing' => $existing,
        'today'    => now()->format('l, F j, Y'),
        'inSessionToday' => $inSessionToday,
    ]);
}

public function storeHandsOnEvaluation(Request $request)
{
    $trainer = Auth::user();

    $course = Course_tbl::where('trainer_id', $trainer->id)->firstOrFail();

    $request->validate([
        'user_id' => 'required|exists:user_tbls,id',
        'result'  => 'required|in:passed,failed',
        'remarks' => 'nullable|string',
    ]);

    $studentEnrolled = \App\Models\Enrollment_tbl::where('user_id', $request->user_id)
    ->where('course_id', $course->id)
    ->exists();

    if (!$studentEnrolled) {
        return back()->with('error', 'This student is not enrolled in your course.');
    }

HandsOnEvaluation::updateOrCreate(
    [
        'user_id'   => $request->user_id,
        'course_id' => $course->id,
    ],
    [
        'trainer_id'   => $trainer->id,
        'result'       => $request->result,
        'evaluated_at' => now(),
        'remarks'      => $request->remarks,
    ]
);

// Check if the student can now complete the course
if ($request->result === 'passed') {

    $enrollment = \App\Models\Enrollment_tbl::where('user_id', $request->user_id)
        ->where('course_id', $course->id)
        ->first();

    if ($enrollment) {

        // Check all active modules
        $moduleIds = \App\Models\Module::where('course_id', $course->id)
            ->active()
            ->pluck('id');

        $completedModules = \App\Models\ModuleCompletion::where('user_id', $request->user_id)
            ->whereIn('module_id', $moduleIds)
            ->count();

        $allModulesCompleted = $moduleIds->count() > 0
            && $completedModules >= $moduleIds->count();

        // Check Post-Test
        $postTest = \App\Models\Quiz::where('course_id', $course->id)
            ->where('quiz_type', 'post_test')
            ->first();

        $postTestPassed = $postTest
            && \App\Models\QuizResult::where('user_id', $request->user_id)
                ->where('quiz_id', $postTest->id)
                ->where('status', 'passed')
                ->exists();

        // Final completion check
        if ($allModulesCompleted && $postTestPassed) {
            $enrollment->status = 'completed';
            $enrollment->completed_at = now();
            $enrollment->progress = 100;
            $enrollment->save();
        }
    }
}

return back()->with('success', 'Hands-On evaluation saved successfully.');
}   

public function storeAttendance(Request $request)
{
    $trainer = Auth::user();
    $course = Course_tbl::where('trainer_id', $trainer->id)->firstOrFail();
    if (!app(\App\Services\CourseScheduleService::class)->isInSessionToday($course)) {
    return back()->with('error', 'This course is not scheduled to meet today.');
    }

    $today = now()->toDateString();
    $absentIds = collect($request->input('absent', []));

    $enrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
        ->where('status', 'active')
        ->pluck('id', 'user_id');

    foreach ($enrollmentIds as $userId => $enrollmentId) {
        $status = $absentIds->contains($enrollmentId) ? 'absent' : 'present';

        \App\Models\Attendance::updateOrCreate(
            ['enrollment_id' => $enrollmentId, 'date' => $today],
            [
                'user_id'     => $userId,
                'status'      => $status,
                'marked_by'   => $trainer->id,
                'auto_marked' => false,
            ]
        );
    }

    return redirect()->route('trainer.attendance')->with('success', 'Attendance submitted for ' . now()->format('F j, Y'));
}

    public function trainerSchedule()
    {
        $trainer = Auth::user();
        $course = Course_tbl::where('trainer_id', $trainer->id)->first();
        $totalStudents = $course
            ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count()
            : 0;

        $deadlines = $course
            ? $course->deadlines()->orderBy('due_date', 'asc')->get()
            : collect();

        return view('trainer.schedule', compact('course', 'totalStudents', 'deadlines'));
    }

    public function storeTrainer(Request $request)
{
    // 1. Auto-split "name" into firstname and lastname if passed from frontend
    if ($request->has('name') && (!$request->filled('firstname') || !$request->filled('lastname'))) {
        $nameParts = explode(' ', trim($request->name), 2);
        $request->merge([
            'firstname' => $nameParts[0] ?? '',
            'lastname'  => $nameParts[1] ?? '',
        ]);
    }

    // 2. Validation
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname'  => 'nullable|string|max:255',
        'email'     => 'required|email|unique:user_tbls,email',
        'password'  => 'required|string|min:6',
        'course_id' => 'nullable|exists:course_tbls,id',
        'contact'   => 'nullable|string|max:11',
        'id_number' => 'nullable|string|max:100',
        'remarks'   => 'nullable|string',
    ]);

    // 3. Generate clean username from email or name
    $cleanFirstName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->firstname));
    $cleanLastName  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $request->lastname));
    $baseUsername   = $cleanLastName ? "{$cleanFirstName}.{$cleanLastName}" : $cleanFirstName;

    $username = $baseUsername;
    if (\App\Models\User_tbl::where('username', $username)->exists()) {
        $username .= rand(10, 99);
    }

    // 4. Create Trainer Record
    $trainer = \App\Models\User_tbl::create([
        'firstname'           => $request->firstname,
        'lastname'            => $request->lastname ?: 'N/A',
        'email'               => $request->email,
        'username'            => $username,
        'password'            => bcrypt($request->password),
        'role'                => 'trainer',
        'status'              => 'Active',
        'contact'             => $request->contact ?? null,
        'id_number'           => $request->id_number ?? null,
        'remarks'             => $request->remarks ?? null,
        'must_reset_password' => true,
    ]);

    // 5. Assign to Course if selected
    if ($request->filled('course_id') && class_exists(\App\Models\Course_tbl::class)) {
        \App\Models\Course_tbl::where('id', $request->course_id)
            ->update(['trainer_id' => $trainer->id]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Trainer registered successfully!'
    ]);
}

    // ── FIRST TIME RESET PASSWORD ─────────────────────────────────────────────
    public function firstResetPage()
    {
        if (!Auth::check() || !Auth::user()->must_reset_password) {
            return redirect()->route("Login");
        }
        return view("student.first_reset");
    }

    public function firstResetSave(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min'       => 'Ang password ay dapat hindi bababa sa 6 na characters.',
            'password.confirmed' => 'Hindi magkatugma ang mga password. Subukan ulit.',
            'password.required'  => 'Kailangan ng password.',
        ]);

        $user = Auth::user();
        $user->password            = bcrypt($request->password);
        $user->must_reset_password = false;
        $user->save();

        return redirect()->route("handle");
    }

    // ── STUDENT PROFILE ───────────────────────────────────────────────────────

    public function studentProfile()
    {
        $user        = Auth::user();
        $enrollments = \App\Models\Enrollment_tbl::with('course')
                        ->where('user_id', $user->id)
                        ->get();

        $avgProgress    = $enrollments->count() > 0
                            ? round($enrollments->avg('progress'))
                            : 0;
        $completedCount = $enrollments->where('status', 'completed')->count();

        return view('student.profile', compact('user', 'enrollments', 'avgProgress', 'completedCount'));
    }

    public function studentProfileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'email'     => 'required|email|unique:user_tbls,email,' . $user->id,
            'username'  => 'required|string|max:100|unique:user_tbls,username,' . $user->id,
        ]);

        User_tbl::where('id', $user->id)->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'username'  => $request->username,
        ]);

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }

    public function studentProfilePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('student.profile')
                ->with('error', 'Current password is incorrect.');
        }

        User_tbl::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('student.profile')->with('success', 'Password updated successfully!');
    }

    // ── TRAINER PROFILE ───────────────────────────────────────────────────────

    public function trainerProfile()
    {
        $user   = Auth::user();
        $course = Course_tbl::where('trainer_id', $user->id)->first();

        $totalStudents = $course
            ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count()
            : 0;

        return view('trainer.profile', compact('user', 'course', 'totalStudents'));
    }

    public function trainerProfileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'email'     => 'required|email|unique:user_tbls,email,' . $user->id,
            'username'  => 'required|string|max:100|unique:user_tbls,username,' . $user->id,
        ]);

        User_tbl::where('id', $user->id)->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'username'  => $request->username,
        ]);

        return redirect()->route('trainer.profile')->with('success', 'Profile updated successfully!');
    }

    public function trainerCertificateUpload(Request $request)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        // Delete old certificate file if one exists, so we don't leave orphaned files
        if ($user->certificate_path && Storage::disk('public')->exists($user->certificate_path)) {
            Storage::disk('public')->delete($user->certificate_path);
        }

        $path = $request->file('certificate')->store('certificates', 'public');

        $user->certificate_path = $path;
        $user->save();

        return back()->with('success', 'Certificate uploaded successfully!');
    }

    public function trainerProfilePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('trainer.profile')
                ->with('error', 'Current password is incorrect.');
        }

        User_tbl::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('trainer.profile')->with('success', 'Password updated successfully!');
        }
// ── ANNOUNCEMENTS ─────────────────────────────────────────────────────────

public function storeAnnouncement(Request $request)
{
    $validated = $this->validateAnnouncement($request);

    \App\Models\Announcement::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Announcement created successfully!'
    ]);
}

public function updateAnnouncement(Request $request, $id)
{
    $validated = $this->validateAnnouncement($request);

    $announcement = \App\Models\Announcement::findOrFail($id);
    $announcement->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Announcement updated successfully!'
    ]);
}

public function destroyAnnouncement($id)
{
    $announcement = \App\Models\Announcement::findOrFail($id);
    $announcement->delete();

    return response()->json([
        'success' => true,
        'message' => 'Announcement deleted successfully!'
    ]);
}

public function toggleAnnouncement($id)
{
    $announcement = \App\Models\Announcement::findOrFail($id);
    $announcement->is_active = !$announcement->is_active;
    $announcement->save();

    return response()->json([
        'success'   => true,
        'is_active' => $announcement->is_active,
        'message'   => 'Announcement status updated successfully!'
    ]);
}

/**
 * Shared Helper — Sanitize input and validate request payload.
 */
private function validateAnnouncement(Request $request): array
{
    $request->merge([
        'audience'   => $request->audience ?: 'general',
        'publish_at' => $request->publish_at ?: null,
        'expires_at' => $request->expires_at ?: null,
        'is_active'  => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
    ]);

    return $request->validate([
        'title'      => 'required|string|max:100',
        'message'    => 'required|string|max:500',
        'type'       => 'required|in:reminder,notice,urgent',
        'audience'   => 'required|in:general,student,trainer',
        'is_active'  => 'required|boolean',
        'publish_at' => 'nullable|date',
        'expires_at' => 'nullable|date|after_or_equal:publish_at',
    ]);
}

    public function studentModules(Request $request)
    {
        $userId = Auth::id();
    
        // Lahat ng enrollments ng student
        $enrollments = \App\Models\Enrollment_tbl::with('course')
                        ->where('user_id', $userId)
                        ->get();
    
        // Piliin ang course — mula sa ?course_id= o default sa active/first
        $selectedId  = $request->get('course_id');
        $enrollment  = $selectedId
            ? $enrollments->firstWhere('course_id', $selectedId)
            : $enrollments->where('status', 'active')->first() ?? $enrollments->first();
    
            if ($enrollment) {
            $modules = \App\Models\Module::where('course_id', $enrollment->course_id)
                        ->active()
                        ->ordered()
                        ->get();

            $unitNumbers = $modules->pluck('unit_number')->unique()->sort()->values();

            $completedModuleIds = \App\Models\ModuleCompletion::where('user_id', $userId)
                                    ->whereIn('module_id', $modules->pluck('id'))
                                    ->pluck('module_id')
                                    ->toArray();

            $quizzes = \App\Models\Quiz::where('course_id', $enrollment->course_id)->get();

            $postTest = $quizzes->firstWhere('quiz_type', 'post_test');

            $quizResults = \App\Models\QuizResult::where('user_id', $userId)
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->get();

            $handsOnEvaluation = \App\Models\HandsOnEvaluation::where('user_id', $userId)
            ->where('course_id', $enrollment->course_id)
            ->first();

            $certificate = \App\Models\Certificate::where('user_id', $userId)
            ->where('course_id', $enrollment->course_id)
            ->first();

            } else {
                $modules             = collect();
                $unitNumbers         = collect();
                $completedModuleIds  = [];
                $quizzes             = collect();
                $quizResults         = collect();
                $handsOnEvaluation   = null;
                $certificate         = null;
            }
    
        return view('student.modules', compact(
            'enrollments',
            'enrollment',
            'modules',
            'unitNumbers',
            'completedModuleIds',
            'quizzes',
            'quizResults',
            'postTest',
            'handsOnEvaluation',
            'certificate'
        ));
    }
    
    public function updateCourseDescription(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string',
        ]);

        $trainer = Auth::user();
        $course = Course_tbl::where('id', $id)
                    ->where('trainer_id', $trainer->id)
                    ->firstOrFail();

        $course->update([
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Description updated successfully!'
        ]);
    }

        public function trainerCoursePreview($id)
{
    $trainer = Auth::user();

    // Get the course owned by the logged-in trainer
    $course = Course_tbl::where('id', $id)
        ->where('trainer_id', $trainer->id)
        ->firstOrFail();

    // Get the course modules
    $modules = \App\Models\Module::where('course_id', $course->id)
        ->active()
        ->ordered()
        ->get();

    // Get all onsite activities created for this course
    $onsiteActivities = \App\Models\OnsiteActivity::where(
        'course_id',
        $course->id
    )
        ->orderByDesc('activity_date')
        ->orderByDesc('id')
        ->get();

    // Pass all required data to the Blade view
    return view('trainer.course_preview', compact(
        'course',
        'modules',
        'onsiteActivities'
    ));
}

    public function updateCourseObjectives(Request $request, $id)
    {
        $request->validate([
            'objectives' => 'nullable|string',
        ]);

        $trainer = Auth::user();
        $course = Course_tbl::where('id', $id)
                    ->where('trainer_id', $trainer->id)
                    ->firstOrFail();

        $course->update([
            'objectives' => $request->objectives,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Objectives updated successfully!'
        ]);
    }

public function markModuleDone(Request $request, $id)
{
    $userId = Auth::id();

    \App\Models\ModuleCompletion::firstOrCreate([
        'user_id'   => $userId,
        'module_id' => $id,
    ]);

    // Get the module's course
    $module = \App\Models\Module::findOrFail($id);

    $courseId = $module->course_id;

    // Get the student's enrollment
    $enrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('course_id', $courseId)
        ->first();

    if ($enrollment) {

        // Check all active modules
        $moduleIds = \App\Models\Module::where('course_id', $courseId)
            ->active()
            ->pluck('id');

        $completedModules = \App\Models\ModuleCompletion::where('user_id', $userId)
            ->whereIn('module_id', $moduleIds)
            ->count();

        $allModulesCompleted = $moduleIds->count() > 0
            && $completedModules >= $moduleIds->count();

        // Check Post-Test
        $postTest = \App\Models\Quiz::where('course_id', $courseId)
            ->where('quiz_type', 'post_test')
            ->first();

        $postTestPassed = $postTest
            && \App\Models\QuizResult::where('user_id', $userId)
                ->where('quiz_id', $postTest->id)
                ->where('status', 'passed')
                ->exists();

        // Check Hands-On
        $handsOnPassed = \App\Models\HandsOnEvaluation::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('result', 'passed')
            ->exists();

        // Final completion requirement
        if ($allModulesCompleted && $postTestPassed && $handsOnPassed) {
            $enrollment->status = 'completed';
            $enrollment->completed_at = now();
            $enrollment->progress = 100;
            $enrollment->save();
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Module marked as done!'
    ]);
}

public function markAnnouncementRead($id)
{
    $announcement = \App\Models\Announcement::findOrFail($id);
    $user = \Auth::user();

    // Admin/Super Admin can read all announcements.
    // Keep the same audience rules used by the notification dropdowns.
    $canRead = match ($user->role) {
        'admin', 'super_admin' => true,
        'trainer' => in_array($announcement->audience, ['general', 'trainer'], true),
        'student' => in_array($announcement->audience, ['general', 'student'], true),
        default => false,
    };

    if (!$canRead) {
        abort(403);
    }

    \DB::table('announcement_reads')->insertOrIgnore([
        'announcement_id' => $announcement->id,
        'user_id' => $user->id,
        'read_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true]);
}

    
    
    }

    
    

