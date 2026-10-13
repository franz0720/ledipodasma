@extends('trainer.layout')

@section('title', 'Attendance · LEDIPO Trainer')

@section('css')

  <style>
    :root {
      --att-green: #075b35;
      --att-green-dark: #043f26;
      --att-green-soft: #eaf5ee;
      --att-yellow: #e1dc36;
      --att-red: #dc3545;
      --att-red-soft: #fff0f1;
      --att-ink: #183329;
      --att-muted: #6b7c73;
      --att-border: #e2ebe5;
      --att-bg: #f4f7f3;
      --att-white: #ffffff;
    }

    .main-content {
      background: var(--att-bg);
      min-width: 0;
    }

    .attendance-page {
      width: 100%;
      max-width: 1500px;
      margin: 0 auto;
      padding: 28px;
      color: var(--att-ink);
    }

    /* Header */
    .att-header {
      position: relative;
      overflow: hidden;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 24px;
      background: var(--att-green);
      border-radius: 18px;
      padding: 30px;
      margin-bottom: 24px;
      color: white;
    }

    .att-header::after {
      content: "";
      position: absolute;
      width: 190px;
      height: 190px;
      border: 32px solid rgba(255, 255, 255, .055);
      border-radius: 50%;
      right: 100px;
      top: -100px;
      pointer-events: none;
    }

    .att-header-content {
      position: relative;
      z-index: 1;
      min-width: 0;
    }

    .att-eyebrow {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #d5e8dc;
      text-transform: uppercase;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      margin-bottom: 12px;
    }

    .att-eyebrow-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--att-yellow);
    }

    .att-header h2 {
      margin: 0;
      color: white;
      font-size: clamp(24px, 3vw, 32px);
      line-height: 1.2;
      font-weight: 750;
      letter-spacing: -.7px;
    }

    .att-header p {
      margin: 9px 0 0;
      color: #d5e8dc;
      font-size: 13px;
      line-height: 1.7;
    }

    .att-header-date {
      position: relative;
      z-index: 1;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 13px 16px;
      border: 1px solid rgba(255, 255, 255, .18);
      background: rgba(255, 255, 255, .08);
      border-radius: 12px;
    }

    .att-date-icon {
      display: grid;
      place-items: center;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: var(--att-yellow);
      color: var(--att-green-dark);
      font-size: 19px;
      font-weight: 800;
    }

    .att-header-date small {
      display: block;
      color: #d5e8dc;
      font-size: 10px;
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: .7px;
    }

    .att-header-date strong {
      display: block;
      font-size: 13px;
      color: white;
    }

    /* Notices */
    * .att-alert {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 15px 17px;
      margin-bottom: 20px;
      border-radius: 12px;
      font-size: 13px;
      line-height: 1.6;
    }

    .att-alert-success {
      color: #17603a;
      background: #e7f5eb;
      border: 1px solid #cce9d5;
    }

    .att-alert-error {
      color: #a12532;
      background: var(--att-red-soft);
      border: 1px solid #f5d0d4;
    }

    .att-alert-warning {
      color: #775d08;
      background: #fffbe1;
      border: 1px solid #f0e7a1;
    }

    .att-alert-icon {
      font-size: 17px;
      font-weight: 800;
      flex-shrink: 0;
    }

    /* Statistics */
    * .att-stats {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .att-stat {
      background: var(--att-white);
      border: 1px solid var(--att-border);
      border-radius: 15px;
      padding: 20px;
      min-width: 0;
      box-shadow: 0 3px 12px rgba(24, 51, 41, .025);
    }

    .att-stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 16px;
    }

    .att-stat-label {
      color: var(--att-muted);
      font-size: 12px;
      font-weight: 600;
    }

    .att-stat-icon {
      display: grid;
      place-items: center;
      width: 38px;
      height: 38px;
      border-radius: 11px;
      font-size: 18px;
      font-weight: 800;
      flex-shrink: 0;
    }

    .att-stat-total .att-stat-icon {
      background: #edf2ef;
      color: var(--att-green);
    }

    .att-stat-present .att-stat-icon {
      background: var(--att-green-soft);
      color: var(--att-green);
    }

    .att-stat-absent .att-stat-icon {
      background: var(--att-red-soft);
      color: var(--att-red);
    }

    .att-stat-rate .att-stat-icon {
      background: #fffbe1;
      color: #776d00;
    }

    .att-stat-value {
      color: var(--att-ink);
      font-size: 32px;
      line-height: 1.1;
      font-weight: 750;
      letter-spacing: -1px;
    }

    .att-stat-caption {
      color: var(--att-muted);
      font-size: 11px;
      margin-top: 9px;
    }

    /* Attendance panel */
    * .att-panel {
      background: white;
      border: 1px solid var(--att-border);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 3px 12px rgba(24, 51, 41, .025);
    }

    .att-panel-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--att-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      min-height: 82px;
      box-sizing: border-box;
    }

    .att-panel-title {
      margin: 0;
      font-size: 16px;
      line-height: 1.35;
      font-weight: 750;
      color: var(--att-ink);
    }

    .att-panel-subtitle {
      margin: 6px 0 0;
      color: var(--att-muted);
      font-size: 12px;
      line-height: 1.5;
    }

    .att-count-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      flex-shrink: 0;
      border-radius: 20px;
      padding: 9px 13px;
      color: var(--att-green);
      background: var(--att-green-soft);
      font-size: 11px;
      font-weight: 700;
    }

    .att-count-badge>span:first-child {
      display: inline-block;
      width: 7px;
      height: 7px;
      flex: 0 0 7px;
      background: var(--att-green);
      border-radius: 50%;
    }

    .att-count-badge>#attVisibleCount {
      display: inline;
      width: auto;
      height: auto;
      flex: 0 0 auto;
      color: var(--att-green-dark);
      font-size: 12px;
      font-weight: 800;
      line-height: 1;
    }

    /* Toolbar */
    * .att-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px;
      padding: 18px 24px;
      background: #fcfdfc;
      border-bottom: 1px solid var(--att-border);
    }

    .att-search {
      position: relative;
      flex: 1 1 240px;
      min-width: 0;
    }

    .att-search-icon {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #829087;
      font-size: 16px;
      pointer-events: none;
    }

    .att-search input,
    .att-filter select {
      width: 100%;
      min-height: 43px;
      border: 1px solid #dce6df;
      background: white;
      border-radius: 10px;
      color: var(--att-ink);
      font-family: inherit;
      font-size: 12px;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .att-search input {
      padding: 0 14px 0 39px;
    }

    .att-filter {
      flex: 0 1 170px;
    }

    .att-filter select {
      padding: 0 12px;
      cursor: pointer;
    }

    .att-search input:focus,
    .att-filter select:focus {
      border-color: var(--att-green);
      box-shadow: 0 0 0 3px rgba(7, 91, 53, .08);
    }

    .att-btn {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      min-height: 41px;
      border-radius: 9px;
      padding: 10px 13px;
      font-family: inherit;
      font-size: 11px;
      font-weight: 700;
      cursor: pointer;
      transition: background .2s, border-color .2s, transform .2s;
      text-decoration: none;
      white-space: nowrap;
    }

    .att-btn:active {
      transform: translateY(1px);
    }

    .att-btn-outline {
      border: 1px solid #dce6df;
      background: white;
      color: var(--att-green);
    }

    .att-btn-outline:hover {
      background: var(--att-green-soft);
      border-color: #b9d9c5;
    }

    .att-btn-danger-outline {
      border: 1px solid #f1d1d4;
      background: white;
      color: #b52d3a;
    }

    .att-btn-danger-outline:hover {
      background: var(--att-red-soft);
    }

    /* Table */
    * .att-table-wrap {
      width: 100%;
      overflow-x: auto;
    }

    .att-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      text-align: left;
    }

    .att-table thead {
      background: #f8faf8;
    }

    .att-table th {
      padding: 14px 24px;
      color: #78877e;
      font-size: 10px;
      font-weight: 750;
      text-transform: uppercase;
      letter-spacing: .7px;
      white-space: nowrap;
      border-bottom: 1px solid var(--att-border);
    }

    .att-table td {
      padding: 16px 24px;
      color: #53635a;
      border-bottom: 1px solid #edf2ee;
      vertical-align: middle;
    }

    .att-table tbody tr {
      transition: background .15s;
    }

    .att-table tbody tr:hover {
      background: #fbfdfb;
    }

    .att-table tbody tr:last-child td {
      border-bottom: none;
    }

    .att-number {
      color: #96a49b;
      font-size: 11px;
      font-weight: 700;
      width: 48px;
    }

    .att-trainee {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 190px;
    }

    .att-avatar {
      display: grid;
      place-items: center;
      width: 40px;
      height: 40px;
      border-radius: 12px;
      flex-shrink: 0;
      background: var(--att-green-soft);
      color: var(--att-green);
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
    }

    .att-trainee-name {
      color: var(--att-ink);
      font-weight: 700;
      font-size: 12px;
      line-height: 1.5;
    }

    .att-trainee-id {
      color: #94a198;
      font-size: 10px;
      margin-top: 3px;
    }

    .att-status {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 7px 10px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 750;
      white-space: nowrap;
    }

    .att-status::before {
      content: "";
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: currentColor;
    }

    .att-status-present {
      color: #187343;
      background: #e9f6ed;
    }

    .att-status-absent {
      color: #bd2938;
      background: #fff0f1;
    }

    .att-status-pending {
      color: #8a7212;
      background: #fffbe5;
    }

    .att-check-control {
      display: inline-flex;
      align-items: center;
      gap: 9px;
      cursor: pointer;
      color: #53635a;
      font-size: 11px;
      font-weight: 600;
      user-select: none;
    }

    .att-check-control input {
      appearance: none;
      -webkit-appearance: none;
      display: grid;
      place-content: center;
      width: 19px;
      height: 19px;
      margin: 0;
      flex-shrink: 0;
      border: 1.5px solid #cad8ce;
      border-radius: 5px;
      background: white;
      cursor: pointer;
      transition: background .15s, border-color .15s;
    }

    .att-check-control input::before {
      content: "✓";
      color: white;
      font-size: 12px;
      font-weight: 800;
      transform: scale(0);
    }

    .att-check-control input:checked {
      background: var(--att-red);
      border-color: var(--att-red);
    }

    .att-check-control input:checked::before {
      transform: scale(1);
    }

    .att-check-control input:focus-visible {
      outline: 3px solid rgba(7, 91, 53, .2);
      outline-offset: 2px;
    }

    .att-empty-filter {
      display: none;
      text-align: center;
      padding: 36px 20px;
      color: var(--att-muted);
      font-size: 12px;
    }

    /* Footer */
    * .att-panel-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
      padding: 19px 24px;
      border-top: 1px solid var(--att-border);
      background: #fcfdfc;
    }

    .att-footer-summary {
      font-size: 11px;
      color: var(--att-muted);
      line-height: 1.7;
    }

    .att-footer-summary strong {
      color: var(--att-ink);
    }

    .att-footer-actions {
      display: flex;
      justify-content: flex-end;
      flex-wrap: wrap;
      gap: 10px;
    }

    .att-submit {
      border: 0;
      background: var(--att-green);
      color: white;
      padding: 12px 20px;
      min-height: 43px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: 750;
      font-family: inherit;
      cursor: pointer;
      transition: background .2s, transform .2s;
    }

    .att-submit:hover {
      background: var(--att-green-dark);
    }

    .att-submit:active {
      transform: translateY(1px);
    }

    .att-submit:disabled {
      opacity: .6;
      cursor: not-allowed;
    }

    /* Empty states */
    * .att-empty {
      padding: 55px 24px;
      text-align: center;
    }

    .att-empty-icon {
      display: grid;
      place-items: center;
      width: 64px;
      height: 64px;
      margin: 0 auto 17px;
      background: var(--att-green-soft);
      color: var(--att-green);
      border-radius: 18px;
      font-size: 27px;
      font-weight: 800;
    }

    .att-empty h3 {
      margin: 0 0 8px;
      color: var(--att-ink);
      font-size: 16px;
      font-weight: 750;
    }

    .att-empty p {
      max-width: 420px;
      margin: 0 auto;
      color: var(--att-muted);
      font-size: 12px;
      line-height: 1.8;
    }

    .att-progress-track {
      height: 5px;
      width: 100%;
      margin-top: 12px;
      overflow: hidden;
      background: #eaf0eb;
      border-radius: 20px;
    }

    .att-progress-fill {
      width: 0;
      height: 100%;
      background: var(--att-yellow);
      border-radius: inherit;
      transition: width .2s;
    }

    /* Daily attendance history */
    * .att-history {
      margin-bottom: 24px;
    }

    .att-history-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px;
      padding: 18px 24px;
      background: #fcfdfc;
      border-bottom: 1px solid var(--att-border);
    }

    .att-history-toolbar label {
      color: var(--att-muted);
      font-size: 12px;
      font-weight: 700;
    }

    .att-history-toolbar select,
    .att-history-toolbar input {
      min-height: 42px;
      padding: 0 12px;
      border: 1px solid #dce6df;
      border-radius: 10px;
      background: #fff;
      color: var(--att-ink);
      font: inherit;
      font-size: 12px;
    }

    .att-history-toolbar select {
      flex: 1 1 220px;
    }

    .att-history-toolbar input {
      flex: 1 1 240px;
    }

    .att-history-day {
      border-bottom: 1px solid var(--att-border);
    }

    .att-history-day:last-child {
      border-bottom: 0;
    }

    .att-history-day[hidden] {
      display: none !important;
    }

    .att-history-day summary {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 18px 24px;
      cursor: pointer;
      list-style: none;
    }

    .att-history-day summary::-webkit-details-marker {
      display: none;
    }

    .att-history-day summary:hover {
      background: #fbfdfb;
    }

    .att-history-date {
      color: var(--att-ink);
      font-size: 13px;
      font-weight: 750;
    }

    .att-history-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 7px;
    }

    .att-history-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      border-radius: 20px;
      padding: 5px 9px;
      font-size: 10px;
      font-weight: 750;
      white-space: nowrap;
    }

    .att-history-pill.present {
      color: #187343;
      background: #e9f6ed;
    }

    .att-history-pill.absent {
      color: #bd2938;
      background: #fff0f1;
    }

    .att-history-content {
      padding: 0 24px 20px;
    }

    .att-history-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    .att-history-table th,
    .att-history-table td {
      padding: 12px 14px;
      text-align: left;
      border-bottom: 1px solid #edf2ee;
    }

    .att-history-table th {
      color: #78877e;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .6px;
      background: #f8faf8;
    }

    .att-history-table tr:last-child td {
      border-bottom: 0;
    }

    .att-history-empty {
      padding: 28px 20px;
      text-align: center;
      color: var(--att-muted);
      font-size: 12px;
    }

    @media (max-width:700px) {
      .att-history-day summary {
        align-items: flex-start;
        padding: 16px;
      }

      .att-history-content {
        padding: 0 12px 16px;
      }

      .att-history-toolbar {
        padding: 14px;
      }

      .att-history-table th,
      .att-history-table td {
        padding: 10px;
      }
    }

    @media (max-width: 1100px) {
      .att-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .attendance-page {
        padding: 22px;
      }
    }

    @media (max-width: 700px) {
      .attendance-page {
        padding: 14px;
      }

      .att-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 23px 20px;
        gap: 18px;
      }

      .att-header-date {
        width: 100%;
        box-sizing: border-box;
      }

      .att-stats {
        gap: 10px;
      }

      .att-stat {
        padding: 15px;
      }

      .att-stat-value {
        font-size: 27px;
      }

      .att-stat-label {
        font-size: 11px;
      }

      .att-stat-icon {
        width: 32px;
        height: 32px;
        font-size: 15px;
      }

      .att-panel-header {
        align-items: center;
        padding: 17px 18px;
        gap: 12px;
        min-height: 76px;
      }

      .att-toolbar {
        padding: 15px;
        gap: 9px;
      }

      .att-search {
        flex-basis: 100%;
      }

      .att-filter {
        flex: 1 1 100%;
      }

      .att-toolbar .att-btn {
        flex: 1 1 auto;
      }

      .att-table th,
      .att-table td {
        padding: 13px 15px;
      }

      .att-panel-footer {
        padding: 16px;
        align-items: stretch;
        flex-direction: column;
      }

      .att-footer-actions {
        display: grid;
        grid-template-columns: 1fr;
      }

      .att-submit,
      .att-footer-actions .att-btn {
        width: 100%;
      }
    }

    @media (max-width: 480px) {
      .att-history-toolbar {
        align-items: stretch;
      }

      .att-history-toolbar label,
      .att-history-toolbar select,
      .att-history-toolbar input {
        flex: 1 1 100%;
        width: 100%;
        box-sizing: border-box;
      }

      .att-history-day summary {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      .att-history-day summary .att-count-badge {
        align-self: flex-start;
      }
    }

    @media (prefers-reduced-motion: reduce) {

      .att-btn,
      .att-table tbody tr,
      .att-progress-fill {
        transition: none;
      }
    }
  </style>

@endsection

@section('content')

  <div class="attendance-page">

    {{-- Page header --}}

    <div class="att-header">

      <div class="att-header-content">

        <h2>Attendance Management</h2>

        <p>

          Record trainee attendance and review today's class participation.

          <br>

          @if ($course)
            {{ $course->title }}
          @else
            Manage your scheduled training sessions.
          @endif

        </p>

      </div>

      <div class="att-header-date">

        <div class="att-date-icon" aria-hidden="true">▦</div>

        <div>

          <small>Attendance date</small>

          <strong>{{ $today }}</strong>

        </div>

      </div>

    </div>

    {{-- Flash messages --}}

    @if (session('success'))
      <div class="att-alert att-alert-success" role="status">

        <span class="att-alert-icon">✓</span>

        <div>{{ session('success') }}</div>

      </div>
    @endif

    @if (session('error'))
      <div class="att-alert att-alert-error" role="alert">

        <span class="att-alert-icon">!</span>

        <div>{{ session('error') }}</div>

      </div>
    @endif

    @if ($errors->any())

      <div class="att-alert att-alert-error" role="alert">

        <span class="att-alert-icon">!</span>

        <div>

          <strong>Please check the following:</strong>

          <ul style="margin:6px 0 0;padding-left:18px;">

            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach

          </ul>

        </div>

      </div>

    @endif

    {{-- Daily attendance history. The controller should pass $dailyAttendance grouped by date. --}}

    <section class="att-panel att-history" aria-labelledby="attHistoryTitle">

      <div class="att-panel-header">

        <div>

          <h3 class="att-panel-title" id="attHistoryTitle">Daily attendance

            history</h3>

          <p class="att-panel-subtitle">Choose a training date to see which

            trainees were present or absent.</p>

        </div>

        <span
          class="att-count-badge"><span></span>{{ collect($dailyAttendance ?? [])->count() }}

          recorded days</span>

      </div>

      <div class="att-history-toolbar">

        <label for="attHistoryDate">Training date</label>

        <select id="attHistoryDate">

          <option value="all">All recorded dates</option>

          @foreach ($dailyAttendance ?? [] as $day)
            <option value="{{ $day['date'] ?? '' }}">

              {{ $day['label'] ?? ($day['date'] ?? 'Training date') }}</option>
          @endforeach

        </select>

        <input type="search" id="attHistorySearch"
          placeholder="Search trainee name..."
          aria-label="Search attendance history" autocomplete="off">

      </div>

      <div id="attHistoryDays">

        @forelse (($dailyAttendance ?? []) as $day)

          @php

            $historyRecords = collect($day['records'] ?? []);

            $historyPresent = $historyRecords

                ->filter(
                    fn($record) => strtolower(
                        (string) data_get($record, 'status', ''),
                    ) === 'present',
                )

                ->count();

            $historyAbsent = $historyRecords

                ->filter(
                    fn($record) => strtolower(
                        (string) data_get($record, 'status', ''),
                    ) === 'absent',
                )

                ->count();
          @endphp

          <details class="att-history-day"
            data-history-date="{{ $day['date'] ?? '' }}">

            <summary>

              <div>

                <div class="att-history-date">

                  {{ $day['label'] ?? ($day['date'] ?? 'Training date') }}</div>

                <div class="att-history-meta">

                  <span class="att-history-pill present">{{ $historyPresent }}

                    present</span>

                  <span class="att-history-pill absent">{{ $historyAbsent }}

                    absent</span>

                </div>

              </div>

              <span class="att-count-badge">{{ $historyRecords->count() }}

                trainees</span>

            </summary>

            <div class="att-history-content">

              @if ($historyRecords->isNotEmpty())
                <div class="att-table-wrap" data-history-table>

                  <table class="att-history-table">

                    <thead>

                      <tr>

                        <th>Trainee</th>

                        <th>Status</th>

                      </tr>

                    </thead>

                    <tbody>

                      @foreach ($historyRecords as $record)
                        @php

                          $historyStatus = strtolower(
                              (string) data_get($record, 'status', 'unknown'),
                          );

                          $historyName = data_get(
                              $record,

                              'student_name',

                              'Unnamed trainee',
                          );
                        @endphp

                        <tr data-history-record
                          data-history-name="{{ strtolower($historyName) }}"
                          data-history-enrollment="{{ data_get($record, 'enrollment_id', '') }}">

                          <td>{{ $historyName }}</td>

                          <td><span
                              class="att-status {{ $historyStatus === 'present' ? 'att-status-present' : ($historyStatus === 'absent' ? 'att-status-absent' : 'att-status-pending') }}">{{ ucfirst($historyStatus) }}</span>

                          </td>

                        </tr>
                      @endforeach

                    </tbody>

                  </table>

                </div>

                <div class="att-history-empty" data-history-no-records hidden>No

                  trainees match this search for the selected date.</div>
              @else
                <div class="att-history-empty">No attendance records were saved

                  for this date.</div>
              @endif

            </div>

          </details>

        @empty

          <div class="att-empty">

            <div class="att-empty-icon">▦</div>

            <h3>No attendance history yet</h3>

            <p>Once attendance has been saved for a training day, the date and

              trainee records will appear here.</p>

          </div>
        @endforelse

      </div>

    </section>

    @if (!$course)

      <div class="att-panel">

        <div class="att-empty">

          <div class="att-empty-icon">!</div>

          <h3>No course assigned</h3>

          <p>

            Your account doesn't currently have an assigned course.

            Contact your administrator to have a course assigned before

            recording attendance.

          </p>

        </div>

      </div>
    @elseif(!$inSessionToday)
      <div class="att-panel">

        <div class="att-empty">

          <div class="att-empty-icon">▦</div>

          <h3>No training session scheduled today</h3>

          <p>

            Attendance is unavailable because this course isn't scheduled

            to meet today.

            Schedule: {{ $course->schedule ?? 'No schedule set' }}.

          </p>

        </div>

      </div>
    @elseif($enrollments->isEmpty())
      <div class="att-panel">

        <div class="att-empty">

          <div class="att-empty-icon">♙</div>

          <h3>No enrolled trainees yet</h3>

          <p>

            There are no active enrollments for this course.

            Trainees will appear here once they are enrolled.

          </p>

        </div>

      </div>
    @else
      {{-- Statistics --}}

      <div class="att-stats" aria-live="polite">

        <div class="att-stat att-stat-total">

          <div class="att-stat-top">

            <span class="att-stat-label">Total trainees</span>

            <span class="att-stat-icon">♙</span>

          </div>

          <div class="att-stat-value" id="attTotal">

            {{ $students->count() }}

          </div>

          <div class="att-stat-caption">Enrolled in this course</div>

        </div>

        <div class="att-stat att-stat-present">

          <div class="att-stat-top">

            <span class="att-stat-label">Marked present</span>

            <span class="att-stat-icon">✓</span>

          </div>

          <div class="att-stat-value" id="attPresent">0</div>

          <div class="att-stat-caption">Not marked absent</div>

        </div>

        <div class="att-stat att-stat-absent">

          <div class="att-stat-top">

            <span class="att-stat-label">Marked absent</span>

            <span class="att-stat-icon">!</span>

          </div>

          <div class="att-stat-value" id="attAbsent">0</div>

          <div class="att-stat-caption">Trainees marked absent</div>

        </div>

        <div class="att-stat att-stat-rate">

          <div class="att-stat-top">

            <span class="att-stat-label">Attendance rate</span>

            <span class="att-stat-icon">%</span>

          </div>

          <div class="att-stat-value" id="attRate">100%</div>

          <div class="att-progress-track">

            <div class="att-progress-fill" id="attProgress"></div>

          </div>

          <div class="att-stat-caption">Based on current selections</div>

        </div>

      </div>

      {{-- Attendance table --}}

      <div class="att-panel">

        <div class="att-panel-header">

          <div>

            <h3 class="att-panel-title">Trainee attendance</h3>

            <p class="att-panel-subtitle">

              Review each trainee and mark anyone who is absent.

            </p>

          </div>

          <span class="att-count-badge">

            <span></span>

            <span id="attVisibleCount">{{ $students->count() }}</span>

            trainees

          </span>

        </div>

        {{-- Search and filter toolbar --}}

        <div class="att-toolbar">

          <div class="att-search">

            <span class="att-search-icon" aria-hidden="true">⌕</span>

            <input type="search" id="attSearch"
              placeholder="Search trainee name..." aria-label="Search trainees"
              autocomplete="off">

          </div>

          <div class="att-filter">

            <select id="attFilter" aria-label="Filter attendance status">

              <option value="all">All statuses</option>

              <option value="present">Present</option>

              <option value="absent">Absent</option>

            </select>

          </div>

          <button type="button" class="att-btn att-btn-outline"
            id="markAllPresent">

            ✓ Mark all present

          </button>

          <button type="button" class="att-btn att-btn-danger-outline"
            id="clearAllAbsent">

            Clear absences

          </button>

        </div>

        <form method="POST" action="{{ route('trainer.attendance.store') }}"
          id="attendanceForm">

          @csrf

          <div class="att-table-wrap">

            <table class="att-table">

              <thead>

                <tr>

                  <th style="width:55px;">#</th>

                  <th>Trainee</th>

                  <th>Attendance status</th>

                  <th>Mark absent</th>

                </tr>

              </thead>

              <tbody id="attTableBody">

                @foreach ($students as $student)
                  @php

                    $studentName = trim(
                        ($student->firstname ?? '') .
                            ' ' .
                            ($student->lastname ?? ''),
                    );

                    $initials = collect(explode(' ', $studentName))
                        ->filter()

                        ->take(2)

                        ->map(fn($part) => mb_substr($part, 0, 1))

                        ->implode('');

                    $enrollmentId = $student->enrollment_id;

                    $isAbsent =
                        strtolower(
                            (string) ($existing[$enrollmentId] ?? 'present'),
                        ) === 'absent';
                  @endphp

                  <tr class="att-row"
                    data-name="{{ strtolower($studentName) }}"
                    data-enrollment="{{ $enrollmentId }}">

                    <td class="att-number">

                      {{ $loop->iteration }}

                    </td>

                    <td>

                      <div class="att-trainee">

                        <div class="att-avatar">

                          {{ $initials ?: 'T' }}

                        </div>

                        <div>

                          <div class="att-trainee-name">

                            {{ $studentName ?: 'Unnamed trainee' }}

                          </div>

                          <div class="att-trainee-id">

                            Enrollment #{{ $enrollmentId }}

                          </div>

                        </div>

                      </div>

                    </td>

                    <td>

                      <span
                        class="att-status {{ $isAbsent ? 'att-status-absent' : 'att-status-present' }}"
                        data-status>

                        {{ $isAbsent ? 'Absent' : 'Present' }}

                      </span>

                    </td>

                    <td>

                      <label class="att-check-control">

                        <input type="checkbox" name="absent[]"
                          value="{{ $enrollmentId }}"
                          class="att-absent-checkbox"
                          {{ $isAbsent ? 'checked' : '' }}>

                        <span>Mark absent</span>

                      </label>

                    </td>

                  </tr>
                @endforeach

                <tr id="attNoResults" style="display: none;">

                  <td colspan="4"
                    style="text-align: center; padding: 32px 16px; color: var(--att-muted);">

                    No trainees match your search or selected filter.

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

          <div class="att-panel-footer">

            <div class="att-footer-summary">

              <strong id="attFooterSummary">

                {{ $students->count() }} trainees

              </strong>

              <br>

              Review the attendance before submitting.

            </div>

            <div class="att-footer-actions">

              <button type="button" class="att-btn att-btn-outline"
                id="resetAttendance">

                Reset changes

              </button>

              <button type="submit" class="att-submit" id="submitAttendance">

                ✓ &nbsp; Submit attendance

              </button>

            </div>

          </div>

        </form>

      </div>

    @endif

  </div>

@endsection

@section('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // Filter the historical attendance independently from today's attendance form.
      const historyDate = document.getElementById('attHistoryDate');

      const historySearch = document.getElementById('attHistorySearch');

      const historyDays = Array.from(document.querySelectorAll(

        '.att-history-day'));

      function applyHistoryFilters() {

        if (!historyDate || !historySearch) return;

        const selectedDate = historyDate.value;

        const query = historySearch.value.trim().toLowerCase();

        historyDays.forEach(day => {

          const dateMatches = selectedDate === 'all' || day.dataset

            .historyDate === selectedDate;

          const records = Array.from(day.querySelectorAll(

            '[data-history-record]'));

          let visibleRecords = 0;

          records.forEach(record => {

            const name = record.dataset.historyName || '';

            const enrollment = record.dataset.historyEnrollment || '';

            const matches = name.includes(query) || enrollment

              .toLowerCase().includes(query);

            record.hidden = !matches;

            if (matches) visibleRecords++;

          });

          const noRecords = day.querySelector(

            '[data-history-no-records]');

          const tableWrap = day.querySelector('[data-history-table]');

          if (noRecords) noRecords.hidden = records.length === 0 ||

            visibleRecords > 0;

          if (tableWrap) tableWrap.hidden = records.length > 0 &&

            visibleRecords === 0;

          day.hidden = !dateMatches;

          if (dateMatches && selectedDate !== 'all') day.open = true;

        });

      }

      if (historyDate) historyDate.addEventListener('change',

        applyHistoryFilters);

      if (historySearch) historySearch.addEventListener('input',

        applyHistoryFilters);

      applyHistoryFilters();

      const form = document.getElementById('attendanceForm');

      if (!form) return;

      const rows = Array.from(form.querySelectorAll('.att-row'));

      const checkboxes = Array.from(form.querySelectorAll(

        '.att-absent-checkbox'));

      const search = document.getElementById('attSearch');

      const filter = document.getElementById('attFilter');

      const noResults = document.getElementById('attNoResults');

      const totalEl = document.getElementById('attTotal');

      const presentEl = document.getElementById('attPresent');

      const absentEl = document.getElementById('attAbsent');

      const rateEl = document.getElementById('attRate');

      const progressEl = document.getElementById('attProgress');

      const visibleCountEl = document.getElementById('attVisibleCount');

      const footerSummary = document.getElementById('attFooterSummary');

      const markAllPresent = document.getElementById('markAllPresent');

      const clearAllAbsent = document.getElementById('clearAllAbsent');

      const resetButton = document.getElementById('resetAttendance');

      // Store the initial checkbox states so Reset changes is predictable.
      const originalStates = checkboxes.map(checkbox => checkbox.checked);

      function updateStatistics() {

        const total = checkboxes.length;

        const absent = checkboxes.filter(checkbox => checkbox.checked).length;

        const present = total - absent;

        const rate = total > 0 ? Math.round((present / total) * 100) : 0;

        if (totalEl) totalEl.textContent = total;

        if (presentEl) presentEl.textContent = present;

        if (absentEl) absentEl.textContent = absent;

        if (rateEl) rateEl.textContent = rate + '%';

        if (progressEl) progressEl.style.width = rate + '%';

        if (footerSummary) {

          footerSummary.textContent =

            present + ' present · ' + absent + ' absent · ' + total +

            ' total';

        }

      }

      function applyFilters() {

        const query = search ? search.value.trim().toLowerCase() : '';

        const selectedStatus = filter ? filter.value : 'all';

        let visibleCount = 0;

        rows.forEach(row => {

          const name = row.dataset.name || '';

          const enrollment = row.dataset.enrollment || '';

          const checkbox = row.querySelector('.att-absent-checkbox');

          const isAbsent = checkbox.checked;

          const status = isAbsent ? 'absent' : 'present';

          const matchesSearch =

            name.includes(query) || enrollment.includes(query);

          const matchesStatus =

            selectedStatus === 'all' || status === selectedStatus;

          const visible = matchesSearch && matchesStatus;

          row.style.display = visible ? '' : 'none';

          const statusBadge = row.querySelector('[data-status]');

          statusBadge.textContent = status === 'absent' ? 'Absent' :

            'Present';

          statusBadge.classList.toggle('att-status-absent', isAbsent);

          statusBadge.classList.toggle('att-status-present', !isAbsent);

          if (visible) visibleCount++;

        });

        if (visibleCountEl) visibleCountEl.textContent = visibleCount;

        if (noResults) noResults.style.display = visibleCount === 0 ?

          'table-row' : 'none';

        updateStatistics();

      }

      checkboxes.forEach(checkbox => {

        checkbox.addEventListener('change', applyFilters);

      });

      if (search) search.addEventListener('input', applyFilters);

      if (filter) filter.addEventListener('change', applyFilters);

      // A checkbox means absent; unchecked means present.
      if (markAllPresent) markAllPresent.addEventListener('click',

        function() {

          checkboxes.forEach(checkbox => {

            checkbox.checked = false;

          });

          applyFilters();

        });

      if (clearAllAbsent) clearAllAbsent.addEventListener('click',

        function() {

          rows.forEach(row => {

            if (row.style.display !== 'none') {

              const checkbox = row.querySelector(

                '.att-absent-checkbox');

              if (checkbox && checkbox.checked) checkbox.checked =

                false;

            }

          });

          applyFilters();

        });

      if (resetButton) resetButton.addEventListener('click', function() {

        checkboxes.forEach((checkbox, index) => {

          checkbox.checked = originalStates[index];

        });

        if (search) search.value = '';

        if (filter) filter.value = 'all';

        applyFilters();

      });

      form.addEventListener('submit', function(event) {

        const absent = checkboxes.filter(checkbox => checkbox.checked)

          .length;

        const present = checkboxes.length - absent;

        const message =

          'Submit attendance for ' + checkboxes.length +

          ' trainees?\n\n' +

          'Present: ' + present + '\n' +

          'Absent: ' + absent + '\n\n' +

          'Please confirm that the attendance is correct.';

        if (!window.confirm(message)) {

          event.preventDefault();

        }

      });

      applyFilters();

    });
  </script>

@endsection

