<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OnsiteActivity;
use App\Models\OnsiteGrade;

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

        return back()->with('success', 'Onsite activity created successfully.');
    }

    /**
     * Delete an activity and its associated grades.
     */
    public function destroyActivity(OnsiteActivity $activity)
    {
        $activity->delete();

        return back()->with('success', 'Activity deleted successfully.');
    }

    /**
     * Fetch trainees and their recorded grades for the modal via AJAX.
     */
    public function getGrades(OnsiteActivity $activity)
    {
        // Query active enrollments for this course and left-join existing grades
        $trainees = DB::table('enrollment_tbls')
            ->join('user_tbls', 'enrollment_tbls.user_id', '=', 'user_tbls.id')
            ->leftJoin('onsite_grades', function ($join) use ($activity) {
                $join->on('enrollment_tbls.id', '=', 'onsite_grades.enrollment_id')
                     ->where('onsite_grades.onsite_activity_id', '=', $activity->id);
            })
            ->where('enrollment_tbls.course_id', $activity->course_id)
            ->where('enrollment_tbls.status', 'active')
            ->select(
                'user_tbls.id as user_id',
                'user_tbls.firstname',
                'user_tbls.lastname',
                'user_tbls.email',
                'user_tbls.id_number',
                'user_tbls.contact',
                'enrollment_tbls.id as enrollment_id',
                'onsite_grades.score',
                'onsite_grades.remarks'
            )
            ->orderBy('user_tbls.lastname', 'asc')
            ->get();

        return response()->json([
            'activity' => $activity,
            'trainees' => $trainees,
        ]);
    }

    /**
     * Batch store/update the encoded scores.
     */
    public function storeGrades(Request $request)
    {
        $activity = OnsiteActivity::findOrFail($request->input('activity_id'));

        $request->validate([
            'activity_id'            => 'required|exists:onsite_activities,id',
            'grades'                 => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollment_tbls,id',
            'grades.*.user_id'       => 'required|exists:user_tbls,id',
            'grades.*.score'         => 'nullable|numeric|min:0|max:' . $activity->max_score,
            'grades.*.remarks'       => 'nullable|string|max:255',
        ]);

        $trainerId = auth()->user()?->id ?? auth('trainer')->user()?->id;

        DB::transaction(function () use ($request, $activity, $trainerId) {
            foreach ($request->input('grades') as $entry) {
                $score = ($entry['score'] !== null && $entry['score'] !== '') ? (float) $entry['score'] : null;
                $remarks = (isset($entry['remarks']) && trim($entry['remarks']) !== '') ? trim($entry['remarks']) : null;

                // Skip completely untouched entries
                if ($score === null && $remarks === null) {
                    continue;
                }

                $existing = DB::table('onsite_grades')
                    ->where('onsite_activity_id', $activity->id)
                    ->where('enrollment_id', $entry['enrollment_id'])
                    ->first();

                $payload = [
                    'user_id'    => $entry['user_id'],
                    'score'      => $score,
                    'remarks'    => $remarks,
                    'graded_by'  => $trainerId,
                    'updated_at' => now(),
                ];

                if ($existing) {
                    DB::table('onsite_grades')
                        ->where('id', $existing->id)
                        ->update($payload);
                } else {
                    $payload['onsite_activity_id'] = $activity->id;
                    $payload['enrollment_id']      = $entry['enrollment_id'];
                    $payload['created_at']         = now();
                    DB::table('onsite_grades')->insert($payload);
                }
            }
        });

        return back()->with('success', 'Onsite activity grades saved successfully.');
    }
}