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
use App\Models\QuizResult;
use App\Models\QuizAttemptHistory;
use Illuminate\Support\Carbon;

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
        'student' => \App\Models\Registration::where('user_id', $user->id)->exists()
            ? tap(redirect()->route('homepage'), function () {
                session()->keep(['success', 'info']);
            })
            : redirect()->route('registration.step1'),

            'trainer'     => redirect()->route('teacher'),
            'admin'       => redirect()->route('admin1'),
            'super_admin' => redirect()->route('superadmin.dashboard'),
            default       => $this->logoutInvalidUser(),
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

    // 1. Enrollments
    $enrollments = \App\Models\Enrollment_tbl::with('course.facility.resources')
        ->where('user_id', $userId)
        ->orderByDesc('created_at')
        ->get();

    $selectedId = request('course_id');

    $enrollment = $selectedId
        ? $enrollments->firstWhere('course_id', $selectedId)
        : (
            $enrollments->firstWhere('status', 'active')
            ?? $enrollments->first()
        );

    $courseIds = $enrollments->pluck('course_id')->unique()->values();

    // 2. Calculate completion progress and performance for every enrolled course.
    foreach ($enrollments as $item) {
        $quizIds = \App\Models\Quiz::where('course_id', $item->course_id)
            ->pluck('id');

        $results = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', $quizIds)
            ->get();

        $quizScores = $results->map(function ($result) {
            if (is_numeric($result->percentage ?? null)) {
                return max(0, min(100, (float) $result->percentage));
            }

            if (
                is_numeric($result->score ?? null) &&
                is_numeric($result->total_items ?? null) &&
                (float) $result->total_items > 0
            ) {
                return max(
                    0,
                    min(
                        100,
                        ((float) $result->score / (float) $result->total_items) * 100
                    )
                );
            }

            return null;
        })->filter(fn ($score) => $score !== null)->values();

        $quizAverage = $quizScores->isNotEmpty()
            ? round($quizScores->avg(), 2)
            : null;

        $onsiteAverageValue = DB::table('onsite_grades as og')
            ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
            ->where('og.user_id', $userId)
            ->where('og.enrollment_id', $item->id)
            ->where('oa.course_id', $item->course_id)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->selectRaw('AVG((og.score / oa.max_score) * 100) as average')
            ->value('average');

        $onsiteAverage = $onsiteAverageValue !== null
            ? round(max(0, min(100, (float) $onsiteAverageValue)), 2)
            : null;

        $item->quiz_average = $quizAverage;
        $item->onsite_average = $onsiteAverage;

        // A quiz is complete only when it has a passing result.
        $passedQuizIds = $results
            ->filter(fn ($result) => strtolower((string) $result->status) === 'passed')
            ->pluck('quiz_id')
            ->map(fn ($id) => (int) $id);

        // Include passing attempts stored in quiz_attempt_histories.
        if (
            $quizIds->isNotEmpty() &&
            \Illuminate\Support\Facades\Schema::hasTable('quiz_attempt_histories')
        ) {
            $historyPassedIds = DB::table('quiz_attempt_histories')
                ->where('user_id', $userId)
                ->whereIn('quiz_id', $quizIds)
                ->whereRaw('LOWER(status) = ?', ['passed'])
                ->pluck('quiz_id')
                ->map(fn ($id) => (int) $id);

            $passedQuizIds = $passedQuizIds->merge($historyPassedIds);
        }

        $completedQuizzes = $passedQuizIds->unique()->count();
        $totalQuizzes = $quizIds->count();

        $totalOnsiteActivities = DB::table('onsite_activities')
            ->where('course_id', $item->course_id)
            ->count();

        $completedOnsiteActivities = DB::table('onsite_grades as og')
            ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
            ->where('og.user_id', $userId)
            ->where('og.enrollment_id', $item->id)
            ->where('oa.course_id', $item->course_id)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->distinct('oa.id')
            ->count('oa.id');

        $totalAssessments = $totalQuizzes + $totalOnsiteActivities;
        $completedAssessments = $completedQuizzes + $completedOnsiteActivities;

        $item->total_quizzes = $totalQuizzes;
        $item->completed_quizzes = $completedQuizzes;
        $item->total_onsite_activities = $totalOnsiteActivities;
        $item->completed_onsite_activities = $completedOnsiteActivities;
        $item->total_assessments = $totalAssessments;
        $item->completed_assessments = $completedAssessments;

        $item->calculated_progress = $totalAssessments > 0
            ? round(($completedAssessments / $totalAssessments) * 100, 2)
            : 0;

        $item->calculated_progress = max(
            0,
            min(100, $item->calculated_progress)
        );
    }

    // 3. Selected course modules and quizzes
    if ($enrollment) {
        $modules = \App\Models\Module::where('course_id', $enrollment->course_id)
            ->where('is_active', 1)
            ->orderBy('unit_number')
            ->orderBy('order')
            ->get();

        $unitNumbers = $modules->pluck('unit_number')
            ->filter(fn ($unit) => $unit !== null)
            ->unique()
            ->sort()
            ->values();

        $completedModuleIds = \App\Models\ModuleCompletion::where('user_id', $userId)
            ->whereIn('module_id', $modules->pluck('id'))
            ->pluck('module_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $quizzes = \App\Models\Quiz::where('course_id', $enrollment->course_id)
            ->orderBy('created_at')
            ->get();

        $postTest = $quizzes->firstWhere('quiz_type', 'post_test');

        $quizResults = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->orderByDesc('created_at')
            ->get();
    } else {
        $modules = collect();
        $unitNumbers = collect();
        $completedModuleIds = [];
        $quizzes = collect();
        $postTest = null;
        $quizResults = collect();
    }

    // 4. Personal analytics
    $totalEnrolled = $enrollments->count();

    $totalCompleted = $enrollments
        ->where('status', 'completed')
        ->count();

    $avgProgress = $enrollments->isNotEmpty()
        ? round($enrollments->avg('calculated_progress'), 2)
        : 0;

    $allQuizIds = \App\Models\Quiz::whereIn('course_id', $courseIds)
        ->pluck('id');

    $allQuizResults = \App\Models\QuizResult::where('user_id', $userId)
        ->whereIn('quiz_id', $allQuizIds)
        ->get();

    $quizzesTaken = $allQuizResults->count();

    $quizzesPassed = $allQuizResults
        ->filter(fn ($result) => strtolower((string) $result->status) === 'passed')
        ->count();

    // 5. Deadlines
    $upcomingDeadlines = \App\Models\Deadline_tbl::where(function ($query) use ($courseIds) {
            $query->whereIn('course_id', $courseIds)
                ->orWhereNull('course_id');
        })
        ->whereDate('due_date', '>=', now()->toDateString())
        ->whereDate('due_date', '<=', now()->addDays(30)->toDateString())
        ->count();

    // 6. General announcements
    $announcements = \App\Models\Announcement::query()
        ->where('is_active', 1)
        ->where(function ($query) {
            $query->whereNull('publish_at')
                ->orWhere('publish_at', '<=', now());
        })
        ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        })
        ->where(function ($query) {
            $query->whereNull('audience')
                ->orWhereIn('audience', ['all', 'students', 'all_students']);
        })
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    // 6.1 Trainer announcements for the selected course
    $trainerAnnouncements = collect();

    if ($enrollment && $enrollment->course_id) {
        $trainerAnnouncements = \App\Models\TrainerAnnouncement::with([
                'course',
                'trainer',
            ])
            ->where('course_id', $enrollment->course_id)
            ->where('target_audience', 'all_students')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    // 7. Weekly progress
    $weekStart = now()->startOfWeek();
    $weekEnd = now()->endOfWeek();

    $dailyQuery = \App\Models\DailyProgress::where('user_id', $userId)
        ->whereBetween('date', [
            $weekStart->toDateString(),
            $weekEnd->toDateString(),
        ]);

    if ($enrollment) {
        $dailyQuery->where('enrollment_id', $enrollment->id);
    }

    $dailyProgress = $dailyQuery
        ->selectRaw('date, MAX(progress_percent) as progress_percent')
        ->groupBy('date')
        ->get()
        ->keyBy(fn ($row) => (string) $row->date);

    $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $weeklyData = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $weekStart->copy()->addDays($i)->toDateString();

        $weeklyData[] = isset($dailyProgress[$date])
            ? (float) $dailyProgress[$date]->progress_percent
            : 0;
    }

    // 8. Donut chart
    $notStarted = $enrollments->filter(
        fn ($item) => $item->calculated_progress <= 0
            && $item->status !== 'completed'
    )->count();

    $inProgress = $enrollments->filter(
        fn ($item) => $item->calculated_progress > 0
            && $item->status !== 'completed'
    )->count();

    $donutData = [$totalCompleted, $inProgress, $notStarted];

    // 9. Recent quiz activity
    $recentActivity = \App\Models\QuizResult::with('quiz')
        ->where('user_id', $userId)
        ->whereIn('quiz_id', $allQuizIds)
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    // 10. Onsite grades for enrolled courses
    $enrollmentIds = $enrollments->pluck('id')->all();

    $onsiteGrades = DB::table('onsite_grades as og')
        ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
        ->where('og.user_id', $userId)
        ->whereIn('og.enrollment_id', $enrollmentIds)
        ->select(
            'og.id',
            'og.user_id',
            'og.enrollment_id',
            'og.score',
            'og.remarks',
            'og.created_at',
            'og.updated_at',
            'oa.course_id',
            'oa.title as activity_title',
            'oa.description as activity_description',
            'oa.activity_date',
            'oa.max_score'
        )
        ->orderByDesc('oa.activity_date')
        ->orderByDesc('og.updated_at')
        ->get();

    // 11. Chart: Quiz performance trend
    $trendResults = $quizResults->sortBy('created_at')->values();

    $quizTrendLabels = $trendResults->map(
        fn ($result) => $result->created_at
            ? $result->created_at->format('M d')
            : 'Quiz'
    )->all();

    $quizTrendScores = $trendResults->map(function ($result) {
        if (is_numeric($result->percentage ?? null)) {
            return round(max(0, min(100, (float) $result->percentage)), 2);
        }

        if (
            is_numeric($result->score ?? null) &&
            is_numeric($result->total_items ?? null) &&
            (float) $result->total_items > 0
        ) {
            return round(
                max(
                    0,
                    min(
                        100,
                        ((float) $result->score / (float) $result->total_items) * 100
                    )
                ),
                2
            );
        }

        return null;
    })->all();

    // 12. Chart: Learning progress by unit
    $unitProgressLabels = [];
    $unitProgressValues = [];

    $competencyUnitLabels = [
        1 => 'Basic Competencies',
        2 => 'Common Competencies',
        3 => 'Core Competencies',
    ];

    foreach ($modules->groupBy('unit_number')->sortKeys() as $unit => $unitModules) {
        $unitKey = is_numeric($unit) ? (int) $unit : null;

        $unitProgressLabels[] = $unitKey !== null
            ? ($competencyUnitLabels[$unitKey] ?? 'Unit ' . $unitKey)
            : 'Unassigned unit';

        $completedCount = $unitModules->filter(
            fn ($module) => in_array((int) $module->id, $completedModuleIds, true)
        )->count();

        $unitProgressValues[] = $unitModules->isNotEmpty()
            ? round(($completedCount / $unitModules->count()) * 100, 2)
            : 0;
    }

    // 13. Chart: Quiz vs onsite performance
    $validQuizScores = collect($quizTrendScores)
        ->filter(fn ($score) => $score !== null)
        ->values();

    $selectedOnsiteGrades = $onsiteGrades->filter(
        fn ($grade) => $enrollment
            && (int) $grade->course_id === (int) $enrollment->course_id
            && is_numeric($grade->score)
            && is_numeric($grade->max_score)
            && (float) $grade->max_score > 0
    );

    $validOnsiteScores = $selectedOnsiteGrades->map(
        fn ($grade) => max(
            0,
            min(100, ((float) $grade->score / (float) $grade->max_score) * 100)
        )
    )->values();

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

    // 14. Chart: Assessment results summary
    $assessmentPassed = $allQuizResults->filter(
        fn ($result) => strtolower((string) $result->status) === 'passed'
    )->count();

    $assessmentFailed = $allQuizResults->filter(
        fn ($result) => strtolower((string) $result->status) === 'failed'
    )->count();

    $assessmentLabels = ['Passed', 'Failed'];

    // 14.1 Training facility Google Maps link saved by Admin.
    // Keep the original URL for the external button and generate a separate
    // embed URL for the student homepage iframe.
    $facility = $enrollment?->course?->facility;

    $facilityResources = $facility?->resources ?? collect();

    $facilityMap = $facilityResources
        ->filter(function ($resource) {
            $url = trim((string) ($resource->url ?? ''));

            if ($url === '') {
                return false;
            }

            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            return in_array($scheme, ['http', 'https'], true)
                && in_array($host, [
                    'google.com',
                    'www.google.com',
                    'maps.google.com',
                    'maps.app.goo.gl',
                    'goo.gl',
                ], true);
        })
        ->sortByDesc('updated_at')
        ->first();

    $facilityMapEmbedUrl = null;

    if ($facilityMap) {
        $originalUrl = trim((string) $facilityMap->url);

        $host = strtolower((string) parse_url($originalUrl, PHP_URL_HOST));
        $path = (string) parse_url($originalUrl, PHP_URL_PATH);
        $query = (string) parse_url($originalUrl, PHP_URL_QUERY);

        $googleHosts = [
            'google.com',
            'www.google.com',
            'maps.google.com',
        ];

        // Preserve a direct Google Maps embed URL.
        if (
            in_array($host, $googleHosts, true)
            && str_contains($path, '/maps/embed')
        ) {
            $facilityMapEmbedUrl = $originalUrl;
        } else {
            $place = null;

            // Extract a place or search query from a regular Maps URL.
            if (
                in_array($host, $googleHosts, true)
                && str_contains($path, '/maps')
            ) {
                parse_str($query, $params);

                $place = $params['q'] ?? $params['query'] ?? null;

                if (
                    !$place
                    && preg_match(
                        '~/(?:maps/)?place/([^/]+)~',
                        $path,
                        $matches
                    )
                ) {
                    $place = rawurldecode($matches[1]);
                }
            }

            // Short share links fall back to the facility address.
            if (!$place) {
                $place = $facility?->address;
            }

            if (is_string($place) && trim($place) !== '') {
                $facilityMapEmbedUrl =
                    'https://www.google.com/maps?q='
                    . rawurlencode(trim($place))
                    . '&output=embed';
            }
        }
    }

    // 15. Certificates: use calculated quiz + onsite progress
    $studentCertificates = \App\Models\Certificate::where('user_id', $userId)
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($certificate) use ($enrollments) {
            $certificateEnrollment = $enrollments->firstWhere(
                'course_id',
                $certificate->course_id
            );

            $quizAverage = $certificateEnrollment->quiz_average ?? null;
            $onsiteAverage = $certificateEnrollment->onsite_average ?? null;

            $overallPerformance = (
                $quizAverage !== null &&
                $onsiteAverage !== null
            )
                ? round(((float) $quizAverage + (float) $onsiteAverage) / 2, 2)
                : null;

            $progress = (float) ($certificateEnrollment->calculated_progress ?? 0);
            $isCompetent = $overallPerformance !== null && $overallPerformance >= 75;

            $isReleased = in_array(
                strtolower((string) $certificate->status),
                ['released', 'claimed'],
                true
            );

            $certificate->progress_display = round($progress);
            $certificate->performance_display = $overallPerformance;
            $certificate->competency_display = $overallPerformance === null
                ? 'Pending Assessment'
                : ($isCompetent ? 'Competent' : 'Not Yet Competent');

            $certificate->is_downloadable = $isCompetent && $isReleased;

            return $certificate;
        });

    return view('student.homepage', compact(
        'enrollments',
        'enrollment',
        'facilityMap',
        'facilityMapEmbedUrl',
        'upcomingDeadlines',
        'announcements',
        'trainerAnnouncements',
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
    // Eager-load relationships & counts, then paginate 9 items per page
    $courses = \App\Models\Course_tbl::with(['facility', 'trainer'])
        ->withCount(['enrollments', 'modules', 'quizzes'])
        ->paginate(9);
    // All courses list (used for multi-select dropdowns in modals)
    $allCourses = \App\Models\Course_tbl::all();
    $trainers      = \App\Models\User_tbl::where('role', 'trainer')->get();
    $trainees      = \App\Models\User_tbl::where('role', 'student')->paginate(10, ['*'], 'trainee_page');
    $trainersList = \App\Models\User_tbl::where('role', 'trainer')
    ->orderBy('lastname', 'asc')
    ->orderBy('firstname', 'asc')
    ->paginate(10, ['*'], 'trainer_page')
    ->appends($request->except('trainer_page'));
    $announcements = \App\Models\Announcement::latest()->paginate(10, ['*'], 'announcement_page');
    $registrations = \App\Models\Registration::latest()
        ->paginate(10, ['*'], 'reg_page')
        ->appends($request->except('reg_page'));
    $facilities = \App\Models\Facility::with([
    'courses',
    'resources' => function ($query) {
        $query->latest();
    },
])->get();
    // ── 1. FETCH CERTIFICATES FOR TABLE & STATS ──────────────────────────────
    $certificates  = \App\Models\Certificate::with(['user', 'course'])->latest()->get();
// ── 2. FETCH ENROLLED TRAINEES FOR THE ISSUE MODAL DROPDOWN ──
$eligibleTrainees = \DB::table('enrollment_tbls as e')
    ->join('user_tbls as u', 'u.id', '=', 'e.user_id')
    ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
    ->where('u.role', 'student')
    ->whereIn('e.status', ['active', 'completed'])
    ->select(
        'u.id as user_id',
        'u.firstname',
        'u.lastname',
        'e.id as enrollment_id',
        'e.course_id',
        'e.status as enrollment_status',
        'e.progress as stored_progress',
        'c.title as course_title',
        'c.training_id'
    )
    ->orderBy('u.lastname', 'asc')
    ->orderBy('u.firstname', 'asc')
    ->get()
    ->map(function ($trainee) {
        /*
        |--------------------------------------------------------------------------
        | Quiz average for this trainee and course
        |--------------------------------------------------------------------------
        */
        $quizScores = \DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->where('qr.user_id', $trainee->user_id)
            ->where('q.course_id', $trainee->course_id)
            ->selectRaw('
                COALESCE(
                    qr.percentage,
                    (qr.score / NULLIF(qr.total_items, 0)) * 100
                ) as percentage
            ')
            ->get()
            ->pluck('percentage')
            ->filter(fn ($score) => $score !== null)
            ->map(fn ($score) => (float) $score);

        $quizAverage = $quizScores->isNotEmpty()
            ? $quizScores->avg()
            : null;

        /*
        |--------------------------------------------------------------------------
        | Onsite average: average individual activity percentages
        |--------------------------------------------------------------------------
        */
        $onsiteScores = \DB::table('onsite_grades as og')
            ->join(
                'onsite_activities as oa',
                'oa.id',
                '=',
                'og.onsite_activity_id'
            )
            ->where('og.user_id', $trainee->user_id)
            ->where('og.enrollment_id', $trainee->enrollment_id)
            ->where('oa.course_id', $trainee->course_id)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->select('og.score', 'oa.max_score')
            ->get();

        $onsitePercentages = $onsiteScores->map(function ($grade) {
            return min(
                100,
                max(
                    0,
                    ((float) $grade->score / (float) $grade->max_score) * 100
                )
            );
        });

        $onsiteAverage = $onsitePercentages->isNotEmpty()
            ? $onsitePercentages->avg()
            : null;

        /*
        |--------------------------------------------------------------------------
        | Competency = (Quiz Average + Onsite Average) / 2
        |--------------------------------------------------------------------------
        | Missing either category means Not Assessed, not zero.
        */
        $trainee->quiz_average = $quizAverage !== null
            ? round($quizAverage, 2)
            : null;

        $trainee->onsite_average = $onsiteAverage !== null
            ? round($onsiteAverage, 2)
            : null;

        $trainee->progress = (
            $quizAverage !== null && $onsiteAverage !== null
        )
            ? round(($quizAverage + $onsiteAverage) / 2, 2)
            : null;

        $trainee->competency_remarks = $trainee->progress === null
            ? 'Not Assessed'
            : ($trainee->progress >= 75
                ? 'Competent'
                : 'Not Yet Competent');

        // Maintain compatibility with the existing Blade dropdown.
        $trainee->id = $trainee->user_id;

        return $trainee;
    });
// ============================================================
// NEW DASHBOARD GRAPHS
// ============================================================
// GRAPH 1: Enrollment by Course
$coursesWithTrainees = \App\Models\Course_tbl::withCount('enrollments')
    ->orderByDesc('enrollments_count')
    ->orderBy('title')
    ->get();
$traineeCourseLabels = $coursesWithTrainees
    ->pluck('title')
    ->toArray();
$traineeCourseCounts = $coursesWithTrainees
    ->pluck('enrollments_count')
    ->map(fn ($count) => (int) $count)
    ->toArray();
// GRAPH 2: Monthly Enrollment Trend — Last 6 Months
$monthlyEnrollmentLabels = [];
$monthlyEnrollmentCounts = [];
for ($i = 5; $i >= 0; $i--) {
    $monthDate = now()->startOfMonth()->subMonths($i);
    $monthlyEnrollmentLabels[] = $monthDate->format('M Y');
    $monthlyEnrollmentCounts[] = (int) \DB::table('enrollment_tbls')
        ->whereYear('enrolled_at', $monthDate->year)
        ->whereMonth('enrolled_at', $monthDate->month)
        ->count();
}
// GRAPH 3: Quiz Performance by Course
$quizPerformance = \DB::table('quiz_results as qr')
    ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
    ->join('course_tbls as c', 'c.id', '=', 'q.course_id')
    ->select(
        'c.id as course_id',
        'c.title as course_title',
        \DB::raw('
            AVG(
                COALESCE(
                    qr.percentage,
                    (qr.score / NULLIF(qr.total_items, 0)) * 100
                )
            ) as average_percentage
        ')
    )
    ->groupBy('c.id', 'c.title')
    ->orderBy('c.title')
    ->get();
$quizPerformanceLabels = $quizPerformance
    ->pluck('course_title')
    ->toArray();
$quizPerformanceScores = $quizPerformance
    ->map(fn ($row) => round((float) $row->average_percentage, 2))
    ->toArray();
// GRAPH 4: Quiz vs. Onsite Performance by Course
$onsitePerformance = \DB::table('onsite_grades as og')
    ->join(
        'onsite_activities as oa',
        'oa.id',
        '=',
        'og.onsite_activity_id'
    )
    ->join('course_tbls as c', 'c.id', '=', 'oa.course_id')
    ->whereNotNull('og.score')
    ->where('oa.max_score', '>', 0)
    ->select(
        'c.id as course_id',
        \DB::raw('
            AVG(
                (og.score / NULLIF(oa.max_score, 0)) * 100
            ) as average_percentage
        ')
    )
    ->groupBy('c.id')
    ->get()
    ->keyBy('course_id');
$quizAveragesByCourse = $quizPerformance->keyBy('course_id');
$performanceCourses = \App\Models\Course_tbl::orderBy('title')->get();
$performanceCourseLabels = $performanceCourses
    ->pluck('title')
    ->toArray();
$courseQuizAverages = [];
$courseOnsiteAverages = [];
foreach ($performanceCourses as $course) {
    $quizAverage = $quizAveragesByCourse->get($course->id);
    $onsiteAverage = $onsitePerformance->get($course->id);
    $courseQuizAverages[] = $quizAverage
        ? round((float) $quizAverage->average_percentage, 2)
        : null;
    $courseOnsiteAverages[] = $onsiteAverage
        ? round((float) $onsiteAverage->average_percentage, 2)
        : null;
}
// GRAPH 5: Competency Distribution
// Overall = (Quiz Average + Onsite Average) / 2
// >= 75% = Competent; < 75% = Not Yet Competent
// Missing either category = Not Assessed.

// Average quiz percentages by student and course.
$quizByStudentCourse = \DB::table('quiz_results as qr')
    ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
    ->select(
        'qr.user_id',
        'q.course_id',
        \DB::raw('
            AVG(
                COALESCE(
                    qr.percentage,
                    (qr.score / NULLIF(qr.total_items, 0)) * 100
                )
            ) as average_percentage
        ')
    )
    ->groupBy('qr.user_id', 'q.course_id')
    ->get()
    ->keyBy(fn ($row) => $row->user_id . '-' . $row->course_id);

// Average each recorded onsite activity percentage per enrollment.
$onsiteByStudentCourse = \DB::table('onsite_grades as og')
    ->join(
        'onsite_activities as oa',
        'oa.id',
        '=',
        'og.onsite_activity_id'
    )
    ->select(
        'og.user_id',
        'og.enrollment_id',
        'oa.course_id',
        \DB::raw('
            AVG(
                LEAST(
                    100,
                    GREATEST(0, (og.score / NULLIF(oa.max_score, 0)) * 100)
                )
            ) as average_percentage
        ')
    )
    ->whereNotNull('og.score')
    ->where('oa.max_score', '>', 0)
    ->groupBy('og.user_id', 'og.enrollment_id', 'oa.course_id')
    ->get()
    ->keyBy(fn ($row) =>
        $row->user_id . '-' . $row->course_id . '-' . $row->enrollment_id
    );

$competencyCounts = [
    'Competent' => 0,
    'Not Yet Competent' => 0,
    'Not Assessed' => 0,
];

$activeEnrollments = \DB::table('enrollment_tbls')
    ->whereIn('status', ['active', 'completed'])
    ->select('id', 'user_id', 'course_id')
    ->get();

$competentCountsByCourse = [];
$assessedCountsByCourse = [];

foreach ($activeEnrollments as $enrollment) {
    $key = $enrollment->user_id . '-' . $enrollment->course_id;
    $quizResult = $quizByStudentCourse->get($key);

    $onsiteKey = $enrollment->user_id
        . '-' . $enrollment->course_id
        . '-' . $enrollment->id;
    $onsiteResult = $onsiteByStudentCourse->get($onsiteKey);

    if (
        !$quizResult ||
        !$onsiteResult ||
        $quizResult->average_percentage === null ||
        $onsiteResult->average_percentage === null
    ) {
        $competencyCounts['Not Assessed']++;
        continue;
    }

    $overallScore = (
        (float) $quizResult->average_percentage +
        (float) $onsiteResult->average_percentage
    ) / 2;

    $courseId = (string) $enrollment->course_id;
    $assessedCountsByCourse[$courseId] =
        ($assessedCountsByCourse[$courseId] ?? 0) + 1;

    if ($overallScore >= 75) {
        $competencyCounts['Competent']++;
        $competentCountsByCourse[$courseId] =
            ($competentCountsByCourse[$courseId] ?? 0) + 1;
    } else {
        $competencyCounts['Not Yet Competent']++;
    }
}

$competencyLabels = array_keys($competencyCounts);
$competencyStudentCounts = array_values($competencyCounts);
// GRAPH 6: Competency Rate by Course (%).
// Rate = competent trainees / assessed trainees * 100.
$competencyCourses = \App\Models\Course_tbl::orderBy('title')->get();
$competencyRateLabels = $competencyCourses->pluck('title')->toArray();
$competencyRates = $competencyCourses
    ->map(function ($course) use ($competentCountsByCourse, $assessedCountsByCourse) {
        $courseId = (string) $course->id;
        $competent = (int) ($competentCountsByCourse[$courseId] ?? 0);
        $assessed = (int) ($assessedCountsByCourse[$courseId] ?? 0);
        return $assessed > 0
            ? round(($competent / $assessed) * 100, 2)
            : 0;
    })
    ->toArray();
// Names used by the dashboard chart. Keep the assessed denominator separate
// from eligible enrollments used by the course-completion chart.
$competentCourseLabels = $competencyRateLabels;
$competentCourseCounts = $competencyCourses
    ->map(fn ($course) => (int) ($competentCountsByCourse[(string) $course->id] ?? 0))
    ->toArray();
$assessedCourseCounts = $competencyCourses
    ->map(fn ($course) => (int) ($assessedCountsByCourse[(string) $course->id] ?? 0))
    ->toArray();
// GRAPH 6: Course Completion Rate
// Completed enrollments / active and completed enrollments * 100
$completionData = \DB::table('enrollment_tbls as e')
    ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
    ->whereIn('e.status', ['active', 'completed'])
    ->select(
        'c.id as course_id',
        'c.title as course_title',
        \DB::raw('COUNT(*) as eligible_enrollments'),
        \DB::raw("
            SUM(
                CASE WHEN e.status = 'completed'
                THEN 1 ELSE 0 END
            ) as completed_enrollments
        ")
    )
    ->groupBy('c.id', 'c.title')
    ->orderBy('c.title')
    ->get();
$completionCourseLabels = $completionData
    ->pluck('course_title')
    ->toArray();
$courseCompletionRates = $completionData
    ->map(function ($row) {
        $total = (int) $row->eligible_enrollments;
        $completed = (int) $row->completed_enrollments;
        return $total > 0
            ? round(($completed / $total) * 100, 2)
            : 0;
    })
    ->toArray();
$courseCompletedCounts = $completionData
    ->pluck('completed_enrollments')
    ->map(fn ($count) => (int) $count)
    ->toArray();
$courseEligibleCounts = $completionData
    ->pluck('eligible_enrollments')
    ->map(fn ($count) => (int) $count)
    ->toArray();
    // ============================================================
    // GRAPHS 7–10: REGISTRATION DEMOGRAPHICS
    // ============================================================
    // GRAPH 7: Registrations by Barangay
    $registrationsByBarangay = \App\Models\Registration::query()
        ->whereNotNull('address_barangay')
        ->where('address_barangay', '!=', '')
        ->selectRaw('address_barangay, COUNT(*) as total')
        ->groupBy('address_barangay')
        ->orderByDesc('total')
        ->get();
    $barangayLabels = $registrationsByBarangay->pluck('address_barangay')->toArray();
    $barangayCounts = $registrationsByBarangay
        ->pluck('total')
        ->map(fn ($count) => (int) $count)
        ->toArray();
    // GRAPH 8: Applicant Employment Status
    $employmentData = \App\Models\Registration::query()
        ->whereNotNull('employment_status')
        ->where('employment_status', '!=', '')
        ->selectRaw('employment_status, COUNT(*) as total')
        ->groupBy('employment_status')
        ->orderByDesc('total')
        ->get();
    $employmentLabels = $employmentData->pluck('employment_status')->toArray();
    $employmentCounts = $employmentData
        ->pluck('total')
        ->map(fn ($count) => (int) $count)
        ->toArray();
    // GRAPH 9: Registrations by Sex
    $sexData = \App\Models\Registration::query()
        ->whereNotNull('sex')
        ->where('sex', '!=', '')
        ->selectRaw('sex, COUNT(*) as total')
        ->groupBy('sex')
        ->orderByDesc('total')
        ->get();
    $sexLabels = $sexData->pluck('sex')->toArray();
    $sexCounts = $sexData
        ->pluck('total')
        ->map(fn ($count) => (int) $count)
        ->toArray();
    // GRAPH 10: Applicant Age Group Distribution
    $ageGroups = [
        'Under 18' => [0, 17],
        '18–24'    => [18, 24],
        '25–34'    => [25, 34],
        '35–44'    => [35, 44],
        '45–54'    => [45, 54],
        '55+'      => [55, 150],
    ];
    $ageGroupLabels = [];
    $ageGroupCounts = [];
    foreach ($ageGroups as $label => [$minAge, $maxAge]) {
        $ageGroupLabels[] = $label;
        $ageGroupCounts[] = (int) \App\Models\Registration::query()
            ->whereNotNull('age')
            ->whereBetween('age', [$minAge, $maxAge])
            ->count();
    }

    $registrationCourses = \Illuminate\Support\Facades\DB::table('registrations')
    ->whereNotNull('course_name')
    ->where('course_name', '!=', '')
    ->distinct()
    ->orderBy('course_name')
    ->pluck('course_name');
return view('admin.admin1', compact(
    // Existing dashboard data
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
    'currentView',
    // Graph 1: Enrollment by Course
    'traineeCourseLabels',
    'traineeCourseCounts',
    // Graph 2: Monthly Enrollment Trend
    'monthlyEnrollmentLabels',
    'monthlyEnrollmentCounts',
    // Graph 3: Quiz Performance by Course
    'quizPerformanceLabels',
    'quizPerformanceScores',
    // Graph 4: Quiz vs. Onsite Performance
    'performanceCourseLabels',
    'courseQuizAverages',
    'courseOnsiteAverages',
    // Graph 5: Competency Distribution
    'competencyLabels',
    'competencyStudentCounts',
    // Competent students by course (combined average >= 75%)
    'competencyRateLabels',
    'competencyRates',
    'competentCourseLabels',
    'competentCourseCounts',
    'assessedCourseCounts',
    // Graph 6: Course Completion Rate
    'completionCourseLabels',
    'courseCompletionRates',
    'courseCompletedCounts',
    'courseEligibleCounts',
    // Graph 7: Registrations by Barangay
    'barangayLabels',
    'barangayCounts',
    // Graph 8: Applicant Employment Status
    'employmentLabels',
    'employmentCounts',
    // Graph 9: Registrations by Sex
    'sexLabels',
    'sexCounts',
    // Graph 10: Applicant Age Group Distribution
    'ageGroupLabels',
    'ageGroupCounts',

    // Registration course filter
    'registrationCourses'

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
        'id'           => 'nullable|integer|min:1',
        'name'         => 'required|string|max:255',
        'address'      => 'required|string|max:255',
        'course_ids'   => 'nullable|array',
        'course_ids.*' => [
            'required',
            'integer',
            'distinct',
            'exists:course_tbls,id',
        ],
    ]);

    try {
        $facility = \Illuminate\Support\Facades\DB::transaction(
            function () use ($validated, $request) {

                // Update an existing facility or create a new one.
                if ($request->filled('id')) {
                    $facility = \App\Models\Facility::findOrFail(
                        $validated['id']
                    );

                    $facility->update([
                        'name'    => $validated['name'],
                        'address' => $validated['address'],
                    ]);
                } else {
                    $facility = \App\Models\Facility::create([
                        'name'    => $validated['name'],
                        'address' => $validated['address'],
                    ]);
                }

                // Get all selected courses.
                $courseIds = $validated['course_ids'] ?? [];

                // Remove existing assignments from this facility
                // if those courses are no longer selected.
                \App\Models\Course_tbl::where(
                    'facility_id',
                    $facility->id
                )
                    ->when(
                        !empty($courseIds),
                        fn ($query) => $query->whereNotIn(
                            'id',
                            $courseIds
                        ),
                        fn ($query) => $query
                    )
                    ->update([
                        'facility_id' => null,
                    ]);

                // Assign selected courses to this facility.
                // If a course belongs to another facility,
                // its facility_id is updated to this facility.
                if (!empty($courseIds)) {
                    \App\Models\Course_tbl::whereIn('id', $courseIds)
                        ->update([
                            'facility_id' => $facility->id,
                        ]);
                }

                return $facility->fresh();
            }
        );

        return response()->json([
            'success'  => true,
            'message'  => 'Facility details saved successfully!',
            'facility' => $facility,
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'The facility you are trying to edit was not found.',
        ], 404);

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error(
            'Error saving facility: ' . $e->getMessage(),
            ['exception' => $e]
        );

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while saving the facility.',
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
        $validated = $request->validate([
            'course_code' => 'nullable|string|max:50',
            'training_id' => 'nullable|string|max:100',
            'title'       => 'required|string|max:255',
            'duration'    => 'required|integer|min:1',
            'slots'       => 'required|integer|min:0',
            'sector'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'objectives'  => 'nullable|string',
            'schedule'    => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'thumbnail'   => 'nullable|string|max:255',
            'status'      => 'nullable|string|in:active,inactive',
            'trainer_id'  => 'nullable|integer|exists:user_tbls,id',
        ]);

        // Generate the Training ID automatically if none was submitted.
        $trainingId = trim($validated['training_id'] ?? '');

        if ($trainingId === '') {
            $trainingId = $this->generateTrainingId(
                $validated['title']
            );
        }

        // Prevent duplicate Training IDs.
        if (Course_tbl::where('training_id', $trainingId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This Training ID already exists. Please try again.',
            ], 422);
        }

        $course = Course_tbl::create([
            'course_code' => $validated['course_code'] ?? null,
            'training_id' => $trainingId,
            'title'       => $validated['title'],
            'duration'    => $validated['duration'],
            'slots'       => $validated['slots'],
            'sector'      => $validated['sector'] ?? 'General',
            'description' => $validated['description'] ?? null,
            'objectives'  => $validated['objectives'] ?? null,
            'schedule'    => $validated['schedule'] ?? null,
            'location'    => $validated['location'] ?? null,
            'thumbnail'   => $validated['thumbnail'] ?? null,
            'status'      => $validated['status'] ?? 'active',
            'trainer_id'  => $validated['trainer_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully!',
            'course'  => $course,
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error(
            'Failed to create course: ' . $e->getMessage()
        );

        return response()->json([
            'success' => false,
            'message' => 'Failed to create course. Please check the submitted information.',
        ], 500);
    }
}




public function duplicateCourse(\App\Models\Course_tbl $course)
{
    $copy = \Illuminate\Support\Facades\DB::transaction(
        function () use ($course) {

            // 1. Duplicate the course record.
            $newCourse = $course->replicate();

            $baseCode = ($course->course_code ?: 'COURSE') . '-COPY';
            $newCode = $baseCode;
            $counter = 1;

            while (
                \App\Models\Course_tbl::where(
                    'course_code',
                    $newCode
                )->exists()
            ) {
                $newCode = $baseCode . '-' . $counter++;
            }

            $newCourse->course_code = $newCode;
            $newCourse->training_id = $this->generateTrainingId(
                $course->title
            );
            $newCourse->trainer_id = null;
            $newCourse->status = 'inactive';
            $newCourse->save();

            // 2. Duplicate modules and map old IDs to new IDs.
            $moduleIdMap = [];

            $modules = \App\Models\Module::where(
                'course_id',
                $course->id
            )->orderBy('unit_number')
             ->orderBy('order')
             ->get();

            foreach ($modules as $module) {
                $newModule = $module->replicate();
                $newModule->course_id = $newCourse->id;
                $newModule->save();

                $moduleIdMap[$module->id] = $newModule->id;
            }

            // 3. Duplicate quizzes and their questions.
            $quizzes = \App\Models\Quiz::where(
                'course_id',
                $course->id
            )->get();

            foreach ($quizzes as $quiz) {
                $newQuiz = $quiz->replicate();
                $newQuiz->course_id = $newCourse->id;

                // Link the quiz to its copied module.
                $newQuiz->module_id = $quiz->module_id !== null
                    ? ($moduleIdMap[$quiz->module_id] ?? null)
                    : null;

                $newQuiz->save();

                // Copy questions to the new quiz.
                $questions = \App\Models\QuizQuestion::where(
                    'quiz_id',
                    $quiz->id
                )->get();

                foreach ($questions as $question) {
                    $newQuestion = $question->replicate();
                    $newQuestion->quiz_id = $newQuiz->id;
                    $newQuestion->save();
                }
            }

            return [
                'course' => $newCourse,
                'modules_count' => count($moduleIdMap),
                'quizzes_count' => $quizzes->count(),
            ];
        }
    );

    return response()->json([
        'success' => true,
        'message' => 'Course, modules, quizzes, and quiz questions duplicated successfully.',
        'course_id' => $copy['course']->id,
        'course_code' => $copy['course']->course_code,
        'training_id' => $copy['course']->training_id,
        'modules_copied' => $copy['modules_count'],
        'quizzes_copied' => $copy['quizzes_count'],
    ]);
}






    


public function updateCourse(Request $request, $id)
{
    $course = Course_tbl::findOrFail($id);

    $validated = $request->validate([
        'course_code'     => 'required|string|max:50',
        'training_id'     => 'nullable|string|max:100',
        'title'           => 'required|string|max:255',
        'duration'        => 'required|integer|min:1',
        'slots'           => 'required|integer|min:0',
        'status'          => 'required|string|in:active,inactive',

        'start_date'      => 'nullable|date',
        'end_date'        => 'nullable|date|after_or_equal:start_date',
        'start_time'      => 'required|date_format:H:i',
        'end_time'        => 'required|date_format:H:i|after:start_time',

        'training_days'   => 'required|array|min:1',
        'training_days.*' => [
            'required',
            'string',
            'distinct',
            'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ],
    ]);

    // Keep the submitted ID, otherwise preserve the existing one.
    $trainingId = trim($validated['training_id'] ?? '');

    if ($trainingId === '') {
        $trainingId = trim($course->training_id ?? '');
    }

    // Generate an ID if this older course has none.
    if ($trainingId === '') {
        $trainingId = $this->generateTrainingId(
            $validated['title']
        );
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

    // Normalize selected weekdays.
    $trainingDays = array_values(array_unique(
        $validated['training_days']
    ));

    $course->update([
        'course_code'   => $validated['course_code'],
        'training_id'   => $trainingId,
        'title'         => $validated['title'],
        'duration'      => $validated['duration'],
        'slots'         => $validated['slots'],
        'status'        => $validated['status'],

        // Preserve the training schedule functionality.
        'start_date'    => $validated['start_date'] ?? null,
        'end_date'      => $validated['end_date'] ?? null,
        'start_time'    => $validated['start_time'],
        'end_time'      => $validated['end_time'],
        'training_days' => $trainingDays,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Course updated successfully!',
        'course'  => $course->fresh(),
    ]);
}

private function generateTrainingId(string $title): string
{
    $normalizedTitle = strtolower(trim($title));

    // Preserve the existing course ID naming conventions.
    $prefixes = [
        'baking (cake, bread, pastries)' => 'BKB',
        'computer literacy' => 'CL',
        'candle making' => 'CM',
    ];

    if (isset($prefixes[$normalizedTitle])) {
        $prefix = $prefixes[$normalizedTitle];
    } else {
        // Generate a prefix from the first letter of each word.
        $words = preg_split('/\s+/', preg_replace(
            '/[^a-zA-Z0-9 ]/',
            '',
            trim($title)
        ));

        $prefix = '';

        foreach ($words as $word) {
            if ($word !== '') {
                $prefix .= strtoupper(substr($word, 0, 1));
            }
        }

        $prefix = substr($prefix, 0, 10);

        if ($prefix === '') {
            $prefix = 'TRN';
        }
    }

    // Find existing IDs for this prefix.
    $existingIds = Course_tbl::where(
        'training_id',
        'like',
        "LED-{$prefix}-%"
    )->pluck('training_id');

    $highestNumber = 0;

    foreach ($existingIds as $existingId) {
        if (preg_match(
            '/^LED-' . preg_quote($prefix, '/') . '-(\d+)$/',
            $existingId,
            $matches
        )) {
            $highestNumber = max(
                $highestNumber,
                (int) $matches[1]
            );
        }
    }

    // Generate the next unused ID.
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

    $course = Course_tbl::where('trainer_id', $trainer->id)->first();
    $today = now()->toDateString();

    /*
    |--------------------------------------------------------------------------
    | Attendance Taken Today
    |--------------------------------------------------------------------------
    */
    $attendanceTakenToday = false;

    if ($course) {
        $activeEnrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->pluck('id');

        $attendanceTakenToday = \App\Models\Attendance::whereIn(
            'enrollment_id',
            $activeEnrollmentIds
        )
            ->where('date', $today)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Weekly Average Progress
    |--------------------------------------------------------------------------
    | This chart uses the progress snapshots already saved in daily_progress.
    | New snapshots should be saved using the same assessment-completion formula
    | as the per-trainee course progress below.
    |--------------------------------------------------------------------------
    */
    $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endOfWeek = now()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $weeklyProgressData = [];

    if ($course) {
        $enrollmentIds = \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->pluck('id');

        for (
            $day = $startOfWeek->copy();
            $day->lte($endOfWeek);
            $day->addDay()
        ) {
            if ($day->gt(now())) {
                $weeklyProgressData[] = null;
                continue;
            }

            $avg = \App\Models\DailyProgress::whereIn('enrollment_id', $enrollmentIds)
                ->where('date', $day->toDateString())
                ->avg('progress_percent');

            $weeklyProgressData[] = $avg !== null
                ? round((float) $avg, 2)
                : 0;
        }
    } else {
        $weeklyProgressData = array_fill(0, 7, null);
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Stat Cards
    |--------------------------------------------------------------------------
    */
    $totalTrainees = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->where('status', 'active')
            ->count()
        : 0;

    $monthlyEnrollment = $course
        ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count()
        : 0;

    $urgentAssessments = $course
        ? \App\Models\Deadline_tbl::where('course_id', $course->id)
            ->whereDate('due_date', '>=', $today)
            ->whereDate('due_date', '<=', now()->addDays(3)->toDateString())
            ->count()
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Enrollment Trend Chart: Last 6 Months
    |--------------------------------------------------------------------------
    */
    $chartEnrollmentLabels = [];
    $chartEnrollmentValues = [];

    for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
        $month = now()->copy()->subMonths($monthOffset);
        $chartEnrollmentLabels[] = $month->format('M Y');

        $chartEnrollmentValues[] = $course
            ? \App\Models\Enrollment_tbl::where('course_id', $course->id)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count()
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Course-Level Quiz and Onsite Averages
    |--------------------------------------------------------------------------
    */
    $quizAverageByCourse = null;
    $onsiteAverageByCourse = null;

    if ($course) {
        $quizAverageByCourse = DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->where('q.course_id', $course->id)
            ->whereNotNull('qr.percentage')
            ->avg('qr.percentage');

        $onsiteAverageByCourse = DB::table('onsite_grades as og')
            ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
            ->where('oa.course_id', $course->id)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->selectRaw('AVG((og.score / oa.max_score) * 100) as average_score')
            ->value('average_score');
    }

    $chartAssessmentLabels = [];
    $chartQuizAverages = [];
    $chartOnsiteAverages = [];

    if ($course) {
        $chartAssessmentLabels[] = $course->title;
        $chartQuizAverages[] = $quizAverageByCourse !== null
            ? round(max(0, min(100, (float) $quizAverageByCourse)), 2)
            : 0;
        $chartOnsiteAverages[] = $onsiteAverageByCourse !== null
            ? round(max(0, min(100, (float) $onsiteAverageByCourse)), 2)
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Assessment Rows: Score, Competency, and Actual Course Completion
    |--------------------------------------------------------------------------
    */
    $assessmentRows = collect();

    if ($course) {
        $courseQuizIds = DB::table('quizzes')
            ->where('course_id', $course->id)
            ->pluck('id');

        $courseActivities = DB::table('onsite_activities')
            ->where('course_id', $course->id)
            ->get(['id', 'max_score']);

        $totalQuizzes = $courseQuizIds->count();
        $totalOnsiteActivities = $courseActivities->count();
        $totalRequiredAssessments = $totalQuizzes + $totalOnsiteActivities;

        $assessmentRows = DB::table('enrollment_tbls as e')
            ->leftJoin('user_tbls as u', 'u.id', '=', 'e.user_id')
            ->where('e.course_id', $course->id)
            ->where('e.status', '!=', 'dropped')
            ->select(
                'e.id as enrollment_id',
                'e.user_id',
                'e.course_id',
                'e.status as enrollment_status',
                'e.progress',
                'u.firstname',
                'u.lastname'
            )
            ->get()
            ->map(function ($enrollment) use (
                $course,
                $courseQuizIds,
                $courseActivities,
                $totalQuizzes,
                $totalOnsiteActivities,
                $totalRequiredAssessments
            ) {
                // Quiz results for this student in this course.
                $quizResults = DB::table('quiz_results as qr')
                    ->where('qr.user_id', $enrollment->user_id)
                    ->whereIn('qr.quiz_id', $courseQuizIds)
                    ->get(['qr.quiz_id', 'qr.score', 'qr.total_items', 'qr.percentage', 'qr.status']);

                $quizPercentages = $quizResults->map(function ($result) {
                    if ($result->percentage !== null && is_numeric($result->percentage)) {
                        return max(0, min(100, (float) $result->percentage));
                    }

                    if (
                        is_numeric($result->score) &&
                        is_numeric($result->total_items) &&
                        (float) $result->total_items > 0
                    ) {
                        return max(
                            0,
                            min(100, ((float) $result->score / (float) $result->total_items) * 100)
                        );
                    }

                    return null;
                })->filter(fn ($score) => $score !== null)->values();

                $quizAverage = $quizPercentages->isNotEmpty()
                    ? round($quizPercentages->avg(), 2)
                    : null;

                // Count each quiz once, and only if a passing attempt exists.
                $passedQuizIds = $quizResults
                    ->filter(fn ($result) => strtolower((string) $result->status) === 'passed')
                    ->pluck('quiz_id');

                if ($courseQuizIds->isNotEmpty()) {
                    $historyPassedQuizIds = DB::table('quiz_attempt_histories')
                        ->where('user_id', $enrollment->user_id)
                        ->whereIn('quiz_id', $courseQuizIds)
                        ->whereRaw('LOWER(status) = ?', ['passed'])
                        ->pluck('quiz_id');

                    $passedQuizIds = $passedQuizIds
                        ->merge($historyPassedQuizIds)
                        ->unique()
                        ->values();
                }

                $completedQuizzes = $passedQuizIds->count();

                // Only count valid, recorded grades for this specific enrollment.
                $onsiteGrades = DB::table('onsite_grades as og')
                    ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
                    ->where('og.enrollment_id', $enrollment->enrollment_id)
                    ->where('og.user_id', $enrollment->user_id)
                    ->where('oa.course_id', $course->id)
                    ->whereNotNull('og.score')
                    ->where('oa.max_score', '>', 0)
                    ->select('oa.id as activity_id', 'og.score', 'oa.max_score')
                    ->get();

                $onsitePercentages = $onsiteGrades->map(function ($grade) {
                    return max(
                        0,
                        min(100, ((float) $grade->score / (float) $grade->max_score) * 100)
                    );
                });

                $onsiteAverage = $onsitePercentages->isNotEmpty()
                    ? round($onsitePercentages->avg(), 2)
                    : null;

                $completedOnsiteActivities = $onsiteGrades
                    ->pluck('activity_id')
                    ->unique()
                    ->count();

                $completedAssessments = $completedQuizzes + $completedOnsiteActivities;

                $courseProgress = $totalRequiredAssessments > 0
                    ? round(($completedAssessments / $totalRequiredAssessments) * 100, 2)
                    : 0;

                // Overall score is separate from completion progress.
                $assessmentScore = (
                    $quizAverage !== null &&
                    $onsiteAverage !== null
                )
                    ? round(($quizAverage + $onsiteAverage) / 2, 2)
                    : null;

                $assessmentStatus = $assessmentScore === null
                    ? 'Pending Assessment'
                    : ($assessmentScore >= 75
                        ? 'Competent'
                        : 'Not Yet Competent');

                $enrollment->quiz_average = $quizAverage;
                $enrollment->onsite_average = $onsiteAverage;
                $enrollment->assessment_score = $assessmentScore;
                $enrollment->assessment_status = $assessmentStatus;

                $enrollment->course_progress = $courseProgress;
                $enrollment->total_quizzes = $totalQuizzes;
                $enrollment->completed_quizzes = $completedQuizzes;
                $enrollment->total_onsite_activities = $totalOnsiteActivities;
                $enrollment->completed_onsite_activities = $completedOnsiteActivities;
                $enrollment->total_assessments = $totalRequiredAssessments;
                $enrollment->completed_assessments = $completedAssessments;

                // Preserve relationships/properties expected by the Blade.
                $enrollment->user = (object) [
                    'firstname' => $enrollment->firstname ?? '',
                    'lastname' => $enrollment->lastname ?? '',
                ];
                $enrollment->course = $course;

                return $enrollment;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Completion Rate Based on Competency
    |--------------------------------------------------------------------------
    | Retains the existing definition: competent active trainees / all active
    | trainees. This is a competency rate, not the course completion percentage.
    |--------------------------------------------------------------------------
    */
    $activeAssessmentRows = $assessmentRows
        ->where('enrollment_status', 'active');

    $competentActiveCount = $activeAssessmentRows
        ->filter(fn ($row) => $row->assessment_status === 'Competent')
        ->count();

    $completionRate = $totalTrainees > 0
        ? round(($competentActiveCount / $totalTrainees) * 100, 1) . '%'
        : '0%';

    /*
    |--------------------------------------------------------------------------
    | Trainee Progress Distribution
    |--------------------------------------------------------------------------
    | Distribution is based on assessment scores. Pending trainees are excluded
    | from score bands instead of being incorrectly counted as 0%.
    |--------------------------------------------------------------------------
    */
    $scoredRows = $assessmentRows->filter(
        fn ($row) => $row->assessment_score !== null
    );

    $progressDistribution = [
        $scoredRows->filter(fn ($row) => $row->assessment_score >= 75)->count(),
        $scoredRows->filter(fn ($row) =>
            $row->assessment_score >= 50 && $row->assessment_score < 75
        )->count(),
        $scoredRows->filter(fn ($row) =>
            $row->assessment_score > 0 && $row->assessment_score < 50
        )->count(),
        $scoredRows->filter(fn ($row) => $row->assessment_score <= 0)->count(),
        $assessmentRows->where('assessment_status', 'Pending Assessment')->count(),
    ];

    /*
    |--------------------------------------------------------------------------
    | Competency Breakdown
    |--------------------------------------------------------------------------
    */
    $chartCompetentCount = $assessmentRows
        ->where('assessment_status', 'Competent')
        ->count();

    $chartNotYetCompetentCount = $assessmentRows
        ->where('assessment_status', 'Not Yet Competent')
        ->count();

    // Pending trainees are counted separately and are not included in assessed totals.
    $chartPendingAssessmentCount = $assessmentRows
        ->where('assessment_status', 'Pending Assessment')
        ->count();

    $chartAssessedCount = $chartCompetentCount + $chartNotYetCompetentCount;
    $chartTotalTrainees = $assessmentRows->count();

    /*
    |--------------------------------------------------------------------------
    | Trainees Requiring Attention
    |--------------------------------------------------------------------------
    | Include trainees who are below 75% AND trainees whose assessment is
    | pending because either quiz or onsite results are not yet available.
    | Pending trainees appear first, followed by the lowest scored trainees.
    |--------------------------------------------------------------------------
    */
    $lowPerforming = $assessmentRows
        ->filter(fn ($row) =>
            $row->assessment_status === 'Pending Assessment'
            || (
                $row->assessment_score !== null
                && $row->assessment_score < 75
            )
        )
        ->sortBy(fn ($row) =>
            $row->assessment_status === 'Pending Assessment'
                ? -1
                : $row->assessment_score
        )
        ->take(5)
        ->values();

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
        'chartCompetentCount',
        'chartNotYetCompetentCount',
        'chartPendingAssessmentCount',
        'chartAssessedCount',
        'chartTotalTrainees'
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

    $courses = Course_tbl::where('trainer_id', $trainer->id)
        ->withCount('students')
        ->get();

    foreach ($courses as $course) {
        // Count active enrollments for the denominator.
        $enrollments = DB::table('enrollment_tbls')
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->get(['id', 'user_id']);

        $totalTrainees = $enrollments->count();
        $competentTrainees = 0;

        $quizIds = DB::table('quizzes')
            ->where('course_id', $course->id)
            ->pluck('id');

        foreach ($enrollments as $enrollment) {
            // Average the trainee's recorded quiz percentages for this course.
            $quizScores = DB::table('quiz_results')
                ->where('user_id', $enrollment->user_id)
                ->whereIn('quiz_id', $quizIds)
                ->get(['percentage', 'score', 'total_items'])
                ->map(function ($result) {
                    if ($result->percentage !== null) {
                        return max(0, min(100, (float) $result->percentage));
                    }

                    if (
                        $result->total_items !== null
                        && (float) $result->total_items > 0
                        && $result->score !== null
                    ) {
                        return max(
                            0,
                            min(100, ((float) $result->score / (float) $result->total_items) * 100)
                        );
                    }

                    return null;
                })
                ->filter(fn ($score) => $score !== null);

            $quizAverage = $quizScores->isNotEmpty()
                ? $quizScores->avg()
                : null;

            // Average onsite grades as a percentage of each activity's max score.
            $onsiteAverage = DB::table('onsite_grades as og')
                ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
                ->where('oa.course_id', $course->id)
                ->where('og.enrollment_id', $enrollment->id)
                ->where('og.user_id', $enrollment->user_id)
                ->whereNotNull('og.score')
                ->where('oa.max_score', '>', 0)
                ->selectRaw('AVG((og.score / oa.max_score) * 100) as average_score')
                ->value('average_score');

            // A trainee is competent only when both assessment categories
            // have recorded results and their equal-weight average is >= 75%.
            if (
                $quizAverage !== null
                && $onsiteAverage !== null
                && (((float) $quizAverage + (float) $onsiteAverage) / 2) >= 75
            ) {
                $competentTrainees++;
            }
        }

        $course->competent_count = $competentTrainees;
        $course->total_trainees = $totalTrainees;

        // Course progress = percentage of active enrolled trainees who are competent.
        $course->progress = $totalTrainees > 0
            ? round(($competentTrainees / $totalTrainees) * 100, 1)
            : 0;
    }

    return view('trainer.courses', compact('courses'));
}


    

public function studentGrades()
{
    $studentId = Auth::id();

    if (!$studentId) {
        return redirect()->route('Login');
    }

    /*
    |--------------------------------------------------------------------------
    | Student Enrollments
    |--------------------------------------------------------------------------
    */
    $enrollments = DB::table('enrollment_tbls as e')
        ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
        ->where('e.user_id', $studentId)
        ->whereIn('e.status', ['active', 'completed'])
        ->select([
            'e.id as enrollment_id',
            'e.course_id',
            'c.title as course_title',
        ])
        ->get();

    $courseIds = $enrollments->pluck('course_id')
        ->unique()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Quiz Definitions
    |--------------------------------------------------------------------------
    | Retrieve all quizzes for the student's enrolled courses, including
    | quizzes that have not been attempted.
    |--------------------------------------------------------------------------
    */
    $quizScores = collect();
    $quizDefinitions = collect();
    $untakenQuizzes = collect();

    if ($courseIds->isNotEmpty()) {
        $quizDefinitions = DB::table('quizzes as q')
            ->join('course_tbls as c', 'c.id', '=', 'q.course_id')
            ->whereIn('q.course_id', $courseIds)
            ->select([
                'q.id',
                'q.course_id',
                'q.title as quiz_title',
                'q.quiz_type',
                'c.title as course_title',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recorded Quiz Results
        |--------------------------------------------------------------------------
        */
        $quizScores = DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->join('course_tbls as c', 'c.id', '=', 'q.course_id')
            ->where('qr.user_id', $studentId)
            ->whereIn('q.course_id', $courseIds)
            ->select([
                'qr.id',
                'qr.quiz_id',
                'qr.score',
                'qr.total_items',
                'qr.percentage',
                'qr.status',
                'qr.attempt_number',
                'qr.created_at',
                'q.title as quiz_title',
                'q.quiz_type',
                'c.title as course_title',
            ])
            ->orderByDesc('qr.created_at')
            ->orderByDesc('qr.id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Attempt History Counts
        |--------------------------------------------------------------------------
        | Count recorded attempt-history rows per quiz.
        |--------------------------------------------------------------------------
        */
        $attemptCounts = DB::table('quiz_attempt_histories')
            ->where('user_id', $studentId)
            ->whereIn('quiz_id', $quizDefinitions->pluck('id'))
            ->selectRaw('quiz_id, COUNT(*) as history_attempts, MAX(attempt_number) as latest_attempt_number')
            ->groupBy('quiz_id')
            ->get()
            ->keyBy('quiz_id');

        /*
        |--------------------------------------------------------------------------
        | Attach Attempts Used to Quiz Results
        |--------------------------------------------------------------------------
        */
        foreach ($quizScores as $quiz) {
            $history = $attemptCounts->get($quiz->quiz_id);

            $quiz->attempts_used = max(
                (int) ($quiz->attempt_number ?? 0),
                (int) ($history->history_attempts ?? 0),
                (int) ($history->latest_attempt_number ?? 0)
            );

            $quiz->is_untaken = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Untaken Quizzes
        |--------------------------------------------------------------------------
        | A quiz is untaken when it has neither a saved result nor a recorded
        | attempt-history entry for the current student.
        |--------------------------------------------------------------------------
        */
        $attemptedQuizIds = $quizScores
            ->pluck('quiz_id')
            ->merge($attemptCounts->keys())
            ->unique()
            ->values();

        $untakenQuizzes = $quizDefinitions
            ->whereNotIn('id', $attemptedQuizIds)
            ->map(function ($quiz) {
                $quiz->quiz_id = $quiz->id;
                $quiz->score = null;
                $quiz->total_items = null;
                $quiz->percentage = null;
                $quiz->status = null;
                $quiz->attempt_number = 0;
                $quiz->attempts_used = 0;
                $quiz->created_at = null;
                $quiz->is_untaken = true;

                return $quiz;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Attempts Allowed
        |--------------------------------------------------------------------------
        | Default to 2 if your quizzes table does not expose an attempts_allowed
        | field in this query. Change this to match your system's configured
        | attempt limit if necessary.
        |--------------------------------------------------------------------------
        */
        foreach ($quizScores as $quiz) {
            $quiz->attempts_allowed = 2;
        }

        foreach ($untakenQuizzes as $quiz) {
            $quiz->attempts_allowed = 2;
        }
    } else {
        $attemptCounts = collect();
    }

    /*
    |--------------------------------------------------------------------------
    | Onsite Activities and Grades
    |--------------------------------------------------------------------------
    */
    $onsiteScores = collect();

    if ($enrollments->isNotEmpty()) {
        $enrollmentIds = $enrollments->pluck('enrollment_id');

        $onsiteScores = DB::table('onsite_activities as oa')
            ->join('course_tbls as c', 'c.id', '=', 'oa.course_id')
            ->join('enrollment_tbls as e', function ($join) {
                $join->on('e.course_id', '=', 'oa.course_id');
            })
            ->leftJoin('onsite_grades as og', function ($join) {
                $join->on('og.onsite_activity_id', '=', 'oa.id')
                    ->on('og.enrollment_id', '=', 'e.id')
                    ->on('og.user_id', '=', 'e.user_id');
            })
            ->where('e.user_id', $studentId)
            ->whereIn('e.id', $enrollmentIds)
            ->whereIn('e.status', ['active', 'completed'])
            ->select([
                'oa.id as activity_id',
                'oa.course_id',
                'oa.title as activity_title',
                'oa.max_score',
                'oa.activity_date',
                'c.title as course_title',
                'og.id as grade_id',
                'og.score',
                'og.remarks',
            ])
            ->orderByDesc('oa.activity_date')
            ->orderByDesc('oa.id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Quiz Average
    |--------------------------------------------------------------------------
    | Preserve existing behavior: average the recorded quiz result percentages.
    |--------------------------------------------------------------------------
    */
    $quizPercentages = $quizScores
        ->pluck('percentage')
        ->filter(fn ($value) => $value !== null && $value !== '')
        ->map(fn ($value) => max(0, min(100, (float) $value)));

    $quizAverage = $quizPercentages->isNotEmpty()
        ? round($quizPercentages->avg(), 2)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Onsite Average
    |--------------------------------------------------------------------------
    */
    $gradedOnsiteScores = $onsiteScores->filter(function ($activity) {
        return $activity->grade_id !== null
            && $activity->score !== null
            && $activity->max_score !== null
            && (float) $activity->max_score > 0;
    });

    $onsitePercentages = $gradedOnsiteScores->map(function ($activity) {
        return max(
            0,
            min(
                100,
                ((float) $activity->score / (float) $activity->max_score) * 100
            )
        );
    });

    $onsiteAverage = $onsitePercentages->isNotEmpty()
        ? round($onsitePercentages->avg(), 2)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Overall Assessment and Competency
    |--------------------------------------------------------------------------
    */
    $overallAverage = (
        $quizAverage !== null && $onsiteAverage !== null
    )
        ? round(($quizAverage + $onsiteAverage) / 2, 2)
        : null;

    $overallStatus = $overallAverage === null
        ? 'Pending Assessment'
        : ($overallAverage >= 75
            ? 'Competent'
            : 'Not Yet Competent');

    $assessmentStatus = $overallStatus;

    /*
    |--------------------------------------------------------------------------
    | Course Completion Progress
    |--------------------------------------------------------------------------
    | Preserve your existing rule: a quiz counts as completed if it has a
    | passing result in quiz_results or quiz_attempt_histories.
    |--------------------------------------------------------------------------
    */
    $quizIds = $quizDefinitions->pluck('id');

    $passedQuizIds = $quizScores
        ->filter(fn ($result) =>
            strtolower(trim((string) ($result->status ?? ''))) === 'passed'
        )
        ->pluck('quiz_id');

    if ($quizIds->isNotEmpty()) {
        $historyPassedQuizIds = DB::table('quiz_attempt_histories')
            ->where('user_id', $studentId)
            ->whereIn('quiz_id', $quizIds)
            ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
            ->pluck('quiz_id');

        $passedQuizIds = $passedQuizIds
            ->merge($historyPassedQuizIds)
            ->unique()
            ->values();
    }

    $totalQuizzes = $quizDefinitions->count();
    $completedQuizzes = $passedQuizIds->count();

    $completedOnsiteActivityIds = $gradedOnsiteScores
        ->pluck('activity_id')
        ->unique();

    $totalOnsiteActivities = $courseIds->isNotEmpty()
        ? DB::table('onsite_activities')
            ->whereIn('course_id', $courseIds)
            ->count()
        : 0;

    $completedOnsiteActivities = $completedOnsiteActivityIds->count();

    $totalRequiredAssessments = $totalQuizzes + $totalOnsiteActivities;

    $completedRequiredAssessments = $completedQuizzes
        + $completedOnsiteActivities;

    $courseProgress = $totalRequiredAssessments > 0
        ? (int) round(
            ($completedRequiredAssessments / $totalRequiredAssessments) * 100
        )
        : 0;

    $courseProgress = min(100, max(0, $courseProgress));

    /*
    |--------------------------------------------------------------------------
    | Student Grades View
    |--------------------------------------------------------------------------
    */
    return view('student.grades', compact(
        'enrollments',
        'quizScores',
        'untakenQuizzes',
        'quizAverage',
        'onsiteScores',
        'onsiteAverage',
        'overallAverage',
        'overallStatus',
        'assessmentStatus',
        'courseProgress',
        'totalQuizzes',
        'completedQuizzes',
        'totalOnsiteActivities',
        'completedOnsiteActivities'
    ));
}



    

    

public function assessment()
{
    $trainer = Auth::user();

    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    if (!$course) {
        return view('trainer.assessment', [
            'course' => null,
            'results' => collect(),
            'onsiteGrades' => collect(),
            'totalTaken' => 0,
            'avgScore' => 0,
            'passingRate' => 0,
            'topPerformers' => collect(),
            'quizzes' => collect(),
        ]);
    }

    // QUIZ RESULTS
    $results = \App\Models\QuizResult::with(['user', 'quiz'])
        ->whereHas('quiz', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })
        ->orderByDesc('created_at')
        ->get();

    // QUIZ STATISTICS
    $totalTaken = $results->count();

    $avgScore = $totalTaken > 0
        ? round($results->avg(function ($result) {
            return (float) ($result->percentage ?? 0);
        }), 1)
        : 0;

    $passed = $results->filter(function ($result) {
        return strtolower((string) ($result->status ?? '')) === 'passed';
    })->count();

    $passingRate = $totalTaken > 0
        ? round(($passed / $totalTaken) * 100, 1)
        : 0;

    // QUIZZES FOR THIS COURSE
    $quizzes = \App\Models\Quiz::where('course_id', $course->id)
        ->orderBy('id')
        ->get();

    // ONSITE ACTIVITY GRADES
    $onsiteGrades = \Illuminate\Support\Facades\DB::table('onsite_grades as og')
        ->join(
            'onsite_activities as oa',
            'oa.id',
            '=',
            'og.onsite_activity_id'
        )
        ->leftJoin(
            'user_tbls as u',
            'u.id',
            '=',
            'og.user_id'
        )
        ->where('oa.course_id', $course->id)
        ->select(
            'og.id',
            'og.user_id',
            'og.enrollment_id',
            'og.onsite_activity_id',
            'og.score',
            'og.remarks',
            'og.graded_by',
            'og.created_at',
            'og.updated_at',
            'oa.title as activity_title',
            'oa.max_score',
            'oa.activity_date',
            'oa.course_id',
            'u.firstname',
            'u.lastname'
        )
        ->orderByDesc('og.created_at')
        ->get();

    // TOP PERFORMERS: OVERALL QUIZ + ONSITE AVERAGE
    // Include enrolled trainees, even if they have no recorded grades.
    $students = $course->students()->get();

    $quizResultsByStudent = $results->groupBy('user_id');
    $onsiteGradesByStudent = $onsiteGrades->groupBy('user_id');

    $topPerformers = $students->map(function ($student) use (
        $quizResultsByStudent,
        $onsiteGradesByStudent
    ) {
        $studentQuizResults = $quizResultsByStudent->get(
            $student->id,
            collect()
        );

        $studentOnsiteGrades = $onsiteGradesByStudent->get(
            $student->id,
            collect()
        );

        // Average of recorded quiz percentages.
        $quizAverage = $studentQuizResults->isNotEmpty()
            ? $studentQuizResults->avg(function ($result) {
                return max(
                    0,
                    min(100, (float) ($result->percentage ?? 0))
                );
            })
            : null;

        // Average of recorded onsite activity percentages.
        $validOnsiteGrades = $studentOnsiteGrades->filter(function ($grade) {
            return is_numeric($grade->max_score)
                && (float) $grade->max_score > 0
                && is_numeric($grade->score);
        });

        $onsiteAverage = $validOnsiteGrades->isNotEmpty()
            ? $validOnsiteGrades->avg(function ($grade) {
                return max(
                    0,
                    min(
                        100,
                        ((float) $grade->score / (float) $grade->max_score) * 100
                    )
                );
            })
            : null;

        // Both categories must have recorded scores.
        $overallScore = ($quizAverage !== null && $onsiteAverage !== null)
            ? round(($quizAverage + $onsiteAverage) / 2, 1)
            : null;

        return (object) [
            'user' => $student,
            'quiz_average' => $quizAverage !== null
                ? round($quizAverage, 1)
                : null,
            'onsite_average' => $onsiteAverage !== null
                ? round($onsiteAverage, 1)
                : null,
            'avg_score' => $overallScore,
            'status' => $overallScore === null
                ? 'Pending Assessment'
                : ($overallScore >= 75
                    ? 'Competent'
                    : 'Not Yet Competent'),
        ];
    })
    ->sortByDesc(function ($student) {
        // Completed assessments rank above pending assessments.
        return $student->avg_score ?? -1;
    })
    ->take(5)
    ->values();

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

    
public function courseDetail($id)
{
    $course = \App\Models\Course_tbl::findOrFail($id);
    $userId = Auth::id();

    // Check whether the trainee already has an enrollment record.
    $enrolled = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('course_id', $course->id)
        ->exists();

    // Find the trainee's active course.
    $activeEnrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('status', 'active')
        ->first();

    // Find the most recently completed course.
    $lastCompletedEnrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('status', 'completed')
        ->orderByDesc('completed_at')
        ->first();

    // Allow another enrollment starting the next calendar month
    // after the previous course was completed.
    $canEnrollAfterCompletion = true;

    if ($lastCompletedEnrollment) {
        if (!$lastCompletedEnrollment->completed_at) {
            $canEnrollAfterCompletion = false;
        } else {
            $eligibleDate = \Carbon\Carbon::parse(
                $lastCompletedEnrollment->completed_at
            )->startOfMonth()->addMonth();

            $canEnrollAfterCompletion = now()->startOfMonth()
                ->greaterThanOrEqualTo($eligibleDate);
        }
    }

    // Prevent enrollment in another course while active or waiting.
    $atLimit = !$enrolled && (
        $activeEnrollment !== null ||
        !$canEnrollAfterCompletion
    );

    // Calculate remaining course slots.
    $occupiedSlots = \App\Models\Enrollment_tbl::where('course_id', $course->id)
        ->where('status', 'active')
        ->count();

    $availableSlots = max(0, (int) $course->slots - $occupiedSlots);

    return view('student.course_detail', compact(
        'course',
        'atLimit',
        'availableSlots',
        'activeEnrollment',
        'lastCompletedEnrollment'
    ));
}


public function enroll(\Illuminate\Http\Request $request, $id)
{
    $userId = \Illuminate\Support\Facades\Auth::id();

    $result = \Illuminate\Support\Facades\DB::transaction(
        function () use ($id, $userId) {

            $course = \App\Models\Course_tbl::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Prevent enrollment after training has started.
            if (
                $course->start_date &&
                \Carbon\Carbon::today()->greaterThanOrEqualTo(
                    \Carbon\Carbon::parse($course->start_date)->startOfDay()
                )
            ) {
                return [
                    'type' => 'error',
                    'message' => 'Enrollment is closed because this training has already started.',
                ];
            }

            // Prevent duplicate enrollment in the same course.
            $existing = \App\Models\Enrollment_tbl::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->exists();

            if ($existing) {
                return [
                    'type' => 'error',
                    'message' => 'You already have an enrollment record for this course.',
                ];
            }

            // Allow only one active course at a time.
            $activeEnrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
                ->where('status', 'active')
                ->exists();

            if ($activeEnrollment) {
                return [
                    'type' => 'error',
                    'message' => 'You must complete your current course before enrolling in another course.',
                ];
            }

            // Find the most recently completed course.
            $lastCompletedEnrollment = \App\Models\Enrollment_tbl::where('user_id', $userId)
                ->where('status', 'completed')
                ->orderByDesc('completed_at')
                ->first();

            // Require waiting until the next calendar month after completion.
            if ($lastCompletedEnrollment) {
                if (!$lastCompletedEnrollment->completed_at) {
                    return [
                        'type' => 'error',
                        'message' => 'Your previous course completion date is missing. Please contact the administrator.',
                    ];
                }

                $eligibleDate = \Carbon\Carbon::parse(
                    $lastCompletedEnrollment->completed_at
                )->startOfMonth()->addMonth();

                if (now()->startOfMonth()->lt($eligibleDate)) {
                    return [
                        'type' => 'error',
                        'message' => 'You can enroll in another course starting next month after completing your previous course.',
                    ];
                }
            }

            // Calculate remaining slots using the existing `slots` column.
            $occupiedSlots = \App\Models\Enrollment_tbl::where('course_id', $course->id)
                ->where('status', 'active')
                ->count();

            $availableSlots = max(0, (int) $course->slots - $occupiedSlots);

            if ($availableSlots <= 0) {
                return [
                    'type' => 'error',
                    'message' => 'Sorry, no more slots are available for this course!',
                ];
            }

            // Create the enrollment.
            \App\Models\Enrollment_tbl::create([
                'user_id'     => $userId,
                'course_id'   => $course->id,
                'status'      => 'active',
                'progress'    => 0,
                'enrolled_at' => now()->toDateString(),
            ]);

            // Notify the assigned trainer.
            if ($course->trainer_id) {
                $student = \Illuminate\Support\Facades\Auth::user();

                $studentName = trim(
                    ($student->firstname ?? '') . ' ' .
                    ($student->lastname ?? '')
                );

                if ($studentName === '') {
                    $studentName = $student->name ?? 'A trainee';
                }

                \Illuminate\Support\Facades\DB::table('trainer_notifications')
                    ->insert([
                        'user_id'    => $course->trainer_id,
                        'course_id'  => $course->id,
                        'type'       => 'enrollment',
                        'title'      => 'New trainee enrollment',
                        'message'    => $studentName . ' enrolled in ' . $course->title . '.',
                        'url'        => route('trainer.students'),
                        'read_at'    => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            return [
                'type' => 'success',
                'message' => 'You have successfully enrolled in ' . $course->title . '!',
            ];
        }
    );

    // Keep errors on the enrollment page.
    if ($result['type'] === 'error') {
        return back()->with('error', $result['message']);
    }

    // Successful enrollment redirects to registration step 1.
    return redirect('/registration/step1')
        ->with('success', $result['message']);
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

    
public function landingCourseDetail($id)
{
    $course = \App\Models\Course_tbl::with([
        'facility',
        'trainer',
    ])->findOrFail($id);

    return view('landing_course_detail', compact('course'));
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
    // Normalize the title to prevent duplicates caused by extra spaces.
    $request->merge([
        'title' => preg_replace('/\s+/', ' ', trim($request->input('title', ''))),
    ]);

    $request->validate([
        'course_id'   => 'required|exists:course_tbls,id',

        'title' => [
            'required',
            'string',
            'max:255',
            \Illuminate\Validation\Rule::unique('modules', 'title')
                ->where(function ($query) use ($request) {
                    return $query->where('course_id', $request->course_id);
                }),
        ],

        'description' => 'nullable|string',
        'unit_number' => 'nullable|integer|min:1',
        'file'        => 'required|file|mimes:pdf,doc,docx|max:10240',
    ], [
        'title.unique' => 'A module with this title already exists in this course. Please use a different title.',
    ]);

    $order = \App\Models\Module::where('course_id', $request->course_id)
        ->max('order') + 1;

    $filePath = null;
    $fileType = null;
    $fileSize = null;

    if ($request->hasFile('file')) {
        $file = $request->file('file');

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

    return response()->json([
        'success' => true,
        'module'  => $module,
    ]);
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
    // Normalize the title before checking for duplicates.
    $request->merge([
        'title' => preg_replace(
            '/\s+/',
            ' ',
            trim($request->input('title', ''))
        ),
    ]);

    // Use the actual table configured in the Quiz model.
    $quizTable = (new \App\Models\Quiz())->getTable();

    $validated = $request->validate([
        'course_id' => 'required|integer|exists:course_tbls,id',
        'module_id' => 'nullable|integer|exists:modules,id',

        'title' => [
            'required',
            'string',
            'max:255',
            \Illuminate\Validation\Rule::unique($quizTable, 'title')
                ->where(function ($query) use ($request) {
                    return $query->where(
                        'course_id',
                        $request->input('course_id')
                    );
                }),
        ],

        'passing_score' => 'required|integer|min:1|max:100',
        'time_limit' => 'required|integer|min:1',
        'attempts_allowed' => 'nullable|integer|min:1',
        'quiz_type' => 'nullable|string|max:50',
        'instructions' => 'nullable|string',

        'questions' => 'required|array|min:1',
        'questions.*.text' => 'required|string',
        'questions.*.type' => 'required|string|in:multiple_choice,checkboxes,short_answer,paragraph',
        'questions.*.options' => 'nullable|array',
        'questions.*.options.*' => 'nullable|string|max:255',
        'questions.*.correctAnswers' => 'nullable|array',
        'questions.*.correctAnswers.*' => 'required_with:questions.*.correctAnswers',
        'questions.*.correctAnswer' => 'nullable|string',
        'questions.*.points' => 'nullable|integer|min:1',
        'questions.*.feedback' => 'nullable|string',
        'questions.*.required' => 'nullable|boolean',

    ], [
        'title.unique' => 'A quiz with this title already exists in this course. Please choose a different title.',
    ]);

    // Keep the rest of your existing storeQuiz() method here.
}





public function editQuiz($id)
{
    $quiz = \App\Models\Quiz::with([
        'questions',
        'module',
    ])->findOrFail($id);

    $questions = $quiz->questions
        ->sortBy('order')
        ->values()
        ->map(function ($question) {
            $options = $question->options ?? [];

            // Decode options if stored as JSON text.
            if (is_string($options)) {
                $decoded = json_decode($options, true);
                $options = is_array($decoded) ? $decoded : [];
            }

            if (!is_array($options)) {
                $options = [];
            }

            // Support older quizzes using separate choice columns.
            if (empty($options)) {
                $options = array_values(array_filter([
                    $question->choice_a,
                    $question->choice_b,
                    $question->choice_c,
                    $question->choice_d,
                ], fn ($option) =>
                    $option !== null && trim((string) $option) !== ''
                ));
            }

            $correctAnswers = $question->correct_answers ?? [];

            if (is_string($correctAnswers)) {
                $decoded = json_decode($correctAnswers, true);

                $correctAnswers = is_array($decoded)
                    ? $decoded
                    : [$correctAnswers];
            }

            if (!is_array($correctAnswers)) {
                $correctAnswers = [];
            }

            // Support legacy answer letters.
            if (
                empty($correctAnswers) &&
                !empty($question->correct_answer)
            ) {
                $answer = strtolower(
                    trim((string) $question->correct_answer)
                );

                if (in_array($answer, ['a', 'b', 'c', 'd'], true)) {
                    $correctAnswers = [
                        ord($answer) - ord('a'),
                    ];
                } else {
                    $index = array_search(
                        (string) $question->correct_answer,
                        array_map('strval', $options),
                        true
                    );

                    if ($index !== false) {
                        $correctAnswers = [$index];
                    }
                }
            }

            // Normalize multiple-choice answer indexes.
            if (in_array(
                $question->question_type,
                ['multiple_choice', 'checkboxes'],
                true
            )) {
                $correctAnswers = array_values(array_filter(
                    array_map(function ($answer) use ($options) {
                        $value = trim((string) $answer);

                        if (ctype_digit($value)) {
                            $index = (int) $value;

                            return $index >= 0 && $index < count($options)
                                ? $index
                                : null;
                        }

                        $letterMap = [
                            'a' => 0,
                            'b' => 1,
                            'c' => 2,
                            'd' => 3,
                        ];

                        if (isset($letterMap[strtolower($value)])) {
                            $index = $letterMap[strtolower($value)];

                            return $index < count($options)
                                ? $index
                                : null;
                        }

                        $index = array_search(
                            $value,
                            array_map('strval', $options),
                            true
                        );

                        return $index === false ? null : $index;
                    }, $correctAnswers),
                    fn ($answer) => $answer !== null
                ));
            }

            return [
                'id' => $question->id,

                // Return both formats for compatibility with the editor.
                'question' => $question->question ?? '',
                'question_text' => $question->question ?? '',
                'text' => $question->question ?? '',

                'question_type' =>
                    $question->question_type ?? 'multiple_choice',

                'type' =>
                    $question->question_type ?? 'multiple_choice',

                'options' => array_values($options),

                'correct_answers' => array_values($correctAnswers),
                'correctAnswers' => array_values($correctAnswers),

                'correct_answer' => $question->correct_answer ?? null,

                'choice_a' => $question->choice_a,
                'choice_b' => $question->choice_b,
                'choice_c' => $question->choice_c,
                'choice_d' => $question->choice_d,

                'points' => $question->points ?? 1,
                'feedback' => $question->feedback ?? '',
                'required' => (bool) ($question->required ?? false),
                'order' => $question->order,
            ];
        });

    return response()->json([
        'success' => true,

        'quiz' => [
            'id' => $quiz->id,
            'title' => $quiz->title,

            // Return the saved instructions to the edit modal.
            'instructions' => $quiz->instructions ?? '',

            'passing_score' => $quiz->passing_score ?? 75,
            'time_limit' => $quiz->time_limit,
            'attempts_allowed' => $quiz->attempts_allowed,

            'course_id' => $quiz->course_id,
            'module_id' => $quiz->module_id,
            'module' => $quiz->module,

            'questions' => $questions,
        ],
    ]);
}

public function updateQuiz(Request $request, $id)
{
    $quiz = \App\Models\Quiz::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'course_id' => 'sometimes|required|integer|exists:course_tbls,id',
        'module_id' => 'sometimes|nullable|integer|exists:modules,id',
        'passing_score' => 'required|integer|min:1|max:100',
        'time_limit' => 'required|integer|min:1',
        'attempts_allowed' => 'sometimes|nullable|integer|min:1',
        'quiz_type' => 'sometimes|nullable|string|max:50',
        'instructions' => 'sometimes|nullable|string',

        'questions' => 'required|array|min:1',
        'questions.*.id' => 'sometimes|nullable|integer',
        'questions.*.text' => 'required|string',
        'questions.*.type' => [
            'required',
            'in:multiple_choice,checkboxes,short_answer,paragraph',
        ],
        'questions.*.options' => 'nullable|array',
        'questions.*.options.*' => 'nullable|string|max:1000',
        'questions.*.correctAnswers' => 'nullable|array',
        'questions.*.correctAnswers.*' => 'nullable',
        'questions.*.correctAnswer' => 'nullable|string',
        'questions.*.points' => 'nullable|integer|min:0',
        'questions.*.feedback' => 'nullable|string',
        'questions.*.required' => 'sometimes|boolean',
    ]);

    \Illuminate\Support\Facades\Log::info('QUIZ UPDATE INSTRUCTIONS RECEIVED', [
    'quiz_id' => $id,
    'has_instructions' => $request->exists('instructions'),
    'instructions_value' => $request->input('instructions'),
    'content_type' => $request->header('Content-Type'),
]);

    $courseId = $validated['course_id'] ?? $quiz->course_id;

    $moduleId = array_key_exists('module_id', $validated)
        ? $validated['module_id']
        : $quiz->module_id;

    if ($moduleId !== null) {
        $moduleBelongsToCourse = \Illuminate\Support\Facades\DB::table('modules')
            ->where('id', $moduleId)
            ->where('course_id', $courseId)
            ->exists();

        if (!$moduleBelongsToCourse) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'module_id' => 'The selected module does not belong to this course.',
            ]);
        }
    }

    $normalizedQuestions = [];

    foreach ($validated['questions'] as $index => $data) {
        $type = $data['type'];

        $options = array_values(array_map(
            fn ($value) => trim((string) ($value ?? '')),
            $data['options'] ?? []
        ));

        $correctAnswers = array_values(array_filter(
            array_map(
                fn ($value) => is_scalar($value)
                    ? trim((string) $value)
                    : '',
                $data['correctAnswers'] ?? []
            ),
            fn ($value) => $value !== ''
        ));

        $answerIndexes = [];

        if (in_array($type, ['multiple_choice', 'checkboxes'], true)) {
            $options = array_values(array_filter(
                $options,
                fn ($option) => $option !== ''
            ));

            if (
                count($options) < 2 ||
                count($options) > 4 ||
                count($options) !== count(array_unique($options))
            ) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "questions.$index.options" =>
                        'Choice questions must have 2 to 4 unique, non-empty options.',
                ]);
            }

            if (empty($correctAnswers)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "questions.$index.correctAnswers" =>
                        'Select at least one correct answer.',
                ]);
            }

            foreach ($correctAnswers as $answer) {
                if (ctype_digit($answer)) {
                    $answerIndex = (int) $answer;
                } elseif (in_array(strtolower($answer), ['a', 'b', 'c', 'd'], true)) {
                    $answerIndex = array_search(
                        strtolower($answer),
                        ['a', 'b', 'c', 'd'],
                        true
                    );
                } else {
                    $answerIndex = array_search($answer, $options, true);
                }

                if (
                    $answerIndex === false ||
                    !array_key_exists($answerIndex, $options)
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "questions.$index.correctAnswers" =>
                            'A correct answer does not match an available option.',
                    ]);
                }

                $answerIndexes[] = (int) $answerIndex;
            }

            $answerIndexes = array_values(array_unique($answerIndexes));

            if ($type === 'multiple_choice' && count($answerIndexes) !== 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "questions.$index.correctAnswers" =>
                        'Multiple-choice questions must have exactly one correct answer.',
                ]);
            }
        } else {
            $writtenAnswer = trim((string) ($data['correctAnswer'] ?? ''));

            if ($writtenAnswer === '' && !empty($correctAnswers)) {
                $writtenAnswer = $correctAnswers[0];
            }

            if ($type === 'short_answer' && $writtenAnswer === '') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "questions.$index.correctAnswer" =>
                        'Provide an accepted short answer.',
                ]);
            }

            $correctAnswers = $writtenAnswer !== ''
                ? [$writtenAnswer]
                : [];

            $options = [];
        }

        $normalizedQuestions[] = [
            'id' => $data['id'] ?? null,
            'text' => trim($data['text']),
            'type' => $type,
            'options' => $options,
            'answer_indexes' => $answerIndexes,
            'correct_answers' => $correctAnswers,
            'points' => $data['points'] ?? 1,
            'feedback' => $data['feedback'] ?? null,
            'required' => $data['required'] ?? false,
        ];
    }

    \Illuminate\Support\Facades\DB::transaction(function () use (
    $quiz,
    $request,
    $validated,
    $courseId,
    $moduleId,
    $normalizedQuestions
) {
        $quizTable = $quiz->getTable();

        $quiz->course_id = $courseId;
        $quiz->module_id = $moduleId;
        $quiz->title = trim($validated['title']);
        $quiz->passing_score = $validated['passing_score'];
        $quiz->time_limit = $validated['time_limit'];

        // Save instructions when they are included in the update request.
if (
    \Illuminate\Support\Facades\Schema::hasColumn($quizTable, 'instructions')
    && $request->exists('instructions')
) {
    $quiz->instructions = trim(
        (string) ($request->input('instructions') ?? '')
    );
}

        if (
            \Illuminate\Support\Facades\Schema::hasColumn($quizTable, 'attempts_allowed') &&
            array_key_exists('attempts_allowed', $validated)
        ) {
            $quiz->attempts_allowed = $validated['attempts_allowed'] ?? 2;
        }

        if (
            \Illuminate\Support\Facades\Schema::hasColumn($quizTable, 'quiz_type') &&
            array_key_exists('quiz_type', $validated)
        ) {
            $quiz->quiz_type = $validated['quiz_type'] ?? 'regular';
        }

        $quiz->save();

        $retainedIds = [];

        foreach ($normalizedQuestions as $index => $data) {
            if (!empty($data['id'])) {
                $question = $quiz->questions()
                    ->whereKey($data['id'])
                    ->first();

                if (!$question) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "questions.$index.id" =>
                            'The selected question does not belong to this quiz.',
                    ]);
                }
            } else {
                $question = new \App\Models\QuizQuestion();
                $question->quiz_id = $quiz->id;
            }

            $options = $data['options'];
            $answerIndexes = $data['answer_indexes'];
            $type = $data['type'];

            $question->question = $data['text'];
            $question->question_type = $type;
            $question->options = $options;
            $question->points = $data['points'];
            $question->feedback = $data['feedback'];
            $question->required = $data['required'];
            $question->order = $index + 1;

            if (in_array($type, ['multiple_choice', 'checkboxes'], true)) {
                $question->correct_answers = array_map('strval', $answerIndexes);
                $question->correct_answer = $options[$answerIndexes[0]] ?? null;
            } else {
                $question->correct_answers = $data['correct_answers'];
                $question->correct_answer = $data['correct_answers'][0] ?? null;
            }

            $question->choice_a = $options[0] ?? '';
            $question->choice_b = $options[1] ?? '';
            $question->choice_c = $options[2] ?? '';
            $question->choice_d = $options[3] ?? '';

            $question->save();

            $retainedIds[] = $question->id;
        }

        $questionsToDelete = $quiz->questions();

        if (!empty($retainedIds)) {
            $questionsToDelete->whereNotIn('id', $retainedIds);
        }

        $questionsToDelete->delete();
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
    $quiz = \App\Models\Quiz::with('questions')
        ->findOrFail($quizId);

    // This endpoint is for the admin/trainer quiz editor.
    // Keep answer keys available for editing.
    return response()->json([
        'success' => true,
        'questions' => $quiz->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'quiz_id' => $question->quiz_id,
                'question' => $question->question,
                'question_type' => $question->question_type,
                'options' => $question->options ?? [],
                'correct_answers' => $question->correct_answers ?? [],
                'correct_answer' => $question->correct_answer,
                'choice_a' => $question->choice_a,
                'choice_b' => $question->choice_b,
                'choice_c' => $question->choice_c,
                'choice_d' => $question->choice_d,
                'points' => $question->points ?? 1,
                'feedback' => $question->feedback,
                'required' => (bool) $question->required,
                'order' => $question->order,
            ];
        })->values(),
    ]);
}



public function storeQuizQuestion(Request $request)
{
    $validated = $request->validate([
        'quiz_id' => 'required|integer|exists:quizzes,id',
        'question' => 'required|string|max:5000',
        'question_type' => [
            'required',
            'in:multiple_choice,checkboxes,short_answer,paragraph',
        ],
        'options' => 'nullable|array',
        'options.*' => 'nullable|string|max:1000',
        'correct_answers' => 'nullable|array',
        'correct_answers.*' => 'nullable|string|max:1000',
        'correct_answer' => 'nullable|string|max:1000',
        'points' => 'nullable|integer|min:0',
        'feedback' => 'nullable|string|max:2000',
        'required' => 'sometimes|boolean',
    ]);

    $quiz = \App\Models\Quiz::findOrFail($validated['quiz_id']);

    $type = $validated['question_type'];

    $options = array_values(array_filter(
        array_map(
            fn ($option) => trim((string) $option),
            $validated['options'] ?? []
        ),
        fn ($option) => $option !== ''
    ));

    $correctAnswers = array_values(array_filter(
        array_map(
            fn ($answer) => trim((string) $answer),
            $validated['correct_answers'] ?? []
        ),
        fn ($answer) => $answer !== ''
    ));

    // Support the legacy single-answer field.
    if (empty($correctAnswers) && !empty($validated['correct_answer'])) {
        $correctAnswers = [trim($validated['correct_answer'])];
    }

    if (in_array($type, ['multiple_choice', 'checkboxes'], true)) {
        if (count($options) < 2 || count($options) > 4) {
            return response()->json([
                'success' => false,
                'message' => 'Choice questions must have between 2 and 4 options.',
            ], 422);
        }

        if (count(array_unique($options)) !== count($options)) {
            return response()->json([
                'success' => false,
                'message' => 'Choice options must be unique.',
            ], 422);
        }

        if (empty($correctAnswers)) {
            return response()->json([
                'success' => false,
                'message' => 'Please specify at least one correct answer.',
            ], 422);
        }

        foreach ($correctAnswers as $answer) {
            $validAnswer = false;

            foreach ($options as $index => $option) {
                $letter = chr(97 + $index);

                if (
                    $answer === $option ||
                    strtolower($answer) === $letter ||
                    $answer === (string) $index
                ) {
                    $validAnswer = true;
                    break;
                }
            }

            if (!$validAnswer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Each correct answer must match an available option.',
                ], 422);
            }
        }

        if ($type === 'multiple_choice' && count($correctAnswers) !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Multiple-choice questions must have exactly one correct answer.',
            ], 422);
        }
    }

    if ($type === 'short_answer' && empty($correctAnswers)) {
        return response()->json([
            'success' => false,
            'message' => 'Please provide an accepted answer for the short-answer question.',
        ], 422);
    }

    // Store the answer key as zero-based option indexes for choice questions.
    if (in_array($type, ['multiple_choice', 'checkboxes'], true)) {
        $answerIndexes = [];

        foreach ($correctAnswers as $answer) {
            foreach ($options as $index => $option) {
                if (
                    $answer === $option ||
                    strtolower($answer) === chr(97 + $index) ||
                    $answer === (string) $index
                ) {
                    $answerIndexes[] = (string) $index;
                    break;
                }
            }
        }

        $correctAnswers = array_values(array_unique($answerIndexes));
    }

    $legacyChoices = [
        'choice_a' => $options[0] ?? null,
        'choice_b' => $options[1] ?? null,
        'choice_c' => $options[2] ?? null,
        'choice_d' => $options[3] ?? null,
    ];

    $legacyCorrectAnswer = null;

    if (in_array($type, ['multiple_choice', 'checkboxes'], true)) {
        $firstIndex = isset($correctAnswers[0])
            ? (int) $correctAnswers[0]
            : null;

        $legacyCorrectAnswer = $firstIndex !== null
            ? chr(97 + $firstIndex)
            : null;
    } elseif ($type === 'short_answer') {
        $legacyCorrectAnswer = $correctAnswers[0] ?? null;
    }

    $nextOrder = (
        (int) \App\Models\QuizQuestion::where('quiz_id', $quiz->id)
            ->max('order')
    ) + 1;

    $questionData = [
        'quiz_id' => $quiz->id,
        'question' => $validated['question'],
        'question_type' => $type,
        'options' => in_array($type, ['multiple_choice', 'checkboxes'], true)
            ? $options
            : [],
        'correct_answers' => $correctAnswers,
        'points' => $validated['points'] ?? 1,
        'feedback' => $validated['feedback'] ?? null,
        'required' => $validated['required'] ?? true,
        'order' => $nextOrder,
        'correct_answer' => $legacyCorrectAnswer,
        ...$legacyChoices,
    ];

    $question = \App\Models\QuizQuestion::create($questionData);

    return response()->json([
        'success' => true,
        'message' => 'Question added successfully.',
        'question' => $question,
    ], 201);
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

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Please log in to access this quiz.',
        ], 401);
    }

    $quiz = \App\Models\Quiz::with([
        'questions' => function ($query) {
            $query->orderBy('order')->orderBy('id');
        },
    ])->findOrFail($quizId);

    // Verify enrollment in the quiz's course.
    $isEnrolled = \App\Models\Enrollment_tbl::where('user_id', $userId)
        ->where('course_id', $quiz->course_id)
        ->whereIn('status', ['active', 'completed'])
        ->exists();

    if (!$isEnrolled) {
        return response()->json([
            'success' => false,
            'message' => 'You are not enrolled in this course.',
        ], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | SEQUENTIAL QUIZ LOCKING
    |--------------------------------------------------------------------------
    | Every earlier quiz in the same course must be passed before this quiz
    | can be opened. This assumes quiz IDs follow the intended sequence.
    */

    $previousQuizzes = \App\Models\Quiz::where('course_id', $quiz->course_id)
        ->where('id', '<', $quiz->id)
        ->orderBy('id')
        ->get(['id', 'title', 'passing_score']);

    foreach ($previousQuizzes as $previousQuiz) {
        $previousResult = \App\Models\QuizResult::where(
            'quiz_id',
            $previousQuiz->id
        )
            ->where('user_id', $userId)
            ->first();

        $previousHistoryQuery = \App\Models\QuizAttemptHistory::where(
            'quiz_id',
            $previousQuiz->id
        )->where('user_id', $userId);

        $previousHistoryPassed = (clone $previousHistoryQuery)
            ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
            ->exists();

        $previousSummaryPassed = $previousResult
            && strtolower(trim((string) $previousResult->status)) === 'passed';

        // Accept a pass recorded in either result table.
        $previousHasPassed = $previousSummaryPassed || $previousHistoryPassed;

        if (!$previousHasPassed) {
            return response()->json([
                'success' => false,
                'locked' => true,
                'can_attempt' => false,
                'message' => 'You must pass "' .
                    $previousQuiz->title .
                    '" before taking this quiz.',
                'required_quiz_id' => $previousQuiz->id,
                'required_quiz_title' => $previousQuiz->title,
            ], 403);
        }
    }

    // Get this student's saved best-result summary.
    $result = \App\Models\QuizResult::where('quiz_id', $quiz->id)
        ->where('user_id', $userId)
        ->first();

    // Get this student's individual attempt records.
    $historyQuery = \App\Models\QuizAttemptHistory::where(
        'quiz_id',
        $quiz->id
    )->where('user_id', $userId);

    $historyCount = (clone $historyQuery)->count();

    $latestAttemptNumber = (int) (
        (clone $historyQuery)->max('attempt_number') ?? 0
    );

    // Use the highest reliable count to avoid undercounting attempts.
    $attemptsUsed = max(
        (int) ($result->attempts_used ?? 0),
        (int) ($result->attempt_number ?? 0),
        $latestAttemptNumber,
        $historyCount
    );

    // New quizzes default to 2 attempts.
    $attemptsAllowed = max(
        1,
        (int) ($quiz->attempts_allowed ?? 2)
    );

    // A passing result in either table closes further attempts.
    $historyHasPassed = (clone $historyQuery)
        ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
        ->exists();

    $summaryHasPassed = $result
        && strtolower(trim((string) $result->status)) === 'passed';

    $hasPassed = $summaryHasPassed || $historyHasPassed;

    $canAttempt = !$hasPassed
        && $attemptsUsed < $attemptsAllowed;

    // Retrieve the latest attempt for fallback display.
    $latestAttempt = (clone $historyQuery)
        ->orderByDesc('attempt_number')
        ->orderByDesc('created_at')
        ->first();

    // Do not send answer keys or grading feedback to students.
    $safeQuestions = $quiz->questions->map(function ($question) {
        $options = $question->options ?? [];

        if (is_string($options)) {
            $decoded = json_decode($options, true);
            $options = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($options)) {
            $options = [];
        }

        return [
            'id'            => $question->id,
            'question'      => $question->question,
            'question_type' => $question->question_type,
            'options'       => $options,
            'choice_a'      => $question->choice_a,
            'choice_b'      => $question->choice_b,
            'choice_c'      => $question->choice_c,
            'choice_d'      => $question->choice_d,
            'points'        => $question->points ?? 1,
            'required'      => (bool) $question->required,
            'order'         => $question->order,
        ];
    })->values();

    $safeQuiz = [
        'id'               => $quiz->id,
        'course_id'        => $quiz->course_id,
        'module_id'        => $quiz->module_id,
        'title'            => $quiz->title,
        'instructions'     => $quiz->instructions,
        'passing_score'    => $quiz->passing_score,
        'time_limit'       => $quiz->time_limit,
        'attempts_allowed' => $attemptsAllowed,
        'questions'        => $safeQuestions,
    ];

    // Prefer the saved summary; fall back to the latest attempt.
    $displayResult = $result ?: $latestAttempt;

    $safeResult = $displayResult ? [
        'score'         => $displayResult->score,
        'total_items'   => $displayResult->total_items,
        'percentage'    => $displayResult->percentage,
        'status'        => $displayResult->status,
        'attempts_used' => $attemptsUsed,
    ] : null;

    $attemptHistory = (clone $historyQuery)
        ->orderBy('attempt_number')
        ->orderBy('created_at')
        ->get([
            'id',
            'quiz_id',
            'attempt_number',
            'score',
            'total_items',
            'percentage',
            'status',
            'timed_out',
            'created_at',
        ]);

    return response()->json([
        'success' => true,
        'quiz' => $safeQuiz,
        'taken' => !$canAttempt,
        'can_attempt' => $canAttempt,
        'result' => $safeResult,

        'latest_attempt' => $latestAttempt ? [
            'attempt_number' => $latestAttempt->attempt_number,
            'score'          => $latestAttempt->score,
            'total_items'    => $latestAttempt->total_items,
            'percentage'     => $latestAttempt->percentage,
            'status'         => $latestAttempt->status,
            'timed_out'      => $latestAttempt->timed_out,
            'created_at'     => $latestAttempt->created_at,
        ] : null,

        'attempts_used' => $attemptsUsed,
        'attempts_allowed' => $attemptsAllowed,
        'attempts_remaining' => max(
            0,
            $attemptsAllowed - $attemptsUsed
        ),
        'has_passed' => $hasPassed,
        'attempt_history' => $attemptHistory,
    ]);
}



public function submitQuiz(Request $request)
{
    $validated = $request->validate([
        'quiz_id'   => 'required|integer|exists:quizzes,id',
        'answers'   => 'sometimes|array',
        'timed_out' => 'sometimes|boolean',
    ]);

    $userId = \Illuminate\Support\Facades\Auth::id();

    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Please log in before submitting your quiz.',
        ], 401);
    }

    $answers = $validated['answers'] ?? [];
    $timedOut = $request->boolean('timed_out');

    $responseData = \Illuminate\Support\Facades\DB::transaction(
        function () use ($validated, $userId, $answers, $timedOut) {
            $quiz = \App\Models\Quiz::whereKey($validated['quiz_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // Only enrolled students with an eligible enrollment may submit.
            $enrollment = \App\Models\Enrollment_tbl::where(
                'user_id',
                $userId
            )
                ->where('course_id', $quiz->course_id)
                ->whereIn('status', ['active', 'completed'])
                ->first();

            if (!$enrollment) {
                return [
                    'http_status' => 403,
                    'body' => [
                        'success' => false,
                        'message' => 'You are not enrolled in this course.',
                    ],
                ];
            }

            /*
             * Block quiz submissions after the course end date.
             * Submissions remain allowed throughout the end date itself.
             */
            $course = \App\Models\Course_tbl::find($quiz->course_id);

            if (
                $course &&
                $course->end_date &&
                \Carbon\Carbon::today()->greaterThan(
                    \Carbon\Carbon::parse($course->end_date)->startOfDay()
                )
            ) {
                return [
                    'http_status' => 403,
                    'body' => [
                        'success' => false,
                        'course_ended' => true,
                        'message' => 'This course has ended. Quiz submissions are no longer allowed.',
                    ],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | SEQUENTIAL QUIZ LOCKING
            |--------------------------------------------------------------------------
            | This assumes lower quiz IDs represent earlier quizzes in the
            | same course. Every earlier quiz must have a passed result.
            */

            $previousQuizzes = \App\Models\Quiz::where(
                'course_id',
                $quiz->course_id
            )
                ->where('id', '<', $quiz->id)
                ->orderBy('id')
                ->get(['id', 'title']);

            foreach ($previousQuizzes as $previousQuiz) {
                $previousResult = \App\Models\QuizResult::where(
                    'quiz_id',
                    $previousQuiz->id
                )
                    ->where('user_id', $userId)
                    ->first();

                $previousSummaryPassed = $previousResult
                    && strtolower(trim((string) $previousResult->status)) === 'passed';

                $previousHistoryPassed = \App\Models\QuizAttemptHistory::where(
                    'quiz_id',
                    $previousQuiz->id
                )
                    ->where('user_id', $userId)
                    ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
                    ->exists();

                if (!$previousSummaryPassed && !$previousHistoryPassed) {
                    return [
                        'http_status' => 403,
                        'body' => [
                            'success' => false,
                            'locked' => true,
                            'can_attempt' => false,
                            'message' => 'You must pass "' .
                                $previousQuiz->title .
                                '" before submitting this quiz.',
                            'required_quiz_id' => $previousQuiz->id,
                            'required_quiz_title' => $previousQuiz->title,
                        ],
                    ];
                }
            }

            $questions = \App\Models\QuizQuestion::where(
                'quiz_id',
                $quiz->id
            )
                ->orderBy('order')
                ->orderBy('id')
                ->get();

            if ($questions->isEmpty()) {
                return [
                    'http_status' => 422,
                    'body' => [
                        'success' => false,
                        'message' => 'This quiz has no questions.',
                    ],
                ];
            }

            // Retrieve the student's best recorded result.
            $bestResult = \App\Models\QuizResult::where(
                'quiz_id',
                $quiz->id
            )
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            $historyQuery = \App\Models\QuizAttemptHistory::where(
                'quiz_id',
                $quiz->id
            )->where('user_id', $userId);

            $latestAttemptNumber = (int) (
                (clone $historyQuery)->max('attempt_number') ?? 0
            );

            $historyCount = (int) (clone $historyQuery)->count();

            $attemptsUsed = max(
                (int) ($bestResult->attempts_used ?? 0),
                (int) ($bestResult->attempt_number ?? 0),
                $latestAttemptNumber,
                $historyCount
            );

            $attemptsAllowed = max(
                1,
                (int) ($quiz->attempts_allowed ?? 2)
            );

            $summaryHasPassed = $bestResult
                && strtolower(trim((string) $bestResult->status)) === 'passed';

            $historyHasPassed = (clone $historyQuery)
                ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
                ->exists();

            if ($summaryHasPassed || $historyHasPassed) {
                return [
                    'http_status' => 409,
                    'body' => [
                        'success' => false,
                        'message' => 'You have already passed this quiz.',
                        'attempts_used' => $attemptsUsed,
                        'attempts_allowed' => $attemptsAllowed,
                        'attempts_remaining' => 0,
                    ],
                ];
            }

            if ($attemptsUsed >= $attemptsAllowed) {
                return [
                    'http_status' => 409,
                    'body' => [
                        'success' => false,
                        'message' => 'You have used all attempts allowed for this quiz.',
                        'attempts_used' => $attemptsUsed,
                        'attempts_allowed' => $attemptsAllowed,
                        'attempts_remaining' => 0,
                    ],
                ];
            }

            /*
             * Normalize answers for case-insensitive comparison.
             */
            $normalizeAnswer = function ($value) {
                if (!is_scalar($value)) {
                    return '';
                }

                return mb_strtolower(trim((string) $value));
            };

            /*
             * Get options from JSON or legacy choice_a-choice_d columns.
             */
            $getOptions = function ($question) {
                $options = $question->options ?? [];

                if (is_string($options)) {
                    $decoded = json_decode($options, true);
                    $options = is_array($decoded) ? $decoded : [];
                }

                if (!is_array($options) || empty($options)) {
                    $options = [];

                    foreach (['a', 'b', 'c', 'd'] as $letter) {
                        $field = 'choice_' . $letter;
                        $value = $question->{$field} ?? null;

                        if (
                            $value !== null
                            && trim((string) $value) !== ''
                        ) {
                            $options[] = [
                                'value' => $letter,
                                'label' => (string) $value,
                            ];
                        }
                    }
                } else {
                    $options = array_values($options);

                    foreach ($options as $index => &$option) {
                        $defaultValue = ['a', 'b', 'c', 'd'][$index]
                            ?? (string) $index;

                        if (is_array($option)) {
                            $label = $option['label']
                                ?? $option['text']
                                ?? $option['option']
                                ?? $option['name']
                                ?? $option['value']
                                ?? '';

                            $value = $option['value']
                                ?? $option['id']
                                ?? $defaultValue;

                            $option = [
                                'value' => (string) $value,
                                'label' => (string) $label,
                            ];
                        } elseif (is_object($option)) {
                            $label = $option->label
                                ?? $option->text
                                ?? $option->option
                                ?? $option->name
                                ?? $option->value
                                ?? '';

                            $value = $option->value
                                ?? $option->id
                                ?? $defaultValue;

                            $option = [
                                'value' => (string) $value,
                                'label' => (string) $label,
                            ];
                        } else {
                            $option = [
                                'value' => $defaultValue,
                                'label' => (string) $option,
                            ];
                        }
                    }

                    unset($option);
                }

                return array_values($options);
            };

            /*
             * IMPORTANT: Define this helper before $mapAnswerKey.
             */
            $getOptionValue = function ($option) {
                if (is_array($option)) {
                    return (string) (
                        $option['value']
                        ?? $option['label']
                        ?? $option['text']
                        ?? ''
                    );
                }

                if (is_object($option)) {
                    return (string) (
                        $option->value
                        ?? $option->label
                        ?? $option->text
                        ?? ''
                    );
                }

                return is_scalar($option)
                    ? (string) $option
                    : '';
            };

            /*
             * Convert answer keys such as A, B, C, D or numeric indexes
             * into the corresponding option value.
             */
            $mapAnswerKey = function ($value, $options) use ($getOptionValue) {
                if (!is_scalar($value)) {
                    return '';
                }

                $value = trim((string) $value);

                if ($value === '') {
                    return '';
                }

                // Numeric indexes: 0, 1, 2, 3.
                if (ctype_digit($value)) {
                    $index = (int) $value;

                    if (array_key_exists($index, $options)) {
                        return $getOptionValue($options[$index]);
                    }
                }

                // Legacy answer keys: A, B, C, D.
                if (preg_match('/^[a-d]$/i', $value)) {
                    $index = ord(strtolower($value)) - ord('a');

                    if (array_key_exists($index, $options)) {
                        return $getOptionValue($options[$index]);
                    }
                }

                // Support answer keys saved as visible option text or value.
                foreach ($options as $option) {
                    $optionValue = $getOptionValue($option);

                    if (is_array($option)) {
                        $optionLabel = (string) (
                            $option['label'] ?? $option['text'] ?? ''
                        );
                    } elseif (is_object($option)) {
                        $optionLabel = (string) (
                            $option->label ?? $option->text ?? ''
                        );
                    } else {
                        $optionLabel = (string) $option;
                    }

                    if (
                        $normalizeAnswer($value) ===
                            $normalizeAnswer($optionValue)
                        || $normalizeAnswer($value) ===
                            $normalizeAnswer($optionLabel)
                    ) {
                        return $optionValue;
                    }
                }

                return $value;
            };

            /*
             * Grade questions using configured points.
             */
            $score = 0;
            $totalPoints = 0;

            foreach ($questions as $question) {
                $points = max(0, (int) ($question->points ?? 1));
                $totalPoints += $points;

                $answer = $answers[$question->id] ?? null;

                $questionType = strtolower(trim(
                    (string) ($question->question_type ?? 'multiple_choice')
                ));

                // Normalize hyphens to underscores.
                $questionType = str_replace('-', '_', $questionType);

                $options = $getOptions($question);

                $correctAnswer = $question->correct_answer ?? null;
                $correctAnswers = $question->correct_answers ?? [];

                if (is_string($correctAnswers)) {
                    $decoded = json_decode($correctAnswers, true);

                    $correctAnswers = is_array($decoded)
                        ? $decoded
                        : [$correctAnswers];
                }

                if (!is_array($correctAnswers)) {
                    $correctAnswers = [];
                }

                /*
                 * Checkbox / multiple-select questions:
                 * all selected answers must match the answer key.
                 */
                $isCheckbox = in_array($questionType, [
                    'checkboxes',
                    'checkbox',
                    'multi_select',
                    'multiple_select',
                    'multiple_answer',
                    'multiple_checkboxes',
                ], true);

                if ($isCheckbox) {
                    if (
                        empty($correctAnswers)
                        && $correctAnswer !== null
                    ) {
                        $correctAnswers = [$correctAnswer];
                    }

                    $expected = array_map(
                        fn ($value) => $normalizeAnswer(
                            $mapAnswerKey($value, $options)
                        ),
                        $correctAnswers
                    );

                    $submitted = is_array($answer)
                        ? $answer
                        : (
                            is_scalar($answer)
                            && trim((string) $answer) !== ''
                                ? [$answer]
                                : []
                        );

                    $submitted = array_map(
                        fn ($value) => $normalizeAnswer($value),
                        $submitted
                    );

                    $submitted = array_values(array_filter(
                        $submitted,
                        fn ($value) => $value !== ''
                    ));

                    $expected = array_values(array_filter(
                        $expected,
                        fn ($value) => $value !== ''
                    ));

                    $submitted = array_values(array_unique($submitted));
                    $expected = array_values(array_unique($expected));

                    sort($submitted);
                    sort($expected);

                    if (!empty($expected) && $submitted === $expected) {
                        $score += $points;
                    }

                    continue;
                }

                /*
                 * Multiple-choice and true/false questions.
                 */
                if (in_array($questionType, [
                    'multiple_choice',
                    'radio',
                    'true_false',
                ], true)) {
                    $expected = null;

                    // Prefer the configured correct_answers key when present.
                    if (!empty($correctAnswers)) {
                        $expected = $mapAnswerKey(
                            $correctAnswers[0],
                            $options
                        );
                    }

                    // Otherwise use the legacy correct_answer field.
                    if ($expected === null || $expected === '') {
                        $expected = $mapAnswerKey(
                            $correctAnswer,
                            $options
                        );
                    }

                    if (
                        is_scalar($answer)
                        && $expected !== ''
                        && $expected !== null
                        && $normalizeAnswer($answer) !== ''
                        && $normalizeAnswer($answer) ===
                            $normalizeAnswer($expected)
                    ) {
                        $score += $points;
                    }

                    continue;
                }

                /*
                 * Short-answer and text questions.
                 * Exact match, ignoring case and surrounding whitespace.
                 */
                if (in_array($questionType, [
                    'short_answer',
                    'text',
                    'paragraph',
                ], true)) {
                    $expectedAnswers = [];

                    if (
                        $correctAnswer !== null
                        && trim((string) $correctAnswer) !== ''
                    ) {
                        $expectedAnswers[] = $correctAnswer;
                    }

                    foreach ($correctAnswers as $acceptedAnswer) {
                        if (is_scalar($acceptedAnswer)) {
                            $expectedAnswers[] = $acceptedAnswer;
                        }
                    }

                    $expectedAnswers = array_map(
                        $normalizeAnswer,
                        $expectedAnswers
                    );

                    $expectedAnswers = array_values(array_unique(
                        array_filter(
                            $expectedAnswers,
                            fn ($value) => $value !== ''
                        )
                    ));

                    if (
                        is_scalar($answer)
                        && $normalizeAnswer($answer) !== ''
                        && in_array(
                            $normalizeAnswer($answer),
                            $expectedAnswers,
                            true
                        )
                    ) {
                        $score += $points;
                    }
                }
            }

            $percentage = $totalPoints > 0
                ? round(($score / $totalPoints) * 100, 2)
                : 0;

            $passingScore = (float) ($quiz->passing_score ?? 75);

            $status = $percentage >= $passingScore
                ? 'passed'
                : 'failed';

            $attemptNumber = $attemptsUsed + 1;

            /*
             * Save every attempt separately.
             */
            \App\Models\QuizAttemptHistory::create([
                'quiz_id' => $quiz->id,
                'user_id' => $userId,
                'attempt_number' => $attemptNumber,
                'score' => $score,
                'total_items' => $totalPoints,
                'percentage' => $percentage,
                'status' => $status,
                'timed_out' => $timedOut,
            ]);

            /*
             * Keep the best score in quiz_results while counting every attempt.
             */
            if (!$bestResult) {
                $bestResult = \App\Models\QuizResult::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $userId,
                    'score' => $score,
                    'total_items' => $totalPoints,
                    'percentage' => $percentage,
                    'status' => $status,
                    'attempt_number' => $attemptNumber,
                    'attempts_used' => $attemptNumber,
                ]);
            } else {
                $isBetter =
                    $percentage > (float) $bestResult->percentage
                    || (
                        $percentage == (float) $bestResult->percentage
                        && $score > (int) $bestResult->score
                    );

                $bestResult->attempts_used = $attemptNumber;

                if ($isBetter) {
                    $bestResult->score = $score;
                    $bestResult->total_items = $totalPoints;
                    $bestResult->percentage = $percentage;
                    $bestResult->status = $status;
                    $bestResult->attempt_number = $attemptNumber;
                }

                $bestResult->save();
            }

            /*
             * Update enrollment progress using passed quizzes.
             */
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
                ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
                ->count();

            $progress = $totalQuizzes > 0
                ? (int) round(
                    ($completedQuizzes / $totalQuizzes) * 100
                )
                : 0;

            $enrollment->progress = $progress;

            /*
             * Preserve the existing course completion requirements.
             */
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
                ->distinct()
                ->count('module_id');

            $allModulesCompleted = $moduleIds->count() > 0
                && $completedModules >= $moduleIds->count();

            $postTest = \App\Models\Quiz::where(
                'course_id',
                $quiz->course_id
            )
                ->where('quiz_type', 'post_test')
                ->first();

            $postTestPassed = $postTest
                && \App\Models\QuizResult::where('user_id', $userId)
                    ->where('quiz_id', $postTest->id)
                    ->whereRaw('LOWER(TRIM(status)) = ?', ['passed'])
                    ->exists();

            $handsOnPassed = \App\Models\HandsOnEvaluation::where(
                'user_id',
                $userId
            )
                ->where('course_id', $quiz->course_id)
                ->whereRaw('LOWER(TRIM(result)) = ?', ['passed'])
                ->exists();

            if (
                $allModulesCompleted
                && $postTestPassed
                && $handsOnPassed
            ) {
                $enrollment->status = 'completed';

                if (!$enrollment->completed_at) {
                    $enrollment->completed_at = now();
                }
            }

            $enrollment->save();

            $bestResult->refresh();

            return [
                'http_status' => 200,
                'body' => [
                    'success' => true,

                    // Current attempt result.
                    'score' => $score,
                    'total' => $totalPoints,
                    'percentage' => $percentage,
                    'status' => $status,
                    'current_attempt_score' => $score,
                    'current_attempt_percentage' => $percentage,
                    'current_attempt_status' => $status,

                    'passing_score' => $passingScore,
                    'progress' => $progress,

                    // Attempt limits.
                    'attempt_number' => $attemptNumber,
                    'attempts_used' => $attemptNumber,
                    'attempts_allowed' => $attemptsAllowed,
                    'attempts_remaining' => max(
                        0,
                        $attemptsAllowed - $attemptNumber
                    ),

                    'timed_out' => $timedOut,

                    // Best result across attempts.
                    'best_result' => $bestResult,
                    'best_score' => (int) $bestResult->score,
                    'best_percentage' => (float) $bestResult->percentage,
                    'best_status' => $bestResult->status,
                ],
            ];
        }
    );

    return response()->json(
        $responseData['body'],
        $responseData['http_status']
    );
}



public function storeAttendance(Request $request)
{
    $trainer = Auth::user();

    $course = Course_tbl::where('trainer_id', $trainer->id)
        ->firstOrFail();

    if (!app(\App\Services\CourseScheduleService::class)
        ->isInSessionToday($course)) {
        return back()->with(
            'error',
            'This course is not scheduled to meet today.'
        );
    }

    $today = now()->toDateString();

    $absentIds = collect($request->input('absent', []))
        ->map(fn ($id) => (int) $id);

    $enrollments = \App\Models\Enrollment_tbl::where(
        'course_id',
        $course->id
    )
        ->where('status', 'active')
        ->get(['id', 'user_id']);

    if ($enrollments->isEmpty()) {
        return back()->with(
            'error',
            'There are no active trainees enrolled in this course.'
        );
    }

    foreach ($enrollments as $enrollment) {
        $status = $absentIds->contains((int) $enrollment->id)
            ? 'absent'
            : 'present';

        \App\Models\Attendance::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'date' => $today,
            ],
            [
                'user_id' => $enrollment->user_id,
                'status' => $status,
                'marked_by' => $trainer->id,
                'auto_marked' => false,
            ]
        );
    }

    return redirect()
        ->route('trainer.attendance')
        ->with(
            'success',
            'Attendance submitted for ' . now()->format('F j, Y')
        );
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

    $announcement = \App\Models\Announcement::create($validated);

    // Send notifications to trainers for eligible announcements.
    $this->notifyTrainersAboutAnnouncement($announcement);

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
    $announcement->refresh();

    // Notify trainers if the announcement is active and published.
    $this->notifyTrainersAboutAnnouncement($announcement);

    return response()->json([
        'success' => true,
        'message' => 'Announcement updated successfully!'
    ]);
}

/**
 * Send an admin announcement to trainer notification bells.
 */

private function notifyTrainersAboutAnnouncement($announcement): void
{
    if (!(bool) $announcement->is_active) {
        return;
    }

    if (
        $announcement->publish_at &&
        \Illuminate\Support\Carbon::parse($announcement->publish_at)->isFuture()
    ) {
        return;
    }

    if (
        $announcement->expires_at &&
        \Illuminate\Support\Carbon::parse($announcement->expires_at)->isPast()
    ) {
        return;
    }

    if (
        strtolower(trim($announcement->audience ?? 'general')) !== 'general'
    ) {
        return;
    }

    $trainers = \Illuminate\Support\Facades\DB::table('user_tbls')
        ->whereRaw('LOWER(role) = ?', ['trainer'])
        ->pluck('id');

    foreach ($trainers as $trainerId) {
        // Avoid duplicate notifications when editing an announcement.
        $exists = \Illuminate\Support\Facades\DB::table('trainer_notifications')
            ->where('user_id', $trainerId)
            ->where('type', 'announcement')
            ->where('url', route('teacher') . '?announcement=' . $announcement->id)
            ->exists();

        if ($exists) {
            continue;
        }

        \Illuminate\Support\Facades\DB::table('trainer_notifications')->insert([
            'user_id' => $trainerId,
            'course_id' => null,
            'type' => 'announcement',
            'title' => $announcement->title,
            'message' => $announcement->message,
            'url' => route('teacher') . '?announcement=' . $announcement->id,
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
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

    // Get the student's enrollments and associated courses.
    $enrollments = \App\Models\Enrollment_tbl::with('course')
        ->where('user_id', $userId)
        ->get();

    // Select the requested enrollment, or default to an active enrollment.
    $selectedId = $request->get('course_id');

    $enrollment = $selectedId
        ? $enrollments->firstWhere('course_id', $selectedId)
        : (
            $enrollments->firstWhere('status', 'active')
            ?? $enrollments->first()
        );

    // Initialize defaults to prevent undefined variables.
    $modules = collect();
    $unitNumbers = collect();
    $completedModuleIds = [];
    $quizzes = collect();
    $quizResults = collect();
    $quizAttempts = collect();
    $onsiteGrades = collect();

    $handsOnEvaluation = null;
    $certificate = null;
    $postTest = null;

    $quizAverage = null;
    $onsiteAverage = null;
    $overallProgress = 0;

    if ($enrollment) {
        $courseId = $enrollment->course_id;

        /*
        |--------------------------------------------------------------------------
        | Course modules
        |--------------------------------------------------------------------------
        */

        $modules = \App\Models\Module::where('course_id', $courseId)
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

        /*
        |--------------------------------------------------------------------------
        | Quizzes and quiz results
        |--------------------------------------------------------------------------
        */

        $quizzes = \App\Models\Quiz::where('course_id', $courseId)
            ->orderBy('id')
            ->get();

        $postTest = $quizzes->firstWhere('quiz_type', 'post_test');

        $quizResults = \App\Models\QuizResult::where('user_id', $userId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Quiz attempt history
        |--------------------------------------------------------------------------
        */

        $quizAttempts = \Illuminate\Support\Facades\DB::table(
            'quiz_attempt_histories'
        )
            ->join(
                'quizzes',
                'quizzes.id',
                '=',
                'quiz_attempt_histories.quiz_id'
            )
            ->where('quiz_attempt_histories.user_id', $userId)
            ->whereIn(
                'quiz_attempt_histories.quiz_id',
                $quizzes->pluck('id')
            )
            ->select([
                'quiz_attempt_histories.id',
                'quiz_attempt_histories.quiz_id',
                'quizzes.title as quiz_title',
                'quiz_attempt_histories.attempt_number',
                'quiz_attempt_histories.score',
                'quiz_attempt_histories.total_items',
                'quiz_attempt_histories.percentage',
                'quiz_attempt_histories.status',
                'quiz_attempt_histories.timed_out',
                'quiz_attempt_histories.created_at',
            ])
            ->orderByDesc('quiz_attempt_histories.created_at')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Quiz average score
        |--------------------------------------------------------------------------
        */

        $quizPercentages = $quizResults
            ->pluck('percentage')
            ->filter(fn ($percentage) => $percentage !== null)
            ->map(fn ($percentage) => (float) $percentage);

        if ($quizPercentages->isNotEmpty()) {
            $quizAverage = round($quizPercentages->avg(), 2);
        }

        /*
        |--------------------------------------------------------------------------
        | Onsite activity grades
        |--------------------------------------------------------------------------
        */

        $onsiteGrades = \Illuminate\Support\Facades\DB::table(
            'onsite_grades'
        )
            ->join(
                'onsite_activities',
                'onsite_grades.onsite_activity_id',
                '=',
                'onsite_activities.id'
            )
            ->where('onsite_grades.user_id', $userId)
            ->where('onsite_grades.enrollment_id', $enrollment->id)
            ->where('onsite_activities.course_id', $courseId)
            ->select([
                'onsite_grades.id',
                'onsite_grades.score',
                'onsite_activities.max_score',
                'onsite_activities.id as activity_id',
            ])
            ->get();

        $onsitePercentages = $onsiteGrades
            ->filter(function ($grade) {
                return $grade->score !== null
                    && $grade->max_score !== null
                    && (float) $grade->max_score > 0;
            })
            ->map(function ($grade) {
                return min(
                    100,
                    max(
                        0,
                        ((float) $grade->score
                            / (float) $grade->max_score) * 100
                    )
                );
            });

        if ($onsitePercentages->isNotEmpty()) {
            $onsiteAverage = round($onsitePercentages->avg(), 2);
        }

        /*
        |--------------------------------------------------------------------------
        | Overall course completion progress
        |--------------------------------------------------------------------------
        |
        | Each quiz counts once and only if the student has passed it.
        | Each onsite activity counts once if a valid grade is recorded.
        | A failed attempt does not count as a passed quiz.
        |
        */

        // Count distinct quizzes with at least one passing result.
        $passedQuizIds = $quizResults
            ->filter(function ($result) {
                return strtolower(trim(
                    (string) ($result->status ?? '')
                )) === 'passed';
            })
            ->pluck('quiz_id')
            ->unique();

        $completedQuizzes = $passedQuizIds->count();

        // Count required onsite activities for this course.
        $totalOnsiteActivities = \Illuminate\Support\Facades\DB::table(
            'onsite_activities'
        )
            ->where('course_id', $courseId)
            ->count();

        // Count distinct onsite activities with valid recorded grades.
        $completedOnsiteActivities = $onsiteGrades
            ->filter(function ($grade) {
                return $grade->score !== null
                    && $grade->max_score !== null
                    && (float) $grade->max_score > 0;
            })
            ->pluck('activity_id')
            ->unique()
            ->count();

        $totalAssessments = $quizzes->count()
            + $totalOnsiteActivities;

        $completedAssessments = $completedQuizzes
            + $completedOnsiteActivities;

        $overallProgress = $totalAssessments > 0
            ? (int) round(
                ($completedAssessments / $totalAssessments) * 100
            )
            : 0;

        $overallProgress = min(100, max(0, $overallProgress));

        /*
        |--------------------------------------------------------------------------
        | Hands-on evaluation and certificate
        |--------------------------------------------------------------------------
        */

        $handsOnEvaluation = \App\Models\HandsOnEvaluation::where(
            'user_id',
            $userId
        )
            ->where('course_id', $courseId)
            ->first();

        $certificate = \App\Models\Certificate::where(
            'user_id',
            $userId
        )
            ->where('course_id',
                $courseId
            )
            ->first();
    }

    return view('student.modules', compact(
        'enrollments',
        'enrollment',
        'modules',
        'unitNumbers',
        'completedModuleIds',
        'quizzes',
        'quizResults',
        'quizAttempts',
        'postTest',
        'handsOnEvaluation',
        'certificate',
        'onsiteGrades',
        'quizAverage',
        'onsiteAverage',
        'overallProgress'
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

    
    

