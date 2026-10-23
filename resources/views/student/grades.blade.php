@extends('student.layout')

@section('title', 'My Grades')

@section('css')
<style>
    :root {
        --grades-green: #075b35;
        --grades-dark: #043f26;
        --grades-soft: #eaf5ee;
        --grades-yellow: #e1dc36;
        --grades-text: #183329;
        --grades-muted: #718096;
        --grades-border: #e2ebe5;
    }

    .grades-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 26px;
        color: var(--grades-text);
        font-family: 'Open Sans', sans-serif;
    }

    .grades-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .grades-title {
        margin: 0;
        color: var(--grades-dark);
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 800;
        letter-spacing: -1px;
    }

    .grades-subtitle {
        margin: 8px 0 0;
        color: var(--grades-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .grades-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: #fff;
        border: 1px solid var(--grades-border);
        border-radius: 10px;
        color: var(--grades-green);
        font-size: 12px;
        font-weight: 700;
    }

    .grades-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 26px;
    }

    .grade-summary-card {
        min-width: 0;
        padding: 22px;
        background: #fff;
        border: 1px solid var(--grades-border);
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(24, 51, 41, .035);
    }

    .grade-summary-card.overall {
        background: var(--grades-green);
        border-color: var(--grades-green);
        color: #fff;
    }

    .grade-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
    }

    .grade-card-label {
        color: var(--grades-muted);
        font-size: 12px;
        font-weight: 700;
    }

    .overall .grade-card-label {
        color: rgba(255, 255, 255, .82);
    }

    .grade-card-icon,
    .grades-section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: var(--grades-soft);
        color: var(--grades-green);
    }

    .overall .grade-card-icon {
        background: rgba(255, 255, 255, .14);
        color: var(--grades-yellow);
    }

    .grade-card-value {
        color: var(--grades-dark);
        font-size: clamp(27px, 3vw, 36px);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        overflow-wrap: anywhere;
    }

    .overall .grade-card-value {
        color: #fff;
    }

    .grade-card-foot {
        margin-top: 10px;
        color: var(--grades-muted);
        font-size: 11px;
    }

    .overall .grade-card-foot {
        color: rgba(255, 255, 255, .82);
    }

    .grade-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 13px;
        padding: 7px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-competent {
        color: #166534;
        background: #dcfce7;
    }

    .status-not-competent {
        color: #b42318;
        background: #fee4e2;
    }

    .status-pending {
        color: #92400e;
        background: #fef3c7;
    }

    .grades-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px 17px;
        margin-bottom: 22px;
        background: #fffef2;
        border: 1px solid #e4e7a4;
        border-radius: 11px;
        color: #596016;
        font-size: 11px;
        line-height: 1.7;
    }

    .grades-note i {
        margin-top: 3px;
    }

    .grades-section {
        margin-bottom: 22px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--grades-border);
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(24, 51, 41, .025);
    }

    .grades-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 20px 22px;
        border-bottom: 1px solid var(--grades-border);
    }

    .grades-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .grades-section-title {
        margin: 0;
        color: var(--grades-dark);
        font-size: 16px;
        font-weight: 800;
    }

    .grades-section-description {
        margin: 4px 0 0;
        color: var(--grades-muted);
        font-size: 11px;
    }

    .grades-count {
        padding: 6px 10px;
        background: var(--grades-soft);
        border-radius: 7px;
        color: var(--grades-green);
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .grades-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .grades-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .grades-table th {
        padding: 13px 20px;
        background: #f8faf9;
        border-bottom: 1px solid var(--grades-border);
        color: var(--grades-muted);
        text-align: left;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .grades-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #edf2ee;
        color: var(--grades-text);
        vertical-align: middle;
    }

    .grades-table tbody tr:last-child td {
        border-bottom: none;
    }

    .grades-table tbody tr:hover {
        background: #fbfdfb;
    }

    .grade-item-title {
        margin-bottom: 4px;
        color: var(--grades-dark);
        font-size: 12px;
        font-weight: 800;
    }

    .grade-item-course {
        color: var(--grades-muted);
        font-size: 11px;
    }

    .grade-score {
        font-weight: 800;
        white-space: nowrap;
    }

    .grade-percentage {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .grade-percentage.good {
        background: #dcfce7;
        color: #166534;
    }

    .grade-percentage.low {
        background: #fee4e2;
        color: #b42318;
    }

    .grade-percentage.pending {
        background: #f1f5f9;
        color: #64748b;
    }

    .grade-remarks {
        display: block;
        max-width: 220px;
        color: var(--grades-muted);
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .grades-empty {
        padding: 42px 20px;
        text-align: center;
    }

    .grades-empty-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 52px;
        height: 52px;
        margin: 0 auto 13px;
        border-radius: 15px;
        background: var(--grades-soft);
        color: var(--grades-green);
        font-size: 20px;
    }

    .grades-empty h4 {
        margin: 0 0 7px;
        color: var(--grades-dark);
        font-size: 14px;
        font-weight: 800;
    }

    .grades-empty p {
        margin: 0;
        color: var(--grades-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    @media (max-width: 900px) {
        .grades-page {
            padding: 20px;
        }

        .grades-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grade-summary-card.overall {
            grid-column: auto;
        }
    }

    @media (max-width: 560px) {
        .grades-page {
            padding: 15px;
        }

        .grades-summary {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .grade-summary-card.overall {
            grid-column: auto;
        }

        .grade-summary-card {
            padding: 18px;
        }

        .grades-section-header {
            padding: 16px;
        }

        .grades-table th,
        .grades-table td {
            padding: 12px 14px;
        }
    }
</style>
@endsection

@section('content')
@php
    $formatAverage = function ($value, $decimals = 2) {
        return $value !== null
            ? number_format((float) $value, $decimals) . '%'
            : '—';
    };

    $statusClass = match ($overallStatus ?? 'Pending Assessment') {
        'Competent' => 'status-competent',
        'Not Yet Competent' => 'status-not-competent',
        default => 'status-pending',
    };

    $gradedOnsiteCount = $onsiteScores
        ->filter(fn ($activity) =>
            $activity->grade_id !== null &&
            $activity->score !== null
        )
        ->count();
@endphp

<div class="grades-page">

    <header class="grades-header">
        <div>
            <h1 class="grades-title">My Grades</h1>
            <p class="grades-subtitle">
                Track your quiz results, onsite activity scores, and overall assessment.
            </p>
        </div>

        <div class="grades-date">
            <i class="fa-regular fa-calendar"></i>
            {{ now()->format('F d, Y') }}
        </div>
    </header>

    <div class="grades-summary">

        <article class="grade-summary-card">
            <div class="grade-card-top">
                <span class="grade-card-label">Quiz Average</span>
                <span class="grade-card-icon">
                    <i class="fa-solid fa-clipboard-question"></i>
                </span>
            </div>

            <div class="grade-card-value">
                {{ $formatAverage($quizAverage) }}
            </div>

            <div class="grade-card-foot">
                {{ $quizScores->count() }}
                recorded {{ $quizScores->count() === 1 ? 'result' : 'results' }}
            </div>
        </article>

        <article class="grade-summary-card">
            <div class="grade-card-top">
                <span class="grade-card-label">Onsite Average</span>
                <span class="grade-card-icon">
                    <i class="fa-solid fa-person-chalkboard"></i>
                </span>
            </div>

            <div class="grade-card-value">
                {{ $formatAverage($onsiteAverage) }}
            </div>

            <div class="grade-card-foot">
                {{ $gradedOnsiteCount }}
                graded onsite {{ $gradedOnsiteCount === 1 ? 'activity' : 'activities' }}
            </div>
        </article>

        <article class="grade-summary-card overall">
            <div class="grade-card-top">
                <span class="grade-card-label">Overall Assessment</span>
                <span class="grade-card-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
            </div>

            {{-- Rounded for display only: 19.84% displays as 20%. --}}
            <div class="grade-card-value">
                {{ $formatAverage($overallAverage, 0) }}
            </div>

            <div class="grade-card-foot">
                Based on quiz and onsite averages
            </div>

            <span class="grade-status {{ $statusClass }}">
                <i class="fa-solid fa-circle-check"></i>
                {{ $overallStatus ?? 'Pending Assessment' }}
            </span>
        </article>

        {{-- COURSE COMPLETION --}}
        <article class="grade-summary-card">
            <div class="grade-card-top">
                <span class="grade-card-label">Course Completion</span>
                <span class="grade-card-icon">
                    <i class="fa-solid fa-list-check"></i>
                </span>
            </div>

            <div class="grade-card-value">
                {{ number_format((float) ($courseProgress ?? 0), 0) }}%
            </div>

            <div class="grade-card-foot">
                {{ $completedQuizzes ?? 0 }} of {{ $totalQuizzes ?? 0 }} quizzes passed
                <br>
                {{ $completedOnsiteActivities ?? 0 }} of {{ $totalOnsiteActivities ?? 0 }} onsite activities graded
            </div>
        </article>

    </div>

    <div class="grades-note">
        <i class="fa-solid fa-circle-info"></i>
        <div>
            <strong>How your grades are calculated:</strong>
            Quiz average includes the recorded quiz results supplied by the system.
            Onsite average uses graded activities only, converted to percentages based
            on each activity's maximum score. Overall assessment is calculated from
            the quiz and onsite averages when both are available; 75% or higher is
            Competent. Course completion is separate from your grade: it is the
            percentage of required quizzes passed and onsite activities with a recorded
            grade. The summary percentages are rounded for display only.
        </div>
    </div>

    {{-- QUIZZES --}}
@php
    $allQuizzes = collect();

    foreach (($quizScores ?? collect()) as $quiz) {
        $quiz->is_untaken = false;
        $allQuizzes->push($quiz);
    }

    foreach (($untakenQuizzes ?? collect()) as $quiz) {
        $quiz->is_untaken = true;
        $allQuizzes->push($quiz);
    }

    $allQuizzes = $allQuizzes
        ->unique(fn ($quiz) => $quiz->quiz_id ?? $quiz->id)
        ->sortBy(function ($quiz) {
            $title = $quiz->quiz_title ?? $quiz->title ?? '';

            if (preg_match('/\bModule\s*(\d+)/i', $title, $matches)) {
                return (int) $matches[1];
            }

            return PHP_INT_MAX;
        })
        ->values();
@endphp

<section class="grades-section">
    <div class="grades-section-header">
        <div class="grades-section-heading">
            <span class="grades-section-icon">
                <i class="fa-solid fa-clipboard-list"></i>
            </span>

            <div>
                <h2 class="grades-section-title">Quizzes</h2>
                <p class="grades-section-description">
                    All quizzes in your enrolled courses, including scores and attempts used.
                </p>
            </div>
        </div>

        <span class="grades-count">
            {{ $allQuizzes->count() }}
            {{ $allQuizzes->count() === 1 ? 'QUIZ' : 'QUIZZES' }}
        </span>
    </div>

    @if ($allQuizzes->isNotEmpty())
        <div class="grades-table-wrap">
            <table class="grades-table">
                <thead>
                    <tr>
                        <th>Quiz / Course</th>
                        <th>Score</th>
                        <th>Attempts Used</th>
                        <th>Percentage</th>
                        <th>Result</th>
                        <th>Date Taken</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($allQuizzes as $quiz)
                        @php
                            $isUntaken = (bool) ($quiz->is_untaken ?? false);

                            $percentage = !$isUntaken && $quiz->percentage !== null
                                ? (float) $quiz->percentage
                                : null;

                            $attemptsUsed = (int) ($quiz->attempts_used ?? 0);
                            $attemptsAllowed = (int) ($quiz->attempts_allowed ?? 2);
                        @endphp

                        <tr>
                            <td>
                                <div class="grade-item-title">
                                    {{ $quiz->quiz_title ?? $quiz->title ?? 'Untitled Quiz' }}
                                </div>

                                <div class="grade-item-course">
                                    {{ $quiz->course_title ?? '—' }}
                                </div>
                            </td>

                            {{-- SCORE --}}
                            <td>
                                @if ($isUntaken)
                                    <span class="grade-score">—</span>
                                @else
                                    <span class="grade-score">
                                        {{ $quiz->score ?? '—' }}
                                        / {{ $quiz->total_items ?? '—' }}
                                    </span>
                                @endif
                            </td>

                            {{-- ATTEMPTS USED --}}
                            <td>
                                <span class="grade-score">
                                    {{ $attemptsUsed }} / {{ $attemptsAllowed }}
                                </span>
                            </td>

                            {{-- PERCENTAGE --}}
                            <td>
                                @if ($isUntaken)
                                    <span class="grade-percentage pending">—</span>
                                @elseif ($percentage !== null)
                                    <span class="grade-percentage {{ $percentage >= 75 ? 'good' : 'low' }}">
                                        {{ number_format($percentage, 2) }}%
                                    </span>
                                @else
                                    <span class="grade-percentage pending">
                                        Not recorded
                                    </span>
                                @endif
                            </td>

                            {{-- RESULT --}}
                            <td>
                                @if ($isUntaken)
                                    <span class="grade-percentage pending">
                                        Not yet taken
                                    </span>
                                @elseif ($percentage !== null)
                                    <span class="grade-percentage {{ strtolower($quiz->status ?? '') === 'passed' ? 'good' : (strtolower($quiz->status ?? '') === 'failed' ? 'low' : 'pending') }}">
                                        {{ $quiz->status ? ucfirst($quiz->status) : 'Recorded' }}
                                    </span>
                                @else
                                    <span class="grade-percentage pending">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- DATE TAKEN --}}
                            <td>
                                @if (!$isUntaken && $quiz->created_at)
                                    {{ \Illuminate\Support\Carbon::parse($quiz->created_at)->format('M d, Y') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="grades-empty">
            <div class="grades-empty-icon">
                <i class="fa-solid fa-clipboard-question"></i>
            </div>

            <h4>No quizzes available</h4>
            <p>
                Quizzes for your enrolled courses will appear here when available.
            </p>
        </div>
    @endif
</section>

    {{-- ONSITE ACTIVITY SCORES --}}
    <section class="grades-section">
        <div class="grades-section-header">
            <div class="grades-section-heading">
                <span class="grades-section-icon">
                    <i class="fa-solid fa-person-chalkboard"></i>
                </span>
                <div>
                    <h2 class="grades-section-title">Onsite Activity Scores</h2>
                    <p class="grades-section-description">
                        Scores recorded by your trainer for onsite activities
                    </p>
                </div>
            </div>

            <span class="grades-count">
                {{ $onsiteScores->count() }}
                {{ $onsiteScores->count() === 1 ? 'ACTIVITY' : 'ACTIVITIES' }}
            </span>
        </div>

        @if ($onsiteScores->isNotEmpty())
            <div class="grades-table-wrap">
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Activity / Course</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Remarks</th>
                            <th>Activity Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($onsiteScores as $activity)
                            @php
                                $hasGrade = $activity->grade_id !== null
                                    && $activity->score !== null;

                                $maxScore = (float) ($activity->max_score ?? 0);

                                $activityPercentage = $hasGrade && $maxScore > 0
                                    ? ((float) $activity->score / $maxScore) * 100
                                    : null;
                            @endphp

                            <tr>
                                <td>
                                    <div class="grade-item-title">
                                        {{ $activity->activity_title }}
                                    </div>
                                    <div class="grade-item-course">
                                        {{ $activity->course_title }}
                                    </div>
                                </td>

                                <td>
                                    @if ($hasGrade)
                                        <span class="grade-score">
                                            {{ rtrim(rtrim(number_format((float) $activity->score, 2, '.', ''), '0'), '.') }}
                                            /
                                            {{ rtrim(rtrim(number_format($maxScore, 2, '.', ''), '0'), '.') }}
                                        </span>
                                    @else
                                        <span class="grade-percentage pending">Not graded</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($activityPercentage !== null)
                                        <span class="grade-percentage {{ $activityPercentage >= 75 ? 'good' : 'low' }}">
                                            {{ number_format($activityPercentage, 2) }}%
                                        </span>
                                    @else
                                        <span class="grade-percentage pending">Pending</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="grade-remarks">
                                        {{ $activity->remarks ?: ($hasGrade ? 'No remarks' : 'Awaiting trainer grade') }}
                                    </span>
                                </td>

                                <td>
                                    {{ $activity->activity_date
                                        ? \Illuminate\Support\Carbon::parse($activity->activity_date)->format('M d, Y')
                                        : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="grades-empty">
                <div class="grades-empty-icon">
                    <i class="fa-solid fa-person-chalkboard"></i>
                </div>
                <h4>No onsite activities available</h4>
                <p>Onsite activities for your enrolled courses will appear here when available.</p>
            </div>
        @endif
    </section>

</div>
@endsection
