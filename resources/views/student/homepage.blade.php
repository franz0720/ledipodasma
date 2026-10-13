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

    /* =========================================
                           DASHBOARD SECTION
                        ========================================= */
    .chart-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 18px;
      margin-bottom: 26px;
      align-items: stretch;
    }

    .chart-card {
      min-width: 0;
      min-height: 360px;
      padding: 22px;
      background: #ffffff;
      border: 1px solid #e2ebe5;
      border-radius: 16px;
      box-shadow: 0 3px 12px rgba(24, 51, 41, 0.035);
      transition:
        box-shadow 0.2s ease,
        transform 0.2s ease;
    }

    .chart-card:hover {
      box-shadow: 0 7px 20px rgba(24, 51, 41, 0.07);
      transform: translateY(-2px);
    }

    .cc-heading {
      min-height: 52px;
      margin-bottom: 16px;
    }

    .chart-card .cc-title {
      margin: 0 0 5px;
      color: #183329;
      font-size: 15px;
      font-weight: 700;
      line-height: 1.45;
    }

    .cc-subtitle {
      color: #87948c;
      font-size: 12px;
      line-height: 1.5;
    }

    .chart-wrap {
      position: relative;
      width: 100%;
      height: 250px;
      min-width: 0;
    }

    .chart-wrap canvas {
      display: block;
      width: 100% !important;
      max-width: 100%;
    }

    .chart-empty-note {
      display: none;
      position: absolute;
      inset: 0;
      align-items: center;
      justify-content: center;
      padding: 18px;
      color: #718096;
      background: #fbfdfb;
      border: 1px dashed #dce7df;
      border-radius: 10px;
      text-align: center;
      font-size: 13px;
      line-height: 1.6;
    }

    .chart-empty-note.is-visible {
      display: flex;
    }

    /* =========================================
                           DASHBOARD SECTION
                        ========================================= */
    @media (max-width: 900px) {
      .chart-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .chart-card {
        min-height: 340px;
      }

      .chart-wrap {
        height: 250px;
      }
    }

    @media (max-width: 700px) {
      .greeting-banner {
        padding: 20px;
        align-items: flex-start;
      }

      .prog-bar-wrap {
        width: min(220px, 100%);
      }

      .banner-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        width: 100%;
      }

      .banner-register-btn,
      .banner-stat {
        min-width: 0;
      }

      .stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .chart-grid,
      .bottom-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .donut-wrap {
        flex-wrap: wrap;
      }

      .activity-item {
        align-items: flex-start;
        flex-wrap: wrap;
      }

      .activity-time {
        margin-left: 44px;
      }
    }

    @media (max-width: 480px) {
      .chart-card {
        min-height: 320px;
        padding: 15px;
        border-radius: 12px;
      }

      .cc-heading {
        min-height: auto;
        margin-bottom: 18px;
      }

      .chart-wrap {
        height: 230px;
      }
    }

    @media (max-width: 420px) {
      .stat-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .greeting-banner h2 {
        font-size: 21px;
      }

      .chart-card,
      .activity-card,
      .ann-card,
      .sched-card {
        padding: 14px;
      }
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

    .current-course-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 18px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
      flex-wrap: wrap;
    }

    .current-course-main {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      min-width: 0;
      flex: 1;
    }

    .current-course-icon {
      width: 44px;
      height: 44px;
      flex: 0 0 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      background: #e6f4eb;
      color: #1a4d2e;
      font-size: 19px;
    }

    .current-course-title {
      color: #1a4d2e;
      font-size: 16px;
      font-weight: 700;
      line-height: 1.4;
      overflow-wrap: anywhere;
    }

    .current-course-meta {
      color: #718096;
      font-size: 12px;
      margin-top: 5px;
      line-height: 1.6;
    }

    .course-progress-track {
      height: 7px;
      width: min(240px, 100%);
      margin-top: 10px;
      overflow: hidden;
      border-radius: 999px;
      background: #e8eee9;
    }

    .course-progress-fill {
      height: 100%;
      border-radius: inherit;
      background: #f5c842;
    }

    .current-course-status {
      background: #e6f4eb;
      color: #276749;
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 11px;
      font-weight: 700;
      white-space: nowrap;
    }

    .chart-empty-note {
      display: none;
      position: absolute;
      inset: 0;
      align-items: center;
      justify-content: center;
      color: #718096;
      text-align: center;
      font-size: 12px;
      padding: 16px;
      line-height: 1.5;
    }

    .chart-empty-note.is-visible {
      display: flex;
    }

    @media (max-width: 700px) {
      .greeting-banner {
        padding: 20px;
        align-items: flex-start;
      }

      .prog-bar-wrap {
        width: min(220px, 100%);
      }

      .banner-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        width: 100%;
      }

      .banner-register-btn,
      .banner-stat {
        min-width: 0;
      }

      .stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .chart-grid,
      .bottom-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .donut-wrap {
        flex-wrap: wrap;
      }

      .activity-item {
        align-items: flex-start;
        flex-wrap: wrap;
      }

      .activity-time {
        margin-left: 44px;
      }
    }

    @media (max-width: 420px) {
      .stat-grid {
        grid-template-columns: minmax(0, 1fr);
      }

      .greeting-banner h2 {
        font-size: 21px;
      }

      .chart-card,
      .activity-card,
      .ann-card,
      .sched-card {
        padding: 14px;
      }
    }

    /* TRAINING FACILITY CARD */
    .location-card {
      background: #ffffff;
      border: 1px solid #e2ebe5;
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 24px;
      box-shadow: 0 4px 16px rgba(24, 51, 41, 0.04);
    }

    .location-map {
      height: auto;
      min-height: 0;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      padding: 0;
      color: #183329;
      font-size: 13px;
      gap: 0;
    }

    .facility-map-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      padding: 18px 20px;
      background: #ffffff;
      border-bottom: 1px solid #edf2ee;
    }

    .facility-map-header i {
      color: #075b35;
      font-size: 16px;
    }

    .facility-map-header span {
      color: #183329;
      font-size: 14px;
      font-weight: 700;
    }

    .training-facility-map {
      display: block;
      width: 100%;
      height: 300px;
      min-height: 300px;
      background: #eaf5ee;
      z-index: 1;
    }

    .facility-directions {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 9px;
      margin: 14px 18px;
      padding: 12px 18px;
      border: 1px solid #dce9df;
      border-radius: 10px;
      background: #eaf5ee;
      color: #075b35;
      text-decoration: none;
      font-size: 13px;
      font-weight: 700;
      transition: background 0.2s ease, transform 0.2s ease;
    }

    .facility-directions:hover {
      background: #dcefe2;
      color: #043f26;
      transform: translateY(-1px);
    }

    .location-info {
      padding: 16px 20px 20px;
      border-top: 1px solid #edf2ee;
    }

    .location-info .li-title {
      font-size: 14px;
      font-weight: 700;
      color: #183329;
      margin-bottom: 7px;
    }

    .location-info .li-addr {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      color: #718096;
      font-size: 12px;
      line-height: 1.6;
    }

    @media (max-width: 600px) {
      .training-facility-map {
        height: 240px;
        min-height: 240px;
      }

      .facility-map-header {
        padding: 15px;
      }

      .facility-directions {
        margin: 12px;
      }

      .location-info {
        padding: 14px 15px 17px;
      }
    }
  </style>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

    {{-- ── Continue Learning / Current Course ───────────────────────── --}}

    <p class="section-heading">Continue Learning</p>

    <div class="current-course-card">

      @if ($enrollment && $enrollment->course)
        @php

          $courseProgress = (int) ($enrollment->calculated_progress ?? 0);

          $courseProgress = max(0, min(100, $courseProgress));

        @endphp

        <div class="current-course-main">

          <div class="current-course-icon" aria-hidden="true">

            <i class="fa fa-book-open"></i>

          </div>

          <div style="min-width:0;flex:1;">

            <div class="current-course-title">{{ $enrollment->course->title }}

            </div>

            <div class="current-course-meta">

              <span><i class="fa fa-clock" aria-hidden="true"></i>

                {{ $enrollment->course->duration ?? 'Duration not specified' }}</span>

              <span style="margin: 0 6px;">·</span>

              <span><i class="fa fa-location-dot" aria-hidden="true"></i>

                {{ $enrollment->course->facility->name ?? 'No facility assigned' }}</span>

            </div>

            <div class="course-progress-track" role="progressbar"
              aria-label="Selected course progress" aria-valuemin="0"
              aria-valuemax="100"
              aria-valuenow="{{ min(100, max(0, (float) $courseProgress)) }}">

              <div class="course-progress-fill"
                style="width: {{ min(100, max(0, (float) $courseProgress)) }}%;">

              </div>

            </div>

            <div class="current-course-meta">

              {{ min(100, max(0, (float) $courseProgress)) }}% course progress

              · Continue from the My Courses page.

            </div>

          </div>

        </div>

        <span class="current-course-status">

          {{ ucfirst($enrollment->status ?? 'Enrolled') }}

        </span>
      @else
        <div class="current-course-main">

          <div class="current-course-icon" aria-hidden="true">

            <i class="fa fa-book-open"></i>

          </div>

          <div>

            <div class="current-course-title">No active course yet</div>

            <div class="current-course-meta">

              Your enrolled course will appear here once your registration is

              active.

            </div>

          </div>

        </div>
      @endif

    </div>

    {{-- ── Personal Analytics Charts ───────────────────────────────────── --}}

    <p class="section-heading">Learning Summary</p>

    <div class="chart-grid">

      <div class="chart-card">

        <div class="cc-heading">

          <div class="cc-title">1. Quiz Performance Trend</div>

          <div class="cc-subtitle">Your quiz scores over time</div>

        </div>

        <div class="chart-wrap">

          <canvas id="quizTrendChart" role="img"
            aria-label="Line chart showing quiz scores over time">Quiz performance

            trend chart.</canvas>

          <div id="quizTrendEmpty" class="chart-empty-note">No quiz score history

            is available yet.</div>

        </div>

      </div>

      <div class="chart-card">

        <div class="cc-heading">

          <div class="cc-title">2. Learning Progress by Unit</div>

          <div class="cc-subtitle">Completion percentage for each unit</div>

        </div>

        <div class="chart-wrap">

          <canvas id="unitProgressChart" role="img"
            aria-label="Bar chart showing learning progress by unit">Learning

            progress by unit chart.</canvas>

          <div id="unitProgressEmpty" class="chart-empty-note">Unit progress will

            appear when learning progress is recorded.</div>

        </div>

      </div>

      <div class="chart-card">

        <div class="cc-heading">

          <div class="cc-title">3. Quiz vs. Onsite Performance</div>

          <div class="cc-subtitle">Compare average results by assessment type

          </div>

        </div>

        <div class="chart-wrap">

          <canvas id="performanceCompareChart" role="img"
            aria-label="Bar chart comparing quiz and onsite performance percentages">Quiz

            and onsite performance comparison chart.</canvas>

          <div id="performanceCompareEmpty" class="chart-empty-note">Performance

            comparison will appear when assessment results are available.</div>

        </div>

      </div>

      <div class="chart-card">

        <div class="cc-heading">

          <div class="cc-title">4. Assessment Results Summary</div>

          <div class="cc-subtitle">Passed and failed assessment attempts</div>

        </div>

        <div class="chart-wrap">

          <canvas id="assessmentSummaryChart" role="img"
            aria-label="Bar chart summarizing passed and failed assessments">Assessment

            results summary chart.</canvas>

          <div id="assessmentSummaryEmpty" class="chart-empty-note">Assessment

            results will appear after you complete a quiz or onsite evaluation.

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

            <svg width="14" height="14" fill="none"
              viewBox="0 0 16 16">

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

    {{-- ── Announcements + Course Schedule ────────────────────────────── --}}

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

          Course Schedule

        </p>

        <div class="sched-card">

          @if ($enrollment && $enrollment->course)
            <div class="sched-item">

              <span class="sched-badge">

                {{ filled($enrollment->course->schedule) ? $enrollment->course->schedule : 'Schedule not specified in the database' }}

              </span>

              <div>

                <div class="sched-title">{{ $enrollment->course->title }}</div>

                <div class="sched-loc">

                  <i class="fa fa-location-dot"
                    style="color:#e05c6e;font-size:11px;"></i>

                  {{ $enrollment->course->facility->name ?? 'No facility assigned' }}

                </div>

              </div>

            </div>
          @else
            <div class="no-data">No schedule found.</div>
          @endif

        </div>

      </div>

    </div>

    {{-- ── Training Facility ────────────────────────────────────────────── --}}

    <p class="section-heading">

      <i class="fa fa-building" style="color:#1a4d2e;margin-right:6px;"
        aria-hidden="true"></i>

      Training Facility

    </p>

    <div class="location-map">
      @if ($enrollment && $enrollment->course && $enrollment->course->facility)
        <div class="facility-map-header">
          <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
          <span>{{ $enrollment->course->facility->name }}</span>
        </div>

        <div id="trainingFacilityMap" class="training-facility-map"
          data-address="{{ $enrollment->course->facility->address ?? '' }}">
        </div>

        <a class="facility-directions"
          href="https://www.google.com/maps/dir/?api=1&destination=14.3218361,120.9669115"
          target="_blank" rel="noopener noreferrer">
          <i class="fa fa-directions" aria-hidden="true"></i>
          Get Directions
        </a>
      @else
        <i class="fa fa-building" style="font-size:20px;"
          aria-hidden="true"></i>
        <span>No facility assigned</span>
      @endif
    </div>

  </div>

  </div>

@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js">
  </script>
  <script>
    (function() {
      function initStudentCharts() {
        if (typeof Chart === 'undefined') {
          console.error('Chart.js failed to load.');
          return;
        }

        const colors = {
          green: '#075b35',
          greenLight: 'rgba(7, 91, 53, 0.10)',
          yellow: '#e1dc36',
          red: '#dc4c4c',
          muted: '#718096',
          grid: '#e8eee9'
        };

        const commonOptions = {
          responsive: true,
          maintainAspectRatio: false,
          animation: {
            duration: 600
          },
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: {
              position: 'top',
              labels: {
                color: colors.muted,
                usePointStyle: true,
                pointStyle: 'circle',
                boxWidth: 9,
                padding: 16,
                font: {
                  size: 11
                }
              }
            },
            tooltip: {
              backgroundColor: '#183329',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 10,
              cornerRadius: 8
            }
          }
        };

        const percentAxis = () => ({
          beginAtZero: true,
          min: 0,
          max: 100,
          grid: {
            color: colors.grid
          },
          border: {
            display: false
          },
          ticks: {
            stepSize: 25,
            color: colors.muted,
            font: {
              size: 11
            },
            callback: value => value + '%'
          }
        });

        const categoryAxis = () => ({
          grid: {
            display: false
          },
          border: {
            display: false
          },
          ticks: {
            color: colors.muted,
            font: {
              size: 11
            }
          }
        });

        function hasNumericValue(values) {
          return Array.isArray(values) && values.some(value =>
            value !== null && value !== '' && value !== undefined &&
            Number.isFinite(Number(value))
          );
        }

        function makeChart(canvasId, emptyId, config, hasData, emptyMessage) {
          const canvas = document.getElementById(canvasId);
          const empty = document.getElementById(emptyId);
          if (!canvas || !empty) {
            console.warn('Missing chart element:', canvasId, emptyId);
            return;
          }

          const previous = Chart.getChart(canvas);
          if (previous) previous.destroy();

          if (!hasData) {
            canvas.style.display = 'none';
            empty.textContent = emptyMessage;
            empty.classList.add('is-visible');
            return;
          }

          canvas.style.display = 'block';
          empty.classList.remove('is-visible');

          try {
            new Chart(canvas, config);
          } catch (error) {
            console.error('Could not render chart:', canvasId, error);
            canvas.style.display = 'none';
            empty.textContent =
              'This chart could not be displayed. Please refresh the page.';
            empty.classList.add('is-visible');
          }
        }

        // 1. Quiz Performance Trend
        const trendLabels = @json($quizTrendLabels ?? []);
        const trendScores = @json($quizTrendScores ?? []);
        const validTrend = Array.isArray(trendLabels) &&
          Array.isArray(trendScores) &&
          trendLabels.length > 0 &&
          trendLabels.length === trendScores.length &&
          hasNumericValue(trendScores);

        makeChart('quizTrendChart', 'quizTrendEmpty', {
            type: 'line',
            data: {
              labels: trendLabels,
              datasets: [{
                label: 'Quiz score',
                data: trendScores,
                borderColor: colors.green,
                backgroundColor: colors.greenLight,
                pointBackgroundColor: colors.yellow,
                pointBorderColor: colors.green,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                spanGaps: false
              }]
            },
            options: {
              ...commonOptions,
              scales: {
                x: {
                  ...categoryAxis(),
                  ticks: {
                    color: colors.muted,
                    autoSkip: true,
                    maxTicksLimit: 6,
                    maxRotation: 0,
                    font: {
                      size: 11
                    }
                  }
                },
                y: percentAxis()
              },
              plugins: {
                ...commonOptions.plugins,
                legend: {
                  display: false
                },
                tooltip: {
                  ...commonOptions.plugins.tooltip,
                  callbacks: {
                    label: context => ` Score: ${context.parsed.y}%`
                  }
                }
              }
            }
          }, validTrend,
          'Your quiz scores will appear here after you complete quizzes.');

        // 2. Learning Progress by Unit
        const unitLabels = @json($unitProgressLabels ?? []);
        const unitValues = @json($unitProgressValues ?? []);
        const validUnits = Array.isArray(unitLabels) &&
          Array.isArray(unitValues) &&
          unitLabels.length > 0 &&
          unitLabels.length === unitValues.length &&
          hasNumericValue(unitValues);

        makeChart('unitProgressChart', 'unitProgressEmpty', {
            type: 'bar',
            data: {
              labels: unitLabels,
              datasets: [{
                label: 'Completion',
                data: unitValues,
                backgroundColor: colors.green,
                hoverBackgroundColor: '#0b7546',
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 34
              }]
            },
            options: {
              ...commonOptions,
              indexAxis: 'y',
              scales: {
                x: percentAxis(),
                y: categoryAxis()
              },
              plugins: {
                ...commonOptions.plugins,
                legend: {
                  display: false
                },
                tooltip: {
                  ...commonOptions.plugins.tooltip,
                  callbacks: {
                    label: context => ` Completion: ${context.parsed.x}%`
                  }
                }
              }
            }
          }, validUnits,
          'Unit progress will appear as you complete course modules.');

        // 3. Quiz vs. Onsite Performance
        const comparisonLabels = @json($comparisonLabels ?? ['Overall']);
        const quizAverages = @json($quizAverages ?? []);
        const onsiteAverages = @json($onsiteAverages ?? []);
        const validComparison = Array.isArray(comparisonLabels) &&
          Array.isArray(quizAverages) &&
          Array.isArray(onsiteAverages) &&
          comparisonLabels.length > 0 &&
          comparisonLabels.length === quizAverages.length &&
          comparisonLabels.length === onsiteAverages.length &&
          (hasNumericValue(quizAverages) || hasNumericValue(onsiteAverages));

        makeChart('performanceCompareChart', 'performanceCompareEmpty', {
            type: 'bar',
            data: {
              labels: comparisonLabels,
              datasets: [{
                  label: 'Quiz performance',
                  data: quizAverages,
                  backgroundColor: colors.green,
                  borderRadius: 5,
                  borderSkipped: false,
                  maxBarThickness: 42
                },
                {
                  label: 'Onsite performance',
                  data: onsiteAverages,
                  backgroundColor: colors.yellow,
                  borderRadius: 5,
                  borderSkipped: false,
                  maxBarThickness: 42
                }
              ]
            },
            options: {
              ...commonOptions,
              scales: {
                x: categoryAxis(),
                y: percentAxis()
              },
              plugins: {
                ...commonOptions.plugins,
                tooltip: {
                  ...commonOptions.plugins.tooltip,
                  callbacks: {
                    label: context => context.parsed.y === null ?
                      ` ${context.dataset.label}: No data` :
                      ` ${context.dataset.label}: ${context.parsed.y}%`
                  }
                }
              }
            }
          }, validComparison,
          'Quiz and onsite results will appear when assessment scores are available.'
        );

        // 4. Assessment Results Summary — counts, not percentages
        const passedCount = Number(@json($assessmentPassed ?? 0));
        const failedCount = Number(@json($assessmentFailed ?? 0));
        const validAssessment = Number.isFinite(passedCount) &&
          Number.isFinite(failedCount) &&
          (passedCount > 0 || failedCount > 0);

        makeChart('assessmentSummaryChart', 'assessmentSummaryEmpty', {
            type: 'bar',
            data: {
              labels: ['Passed', 'Failed'],
              datasets: [{
                label: 'Assessment attempts',
                data: [passedCount, failedCount],
                backgroundColor: [colors.green, colors.red],
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 78
              }]
            },
            options: {
              ...commonOptions,
              scales: {
                x: categoryAxis(),
                y: {
                  beginAtZero: true,
                  min: 0,
                  suggestedMax: Math.max(4, Math.ceil(Math.max(passedCount,
                    failedCount) * 1.2)),
                  grid: {
                    color: colors.grid
                  },
                  border: {
                    display: false
                  },
                  ticks: {
                    precision: 0,
                    stepSize: 1,
                    color: colors.muted,
                    font: {
                      size: 11
                    }
                  }
                }
              },
              plugins: {
                ...commonOptions.plugins,
                legend: {
                  display: false
                },
                tooltip: {
                  ...commonOptions.plugins.tooltip,
                  callbacks: {
                    label: context =>
                      ` Assessment attempts: ${context.parsed.y}`
                  }
                }
              }
            }
          }, validAssessment,
          'Assessment results will appear after your assessment records are available.'
        );
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStudentCharts);
      } else {
        initStudentCharts();
      }
    })();
  </script>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mapElement = document.getElementById('trainingFacilityMap');

      if (!mapElement || typeof L === 'undefined') return;

      const latitude = 14.3218361;
      const longitude = 120.9669115;

      const map = L.map(mapElement).setView(
        [latitude, longitude],
        16
      );

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
      }).addTo(map);

      L.marker([latitude, longitude])
        .addTo(map)
        .bindPopup('<strong>Dasmariñas City Training Center Main</strong>')
        .openPopup();

      setTimeout(function() {
        map.invalidateSize();
      }, 200);
    });
  </script>

@endsection

