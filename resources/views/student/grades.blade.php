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
      --grades-bg: #f7faf8;
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
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    .grades-eyebrow {
      color: var(--grades-green);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 7px;
    }

    .grades-title {
      margin: 0;
      font-size: clamp(25px, 3vw, 34px);
      font-weight: 800;
      letter-spacing: -1px;
      color: var(--grades-dark);
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
      border: 1px solid var(--grades-border);
      background: #fff;
      border-radius: 10px;
      color: var(--grades-green);
      font-size: 12px;
      font-weight: 700;
    }

    .grades-summary {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 26px;
    }

    .grade-summary-card {
      position: relative;
      overflow: hidden;
      min-width: 0;
      background: #fff;
      border: 1px solid var(--grades-border);
      border-radius: 15px;
      padding: 22px;
      box-shadow: 0 3px 12px rgba(24, 51, 41, .035);
    }

    .grade-summary-card.overall {
      background: var(--grades-green);
      border-color: var(--grades-green);
      color: #fff;
    }

    .grade-card-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 22px;
    }

    .grade-card-label {
      font-size: 12px;
      font-weight: 700;
      color: var(--grades-muted);
    }

    .overall .grade-card-label {
      color: rgba(255, 255, 255, .78);
    }

    .grade-card-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 39px;
      height: 39px;
      border-radius: 11px;
      background: var(--grades-soft);
      color: var(--grades-green);
      flex-shrink: 0;
    }

    .overall .grade-card-icon {
      background: rgba(255, 255, 255, .14);
      color: var(--grades-yellow);
    }

    .grade-card-value {
      font-size: clamp(27px, 3vw, 36px);
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -1px;
      color: var(--grades-dark);
      overflow-wrap: anywhere;
    }

    .overall .grade-card-value {
      color: #fff;
    }

    .grade-card-foot {
      margin-top: 10px;
      font-size: 11px;
      color: var(--grades-muted);
    }

    .overall .grade-card-foot {
      color: rgba(255, 255, 255, .78);
    }

    .grade-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 13px;
      padding: 6px 10px;
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

    .grades-section {
      background: #fff;
      border: 1px solid var(--grades-border);
      border-radius: 15px;
      overflow: hidden;
      margin-bottom: 22px;
      box-shadow: 0 3px 12px rgba(24, 51, 41, .025);
    }

    .grades-section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 15px;
      padding: 20px 22px;
      border-bottom: 1px solid var(--grades-border);
      flex-wrap: wrap;
    }

    .grades-section-heading {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .grades-section-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      flex-shrink: 0;
      border-radius: 11px;
      background: var(--grades-soft);
      color: var(--grades-green);
    }

    .grades-section-title {
      margin: 0;
      color: var(--grades-dark);
      font-size: 16px;
      font-weight: 800;
    }

    .grades-section-description {
      margin: 4px 0 0;
      font-size: 11px;
      color: var(--grades-muted);
    }

    .grades-count {
      padding: 6px 10px;
      border-radius: 7px;
      background: var(--grades-soft);
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
      color: #718096;
      text-align: left;
      text-transform: uppercase;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .6px;
      white-space: nowrap;
      border-bottom: 1px solid var(--grades-border);
    }

    .grades-table td {
      padding: 15px 20px;
      border-bottom: 1px solid #edf2ee;
      vertical-align: middle;
      color: var(--grades-text);
    }

    .grades-table tbody tr:last-child td {
      border-bottom: none;
    }

    .grades-table tbody tr:hover {
      background: #fbfdfb;
    }

    .grade-item-title {
      font-size: 12px;
      font-weight: 800;
      color: var(--grades-dark);
      margin-bottom: 4px;
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
      font-weight: 800;
      font-size: 11px;
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
      align-items: center;
      justify-content: center;
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
      font-size: 14px;
      color: var(--grades-dark);
      font-weight: 800;
    }

    .grades-empty p {
      margin: 0;
      color: var(--grades-muted);
      font-size: 12px;
      line-height: 1.6;
    }

    .grades-note {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      padding: 15px 17px;
      border: 1px solid #e4e7a4;
      background: #fffef2;
      color: #596016;
      border-radius: 11px;
      font-size: 11px;
      line-height: 1.7;
      margin-bottom: 20px;
    }

    .grades-note i {
      margin-top: 3px;
    }

    @media (max-width: 900px) {
      .grades-page {
        padding: 20px;
      }

      .grades-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .grade-summary-card.overall {
        grid-column: 1 / -1;
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
    $formatAverage = function ($value) {
        return $value !== null ? number_format((float) $value, 2) . '%' : '—';
    };

    $statusClass = match ($overallStatus ?? 'Pending Assessment') {
        'Competent' => 'status-competent',
        'Not Yet Competent' => 'status-not-competent',
        default => 'status-pending',
    };
  @endphp

  <div class="grades-page">

    {{-- Page heading --}}
    <header class="grades-header">
      <div>
        <h1 class="grades-title">My Grades</h1>

      </div>

      <div class="grades-date">
        <i class="fa-regular fa-calendar"></i>
        {{ now()->format('F d, Y') }}
      </div>
    </header>

    {{-- Summary cards --}}
    <div class="grades-summary">

      <article class="grade-summary-card">
        <div class="grade-card-top">
          <span class="grade-card-label">Quiz Average</span>
          <span class="grade-card-icon">
            <i class="fa-solid fa-clipboard-question"></i>
          </span>
        </div>

        <div class="grade-card-value">
          {{ $formatAverage($quizAverage ?? null) }}
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
          {{ $formatAverage($onsiteAverage ?? null) }}
        </div>

        <div class="grade-card-foot">
          {{ $onsiteScores->whereNotNull('grade_id')->whereNotNull('score')->count() }}
          graded onsite
          {{ $onsiteScores->whereNotNull('grade_id')->whereNotNull('score')->count() === 1 ? 'activity' : 'activities' }}
        </div>
      </article>

      <article class="grade-summary-card overall">
        <div class="grade-card-top">
          <span class="grade-card-label">Overall Assessment</span>
          <span class="grade-card-icon">
            <i class="fa-solid fa-chart-line"></i>
          </span>
        </div>

        <div class="grade-card-value">
          {{ $overallAverage !== null ? number_format($overallAverage, 0) . '%' : '—' }}
        </div>

        <div class="grade-card-foot">
          Based on quiz and onsite averages
        </div>

        <span class="grade-status {{ $statusClass }}">
          <i class="fa-solid fa-circle-check"></i>
          {{ $overallStatus ?? 'Pending Assessment' }}
        </span>
      </article>

    </div>

    {{-- Quiz results --}}
    <section class="grades-section">
      <div class="grades-section-header">
        <div class="grades-section-heading">
          <div>
            <h2 class="grades-section-title">Quiz Results</h2>
            <p class="grades-section-description">
              Your recorded quiz scores and percentages
            </p>
          </div>
        </div>

        <span class="grades-count">
          {{ $quizScores->count() }}
          {{ $quizScores->count() === 1 ? 'RESULT' : 'RESULTS' }}
        </span>
      </div>

      @if ($quizScores->isNotEmpty())
        <div class="grades-table-wrap">
          <table class="grades-table">
            <thead>
              <tr>
                <th>Quiz / Course</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Result</th>
                <th>Date Taken</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($quizScores as $quiz)
                @php
                  $percentage =
                      $quiz->percentage !== null
                          ? (float) $quiz->percentage
                          : null;
                @endphp

                <tr>
                  <td>
                    <div class="grade-item-title">
                      {{ $quiz->quiz_title }}
                    </div>
                    <div class="grade-item-course">
                      {{ $quiz->course_title }}
                    </div>
                  </td>

                  <td>
                    <span class="grade-score">
                      {{ $quiz->score ?? '—' }}
                      /
                      {{ $quiz->total_items ?? '—' }}
                    </span>
                  </td>

                  <td>
                    @if ($percentage !== null)
                      <span
                        class="grade-percentage {{ $percentage >= 75 ? 'good' : 'low' }}">
                        {{ number_format($percentage, 2) }}%
                      </span>
                    @else
                      <span class="grade-percentage pending">Not recorded</span>
                    @endif
                  </td>

                  <td>
                    @if ($percentage !== null)
                      <span
                        class="grade-percentage {{ $percentage >= 75 ? 'good' : 'low' }}">
                        {{ $percentage >= 75 ? 'Passed' : 'Failed' }}
                      </span>
                    @else
                      <span class="grade-percentage pending">
                        Pending
                      </span>
                    @endif
                  </td>

                  <td>
                    {{ $quiz->created_at
                        ? \Illuminate\Support\Carbon::parse($quiz->created_at)->format('M d, Y')
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
            <i class="fa-solid fa-clipboard-question"></i>
          </div>
          <h4>No quiz results yet</h4>
          <p>Your recorded quiz results will appear here after you take a quiz.
          </p>
        </div>
      @endif
    </section>

    {{-- Onsite activity results --}}
    <section class="grades-section">
      <div class="grades-section-header">
        <div class="grades-section-heading">
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
                  $hasGrade =
                      $activity->grade_id !== null && $activity->score !== null;

                  $maxScore = (float) ($activity->max_score ?? 0);

                  $activityPercentage =
                      $hasGrade && $maxScore > 0
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
                      <span class="grade-percentage pending">
                        Not graded
                      </span>
                    @endif
                  </td>

                  <td>
                    @if ($activityPercentage !== null)
                      <span
                        class="grade-percentage {{ $activityPercentage >= 75 ? 'good' : 'low' }}">
                        {{ number_format($activityPercentage, 2) }}%
                      </span>
                    @else
                      <span class="grade-percentage pending">
                        Pending
                      </span>
                    @endif
                  </td>

                  <td>
                    <span class="grade-remarks">
                      {{ $activity->remarks ?: ($hasGrade ? 'No remarks' : 'Awaiting trainer grade') }}
                    </span>
                  </td>

                  <td>
                    {{ $activity->activity_date
                        ? \Illuminate\Support\Carbon::parse($activity->activity_date)->format(
                            'M d, Y',
                        )
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
          <p>Onsite activities for your enrolled courses will appear here when
            available.</p>
        </div>
      @endif
    </section>

  </div>
@endsection
