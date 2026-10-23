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
     * Calculate the same competency grade used by the grades workflow:
     * overall score = (average quiz percentage + average onsite percentage) / 2.
     * Missing assessment categories count as 0.
     */
    private function calculateAssessmentScore(int $userId, int $courseId, int $enrollmentId): array
    {
        $quizAverage = DB::table('quiz_results as qr')
            ->join('quizzes as q', 'q.id', '=', 'qr.quiz_id')
            ->where('qr.user_id', $userId)
            ->where('q.course_id', $courseId)
            ->selectRaw('AVG(CASE
                WHEN qr.percentage IS NOT NULL THEN qr.percentage
                WHEN qr.total_items > 0 THEN (qr.score / qr.total_items) * 100
                ELSE NULL
            END) as average_score')
            ->value('average_score');

        $onsiteAverage = DB::table('onsite_grades as og')
            ->join('onsite_activities as oa', 'oa.id', '=', 'og.onsite_activity_id')
            ->where('og.user_id', $userId)
            ->where('og.enrollment_id', $enrollmentId)
            ->where('oa.course_id', $courseId)
            ->whereNotNull('og.score')
            ->where('oa.max_score', '>', 0)
            ->selectRaw('AVG((og.score / oa.max_score) * 100) as average_score')
            ->value('average_score');

        $quizAverage = max(0, min(100, (float) ($quizAverage ?? 0)));
        $onsiteAverage = max(0, min(100, (float) ($onsiteAverage ?? 0)));
        $overallScore = max(0, min(100, round(($quizAverage + $onsiteAverage) / 2, 2)));

        return [
            'quiz_average' => round($quizAverage, 2),
            'onsite_average' => round($onsiteAverage, 2),
            'overall_score' => $overallScore,
            'remarks' => $overallScore >= 75 ? 'Competent' : 'Not Yet Competent',
        ];
    }

    /**
     * Store a certificate record with an automatically generated certificate number.
     * New certificates start as Pending; an admin must release them.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainee_id' => ['required', 'exists:user_tbls,id'],
            'course_id' => ['required', 'exists:course_tbls,id'],
            'issue_date' => ['required', 'date'],
            'document_type' => ['required', 'string', Rule::in(['completion'])],
        ]);

        $result = DB::transaction(function () use ($validated) {
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

            $assessment = $this->calculateAssessmentScore(
                (int) $validated['trainee_id'],
                (int) $validated['course_id'],
                (int) $enrollment->id
            );

            $course = Course_tbl::findOrFail($validated['course_id']);
            $trainingId = trim((string) ($course->training_id ?? ''));

            if ($trainingId === '') {
                throw ValidationException::withMessages([
                    'course_id' => 'This course does not have a training ID yet. Please assign a training ID to the course before creating its certificate.',
                ]);
            }

            // Build a short prefix from the first letters of the course title.
            $words = preg_split('/[^A-Za-z0-9]+/', $course->title ?? 'COURSE', -1, PREG_SPLIT_NO_EMPTY);
            $prefix = '';

            foreach ($words as $word) {
                $prefix .= strtoupper(substr($word, 0, 1));
                if (strlen($prefix) >= 3) {
                    break;
                }
            }

            $prefix = $prefix ?: 'CRS';
            $numberPrefix = $prefix . '-' . now()->format('Y') . '-';

            $existingNumbers = Certificate::where('course_id', $validated['course_id'])
                ->where('certificate_no', 'like', $numberPrefix . '%')
                ->lockForUpdate()
                ->pluck('certificate_no');

            $maxNumber = 0;
            foreach ($existingNumbers as $existingNumber) {
                if (preg_match('/^' . preg_quote($numberPrefix, '/') . '(\d+)$/', $existingNumber, $matches)) {
                    $maxNumber = max($maxNumber, (int) $matches[1]);
                }
            }

            $certificateNo = $numberPrefix . str_pad((string) ($maxNumber + 1), 3, '0', STR_PAD_LEFT);

            $certificate = Certificate::create([
                'user_id' => $validated['trainee_id'],
                'course_id' => $validated['course_id'],
                'certificate_no' => $certificateNo,
                'training_id' => $trainingId,
                'document_type' => 'completion',
                'issue_date' => $validated['issue_date'],
                'status' => 'Pending',
                'grade' => $assessment['overall_score'],
                'remarks' => $assessment['remarks'],
            ]);

            return [
                'certificate' => $certificate,
                'assessment' => $assessment,
            ];
        });

        $certificate = $result['certificate'];
        $assessment = $result['assessment'];
        $certificate->load(['user', 'course']);

        return response()->json([
            'success' => true,
            'message' => 'Certificate record saved successfully.',
            'certificate' => [
                'id' => $certificate->id,
                'certificate_no' => $certificate->certificate_no,
                'training_id' => $certificate->training_id,
                'full_name' => trim(($certificate->user->firstname ?? '') . ' ' . ($certificate->user->lastname ?? '')),
                'course' => $certificate->course->title ?? 'General Training',
                'issue_date' => \Carbon\Carbon::parse($certificate->issue_date)->format('F j, Y'),
                'status' => $certificate->status,
                'grade' => $assessment['overall_score'],
                'remarks' => $assessment['remarks'],
                'progress' => $assessment['overall_score'],
                'quiz_average' => $assessment['quiz_average'],
                'onsite_average' => $assessment['onsite_average'],
                'document_type' => $certificate->document_type,
            ],
        ]);
    }

    /**
     * Display certificates belonging to the authenticated student.
     * Full-color/download eligibility requires a combined grade >= 75 and
     * a Released or Claimed certificate status.
     */
    public function studentIndex()
    {
        $userId = (int) Auth::id();

        $certificates = Certificate::with('course')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->get();

        $enrollmentsByCourse = DB::table('enrollment_tbls')
            ->where('user_id', $userId)
            ->whereIn('course_id', $certificates->pluck('course_id')->filter()->unique()->values())
            ->get()
            ->keyBy('course_id');

        $certificates->transform(function ($certificate) use ($userId, $enrollmentsByCourse) {
            $enrollment = $enrollmentsByCourse->get($certificate->course_id);
            $assessment = $enrollment
                ? $this->calculateAssessmentScore($userId, (int) $certificate->course_id, (int) $enrollment->id)
                : [
                    'quiz_average' => 0,
                    'onsite_average' => 0,
                    'overall_score' => 0,
                    'remarks' => 'Not Yet Competent',
                ];

            $status = strtolower(trim((string) ($certificate->status ?? '')));
            $isReleased = in_array($status, ['released', 'claimed'], true);
            $isCompetent = $assessment['overall_score'] >= 75;

            $certificate->current_progress = $assessment['overall_score'];
            $certificate->current_grade = $assessment['overall_score'];
            $certificate->quiz_average = $assessment['quiz_average'];
            $certificate->onsite_average = $assessment['onsite_average'];
            $certificate->is_competent = $isCompetent;
            $certificate->competency_status = $assessment['remarks'];
            $certificate->is_released = $isReleased;
            $certificate->show_color = $isCompetent && $isReleased;

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

        $certificate->status = $certificate->status === 'Claimed' ? 'Released' : 'Claimed';
        $certificate->save();

        return response()->json([
            'success' => true,
            'new_status' => $certificate->status,
            'message' => "Status changed to {$certificate->status}.",
        ]);
    }

    /**
     * Release a certificate only if the current combined assessment grade is >= 75%.
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

        $assessment = $this->calculateAssessmentScore(
            (int) $certificate->user_id,
            (int) $certificate->course_id,
            (int) $enrollment->id
        );

        if ($assessment['overall_score'] < 75) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate release denied. The trainee has not reached the 75% combined assessment grade.',
                'progress' => $assessment['overall_score'],
                'quiz_average' => $assessment['quiz_average'],
                'onsite_average' => $assessment['onsite_average'],
            ], 422);
        }

        $certificate->grade = $assessment['overall_score'];
        $certificate->remarks = 'Competent';
        $certificate->status = 'Released';
        $certificate->save();

        return response()->json([
            'success' => true,
            'new_status' => $certificate->status,
            'grade' => $assessment['overall_score'],
            'message' => 'Certificate released successfully.',
        ]);
    }
}
