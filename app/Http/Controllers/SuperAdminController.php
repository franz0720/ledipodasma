<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Certificate;
use App\Models\Course_tbl;
use App\Models\Facility;
use App\Models\Registration;
use App\Models\User_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SuperAdminController extends Controller
{
    /**
     * Read-only system overview for Super Admin.
     * This method only retrieves data; it does not create, update, or delete records.
     */
    public function dashboard(Request $request)
    {
        $currentView = $request->query('view', 'overview');

        // Main dashboard datasets. Keep pagination query names separate so each
        // section can paginate without affecting the other sections.
        $courses = Course_tbl::with(['facility', 'trainer'])
            ->withCount(['enrollments', 'modules', 'quizzes'])
            ->latest('id')
            ->paginate(9, ['*'], 'course_page')
            ->withQueryString();

        $allCourses = Course_tbl::with(['facility', 'trainer'])->orderBy('title')->get();

        $trainers = User_tbl::where('role', 'trainer')->get();
        $trainersList = User_tbl::where('role', 'trainer')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->paginate(10, ['*'], 'trainer_page')
            ->withQueryString();

        $trainees = User_tbl::where('role', 'student')->get();

        $announcements = Announcement::latest()
            ->paginate(10, ['*'], 'announcement_page')
            ->withQueryString();

        $registrations = Registration::latest()
            ->paginate(10, ['*'], 'reg_page')
            ->withQueryString();

        $facilities = Facility::with('courses')->orderBy('id')->get();

        $certificates = Certificate::with(['user', 'course'])->latest()->get();

        // The Super Admin page is view-only. This list is for displaying
        // eligible student/course pairs, not for issuing certificates here.
        $eligibleTrainees = DB::table('enrollment_tbls as e')
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
            ->orderBy('u.lastname')
            ->orderBy('u.firstname')
            ->get();

        // Use the same competency calculation as the student modules page:
        // (average quiz percentage + average onsite percentage) / 2.
        // Keep missing assessment categories as null rather than treating them as zero.
        $eligibleQuizAverages = DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->select(
                'qr.user_id',
                'q.course_id',
                DB::raw('AVG(COALESCE(qr.percentage, (qr.score / NULLIF(qr.total_items, 0)) * 100)) as average_percentage')
            )
            ->groupBy('qr.user_id', 'q.course_id')
            ->get()
            ->keyBy(fn ($row) => $row->user_id . '-' . $row->course_id);

        // Match Admin 1 exactly: onsite grades must belong to this enrollment.
        // This prevents grades from another enrollment/course instance being included.
        $eligibleOnsiteAverages = DB::table('onsite_grades as og')
            ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->select(
                'og.user_id',
                'og.enrollment_id',
                'oa.course_id',
                DB::raw('AVG(LEAST(100, GREATEST(0, (og.score / NULLIF(oa.max_score, 0)) * 100))) as average_percentage')
            )
            ->groupBy('og.user_id', 'og.enrollment_id', 'oa.course_id')
            ->get()
            ->keyBy(fn ($row) => $row->user_id . '-' . $row->course_id . '-' . $row->enrollment_id);

        $eligibleTrainees->transform(function ($trainee) use ($eligibleQuizAverages, $eligibleOnsiteAverages) {
            $quizKey = $trainee->user_id . '-' . $trainee->course_id;
            $onsiteKey = $trainee->user_id . '-' . $trainee->course_id . '-' . $trainee->enrollment_id;

            $quizRow = $eligibleQuizAverages->get($quizKey);
            $onsiteRow = $eligibleOnsiteAverages->get($onsiteKey);

            $quizAverage = $quizRow && $quizRow->average_percentage !== null
                ? round((float) $quizRow->average_percentage, 2)
                : null;
            $onsiteAverage = $onsiteRow && $onsiteRow->average_percentage !== null
                ? round((float) $onsiteRow->average_percentage, 2)
                : null;

            // Keep the same property names used by Admin 1's Blade.
            // The old e.progress value is stored completion progress (e.g. 6),
            // not the calculated quiz/onsite competency percentage.
            $trainee->quiz_average = $quizAverage;
            $trainee->onsite_average = $onsiteAverage;
            $calculatedProgress = ($quizAverage !== null && $onsiteAverage !== null)
                ? round(($quizAverage + $onsiteAverage) / 2, 2)
                : null;

            $trainee->progress = $calculatedProgress;
            $trainee->competency_score = $calculatedProgress;
            $trainee->competency_remarks = $calculatedProgress === null
                ? 'Not Assessed'
                : ($calculatedProgress >= 75 ? 'Competent' : 'Not Yet Competent');
            $trainee->competency_status = $trainee->competency_remarks;

            return $trainee;
        });

        // Safe defaults for the dashboard's chart scripts. Each chart variable
        // is passed explicitly so Blade never fails if a chart has no records.
        $traineeCourseLabels = [];
        $traineeCourseCounts = [];
        $monthlyEnrollmentLabels = [];
        $monthlyEnrollmentCounts = [];
        $quizPerformanceLabels = [];
        $quizPerformanceScores = [];
        $performanceCourseLabels = [];
        $courseQuizAverages = [];
        $courseOnsiteAverages = [];
        $competencyLabels = ['Competent', 'Not Yet Competent', 'Not Assessed'];
        $competencyStudentCounts = [0, 0, 0];
        $competentCourseLabels = [];
        $competentCourseCounts = [];
        $competencyRateLabels = [];
        $competencyRates = [];
        $assessedCourseCounts = [];
        $completionCourseLabels = [];
        $courseCompletionRates = [];
        $courseCompletedCounts = [];
        $courseEligibleCounts = [];
        $barangayLabels = [];
        $barangayCounts = [];
        $employmentLabels = [];
        $employmentCounts = [];
        $sexLabels = [];
        $sexCounts = [];
        $ageGroupLabels = [];
        $ageGroupCounts = [];

        // Registrations-based charts use the existing registration records.
        try {
            $barangayData = Registration::query()
                ->whereNotNull('address_barangay')->where('address_barangay', '<>', '')
                ->select('address_barangay', DB::raw('COUNT(*) as total'))
                ->groupBy('address_barangay')->orderByDesc('total')->get();
            $barangayLabels = $barangayData->pluck('address_barangay')->values()->all();
            $barangayCounts = $barangayData->pluck('total')->map(fn ($v) => (int) $v)->all();
        } catch (Throwable $e) {
            // Keep the read-only dashboard available if a deployment uses a
            // different registration schema; the chart simply remains empty.
        }

        try {
            $employmentData = Registration::query()
                ->whereNotNull('employment_status')->where('employment_status', '<>', '')
                ->select('employment_status', DB::raw('COUNT(*) as total'))
                ->groupBy('employment_status')->orderByDesc('total')->get();
            $employmentLabels = $employmentData->pluck('employment_status')->values()->all();
            $employmentCounts = $employmentData->pluck('total')->map(fn ($v) => (int) $v)->all();
        } catch (Throwable $e) {
            // Optional chart data; do not let a schema difference break the page.
        }

        try {
            $sexData = Registration::query()
                ->whereNotNull('sex')->where('sex', '<>', '')
                ->select('sex', DB::raw('COUNT(*) as total'))
                ->groupBy('sex')->orderByDesc('total')->get();
            $sexLabels = $sexData->pluck('sex')->values()->all();
            $sexCounts = $sexData->pluck('total')->map(fn ($v) => (int) $v)->all();
        } catch (Throwable $e) {
            // Optional chart data; do not let a schema difference break the page.
        }

        try {
            $ageGroups = [
                '17 and under' => [0, 17],
                '18–24' => [18, 24],
                '25–34' => [25, 34],
                '35–44' => [35, 44],
                '45–54' => [45, 54],
                '55+' => [55, 150],
            ];
            foreach ($ageGroups as $label => [$minAge, $maxAge]) {
                $ageGroupLabels[] = $label;
                $ageGroupCounts[] = (int) Registration::query()
                    ->whereNotNull('age')->whereBetween('age', [$minAge, $maxAge])->count();
            }
        } catch (Throwable $e) {
            $ageGroupLabels = [];
            $ageGroupCounts = [];
        }

        // Enrollment-by-course chart.
        try {
            $enrollmentData = DB::table('enrollment_tbls as e')
                ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
                ->select('c.title as course_title', DB::raw('COUNT(*) as total'))
                ->groupBy('c.id', 'c.title')->orderBy('c.title')->get();
            $traineeCourseLabels = $enrollmentData->pluck('course_title')->values()->all();
            $traineeCourseCounts = $enrollmentData->pluck('total')->map(fn ($v) => (int) $v)->all();
        } catch (Throwable $e) {
            // Optional chart data.
        }

        // GRAPH 2: Monthly Enrollment Trend — last 6 calendar months.
        try {
            $monthlyEnrollmentLabels = [];
            $monthlyEnrollmentCounts = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthDate = now()->startOfMonth()->subMonths($i);
                $monthlyEnrollmentLabels[] = $monthDate->format('M Y');
                $monthlyEnrollmentCounts[] = (int) DB::table('enrollment_tbls')
                    ->whereYear('enrolled_at', $monthDate->year)
                    ->whereMonth('enrolled_at', $monthDate->month)
                    ->count();
            }
        } catch (Throwable $e) {
            report($e);
            $monthlyEnrollmentLabels = [];
            $monthlyEnrollmentCounts = [];
        }

        // GRAPH 3: Quiz Performance by Course — copied from admin1 graph logic.
        try {
            $quizPerformance = DB::table('quiz_results as qr')
                ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
                ->join('course_tbls as c', 'c.id', '=', 'q.course_id')
                ->select(
                    'c.id as course_id',
                    'c.title as course_title',
                    DB::raw('AVG(COALESCE(qr.percentage, (qr.score / NULLIF(qr.total_items, 0)) * 100)) as average_percentage')
                )
                ->groupBy('c.id', 'c.title')
                ->orderBy('c.title')
                ->get();

            $quizPerformanceLabels = $quizPerformance->pluck('course_title')->all();
            $quizPerformanceScores = $quizPerformance->map(
                fn ($row) => round((float) $row->average_percentage, 2)
            )->all();

            // GRAPH 4: Quiz vs. Onsite Performance by Course.
            $onsitePerformance = DB::table('onsite_grades as og')
                ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
                ->join('course_tbls as c', 'c.id', '=', 'oa.course_id')
                ->whereNotNull('og.score')
                ->where('oa.max_score', '>', 0)
                ->select(
                    'c.id as course_id',
                    DB::raw('AVG((og.score / NULLIF(oa.max_score, 0)) * 100) as average_percentage')
                )
                ->groupBy('c.id')
                ->get()
                ->keyBy('course_id');

            $quizAveragesByCourse = $quizPerformance->keyBy('course_id');
            $performanceCourses = Course_tbl::orderBy('title')->get();
            $performanceCourseLabels = $performanceCourses->pluck('title')->all();
            $courseQuizAverages = [];
            $courseOnsiteAverages = [];

            foreach ($performanceCourses as $course) {
                $quizAverage = $quizAveragesByCourse->get($course->id);
                $onsiteAverage = $onsitePerformance->get($course->id);
                $courseQuizAverages[] = $quizAverage ? round((float) $quizAverage->average_percentage, 2) : null;
                $courseOnsiteAverages[] = $onsiteAverage ? round((float) $onsiteAverage->average_percentage, 2) : null;
            }

            // GRAPH 5: Competency Distribution. Both assessment categories are required.
            $quizByStudentCourse = DB::table('quiz_results as qr')
                ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
                ->select(
                    'qr.user_id', 'q.course_id',
                    DB::raw('AVG(COALESCE(qr.percentage, (qr.score / NULLIF(qr.total_items, 0)) * 100)) as average_percentage')
                )
                ->groupBy('qr.user_id', 'q.course_id')
                ->get()
                ->keyBy(fn ($row) => $row->user_id . '-' . $row->course_id);

            $onsiteByStudentCourse = DB::table('onsite_grades as og')
                ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
                ->whereNotNull('og.score')
                ->where('oa.max_score', '>', 0)
                ->select(
                    'og.user_id', 'oa.course_id',
                    DB::raw('AVG((og.score / NULLIF(oa.max_score, 0)) * 100) as average_percentage')
                )
                ->groupBy('og.user_id', 'oa.course_id')
                ->get()
                ->keyBy(fn ($row) => $row->user_id . '-' . $row->course_id);

            $competencyCounts = ['Competent' => 0, 'Not Yet Competent' => 0, 'Not Assessed' => 0];
            $competentCountsByCourse = [];
            $assessedCountsByCourse = [];
            $activeEnrollments = DB::table('enrollment_tbls')
                ->whereIn('status', ['active', 'completed'])
                ->select('user_id', 'course_id')->distinct()->get();

            foreach ($activeEnrollments as $enrollment) {
                $key = $enrollment->user_id . '-' . $enrollment->course_id;
                $quizResult = $quizByStudentCourse->get($key);
                $onsiteResult = $onsiteByStudentCourse->get($key);
                if (!$quizResult || !$onsiteResult || $quizResult->average_percentage === null || $onsiteResult->average_percentage === null) {
                    $competencyCounts['Not Assessed']++;
                    continue;
                }

                $overallScore = ((float) $quizResult->average_percentage + (float) $onsiteResult->average_percentage) / 2;
                $courseId = (string) $enrollment->course_id;
                $assessedCountsByCourse[$courseId] = ($assessedCountsByCourse[$courseId] ?? 0) + 1;

                if ($overallScore >= 75) {
                    $competencyCounts['Competent']++;
                    $competentCountsByCourse[$courseId] = ($competentCountsByCourse[$courseId] ?? 0) + 1;
                } else {
                    $competencyCounts['Not Yet Competent']++;
                }
            }

            $competencyLabels = array_keys($competencyCounts);
            $competencyStudentCounts = array_values($competencyCounts);

            // GRAPH 6: Competency Rate by Course.
            $competencyCourses = Course_tbl::orderBy('title')->get();
            $competencyRateLabels = $competencyCourses->pluck('title')->all();
            $competencyRates = [];
            $competentCourseLabels = $competencyRateLabels;
            $competentCourseCounts = [];
            $assessedCourseCounts = [];

            foreach ($competencyCourses as $course) {
                $id = (string) $course->id;
                $competent = (int) ($competentCountsByCourse[$id] ?? 0);
                $assessed = (int) ($assessedCountsByCourse[$id] ?? 0);
                $competentCourseCounts[] = $competent;
                $assessedCourseCounts[] = $assessed;
                $competencyRates[] = $assessed > 0 ? round(($competent / $assessed) * 100, 2) : 0;
            }

            // GRAPH 7: Course Completion Rate.
            $completionData = DB::table('enrollment_tbls as e')
                ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
                ->whereIn('e.status', ['active', 'completed'])
                ->select(
                    'c.id as course_id', 'c.title as course_title',
                    DB::raw('COUNT(*) as eligible_enrollments'),
                    DB::raw("SUM(CASE WHEN e.status = 'completed' THEN 1 ELSE 0 END) as completed_enrollments")
                )
                ->groupBy('c.id', 'c.title')
                ->orderBy('c.title')
                ->get();

            $completionCourseLabels = $completionData->pluck('course_title')->all();
            $courseCompletedCounts = $completionData->map(fn ($row) => (int) $row->completed_enrollments)->all();
            $courseEligibleCounts = $completionData->map(fn ($row) => (int) $row->eligible_enrollments)->all();
            $courseCompletionRates = $completionData->map(function ($row) {
                $total = (int) $row->eligible_enrollments;
                return $total > 0 ? round(((int) $row->completed_enrollments / $total) * 100, 2) : 0;
            })->all();
        } catch (Throwable $e) {
            // Log query errors instead of silently rendering every chart as blank.
            report($e);
        }

        return view('superadmin.dashboard', compact(
            'currentView', 'courses', 'allCourses', 'trainers', 'trainees',
            'trainersList', 'announcements', 'registrations', 'facilities',
            'certificates', 'eligibleTrainees',
            'traineeCourseLabels', 'traineeCourseCounts',
            'monthlyEnrollmentLabels', 'monthlyEnrollmentCounts',
            'quizPerformanceLabels', 'quizPerformanceScores',
            'performanceCourseLabels', 'courseQuizAverages', 'courseOnsiteAverages',
            'competencyLabels', 'competencyStudentCounts',
            'competentCourseLabels', 'competentCourseCounts',
            'competencyRateLabels', 'competencyRates', 'assessedCourseCounts',
            'completionCourseLabels', 'courseCompletionRates',
            'courseCompletedCounts', 'courseEligibleCounts',
            'barangayLabels', 'barangayCounts', 'employmentLabels', 'employmentCounts',
            'sexLabels', 'sexCounts', 'ageGroupLabels', 'ageGroupCounts'
        ));
    }
}
