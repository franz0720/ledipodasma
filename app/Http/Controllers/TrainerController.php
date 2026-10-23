<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Course_tbl;
use App\Models\User_tbl;
use App\Models\Module;
use App\Models\TrainerAnnouncement;
use App\Models\AnnouncementComment;
use App\Models\Course_tbl as Course;
use App\Models\OnsiteActivity; // <-- Replace with your actual model name
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TrainerController extends Controller
{
    /**
     * Dashboard — single-course trainer view
     */
 public function dashboard()
{
    $trainer = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | 1. TRAINER COURSES
    |--------------------------------------------------------------------------
    */
    $courses = Course_tbl::where('trainer_id', $trainer->id)
        ->withCount('students')
        ->get();

    $courseIds = $courses->pluck('id');

    // Preserve the existing single-course variable used by the dashboard.
    $course = $courses->first();

    /*
    |--------------------------------------------------------------------------
    | 2. ENROLLMENTS
    |--------------------------------------------------------------------------
    | All dashboard statistics are scoped to the authenticated trainer.
    */
    $enrollments = DB::table('enrollment_tbls as e')
        ->join('user_tbls as u', 'u.id', '=', 'e.user_id')
        ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
        ->whereIn('e.course_id', $courseIds)
        ->where(function ($query) {
            $query->whereNull('e.status')
                ->orWhere('e.status', '!=', 'dropped');
        })
        ->select([
            'e.id as enrollment_id',
            'e.user_id',
            'e.course_id',
            'e.status as enrollment_status',
            'e.progress',
            'e.created_at as enrollment_created_at',
            'u.firstname',
            'u.lastname',
            'u.email',
            'c.title as course_title',
        ])
        ->get();

    $enrollmentIds = $enrollments->pluck('enrollment_id');

    $totalTrainees = $enrollments->count();

    $monthlyEnrollment = $enrollments
        ->filter(function ($enrollment) {
            return $enrollment->enrollment_created_at
                && \Carbon\Carbon::parse(
                    $enrollment->enrollment_created_at
                )->isCurrentMonth();
        })
        ->count();

    $totalCompleted = $enrollments
        ->filter(function ($enrollment) {
            return strtolower(
                trim((string) $enrollment->enrollment_status)
            ) === 'completed';
        })
        ->count();

    $completionRate = $totalTrainees > 0
        ? (int) round(($totalCompleted / $totalTrainees) * 100)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | 3. QUIZ RESULTS
    |--------------------------------------------------------------------------
    | Results are grouped by course and trainee to avoid mixing scores
    | when the same trainee is enrolled in different courses.
    */
    $quizResults = DB::table('quiz_results as qr')
        ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
        ->whereIn('q.course_id', $courseIds)
        ->whereNotNull('qr.percentage')
        ->whereIn('qr.user_id', $enrollments->pluck('user_id')->unique())
        ->select([
            'qr.id',
            'qr.user_id',
            'qr.quiz_id',
            'qr.percentage',
            'q.course_id',
        ])
        ->get()
        ->groupBy(function ($result) {
            return $result->course_id . ':' . $result->user_id;
        });

    /*
    |--------------------------------------------------------------------------
    | 4. ONSITE ACTIVITIES AND GRADES
    |--------------------------------------------------------------------------
    */
    $onsiteActivities = DB::table('onsite_activities')
        ->whereIn('course_id', $courseIds)
        ->get()
        ->keyBy('id');

    $onsiteGrades = DB::table('onsite_grades as og')
        ->join(
            'onsite_activities as oa',
            'oa.id',
            '=',
            'og.onsite_activity_id'
        )
        ->whereIn('oa.course_id', $courseIds)
        ->whereIn('og.enrollment_id', $enrollmentIds)
        ->whereNotNull('og.score')
        ->select([
            'og.id',
            'og.enrollment_id',
            'og.user_id',
            'og.onsite_activity_id',
            'og.score',
            'oa.course_id',
            'oa.max_score',
        ])
        ->get()
        ->groupBy('enrollment_id');

    /*
    |--------------------------------------------------------------------------
    | 5. CALCULATE ASSESSMENT PERFORMANCE
    |--------------------------------------------------------------------------
    | Quiz average = average recorded quiz percentages.
    | Onsite average = average valid onsite score percentages.
    | Overall = (quiz average + onsite average) / 2.
    |
    | Missing either category = Pending Assessment.
    | 75% or above = Competent.
    | Below 75% = Not Yet Competent.
    |
    | Course completion remains separate from assessment performance.
    */
    $performances = collect();

    foreach ($enrollments as $enrollment) {
        $userId = $enrollment->user_id;
        $courseId = $enrollment->course_id;

        $resultKey = $courseId . ':' . $userId;

        $studentQuizResults = $quizResults->get(
            $resultKey,
            collect()
        );

        $quizPercentages = $studentQuizResults
            ->pluck('percentage')
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->map(function ($value) {
                return min(100, max(0, (float) $value));
            });

        $quizAverage = $quizPercentages->isNotEmpty()
            ? round($quizPercentages->avg(), 2)
            : null;

        // Onsite grades belong to a specific enrollment.
        $studentGrades = $onsiteGrades->get(
            $enrollment->enrollment_id,
            collect()
        );

        $onsitePercentages = $studentGrades
            ->map(function ($grade) {
                $maxScore = (float) $grade->max_score;

                if ($maxScore <= 0) {
                    return null;
                }

                return min(
                    100,
                    max(
                        0,
                        ((float) $grade->score / $maxScore) * 100
                    )
                );
            })
            ->filter(function ($value) {
                return $value !== null;
            });

        $onsiteAverage = $onsitePercentages->isNotEmpty()
            ? round($onsitePercentages->avg(), 2)
            : null;

        $isPending = $quizAverage === null
            || $onsiteAverage === null;

        $overallScore = $isPending
            ? null
            : round(($quizAverage + $onsiteAverage) / 2, 2);

        if ($isPending) {
            $assessmentStatus = 'Pending Assessment';
        } elseif ($overallScore >= 75) {
            $assessmentStatus = 'Competent';
        } else {
            $assessmentStatus = 'Not Yet Competent';
        }

        $performances->push((object) [
            'enrollment_id' => $enrollment->enrollment_id,
            'user_id' => $userId,
            'course_id' => $courseId,

            'user' => (object) [
                'firstname' => $enrollment->firstname,
                'lastname' => $enrollment->lastname,
                'email' => $enrollment->email,
            ],

            'course' => (object) [
                'title' => $enrollment->course_title,
            ],

            'progress' => min(
                100,
                max(0, (float) ($enrollment->progress ?? 0))
            ),

            'quiz_average' => $quizAverage,
            'onsite_average' => $onsiteAverage,
            'assessment_score' => $overallScore,
            'assessment_status' => $assessmentStatus,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. LOW-PERFORMING TRAINEES
    |--------------------------------------------------------------------------
    */
    $lowPerforming = $performances
        ->filter(function ($student) {
            return $student->assessment_status === 'Not Yet Competent';
        })
        ->sortBy('assessment_score')
        ->take(10)
        ->values();

    /*
    |--------------------------------------------------------------------------
    | 7. URGENT ONSITE ASSESSMENTS
    |--------------------------------------------------------------------------
    | Count scheduled activities from today through the next three days.
    */
    $today = now()->toDateString();
    $urgentUntil = now()->addDays(3)->toDateString();

    $urgentAssessments = DB::table('onsite_activities')
        ->whereIn('course_id', $courseIds)
        ->whereDate('activity_date', '>=', $today)
        ->whereDate('activity_date', '<=', $urgentUntil)
        ->count();

    /*
    |--------------------------------------------------------------------------
    | 8. COURSE COMPLETION DISTRIBUTION
    |--------------------------------------------------------------------------
    | Categories match the dashboard legend.
    */
    $normalizedProgress = $enrollments->map(function ($enrollment) {
        return min(
            100,
            max(0, (float) ($enrollment->progress ?? 0))
        );
    });

    $progressDistribution = [
        $normalizedProgress->filter(fn ($p) => $p >= 100)->count(),
        $normalizedProgress->filter(fn ($p) => $p >= 50 && $p < 100)->count(),
        $normalizedProgress->filter(fn ($p) => $p > 0 && $p < 50)->count(),
        $normalizedProgress->filter(fn ($p) => $p == 0)->count(),
    ];

    /*
    |--------------------------------------------------------------------------
    | 9. WEEKLY PROGRESS CHART
    |--------------------------------------------------------------------------
    | Generate actual date labels alongside the daily averages.
    */
    $weeklyDates = collect(range(6, 0))
        ->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->startOfDay();
        })
        ->values();

    $weeklyProgressLabels = $weeklyDates
        ->map(fn ($date) => $date->format('D'))
        ->all();

    $weeklyProgressData = $weeklyDates
        ->map(function ($date) use ($enrollmentIds) {
            $average = DB::table('daily_progress')
                ->whereIn('enrollment_id', $enrollmentIds)
                ->whereDate('date', $date->toDateString())
                ->avg('progress_percent');

            return round((float) ($average ?? 0), 2);
        })
        ->all();

    /*
    |--------------------------------------------------------------------------
    | 10. ATTENDANCE REMINDER
    |--------------------------------------------------------------------------
    */
    $attendanceTakenToday = DB::table('attendances')
        ->whereIn('enrollment_id', $enrollmentIds)
        ->whereDate('date', $today)
        ->where('marked_by', $trainer->id)
        ->exists();

    /*
    |--------------------------------------------------------------------------
    | 11. RECENT STUDENTS
    |--------------------------------------------------------------------------
    | Preserve the existing view-compatible student collection for now.
    */
    $recentStudents = $course
        ? $course->students()
            ->orderByPivot('created_at', 'desc')
            ->take(5)
            ->get()
        : collect();

    /*
    |--------------------------------------------------------------------------
    | 12. RETURN DASHBOARD
    |--------------------------------------------------------------------------
    | Preserve the existing view and its variables.
    */
    return view('trainer.teacher', compact(
        'course',
        'courses',
        'totalTrainees',
        'completionRate',
        'monthlyEnrollment',
        'urgentAssessments',
        'progressDistribution',
        'weeklyProgressLabels',
        'weeklyProgressData',
        'attendanceTakenToday',
        'lowPerforming',
        'recentStudents'
    ));
}

    public function updateSyllabus(Request $request, $id)
{
    $course = \App\Models\Course_tbl::findOrFail($id);

    $validated = $request->validate([
        'syllabus_title'   => 'nullable|string|max:255',
        'syllabus_content' => 'nullable|string',
        'syllabus_file'    => 'nullable|file|mimes:pdf,docx,doc|max:10240',
    ]);

    $course->syllabus_title = $validated['syllabus_title'] ?? $course->syllabus_title;
    $course->syllabus_content = $validated['syllabus_content'] ?? null;

    if ($request->hasFile('syllabus_file')) {
        if ($course->syllabus_path && Storage::disk('public')->exists($course->syllabus_path)) {
            Storage::disk('public')->delete($course->syllabus_path);
        }

        $course->syllabus_path = $request->file('syllabus_file')->store("courses/{$course->id}/syllabus", 'public');
    }

    $course->save();

    return back()->with('success', 'Syllabus updated successfully.');
}

public function deleteSyllabus($id)
{
    $course = \App\Models\Course_tbl::findOrFail($id);

    if ($course->syllabus_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->syllabus_path)) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($course->syllabus_path);
    }

    $course->syllabus_title = null;
    $course->syllabus_content = null;
    $course->syllabus_path = null;
    $course->save();

    return back()->with('success', 'Syllabus removed successfully.');
}

    /**
     * My Courses list
     */
    public function courses()
    {
        $trainer = Auth::user();
        $courses = Course_tbl::where('trainer_id', $trainer->id)
            ->withCount('students')
            ->get();

        return view('trainer.courses', compact('courses'));
    }

    /**
     * Course Preview / Detail
     */
    public function courseShow($id)
{
    $trainer = Auth::user();
    $course  = Course_tbl::where('trainer_id', $trainer->id)
        ->with([
            'students',
            'modules' => fn($q) => $q->orderBy('unit_number', 'asc')->orderBy('order', 'asc'),
            'trainerAnnouncements.trainer',
            'trainerAnnouncements.comments.user',
        ])
        ->findOrFail($id);

    $modules = $course->modules ?? collect();

    // Fetch the onsite activities for this course
    $onsiteActivities = OnsiteActivity::where('course_id', $course->id)
        ->orderBy('activity_date', 'desc')
        ->get();

    // Pass 'onsiteActivities' to the view
    return view('trainer.course-preview', compact('course', 'modules', 'onsiteActivities'));
}

    /**
     * Store Curriculum Module (Trainer Action)
     */
    public function storeModule(Request $request, $id)
    {
        // Ensure the course belongs to the authenticated trainer
        $course = Course_tbl::where('trainer_id', Auth::id())->findOrFail($id);

        // Normalize title spacing before duplicate validation.
        $request->merge([
            'title' => preg_replace('/\s+/', ' ', trim((string) $request->input('title', ''))),
        ]);

        $request->validate([
            'unit_number' => 'required|integer|min:1',
            'title'       => [
                'required',
                'string',
                'max:255',
                Rule::unique((new Module())->getTable(), 'title')
                    ->where(function ($query) use ($course) {
                        return $query->where('course_id', $course->id);
                    }),
            ],
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:51200', // up to 50MB
        ], [
            'title.unique' => 'A module with this title already exists in this course. Please choose a different title.',
        ]);

        $filePath = null;
        $fileType = 'pdf';
        $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // 1. Preserve original file name (with timestamp to prevent collisions)
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext          = strtolower($file->getClientOriginalExtension());
            $mime         = $file->getMimeType();

            // Sanitize filename and create readable name (e.g. 1727483000_Lecture_Notes.pdf)
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
            $fileName  = time() . '_' . $cleanName . '.' . $ext;

            // Store in public disk: storage/app/public/courses/{id}/modules/{fileName}
            $filePath = $file->storeAs("courses/{$course->id}/modules", $fileName, 'public');

            // 2. Map extension to table enum ('pdf', 'docx', 'video', 'other')
            if ($ext === 'pdf') {
                $fileType = 'pdf';
            } elseif (in_array($ext, ['doc', 'docx'])) {
                $fileType = 'docx';
            } elseif (str_starts_with($mime, 'video/') || in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm'])) {
                $fileType = 'video';
            } else {
                $fileType = 'other';
            }

            // 3. Format human-readable file size string for varchar(255)
            $bytes = $file->getSize();
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $fileSize = $bytes . ' B';
            }
        }

        // 4. Retrieve unit number
        $unitNumber = max(1, (int) $request->input('unit_number', 1));

        // Calculate next lesson order within THIS specific unit
        $lastLessonInUnit = Module::where('course_id', $course->id)
            ->where('unit_number', $unitNumber)
            ->max('order') ?? 0;

        $nextLessonOrder = $lastLessonInUnit + 1;

        // 5. Create the module record in database
        $module = Module::create([
            'course_id'   => $course->id,
            'title'       => $request->input('title'),
            'description' => $request->input('description'),
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_size'   => $fileSize,
            'order'       => $nextLessonOrder, // Lesson number inside this unit (1, 2, 3...)
            'unit_number' => $unitNumber,      // Correctly groups to the chosen unit
            'is_active'   => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Module uploaded successfully!',
            'module'  => $module
        ], 201);
    }

    /**
     * Delete Curriculum Module (Trainer Action)
     */
    public function deleteModule($id)
    {
        // Make sure module belongs to a course owned by this trainer
        $module = Module::whereHas('course', function ($q) {
            $q->where('trainer_id', Auth::id());
        })->findOrFail($id);

        if ($module->file_path && Storage::disk('public')->exists($module->file_path)) {
            Storage::disk('public')->delete($module->file_path);
        }

        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Module deleted successfully.'
        ]);
    }

    /**
     * Store Announcement (Trainer Action)
     */
    public function storeAnnouncement(Request $request, $id)
    {
        // Authorize: Ensure the course exists and belongs to the authenticated trainer
        $course = Course_tbl::where('trainer_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'content'         => 'required|string',
            'target_audience' => 'nullable|string|max:50',
        ]);

        // Prevent empty rich text submissions (e.g. "<p><br></p>" or "&nbsp;")
        $plainText = trim(html_entity_decode(strip_tags($validated['content'])));
        if (empty($plainText)) {
            return response()->json([
                'success' => false,
                'message' => 'The announcement content cannot be empty.',
            ], 422);
        }

        // Sanitize allowed tags for safe rendering
        $allowedTags = '<b><i><u><ul><ol><li><p><br><strong><em><span>';
        $cleanContent = strip_tags($validated['content'], $allowedTags);

        $announcement = TrainerAnnouncement::create([
            'course_id'       => $course->id,
            'trainer_id'      => Auth::id(),
            'content'         => $cleanContent,
            'target_audience' => $validated['target_audience'] ?? 'all_students',
        ]);

        $announcement->load('trainer');

        return response()->json([
            'success' => true,
            'message' => 'Announcement posted successfully!',
            'data'    => $announcement,
        ], 201);
    }

    /**
     * Update an existing announcement
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $plainText = trim(html_entity_decode(strip_tags($validated['content'])));
        if (empty($plainText)) {
            return response()->json([
                'success' => false,
                'message' => 'The announcement content cannot be empty.',
            ], 422);
        }

        $allowedTags = '<b><i><u><ul><ol><li><p><br><strong><em><span>';
        $cleanContent = strip_tags($validated['content'], $allowedTags);

        $announcement = TrainerAnnouncement::where('id', $id)
            ->where('trainer_id', Auth::id())
            ->firstOrFail();

        $announcement->update([
            'content' => $cleanContent,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully.',
            'data'    => $announcement,
        ]);
    }

    /**
     * Delete an announcement
     */
    public function destroyAnnouncement($id)
    {
        $announcement = TrainerAnnouncement::where('id', $id)
            ->where('trainer_id', Auth::id())
            ->firstOrFail();

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully.',
        ]);
    }

    /**
     * Store Announcement Comment
     */
    
/**
 * Store Announcement Comment and Notify Students
 */
public function storeComment(Request $request, $id)
{
    $validated = $request->validate([
        'comment' => 'required|string|max:2000',
    ]);

    // Only allow the trainer assigned to this announcement to comment.
    $announcement = TrainerAnnouncement::where('trainer_id', Auth::id())
        ->findOrFail($id);

    $cleanComment = trim(
        strip_tags($validated['comment'], '<b><i><u><strong><em><br>')
    );

    if ($cleanComment === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please enter a valid comment.',
        ], 422);
    }

    // Save the comment while keeping the existing model structure.
    $comment = AnnouncementComment::create([
        'announcement_id' => $announcement->id,
        'user_id'         => Auth::id(),
        'comment'         => $cleanComment,
    ]);

    // Find students who previously commented on this announcement.
    $studentIds = AnnouncementComment::where(
            'announcement_id',
            $announcement->id
        )
        ->where('user_id', '!=', Auth::id())
        ->distinct()
        ->pluck('user_id');

    // Create notifications for those previous commenters.
    if ($studentIds->isNotEmpty()) {
        $now = now();

        $notifications = $studentIds->map(function ($studentId) use (
            $announcement,
            $comment,
            $now
        ) {
            return [
                'user_id'         => $studentId,
                'announcement_id' => $announcement->id,
                'comment_id'      => $comment->id,
                'type'             => 'comment_reply',
                'message'          => 'Your trainer posted a comment on an announcement you participated in.',
                'read_at'          => null,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        })->all();

        DB::table('comment_notifications')->insert($notifications);
    }

    // Preserve the existing response format for the frontend.
    $comment->load('user');

    return response()->json([
        'success' => true,
        'message' => 'Comment added successfully.',
        'comment' => $comment,
    ]);
}


    /**
     * Update Course Banner (Upload photo OR Select preset photo)
     */
    public function updateAppearance(Request $request, $id)
    {
        // Ensure course belongs to authenticated trainer
        $course = Course_tbl::where('trainer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'preset_image' => 'nullable|string',
        ]);

        if ($request->hasFile('banner_image')) {
            // Delete previous uploaded custom banner if in public storage
            if ($course->banner_image && str_starts_with($course->banner_image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $course->banner_image));
            }

            $path = $request->file('banner_image')->store('course_banners', 'public');
            $course->banner_image = 'storage/' . $path;
        } elseif ($request->filled('preset_image')) {
            $course->banner_image = $request->preset_image;
        }

        $course->save();

        return response()->json([
            'success'    => true,
            'banner_url' => asset($course->banner_image),
        ]);
    }

    /**
     * Save Course Description
     */
    public function updateDescription(Request $request, $id)
    {
        $request->validate([
            'description' => 'nullable|string|max:5000',
        ]);

        // Ensure course belongs to authenticated trainer
        $course = Course_tbl::where('trainer_id', Auth::id())->findOrFail($id);
        $course->description = $request->description;
        $course->save();

        return response()->json(['success' => true]);
    }

    /**
     * Save Course Objectives
     */
    public function updateObjectives(Request $request, $id)
    {
        $request->validate([
            'objectives' => 'nullable|string|max:5000',
        ]);

        // Ensure course belongs to authenticated trainer
        $course = Course_tbl::where('trainer_id', Auth::id())->findOrFail($id);
        $course->objectives = $request->objectives;
        $course->save();

        return response()->json(['success' => true]);
    }

    /**
     * Enrolled students list
     */
    
public function students(Request $request)
{
    $trainerId = Auth::id();

    // 1. Courses assigned to this trainer.
    $courses = Course_tbl::where('trainer_id', $trainerId)
        ->orderBy('title')
        ->get();

    $courseIds = $courses->pluck('id');

    if ($courseIds->isEmpty()) {
        return view('trainer.students', [
            'students' => collect(),
            'courses' => $courses,
        ]);
    }

    // 2. Fetch enrollments and student information.
    $enrollments = DB::table('enrollment_tbls as e')
        ->join('user_tbls as u', 'u.id', '=', 'e.user_id')
        ->join('course_tbls as c', 'c.id', '=', 'e.course_id')
        ->whereIn('e.course_id', $courseIds)
        ->where('e.status', '!=', 'dropped')
        ->select([
            'e.id as enrollment_id',
            'e.user_id',
            'e.course_id',
            'e.status as enrollment_status',
            'e.progress',
            'e.enrolled_at',
            'e.created_at as enrollment_created_at',
            'u.firstname',
            'u.lastname',
            'u.email',
            'c.title as course_title',
        ])
        ->orderBy('u.lastname')
        ->orderBy('u.firstname')
        ->get();

    // 3. Calculate scores and completion for each enrollment.
    $students = $enrollments->map(function ($enrollment) {

        /*
        |--------------------------------------------------------------------------
        | Quiz definitions and results
        |--------------------------------------------------------------------------
        */
        $quizIds = DB::table('quizzes')
            ->where('course_id', $enrollment->course_id)
            ->pluck('id');

        $totalQuizzes = $quizIds->count();

        $quizResults = DB::table('quiz_results')
            ->where('user_id', $enrollment->user_id)
            ->whereIn('quiz_id', $quizIds)
            ->get([
                'quiz_id',
                'score',
                'total_items',
                'percentage',
                'status',
            ]);

        // Calculate average quiz performance from recorded results.
        $quizPercentages = $quizResults
            ->map(function ($result) {
                if (
                    $result->percentage !== null &&
                    is_numeric($result->percentage)
                ) {
                    return max(
                        0,
                        min(100, (float) $result->percentage)
                    );
                }

                if (
                    is_numeric($result->score) &&
                    is_numeric($result->total_items) &&
                    (float) $result->total_items > 0
                ) {
                    return max(
                        0,
                        min(
                            100,
                            ((float) $result->score /
                                (float) $result->total_items) * 100
                        )
                    );
                }

                return null;
            })
            ->filter(fn ($score) => $score !== null)
            ->values();

        $quizAverage = $quizPercentages->isNotEmpty()
            ? round($quizPercentages->avg(), 2)
            : null;

        // A quiz is completed only when it has at least one passing result.
        $passedQuizIds = $quizResults
            ->filter(function ($result) {
                return strtolower((string) $result->status) === 'passed';
            })
            ->pluck('quiz_id');

        // Include passing attempts recorded in quiz_attempt_histories.
        if ($totalQuizzes > 0) {
            $historyPassedQuizIds = DB::table('quiz_attempt_histories')
                ->where('user_id', $enrollment->user_id)
                ->whereIn('quiz_id', $quizIds)
                ->whereRaw('LOWER(status) = ?', ['passed'])
                ->pluck('quiz_id');

            $passedQuizIds = $passedQuizIds
                ->merge($historyPassedQuizIds)
                ->unique()
                ->values();
        }

        $completedQuizzes = $passedQuizIds->count();

        /*
        |--------------------------------------------------------------------------
        | Onsite activities and grades
        |--------------------------------------------------------------------------
        */
        $onsiteActivities = DB::table('onsite_activities')
            ->where('course_id', $enrollment->course_id)
            ->get([
                'id',
                'max_score',
            ]);

        $totalOnsiteActivities = $onsiteActivities->count();

        $onsiteGrades = DB::table('onsite_grades as og')
            ->join(
                'onsite_activities as oa',
                'oa.id',
                '=',
                'og.onsite_activity_id'
            )
            ->where('og.enrollment_id', $enrollment->enrollment_id)
            ->where('og.user_id', $enrollment->user_id)
            ->where('oa.course_id', $enrollment->course_id)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->select([
                'oa.id as activity_id',
                'og.score',
                'oa.max_score',
            ])
            ->get();

        $onsitePercentages = $onsiteGrades->map(function ($grade) {
            return max(
                0,
                min(
                    100,
                    ((float) $grade->score /
                        (float) $grade->max_score) * 100
                )
            );
        });

        $onsiteAverage = $onsitePercentages->isNotEmpty()
            ? round($onsitePercentages->avg(), 2)
            : null;

        $completedOnsiteActivities = $onsiteGrades
            ->pluck('activity_id')
            ->unique()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Actual course completion
        |--------------------------------------------------------------------------
        */
        $totalAssessments = $totalQuizzes + $totalOnsiteActivities;

        $completedAssessments = $completedQuizzes
            + $completedOnsiteActivities;

        $courseProgress = $totalAssessments > 0
            ? round(
                ($completedAssessments / $totalAssessments) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Overall assessment and competency
        |--------------------------------------------------------------------------
        */
        // Keep the same equal-weight calculation when both categories
        // have recorded scores. Completion is not a grade.
        $overallScore = (
            $quizAverage !== null &&
            $onsiteAverage !== null
        )
            ? round(($quizAverage + $onsiteAverage) / 2, 2)
            : null;

        $assessmentStatus = $overallScore === null
            ? 'Pending Assessment'
            : ($overallScore >= 75
                ? 'Competent'
                : 'Not Yet Competent');

        /*
        |--------------------------------------------------------------------------
        | Return student data for the Blade view
        |--------------------------------------------------------------------------
        */
        return (object) [
            'enrollment_id' => $enrollment->enrollment_id,
            'user_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,

            'firstname' => $enrollment->firstname ?? '',
            'lastname' => $enrollment->lastname ?? '',
            'email' => $enrollment->email ?? '',
            'course_title' => $enrollment->course_title ?? '',

            'enrollment_status' => $enrollment->enrollment_status,
            'enrolled_at' => $enrollment->enrolled_at
                ?? $enrollment->enrollment_created_at,

            // Actual completion progress, not the stored enrollment value.
            'progress' => $courseProgress,
            'course_progress' => $courseProgress,

            'total_quizzes' => $totalQuizzes,
            'completed_quizzes' => $completedQuizzes,

            'total_onsite_activities' => $totalOnsiteActivities,
            'completed_onsite_activities' => $completedOnsiteActivities,

            'total_assessments' => $totalAssessments,
            'completed_assessments' => $completedAssessments,

            'quiz_average' => $quizAverage,
            'onsite_average' => $onsiteAverage,
            'assessment_score' => $overallScore,
            'assessment_status' => $assessmentStatus,
        ];
    });

    return view('trainer.students', [
        'students' => $students,
        'courses' => $courses,
    ]);
}


    /**
     * Schedules
     */
    public function schedule()
    {
        return view('trainer.schedule', [
            'schedules' => collect(),
        ]);
    }

    /**
     * Assessments
     */
    public function assessment(Request $request)
    {
        $trainer = Auth::user();
        $courses = Course_tbl::where('trainer_id', $trainer->id)->get();

        // 1. Fetch activities for all courses taught by this trainer
        // (or filter by selected course if you have a dropdown)
        $selectedCourseId = $request->get('course_id');

        $onsiteActivities = OnsiteActivity::whereIn('course_id', $courses->pluck('id'))
            ->when($selectedCourseId, function ($query, $selectedCourseId) {
                return $query->where('course_id', $selectedCourseId);
            })
            ->orderBy('activity_date', 'desc')
            ->get();

        // 2. Pass 'onsiteActivities' to the view
        return view('trainer.assessment', [
            'courses'          => $courses,
            'selectedCourseId' => $selectedCourseId,
            'assessments'      => collect(),
            'onsiteActivities' => $onsiteActivities, // <-- This was missing
        ]);
    }


public function attendance()
{
    $trainer = Auth::user();

    // Get the trainer's assigned course.
    $course = Course_tbl::where('trainer_id', $trainer->id)->first();

    $today = Carbon::today()->format('F d, Y');
    $todayDate = Carbon::today()->toDateString();

    $inSessionToday = false;
    $enrollments = collect();
    $students = collect();
    $existing = [];
    $dailyAttendance = collect();

    if ($course) {
        // Retrieve enrolled trainees from the correct tables.
        $enrollments = DB::table('enrollment_tbls as e')
            ->join('user_tbls as u', 'u.id', '=', 'e.user_id')
            ->where('e.course_id', $course->id)
            ->where(function ($query) {
                $query->whereNull('e.status')
                    ->orWhere('e.status', '!=', 'dropped');
            })
            ->select([
                'e.id as enrollment_id',
                'e.user_id',
                'u.firstname',
                'u.lastname',
            ])
            ->get();

        // The Blade view uses the same enrolled trainee collection.
        $students = $enrollments;

        // TEMPORARY: Replace with your actual course schedule check.
        $inSessionToday = true;

        $enrollmentIds = $enrollments->pluck('enrollment_id');

        if ($enrollmentIds->isNotEmpty()) {

            // Load previously recorded attendance for today.
            $existing = DB::table('attendances')
                ->whereIn('enrollment_id', $enrollmentIds)
                ->whereDate('date', $todayDate)
                ->where('marked_by', $trainer->id)
                ->pluck('status', 'enrollment_id')
                ->toArray();

            // Retrieve saved attendance history for this course.
            $attendanceRecords = DB::table('attendances as a')
                ->join(
                    'enrollment_tbls as e',
                    'e.id',
                    '=',
                    'a.enrollment_id'
                )
                ->join(
                    'user_tbls as u',
                    'u.id',
                    '=',
                    'e.user_id'
                )
                ->where('e.course_id', $course->id)
                ->whereIn('a.enrollment_id', $enrollmentIds)
                ->where('a.marked_by', $trainer->id)
                ->select([
                    'a.enrollment_id',
                    'a.date as attendance_date',
                    'a.status',
                    'u.firstname',
                    'u.lastname',
                ])
                ->orderByDesc('a.date')
                ->get();

            // Group records by training date, newest date first.
            $dailyAttendance = $attendanceRecords
                ->groupBy(function ($record) {
                    return Carbon::parse(
                        $record->attendance_date
                    )->toDateString();
                })
                ->sortKeysDesc()
                ->map(function ($records, $date) {
                    return [
                        'date' => $date,

                        'label' => Carbon::parse($date)
                            ->format('F d, Y'),

                        'records' => $records
                            ->map(function ($record) {
                                $firstName = trim(
                                    (string) ($record->firstname ?? '')
                                );

                                $lastName = trim(
                                    (string) ($record->lastname ?? '')
                                );

                                return (object) [
                                    'enrollment_id' =>
                                        $record->enrollment_id,

                                    'student_name' => trim(
                                        $firstName . ' ' . $lastName
                                    ) ?: 'Unnamed trainee',

                                    'status' => strtolower(
                                        (string) $record->status
                                    ),
                                ];
                            })
                            ->values(),
                    ];
                })
                ->values();
        }
    }

    return view('trainer.attendance', compact(
        'today',
        'course',
        'inSessionToday',
        'enrollments',
        'students',
        'existing',
        'dailyAttendance'
    ));
}


    /**
     * Save attendance for trainees enrolled in a course owned by this trainer.
     * Accepts either attendance[enrollment_id] or status[enrollment_id]
     * from the attendance form.
     */
    public function storeAttendance(Request $request)
    {
        $trainer = Auth::user();

        $validated = $request->validate([
            'date' => ['nullable', 'date'],
            // The current attendance Blade submits only checked absent[] boxes.
            'absent' => ['nullable', 'array'],
            'absent.*' => ['integer'],
            // Also accept explicit status arrays if the form is updated later.
            'attendance' => ['nullable', 'array'],
            'attendance.*' => ['nullable', 'string', 'in:present,absent,late,excused'],
            'status' => ['nullable', 'array'],
            'status.*' => ['nullable', 'string', 'in:present,absent,late,excused'],
        ]);

        $date = Carbon::parse($validated['date'] ?? Carbon::today())->toDateString();

        // Match attendance() and ensure the trainer can only mark their assigned course.
        $course = Course_tbl::where('trainer_id', $trainer->id)->first();
        if (!$course) {
            return back()->withErrors(['attendance' => 'No course is assigned to your trainer account.']);
        }

        // Keep each enrollment's trainee user_id because attendances.user_id is required.
        $enrollmentUserIds = DB::table('enrollment_tbls')
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->whereNull('status')->orWhere('status', '!=', 'dropped');
            })
            ->pluck('user_id', 'id')
            ->mapWithKeys(fn ($userId, $enrollmentId) => [(string) $enrollmentId => $userId]);

        $allowedEnrollmentIds = $enrollmentUserIds->keys()->all();

        // This Blade uses absent[] checkboxes: checked = absent, unchecked = present.
        // Unchecked boxes are not sent by HTML, so create a status for every active enrolment.
        $absentIds = array_map('strval', $validated['absent'] ?? []);
        $submittedAttendance = $validated['attendance'] ?? $validated['status'] ?? null;

        if ($submittedAttendance !== null) {
            $entries = $submittedAttendance;
        } else {
            $entries = [];
            foreach ($allowedEnrollmentIds as $enrollmentId) {
                $entries[$enrollmentId] = in_array($enrollmentId, $absentIds, true)
                    ? 'absent'
                    : 'present';
            }
        }

        $saved = 0;
        foreach ($entries as $enrollmentId => $status) {
            if (!in_array((string) $enrollmentId, $allowedEnrollmentIds, true)) {
                continue;
            }
            if (!in_array($status, ['present', 'absent', 'late', 'excused'], true)) {
                continue;
            }

            DB::table('attendances')->updateOrInsert(
                [
                    'enrollment_id' => (int) $enrollmentId,
                    'date' => $date,
                    'marked_by' => $trainer->id,
                ],
                [
                    'user_id' => $enrollmentUserIds->get((string) $enrollmentId),
                    'status' => strtolower(trim($status)),
                ]
            );
            $saved++;
        }

        if ($saved === 0) {
            return back()->withErrors([
                'attendance' => 'No active trainees were available to save attendance. Please refresh the page and try again.',
            ])->withInput();
        }

        return redirect()
            ->route('trainer.attendance')
            ->with('success', 'Attendance saved for ' . $saved . ' trainee(s) on ' . Carbon::parse($date)->format('F d, Y') . '.');
    }


}


