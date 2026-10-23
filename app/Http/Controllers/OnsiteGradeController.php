<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\OnsiteActivity;

class OnsiteGradeController extends Controller
{
    /**
     * Store a new onsite activity for a course.
     */
    public function storeActivity(Request $request)
    {
        $validated = $request->validate([
            'course_id'     => 'required|exists:course_tbls,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'max_score'     => 'required|numeric|min:1',
            'activity_date' => 'required|date',
        ]);

        OnsiteActivity::create($validated);

        return back()->with(
            'success',
            'Onsite activity created successfully.'
        );
    }

    /**
     * Delete an activity and its associated grades.
     */
    public function destroyActivity(OnsiteActivity $activity)
    {
        DB::transaction(function () use ($activity) {
            DB::table('onsite_grades')
                ->where('onsite_activity_id', $activity->id)
                ->delete();

            $activity->delete();
        });

        return back()->with(
            'success',
            'Activity and associated grades deleted successfully.'
        );
    }

    /**
     * Fetch trainees and their recorded grades for the modal.
     *
     * Include active enrollments and previously graded enrollments
     * so an existing grade does not disappear just because the
     * enrollment status has changed.
     */
    public function getGrades(OnsiteActivity $activity)
    {
        $trainees = DB::table('enrollment_tbls')
            ->join(
                'user_tbls',
                'enrollment_tbls.user_id',
                '=',
                'user_tbls.id'
            )
            ->leftJoin('onsite_grades', function ($join) use ($activity) {
                $join->on(
                    'enrollment_tbls.id',
                    '=',
                    'onsite_grades.enrollment_id'
                )->where(
                    'onsite_grades.onsite_activity_id',
                    '=',
                    $activity->id
                );
            })
            ->where('enrollment_tbls.course_id', $activity->course_id)
            ->where(function ($query) use ($activity) {
                $query->where('enrollment_tbls.status', 'active')
                    ->orWhereExists(function ($subquery) use ($activity) {
                        $subquery->select(DB::raw(1))
                            ->from('onsite_grades as existing_grades')
                            ->whereColumn(
                                'existing_grades.enrollment_id',
                                'enrollment_tbls.id'
                            )
                            ->where(
                                'existing_grades.onsite_activity_id',
                                $activity->id
                            );
                    });
            })
            ->select(
                'user_tbls.id as user_id',
                'user_tbls.firstname',
                'user_tbls.lastname',
                'user_tbls.email',
                'user_tbls.id_number',
                'user_tbls.contact',
                'enrollment_tbls.id as enrollment_id',
                'enrollment_tbls.status as enrollment_status',
                'onsite_grades.id as grade_id',
                'onsite_grades.score',
                'onsite_grades.remarks'
            )
            ->orderBy('user_tbls.lastname')
            ->orderBy('user_tbls.firstname')
            ->get();

        return response()->json([
            'activity' => $activity,
            'trainees' => $trainees,
        ]);
    }

    /**
     * Save or update onsite grades.
     */
    public function storeGrades(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:onsite_activities,id',
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|integer|exists:enrollment_tbls,id',
            'grades.*.user_id' => 'required|integer|exists:user_tbls,id',
            'grades.*.score' => 'nullable|numeric|min:0',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        $activity = OnsiteActivity::findOrFail($validated['activity_id']);

        // Validate score limits using the selected activity's maximum.
        foreach ($validated['grades'] as $index => $entry) {
            $score = $entry['score'] ?? null;

            if (
                $score !== null
                && $score !== ''
                && (float) $score > (float) $activity->max_score
            ) {
                throw ValidationException::withMessages([
                    "grades.$index.score" =>
                        "The score cannot exceed {$activity->max_score}.",
                ]);
            }
        }

        $trainerId = auth()->id()
            ?? auth('trainer')->id();

        DB::transaction(function () use (
            $validated,
            $activity,
            $trainerId
        ) {
            foreach ($validated['grades'] as $entry) {
                // Confirm this enrollment belongs to this activity's course
                // and the submitted user matches the enrollment owner.
                $enrollment = DB::table('enrollment_tbls')
                    ->where('id', $entry['enrollment_id'])
                    ->where('course_id', $activity->course_id)
                    ->where('user_id', $entry['user_id'])
                    ->first();

                if (!$enrollment) {
                    throw ValidationException::withMessages([
                        'grades' =>
                            'A trainee enrollment does not match this activity.',
                    ]);
                }

                $score = $entry['score'] ?? null;
                $score = ($score !== null && $score !== '')
                    ? (float) $score
                    : null;

                $remarks = trim((string) ($entry['remarks'] ?? ''));
                $remarks = $remarks !== '' ? $remarks : null;

                // Do not create a blank grade for an untouched entry.
                if ($score === null && $remarks === null) {
                    continue;
                }

                $existing = DB::table('onsite_grades')
                    ->where('onsite_activity_id', $activity->id)
                    ->where('enrollment_id', $enrollment->id)
                    ->first();

                $payload = [
                    'user_id' => $enrollment->user_id,
                    'score' => $score,
                    'remarks' => $remarks,
                    'graded_by' => $trainerId,
                    'updated_at' => now(),
                ];

                if ($existing) {
                    DB::table('onsite_grades')
                        ->where('id', $existing->id)
                        ->update($payload);
                } else {
                    $payload['onsite_activity_id'] = $activity->id;
                    $payload['enrollment_id'] = $enrollment->id;
                    $payload['created_at'] = now();

                    DB::table('onsite_grades')->insert($payload);
                }
            }
        });

        return back()->with(
            'success',
            'Onsite activity grades saved successfully.'
        );
    }
}
