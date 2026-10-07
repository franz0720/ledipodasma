<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course_tbl;
use App\Models\User_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainee_id'     => 'required|exists:user_tbls,id',
            'course_id'      => 'nullable',
            'certificate_no' => 'required|string|unique:certificates,certificate_no',
            'issue_date'     => 'required|date',
            'document_type'  => 'required|string',
            'remarks'        => 'nullable|string',
        ]);

        // 1. Get course ID from request, enrollment table, or the first active course in DB
$courseId = $request->input('course_id');

if (!$courseId) {
    $enrollment = DB::table('enrollment_tbls')
        ->where('user_id', $validated['trainee_id'])
        ->first();

    $courseId = $enrollment?->course_id;
}

if (!$courseId) {
    return response()->json([
        'success' => false,
        'message' => 'Certificate cannot be issued because no course enrollment was found.'
    ], 422);
}

$enrollment = DB::table('enrollment_tbls')
    ->where('user_id', $validated['trainee_id'])
    ->where('course_id', $courseId)
    ->where('status', 'completed')
    ->first();

if (!$enrollment) {
    return response()->json([
        'success' => false,
        'message' => 'Certificate cannot be issued. The trainee has not completed the course.'
    ], 422);
}

$course = Course_tbl::find($courseId);

if (!$course || !$course->training_id) {
    return response()->json([
        'success' => false,
        'message' => 'Certificate cannot be issued because the course has no Training ID.'
    ], 422);
}

$postTest = \App\Models\Quiz::where('course_id', $courseId)
    ->where('quiz_type', 'post_test')
    ->first();

$postTestResult = $postTest
    ? \App\Models\QuizResult::where('user_id', $validated['trainee_id'])
        ->where('quiz_id', $postTest->id)
        ->where('status', 'passed')
        ->first()
    : null;

if (!$postTestResult) {
    return response()->json([
        'success' => false,
        'message' => 'Certificate cannot be issued because the trainee has no passed Post-Test result.'
    ], 422);
}

$certificateGrade = $postTestResult->percentage . '%';

        // 2. Insert into database
        $certificate = Certificate::create([
            'user_id'        => $validated['trainee_id'],
            'course_id'      => $courseId,
            'certificate_no' => $validated['certificate_no'],
            'training_id'    => $course->training_id,
            'document_type'  => $validated['document_type'],
            'issue_date'     => $validated['issue_date'],
            'status'         => 'Pending',
            'grade'          => $certificateGrade,
            'remarks'        => $validated['remarks'] ?? null,
        ]);

        $certificate->load(['user', 'course']);

        return response()->json([
            'success' => true,
            'message' => 'Certificate issued and recorded in database!',
            'certificate' => [
                'id'             => $certificate->id,
                'certificate_no' => $certificate->certificate_no,
                'full_name'      => trim(($certificate->user->firstname ?? '') . ' ' . ($certificate->user->lastname ?? '')),
                'course'         => $certificate->course->title ?? 'General Training',
                'issue_date'     => \Carbon\Carbon::parse($certificate->issue_date)->format('F j, Y'),
                'status'         => $certificate->status,
                'grade'          => $certificate->grade ?? '94%',
                'document_type'  => $certificate->document_type
            ]
        ]);
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate record deleted successfully!'
        ]);
    }

    public function toggleStatus($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = strtolower($certificate->status) === 'claimed' ? 'Pending' : 'Claimed';
        $certificate->save();

        return response()->json([
            'success'    => true,
            'new_status' => $certificate->status,
            'message'    => "Status changed to {$certificate->status}"
        ]);
    }
}