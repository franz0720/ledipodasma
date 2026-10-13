<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course_tbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CertificateController extends Controller
{
    /**
     * Store a certificate with an automatically generated certificate number.
     * New certificates always start as Pending.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainee_id' => [
                'required',
                'exists:user_tbls,id',
            ],
            'course_id' => [
                'required',
                'exists:course_tbls,id',
            ],
            'issue_date' => [
                'required',
                'date',
            ],
            'document_type' => [
                'required',
                'string',
                Rule::in(['completion']),
            ],
        ]);

        $certificate = DB::transaction(function () use ($validated) {
            $enrollment = DB::table('enrollment_tbls')
                ->where('user_id', $validated['trainee_id'])
                ->where('course_id', $validated['course_id'])
                ->lockForUpdate()
                ->first();

            if (!$enrollment) {
                throw ValidationException::withMessages([
                    'trainee_id' => 'The selected trainee is not enrolled in this course.',
                ]);
            }

            $progress = (float) ($enrollment->progress ?? 0);

            $remarks = $progress >= 75
                ? 'Competent'
                : 'Not Yet Competent';

            $course = Course_tbl::findOrFail($validated['course_id']);

            // The certificate must use the training ID assigned to this course.
            $trainingId = trim((string) ($course->training_id ?? ''));

            if ($trainingId === '') {
                throw ValidationException::withMessages([
                    'course_id' => 'This course does not have a training ID yet. Please assign a training ID to the course before creating its certificate.',
                ]);
            }

            // Generate a certificate-number prefix from the course title.
            $words = preg_split(
                '/[^A-Za-z0-9]+/',
                $course->title ?? 'COURSE',
                -1,
                PREG_SPLIT_NO_EMPTY
            );

            $prefix = '';

            foreach ($words as $word) {
                $prefix .= strtoupper(substr($word, 0, 1));

                if (strlen($prefix) >= 3) {
                    break;
                }
            }

            $prefix = $prefix ?: 'CRS';

            $year = now()->format('Y');
            $numberPrefix = "{$prefix}-{$year}-";

            // Find the highest existing certificate number for this course and year.
            $existingNumbers = Certificate::where('course_id', $validated['course_id'])
                ->where('certificate_no', 'like', $numberPrefix . '%')
                ->lockForUpdate()
                ->pluck('certificate_no');

            $maxNumber = 0;

            foreach ($existingNumbers as $existingNumber) {
                if (preg_match(
                    '/^' . preg_quote($numberPrefix, '/') . '(\d+)$/',
                    $existingNumber,
                    $matches
                )) {
                    $maxNumber = max($maxNumber, (int) $matches[1]);
                }
            }

            $certificateNo = $numberPrefix . str_pad(
                (string) ($maxNumber + 1),
                3,
                '0',
                STR_PAD_LEFT
            );

            return Certificate::create([
                'user_id'        => $validated['trainee_id'],
                'course_id'      => $validated['course_id'],
                'certificate_no' => $certificateNo,
                'training_id'    => $trainingId,
                'document_type'  => 'completion',
                'issue_date'     => $validated['issue_date'],
                'status'         => 'Pending',
                'grade'          => null,
                'remarks'        => $remarks,
            ]);
        });

        $certificate->load(['user', 'course']);

        $progress = (float) (
            DB::table('enrollment_tbls')
                ->where('user_id', $validated['trainee_id'])
                ->where('course_id', $validated['course_id'])
                ->value('progress') ?? 0
        );

        return response()->json([
            'success' => true,
            'message' => 'Certificate record saved successfully.',
            'certificate' => [
                'id' => $certificate->id,
                'certificate_no' => $certificate->certificate_no,
                'training_id' => $certificate->training_id,
                'full_name' => trim(
                    ($certificate->user->firstname ?? '') . ' ' .
                    ($certificate->user->lastname ?? '')
                ),
                'course' => $certificate->course->title ?? 'General Training',
                'issue_date' => \Carbon\Carbon::parse($certificate->issue_date)
                    ->format('F j, Y'),
                'status' => $certificate->status,
                'grade' => $certificate->grade,
                'remarks' => $certificate->remarks,
                'progress' => $progress,
                'document_type' => $certificate->document_type,
            ],
        ]);
    }

    /**
     * Display certificates belonging to the authenticated student.
     * Certificates are colored only when current progress is at least 75%
     * and the certificate is Released or Claimed.
     */
    public function studentIndex()
    {
        $userId = Auth::id();

        $certificates = Certificate::with('course')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->get();

        $courseIds = $certificates
            ->pluck('course_id')
            ->filter()
            ->unique()
            ->values();

        $progressByCourse = DB::table('enrollment_tbls')
            ->where('user_id', $userId)
            ->whereIn('course_id', $courseIds)
            ->pluck('progress', 'course_id');

        $certificates->transform(function ($certificate) use ($progressByCourse) {
            $progress = (float) ($progressByCourse[$certificate->course_id] ?? 0);
            $status = strtolower(trim($certificate->status ?? ''));

            $certificate->current_progress = $progress;
            $certificate->is_competent = $progress >= 75;
            $certificate->competency_status = $progress >= 75
                ? 'Competent'
                : 'Not Yet Competent';

            $certificate->is_released = in_array(
                $status,
                ['released', 'claimed'],
                true
            );

            $certificate->show_color =
                $certificate->is_competent && $certificate->is_released;

            return $certificate;
        });

        return view('student.certificates', compact('certificates'));
    }

    /**
     * Delete a certificate record.
     */
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Certificate record deleted successfully.',
        ]);
    }

    /**
     * Toggle a released certificate between Released and Claimed.
     */
    public function toggleStatus($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->status === 'Pending') {
            return response()->json([
                'success' => false,
                'message' => 'Release the certificate before marking it as claimed.',
            ], 422);
        }

        if (!in_array($certificate->status, ['Released', 'Claimed'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This certificate cannot be marked as claimed.',
            ], 422);
        }

        $certificate->status = $certificate->status === 'Claimed'
            ? 'Released'
            : 'Claimed';

        $certificate->save();

        return response()->json([
            'success' => true,
            'new_status' => $certificate->status,
            'message' => "Status changed to {$certificate->status}.",
        ]);
    }

    /**
     * Release a certificate only when the trainee's current progress is at least 75%.
     */
    public function release($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->status === 'Released') {
            return response()->json([
                'success' => true,
                'new_status' => $certificate->status,
                'message' => 'This certificate has already been released.',
            ]);
        }

        if ($certificate->status === 'Claimed') {
            return response()->json([
                'success' => false,
                'message' => 'This certificate has already been claimed.',
            ], 422);
        }

        $enrollment = DB::table('enrollment_tbls')
            ->where('user_id', $certificate->user_id)
            ->where('course_id', $certificate->course_id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'No matching enrollment was found.',
            ], 422);
        }

        $progress = (float) ($enrollment->progress ?? 0);

        if ($progress < 75) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate release denied. The trainee has not reached 75% progress.',
                'progress' => $progress,
            ], 422);
        }

        $certificate->remarks = 'Competent';
        $certificate->status = 'Released';
        $certificate->save();

        return response()->json([
            'success' => true,
            'new_status' => $certificate->status,
            'message' => 'Certificate released successfully.',
        ]);
    }
}
