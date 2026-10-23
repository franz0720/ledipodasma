@extends('trainer.layout')

@section('title', 'Dashboard · LEDIPO Trainer')

@section('css')

  <style>

    .main-content {

      background: #f4f6f0;

    }

    /* ── Banner ───────────────────────────────────────────────────────────── */

    .greeting-banner {

      background: #1a4d2e;

      border-radius: 14px;

      padding: 24px 28px;

      color: white;

      display: flex;

      justify-content: space-between;

      align-items: center;

      flex-wrap: wrap;

      gap: 16px;

      margin-bottom: 24px;

    }

    .greeting-banner h2 {

      font-size: 24px;

      font-weight: 700;

      color: white;

      margin: 0;

    }

    .greeting-banner p {

      font-size: 13px;

      color: #b6d9c0;

      margin: 4px 0 0;

    }

    .banner-stats {

      display: flex;

      gap: 12px;

      flex-wrap: wrap;

    }

    .banner-stat {

      background: rgba(255, 255, 255, 0.12);

      border-radius: 10px;

      padding: 10px 18px;

      text-align: center;

      min-width: 90px;

    }

    .banner-stat .bval {

      font-size: 20px;

      font-weight: 700;

      color: #f5c842;

      display: block;

    }

    .banner-stat .blbl {

      font-size: 11px;

      color: #cde8d4;

      margin-top: 2px;

      display: block;

    }

    /* ── Section headings ─────────────────────────────────────────────────── */

    .section-heading {

      font-size: 13px;

      font-weight: 600;

      color: #4a5568;

      text-transform: uppercase;

      letter-spacing: 0.05em;

      margin: 0 0 12px;

    }

    /* ── Stat cards ───────────────────────────────────────────────────────── */

    .stat-grid {

      display: grid;

      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));

      gap: 12px;

      margin-bottom: 24px;

    }

    .stat-card {

      background: white;

      border-radius: 12px;

      padding: 16px;

      border: 0.5px solid #e2e8f0;

    }

    .stat-card .sc-label {

      font-size: 12px;

      color: #718096;

      margin-bottom: 6px;

    }

    .stat-card .sc-value {

      font-size: 26px;

      font-weight: 700;

      color: #1a4d2e;

      line-height: 1;

    }

    .stat-card .sc-sub {

      font-size: 11px;

      color: #a0aec0;

      margin-top: 4px;

    }

    .badge-green {

      display: inline-block;

      background: #e6f4eb;

      color: #276749;

      font-size: 11px;

      padding: 2px 8px;

      border-radius: 999px;

      margin-top: 6px;

    }

    .badge-yellow {

      display: inline-block;

      background: #fff8e1;

      color: #b7791f;

      font-size: 11px;

      padding: 2px 8px;

      border-radius: 999px;

      margin-top: 6px;

    }

    .badge-red {

      display: inline-block;

      background: #fef2f2;

      color: #c53030;

      font-size: 11px;

      padding: 2px 8px;

      border-radius: 999px;

      margin-top: 6px;

    }

    /* ── Chart cards ──────────────────────────────────────────────────────── */

    .chart-grid {

      display: grid;

      grid-template-columns: 1fr 1fr;

      gap: 16px;

      margin-bottom: 24px;

    }

    @media (max-width: 700px) {

      .chart-grid {

        grid-template-columns: 1fr;

      }

    }

    .chart-card {

      background: white;

      border-radius: 12px;

      padding: 18px;

      border: 0.5px solid #e2e8f0;

    }

    .chart-card .cc-title {

      font-size: 13px;

      font-weight: 600;

      color: #4a5568;

      margin-bottom: 14px;

    }

    .chart-wrap {

      position: relative;

      width: 100%;

      height: 200px;

    }

    /* ── Donut ────────────────────────────────────────────────────────────── */

    /* Competency chart: larger doughnut + compact status summaries */

    .donut-wrap {

      display: grid;

      grid-template-columns: minmax(170px, 0.9fr) minmax(180px, 1.1fr);

      align-items: center;

      gap: 22px;

      margin-top: 8px;

      min-height: 220px;

    }

    .donut-canvas-wrap {

      position: relative;

      width: 100%;

      max-width: 210px;

      height: 210px;

      justify-self: center;

      flex-shrink: 0;

    }

    .donut-canvas-wrap canvas {

      position: relative;

      z-index: 1;

    }

    .donut-center-label {

      position: absolute;

      inset: 0;

      display: flex;

      flex-direction: column;

      align-items: center;

      justify-content: center;

      pointer-events: none;

      text-align: center;

      z-index: 2;

    }

    .donut-center-label strong {

      color: #183329;

      font-size: 30px;

      font-weight: 750;

      line-height: 1.05;

    }

    .donut-center-label span {

      color: #87948d;

      font-size: 10px;

      font-weight: 600;

      letter-spacing: .07em;

      text-transform: uppercase;

      margin-top: 5px;

    }

    .donut-legend {

      display: grid;

      gap: 10px;

      min-width: 0;

    }

    .donut-legend-item {

      display: grid;

      grid-template-columns: 10px minmax(0, 1fr);

      align-items: start;

      column-gap: 9px;

      background: #f8faf8;

      border: 1px solid #edf1ed;

      border-radius: 10px;

      padding: 12px;

      color: #52635a;

      font-size: 12px;

      min-width: 0;

    }

    .donut-legend-item .dl-dot {

      width: 9px;

      height: 9px;

      border-radius: 50%;

      margin-top: 4px;

    }

    .competency-status-copy {

      display: flex;

      flex-direction: column;

      gap: 5px;

      min-width: 0;

    }

    .competency-status-heading {

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 8px;

      font-weight: 600;

      color: #405349;

    }

    .competency-status-heading strong {

      color: #183329;

      font-size: 15px;

      font-weight: 750;

    }

    .competency-status-percent {

      color: #87948d;

      font-size: 11px;

    }

    @media (max-width: 900px) {

      .donut-wrap {

        grid-template-columns: 1fr;

        gap: 14px;

      }

      .donut-canvas-wrap {

        width: 190px;

        height: 190px;

      }

      .donut-legend {

        grid-template-columns: 1fr 1fr;

      }

    }

    @media (max-width: 480px) {

      .donut-legend {

        grid-template-columns: 1fr;

      }

    }

    /* ── Student table ────────────────────────────────────────────────────── */

    .tbl-card {

      background: white;

      border-radius: 12px;

      border: 0.5px solid #e2e8f0;

      overflow: hidden;

      margin-bottom: 24px;

    }

    .tbl-head {

      display: flex;

      justify-content: space-between;

      align-items: center;

      padding: 14px 18px;

      border-bottom: 0.5px solid #f0f4f8;

    }

    .tbl-head span {

      font-size: 13px;

      font-weight: 600;

      color: #4a5568;

    }

    .tbl-head a {

      font-size: 12px;

      color: #1a4d2e;

      font-weight: 600;

      text-decoration: none;

    }

    .tbl-card table {

      width: 100%;

      border-collapse: collapse;

      font-size: 13px;

    }

    .tbl-card th {

      font-size: 11px;

      color: #a0aec0;

      font-weight: 600;

      text-transform: uppercase;

      letter-spacing: 0.04em;

      padding: 10px 18px;

      text-align: left;

      border-bottom: 0.5px solid #f0f4f8;

    }

    .tbl-card td {

      padding: 11px 18px;

      color: #4a5568;

      border-bottom: 0.5px solid #f8f9fa;

    }

    .tbl-card tr:last-child td {

      border-bottom: none;

    }

    .prog-bar {

      background: #e8f5e9;

      border-radius: 999px;

      height: 6px;

      width: 90px;

      overflow: hidden;

      display: inline-block;

      vertical-align: middle;

    }

    .prog-fill {

      background: #1a4d2e;

      height: 6px;

      border-radius: 999px;

    }

    .prog-fill-warn {

      background: #f5c842;

    }

    .prog-fill-red {

      background: #e05c6e;

    }

    .no-data {

      color: #a0aec0;

      font-size: 13px;

      text-align: center;

      padding: 24px;

    }

    .table-responsive {

      width: 100%;

      overflow-x: auto;

    }

    .tbl-card table {

      min-width: 760px;

    }

    .trainee-email {

      display: block;

      margin-top: 3px;

      color: #87948d;

      font-size: 11px;

      font-weight: 400;

    }

    .score-cell {

      display: flex;

      align-items: center;

      gap: 8px;

      white-space: nowrap;

    }

    .score-cell .prog-bar {

      width: 72px;

      flex: 0 0 72px;

    }

    .chart-subtitle {

      display: block;

      margin-top: 4px;

      color: #87948d;

      font-size: 11px;

      font-weight: 400;

    }

    .chart-wrap-tall {

      height: 260px;

    }

    .chart-empty {

      margin: 8px 0 0;

      padding: 12px;

      text-align: center;

      color: #87948d;

      font-size: 12px;

      background: #f8faf8;

      border-radius: 8px;

    }

    .chart-empty[hidden] {

      display: none !important;

    }

    canvas[hidden] {

      display: none !important;

    }

    @media (max-width: 700px) {

      .chart-card {

        min-width: 0;

        padding: 14px;

      }

      .chart-wrap {

        height: 220px;

      }

      .chart-wrap-tall {

        height: 280px;

      }

    }

    /* ── Refined analytics chart styling ───────────────────────────────── */

    .chart-grid {

      align-items: stretch;

      gap: 18px;

    }

    .chart-card {

      min-width: 0;

      padding: 20px;

      border: 1px solid #e3ebe5;

      border-radius: 16px;

      box-shadow: 0 3px 12px rgba(24, 51, 41, 0.035);

      transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;

    }

    .chart-card:hover {

      border-color: #cbded0;

      box-shadow: 0 8px 22px rgba(24, 51, 41, 0.07);

      transform: translateY(-2px);

    }

    .chart-card .cc-title {

      margin-bottom: 16px;

      color: #183329;

      font-size: 14px;

      font-weight: 700;

      line-height: 1.45;

    }

    .chart-subtitle {

      margin-top: 5px;

      color: #7b8b81;

      font-size: 11px;

      line-height: 1.5;

    }

    .chart-wrap {

      min-width: 0;

      height: 230px;

    }

    .chart-wrap-tall {

      height: 285px;

    }

    .donut-wrap {

      gap: 18px;

      min-height: 230px;

    }

    .donut-canvas-wrap {

      max-width: 220px;

      height: 220px;

    }

    .donut-legend {

      gap: 9px;

    }

    .donut-legend-item {

      padding: 12px 13px;

      background: #f8fbf8;

      border-color: #e8efe9;

      border-radius: 11px;

    }

    .competency-status-heading strong {

      font-size: 17px;

      font-variant-numeric: tabular-nums;

    }

    .competency-status-percent {

      line-height: 1.45;

    }

    .chart-empty {

      border: 1px dashed #dce8df;

      background: #f8fbf8;

      line-height: 1.5;

    }

    @media (max-width: 900px) {

      .chart-grid {

        gap: 14px;

      }

      .chart-card {

        padding: 16px;

      }

      .donut-wrap {

        grid-template-columns: 1fr;

      }

      .donut-legend {

        grid-template-columns: 1fr 1fr;

      }

    }

    @media (max-width: 600px) {

      .chart-grid {

        grid-template-columns: minmax(0, 1fr);

      }

      .chart-card {

        padding: 15px;

        border-radius: 13px;

      }

      .chart-wrap {

        height: 225px;

      }

      .chart-wrap-tall {

        height: 290px;

      }

      .donut-legend {

        grid-template-columns: 1fr;

      }

    }

    @media (prefers-reduced-motion: reduce) {

      .chart-card {

        transition: none;

      }

      .chart-card:hover {

        transform: none;

      }

    }

  </style>

@endsection

@section('content')

  <div style="padding: 24px;">

    {{-- ── Greeting Banner ──────────────────────────────────────────────── --}}

    <div class="greeting-banner">

      <div>

        <h2>Hello, {{ Auth::user()->firstname }}! </h2>

        <p>{{ now()->format('l, F j, Y') }} · LEDIPO — Trainer Dashboard</p>

      </div>

      <div class="banner-stats">

        <div class="banner-stat">

          <span class="bval">{{ $totalTrainees ?? 0 }}</span>

          <span class="blbl">Trainees</span>

        </div>

        <div class="banner-stat">

          <span class="bval">{{ $completionRate ?? '0%' }}</span>

          <span class="blbl">Completion</span>

        </div>

        <div class="banner-stat">

          <span class="bval">{{ $urgentAssessments ?? 0 }}</span>

          <span class="blbl">Urgent</span>

        </div>

      </div>

    </div>

    @if (!($attendanceTakenToday ?? false))

      <div

        style="background:#fff8e1;color:#b7791f;border-radius:10px;padding:14px 20px;margin-bottom:20px;font-size:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">

        <span>⚠️ You haven't taken today's attendance yet.</span>

        <a href="{{ route('trainer.attendance') }}"

          style="background:#1a4d2e;color:white;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">

          Take attendance

        </a>

      </div>

    @endif

    {{-- ── Analytics heading ─────────────────────────────────────────────── --}}

    <p class="section-heading">Overview</p>

    <div class="stat-grid">

      <div class="stat-card">

        <div class="sc-label">Total trainees</div>

        <div class="sc-value">{{ $totalTrainees ?? 0 }}</div>

        <span class="badge-green">Enrolled</span>

      </div>

      <div class="stat-card">

        <div class="sc-label">New this month</div>

        <div class="sc-value">{{ $monthlyEnrollment ?? 0 }}</div>

        <div class="sc-sub">enrolled in {{ now()->format('F') }}</div>

      </div>

      <div class="stat-card">

        <div class="sc-label">Completion rate</div>

        <div class="sc-value">{{ $completionRate ?? '0%' }}</div>

        <span class="badge-yellow">In progress</span>

      </div>

      <div class="stat-card">

        <div class="sc-label">Urgent assessments</div>

        <div class="sc-value">{{ $urgentAssessments ?? 0 }}</div>

        @if (($urgentAssessments ?? 0) > 0)

          <span class="badge-red">Within 3 days</span>

        @else

          <span class="badge-green">All clear</span>

        @endif

      </div>

    </div>

    {{-- ── Dynamic Analytics Charts ───────────────────────────────────── --}}

    <p class="section-heading">Training Analytics</p>

    <div class="chart-grid">

      <div class="chart-card">

        <div class="cc-title">Trainee Enrollment Trend

          <span class="chart-subtitle">New enrollments over the last 6

            months</span>

        </div>

        <div class="chart-wrap">

          <canvas id="enrollmentTrendChart" role="img"

            aria-label="New trainee enrollments over the last six months"></canvas>

        </div>

        <p class="chart-empty" id="enrollmentTrendEmpty" hidden>No enrollment

          records available for this period.</p>

      </div>

      <div class="chart-card">

        <div class="cc-title">Quiz vs. Onsite Performance

          <span class="chart-subtitle">Average scores by course</span>

        </div>

        <div class="chart-wrap chart-wrap-tall">

          <canvas id="assessmentComparisonChart" role="img"

            aria-label="Average quiz and onsite assessment scores by course"></canvas>

        </div>

        <p class="chart-empty" id="assessmentComparisonEmpty" hidden>No quiz and

          onsite assessment results are available yet.</p>

      </div>

      <div class="chart-card">

        <div class="cc-title">Competency Breakdown

          <span class="chart-subtitle">Based on the combined quiz and onsite

            assessment score</span>

        </div>

        <div class="donut-wrap">

          <div class="donut-canvas-wrap">

            <canvas id="competencyBreakdownChart" role="img"

              aria-label="Competent versus not yet competent trainees"></canvas>

            <div class="donut-center-label" aria-hidden="true">

              <strong id="competencyTotalCount">0</strong>

              <span>Assessed</span>

            </div>

          </div>

          <div class="donut-legend" id="competencyBreakdownLegend">

            <div class="donut-legend-item">

              <span class="dl-dot" style="background:#075b35;"></span>

              <div class="competency-status-copy">

                <div class="competency-status-heading">

                  <span>Competent</span>

                  <strong id="competentCount">0</strong>

                </div>

                <span class="competency-status-percent" id="competentPercent">0%

                  of assessed trainees</span>

              </div>

            </div>

            <div class="donut-legend-item">

              <span class="dl-dot" style="background:#d95f59;"></span>

              <div class="competency-status-copy">

                <div class="competency-status-heading">

                  <span>Not Yet Competent</span>

                  <strong id="notYetCompetentCount">0</strong>

                </div>

                <span class="competency-status-percent"

                  id="notYetCompetentPercent">0% of assessed trainees</span>

              </div>

            </div>

            <div class="donut-legend-item">
            <span class="dl-dot" style="background:#e1dc36;"></span>
            <div class="competency-status-copy">
              <div class="competency-status-heading">
                <span>Pending Assessment</span>
                <strong id="pendingAssessmentCount">0</strong>
              </div>
              <span class="competency-status-percent" id="pendingAssessmentPercent">0% of all trainees</span>
            </div>
          </div>

          </div>

        
          
</div>

        <p class="chart-empty" id="competencyBreakdownEmpty" hidden>No assessment

          results are available yet.</p>

      </div>

      <div class="chart-card">

        <div class="cc-title">Trainee Progress Distribution

          <span class="chart-subtitle">Trainees grouped by their learning

            progress</span>

        </div>

        <div class="chart-wrap chart-wrap-tall">

          <canvas id="traineeProgressDistributionChart" role="img"

            aria-label="Trainee learning progress distribution"></canvas>

        </div>

        <p class="chart-empty" id="traineeProgressDistributionEmpty" hidden>No

          trainee progress records are available yet.</p>

      </div>

    </div>

    {{-- ── Assessment Performance ──────────────────────────────────────── --}}

    <p class="section-heading">Assessment performance</p>

    <div class="tbl-card">

      <div class="tbl-head">

        <span>Trainees Requiring Attention (below 75% or pending

          assessment)</span>

        <a href="{{ route('trainer.students') }}">See all →</a>

      </div>

      @if (isset($lowPerforming) && $lowPerforming->isNotEmpty())

        <div class="table-responsive">

          <table>

            <thead>

              <tr>

                <th>Trainee</th>

                <th>Course</th>

                <th>Quiz Average</th>

                <th>Onsite Average</th>

                <th>Overall Assessment</th>

                <th>Status</th>

              </tr>

            </thead>

            <tbody>

              @foreach ($lowPerforming as $s)

                @php

                  // Use the controller's computed assessment values.

$quizAverage = $s->quiz_average ?? null;

$onsiteAverage = $s->onsite_average ?? null;

$assessmentScore = $s->assessment_score ?? null;

$assessmentStatus =

    $s->assessment_status ?? 'Pending Assessment';

                @endphp

                <tr>

                  <td>

                    <strong>{{ $s->user->firstname ?? '—' }}

                      {{ $s->user->lastname ?? '' }}</strong>

                    @if (!empty($s->user->email))

                      <small class="trainee-email">{{ $s->user->email }}</small>

                    @endif

                  </td>

                  <td>{{ $s->course->title ?? '—' }}</td>

                  <td>

                    {{ $quizAverage !== null ? number_format($quizAverage, 2) . '%' : '—' }}

                  </td>

                  <td>

                    {{ $onsiteAverage !== null ? number_format($onsiteAverage, 2) . '%' : '—' }}

                  </td>

                  <td>

                    @if ($assessmentScore !== null)

                      <div class="score-cell">

                        <div class="prog-bar">

                          <div

                            class="prog-fill {{ $assessmentScore < 75 ? 'prog-fill-red' : '' }}"

                            style="width: {{ min(100, max(0, $assessmentScore)) }}%;">

                          </div>

                        </div>

                        <strong>{{ number_format($assessmentScore, 2) }}%</strong>

                      </div>

                    @else

                      <span style="color:#87948d;">—</span>

                    @endif

                  </td>

                  <td>

                    @if ($assessmentStatus === 'Competent')

                      <span class="badge-green">Competent</span>

                    @elseif ($assessmentStatus === 'Not Yet Competent')

                      <span class="badge-red">Not Yet Competent</span>

                    @else

                      <span class="badge-yellow">Pending Assessment</span>

                    @endif

                  </td>

                </tr>

              @endforeach

            </tbody>

          </table>

        </div>

      @else

        <div class="no-data">

          <strong>No trainees currently require assessment follow-up.</strong>

          <p>Trainees need recorded quiz and onsite results before a combined

            assessment score can be calculated.</p>

        </div>

      @endif

    </div>

  </div>

@endsection

@section('scripts')

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js">

  </script>

  @php

    $chartEnrollmentLabels = $chartEnrollmentLabels ?? [];

    $chartEnrollmentValues = $chartEnrollmentValues ?? [];

    $chartAssessmentLabels = $chartAssessmentLabels ?? [];

    $chartQuizAverages = $chartQuizAverages ?? [];

    $chartOnsiteAverages = $chartOnsiteAverages ?? [];

    $chartCompetentCount = $chartCompetentCount ?? 0;

    $chartNotYetCompetentCount = $chartNotYetCompetentCount ?? 0;

    $chartPendingAssessmentCount = $chartPendingAssessmentCount ?? 0;

    $chartAssessedCount = $chartAssessedCount ?? ($chartCompetentCount + $chartNotYetCompetentCount);

    $chartTotalTrainees = $chartTotalTrainees ?? $chartAssessedCount;

    $progressDistribution = $progressDistribution ?? [0, 0, 0, 0, 0];

  @endphp

  <script>

    document.addEventListener('DOMContentLoaded', function() {

      if (typeof Chart === 'undefined') {

        console.error('Chart.js did not load. Check the CDN connection.');

        return;

      }

      const colors = {

        green: '#075b35',

        greenSoft: 'rgba(7, 91, 53, 0.10)',

        yellow: '#e1dc36',

        red: '#d95f59',

        gray: '#aab7af',

        grid: '#edf1ed',

        text: '#6b7c73'

      };

      const baseOptions = {

        responsive: true,

        maintainAspectRatio: false,

        animation: {

          duration: 450

        },

        plugins: {

          legend: {

            position: 'bottom',

            labels: {

              usePointStyle: true,

              pointStyle: 'circle',

              boxWidth: 7,

              boxHeight: 7,

              padding: 16,

              color: colors.text,

              font: {

                size: 11

              }

            }

          },

          tooltip: {

            backgroundColor: '#183329',

            padding: 10,

            cornerRadius: 8,

            titleFont: {

              size: 12

            },

            bodyFont: {

              size: 11

            }

          }

        },

        scales: {

          x: {

            grid: {

              display: false

            },

            ticks: {

              color: colors.text,

              font: {

                size: 10

              },

              maxRotation: 0

            }

          },

          y: {

            beginAtZero: true,

            grid: {

              color: colors.grid

            },

            ticks: {

              color: colors.text,

              font: {

                size: 10

              },

              precision: 0

            }

          }

        }

      };

      // Draw values directly on line and bar charts so trainers do not need to hover.

      const visibleValueLabels = {

        id: 'visibleValueLabels',

        afterDatasetsDraw(chart) {

          if (chart.config.type === 'doughnut') return;

          const { ctx, chartArea } = chart;

          if (!chartArea) return;

          ctx.save();

          ctx.font = '600 11px Inter, system-ui, -apple-system, sans-serif';

          ctx.textBaseline = 'middle';

          chart.data.datasets.forEach((dataset, datasetIndex) => {

            const meta = chart.getDatasetMeta(datasetIndex);

            if (meta.hidden) return;

            meta.data.forEach((element, index) => {

              const rawValue = dataset.data[index];

              if (rawValue === null || rawValue === undefined || rawValue === '') return;

              const numericValue = Number(rawValue);

              if (!Number.isFinite(numericValue)) return;

              const position = element.tooltipPosition();

              const isHorizontal = chart.options.indexAxis === 'y';

              const isPercentChart = chart.canvas.id === 'assessmentComparisonChart';

              const label = `${Number.isInteger(numericValue) ? numericValue : numericValue.toFixed(1)}${isPercentChart ? '%' : ''}`;

              ctx.fillStyle = '#405349';

              if (chart.config.type === 'line') {

                ctx.textAlign = 'center';

                ctx.textBaseline = 'bottom';

                ctx.fillText(label, position.x, Math.max(chartArea.top + 12, position.y - 9));

              } else if (isHorizontal) {

                ctx.textAlign = 'left';

                ctx.textBaseline = 'middle';

                ctx.fillText(label, Math.min(position.x + 7, chartArea.right - 4), position.y);

              } else {

                ctx.textAlign = 'center';

                ctx.textBaseline = 'bottom';

                ctx.fillText(label, position.x, Math.max(chartArea.top + 12, position.y - 7));

              }

            });

          });

          ctx.restore();

        }

      };

      Chart.register(visibleValueLabels);

      function showEmpty(canvasId, emptyId, isEmpty) {

        const canvas = document.getElementById(canvasId);

        const empty = document.getElementById(emptyId);

        if (!canvas || !empty) return false;

        canvas.hidden = isEmpty;

        empty.hidden = !isEmpty;

        return !isEmpty;

      }

      function createChart(canvasId, emptyId, type, labels, datasets,

        options = {}, hasDataOverride = null) {

        const hasData = hasDataOverride !== null ?

          hasDataOverride :

          labels.length > 0 && datasets.some(dataset =>

            Array.isArray(dataset.data) &&

            dataset.data.some(value => value !== null && value !==

              undefined && Number.isFinite(Number(value)))

          );

        if (!showEmpty(canvasId, emptyId, !hasData)) return;

        const canvas = document.getElementById(canvasId);

        new Chart(canvas, {

          type,

          data: {

            labels,

            datasets

          },

          options: {

            ...baseOptions,

            ...options,

            plugins: {

              ...baseOptions.plugins,

              ...(options.plugins || {})

            },

            scales: type === 'doughnut' ?

              {} :

              {

                ...baseOptions.scales,

                ...(options.scales || {})

              }

          }

        });

      }

      const enrollmentLabels = @json($chartEnrollmentLabels);

      const enrollmentValues = @json($chartEnrollmentValues);

      createChart('enrollmentTrendChart', 'enrollmentTrendEmpty', 'line',

        enrollmentLabels,

        [{

          label: 'New enrollments',

          data: enrollmentValues,

          borderColor: colors.green,

          backgroundColor: colors.greenSoft,

          borderWidth: 2.5,

          pointRadius: 3,

          pointHoverRadius: 5,

          pointBackgroundColor: colors.yellow,

          pointBorderColor: colors.green,

          fill: true,

          tension: 0.32,

          spanGaps: false

        }], {

          plugins: {

            legend: {

              display: false

            }

          },

          scales: {

            y: {

              beginAtZero: true,

              ticks: {

                precision: 0,

                stepSize: 1

              }

            }

          }

        },

        enrollmentLabels.length > 0 && enrollmentValues.some(value =>

          Number(value) > 0)

      );

      const assessmentLabels = @json($chartAssessmentLabels);

      const quizAverages = @json($chartQuizAverages);

      const onsiteAverages = @json($chartOnsiteAverages);

      createChart('assessmentComparisonChart', 'assessmentComparisonEmpty',

        'bar',

        assessmentLabels,

        [{

            label: 'Quiz average',

            data: quizAverages,

            backgroundColor: colors.green,

            borderRadius: 5,

            maxBarThickness: 24

          },

          {

            label: 'Onsite average',

            data: onsiteAverages,

            backgroundColor: colors.yellow,

            borderRadius: 5,

            maxBarThickness: 24

          }

        ], {

          scales: {

            x: {

              ...baseOptions.scales.x,

              ticks: {

                ...baseOptions.scales.x.ticks,

                maxRotation: 25,

                minRotation: 0

              }

            },

            y: {

              min: 0,

              max: 100,

              grid: {

                color: colors.grid

              },

              ticks: {

                color: colors.text,

                stepSize: 25,

                callback: value => value + '%'

              }

            }

          }

        },

        assessmentLabels.length > 0 && [...quizAverages, ...onsiteAverages]

        .some(value => Number(value) > 0)

      );

      const competentCount = Number(@json($chartCompetentCount));

      const notYetCompetentCount = Number(@json($chartNotYetCompetentCount));

      const pendingAssessmentCount = Number(@json($chartPendingAssessmentCount));

      const assessedTotal = Number(@json($chartAssessedCount));

      const totalTrainees = Number(@json($chartTotalTrainees));

      document.getElementById('competentCount').textContent = competentCount;

      document.getElementById('notYetCompetentCount').textContent =

        notYetCompetentCount;

      document.getElementById('competencyTotalCount').textContent =

        assessedTotal;

      document.getElementById('competentPercent').textContent =

        `${assessedTotal ? ((competentCount / assessedTotal) * 100).toFixed(1) : '0.0'}% of assessed trainees`;

      document.getElementById('notYetCompetentPercent').textContent =

        `${assessedTotal ? ((notYetCompetentCount / assessedTotal) * 100).toFixed(1) : '0.0'}% of assessed trainees`;

      document.getElementById('pendingAssessmentCount').textContent = pendingAssessmentCount;

      document.getElementById('pendingAssessmentPercent').textContent =

        `${totalTrainees ? ((pendingAssessmentCount / totalTrainees) * 100).toFixed(1) : '0.0'}% of all trainees`;

      createChart('competencyBreakdownChart', 'competencyBreakdownEmpty',

        'doughnut',

        ['Competent', 'Not Yet Competent', 'Pending Assessment'],

        [{

          data: [competentCount, notYetCompetentCount, pendingAssessmentCount],

          backgroundColor: [colors.green, colors.red, colors.yellow],

          borderColor: '#ffffff',

          borderWidth: 4,

          spacing: 2,

          hoverOffset: 6

        }], {

          cutout: '70%',

          plugins: {

            legend: {

              display: false

            },

            tooltip: {

              callbacks: {

                label: context => {

                  const total = context.dataset.data.reduce((sum,

                    value) => sum + Number(value || 0), 0);

                  const count = Number(context.raw || 0);

                  const percentage = total > 0 ? ((count / total) * 100)

                    .toFixed(1) : '0.0';

                  return `${context.label}: ${count} (${percentage}%)`;

                }

              }

            }

          }

        },

        competentCount + notYetCompetentCount + pendingAssessmentCount > 0

      );

      const progressValues = @json(array_values($progressDistribution));

createChart(

    'traineeProgressDistributionChart',

    'traineeProgressDistributionEmpty',

    'bar',

    [

        'Competent (75–100%)',

    'Not Yet Competent (50–74.99%)',

    'Below 50% (Above 0–49.99%)',

    '0% Combined Score',
    'Pending Assessment',

    ],

        [{

          label: 'Trainees',

          data: progressValues,

          backgroundColor: [colors.green, colors.red, '#83b99a', colors.gray, colors.yellow],

          borderRadius: 5,

          maxBarThickness: 34

        }], {

          indexAxis: 'y',

          plugins: {

            legend: {

              display: false

            },

            tooltip: {

              callbacks: {

                label: context =>

                  ` ${Number(context.raw || 0)} trainee${Number(context.raw || 0) === 1 ? '' : 's'}`

              }

            }

          },

          layout: {
            padding: {
              right: 24
            }
          },

          scales: {

            x: {

              beginAtZero: true,
              // Leave room for labels at the end of bars (e.g. 18).
              suggestedMax: Math.max(20, ...progressValues.map(value => Number(value) || 0)),


              grid: {

                color: colors.grid

              },

              ticks: {

                color: colors.text,

                precision: 0,

                stepSize: 1

              }

            },

            y: {

              grid: {

                display: false

              },

              ticks: {

                color: colors.text,

                font: {

                  size: 10

                }

              }

            }

          }

        },

        progressValues.some(value => Number(value) > 0)

      );

    });

  </script>

@endsection

