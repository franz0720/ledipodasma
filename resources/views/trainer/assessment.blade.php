@extends('trainer.layout')
@section('title', 'Assessment')
@section('css')
  <style>
    :root {
      --as-green: #075b35;
      --as-green-dark: #043f26;
      --as-green-soft: #eaf5ee;
      --as-yellow: #e1dc36;
      --as-ink: #183329;
      --as-muted: #6b7c73;
      --as-border: #e2ebe5;
      --as-surface: #fff;
      --as-red: #a32d2d
    }
    .as-wrap {
      max-width: 1440px;
      margin: 0 auto;
      padding: clamp(16px, 2.4vw, 32px);
      color: var(--as-ink)
    }
    .as-page-title {
      font-size: clamp(24px, 2vw, 30px);
      font-weight: 850;
      letter-spacing: -.035em;
      color: var(--as-green);
      margin: 0 0 6px
    }
    .as-page-sub {
      font-size: 13px;
      line-height: 1.6;
      color: var(--as-muted);
      margin: 0 0 24px
    }
    .as-stats {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 10px;
      margin: 0 0 22px;
      align-items: stretch;
    }
    .as-stats-group {
      margin: 0 0 22px;
    }
    .as-stats-heading {
      margin: 0 0 10px;
    }
    .as-stats-heading h2 {
      margin: 0;
      color: var(--as-green);
      font-size: 14px;
      font-weight: 850;
      letter-spacing: -.01em;
    }
    .as-stats-heading p {
      margin: 4px 0 0;
      color: var(--as-muted);
      font-size: 11px;
      line-height: 1.5;
    }
    .as-stat {
      min-width: 0;
      background: #fff;
      border: 1px solid var(--as-border);
      border-radius: 12px;
      padding: 14px 16px;
      position: relative;
      box-shadow: none;
      transition: border-color .15s ease;
    }
    .as-stat:hover {
      transform: none;
      box-shadow: none;
      border-color: #bfd7c7;
    }
    .as-stat:before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 3px;
      background: var(--as-green);
      border-radius: 12px 0 0 12px;
    }
    .as-stat.urgent:before {
      background: var(--as-yellow);
    }
    .as-stat-val {
      font-size: clamp(21px, 1.6vw, 25px);
      line-height: 1.2;
      font-weight: 800;
      color: var(--as-green);
      margin: 0 0 5px;
      letter-spacing: -.025em;
    }
    .as-stat.urgent .as-stat-val {
      color: var(--as-green);
    }
    .as-stat-label {
      font-size: 11px;
      line-height: 1.4;
      color: var(--as-muted);
      font-weight: 650;
    }
    .as-stat-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 8px;
      padding: 0;
      border: 0;
      background: none;
      color: var(--as-green);
      font-size: 11px;
      font-weight: 750;
      cursor: pointer;
    }
    .as-stat-link:hover {
      text-decoration: underline;
    }
    .as-filters {
      display: grid;
      grid-template-columns: minmax(230px, 1.55fr) minmax(190px, 1fr) minmax(190px, 1.15fr) minmax(150px, .9fr) minmax(150px, .9fr) auto;
      gap: 12px;
      align-items: end;
      padding: 18px;
      background: #fff;
      border: 1px solid var(--as-border);
      border-radius: 18px;
      margin-bottom: 18px;
      box-shadow: 0 4px 18px rgba(20, 65, 40, .035)
    }
    .as-filter-field {
      display: flex;
      flex-direction: column;
      gap: 8px;
      min-width: 0
    }
    .as-filter-label {
      display: block;
      color: #536b5d;
      font-size: 11px;
      line-height: 1.2;
      font-weight: 800;
      letter-spacing: .045em
    }
    .as-filter-hint {
      grid-column: 1 / -1;
      margin: 0;
      padding-top: 12px;
      border-top: 1px solid #edf3ee;
      color: var(--as-muted);
      font-size: 11px;
      line-height: 1.5
    }
    .as-filter-type {
      display: flex;
      align-items: center;
      gap: 4px;
      min-height: 44px;
      padding: 4px;
      border: 1px solid #dce7df;
      border-radius: 11px;
      background: #f5f9f6
    }
    .as-filter-type button {
      appearance: none;
      flex: 1 1 0;
      min-width: 0;
      min-height: 34px;
      padding: 8px 10px;
      border: 1px solid transparent;
      border-radius: 8px;
      background: transparent;
      color: #587064;
      font-family: inherit;
      font-size: 12px;
      line-height: 1.2;
      font-weight: 750;
      cursor: pointer;
      transition: background .15s, color .15s, box-shadow .15s
    }
    .as-filter-type button:hover {
      background: #e8f2eb;
      color: var(--as-green)
    }
    .as-filter-type button.active {
      background: var(--as-green);
      color: #fff;
      box-shadow: 0 2px 7px rgba(7, 91, 53, .18)
    }
    .as-filter-type button:focus-visible {
      outline: 2px solid var(--as-green);
      outline-offset: 2px
    }
    .as-search-wrap {
      position: relative;
      display: block;
      min-width: 0
    }
    .as-search-wrap i {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #8a9a90;
      font-size: 13px
    }
    .as-filter {
      width: 100%;
      min-width: 0;
      padding: 11px 12px;
      border: 1px solid #dce7df;
      border-radius: 10px;
      font: inherit;
      font-size: 12px;
      color: #26382e;
      background: #fff;
      outline: none;
      transition: border-color .15s, box-shadow .15s
    }
    .as-search {
      padding-left: 36px
    }
    .as-filter:focus {
      border-color: var(--as-green);
      box-shadow: 0 0 0 3px rgba(7, 91, 53, .1)
    }
    .as-reset {
      padding: 11px 13px;
      border: 1px solid #dce7df;
      border-radius: 10px;
      background: #fff;
      color: var(--as-green);
      font-size: 12px;
      font-weight: 750;
      white-space: nowrap;
      cursor: pointer
    }
    .as-reset:hover {
      background: var(--as-green-soft);
      border-color: #b9d5c3
    }
    .as-filter-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      margin: -4px 2px 14px;
      color: var(--as-muted);
      font-size: 12px
    }
    .as-filter-summary strong {
      color: var(--as-green)
    }
    .as-table-card {
      background: #fff;
      border: 1px solid var(--as-border);
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 20px;
      box-shadow: 0 5px 22px rgba(20, 65, 40, .04)
    }
    .as-section-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 18px 20px;
      border-bottom: 1px solid var(--as-border);
      background: linear-gradient(180deg, #fff, #fcfefc)
    }
    .as-section-head h3 {
      color: var(--as-green);
      font-size: 15px;
      margin: 0;
      font-weight: 850;
      letter-spacing: -.01em
    }
    .as-section-head p {
      color: var(--as-muted);
      font-size: 12px;
      margin: 5px 0 0
    }
    .as-count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 32px;
      padding: 6px 10px;
      border-radius: 999px;
      background: var(--as-green-soft);
      color: var(--as-green);
      font-size: 11px;
      font-weight: 800
    }
    .as-table-scroll {
      overflow-x: auto
    }
    .as-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      min-width: 780px
    }
    .as-table thead tr {
      background: #f4f9f5
    }
    .as-table th {
      padding: 13px 15px;
      font-size: 10px;
      font-weight: 850;
      color: #537261;
      text-align: left;
      text-transform: uppercase;
      letter-spacing: .07em;
      white-space: nowrap;
      border-bottom: 1px solid var(--as-border)
    }
    .as-table td {
      padding: 14px 15px;
      font-size: 12px;
      border-bottom: 1px solid #edf3ee;
      color: #344239;
      vertical-align: middle
    }
    .as-table tbody tr:last-child td {
      border-bottom: 0
    }
    .as-table tbody tr {
      transition: background .15s
    }
    .as-table tbody tr:hover {
      background: #f9fcfa
    }
    .as-table td strong {
      font-weight: 750;
      color: #23372b
    }
    .as-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 9px;
      border-radius: 999px;
      font-size: 10px;
      font-weight: 800;
      white-space: nowrap
    }
    .as-badge.complete,
    .as-badge.competent {
      background: #e7f5eb;
      color: #12623a
    }
    .as-badge.incomplete {
      background: #fff0ef;
      color: #a32d2d
    }
    .as-badge.not-competent {
      background: #fff5d9;
      color: #80550a
    }
    .as-actions {
      display: flex;
      gap: 7px;
      align-items: center
    }
    .as-icon {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #d8e9de;
      cursor: pointer;
      transition: all .15s
    }
    .as-icon.view {
      background: var(--as-green-soft);
      color: var(--as-green)
    }
    .as-icon:hover {
      background: var(--as-green);
      color: #fff;
      border-color: var(--as-green)
    }
    .as-btn {
      padding: 10px 15px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: 750;
      cursor: pointer;
      border: 1px solid var(--as-green);
      font-family: inherit;
      transition: all .15s
    }
    .as-btn.primary {
      background: var(--as-green);
      color: #fff
    }
    .as-btn.primary:hover {
      background: var(--as-green-dark)
    }
    .as-btn.outline {
      background: #fff;
      color: var(--as-green)
    }
    .as-btn.outline:hover {
      background: var(--as-green-soft)
    }
    .as-bottom {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin: 4px 0 22px
    }
    .as-empty {
      text-align: center;
      padding: 34px 18px;
      color: #87958c;
      font-size: 12px
    }
    .as-empty i {
      display: block;
      color: var(--as-green);
      opacity: .5;
      font-size: 26px;
      margin-bottom: 10px
    }
    .as-modal {
      display: none;
      position: fixed;
      z-index: 3000;
      inset: 0;
      background: rgba(7, 30, 17, .54);
      backdrop-filter: blur(4px);
      align-items: center;
      justify-content: center;
      padding: 18px
    }
    .as-modal.open {
      display: flex
    }
    .as-modal-content {
      width: min(560px, 100%);
      max-height: 90vh;
      overflow: auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 24px 70px rgba(0, 0, 0, .22);
      animation: asModalIn .18s ease-out
    }
    @keyframes asModalIn {
      from {
        opacity: 0;
        transform: translateY(8px) scale(.99)
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1)
      }
    }
    .as-modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 18px 22px;
      background: #f6fbf7;
      border-bottom: 1px solid #e7efe9
    }
    .as-modal-header h3 {
      margin: 0;
      color: var(--as-green);
      font-size: 15px;
      font-weight: 850
    }
    .as-modal-close {
      border: 0;
      background: transparent;
      color: #788078;
      font-size: 26px;
      line-height: 1;
      cursor: pointer
    }
    .as-modal-body {
      padding: 20px 22px
    }
    .as-modal-footer {
      padding: 14px 22px;
      display: flex;
      justify-content: flex-end;
      border-top: 1px solid #edf2ee;
      background: #fbfdfb
    }
    .as-detail-row {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      padding: 12px 0;
      border-bottom: 1px solid #eef3ef;
      font-size: 12px
    }
    .as-detail-row:last-child {
      border-bottom: 0
    }
    .as-detail-label {
      color: var(--as-muted)
    }
    .as-detail-val {
      color: #1f3025;
      font-weight: 750;
      text-align: right;
      overflow-wrap: anywhere
    }
    .as-top-table {
      width: 100%;
      border-collapse: collapse
    }
    .as-top-table th,
    .as-top-table td {
      text-align: left;
      padding: 12px 14px;
      font-size: 12px;
      border-bottom: 1px solid #eef3ef
    }
    .as-top-table th {
      color: var(--as-green);
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .06em;
      background: var(--as-green-soft)
    }
    .as-top-table tr:last-child td {
      border-bottom: 0
    }
    @media(max-width:1100px) {
      .as-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
      .as-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr))
      }
      .as-filter-field.search-field,
      .as-filter-hint {
        grid-column: 1 / -1
      }
      .as-reset {
        justify-self: start
      }
    }
    @media(max-width:600px) {
      .as-wrap {
        padding: 14px
      }
      .as-stats {
        gap: 10px
      }
      .as-stat {
        padding: 13px;
        border-radius: 12px
      }
      .as-stat-val {
        font-size: 23px
      }
      .as-filters {
        grid-template-columns: 1fr;
        padding: 12px
      }
      .as-filter-field.search-field,
      .as-filter-hint {
        grid-column: auto
      }
      .as-filter-type button {
        padding: 8px 6px
      }
      .as-section-head {
        padding: 15px
      }
      .as-table-card {
        border-radius: 13px
      }
      .as-filter-summary {
        align-items: flex-start;
        flex-direction: column
      }
      .as-bottom {
        justify-content: stretch
      }
      .as-bottom .as-btn {
        width: 100%
      }
      .as-modal-body {
        padding: 16px
      }
    }
  
    /* Responsive safeguards for the assessment page */
    .as-wrap,
    .as-wrap * { box-sizing: border-box; }
    .as-wrap { width: 100%; min-width: 0; }
    .as-stats, .as-filters, .as-table-card, .as-section-head,
    .as-table-scroll, .as-modal-content { min-width: 0; }
    .as-table-scroll {
      max-width: 100%;
      overflow-x: auto;
      overflow-y: hidden;
      -webkit-overflow-scrolling: touch;
      overscroll-behavior-x: contain;
    }
    .as-table { min-width: 780px; }
    .as-top-table { min-width: 420px; }
    .as-filter, .as-filter-type, .as-reset, .as-btn { min-height: 42px; }
    .as-section-head > div:first-child { min-width: 0; }
    .as-section-head h3, .as-section-head p,
    .as-page-title, .as-page-sub { overflow-wrap: anywhere; }
    .as-modal { padding: clamp(8px, 2vw, 18px); }
    .as-modal-content {
      max-height: min(90vh, 900px);
      max-height: min(90dvh, 900px);
      overflow-x: hidden;
      overflow-y: auto;
      overscroll-behavior: contain;
    }
    .as-modal-header, .as-modal-footer { flex-wrap: wrap; }
    .as-modal-header { padding: clamp(14px, 2.5vw, 18px) clamp(14px, 3vw, 22px); }
    .as-modal-body { padding: clamp(14px, 3vw, 22px); }
    .as-detail-row { align-items: flex-start; }
    .as-detail-label { flex: 0 0 38%; }
    .as-detail-val { min-width: 0; flex: 1 1 auto; }
    .as-icon { flex: 0 0 34px; }
    @media (max-width: 1100px) {
      .as-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
      .as-filter-field.search-field, .as-filter-hint { grid-column: 1 / -1; }
      .as-reset { justify-self: start; }
    }
    @media (max-width: 768px) {
      .as-wrap { padding: 16px; }
      .as-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .as-section-head { align-items: flex-start; padding: 15px; }
      .as-table-card { border-radius: 12px; }
      .as-table th, .as-table td { padding: 11px 12px; }
      .as-filter-summary { align-items: flex-start; flex-direction: column; gap: 6px; }
      .as-bottom { justify-content: stretch; }
      .as-bottom .as-btn { flex: 1 1 100%; }
      .as-modal-content { width: min(560px, 100%); max-height: 92vh; max-height: 92dvh; }
    }
    @media (max-width: 480px) {
      .as-wrap { padding: 12px; }
      .as-page-title { font-size: 25px; }
      .as-page-sub { margin-bottom: 18px; }
      .as-stats { grid-template-columns: minmax(0, 1fr); gap: 8px; }
      .as-stat { padding: 13px 14px; }
      .as-stat-val { font-size: 22px; }
      .as-filters { grid-template-columns: minmax(0, 1fr); padding: 12px; gap: 12px; }
      .as-filter-field.search-field, .as-filter-hint { grid-column: auto; }
      .as-filter-type { width: 100%; }
      .as-filter-type button { padding: 8px 5px; font-size: 11px; }
      .as-section-head { gap: 8px; padding: 13px; }
      .as-section-head h3 { font-size: 14px; }
      .as-count { flex-shrink: 0; }
      .as-modal { padding: 8px; align-items: center; }
      .as-modal-content { border-radius: 14px; max-height: 94vh; max-height: 94dvh; }
      .as-modal-header h3 { font-size: 14px; line-height: 1.45; }
      .as-modal-footer { padding: 12px 14px; }
      .as-modal-footer .as-btn { width: 100%; }
      .as-detail-row { gap: 10px; font-size: 12px; }
      .as-detail-label { flex-basis: 34%; }
      .as-bottom { margin-bottom: 16px; }
    }
    @media (prefers-reduced-motion: reduce) {
      .as-wrap *, .as-wrap *::before, .as-wrap *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
      }
    }

  </style>
@endsection
@section('content')
  @php
    $course = $course ?? null;
    $results = $results ?? collect();
    $onsiteGrades = $onsiteGrades ?? collect();
    // Quiz KPIs and rankings must use quiz_results only.
    $topPerformers = $results
        ->groupBy(function ($result) {
            return $result->user_id ?? $result->trainee_id ?? null;
        })
        ->filter(function ($quizResults, $userId) {
            return $userId !== null && $userId !== '';
        })
        ->map(function ($quizResults) {
            $first = $quizResults->first();
            $user = $first->user ?? null;
            return (object) [
                'user_id' => $first->user_id ?? $first->trainee_id ?? null,
                'firstname' => $first->firstname ?? ($user?->firstname ?? ''),
                'lastname' => $first->lastname ?? ($user?->lastname ?? ''),
                'avg_score' => round((float) $quizResults->avg(function ($result) {
                    return (float) ($result->percentage ?? 0);
                }), 1),
            ];
        })
        ->sortByDesc('avg_score')
        ->take(5)
        ->values();
    $totalTaken = $totalTaken ?? $results->count();
    $avgScore =
        $avgScore ??
        ($results->count() > 0
            ? round(
                (float) $results->avg(function ($r) {
                    return (float) ($r->percentage ?? 0);
                }),
                1,
            )
            : 0);
    $passingRate =
        $passingRate ??
        ($results->count() > 0
            ? round(
                ($results->where('status', 'passed')->count() /
                    $results->count()) *
                    100,
                1,
            )
            : 0);
    $totalOnsiteGrades = $onsiteGrades->count();
    $avgOnsiteScore = $totalOnsiteGrades > 0
        ? round((float) $onsiteGrades->avg(function ($grade) {
            $maxScore = (float) ($grade->max_score ?? 0);
            $score = (float) ($grade->score ?? 0);
            return $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
        }), 1)
        : 0;
    $onsiteCompetencyRate = $totalOnsiteGrades > 0
        ? round(($onsiteGrades->filter(function ($grade) {
            $maxScore = (float) ($grade->max_score ?? 0);
            $score = (float) ($grade->score ?? 0);
            return $maxScore > 0 && (($score / $maxScore) * 100) >= 75;
        })->count() / $totalOnsiteGrades) * 100, 1)
        : 0;
    // Rank trainees by their average onsite activity percentage.
    $onsiteTopPerformers = $onsiteGrades
        ->groupBy(function ($grade) {
            return $grade->user_id ?? $grade->trainee_id ?? null;
        })
        ->filter(function ($grades, $userId) {
            return $userId !== null && $userId !== '';
        })
        ->map(function ($grades) {
            $first = $grades->first();
            $user = $first->user ?? null;
            $percentages = $grades->map(function ($grade) {
                $maxScore = (float) ($grade->max_score ?? 0);
                $score = (float) ($grade->score ?? 0);
                return $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
            });
            return (object) [
                'user_id' => $first->user_id ?? $first->trainee_id ?? null,
                'firstname' => $first->firstname ?? ($user?->firstname ?? ''),
                'lastname' => $first->lastname ?? ($user?->lastname ?? ''),
                'avg_score' => round((float) $percentages->avg(), 1),
            ];
        })
        ->sortByDesc('avg_score')
        ->take(5)
        ->values();
  @endphp
  <div class="as-wrap" data-enrolled-count="{{ (int) ($enrolledCount ?? $course?->enrolled_count ?? $course?->enrollments_count ?? 0) }}">
    <h1 class="as-page-title">Assessment</h1>
    <p class="as-page-sub">Review quiz submissions and onsite competency records
      for your trainees.</p>
    <section class="as-stats-group" aria-labelledby="quizStatsHeading">
      <div class="as-stats-heading">
        <h2 id="quizStatsHeading">Quiz Statistics</h2>
        <p>Summary of quiz scores and trainee performance.</p>
      </div>
      <div class="as-stats">
        <div class="as-stat">
          <div class="as-stat-val">{{ number_format((float) $avgScore, 1) }}%</div>
          <div class="as-stat-label">Average Quiz Score</div>
        </div>
        <div class="as-stat">
          <div class="as-stat-val">{{ number_format((float) $passingRate, 1) }}%</div>
          <div class="as-stat-label">Quiz Passing Rate</div>
        </div>
        <div class="as-stat urgent">
          <div class="as-stat-val">{{ $topPerformers->count() }}</div>
          <div class="as-stat-label">Top Quiz Performers</div>
          <button type="button" class="as-stat-link" onclick="openModal('topModal')">View ranking →</button>
        </div>
      </div>
    </section>
    <section class="as-stats-group" aria-labelledby="onsiteStatsHeading">
      <div class="as-stats-heading">
        <h2 id="onsiteStatsHeading">Onsite Activity Statistics</h2>
        <p>Summary of recorded onsite grades and competency.</p>
      </div>
      <div class="as-stats">
        <div class="as-stat">
          <div class="as-stat-val">{{ number_format((float) $avgOnsiteScore, 1) }}%</div>
          <div class="as-stat-label">Average Onsite Score</div>
        </div>
        <div class="as-stat">
          <div class="as-stat-val">{{ number_format((float) $onsiteCompetencyRate, 1) }}%</div>
          <div class="as-stat-label">Onsite Competency Rate</div>
        </div>
        <div class="as-stat urgent">
          <div class="as-stat-val">{{ $onsiteTopPerformers->count() }}</div>
          <div class="as-stat-label">Top Onsite Performers</div>
          <button type="button" class="as-stat-link" onclick="openModal('onsiteTopModal')">View ranking →</button>
        </div>
      </div>
    </section>
    @php
      $quizFilterOptions = $results
          ->map(function ($item) {
              return $item->quiz_title ?? ($item->quiz->title ?? null);
          })
          ->filter()
          ->unique()
          ->sort()
          ->values();
      $activityFilterOptions = $onsiteGrades
          ->pluck('activity_title')
          ->filter()
          ->unique()
          ->sort()
          ->values();
    @endphp
    <div class="as-filters" role="search" aria-label="Filter assessment records">
      <label class="as-filter-field search-field">
        <span class="as-filter-label">Search trainee or assessment</span>
        <span class="as-search-wrap">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input class="as-filter as-search" type="search" id="filterSearch"
            placeholder="Name, trainee ID, quiz, or activity..."
            aria-label="Search trainee or assessment" oninput="applyFilters()">
        </span>
      </label>
      <div class="as-filter-field">
        <span class="as-filter-label">Assessment type</span>
        <div class="as-filter-type" role="group" aria-label="Assessment type">
          <button type="button" class="active" data-filter-type="all"
            aria-pressed="true"
            onclick="setAssessmentType('all', this)">All</button>
          <button type="button" data-filter-type="quiz" aria-pressed="false"
            onclick="setAssessmentType('quiz', this)">Quizzes</button>
          <button type="button" data-filter-type="onsite" aria-pressed="false"
            onclick="setAssessmentType('onsite', this)">Onsite</button>
        </div>
      </div>
      <label class="as-filter-field">
        <span class="as-filter-label">Specific assessment</span>
        <select class="as-filter" id="filterAssessment" onchange="applyFilters()"
          aria-label="Filter by specific assessment">
          <option value="">All assessments</option>
          <optgroup label="Quizzes">
            @foreach ($quizFilterOptions as $quizOption)
              <option value="quiz::{{ $quizOption }}">{{ $quizOption }}
              </option>
            @endforeach
          </optgroup>
          <optgroup label="Onsite activities">
            @foreach ($activityFilterOptions as $activityOption)
              <option value="onsite::{{ $activityOption }}">{{ $activityOption }}
              </option>
            @endforeach
          </optgroup>
        </select>
      </label>
      <label class="as-filter-field">
        <span class="as-filter-label">Score range</span>
        <select class="as-filter" id="filterScore" onchange="applyFilters()"
          aria-label="Filter by score range">
          <option value="">Any score</option>
          <option value="below75">Below 75%</option>
          <option value="75plus">75% and above</option>
        </select>
      </label>
      <label class="as-filter-field" id="quizResultField">
        <span class="as-filter-label">Quiz result</span>
        <select class="as-filter" id="filterResult" onchange="applyFilters()"
          aria-label="Filter quiz result">
          <option value="">All results</option>
          <option value="passed">Passed</option>
          <option value="failed">Failed</option>
        </select>
      </label>
      <button type="button" class="as-reset" onclick="resetFilters()">
        <i class="fa fa-rotate-left" aria-hidden="true"></i> Reset
      </button>
      <p class="as-filter-hint">
        Filters work together. Search and score range apply to both tables;
        specific assessment and quiz result narrow the matching records.
      </p>
    </div>
    <div class="as-filter-summary"><span>Showing <strong
          id="visibleQuizCount">0</strong> quiz results and <strong
          id="visibleOnsiteCount">0</strong> onsite grades</span><span
        id="filterSummaryHint">Search and score filters apply to both tables; quiz
        result applies only to quizzes.</span></div>
    <section class="as-table-card">
      <div class="as-section-head">
        <div>
          <h3>Quiz Results</h3>
          <p>Submission scores saved in the quiz_results table.</p>
        </div>
      </div>
      <div class="as-table-scroll">
        <table class="as-table" id="assessmentTable">
          <thead>
            <tr>
              <th>Trainee ID</th>
              <th>Full Name</th>
              <th>Course</th>
              <th>Quiz</th>
              <th>Score</th>
              <th>Result</th>
              <th>Submitted</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($results as $result)
              @php
                $percentage = (float) ($result->percentage ?? 0);
                $passed =
                    strtolower((string) ($result->status ?? '')) === 'passed';
                $trainee = $result->user ?? null;
                $firstName = trim(
                    (string) ($result->firstname ??
                        ($trainee?->firstname ?? '')),
                );
                $lastName = trim(
                    (string) ($result->lastname ?? ($trainee?->lastname ?? '')),
                );
                $name = trim($lastName . ', ' . $firstName, ', ');
                $traineeId =
                    $result->trainee_id ??
                    ($trainee?->id ?? ($result->user_id ?? '—'));
                $quizTitle =
                    $result->quiz_title ?? ($result->quiz?->title ?? 'Quiz');
                $courseTitle =
                    $result->course_title ??
                    ($result->quiz?->course?->title ?? ($course->title ?? '—'));
              @endphp
              <tr data-assessment-kind="quiz" data-course="{{ $courseTitle }}"
                data-quiz="{{ $quizTitle }}"
                data-result="{{ $passed ? 'passed' : 'failed' }}"
                data-score="{{ $percentage }}"
                data-competency="{{ $percentage >= 75 ? 'competent' : 'not yet competent' }}">
                <td>{{ $traineeId }}</td>
                <td>
                  <strong>{{ $name !== '' ? $name : 'Name unavailable (user ID: ' . ($result->user_id ?? '—') . ')' }}</strong>
                </td>
                <td>{{ $courseTitle }}</td>
                <td>{{ $quizTitle }}</td>
                <td>
                  <span
                    class="as-badge {{ $passed ? 'complete' : 'incomplete' }}">
                    {{ $result->score ?? 0 }}/{{ $result->total_items ?? 0 }}
                    ({{ number_format($percentage, 1) }}%)
                  </span>
                </td>
                <td><span
                    class="as-badge {{ $passed ? 'competent' : 'not-competent' }}">{{ ucfirst($result->status ?? 'unknown') }}</span>
                </td>
                <td>
                  {{ !empty($result->created_at) ? \Carbon\Carbon::parse($result->created_at)->format('M d, Y h:i A') : '—' }}
                </td>
                <td>
                  <div class="as-actions">
                    <button type="button" class="as-icon view"
                      title="View result" aria-label="View result"
                      data-name="{{ $name }}"
                      data-id="{{ $traineeId }}"
                      data-course="{{ $courseTitle }}"
                      data-quiz="{{ $quizTitle }}"
                      data-score="{{ $result->score ?? 0 }}"
                      data-total="{{ $result->total_items ?? 0 }}"
                      data-percentage="{{ $percentage }}"
                      data-status="{{ $result->status ?? 'unknown' }}"
                      data-date="{{ !empty($result->created_at) ? \Carbon\Carbon::parse($result->created_at)->format('M d, Y h:i A') : '—' }}"
                      onclick="openDetailsModal(this)">
                      <i class="fa fa-eye"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="as-empty"><i class="fa fa-inbox"></i>No quiz
                    submissions have been recorded for this course.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
    <section class="as-table-card">
      <div class="as-section-head">
        <div>
          <h3>Onsite Activity Grades</h3>
          <p>Scores recorded in onsite_grades, linked to onsite_activities.</p>
        </div>
      </div>
      <div class="as-table-scroll">
        <table class="as-table" id="onsiteTable">
          <thead>
            <tr>
              <th>Trainee ID</th>
              <th>Full Name</th>
              <th>Activity</th>
              <th>Score</th>
              <th>Activity Date</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($onsiteGrades as $grade)
              @php
                $maxScore = (float) ($grade->max_score ?? 0);
                $score = (float) ($grade->score ?? 0);
                $onsitePercentage =
                    $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
                $competent = $onsitePercentage >= 75;
                $gradeUser = $grade->user ?? null;
                $gradeFirstName = trim(
                    (string) ($grade->firstname ??
                        ($gradeUser?->firstname ?? '')),
                );
                $gradeLastName = trim(
                    (string) ($grade->lastname ??
                        ($gradeUser?->lastname ?? '')),
                );
                $gradeName = trim(
                    $gradeLastName . ', ' . $gradeFirstName,
                    ', ',
                );
              @endphp
              <tr data-assessment-kind="onsite"
                data-course="{{ $grade->course_title ?? ($course->title ?? '') }}"
                data-activity="{{ $grade->activity_title ?? 'Onsite activity' }}"
                data-result="onsite" data-score="{{ $onsitePercentage }}"
                data-competency="{{ $competent ? 'competent' : 'not yet competent' }}">
                <td>{{ $grade->user_id ?? '—' }}</td>
                <td>
                  <strong>{{ $gradeName !== '' ? $gradeName : 'Unknown trainee' }}</strong>
                </td>
                <td>{{ $grade->activity_title ?? 'Onsite activity' }}</td>
                <td>
                  <span
                    class="as-badge {{ $competent ? 'complete' : 'incomplete' }}">
                    {{ number_format($score, 2) }}/{{ number_format($maxScore, 2) }}
                    ({{ number_format($onsitePercentage, 1) }}%)
                  </span>
                </td>
                <td>
                  {{ !empty($grade->activity_date) ? \Carbon\Carbon::parse($grade->activity_date)->format('M d, Y') : '—' }}
                </td>
                <td>{{ $grade->remarks ?: '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="as-empty"><i class="fa fa-clipboard-check"></i>No
                    onsite grades have been recorded for this course.</div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
    <div class="as-bottom">
      <button type="button" class="as-btn outline"
        onclick="exportAssessmentCsv()">
        <i class="fa fa-file-csv"></i> Export CSV
      </button>
    </div>
  </div>
  <div id="detailsModal" class="as-modal" aria-hidden="true">
    <div class="as-modal-content" role="dialog" aria-modal="true"
      aria-labelledby="detailsTitle">
      <div class="as-modal-header">
        <h3 id="detailsTitle"><i class="fa fa-chart-line"></i> Quiz Performance
          Details</h3>
        <button type="button" class="as-modal-close"
          onclick="closeModal('detailsModal')"
          aria-label="Close">&times;</button>
      </div>
      <div class="as-modal-body">
        <div class="as-detail-row"><span
            class="as-detail-label">Trainee</span><span class="as-detail-val"
            id="det-name">—</span></div>
        <div class="as-detail-row"><span class="as-detail-label">Trainee
            ID</span><span class="as-detail-val" id="det-id">—</span></div>
        <div class="as-detail-row"><span
            class="as-detail-label">Course</span><span class="as-detail-val"
            id="det-course">—</span></div>
        <div class="as-detail-row"><span
            class="as-detail-label">Quiz</span><span class="as-detail-val"
            id="det-quiz">—</span></div>
        <div class="as-detail-row"><span
            class="as-detail-label">Score</span><span class="as-detail-val"
            id="det-score">—</span></div>
        <div class="as-detail-row"><span
            class="as-detail-label">Result</span><span class="as-detail-val"
            id="det-status">—</span></div>
        <div class="as-detail-row"><span
            class="as-detail-label">Submitted</span><span class="as-detail-val"
            id="det-date">—</span></div>
      </div>
      <div class="as-modal-footer"><button type="button"
          class="as-btn outline"
          onclick="closeModal('detailsModal')">Close</button></div>
    </div>
  </div>
  <div id="topModal" class="as-modal" aria-hidden="true">
    <div class="as-modal-content" role="dialog" aria-modal="true"
      aria-labelledby="topTitle">
      <div class="as-modal-header">
        <h3 id="topTitle"><i class="fa fa-trophy"></i> Top Quiz Performers</h3>
        <button type="button" class="as-modal-close"
          onclick="closeModal('topModal')" aria-label="Close">&times;</button>
      </div>
      <div class="as-modal-body" style="padding:0">
        <div class="as-table-scroll">
          <table class="as-top-table">
            <thead>
              <tr>
                <th>Rank</th>
                <th>Full Name</th>
                <th>Average Quiz Score</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($topPerformers as $i => $performer)
                @php
                  $performerUser = $performer->user ?? null;
                  $performerFirstName = trim(
                      (string) ($performer->firstname ??
                          ($performerUser?->firstname ?? '')),
                  );
                  $performerLastName = trim(
                      (string) ($performer->lastname ??
                          ($performerUser?->lastname ?? '')),
                  );
                  $performerName = trim(
                      $performerLastName . ', ' . $performerFirstName,
                      ', ',
                  );
                @endphp
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>
                    <strong>{{ $performerName !== '' ? $performerName : 'Unknown trainee' }}</strong>
                  </td>
                  <td><span
                      class="as-badge competent">{{ number_format((float) ($performer->avg_score ?? 0), 1) }}%</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="as-empty">No quiz performance data
                    yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="as-modal-footer"><button type="button"
          class="as-btn outline" onclick="closeModal('topModal')">Close</button>
      </div>
    </div>
  </div>
  <div id="onsiteTopModal" class="as-modal" aria-hidden="true">
    <div class="as-modal-content" role="dialog" aria-modal="true" aria-labelledby="onsiteTopTitle">
      <div class="as-modal-header">
        <h3 id="onsiteTopTitle"><i class="fa fa-trophy"></i> Top Onsite Performers</h3>
        <button type="button" class="as-modal-close" onclick="closeModal('onsiteTopModal')" aria-label="Close">&times;</button>
      </div>
      <div class="as-modal-body" style="padding:0">
        <div class="as-table-scroll">
          <table class="as-top-table">
            <thead>
              <tr>
                <th>Rank</th>
                <th>Full Name</th>
                <th>Average Onsite Score</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($onsiteTopPerformers as $i => $performer)
                @php
                  $performerName = trim(
                    (string) ($performer->lastname ?? '') . ', ' . (string) ($performer->firstname ?? ''),
                    ', '
                  );
                @endphp
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td><strong>{{ $performerName !== '' ? $performerName : 'Trainee ID: ' . ($performer->user_id ?? '—') }}</strong></td>
                  <td><span class="as-badge {{ (float) ($performer->avg_score ?? 0) >= 75 ? 'competent' : 'not-competent' }}">{{ number_format((float) ($performer->avg_score ?? 0), 1) }}%</span></td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="as-empty">No onsite performance data yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="as-modal-footer">
        <button type="button" class="as-btn outline" onclick="closeModal('onsiteTopModal')">Close</button>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    function openModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
    }
    function closeModal(id) {
      const modal = document.getElementById(id);
      if (!modal) return;
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
    }
    document.addEventListener('click', function(event) {
      document.querySelectorAll('.as-modal.open').forEach(function(modal) {
        if (event.target === modal) closeModal(modal.id);
      });
    });
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        document.querySelectorAll('.as-modal.open').forEach(function(modal) {
          closeModal(modal.id);
        });
      }
    });
    function openDetailsModal(button) {
      const d = button.dataset;
      document.getElementById('det-name').textContent = d.name ||
        'Unknown trainee';
      document.getElementById('det-id').textContent = d.id || '—';
      document.getElementById('det-course').textContent = d.course || '—';
      document.getElementById('det-quiz').textContent = d.quiz || '—';
      document.getElementById('det-score').textContent =
        (d.score || '0') + '/' + (d.total || '0') + ' (' + Number(d.percentage ||
          0).toFixed(1) + '%)';
      document.getElementById('det-status').textContent = (d.status || 'unknown')
        .replace(/\b\w/g, c => c.toUpperCase());
      document.getElementById('det-date').textContent = d.date || '—';
      openModal('detailsModal');
    }
    let activeAssessmentType = 'all';
    function setAssessmentType(type, button) {
      activeAssessmentType = type;
      document.querySelectorAll('[data-filter-type]').forEach(function(item) {
        const active = item.dataset.filterType === type;
        item.classList.toggle('active', active);
        item.setAttribute('aria-pressed', active ? 'true' : 'false');
      });
      const assessmentSelect = document.getElementById('filterAssessment');
      const resultField = document.getElementById('quizResultField');
      const resultSelect = document.getElementById('filterResult');
      if (assessmentSelect) {
        Array.from(assessmentSelect.options).forEach(function(option) {
          if (!option.value) {
            option.hidden = false;
            return;
          }
          const optionType = option.value.split('::', 1)[0];
          option.hidden = type !== 'all' && optionType !== type;
        });
        const selected = assessmentSelect.selectedOptions[0];
        if (selected && selected.hidden) assessmentSelect.value = '';
      }
      if (resultField) resultField.style.display = type === 'onsite' ? 'none' :
      '';
      if (type === 'onsite' && resultSelect) resultSelect.value = '';
      applyFilters();
    }
    function applyFilters() {
      const search = (document.getElementById('filterSearch')?.value || '')
        .trim().toLocaleLowerCase();
      const assessment = document.getElementById('filterAssessment')?.value || '';
      const scoreRange = document.getElementById('filterScore')?.value || '';
      const result = (document.getElementById('filterResult')?.value || '')
        .trim().toLocaleLowerCase();
      let selectedKind = '';
      let selectedTitle = '';
      if (assessment) {
        const parts = assessment.split('::');
        selectedKind = parts.shift() || '';
        selectedTitle = parts.join('::').toLocaleLowerCase();
      }
      function filterTable(selector, kind) {
        const table = document.querySelector(selector);
        if (!table) return 0;
        const rows = Array.from(table.querySelectorAll(
          'tbody tr[data-assessment-kind]'));
        let visible = 0;
        const wrapper = document.querySelector('.as-wrap');
const initialLimit = Number(wrapper?.dataset.enrolledCount || 0);
const hasActiveFilters =
    search !== '' ||
    assessment !== '' ||
    scoreRange !== '' ||
    result !== '' ||
    activeAssessmentType !== 'all';
rows.forEach(function(row, index) {
    const rowKind = (row.dataset.assessmentKind || '').toLocaleLowerCase();
    const rowAssessment = (
        rowKind === 'quiz' ? row.dataset.quiz : row.dataset.activity
    ) || (row.cells[3]?.innerText || row.cells[2]?.innerText || '');
    const rowText = (row.innerText || '').toLocaleLowerCase();
    const rowScore = Number(row.dataset.score || 0);
    const rowResult = (row.dataset.result || '').toLocaleLowerCase();
    const typeMatch =
        activeAssessmentType === 'all' || rowKind === activeAssessmentType;
    const selectedAssessmentMatch =
        !selectedKind ||
        (rowKind === selectedKind &&
            rowAssessment.trim().toLocaleLowerCase() === selectedTitle);
    const searchMatch = !search || rowText.includes(search);
    const scoreMatch =
        !scoreRange ||
        (scoreRange === 'below75' && rowScore < 75) ||
        (scoreRange === '75plus' && rowScore >= 75);
    const resultMatch =
        rowKind !== 'quiz' || !result || rowResult === result;
    const matchesFilters =
        typeMatch &&
        selectedAssessmentMatch &&
        searchMatch &&
        scoreMatch &&
        resultMatch;
    // Limit the initial view only. Active filters reveal every matching row.
    const withinInitialLimit =
        hasActiveFilters || initialLimit <= 0 || index < initialLimit;
    const show = matchesFilters && withinInitialLimit;
    row.style.display = show ? '' : 'none';
    if (show) visible++;
});
        let message = table.querySelector('tbody .as-filter-empty');
        if (rows.length > 0 && visible === 0) {
          if (!message) {
            message = document.createElement('tr');
            message.className = 'as-filter-empty';
            const label = kind === 'quiz' ? 'quiz results' : 'onsite grades';
            const colspan = kind === 'quiz' ? 8 : 6;
            message.innerHTML =
              `<td colspan="${colspan}"><div class="as-empty"><i class="fa fa-filter"></i>No matching ${label}. Try changing or resetting your filters.</div></td>`;
            table.querySelector('tbody').appendChild(message);
          }
          message.style.display = '';
        } else if (message) {
          message.remove();
        }
        return visible;
      }
      const quizCount = filterTable('#assessmentTable', 'quiz');
      const onsiteCount = filterTable('#onsiteTable', 'onsite');
      const quizCard = document.querySelector('#assessmentTable')?.closest(
        '.as-table-card');
      const onsiteCard = document.querySelector('#onsiteTable')?.closest(
        '.as-table-card');
      if (quizCard) quizCard.style.display = activeAssessmentType === 'onsite' ?
        'none' : '';
      if (onsiteCard) onsiteCard.style.display = activeAssessmentType === 'quiz' ?
        'none' : '';
      const visibleQuiz = document.getElementById('visibleQuizCount');
      const visibleOnsite = document.getElementById('visibleOnsiteCount');
      if (visibleQuiz) visibleQuiz.textContent = quizCount;
      if (visibleOnsite) visibleOnsite.textContent = onsiteCount;
      const hint = document.getElementById('filterSummaryHint');
      if (hint) {
        hint.textContent = activeAssessmentType === 'quiz' ?
          'Showing quiz submissions only. Score and result filters apply to quizzes.' :
          activeAssessmentType === 'onsite' ?
          'Showing onsite activity grades only. Score filtering uses each activity percentage.' :
          'Search and score filters apply to both tables; quiz result applies only to quizzes.';
      }
    }
    function resetFilters() {
      ['filterSearch', 'filterAssessment', 'filterScore', 'filterResult']
      .forEach(function(id) {
        const field = document.getElementById(id);
        if (field) field.value = '';
      });
      setAssessmentType('all');
    }
    document.addEventListener('DOMContentLoaded', function() {
      setAssessmentType('all');
      applyFilters();
    });
    function exportAssessmentCsv() {
      const sections = [{
          title: 'Quiz Results',
          table: document.getElementById('assessmentTable')
        },
        {
          title: 'Onsite Activity Grades',
          table: document.getElementById('onsiteTable')
        }
      ];
      const escapeCsv = value => '"' + String(value ?? '').replace(/"/g, '""')
        .replace(/\s+/g, ' ').trim() + '"';
      const lines = [];
      sections.forEach(section => {
        lines.push(escapeCsv(section.title));
        section.table.querySelectorAll('tr').forEach(row => {
          if (row.style.display === 'none') return;
          const cells = Array.from(row.querySelectorAll('th,td')).map(
            cell => escapeCsv(cell.innerText));
          if (cells.length) lines.push(cells.join(','));
        });
        lines.push('');
      });
      const blob = new Blob(['\ufeff' + lines.join('\r\n')], {
        type: 'text/csv;charset=utf-8;'
      });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = 'assessment_records.csv';
      document.body.appendChild(link);
      link.click();
      link.remove();
      URL.revokeObjectURL(url);
    }
  </script>
@endsection
