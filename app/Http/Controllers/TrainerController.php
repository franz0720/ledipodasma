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

class TrainerController extends Controller
{
    /**
     * Dashboard — single-course trainer view
     */
    public function dashboard()
    {
        $trainer = Auth::user();

        // Assigned course of the trainer
        $course = Course_tbl::where('trainer_id', $trainer->id)
            ->withCount('students')
            ->first();

        // Stat counts
        $totalStudents = $course ? $course->students_count : 0;
        $totalCourses  = Course_tbl::where('trainer_id', $trainer->id)->count();

        // Completion rate
        $totalEnrolled  = $course ? $course->students()->count() : 0;
        $totalCompleted = $course ? $course->students()->wherePivot('status', 'completed')->count() : 0;
        $completionRate = $totalEnrolled > 0
            ? round(($totalCompleted / $totalEnrolled) * 100)
            : 0;

        // Recent 5 students
        $recentStudents = $course
            ? $course->students()
                ->orderByPivot('created_at', 'desc')
                ->take(5)
                ->get()
            : collect();

        return view('trainer.teacher', compact(
            'course',
            'totalStudents',
            'totalCourses',
            'completionRate',
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

        $request->validate([
            'unit_number' => 'required|integer|min:1',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:51200', // up to 50MB
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
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $announcement = TrainerAnnouncement::findOrFail($id);

        $cleanComment = strip_tags($request->comment, '<b><i><u><strong><em><br>');

        $comment = AnnouncementComment::create([
            'announcement_id' => $announcement->id,
            'user_id'         => Auth::id(),
            'comment'         => $cleanComment,
        ]);

        // Load the author relationship to pass back to the front-end
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
        $trainer = Auth::user();
        $courses = Course_tbl::where('trainer_id', $trainer->id)->get();

        $students = User_tbl::whereHas('enrollments', fn($q) =>
                $q->whereIn('course_id', $courses->pluck('id'))
            )
            ->with(['enrollments' => fn($q) =>
                $q->whereIn('course_id', $courses->pluck('id'))->with('course')
            ])
            ->get()
            ->each(fn($u) => $u->setRelation('pivot', $u->enrollments->first()));

        return view('trainer.students', compact('students', 'courses'));
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
}
