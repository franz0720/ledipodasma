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
    .donut-wrap {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-top: 10px;
    }

    .donut-canvas-wrap {
      position: relative;
      width: 140px;
      height: 140px;
      flex-shrink: 0;
    }

    .donut-legend {
      font-size: 12px;
      color: #718096;
    }

    .donut-legend-item {
      display: flex;
      align-items: center;
      gap: 7px;
      margin-bottom: 8px;
    }

    .dl-dot {
      width: 10px;
      height: 10px;
      border-radius: 2px;
      flex-shrink: 0;
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
  </style>
@endsection

@section('content')
  <div style="padding: 24px;">

    {{-- ── Greeting Banner ──────────────────────────────────────────────── --}}
    <div class="greeting-banner">
      <div>
        <h2>Hello, {{ Auth::user()->firstname }}! 👋</h2>
        <p>{{ now()->format('l, F j, Y') }} · LEDIPO — Trainer Dashboard</p>
      </div>
      <div class="banner-stats">
        <div class="banner-stat">
          <span class="bval">{{ $totalTrainees }}</span>
          <span class="blbl">Trainees</span>
        </div>
        <div class="banner-stat">
          <span class="bval">{{ $completionRate }}</span>
          <span class="blbl">Completion</span>
        </div>
        <div class="banner-stat">
          <span class="bval">{{ $urgentAssessments }}</span>
          <span class="blbl">Urgent</span>
        </div>
      </div>
    </div>

    @if (!$attendanceTakenToday)
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
        <div class="sc-value">{{ $totalTrainees }}</div>
        <span class="badge-green">Active</span>
      </div>
      <div class="stat-card">
        <div class="sc-label">New this month</div>
        <div class="sc-value">{{ $monthlyEnrollment }}</div>
        <div class="sc-sub">enrolled in {{ now()->format('F') }}</div>
      </div>
      <div class="stat-card">
        <div class="sc-label">Completion rate</div>
        <div class="sc-value">{{ $completionRate }}</div>
        <span class="badge-yellow">In progress</span>
      </div>
      <div class="stat-card">
        <div class="sc-label">Urgent assessments</div>
        <div class="sc-value">{{ $urgentAssessments }}</div>
        @if ($urgentAssessments > 0)
          <span class="badge-red">Within 3 days</span>
        @else
          <span class="badge-green">All clear</span>
        @endif
      </div>
    </div>

    {{-- ── Charts ───────────────────────────────────────────────────────── --}}
    <p class="section-heading">Analytics</p>
    <div class="chart-grid">

      {{-- Student Progress Over the Week (line chart) --}}
      <div class="chart-card">
        <div class="cc-title">
          Student avg. progress this week (%)
          <span
            style="font-size:11px;color:#a0aec0;font-weight:400;margin-left:6px;">·
            updates daily once attendance is live</span>
        </div>
        <div class="chart-wrap">
          <canvas id="weeklyProgressChart" role="img"
            aria-label="Line chart showing average student progress per day this week">
            No data available.
          </canvas>
        </div>
      </div>

      {{-- Student Progress Distribution Donut --}}
      <div class="chart-card">
        <div class="cc-title">Student progress distribution</div>
        <div class="donut-wrap">
          <div class="donut-canvas-wrap">
            <canvas id="donutChart" role="img"
              aria-label="Donut chart showing student progress breakdown by range">
              No data available.
            </canvas>
          </div>
          <div class="donut-legend">
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#1a4d2e;"></span>
              Completed — {{ $progressDistribution[0] }}
            </div>
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#f5c842;"></span>
              50–99% — {{ $progressDistribution[1] }}
            </div>
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#e05c6e;"></span>
              Below 50% — {{ $progressDistribution[2] }}
            </div>
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#e2e8f0;"></span>
              Not started — {{ $progressDistribution[3] }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Low Performing Students ──────────────────────────────────────── --}}
    <p class="section-heading">Students needing attention</p>
    <div class="tbl-card">
      <div class="tbl-head">
        <span>Low performing trainees (below 50%)</span>
        <a href="{{ route('trainer.students') }}">See all →</a>
      </div>
      @if ($lowPerforming->count())
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Course</th>
              <th>Progress</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lowPerforming as $s)
              <tr>
                <td>{{ $s->user->firstname ?? '—' }}
                  {{ $s->user->lastname ?? '' }}</td>
                <td>{{ $s->course->title ?? '—' }}</td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px;">
                    <div class="prog-bar">
                      <div
                        class="prog-fill {{ $s->progress < 25 ? 'prog-fill-red' : 'prog-fill-warn' }}"
                        style="width:{{ $s->progress }}%;"></div>
                    </div>
                    <span>{{ $s->progress }}%</span>
                  </div>
                </td>
                <td>
                  @if ($s->progress < 25)
                    <span class="badge-red">At risk</span>
                  @else
                    <span class="badge-yellow">Needs help</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="no-data">All students are performing well!</div>
      @endif
    </div>

  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js">
  </script>
  <script>
    // ── Student Avg Progress Line Chart ───────────────────────────────────
    // Real data from daily_progress, averaged across the trainer's active students.
    const weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    const progressData = @json($weeklyProgressData);

    new Chart(document.getElementById('weeklyProgressChart'), {
      type: 'line',
      data: {
        labels: weekDays,
        datasets: [{
          label: 'Avg Progress %',
          data: progressData,
          borderColor: '#1a4d2e',
          backgroundColor: 'rgba(26,77,46,0.08)',
          borderWidth: 2.5,
          pointBackgroundColor: '#f5c842',
          pointBorderColor: '#1a4d2e',
          pointBorderWidth: 2,
          pointRadius: 5,
          fill: true,
          tension: 0.3,
          spanGaps: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            ticks: {
              autoSkip: false,
              font: {
                size: 11
              },
              color: '#718096'
            },
            grid: {
              display: false
            }
          },
          y: {
            min: 0,
            max: 100,
            ticks: {
              stepSize: 25,
              font: {
                size: 11
              },
              color: '#718096',
              callback: v => Math.round(v) + '%'
            },
            grid: {
              color: '#f0f4f8'
            }
          }
        }
      }
    });

    // ── Progress Distribution Donut ───────────────────────────────────────
    new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: ['Completed', '50–99%', 'Below 50%', 'Not started'],
        datasets: [{
          data: @json($progressDistribution),
          backgroundColor: ['#1a4d2e', '#f5c842', '#e05c6e', '#e2e8f0'],
          borderWidth: 0,
          hoverOffset: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>
@endsection

