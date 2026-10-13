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

    // ── Enrollments ──────────────────────────────────────────────
    $enrollments = \App\Models\Enrollment_tbl::with('course')
        ->where('user_id', $userId)
        ->get();

    $selectedId = request('course_id');

    $enrollment = $selectedId
        ? $enrollments->firstWhere('course_id', $selectedId)
        : (
            $enrollments->where('status', 'active')->first()
            ?? $enrollments->first()
        );

    // ── Deadlines ────────────────────────────────────────────────
    $courseIds = $enrollments->pluck('course_id');

    $upcomingDeadlines = \App\Models\Deadline_tbl::whereIn(
        'course_id',
        $courseIds
    )
        ->where('due_date', '>=', now())
        ->where('due_date', '<=', now()->addDays(30))
        ->count();

    // ── Announcements ────────────────────────────────────────────
    $announcements = \App\Models\Announcement::active()
        ->latest()
        ->take(5)
        ->get();

    // ── Modules / Quizzes / Results ──────────────────────────────
    if ($enrollment) {
        $modules = \App\Models\Module::where(
            'course_id',
            $enrollment->course_id
        )
            ->active()
            ->ordered()
            ->get();

        $unitNumbers = $modules->pluck('unit_number')
            ->unique()
            ->sort()
            ->values();

        $completedModuleIds = \App\Models\ModuleCompletion::where(
            'user_id',
            $userId
        )
            ->whereIn('module_id', $modules->pluck('id'))
            ->pluck('module_id')
            ->toArray();

        $quizzes = \App\Models\Quiz::where(
            'course_id',
            $enrollment->course_id
        )->get();

        $postTest = $quizzes->firstWhere('quiz_type', 'post_test');

        $quizResults = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get();
    } else {
        $modules = collect();
        $unitNumbers = collect();
        $completedModuleIds = [];
        $quizzes = collect();
        $postTest = null;
        $quizResults = collect();
    }

    // ── Personal Analytics ───────────────────────────────────────
    $totalEnrolled = $enrollments->count();

    $totalCompleted = $enrollments
        ->where('status', 'completed')
        ->count();

    // ── Calculate Progress for Each Course ────────────────────────
    foreach ($enrollments as $courseEnrollment) {
        $courseQuizIds = \App\Models\Quiz::where(
            'course_id',
            $courseEnrollment->course_id
        )->pluck('id');

        $courseQuizResults = \App\Models\QuizResult::where(
            'user_id',
            $userId
        )
            ->whereIn('quiz_id', $courseQuizIds)
            ->get();

        $quizPercentages = $courseQuizResults
            ->map(function ($result) {
                if (is_numeric($result->percentage ?? null)) {
                    return max(
                        0,
                        min(100, (float) $result->percentage)
                    );
                }

                $score = $result->score ?? null;
                $totalItems = $result->total_items ?? null;

                if (
                    is_numeric($score) &&
                    is_numeric($totalItems) &&
                    (float) $totalItems > 0
                ) {
                    return max(
                        0,
                        min(
                            100,
                            ((float) $score / (float) $totalItems) * 100
                        )
                    );
                }

                return null;
            })
            ->filter(fn ($percentage) => $percentage !== null)
            ->values();

        $quizAverage = $quizPercentages->isNotEmpty()
            ? $quizPercentages->avg()
            : null;

        $onsiteGradesForCourse = DB::table('onsite_grades')
            ->join(
                'onsite_activities',
                'onsite_grades.onsite_activity_id',
                '=',
                'onsite_activities.id'
            )
            ->where('onsite_grades.user_id', $userId)
            ->where(
                'onsite_grades.enrollment_id',
                $courseEnrollment->id
            )
            ->where(
                'onsite_activities.course_id',
                $courseEnrollment->course_id
            )
            ->select(
                'onsite_grades.score',
                'onsite_activities.max_score'
            )
            ->get();

        $onsitePercentages = $onsiteGradesForCourse
            ->map(function ($grade) {
                if (
                    !is_numeric($grade->score) ||
                    !is_numeric($grade->max_score) ||
                    (float) $grade->max_score <= 0
                ) {
                    return null;
                }

                return max(
                    0,
                    min(
                        100,
                        ((float) $grade->score / (float) $grade->max_score) * 100
                    )
                );
            })
            ->filter(fn ($percentage) => $percentage !== null)
            ->values();

        $onsiteAverage = $onsitePercentages->isNotEmpty()
            ? $onsitePercentages->avg()
            : null;

        $availableAverages = collect([
            $quizAverage,
            $onsiteAverage,
        ])->filter(fn ($average) => $average !== null);

        $courseProgress = $availableAverages->isNotEmpty()
            ? (int) round($availableAverages->avg())
            : 0;

        $courseEnrollment->calculated_progress = max(
            0,
            min(100, $courseProgress)
        );

        $courseEnrollment->quiz_average = $quizAverage;
        $courseEnrollment->onsite_average = $onsiteAverage;
    }

    $avgProgress = $enrollments->isNotEmpty()
        ? (int) round($enrollments->avg('calculated_progress'))
        : 0;

    // ── Quiz Totals ───────────────────────────────────────────────
    $allCourseIds = $enrollments->pluck('course_id');

    $allQuizIds = \App\Models\Quiz::whereIn(
        'course_id',
        $allCourseIds
    )->pluck('id');

    $quizzesTaken = \App\Models\QuizResult::where('user_id', $userId)
        ->whereIn('quiz_id', $allQuizIds)
        ->count();

    $quizzesPassed = \App\Models\QuizResult::where('user_id', $userId)
        ->whereIn('quiz_id', $allQuizIds)
        ->where('status', 'passed')
        ->count();

    // ── Weekly Progress ───────────────────────────────────────────
    $dailyProgressThisWeek = \App\Models\DailyProgress::where(
        'user_id',
        $userId
    )
        ->whereBetween('date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString(),
        ])
        ->pluck('progress_percent', 'date');

    $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $weeklyData = [];

    for ($i = 0; $i < 7; $i++) {
        $date = now()->startOfWeek()->addDays($i)->toDateString();
        $weeklyData[] = $dailyProgressThisWeek[$date] ?? 0;
    }

    // ── Course Progress Distribution ──────────────────────────────
    $notStarted = $enrollments
        ->filter(fn ($item) => (int) $item->calculated_progress === 0)
        ->count();

    $inProgress = $enrollments
        ->filter(fn ($item) =>
            $item->calculated_progress > 0 &&
            $item->status !== 'completed'
        )
        ->count();

    $donutData = [
        $totalCompleted,
        $inProgress,
        $notStarted,
    ];

    // ── Recent Activity ───────────────────────────────────────────
    $recentActivity = \App\Models\QuizResult::with('quiz')
        ->where('user_id', $userId)
        ->latest()
        ->take(5)
        ->get();

    // ── Onsite Activity Grades ────────────────────────────────────
    $studentEnrollmentIds = $enrollments->pluck('id')->all();

    $onsiteGrades = DB::table('onsite_grades')
        ->join(
            'onsite_activities',
            'onsite_grades.onsite_activity_id',
            '=',
            'onsite_activities.id'
        )
        ->where('onsite_grades.user_id', $userId)
        ->whereIn(
            'onsite_grades.enrollment_id',
            $studentEnrollmentIds
        )
        ->select(
            'onsite_grades.id',
            'onsite_grades.user_id',
            'onsite_grades.enrollment_id',
            'onsite_grades.score',
            'onsite_grades.remarks',
            'onsite_grades.created_at',
            'onsite_grades.updated_at',
            'onsite_activities.title as activity_title',
            'onsite_activities.description as activity_description',
            'onsite_activities.activity_date',
            'onsite_activities.max_score',
            'onsite_activities.course_id'
        )
        ->orderByDesc('onsite_activities.activity_date')
        ->orderByDesc('onsite_grades.updated_at')
        ->get();

    // ── 1. Quiz Performance Trend ─────────────────────────────────
    $trendResults = $quizResults
        ->sortBy('created_at')
        ->values();

    $quizTrendLabels = $trendResults->map(function ($result) {
        return $result->created_at
            ? $result->created_at->format('M d')
            : 'Quiz';
    })->all();

    $quizTrendScores = $trendResults->map(function ($result) {
        if (is_numeric($result->percentage ?? null)) {
            return round(
                max(0, min(100, (float) $result->percentage)),
                2
            );
        }

        $score = $result->score ?? null;
        $totalItems = $result->total_items ?? null;

        if (
            is_numeric($score) &&
            is_numeric($totalItems) &&
            (float) $totalItems > 0
        ) {
            return round(
                max(
                    0,
                    min(
                        100,
                        ((float) $score / (float) $totalItems) * 100
                    )
                ),
                2
            );
        }

        return null;
    })->all();

    // ── 2. Learning Progress by Unit ──────────────────────────────
    $unitProgressLabels = [];
    $unitProgressValues = [];

    foreach (
        $modules->groupBy('unit_number')->sortKeys()
        as $unit => $unitModules
    ) {
        $unitProgressLabels[] = 'Unit ' . $unit;

        $completedCount = $unitModules
            ->filter(fn ($module) => in_array(
                $module->id,
                $completedModuleIds
            ))
            ->count();

        $unitProgressValues[] = $unitModules->isNotEmpty()
            ? round(
                ($completedCount / $unitModules->count()) * 100,
                2
            )
            : 0;
    }

    // ── 3. Quiz vs. Onsite Performance ────────────────────────────
    $validQuizScores = collect($quizTrendScores)
        ->filter(fn ($score) => $score !== null)
        ->values();

    // Compare onsite scores from the same selected course.
    $selectedOnsiteGrades = $onsiteGrades->filter(function ($grade) use ($enrollment) {
        return $enrollment &&
            (int) $grade->course_id === (int) $enrollment->course_id &&
            is_numeric($grade->score) &&
            is_numeric($grade->max_score) &&
            (float) $grade->max_score > 0;
    });

    $validOnsiteScores = $selectedOnsiteGrades
        ->map(fn ($grade) => max(
            0,
            min(
                100,
                ((float) $grade->score / (float) $grade->max_score) * 100
            )
        ))
        ->values();

    $comparisonLabels = ['Overall'];

    $quizAverages = [
        $validQuizScores->isNotEmpty()
            ? round($validQuizScores->avg(), 2)
            : null,
    ];

    $onsiteAverages = [
        $validOnsiteScores->isNotEmpty()
            ? round($validOnsiteScores->avg(), 2)
            : null,
    ];

    // ── 4. Assessment Results Summary ─────────────────────────────
    $assessmentResults = \App\Models\QuizResult::where('user_id', $userId)
        ->whereIn('quiz_id', $allQuizIds)
        ->get();

    $assessmentPassed = $assessmentResults
        ->filter(fn ($result) =>
            strtolower((string) $result->status) === 'passed'
        )
        ->count();

    $assessmentFailed = $assessmentResults
        ->filter(fn ($result) =>
            strtolower((string) $result->status) === 'failed'
        )
        ->count();

    $assessmentLabels = ['Passed', 'Failed'];

    // ── Student Certificates ──────────────────────────────────────
    $studentCertificates = \App\Models\Certificate::with('course')
        ->where('user_id', $userId)
        ->latest()
        ->get()
        ->map(function ($certificate) {
            $certificateEnrollment = DB::table('enrollment_tbls')
                ->where('user_id', $certificate->user_id)
                ->where('course_id', $certificate->course_id)
                ->first();

            $progress = (float) ($certificateEnrollment->progress ?? 0);

            $isCompetent = $progress >= 75;

            $isReleased = in_array(
                $certificate->status,
                ['Released', 'Claimed'],
                true
            );

            $certificate->progress_display = round($progress);

            $certificate->competency_display = $isCompetent
                ? 'Competent'
                : 'Not Yet Competent';

            $certificate->is_downloadable = $isCompetent && $isReleased;

            return $certificate;
        });

    // ── Return Student Homepage ───────────────────────────────────
    return view('student.homepage', compact(
        'enrollments',
        'enrollment',
        'upcomingDeadlines',
        'announcements',
        'modules',
        'unitNumbers',
        'completedModuleIds',
        'quizzes',
        'postTest',
        'quizResults',
        'totalEnrolled',
        'totalCompleted',
        'avgProgress',
        'quizzesTaken',
        'quizzesPassed',
        'weekDays',
        'weeklyData',
        'donutData',
        'recentActivity',
        'onsiteGrades',
        'studentCertificates',

        // Learning Analytics chart variables
        'quizTrendLabels',
        'quizTrendScores',
        'unitProgressLabels',
        'unitProgressValues',
        'comparisonLabels',
        'quizAverages',
        'onsiteAverages',
        'assessmentLabels',
        'assessmentPassed',
        'assessmentFailed'
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

    // Courses with relationships and counts
    $courses = \App\Models\Course_tbl::with(['facility', 'trainer'])
        ->withCount(['enrollments', 'modules', 'quizzes'])
        ->paginate(9);

    // All courses for dropdowns
    $allCourses = \App\Models\Course_tbl::all();

    $trainers = \App\Models\User_tbl::where('role', 'trainer')->get();

    $trainees = \App\Models\User_tbl::where('role', 'student')
        ->paginate(10, ['*'], 'trainee_page');

    $trainersList = \App\Models\User_tbl::where('role', 'trainer')
        ->paginate(10, ['*'], 'trainer_page');

    $announcements = \App\Models\Announcement::latest()
        ->paginate(10, ['*'], 'announcement_page');

    $registrations = \App\Models\Registration::latest()
        ->paginate(10, ['*'], 'reg_page');

    $facilities = \App\Models\Facility::with('courses')->get();

    // Fetch all certificate records
    $certificates = \App\Models\Certificate::with(['user', 'course'])
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Enrolled Trainees: Match Student Progress Calculation
    |--------------------------------------------------------------------------
    | Quiz average = average of quiz percentages.
    | Onsite average = average of onsite score percentages.
    | Overall progress = average of the available category averages.
    | If neither category has a score, progress is 0%.
    */

    $eligibleTrainees = \DB::table('enrollment_tbls')
        ->join(
            'user_tbls',
            'user_tbls.id',
            '=',
            'enrollment_tbls.user_id'
        )
        ->join(
            'course_tbls',
            'course_tbls.id',
            '=',
            'enrollment_tbls.course_id'
        )
        ->select(
            'enrollment_tbls.id as enrollment_id',
            'enrollment_tbls.user_id',
            'user_tbls.firstname',
            'user_tbls.lastname',
            'course_tbls.id as course_id',
            'course_tbls.title as course_title',
            'enrollment_tbls.progress as stored_progress',
            'enrollment_tbls.status as enrollment_status'
        )
        ->orderBy('user_tbls.lastname', 'asc')
        ->get()
        ->map(function ($trainee) {

            /*
            |--------------------------------------------------------------------------
            | 1. Quiz Average
            |--------------------------------------------------------------------------
            */

            $quizIds = \App\Models\Quiz::where(
                'course_id',
                $trainee->course_id
            )->pluck('id');

            $quizResults = \App\Models\QuizResult::where(
                'user_id',
                $trainee->user_id
            )
                ->whereIn('quiz_id', $quizIds)
                ->get();

            $quizPercentages = $quizResults
                ->map(function ($result) {

                    // Use the saved quiz percentage when available.
                    $percentage = data_get($result, 'percentage');

                    if (is_numeric($percentage)) {
                        return max(
                            0,
                            min(100, (float) $percentage)
                        );
                    }

                    // Otherwise calculate from the saved score.
                    $score = data_get($result, 'score');
                    $totalItems = data_get($result, 'total_items');

                    if (
                        is_numeric($score) &&
                        is_numeric($totalItems) &&
                        (float) $totalItems > 0
                    ) {
                        return max(
                            0,
                            min(
                                100,
                                ((float) $score / (float) $totalItems) * 100
                            )
                        );
                    }

                    return null;
                })
                ->filter(function ($percentage) {
                    return $percentage !== null;
                })
                ->values();

            $quizAverage = $quizPercentages->isNotEmpty()
                ? $quizPercentages->avg()
                : null;

            /*
            |--------------------------------------------------------------------------
            | 2. Onsite Activity Average
            |--------------------------------------------------------------------------
            | Only grades for this student, enrollment, and course are included.
            */

            $onsiteGrades = \DB::table('onsite_grades')
                ->join(
                    'onsite_activities',
                    'onsite_grades.onsite_activity_id',
                    '=',
                    'onsite_activities.id'
                )
                ->where(
                    'onsite_grades.user_id',
                    $trainee->user_id
                )
                ->where(
                    'onsite_grades.enrollment_id',
                    $trainee->enrollment_id
                )
                ->where(
                    'onsite_activities.course_id',
                    $trainee->course_id
                )
                ->select(
                    'onsite_grades.score',
                    'onsite_activities.max_score'
                )
                ->get();

            $onsitePercentages = $onsiteGrades
                ->map(function ($grade) {

                    $score = $grade->score;
                    $maxScore = $grade->max_score;

                    if (
                        !is_numeric($score) ||
                        !is_numeric($maxScore) ||
                        (float) $maxScore <= 0
                    ) {
                        return null;
                    }

                    return max(
                        0,
                        min(
                            100,
                            ((float) $score / (float) $maxScore) * 100
                        )
                    );
                })
                ->filter(function ($percentage) {
                    return $percentage !== null;
                })
                ->values();

            $onsiteAverage = $onsitePercentages->isNotEmpty()
                ? $onsitePercentages->avg()
                : null;

            /*
            |--------------------------------------------------------------------------
            | 3. Overall Progress
            |--------------------------------------------------------------------------
            */

            $availableAverages = collect([
                $quizAverage,
                $onsiteAverage,
            ])->filter(function ($average) {
                return $average !== null;
            });

            $overallProgress = $availableAverages->isNotEmpty()
                ? (int) round($availableAverages->avg())
                : 0;

            $overallProgress = max(
                0,
                min(100, $overallProgress)
            );

            // Provide calculated progress to the admin Blade view.
            $trainee->progress = $overallProgress;
            $trainee->quiz_average = $quizAverage;
            $trainee->onsite_average = $onsiteAverage;

            $trainee->competency_remarks = $overallProgress >= 75
                ? 'Competent'
                : 'Not Yet Competent';

            return $trainee;
        });

    // Chart 1: Courses grouped by sector
    $sectorData = \App\Models\Course_tbl::select(
        'sector',
        \DB::raw('COUNT(*) as total')
    )
        ->whereNotNull('sector')
        ->groupBy('sector')
        ->pluck('total', 'sector')
        ->toArray();

    $sectorLabels = array_keys($sectorData);
    $sectorCounts = array_values($sectorData);

    // Chart 2: Course capacity
    $slotData = \App\Models\Course_tbl::pluck('slots', 'title')->toArray();

    $courseTitles = array_keys($slotData);
    $courseSlots = array_values($slotData);

    // Chart 3: Trainees enrolled per course
    $coursesWithTrainees = \App\Models\Course_tbl::withCount('enrollments')
        ->get();

    $traineeCourseLabels = $coursesWithTrainees->pluck('title')->toArray();
    $traineeCourseCounts = $coursesWithTrainees->pluck('enrollments_count')->toArray();

    // Chart 4: Monthly enrollment trends for the last four months
    $months = collect(range(3, 0))
        ->map(fn ($i) => now()->subMonths($i)->format('M'))
        ->toArray();

    $monthNumbers = collect(range(3, 0))
        ->map(fn ($i) => (int) now()->subMonths($i)->format('m'))
        ->toArray();

    $colors = ['#c19a6b', '#6b9e7c', '#f4d03f', '#004d26', '#854f0b'];

    $topCourses = \App\Models\Course_tbl::take(5)->get();
    $overviewCourseDatasets = [];

    foreach ($topCourses as $index => $course) {
        $monthlyCounts = [];

        foreach ($monthNumbers as $monthNumber) {
            $count = \DB::table('enrollment_tbls')
                ->where('course_id', $course->id)
                ->whereMonth('created_at', $monthNumber)
                ->whereYear('created_at', now()->year)
                ->count();

            $monthlyCounts[] = $count;
        }

        $overviewCourseDatasets[] = [
            'label' => $course->title,
            'data' => $monthlyCounts,
            'borderColor' => $colors[$index % count($colors)],
            'tension' => 0.3,
            'fill' => false,
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
        'certificates',
        'eligibleTrainees',
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
    $validated = $request->validate([
        'id' => 'nullable|integer|exists:facilities,id',
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'course_ids' => 'nullable|array',
        'course_ids.*' => 'required|integer|distinct|exists:course_tbls,id',
    ]);

    try {
        $result = \Illuminate\Support\Facades\DB::transaction(
            function () use ($validated) {

                // Create or update the facility.
                if (!empty($validated['id'])) {
                    $facility = \App\Models\Facility::findOrFail(
                        $validated['id']
                    );

                    $facility->name = $validated['name'];
                    $facility->address = $validated['address'];
                    $facility->save();
                } else {
                    $facility = \App\Models\Facility::create([
                        'name' => $validated['name'],
                        'address' => $validated['address'],
                    ]);
                }

                // Normalize the selected course IDs.
                $courseIds = collect($validated['course_ids'] ?? [])
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();

                // Clear this facility's previous assignments.
                \App\Models\Course_tbl::where(
                    'facility_id',
                    $facility->id
                )->update([
                    'facility_id' => null,
                ]);

                // Assign the newly selected courses.
                if (!empty($courseIds)) {
                    \App\Models\Course_tbl::whereIn('id', $courseIds)
                        ->update([
                            'facility_id' => $facility->id,
                        ]);
                }

                // Return the assignments currently stored in the database.
                $assignedCourses = \App\Models\Course_tbl::where(
                    'facility_id',
                    $facility->id
                )->get(['id', 'title']);

                return [
                    'facility' => $facility,
                    'assigned_courses' => $assignedCourses,
                ];
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'Facility and course assignments saved successfully.',
            'facility' => $result['facility'],
            'assigned_courses' => $result['assigned_courses'],
            'assigned_count' => $result['assigned_courses']->count(),
        ]);

    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error(
            'Facility save failed: ' . $e->getMessage(),
            [
                'facility_id' => $request->input('id'),
                'course_ids' => $request->input('course_ids', []),
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Failed to save facility. Please check the Laravel log.',
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

public function updateCourse(Request $request, $id)
{
    $validated = $request->validate([
        'course_code'   => 'nullable|string|max:50',
        'training_id'   => 'nullable|string|max:100',
        'title'         => 'required|string|max:255',
        'duration'      => 'required|string|max:255',
        'slots'         => 'required|integer|min:0',
        'status'        => 'required|string|in:active,inactive',

        // Training date schedule
        'start_date'    => 'nullable|date',
        'end_date'      => 'nullable|date|after_or_equal:start_date',

        // Training time schedule
        'start_time'    => 'required|date_format:H:i',
        'end_time'      => 'required|date_format:H:i|after:start_time',

        // Training days
        'training_days'   => 'nullable|array',
        'training_days.*' => [
            'required',
            'string',
            'distinct',
            'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ],
    ]);

    $course = Course_tbl::findOrFail($id);

    // Preserve the existing Training ID if none is submitted.
    $trainingId = trim($validated['training_id'] ?? '');

    if ($trainingId === '') {
        $trainingId = trim($course->training_id ?? '');

        // Generate an ID for older courses that have none.
        if ($trainingId === '') {
            $trainingId = $this->generateTrainingId(
                $validated['title']
            );
        }
    }

    // Prevent duplicate Training IDs on other courses.
    $duplicate = Course_tbl::where('training_id', $trainingId)
        ->where('id', '!=', $course->id)
        ->exists();

    if ($duplicate) {
        return response()->json([
            'success' => false,
            'message' => 'This Training ID is already assigned to another course.',
        ], 422);
    }

    // Normalize the selected weekdays.
    $trainingDays = array_values(array_unique(
        $validated['training_days'] ?? []
    ));

    // Save the updated course and training schedule.
    $course->update([
        'course_code'   => $validated['course_code'] ?? null,
        'training_id'   => $trainingId,
        'title'         => $validated['title'],
        'duration'      => $validated['duration'],
        'slots'         => $validated['slots'],
        'status'        => $validated['status'],

        'start_date'    => $validated['start_date'] ?? null,
        'end_date'      => $validated['end_date'] ?? null,
        'start_time'    => $validated['start_time'],
        'end_time'      => $validated['end_time'],
        'training_days' => $trainingDays ?: null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Course updated successfully!',
        'course'  => $course->fresh(),
    ]);
}


public function storeCourse(Request $request)
{
    $validated = $request->validate([
        'course_code'   => 'nullable|string|max:50',
        'training_id'   => 'nullable|string|max:100',
        'title'         => 'required|string|max:255',
        'duration'      => 'required|string|max:255',
        'slots'         => 'required|integer|min:0',

        'sector'        => 'nullable|string|max:255',
        'description'   => 'nullable|string',
        'objectives'    => 'nullable|string',
        'schedule'      => 'nullable|string',

        // Training schedule
        'start_date'    => 'nullable|date',
        'end_date'      => 'nullable|date|after_or_equal:start_date',
        'training_days' => 'nullable|array',
        'training_days.*' => [
            'required',
            'string',
            'distinct',
            'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ],

        'location'      => 'nullable|string|max:255',
        'thumbnail'     => 'nullable|string|max:255',
        'status'        => 'nullable|in:active,inactive',
        'trainer_id'    => 'nullable|integer',
    ]);

    try {
        // Generate a Training ID if none was provided.
        $trainingId = trim($validated['training_id'] ?? '');

        if ($trainingId === '') {
            $trainingId = $this->generateTrainingId(
                $validated['title']
            );
        }

        // Prevent duplicate Training IDs.
        if (
            Course_tbl::where('training_id', $trainingId)->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This Training ID already exists. Please use a unique ID.',
            ], 422);
        }

        // Normalize selected weekdays.
        $trainingDays = array_values(array_unique(
            $validated['training_days'] ?? []
        ));

        // Create the course.
        $course = Course_tbl::create([
            'course_code'   => $validated['course_code'] ?? null,
            'training_id'   => $trainingId,
            'title'         => $validated['title'],
            'duration'      => $validated['duration'],
            'slots'         => $validated['slots'],

            'sector'        => $validated['sector'] ?? 'General',
            'description'   => $validated['description'] ?? null,
            'objectives'    => $validated['objectives'] ?? null,
            'schedule'      => $validated['schedule'] ?? null,

            // Training schedule fields
            'start_date'    => $validated['start_date'] ?? null,
            'end_date'      => $validated['end_date'] ?? null,
            'training_days' => $trainingDays ?: null,

            'location'      => $validated['location'] ?? null,
            'thumbnail'     => $validated['thumbnail'] ?? null,
            'status'        => $validated['status'] ?? 'active',
            'trainer_id'    => $validated['trainer_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully!',
            'course'  => $course->fresh(),
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        \Illuminate\Support\Facades\Log::error(
            'Failed to create course: ' . $e->getMessage()
        );

        return response()->json([
            'success' => false,
            'message' => 'Unable to create the course. Please check the submitted information and try again.',
        ], 500);
    }
}

/**
 * Generate the next available Training ID for a course.
 *
 * Format: LED-{COURSE PREFIX}-{SEQUENCE}
 * Example: LED-BKB-001
 */
private function generateTrainingId(string $title): string
{
    $normalizedTitle = mb_strtolower(trim($title));

    $knownPrefixes = [
        'baking (cake, bread, pastries)' => 'BKB',
        'basic sewing'                   => 'BS',
        'computer literacy'              => 'CL',
    ];

    if (isset($knownPrefixes[$normalizedTitle])) {
        $prefix = $knownPrefixes[$normalizedTitle];
    } else {
        $words = preg_split(
            '/\s+/',
            preg_replace('/[^a-zA-Z0-9 ]/', '', trim($title))
        );

        $ignoredWords = [
            'a', 'an', 'and', 'of', 'the',
            'for', 'in', 'with', 'to',
        ];

        $prefix = '';

        foreach ($words as $word) {
            if (
                $word !== '' &&
                !in_array(mb_strtolower($word), $ignoredWords, true)
            ) {
                $prefix .= mb_strtoupper(mb_substr($word, 0, 1));
            }
        }

        $prefix = substr($prefix, 0, 10);

        if ($prefix === '') {
            $prefix = 'TRN';
        }
    }

    // Find the highest existing sequence for this prefix.
    $existingIds = Course_tbl::where(
        'training_id',
        'like',
        'LED-' . $prefix . '-%'
    )->pluck('training_id');

    $highestNumber = 0;

    foreach ($existingIds as $existingId) {
        if (
            preg_match(
                '/^LED-' . preg_quote($prefix, '/') . '-(\d+)$/',
                $existingId,
                $matches
            )
        ) {
            $highestNumber = max(
                $highestNumber,
                (int) $matches[1]
            );
        }
    }

    // Skip any number that is already in use.
    do {
        $highestNumber++;

        $trainingId = sprintf(
            'LED-%s-%03d',
            $prefix,
            $highestNumber
        );

    } while (
        Course_tbl::where('training_id', $trainingId)->exists()
    );

    return $trainingId;
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
    $trainer = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Trainer's assigned courses
    |--------------------------------------------------------------------------
    | The dashboard's charts use all courses assigned to this trainer.
    | The existing single-course dashboard cards and follow-up table continue
    | to use the first assigned course, preserving the original page behavior.
    */
    $trainerCourses = Course_tbl::where('trainer_id', $trainer->id)->get();
    $course = $trainerCourses->first();
    $courseIds = $trainerCourses->pluck('id')->values();

    /*
    |--------------------------------------------------------------------------
    | Attendance status for today
    |--------------------------------------------------------------------------
    */
    $today = now()->toDateString();
    $attendanceTakenToday = false;

    if ($course) {
        $activeEnrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->pluck('id');

        $attendanceTakenToday = \App\Models\Attendance::whereIn('enrollment_id', $activeEnrollmentIds)
            ->whereDate('date', $today)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Weekly average progress (existing dashboard chart)
    |--------------------------------------------------------------------------
    */
    $startOfWeek = now()->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endOfWeek = now()->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $weeklyProgressData = [];

    if ($course) {
        $enrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->pluck('id');

        for ($day = $startOfWeek->copy(); $day->lte($endOfWeek); $day->addDay()) {
            if ($day->gt(now())) {
                $weeklyProgressData[] = null;
                continue;
            }

            $average = \App\Models\DailyProgress::whereIn('enrollment_id', $enrollmentIds)
                ->whereDate('date', $day->toDateString())
                ->avg('progress_percent');

            $weeklyProgressData[] = $average !== null
                ? round((float) $average, 2)
                : 0;
        }
    } else {
        $weeklyProgressData = array_fill(0, 7, null);
    }

    /*
    |--------------------------------------------------------------------------
    | Existing dashboard statistics
    |--------------------------------------------------------------------------
    */
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

    $totalInCourse = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count()
        : 0;

    $completedInCourse = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'completed')
            ->count()
        : 0;

    $completionRate = $totalInCourse > 0
        ? round(($completedInCourse / $totalInCourse) * 100) . '%'
        : '0%';

    $urgentAssessments = $course
        ? \App\Models\Deadline_tbl::where('course_id', $course->id)
            ->where('due_date', '<=', now()->copy()->addDays(3))
            ->where('due_date', '>=', now())
            ->count()
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Assessment performance for the existing follow-up table
    |--------------------------------------------------------------------------
    | Overall Assessment = (Quiz Average + Onsite Average) / 2.
    | Both categories must have recorded scores before assigning a status.
    */
    $lowPerforming = collect();

    if ($course) {
        $quizIds = \App\Models\Quiz::where('course_id', $course->id)->pluck('id');

        $assessmentRows = \App\Models\Enrollment_tbl::with(['user', 'course'])
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->get()
            ->map(function ($enrollment) use ($quizIds, $course) {
                $quizResults = $quizIds->isNotEmpty()
                    ? \App\Models\QuizResult::where('user_id', $enrollment->user_id)
                        ->whereIn('quiz_id', $quizIds)
                        ->get()
                    : collect();

                $quizPercentages = $quizResults->map(function ($result) {
                    $percentage = data_get($result, 'percentage');

                    if (is_numeric($percentage)) {
                        return max(0, min(100, (float) $percentage));
                    }

                    $score = data_get($result, 'score');
                    $totalItems = data_get($result, 'total_items');

                    if (is_numeric($score) && is_numeric($totalItems) && (float) $totalItems > 0) {
                        return max(0, min(100, ((float) $score / (float) $totalItems) * 100));
                    }

                    return null;
                })->filter(fn ($percentage) => $percentage !== null)->values();

                $quizAverage = $quizPercentages->isNotEmpty()
                    ? round((float) $quizPercentages->avg(), 2)
                    : null;

                $onsiteGrades = \Illuminate\Support\Facades\DB::table('onsite_grades')
                    ->join('onsite_activities', 'onsite_grades.onsite_activity_id', '=', 'onsite_activities.id')
                    ->where('onsite_grades.user_id', $enrollment->user_id)
                    ->where('onsite_grades.enrollment_id', $enrollment->id)
                    ->where('onsite_activities.course_id', $course->id)
                    ->select('onsite_grades.score', 'onsite_activities.max_score')
                    ->get();

                $onsitePercentages = $onsiteGrades->map(function ($grade) {
                    if (!is_numeric($grade->score) || !is_numeric($grade->max_score) || (float) $grade->max_score <= 0) {
                        return null;
                    }

                    return max(0, min(100, ((float) $grade->score / (float) $grade->max_score) * 100));
                })->filter(fn ($percentage) => $percentage !== null)->values();

                $onsiteAverage = $onsitePercentages->isNotEmpty()
                    ? round((float) $onsitePercentages->avg(), 2)
                    : null;

                $assessmentScore = ($quizAverage !== null && $onsiteAverage !== null)
                    ? round(($quizAverage + $onsiteAverage) / 2, 2)
                    : null;

                $assessmentStatus = $assessmentScore === null
                    ? 'Pending Assessment'
                    : ($assessmentScore >= 75 ? 'Competent' : 'Not Yet Competent');

                $enrollment->setAttribute('quiz_average', $quizAverage);
                $enrollment->setAttribute('onsite_average', $onsiteAverage);
                $enrollment->setAttribute('assessment_score', $assessmentScore);
                $enrollment->setAttribute('assessment_status', $assessmentStatus);

                return $enrollment;
            });

        $lowPerforming = $assessmentRows
            ->filter(fn ($trainee) => $trainee->assessment_status !== 'Competent')
            ->sortBy(fn ($trainee) => $trainee->assessment_score === null ? -1 : (float) $trainee->assessment_score)
            ->take(5)
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Existing progress-distribution chart
    |--------------------------------------------------------------------------
    */
    $allEnrollments = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->get()
        : collect();

    $progressDistribution = [
        $allEnrollments->where('status', 'completed')->count(),
        $allEnrollments->where('status', '!=', 'completed')->where('progress', '>=', 50)->count(),
        $allEnrollments->where('status', '!=', 'completed')->where('progress', '>', 0)->where('progress', '<', 50)->count(),
        $allEnrollments->where('progress', 0)->where('status', '!=', 'completed')->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | Competency chart counts (Pending is excluded from this chart)
    |--------------------------------------------------------------------------
    */
    $chartCompetentCount = 0;
    $chartNotYetCompetentCount = 0;

    if ($course) {
        $competencyEnrollments = \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed'])
            ->get();
        $competencyQuizIds = \App\Models\Quiz::where('course_id', $course->id)->pluck('id');

        foreach ($competencyEnrollments as $competencyEnrollment) {
            $quizRows = $competencyQuizIds->isNotEmpty()
                ? \App\Models\QuizResult::where('user_id', $competencyEnrollment->user_id)
                    ->whereIn('quiz_id', $competencyQuizIds)->get()
                : collect();

            $quizValues = $quizRows->map(function ($result) {
                $percentage = data_get($result, 'percentage');
                if (is_numeric($percentage)) return max(0, min(100, (float) $percentage));

                $score = data_get($result, 'score');
                $total = data_get($result, 'total_items');
                return is_numeric($score) && is_numeric($total) && (float) $total > 0
                    ? max(0, min(100, ((float) $score / (float) $total) * 100))
                    : null;
            })->filter(fn ($value) => $value !== null)->values();

            $quizAverageForChart = $quizValues->isNotEmpty() ? (float) $quizValues->avg() : null;

            $onsiteRows = \Illuminate\Support\Facades\DB::table('onsite_grades')
                ->join('onsite_activities', 'onsite_grades.onsite_activity_id', '=', 'onsite_activities.id')
                ->where('onsite_grades.user_id', $competencyEnrollment->user_id)
                ->where('onsite_grades.enrollment_id', $competencyEnrollment->id)
                ->where('onsite_activities.course_id', $course->id)
                ->select('onsite_grades.score', 'onsite_activities.max_score')
                ->get();

            $onsiteValues = $onsiteRows->map(function ($grade) {
                if (!is_numeric($grade->score) || !is_numeric($grade->max_score) || (float) $grade->max_score <= 0) return null;
                return max(0, min(100, ((float) $grade->score / (float) $grade->max_score) * 100));
            })->filter(fn ($value) => $value !== null)->values();

            $onsiteAverageForChart = $onsiteValues->isNotEmpty() ? (float) $onsiteValues->avg() : null;

            // Require both quiz and onsite scores, consistent with the assessment rule.
            if ($quizAverageForChart === null || $onsiteAverageForChart === null) continue;

            $overallForChart = ($quizAverageForChart + $onsiteAverageForChart) / 2;
            if ($overallForChart >= 75) {
                $chartCompetentCount++;
            } else {
                $chartNotYetCompetentCount++;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Chart 1: Monthly enrollment trend (last 6 months)
    |--------------------------------------------------------------------------
    */
    $chartEnrollmentLabels = [];
    $chartEnrollmentValues = [];
    $chartEnrollmentStart = now()->copy()->startOfMonth()->subMonths(5);
    $chartEnrollmentEnd = now()->copy()->endOfMonth();

    $monthlyEnrollmentRows = $courseIds->isNotEmpty()
        ? \App\Models\Enrollment_tbl::whereIn('course_id', $courseIds)
            ->whereNotNull('enrolled_at')
            ->whereBetween('enrolled_at', [$chartEnrollmentStart, $chartEnrollmentEnd])
            ->selectRaw('YEAR(enrolled_at) as enrollment_year, MONTH(enrolled_at) as enrollment_month, COUNT(*) as total')
            ->groupBy('enrollment_year', 'enrollment_month')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->enrollment_year, $row->enrollment_month))
        : collect();

    for ($i = 0; $i < 6; $i++) {
        $month = $chartEnrollmentStart->copy()->addMonths($i);
        $key = $month->format('Y-m');
        $chartEnrollmentLabels[] = $month->format('M Y');
        $chartEnrollmentValues[] = (int) data_get($monthlyEnrollmentRows->get($key), 'total', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Chart 2: Quiz vs. onsite average by assigned course
    |--------------------------------------------------------------------------
    */
    $chartAssessmentLabels = [];
    $chartQuizAverages = [];
    $chartOnsiteAverages = [];

    foreach ($trainerCourses as $trainerCourse) {
        $chartAssessmentLabels[] = $trainerCourse->title
            ?? $trainerCourse->name
            ?? $trainerCourse->course_name
            ?? ('Untitled course #' . $trainerCourse->id);

        $courseQuizIds = \App\Models\Quiz::where('course_id', $trainerCourse->id)->pluck('id');
        $quizRows = $courseQuizIds->isNotEmpty()
            ? \App\Models\QuizResult::whereIn('quiz_id', $courseQuizIds)->get()
            : collect();

        $quizPercentages = $quizRows->map(function ($result) {
            $percentage = data_get($result, 'percentage');

            if (is_numeric($percentage)) {
                return max(0, min(100, (float) $percentage));
            }

            $score = data_get($result, 'score');
            $totalItems = data_get($result, 'total_items');

            return is_numeric($score) && is_numeric($totalItems) && (float) $totalItems > 0
                ? max(0, min(100, ((float) $score / (float) $totalItems) * 100))
                : null;
        })->filter(fn ($percentage) => $percentage !== null)->values();

        $chartQuizAverages[] = $quizPercentages->isNotEmpty()
            ? round((float) $quizPercentages->avg(), 2)
            : 0;

        $onsiteRows = \Illuminate\Support\Facades\DB::table('onsite_grades')
            ->join('onsite_activities', 'onsite_grades.onsite_activity_id', '=', 'onsite_activities.id')
            ->where('onsite_activities.course_id', $trainerCourse->id)
            ->select('onsite_grades.score', 'onsite_activities.max_score')
            ->get();

        $onsitePercentages = $onsiteRows->map(function ($grade) {
            if (!is_numeric($grade->score) || !is_numeric($grade->max_score) || (float) $grade->max_score <= 0) {
                return null;
            }

            return max(0, min(100, ((float) $grade->score / (float) $grade->max_score) * 100));
        })->filter(fn ($percentage) => $percentage !== null)->values();

        $chartOnsiteAverages[] = $onsitePercentages->isNotEmpty()
            ? round((float) $onsitePercentages->avg(), 2)
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Chart 3: Attendance rate by week (last 6 weeks)
    |--------------------------------------------------------------------------
    | Attendance records with a status of Present or Late count as attended.
    | All recorded attendance rows for active enrollments form the denominator.
    */
    $chartAttendanceLabels = [];
    $chartAttendanceRates = [];
    $attendanceWindowStart = now()->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->subWeeks(5);
    $attendanceWindowEnd = now()->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

    $chartEnrollmentIds = $courseIds->isNotEmpty()
        ? \App\Models\Enrollment_tbl::whereIn('course_id', $courseIds)
            ->where('status', 'active')
            ->pluck('id')
        : collect();

    $attendanceRows = $chartEnrollmentIds->isNotEmpty()
        ? \App\Models\Attendance::whereIn('enrollment_id', $chartEnrollmentIds)
            ->whereBetween('date', [$attendanceWindowStart->toDateString(), $attendanceWindowEnd->toDateString()])
            ->get(['date', 'status'])
        : collect();

    for ($i = 0; $i < 6; $i++) {
        $weekStart = $attendanceWindowStart->copy()->addWeeks($i);
        $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
        $weekRows = $attendanceRows->filter(function ($row) use ($weekStart, $weekEnd) {
            $date = \Carbon\Carbon::parse($row->date);
            return $date->betweenIncluded($weekStart->copy()->startOfDay(), $weekEnd->copy()->endOfDay());
        });

        $chartAttendanceLabels[] = $weekStart->format('M d');
        $recordedCount = $weekRows->count();
        $attendedCount = $weekRows->filter(function ($row) {
            return in_array(strtolower(trim((string) $row->status)), ['present', 'late'], true);
        })->count();

        $chartAttendanceRates[] = $recordedCount > 0
            ? round(($attendedCount / $recordedCount) * 100, 2)
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Chart 4: Course completion rate by course name
    |--------------------------------------------------------------------------
    */
    $chartCourseLabels = [];
    $chartCourseCompletionRates = [];
    $chartCourseCompletedCounts = [];
    $chartCourseEnrollmentCounts = [];

    foreach ($trainerCourses as $trainerCourse) {
        $chartCourseLabels[] = $trainerCourse->title
            ?? $trainerCourse->name
            ?? $trainerCourse->course_name
            ?? ('Training course ' . $trainerCourse->id);

        $enrollmentQuery = \App\Models\Enrollment_tbl::where('course_id', $trainerCourse->id);
        $enrollmentCount = (clone $enrollmentQuery)->count();
        $completedCount = (clone $enrollmentQuery)->where('status', 'completed')->count();

        $chartCourseCompletedCounts[] = $completedCount;
        $chartCourseEnrollmentCounts[] = $enrollmentCount;
        $chartCourseCompletionRates[] = $enrollmentCount > 0
            ? round(($completedCount / $enrollmentCount) * 100, 2)
            : 0;
    }

    return view('trainer.teacher', compact(
        'totalTrainees',
        'monthlyEnrollment',
        'completionRate',
        'urgentAssessments',
        'lowPerforming',
        'progressDistribution',
        'attendanceTakenToday',
        'weeklyProgressData',
        'chartEnrollmentLabels',
        'chartEnrollmentValues',
        'chartAssessmentLabels',
        'chartQuizAverages',
        'chartOnsiteAverages',
        'chartAttendanceLabels',
        'chartAttendanceRates',
        'chartCompetentCount',
        'chartNotYetCompetentCount',
        'chartCourseEnrollmentCounts'
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

    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    // No course assigned to this trainer
    if (!$course) {
        return view('trainer.assessment', [
            'course'        => null,
            'results'       => collect(),
            'onsiteGrades'  => collect(),
            'totalTaken'    => 0,
            'avgScore'      => 0,
            'passingRate'   => 0,
            'topPerformers' => collect(),
            'quizzes'       => collect(),
        ]);
    }

    // =========================================
    // QUIZ RESULTS
    // =========================================
    $results = \App\Models\QuizResult::with(['user', 'quiz'])
        ->whereHas('quiz', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->orderByDesc('created_at')
        ->get();

    // =========================================
    // QUIZ STATISTICS
    // =========================================
    $totalTaken = $results->count();

    $avgScore = $totalTaken > 0
        ? round($results->avg(function ($result) {
            return (float) ($result->percentage ?? 0);
        }), 1)
        : 0;

    $passed = $results->filter(function ($result) {
        return strtolower((string) $result->status) === 'passed';
    })->count();

    $passingRate = $totalTaken > 0
        ? round(($passed / $totalTaken) * 100, 1)
        : 0;

    // =========================================
    // TOP QUIZ PERFORMERS
    // =========================================
    $topPerformers = \App\Models\QuizResult::with('user')
        ->whereHas('quiz', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->select(
            'user_id',
            \Illuminate\Support\Facades\DB::raw(
                'ROUND(AVG(percentage), 1) as avg_score'
            ),
            \Illuminate\Support\Facades\DB::raw(
                'COUNT(*) as total_taken'
            )
        )
        ->groupBy('user_id')
        ->orderByDesc('avg_score')
        ->take(5)
        ->get();

    // =========================================
    // QUIZZES FOR THIS COURSE
    // =========================================
    $quizzes = \App\Models\Quiz::where('course_id', $course->id)
        ->orderBy('id')
        ->get();

    // =========================================
    // ONSITE ACTIVITY GRADES
    // =========================================
    $onsiteGrades = \Illuminate\Support\Facades\DB::table('onsite_grades as og')
        ->join(
            'onsite_activities as oa',
            'oa.id',
            '=',
            'og.onsite_activity_id'
        )
        ->leftJoin('user_tbls as u', 'u.id', '=', 'og.user_id')
        ->where('oa.course_id', $course->id)
        ->select(
            'og.id',
            'og.user_id',
            'og.enrollment_id',
            'og.score',
            'og.remarks',
            'og.graded_by',
            'og.created_at',
            'oa.title as activity_title',
            'oa.max_score',
            'oa.activity_date',
            'oa.course_id',
            'u.firstname',
            'u.lastname'
        )
        ->orderByDesc('og.created_at')
        ->get();

    // =========================================
    // RETURN ALL DATA TO THE VIEW
    // =========================================
    return view('trainer.assessment', compact(
        'course',
        'results',
        'onsiteGrades',
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
    $userId = \Illuminate\Support\Facades\Auth::id();

    $quiz = \App\Models\Quiz::with('questions')
        ->findOrFail($quizId);

    // Keep the existing single best-result row.
    $bestResult = \App\Models\QuizResult::where('quiz_id', $quiz->id)
        ->where('user_id', $userId)
        ->first();

    // Load individual submission records separately.
    $attemptHistory = \App\Models\QuizAttemptHistory::where(
        'quiz_id',
        $quiz->id
    )
        ->where('user_id', $userId)
        ->orderBy('attempt_number')
        ->get();

    $attemptsUsed = $bestResult
        ? max(1, (int) ($bestResult->attempts_used ?? 1))
        : 0;

    $attemptsAllowed = max(
        1,
        (int) ($quiz->attempts_allowed ?? 2)
    );

    $hasPassed = $bestResult
        && strtolower((string) $bestResult->status) === 'passed';

    $canAttempt = !$hasPassed
        && $attemptsUsed < $attemptsAllowed;

    // Never expose the answer key or feedback.
    $quiz->questions->each(function ($question) {
        $question->makeHidden([
            'correct_answer',
            'correct_answers',
            'feedback',
        ]);
    });

    return response()->json([
        'success' => true,
        'quiz' => $quiz,
        'taken' => !$canAttempt,
        'can_attempt' => $canAttempt,
        'attempts_allowed' => $attemptsAllowed,
        'attempts_used' => $attemptsUsed,
        'attempts_remaining' => $canAttempt
            ? max(0, $attemptsAllowed - $attemptsUsed)
            : 0,
        'result' => $bestResult,
        'attempt_history' => $attemptHistory,
    ]);
}


public function submitQuiz(\Illuminate\Http\Request $request)
{
    $request->validate([
        'quiz_id' => 'required|exists:quizzes,id',
        'answers' => 'sometimes|array',
        'timed_out' => 'sometimes|boolean',
    ]);

    $userId = \Illuminate\Support\Facades\Auth::id();
    $timedOut = $request->boolean('timed_out');
    $answers = $request->input('answers', []);

    if (!is_array($answers)) {
        $answers = [];
    }

    $submission = \Illuminate\Support\Facades\DB::transaction(
        function () use ($request, $userId, $timedOut, $answers) {

            $quiz = \App\Models\Quiz::with('questions')
                ->whereKey($request->quiz_id)
                ->lockForUpdate()
                ->firstOrFail();

            $bestResult = \App\Models\QuizResult::where(
                'quiz_id',
                $quiz->id
            )
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            $attemptsUsed = $bestResult
                ? max(1, (int) ($bestResult->attempts_used ?? 1))
                : 0;

            $attemptsAllowed = max(
                1,
                (int) ($quiz->attempts_allowed ?? 2)
            );

            $hasPassed = $bestResult
                && strtolower((string) $bestResult->status) === 'passed';

            if ($hasPassed) {
                return [
                    'error' => 'You have already passed this quiz. No additional attempts are available.',
                    'http_code' => 409,
                ];
            }

            if ($attemptsUsed >= $attemptsAllowed) {
                return [
                    'error' => 'You have used all available attempts for this quiz.',
                    'http_code' => 409,
                ];
            }

            $normalizeAnswer = static function ($value) {
                if (!is_scalar($value)) {
                    return '';
                }

                return mb_strtolower(trim((string) $value));
            };

            $decodeCorrectAnswers = static function ($value): array {
                if (is_array($value)) {
                    return $value;
                }

                if (!is_string($value) || trim($value) === '') {
                    return [];
                }

                $decoded = json_decode($value, true);

                if (
                    json_last_error() === JSON_ERROR_NONE
                    && is_array($decoded)
                ) {
                    return $decoded;
                }

                return [$value];
            };

            // Grade this submission.
            $score = 0;
            $total = $quiz->questions->count();

            foreach ($quiz->questions as $question) {
                $answer = $answers[$question->id]
                    ?? $answers[(string) $question->id]
                    ?? null;

                if ($answer === null || $answer === '') {
                    continue;
                }

                $questionType = strtolower(
                    (string) ($question->question_type ?? 'multiple_choice')
                );

                $correctAnswers = $decodeCorrectAnswers(
                    $question->correct_answers ?? null
                );

                $legacyCorrectAnswer = $normalizeAnswer(
                    $question->correct_answer ?? ''
                );

                if ($questionType === 'checkboxes') {
                    $givenAnswers = is_array($answer)
                        ? $answer
                        : [$answer];

                    $givenAnswers = array_map(
                        $normalizeAnswer,
                        $givenAnswers
                    );

                    $expectedAnswers = array_map(
                        $normalizeAnswer,
                        $correctAnswers
                    );

                    $givenAnswers = array_values(array_unique(
                        array_filter(
                            $givenAnswers,
                            fn ($value) => $value !== ''
                        )
                    ));

                    $expectedAnswers = array_values(array_unique(
                        array_filter(
                            $expectedAnswers,
                            fn ($value) => $value !== ''
                        )
                    ));

                    sort($givenAnswers);
                    sort($expectedAnswers);

                    $isCorrect = !empty($expectedAnswers)
                        && $givenAnswers === $expectedAnswers;
                } else {
                    $givenAnswer = $normalizeAnswer($answer);

                    $acceptedAnswers = array_map(
                        $normalizeAnswer,
                        $correctAnswers
                    );

                    // Support existing multiple-choice answer keys.
                    if (
                        $questionType === 'multiple_choice'
                        && $legacyCorrectAnswer !== ''
                    ) {
                        $acceptedAnswers[] = $legacyCorrectAnswer;
                    }

                    $acceptedAnswers = array_values(array_unique(
                        array_filter(
                            $acceptedAnswers,
                            fn ($value) => $value !== ''
                        )
                    ));

                    $isCorrect = $givenAnswer !== ''
                        && in_array(
                            $givenAnswer,
                            $acceptedAnswers,
                            true
                        );
                }

                if ($isCorrect) {
                    $score++;
                }
            }

            $percentage = $total > 0
                ? (int) round(($score / $total) * 100)
                : 0;

            // A timed-out submission always fails.
            $currentAttemptStatus = (
                !$timedOut
                && $percentage >= (int) $quiz->passing_score
            )
                ? 'passed'
                : 'failed';

            $newAttemptsUsed = $attemptsUsed + 1;
            $attemptNumber = $newAttemptsUsed;

            // Record EVERY accepted submission independently.
            \App\Models\QuizAttemptHistory::create([
                'quiz_id' => $quiz->id,
                'user_id' => $userId,
                'attempt_number' => $attemptNumber,
                'score' => $score,
                'total_items' => $total,
                'percentage' => $percentage,
                'status' => $currentAttemptStatus,
                'timed_out' => $timedOut,
            ]);

            // Determine whether this submission improves the best score.
            $isBetterScore = !$bestResult
                || $percentage > (int) $bestResult->percentage
                || (
                    $percentage === (int) $bestResult->percentage
                    && $score > (int) $bestResult->score
                );

            if (!$bestResult) {
                $bestResult = \App\Models\QuizResult::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $userId,
                    'attempt_number' => $attemptNumber,
                    'attempts_used' => $newAttemptsUsed,
                    'score' => $score,
                    'total_items' => $total,
                    'percentage' => $percentage,
                    'status' => $currentAttemptStatus,
                ]);
            } else {
                $bestResult->attempts_used = $newAttemptsUsed;

                if ($isBetterScore) {
                    $bestResult->attempt_number = $attemptNumber;
                    $bestResult->score = $score;
                    $bestResult->total_items = $total;
                    $bestResult->percentage = $percentage;
                    $bestResult->status = $currentAttemptStatus;
                }

                $bestResult->save();
            }

            // Update enrollment progress using existing quiz results.
            $progress = 0;

            $enrollment = \App\Models\Enrollment_tbl::where(
                'user_id',
                $userId
            )
                ->where('course_id', $quiz->course_id)
                ->first();

            if ($enrollment) {
                $courseQuizIds = \App\Models\Quiz::where(
                    'course_id',
                    $quiz->course_id
                )->pluck('id');

                $totalQuizzes = $courseQuizIds->count();

                $completedQuizzes = \App\Models\QuizResult::where(
                    'user_id',
                    $userId
                )
                    ->whereIn('quiz_id', $courseQuizIds)
                    ->distinct()
                    ->count('quiz_id');

                $progress = $totalQuizzes > 0
                    ? (int) round(
                        ($completedQuizzes / $totalQuizzes) * 100
                    )
                    : 0;

                $enrollment->progress = $progress;

                $moduleIds = \App\Models\Module::where(
                    'course_id',
                    $quiz->course_id
                )
                    ->active()
                    ->pluck('id');

                $completedModules = \App\Models\ModuleCompletion::where(
                    'user_id',
                    $userId
                )
                    ->whereIn('module_id', $moduleIds)
                    ->count();

                $allModulesCompleted = $moduleIds->count() > 0
                    && $completedModules >= $moduleIds->count();

                $postTest = \App\Models\Quiz::where(
                    'course_id',
                    $quiz->course_id
                )
                    ->where('quiz_type', 'post_test')
                    ->first();

                $postTestPassed = $postTest
                    && \App\Models\QuizResult::where(
                        'user_id',
                        $userId
                    )
                        ->where('quiz_id', $postTest->id)
                        ->where('status', 'passed')
                        ->exists();

                $handsOnPassed = \App\Models\HandsOnEvaluation::where(
                    'user_id',
                    $userId
                )
                    ->where('course_id', $quiz->course_id)
                    ->where('result', 'passed')
                    ->exists();

                if (
                    $allModulesCompleted
                    && $postTestPassed
                    && $handsOnPassed
                ) {
                    $enrollment->status = 'completed';
                    $enrollment->completed_at = now();
                }

                $enrollment->save();
            }

            $savedStatus = strtolower((string) $bestResult->status);

            $attemptsRemaining = $savedStatus === 'passed'
                ? 0
                : max(0, $attemptsAllowed - $newAttemptsUsed);

            $canAttemptAgain = $savedStatus !== 'passed'
                && $attemptsRemaining > 0;

            return [
                'success' => true,

                // Best result saved in quiz_results.
                'score' => (int) $bestResult->score,
                'total' => (int) $bestResult->total_items,
                'percentage' => (int) $bestResult->percentage,
                'status' => $bestResult->status,

                'passing' => (int) $quiz->passing_score,
                'progress' => $progress,

                'attempt_number' => $attemptNumber,
                'attempts_used' => $newAttemptsUsed,
                'attempts_allowed' => $attemptsAllowed,
                'attempts_remaining' => $attemptsRemaining,
                'can_attempt_again' => $canAttemptAgain,

                // Result of the submission just made.
                'current_attempt_score' => $score,
                'current_attempt_total' => $total,
                'current_attempt_percentage' => $percentage,
                'current_attempt_status' => $currentAttemptStatus,
                'timed_out' => $timedOut,
                'saved_as_best' => $isBetterScore,
            ];
        }
    );

    if (isset($submission['error'])) {
        $httpCode = $submission['http_code'] ?? 409;
        unset($submission['http_code']);

        return response()->json($submission, $httpCode);
    }

    return response()->json($submission);
}




public function trainerStudents()
{
    $trainer = Auth::user();

    // Get the course assigned to this trainer.
    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    // Default values when no course is assigned.
    if (!$course) {
        return view('trainer.students', [
            'course'           => null,
            'students'         => collect(),
            'quizzes'          => collect(),
            'onsiteActivities' => collect(),
            'handsOnEvaluations' => collect(),
            'metrics' => [
                'total_students'   => 0,
                'quiz_attempts'    => 0,
                'onsite_grades'    => 0,
                'class_average'    => null,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Get enrolled students
    |--------------------------------------------------------------------------
    */

    $students = User_tbl::join(
        'enrollment_tbls',
        'user_tbls.id',
        '=',
        'enrollment_tbls.user_id'
    )
        ->where('enrollment_tbls.course_id', $course->id)
        ->where('user_tbls.role', 'student')
        ->select(
            'user_tbls.id as user_id',
            'user_tbls.firstname',
            'user_tbls.lastname',
            'user_tbls.email',
            'enrollment_tbls.id as enrollment_id',
            'enrollment_tbls.status',
            'enrollment_tbls.progress',
            'enrollment_tbls.enrolled_at'
        )
        ->orderByDesc('enrollment_tbls.enrolled_at')
        ->get();

    $studentIds = $students->pluck('user_id')->unique()->values();

    $enrollmentIds = $students->pluck('enrollment_id')
        ->unique()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | 2. Get quizzes for this course
    |--------------------------------------------------------------------------
    */

    $quizzes = \Illuminate\Support\Facades\DB::table('quizzes')
        ->where('course_id', $course->id)
        ->orderBy('id')
        ->get()
        ->values()
        ->map(function ($quiz, $index) {
            $quiz->quiz_number = $index + 1;

            return $quiz;
        });

    $quizIds = $quizzes->pluck('id');

    /*
    |--------------------------------------------------------------------------
    | 3. Get recorded quiz results
    |--------------------------------------------------------------------------
    */

    $quizResults = collect();

    if ($studentIds->isNotEmpty() && $quizIds->isNotEmpty()) {
        $quizResults = \Illuminate\Support\Facades\DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->whereIn('qr.user_id', $studentIds)
            ->whereIn('qr.quiz_id', $quizIds)
            ->select(
                'qr.id',
                'qr.user_id',
                'qr.quiz_id',
                'qr.score',
                'qr.total_items',
                'qr.percentage',
                'qr.status',
                'qr.created_at',
                'q.title as quiz_title'
            )
            ->orderByDesc('qr.created_at')
            ->get()
            ->groupBy('user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Get onsite activities for this course
    |--------------------------------------------------------------------------
    */

    $onsiteActivities = \Illuminate\Support\Facades\DB::table('onsite_activities')
        ->where('course_id', $course->id)
        ->orderByDesc('activity_date')
        ->get();

    $activityIds = $onsiteActivities->pluck('id');

    /*
    |--------------------------------------------------------------------------
    | 5. Get onsite grades
    |--------------------------------------------------------------------------
    |
    | Match grades by user_id AND enrollment_id so records remain associated
    | with the correct trainee enrollment.
    */

    $onsiteGrades = collect();

    if (
        $studentIds->isNotEmpty()
        && $enrollmentIds->isNotEmpty()
        && $activityIds->isNotEmpty()
    ) {
        $onsiteGrades = \Illuminate\Support\Facades\DB::table('onsite_grades as og')
            ->join(
                'onsite_activities as oa',
                'oa.id',
                '=',
                'og.onsite_activity_id'
            )
            ->where('oa.course_id', $course->id)
            ->whereIn('og.user_id', $studentIds)
            ->whereIn('og.enrollment_id', $enrollmentIds)
            ->whereIn('og.onsite_activity_id', $activityIds)
            ->select(
                'og.id',
                'og.user_id',
                'og.enrollment_id',
                'og.onsite_activity_id',
                'og.score',
                'og.remarks',
                'og.graded_by',
                'og.created_at',
                'oa.title as activity_title',
                'oa.max_score',
                'oa.activity_date'
            )
            ->orderByDesc('oa.activity_date')
            ->get()
            ->groupBy('user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Attach performance records and calculate per-student averages
    |--------------------------------------------------------------------------
    */

    foreach ($students as $student) {
        $studentQuizResults = $quizResults->get(
            $student->user_id,
            collect()
        );

        $studentOnsiteGrades = $onsiteGrades->get(
            $student->user_id,
            collect()
        )->where('enrollment_id', $student->enrollment_id)->values();

        $quizPercentages = $studentQuizResults
            ->map(function ($result) {
                if (
                    $result->percentage !== null
                    && is_numeric($result->percentage)
                ) {
                    return (float) $result->percentage;
                }

                if (
                    is_numeric($result->score)
                    && is_numeric($result->total_items)
                    && (float) $result->total_items > 0
                ) {
                    return (
                        (float) $result->score
                        / (float) $result->total_items
                    ) * 100;
                }

                return null;
            })
            ->filter(fn ($percentage) => $percentage !== null);

        $onsitePercentages = $studentOnsiteGrades
            ->map(function ($grade) {
                if (
                    !is_numeric($grade->score)
                    || !is_numeric($grade->max_score)
                    || (float) $grade->max_score <= 0
                ) {
                    return null;
                }

                return (
                    (float) $grade->score
                    / (float) $grade->max_score
                ) * 100;
            })
            ->filter(fn ($percentage) => $percentage !== null);

        $student->quiz_results = $studentQuizResults->values();
        $student->onsite_grades = $studentOnsiteGrades;

        $student->quiz_average = $quizPercentages->isNotEmpty()
            ? round($quizPercentages->avg(), 2)
            : null;

        $student->onsite_average = $onsitePercentages->isNotEmpty()
            ? round($onsitePercentages->avg(), 2)
            : null;

        // Preserve the existing enrollment progress value.
        $student->progress = min(
            100,
            max(0, (float) ($student->progress ?? 0))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 7. Dashboard metrics
    |--------------------------------------------------------------------------
    */

    $allQuizPercentages = $quizResults
        ->flatten(1)
        ->map(function ($result) {
            if (
                $result->percentage !== null
                && is_numeric($result->percentage)
            ) {
                return (float) $result->percentage;
            }

            if (
                is_numeric($result->score)
                && is_numeric($result->total_items)
                && (float) $result->total_items > 0
            ) {
                return (
                    (float) $result->score
                    / (float) $result->total_items
                ) * 100;
            }

            return null;
        })
        ->filter(fn ($percentage) => $percentage !== null);

    $allOnsitePercentages = $onsiteGrades
        ->flatten(1)
        ->map(function ($grade) {
            if (
                !is_numeric($grade->score)
                || !is_numeric($grade->max_score)
                || (float) $grade->max_score <= 0
            ) {
                return null;
            }

            return (
                (float) $grade->score
                / (float) $grade->max_score
            ) * 100;
        })
        ->filter(fn ($percentage) => $percentage !== null);

    $allAssessmentPercentages = $allQuizPercentages
        ->concat($allOnsitePercentages);

    $metrics = [
        'total_students' => $students->count(),

        // Counts recorded submissions, not unique quizzes.
        'quiz_attempts' => $quizResults->flatten(1)->count(),

        'onsite_grades' => $onsiteGrades->flatten(1)->count(),

        'class_average' => $allAssessmentPercentages->isNotEmpty()
            ? round($allAssessmentPercentages->avg(), 2)
            : null,
    ];

    /*
    |--------------------------------------------------------------------------
    | 8. Return the Students view
    |--------------------------------------------------------------------------
    */

    return view('trainer.students', [
        'course'             => $course,
        'students'           => $students,
        'quizzes'            => $quizzes,
        'onsiteActivities'   => $onsiteActivities,
        'handsOnEvaluations' => collect(),
        'metrics'            => $metrics,
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
    // Get the currently logged-in trainer
    $trainer = Auth::user();

    // Retrieve the trainer's assigned course and its related database records
    $course = $trainer
        ? Course_tbl::with([
            'facility',
            'deadlines' => function ($query) {
                $query->orderBy('due_date', 'asc');
            },
        ])
        ->where('trainer_id', $trainer->id)
        ->first()
        : null;

    // Count actual enrollment records for the assigned course
    $totalStudents = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count()
        : 0;

    // Retrieve the course deadlines in chronological order
    $deadlines = $course
        ? $course->deadlines
        : collect();

    // Send database records to the existing Blade view
    return view('trainer.schedule', compact(
        'course',
        'totalStudents',
        'deadlines'
    ));
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

    // Get only courses this student is enrolled in.
    $enrollments = \App\Models\Enrollment_tbl::with('course')
        ->where('user_id', $userId)
        ->get();

    // Read the selected course ID from the URL.
    $selectedId = $request->query('course_id');

    // Select the requested enrollment, or fall back to the active/first enrollment.
    if ($selectedId !== null && $selectedId !== '') {
        $enrollment = $enrollments->first(function ($item) use ($selectedId) {
            return (string) $item->course_id === (string) $selectedId;
        });

        if (!$enrollment) {
            abort(404, 'Course not found or you are not enrolled in this course.');
        }
    } else {
        $enrollment = $enrollments->first(function ($item) {
            return strtolower((string) $item->status) === 'active';
        }) ?? $enrollments->first();
    }

    // Initialize variables.
    $modules = collect();
    $unitNumbers = collect();
    $completedModuleIds = [];
    $quizzes = collect();

    // Best/current summary result rows from quiz_results.
    $quizResults = collect();
    $latestQuizResults = collect();

    // Individual submission history from quiz_attempt_histories.
    $allQuizAttemptHistories = collect();

    $onsiteGrades = collect();

    $quizAverage = null;
    $onsiteAverage = null;
    $overallProgress = 0;

    $postTest = null;
    $handsOnEvaluation = null;
    $certificate = null;

    if ($enrollment) {
        $courseId = $enrollment->course_id;

        /*
        |--------------------------------------------------------------------------
        | ONSITE GRADES
        |--------------------------------------------------------------------------
        */

        $onsiteGrades = \Illuminate\Support\Facades\DB::table('onsite_grades')
            ->join(
                'onsite_activities',
                'onsite_grades.onsite_activity_id',
                '=',
                'onsite_activities.id'
            )
            ->where('onsite_grades.user_id', $userId)
            ->where('onsite_grades.enrollment_id', $enrollment->id)
            ->where('onsite_activities.course_id', $courseId)
            ->select(
                'onsite_grades.id',
                'onsite_grades.user_id',
                'onsite_grades.enrollment_id',
                'onsite_grades.onsite_activity_id',
                'onsite_grades.score',
                'onsite_grades.remarks',
                'onsite_activities.title as activity_title',
                'onsite_activities.activity_date',
                'onsite_activities.max_score'
            )
            ->orderByDesc('onsite_activities.activity_date')
            ->orderByDesc('onsite_activities.id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MODULES
        |--------------------------------------------------------------------------
        */

        $modules = \App\Models\Module::where('course_id', $courseId)
            ->active()
            ->ordered()
            ->get();

        $moduleIds = $modules->pluck('id');

        $unitNumbers = $modules
            ->pluck('unit_number')
            ->filter(function ($number) {
                return $number !== null;
            })
            ->unique()
            ->sort()
            ->values();

        $completedModuleIds = \App\Models\ModuleCompletion::where(
            'user_id',
            $userId
        )
            ->whereIn('module_id', $moduleIds)
            ->pluck('module_id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | COURSE QUIZZES
        |--------------------------------------------------------------------------
        */

        $quizzes = \App\Models\Quiz::where('course_id', $courseId)
            ->orderBy('id')
            ->get();

        $quizIds = $quizzes->pluck('id');

        $postTest = $quizzes->first(function ($quiz) {
            return strtolower((string) $quiz->quiz_type) === 'post_test';
        });

        /*
        |--------------------------------------------------------------------------
        | QUIZ RESULTS SUMMARY
        |--------------------------------------------------------------------------
        | quiz_results stores one summary/best-result row per student and quiz.
        | Do not treat this table as the source of every submission.
        */

        $quizResults = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', $quizIds)
            ->orderBy('quiz_id')
            ->orderByDesc('id')
            ->get();

        // One summary result per quiz for progress calculations.
        $latestQuizResults = $quizResults
            ->groupBy('quiz_id')
            ->map(function ($results) {
                return $results->first();
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | INDIVIDUAL QUIZ ATTEMPT HISTORY
        |--------------------------------------------------------------------------
        | This is the collection the Blade should use to display Attempt 1,
        | Attempt 2, and any additional recorded submissions.
        */

        $allQuizAttemptHistories = \App\Models\QuizAttemptHistory::where(
            'user_id',
            $userId
        )
            ->whereIn('quiz_id', $quizIds)
            ->orderBy('quiz_id')
            ->orderBy('attempt_number')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | HANDS-ON EVALUATION
        |--------------------------------------------------------------------------
        */

        $handsOnEvaluation = \App\Models\HandsOnEvaluation::where(
            'user_id',
            $userId
        )
            ->where('course_id', $courseId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | CERTIFICATE
        |--------------------------------------------------------------------------
        */

        $certificate = \App\Models\Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereIn('status', ['Released', 'Claimed'])
            ->whereRaw(
                'LOWER(TRIM(COALESCE(remarks, ""))) = ?',
                ['competent']
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | QUIZ AVERAGE
        |--------------------------------------------------------------------------
        | Use the saved summary result for each quiz, not every retake.
        */

        $quizPercentages = $latestQuizResults
            ->map(function ($result) {
                $percentage = data_get($result, 'percentage');

                if (is_numeric($percentage)) {
                    return max(0, min(100, (float) $percentage));
                }

                $score = data_get($result, 'score');
                $totalScore = data_get($result, 'total_score')
                    ?? data_get($result, 'total_items');

                if (
                    is_numeric($score) &&
                    is_numeric($totalScore) &&
                    (float) $totalScore > 0
                ) {
                    return max(
                        0,
                        min(
                            100,
                            ((float) $score / (float) $totalScore) * 100
                        )
                    );
                }

                return null;
            })
            ->filter(function ($percentage) {
                return $percentage !== null;
            })
            ->values();

        $quizAverage = $quizPercentages->isNotEmpty()
            ? $quizPercentages->avg()
            : null;

        /*
        |--------------------------------------------------------------------------
        | ONSITE ACTIVITY AVERAGE
        |--------------------------------------------------------------------------
        */

        $onsitePercentages = $onsiteGrades
            ->map(function ($grade) {
                $score = $grade->score;
                $maxScore = $grade->max_score;

                if (
                    !is_numeric($score) ||
                    !is_numeric($maxScore) ||
                    (float) $maxScore <= 0
                ) {
                    return null;
                }

                return max(
                    0,
                    min(100, ((float) $score / (float) $maxScore) * 100)
                );
            })
            ->filter(function ($percentage) {
                return $percentage !== null;
            })
            ->values();

        $onsiteAverage = $onsitePercentages->isNotEmpty()
            ? $onsitePercentages->avg()
            : null;

        /*
        |--------------------------------------------------------------------------
        | OVERALL PROGRESS
        |--------------------------------------------------------------------------
        | Average the available quiz and onsite category averages.
        | A category without scores is not counted as zero.
        */

        $availableAverages = collect([
            $quizAverage,
            $onsiteAverage,
        ])->filter(function ($average) {
            return $average !== null;
        });

        $overallProgress = $availableAverages->isNotEmpty()
            ? (int) round($availableAverages->avg())
            : 0;

        $overallProgress = max(0, min(100, $overallProgress));
    }

    return view('student.modules', compact(
        'enrollments',
        'enrollment',
        'modules',
        'unitNumbers',
        'completedModuleIds',
        'quizzes',
        'quizResults',
        'latestQuizResults',
        'allQuizAttemptHistories',
        'postTest',
        'handsOnEvaluation',
        'certificate',
        'onsiteGrades',
        'quizAverage',
        'onsiteAverage',
        'overallProgress'
    ));
}


public function studentGrades()
{
    $userId = \Illuminate\Support\Facades\Auth::id();

    // Courses the student is enrolled in.
    $courseIds = \Illuminate\Support\Facades\DB::table('enrollment_tbls')
        ->where('user_id', $userId)
        ->pluck('course_id')
        ->unique()
        ->values();

    // Initialize defaults.
    $quizScores = collect();
    $onsiteScores = collect();

    $quizAverage = null;
    $onsiteAverage = null;
    $overallAverage = null;
    $overallStatus = 'Pending Assessment';

    /*
    |--------------------------------------------------------------------------
    | QUIZ RESULTS
    |--------------------------------------------------------------------------
    */
    if (
        $courseIds->isNotEmpty()
        && \Illuminate\Support\Facades\Schema::hasTable('quizzes')
        && \Illuminate\Support\Facades\Schema::hasTable('quiz_results')
    ) {
        $quizScores = \Illuminate\Support\Facades\DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->join('course_tbls as c', 'c.id', '=', 'q.course_id')
            ->where('qr.user_id', $userId)
            ->whereIn('q.course_id', $courseIds)
            ->select(
                'qr.id',
                'qr.quiz_id',
                'qr.score',
                'qr.total_items',
                'qr.percentage',
                'qr.status',
                'qr.created_at',
                'q.title as quiz_title',
                'c.id as course_id',
                'c.title as course_title'
            )
            ->orderBy('c.title')
            ->orderBy('q.title')
            ->orderByDesc('qr.created_at')
            ->orderByDesc('qr.id')
            ->get();

        $percentages = $quizScores
            ->pluck('percentage')
            ->filter(fn ($value) => $value !== null);

        if ($percentages->isNotEmpty()) {
            $quizAverage = round($percentages->avg(), 2);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ONSITE ACTIVITY SCORES
    |--------------------------------------------------------------------------
    | Ungraded activities remain visible but are excluded from the average.
    */
    if (
        $courseIds->isNotEmpty()
        && \Illuminate\Support\Facades\Schema::hasTable('onsite_activities')
        && \Illuminate\Support\Facades\Schema::hasTable('onsite_grades')
    ) {
        $onsiteScores = \Illuminate\Support\Facades\DB::table('enrollment_tbls as e')
            ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
            ->join(
                'onsite_activities as oa',
                'oa.course_id',
                '=',
                'e.course_id'
            )
            ->leftJoin('onsite_grades as og', function ($join) use ($userId) {
                $join->on('og.onsite_activity_id', '=', 'oa.id')
                    ->on('og.enrollment_id', '=', 'e.id')
                    ->where('og.user_id', '=', $userId);
            })
            ->where('e.user_id', $userId)
            ->select(
                'e.id as enrollment_id',
                'c.id as course_id',
                'c.title as course_title',
                'oa.id as activity_id',
                'oa.title as activity_title',
                'oa.activity_date',
                'oa.max_score',
                'og.id as grade_id',
                'og.score',
                'og.remarks'
            )
            ->orderBy('c.title')
            ->orderByDesc('oa.activity_date')
            ->orderByDesc('oa.id')
            ->get();

        $onsitePercentages = $onsiteScores
            ->filter(function ($activity) {
                return $activity->grade_id !== null
                    && $activity->score !== null
                    && (float) $activity->max_score > 0;
            })
            ->map(function ($activity) {
                return (
                    (float) $activity->score
                    / (float) $activity->max_score
                ) * 100;
            });

        if ($onsitePercentages->isNotEmpty()) {
            $onsiteAverage = round($onsitePercentages->avg(), 2);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | OVERALL ASSESSMENT
    |--------------------------------------------------------------------------
    */
    if ($quizAverage !== null && $onsiteAverage !== null) {
        $overallAverage = round(
            ($quizAverage + $onsiteAverage) / 2,
            2
        );

        $overallStatus = $overallAverage >= 75
            ? 'Competent'
            : 'Not Yet Competent';
    }

    return view('student.grades', compact(
        'quizScores',
        'onsiteScores',
        'quizAverage',
        'onsiteAverage',
        'overallAverage',
        'overallStatus'
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

    
    

