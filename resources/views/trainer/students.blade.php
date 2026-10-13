{{-- resources/views/trainer/students.blade.php --}}

@extends('trainer.layout')

@section('title', 'Students')

@section('css')

  <style>
    .st-wrap {
      padding: 28px;
    }

    .st-page-title {
      font-size: 20px;
      font-weight: 700;
      color: #025628;
      margin-bottom: 6px;
    }

    .st-page-sub {
      font-size: 13px;
      color: #89968e;
      margin-bottom: 22px;
    }

    .st-filter-bar {
      display: grid;
      grid-template-columns: minmax(260px, 1.7fr) minmax(180px, 1fr) minmax(180px, 1fr) 110px;
      align-items: end;
      gap: 12px;
      margin-bottom: 16px;
      padding: 18px;
      background: #fff;
      border: 1px solid #e2ebe5;
      border-radius: 16px;
      box-shadow: 0 4px 18px rgba(20, 65, 40, .035);
    }

    .st-filter-field {
      display: flex;
      flex-direction: column;
      gap: 7px;
      min-width: 0;
    }

    .st-filter-label {
      color: #537261;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .07em;
      text-transform: uppercase;
    }

    .st-search-wrap {
      position: relative;
      min-width: 0;
      width: 100%;
    }

    .st-search-wrap i {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #91a49a;
      font-size: 13px;
      pointer-events: none;
    }

    .st-search-input,
    .st-select {
      box-sizing: border-box;
      width: 100%;
      min-width: 0;
      height: 46px;
      border: 1px solid #dfe9e2;
      border-radius: 10px;
      background: #fff;
      color: #24382d;
      font-family: "Open Sans", sans-serif;
      font-size: 13px;
      outline: none;
      transition: border-color .18s, box-shadow .18s;
    }

    .st-search-input {
      padding: 0 12px 0 36px;
    }

    .st-select {
      padding: 0 34px 0 12px;
      cursor: pointer;
    }

    .st-search-input:focus,
    .st-select:focus {
      border-color: #087443;
      box-shadow: 0 0 0 3px rgba(8, 116, 67, .09);
    }

    .st-reset {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      align-self: end;
      box-sizing: border-box;
      height: 46px;
      min-width: 110px;
      padding: 0 15px;
      border: 1px solid #d8e7dc;
      border-radius: 10px;
      background: #fff;
      color: #075b35;
      font-family: "Open Sans", sans-serif;
      font-size: 13px;
      font-weight: 700;
      white-space: nowrap;
      cursor: pointer;
      transition: background .18s, border-color .18s, box-shadow .18s;
    }

    .st-reset:hover {
      background: #f0f7f2;
      border-color: #a9cbb5;
    }

    .st-reset:focus-visible {
      outline: 2px solid #075b35;
      outline-offset: 3px;
    }

    .st-filter-note {
      grid-column: 1 / -1;
      margin: 0;
      padding-top: 12px;
      border-top: 1px solid #edf2ee;
      color: #708077;
      font-size: 12px;
      line-height: 1.6;
    }

    .st-summary {
      font-size: 13px;
      color: #89968e;
      margin-bottom: 12px;
    }

    .st-summary span {
      font-weight: 700;
      color: #025628;
    }

    .st-table-wrap {
      background: #fff;
      border: 0.5px solid #e2e8f0;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 24px;
      width: 100%;
    }

    .st-table-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      padding: 14px 18px;
      border-bottom: 0.5px solid #f0f4f8;
      background: #fff;
    }

    .st-table-head-title {
      font-size: 13px;
      font-weight: 600;
      color: #4a5568;
    }

    .st-table-head-meta {
      color: #89968e;
      font-size: 12px;
      white-space: nowrap;
    }

    .st-table-head-meta strong {
      color: #075b35;
      font-weight: 700;
    }

    .st-table-scroll {
      width: 100%;
      overflow-x: auto;
    }

    .st-table {
      width: 100%;
      min-width: 940px;
      border-collapse: collapse;
      font-size: 13px;
      background: #fff;
    }

    .st-table th {
      background: #fff;
      padding: 10px 18px;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      color: #a0aec0;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      border-bottom: 0.5px solid #f0f4f8;
      white-space: nowrap;
    }

    .st-table td {
      padding: 11px 18px;
      border-bottom: 0.5px solid #f8f9fa;
      color: #4a5568;
      vertical-align: middle;
    }

    .st-table tbody tr:last-child td {
      border-bottom: none;
    }

    .st-table tbody tr[data-name]:hover {
      background: #fafcfa;
    }

    *

    /* Assessment-style details matching the trainer dashboard table */
    * .st-trainee-name {
      display: block;
      color: #4a5568;
      font-size: 13px;
      font-weight: 600;
      line-height: 1.45;
    }

    .st-trainee-meta {
      display: block;
      margin-top: 3px;
      color: #87948d;
      font-size: 11px;
      font-weight: 400;
      line-height: 1.45;
    }

    .st-course-name,
    .st-score {
      color: #4a5568;
      white-space: nowrap;
    }

    .st-overall-cell {
      display: flex;
      align-items: center;
      gap: 9px;
      white-space: nowrap;
    }

    .st-overall-cell .st-progress-wrap {
      width: 90px;
      min-width: 90px;
      flex: 0 0 90px;
    }

    .st-overall-cell .st-progress-bg {
      width: 90px;
      min-width: 90px;
      flex: 0 0 90px;
    }

    .st-overall-value {
      color: #4a5568;
      font-weight: 700;
      font-size: 13px;
    }

    .st-progress-fill.is-low {
      background: #e05c6e;
    }

    .st-badge.not-yet-competent {
      background: #fff1f2;
      color: #dc3545;
    }

    .st-table th:first-child,
    .st-table td:first-child {
      min-width: 230px;
    }

    .st-table th:nth-child(2),
    .st-table td:nth-child(2) {
      min-width: 160px;
    }

    .st-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #e6f4eb;
      color: #276749;
      font-size: 11px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .st-name-cell {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .st-name-cell .st-name {
      font-weight: 600;
      color: #4a5568;
    }

    .st-name-cell .st-email {
      font-size: 11px;
      color: #87948d;
    }

    .st-progress-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      min-width: 125px;
    }

    .st-progress-bg {
      flex: 1;
      height: 6px;
      background: #e8f5e9;
      border-radius: 999px;
      overflow: hidden;
      min-width: 60px;
    }

    .st-progress-fill {
      height: 100%;
      background: #1a4d2e;
      border-radius: 999px;
    }

    .st-progress-pct {
      font-size: 11px;
      color: #87948d;
      flex-shrink: 0;
      width: 32px;
    }

    .st-badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 9px;
      border-radius: 999px;
      white-space: nowrap;
    }

    .st-badge.competent {
      background: #e6f4eb;
      color: #276749;
    }

    .st-badge.not-yet-competent {
      background: #fff1f2;
      color: #dc3545;
    }

    .st-empty {
      text-align: center;
      padding: 24px;
      color: #a0aec0;
    }

    .st-empty i {
      font-size: 26px;
      opacity: 0.4;
      display: block;
      margin-bottom: 10px;
    }

    .st-empty p {
      font-size: 13px;
      margin: 0;
    }

    @media (max-width: 1100px) {
      .st-filter-bar {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .st-filter-field.search-field,
      .st-filter-note {
        grid-column: 1 / -1;
      }

      .st-reset {
        justify-self: start;
      }
    }

    @media (max-width: 600px) {
      .st-wrap {
        padding: 16px;
      }

      .st-filter-bar {
        grid-template-columns: minmax(0, 1fr);
        padding: 13px;
      }

      .st-filter-field.search-field,
      .st-filter-note {
        grid-column: auto;
      }

      .st-table-head {
        align-items: flex-start;
        flex-direction: column;
        gap: 5px;
        padding: 14px 16px;
      }

      .st-table-head-meta {
        white-space: normal;
      }

      .st-table-scroll {
        overflow-x: auto;
      }

      .st-table {
        min-width: 940px;
      }
    }
  </style>

@endsection

@section('content')

  <div class="st-wrap">

    <div class="st-page-title">Students</div>

    <div class="st-page-sub">

      Students enrolled in

      <strong style="color:#025628;">{{ $course->title ?? 'your course' }}</strong>

    </div>

    {{-- ── FILTER BAR ─────────────────────────────────────────── --}}

    <div class="st-filter-bar" role="search" aria-label="Filter students">

      <label class="st-filter-field search-field">

        <span class="st-filter-label">Search students</span>

        <span class="st-search-wrap">

          <i class="fa fa-search" aria-hidden="true"></i>

          <input type="search" class="st-search-input" id="studentSearch"
            placeholder="Search by name or email..."
            aria-label="Search by name or email">

        </span>

      </label>

      <label class="st-filter-field">

        <span class="st-filter-label">Competency status</span>

        <select class="st-select" id="statusFilter"
          aria-label="Filter by competency status">

          <option value="">All statuses</option>

          <option value="competent">Competent (75% and above)</option>

          <option value="not-yet-competent">Not Yet Competent (below 75%)</option>

        </select>

      </label>

      <label class="st-filter-field">

        <span class="st-filter-label">Sort students</span>

        <select class="st-select" id="sortFilter">

          <option value="name">Name (A–Z)</option>

          <option value="progress-low">Lowest progress first</option>

          <option value="progress-high">Highest progress first</option>

          <option value="newest">Recently enrolled</option>

        </select>

      </label>

      <button type="button" class="st-reset" id="resetFilters"><i
          class="fa fa-rotate-left" aria-hidden="true"></i> Reset</button>

    </div>

    {{-- ── STUDENT TABLE (styled to match the teacher dashboard table) --}}

    <div class="st-table-wrap">

      <div class="st-table-head">

        <span class="st-table-head-title">Student Assessment Performance</span>

        <span class="st-table-head-meta">

          Showing <strong id="visibleCount">{{ $students->count() }}</strong>

          of <strong id="totalStudentCount">{{ $students->count() }}</strong>

          students

        </span>

      </div>

      <div class="st-table-scroll">

        <table class="st-table" id="studentsTable">

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

            @forelse($students as $student)

              @php

                // Keep the assessment calculation used on the existing Students page.

                $quizAverage = max(
                    0,

                    min(100, (float) ($student->quiz_average ?? 0)),
                );

                $onsiteAverage = max(
                    0,

                    min(100, (float) ($student->onsite_average ?? 0)),
                );

                $progress = round(
                    max(0, min(100, ($quizAverage + $onsiteAverage) / 2)),

                    2,
                );

                $competencyStatus =
                    $progress >= 75 ? 'Competent' : 'Not Yet Competent';

                $competencyClass =
                    $progress >= 75 ? 'competent' : 'not-yet-competent';

                $studentName = trim(
                    ($student->firstname ?? '') .
                        ' ' .
                        ($student->lastname ?? ''),
                );

                $studentEmail = $student->email ?? '';

                $enrolledTimestamp = !empty($student->enrolled_at)
                    ? \Carbon\Carbon::parse($student->enrolled_at)->timestamp
                    : 0;
              @endphp

              <tr data-name="{{ strtolower($studentName) }}"
                data-email="{{ strtolower($studentEmail) }}"
                data-progress="{{ $progress }}"
                data-quiz-average="{{ $quizAverage }}"
                data-onsite-average="{{ $onsiteAverage }}"
                data-enrolled-at="{{ $enrolledTimestamp }}">

                <td>

                  <strong
                    class="st-trainee-name">{{ $studentName !== '' ? $studentName : 'Unknown trainee' }}</strong>

                  <small class="st-trainee-meta">

                    {{ $studentEmail !== '' ? $studentEmail : 'No email provided' }}

                  </small>

                </td>

                <td class="st-course-name">{{ $course->title ?? '—' }}</td>

                <td class="st-score">{{ number_format($quizAverage, 2) }}%</td>

                <td class="st-score">{{ number_format($onsiteAverage, 2) }}%</td>

                <td>

                  <div class="st-overall-cell">

                    <div class="st-progress-wrap">

                      <div class="st-progress-bg">

                        <div
                          class="st-progress-fill {{ $progress < 75 ? 'is-low' : '' }}"
                          style="width: {{ $progress }}%;"></div>

                      </div>

                    </div>

                    <strong
                      class="st-overall-value">{{ number_format($progress, 2) }}%</strong>

                  </div>

                </td>

                <td>

                  <span
                    class="st-badge {{ $competencyClass }}">{{ $competencyStatus }}</span>

                </td>

              </tr>

            @empty

              <tr>

                <td colspan="6">

                  <div class="st-empty">

                    <i class="fa fa-users" aria-hidden="true"></i>

                    <p>No students enrolled yet.</p>

                  </div>

                </td>

              </tr>
            @endforelse

          </tbody>

        </table>

      </div>

    </div>

  </div>

@endsection

@section('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const searchInput = document.getElementById('studentSearch');

      const statusFilter = document.getElementById('statusFilter');

      const sortFilter = document.getElementById('sortFilter');

      const resetButton = document.getElementById('resetFilters');

      const tableBody = document.querySelector('#studentsTable tbody');

      const countEl = document.getElementById('visibleCount');

      if (!tableBody || !searchInput || !statusFilter || !sortFilter)

        return;

      const rows = Array.from(tableBody.querySelectorAll('tr[data-name]'));

      const originalOrder = new Map(rows.map((row, index) => [row, index]));

      const totalCountEl = document.getElementById('totalStudentCount');

      function filterTable() {

        const query = searchInput.value.trim().toLocaleLowerCase();

        const statusChoice = statusFilter.value;

        let visible = 0;

        rows.forEach(function(row) {

          const name = (row.dataset.name || '').toLocaleLowerCase();

          const email = (row.dataset.email || '').toLocaleLowerCase();

          const progress = Number(row.dataset.progress || 0);

          const matchesSearch = !query || name.includes(query) || email

            .includes(query);

          const matchesStatus = !statusChoice ||

            (statusChoice === 'competent' && progress >= 75) ||

            (statusChoice === 'not-yet-competent' && progress < 75);

          const show = matchesSearch && matchesStatus;

          row.style.display = show ? '' : 'none';

          if (show) visible++;

        });

        const sortedRows = [...rows].sort(function(a, b) {

          const pa = Number(a.dataset.progress || 0);

          const pb = Number(b.dataset.progress || 0);

          const da = Number(a.dataset.enrolledAt || 0);

          const db = Number(b.dataset.enrolledAt || 0);

          switch (sortFilter.value) {

            case 'progress-low':

              return pa - pb || originalOrder.get(a) - originalOrder.get(

                b);

            case 'progress-high':

              return pb - pa || originalOrder.get(a) - originalOrder.get(

                b);

            case 'newest':

              return db - da || originalOrder.get(a) - originalOrder.get(

                b);

            default:

              return (a.dataset.name || '').localeCompare(b.dataset

                  .name || '') ||

                originalOrder.get(a) - originalOrder.get(b);

          }

        });

        sortedRows.forEach(function(row) {

          tableBody.appendChild(row);

        });

        if (countEl) countEl.textContent = String(visible);

        if (totalCountEl) totalCountEl.textContent = String(rows.length);

        let emptyRow = tableBody.querySelector('.st-filter-empty');

        if (rows.length > 0 && visible === 0) {

          if (!emptyRow) {

            emptyRow = document.createElement('tr');

            emptyRow.className = 'st-filter-empty';

            emptyRow.innerHTML =

              '<td colspan="6"><div class="st-empty"><i class="fa fa-filter" aria-hidden="true"></i><p>No students match these filters. Try changing or resetting your filters.</p></div></td>';

            tableBody.appendChild(emptyRow);

          }

          emptyRow.style.display = '';

        } else if (emptyRow) {

          emptyRow.remove();

        }

      }

      searchInput.addEventListener('input', filterTable);

      statusFilter.addEventListener('change', filterTable);

      sortFilter.addEventListener('change', filterTable);

      if (resetButton) {

        resetButton.addEventListener('click', function() {

          searchInput.value = '';

          statusFilter.value = '';

          sortFilter.value = 'name';

          filterTable();

          searchInput.focus();

        });

      }

      filterTable();

    });
  </script>

@endsection

