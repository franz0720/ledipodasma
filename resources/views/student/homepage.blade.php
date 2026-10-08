@extends('student.layout')

@section('title', 'Dashboard · LEDIPO')

@section('css')
  <style>
    .main-content {
      background: #f4f6f0;
    }

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
      align-items: stretch;
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

    .prog-bar-wrap {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 999px;
      height: 6px;
      margin-top: 10px;
      overflow: hidden;
      width: 220px;
    }

    .prog-bar-fill {
      background: #f5c842;
      height: 6px;
      border-radius: 999px;
    }

    /* ── Register CTA button, sits beside the stat pills ─────────────────── */
    .banner-register-btn {
      background: #f5c842;
      color: #1a4d2e;
      border-radius: 10px;
      padding: 10px 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      text-decoration: none;
      min-width: 110px;
      font-family: inherit;
      transition: background 0.15s ease, transform 0.1s ease;
    }

    .banner-register-btn:hover {
      background: #ffd75e;
      color: #1a4d2e;
      transform: translateY(-1px);
    }

    .banner-register-btn .rb-icon {
      font-size: 18px;
      line-height: 1;
      margin-bottom: 4px;
    }

    .banner-register-btn .rb-label {
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.02em;
    }

    .section-heading {
      font-size: 13px;
      font-weight: 600;
      color: #4a5568;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin: 0 0 12px;
    }

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

    .activity-card {
      background: white;
      border-radius: 12px;
      padding: 18px;
      border: 0.5px solid #e2e8f0;
      margin-bottom: 24px;
    }

    .activity-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 9px 0;
      border-bottom: 0.5px solid #f0f4f8;
      font-size: 13px;
      color: #4a5568;
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #e6f4eb;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .activity-time {
      font-size: 11px;
      color: #a0aec0;
      margin-left: auto;
      white-space: nowrap;
    }

    .badge-pass {
      background: #e6f4eb;
      color: #276749;
      font-size: 11px;
      padding: 1px 7px;
      border-radius: 999px;
    }

    .badge-fail {
      background: #fef2f2;
      color: #c53030;
      font-size: 11px;
      padding: 1px 7px;
      border-radius: 999px;
    }

    .no-data {
      color: #a0aec0;
      font-size: 13px;
      text-align: center;
      padding: 20px 0;
    }

    .bottom-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }

    @media (max-width: 700px) {
      .bottom-grid {
        grid-template-columns: 1fr;
      }
    }

    .ann-card {
      background: white;
      border-radius: 12px;
      padding: 18px;
      border: 0.5px solid #e2e8f0;
    }

    .ann-item {
      padding: 10px 0;
      border-bottom: 0.5px solid #f0f4f8;
    }

    .ann-item:last-child {
      border-bottom: none;
    }

    .ann-title {
      font-size: 14px;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 3px;
    }

    .ann-msg {
      font-size: 13px;
      color: #718096;
    }

    .ann-type-reminder {
      background: #e6f4eb;
      color: #276749;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .ann-type-notice {
      background: #ebf4ff;
      color: #1a5fa3;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .ann-type-urgent {
      background: #fef2f2;
      color: #c53030;
      font-size: 11px;
      padding: 2px 8px;
      border-radius: 999px;
    }

    .sched-card {
      background: white;
      border-radius: 12px;
      padding: 18px;
      border: 0.5px solid #e2e8f0;
    }

    .sched-item {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 8px 0;
      border-bottom: 0.5px solid #f0f4f8;
    }

    .sched-item:last-child {
      border-bottom: none;
    }

    .sched-badge {
      background: #e6f4eb;
      color: #1a4d2e;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 6px;
      white-space: nowrap;
      flex-shrink: 0;
    }

    .sched-title {
      font-size: 14px;
      font-weight: 600;
      color: #2d3748;
    }

    .sched-loc {
      font-size: 12px;
      color: #a0aec0;
      margin-top: 2px;
    }

    .location-card {
      background: white;
      border-radius: 12px;
      border: 0.5px solid #e2e8f0;
      overflow: hidden;
      margin-bottom: 24px;
    }

    .location-map {
      background: #e8f5e9;
      height: 130px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      color: #388e3c;
      font-weight: 500;
      gap: 8px;
    }

    .location-info {
      padding: 14px 18px;
      border-top: 0.5px solid #e2e8f0;
    }

    .location-info .li-title {
      font-size: 14px;
      font-weight: 700;
      color: #1a4d2e;
      margin-bottom: 4px;
    }

    .location-info .li-addr {
      font-size: 13px;
      color: #a0aec0;
      display: flex;
      align-items: center;
      gap: 6px;
    }
  </style>
@endsection

@section('content')
  <div style="padding: 24px;">

    {{-- ── Greeting Banner ──────────────────────────────────────────────── --}}
    <div class="greeting-banner">
      <div>
        <h2>Hello, {{ Auth::user()->firstname }}! 👋</h2>
        <p>{{ now()->format('l, F j, Y') }} · LEDIPO</p>
        @if ($enrollment)
          <div class="prog-bar-wrap">
            <div class="prog-bar-fill" style="width:{{ $avgProgress }}%;"></div>
          </div>
          <p style="font-size:11px;color:#b6d9c0;margin-top:4px;">Overall progress:
            {{ $avgProgress }}%</p>
        @endif
      </div>
      <div class="banner-stats">
        <a href="{{ route('registration.step1') }}" class="banner-register-btn">
          <span class="rb-icon">📝</span>
          <span class="rb-label">Register</span>
        </a>
        <div class="banner-stat">
          <span
            class="bval">{{ $enrollment ? ucfirst($enrollment->status) : 'N/A' }}</span>
          <span class="blbl">Status</span>
        </div>
        <div class="banner-stat">
          <span class="bval">{{ $avgProgress }}%</span>
          <span class="blbl">Progress</span>
        </div>
        <div class="banner-stat">
          <span class="bval">{{ $upcomingDeadlines }}</span>
          <span class="blbl">Deadlines</span>
        </div>
      </div>
    </div>

    {{-- ── Personal Analytics ────────────────────────────────────────────── --}}
    <p class="section-heading">Personal Analytics</p>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="sc-label">Courses enrolled</div>
        <div class="sc-value">{{ $totalEnrolled }}</div>
        <div class="sc-sub">{{ $totalCompleted }} completed</div>
      </div>
      <div class="stat-card">
        <div class="sc-label">Overall progress</div>
        <div class="sc-value">{{ $avgProgress }}%</div>
        @if ($avgProgress >= 50)
          <span class="badge-green">On track</span>
        @else
          <span class="badge-yellow">Keep going!</span>
        @endif
      </div>
      <div class="stat-card">
        <div class="sc-label">Quizzes taken</div>
        <div class="sc-value">{{ $quizzesTaken }}</div>
        <div class="sc-sub">{{ $quizzesPassed }} passed</div>
      </div>
    </div>

    {{-- ── Charts ───────────────────────────────────────────────────────── --}}
    <div class="chart-grid">
      <div class="chart-card">
        <div class="cc-title">
          Daily progress this week (%)
          <span
            style="font-size:11px;color:#a0aec0;font-weight:400;margin-left:6px;">·
            updates daily once attendance is live</span>
        </div>
        <div class="chart-wrap">
          <canvas id="progressChart" role="img"
            aria-label="Line chart showing daily progress percentage this week">
            No data available.
          </canvas>
        </div>
      </div>

      <div class="chart-card">
        <div class="cc-title">Course completion breakdown</div>
        <div class="donut-wrap">
          <div class="donut-canvas-wrap">
            <canvas id="donutChart" role="img"
              aria-label="Donut chart showing completed, in-progress, and not started courses">
              No data available.
            </canvas>
          </div>
          <div class="donut-legend">
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#1a4d2e;"></span>
              Completed — {{ $donutData[0] }}
            </div>
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#f5c842;"></span>
              In progress — {{ $donutData[1] }}
            </div>
            <div class="donut-legend-item">
              <span class="dl-dot" style="background:#e2e8f0;"></span>
              Not started — {{ $donutData[2] }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Recent Activity ──────────────────────────────────────────────── --}}
    <p class="section-heading">Recent activity</p>
    <div class="activity-card">
      @forelse($recentActivity as $item)
        <div class="activity-item">
          <div class="activity-icon">
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16">
              <path d="M13 5L7 11l-3-3" stroke="#276749" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <div style="flex:1;">
            Took quiz: <strong>{{ $item->quiz->title ?? 'Quiz' }}</strong>
            &nbsp;
            @if ($item->status === 'passed')
              <span class="badge-pass">Passed · {{ $item->percentage }}%</span>
            @else
              <span class="badge-fail">Failed · {{ $item->percentage }}%</span>
            @endif
          </div>
          <span
            class="activity-time">{{ $item->created_at->diffForHumans() }}</span>
        </div>
      @empty
        <div class="no-data">No recent activity yet.</div>
      @endforelse
    </div>

    {{-- ── Announcements + Today's Schedule ────────────────────────────── --}}
    <div class="bottom-grid">
      <div>
        <p class="section-heading">
          <i class="fa fa-bullhorn" style="color:#f5c842;margin-right:6px;"></i>
          Announcements
        </p>
        <div class="ann-card">
          @forelse($announcements as $ann)
            <div class="ann-item">
              <div
                style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                <span class="ann-title">{{ $ann->title }}</span>
                <span
                  class="ann-type-{{ $ann->type }}">{{ ucfirst($ann->type) }}</span>
              </div>
              <div class="ann-msg">{{ $ann->message }}</div>
            </div>
          @empty
            <div class="no-data">No announcements at the moment.</div>
          @endforelse
        </div>
      </div>

      <div>
        <p class="section-heading">
          <i class="fa fa-calendar-day"
            style="color:#1a4d2e;margin-right:6px;"></i>
          Today's Schedule
        </p>
        <div class="sched-card">
          @if ($enrollment && $enrollment->course)
            <div class="sched-item">
              <span class="sched-badge">
                {{ $enrollment->course->schedule ?? 'Mon-Fri 10:00 AM' }}
              </span>
              <div>
                <div class="sched-title">{{ $enrollment->course->title }}</div>
                <div class="sched-loc">
                  <i class="fa fa-location-dot"
                    style="color:#e05c6e;font-size:11px;"></i>
                  {{ $enrollment->course->location ?? 'LEDIPO Main, City Hall Compound' }}
                </div>
              </div>
            </div>
          @else
            <div class="no-data">No schedule found.</div>
          @endif
        </div>
      </div>
    </div>

    {{-- ── Training Location ────────────────────────────────────────────── --}}
    <p class="section-heading">
      <i class="fa fa-location-dot" style="color:#e05c6e;margin-right:6px;"></i>
      Training Location
    </p>
    <div class="location-card">
      <div class="location-map">
        <i class="fa fa-map" style="font-size:20px;"></i>
        {{ $enrollment && $enrollment->course ? $enrollment->course->location ?? 'LEDIPO Main, City Hall Compound' : 'LEDIPO Main, City Hall Compound' }}
      </div>
      <div class="location-info">
        @if ($enrollment && $enrollment->course)
          <div class="li-title">{{ $enrollment->course->title }}</div>
        @endif
        <div class="li-addr">
          <i class="fa fa-location-dot"
            style="color:#e05c6e;font-size:12px;"></i>
          {{ $enrollment && $enrollment->course ? $enrollment->course->location ?? 'LEDIPO Main, City Hall Compound' : 'LEDIPO Main, City Hall Compound' }}
        </div>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js">
  </script>
  <script>
    // ── Daily Progress Line Chart ─────────────────────────────────────────
    const weekDays = @json($weekDays);
    const progressData = @json($weeklyData);

    new Chart(document.getElementById('progressChart'), {
      type: 'line',
      data: {
        labels: weekDays,
        datasets: [{
          label: 'Progress %',
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

    // ── Donut Chart ───────────────────────────────────────────────────────
    new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'In progress', 'Not started'],
        datasets: [{
          data: @json($donutData),
          backgroundColor: ['#1a4d2e', '#f5c842', '#e2e8f0'],
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

