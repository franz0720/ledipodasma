{{-- resources/views/trainer/students.blade.php --}}

@extends('trainer.layout')

@section('title', 'Students')

@section('css')
  <style>
    /* ── Students page ───────────────────────────────────────── */
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
      color: #aaa;
      margin-bottom: 22px;
    }

    /* ── Filter bar ───────────────────────────────────────────── */
    .st-filter-bar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }

    .st-search-wrap {
      position: relative;
      flex: 1;
      min-width: 200px;
    }

    .st-search-wrap i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 13px;
    }

    .st-search-input {
      width: 100%;
      padding: 9px 12px 9px 34px;
      border: 1px solid #e8ede9;
      border-radius: 8px;
      font-size: 13px;
      font-family: "Open Sans", sans-serif;
      color: #1a1a1a;
      background: #fff;
      outline: none;
      transition: border-color 0.18s;
    }

    .st-search-input:focus {
      border-color: #025628;
    }

    .st-select {
      padding: 9px 12px;
      border: 1px solid #e8ede9;
      border-radius: 8px;
      font-size: 13px;
      font-family: "Open Sans", sans-serif;
      color: #1a1a1a;
      background: #fff;
      outline: none;
      cursor: pointer;
    }

    .st-select:focus {
      border-color: #025628;
    }

    /* ── Summary count ────────────────────────────────────────── */
    .st-summary {
      font-size: 13px;
      color: #aaa;
      margin-bottom: 12px;
    }

    .st-summary span {
      font-weight: 700;
      color: #025628;
    }

    /* ── Table ────────────────────────────────────────────────── */
    .st-table-wrap {
      background: #fff;
      border: 1px solid #e8ede9;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .st-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .st-table th {
      background: #f5f5f3;
      padding: 10px 16px;
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      color: #aaa;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid #e8ede9;
    }

    .st-table td {
      padding: 11px 16px;
      border-bottom: 1px solid #f0f4f0;
      color: #1a1a1a;
      vertical-align: middle;
    }

    .st-table tr:last-child td {
      border-bottom: none;
    }

    .st-table tbody tr:hover {
      background: #fafcfa;
    }

    /* Avatar */
    .st-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: #e8f5e9;
      color: #025628;
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
      color: #1a1a1a;
    }

    .st-name-cell .st-email {
      font-size: 11px;
      color: #aaa;
    }

    /* Progress bar */
    .st-progress-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .st-progress-bg {
      flex: 1;
      height: 5px;
      background: #e8f5e9;
      border-radius: 3px;
      overflow: hidden;
      min-width: 60px;
    }

    .st-progress-fill {
      height: 100%;
      background: #025628;
      border-radius: 3px;
    }

    .st-progress-pct {
      font-size: 11px;
      color: #aaa;
      flex-shrink: 0;
      width: 28px;
    }

    /* Badges */
    .st-badge {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
    }

    .st-badge.enrolled {
      background: #e8f5e9;
      color: #025628;
    }

    .st-badge.pending {
      background: #FAEEDA;
      color: #633806;
    }

    .st-badge.completed {
      background: #EAF3DE;
      color: #27500A;
    }

    .st-badge.dropped {
      background: #FCEBEB;
      color: #791F1F;
    }

    /* Empty state */
    .st-empty {
      text-align: center;
      padding: 48px 24px;
      color: #aaa;
    }

    .st-empty i {
      font-size: 36px;
      opacity: 0.3;
      display: block;
      margin-bottom: 10px;
    }

    .st-empty p {
      font-size: 13px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .st-wrap {
        padding: 18px 16px;
      }

      .st-table th:nth-child(3),
      .st-table td:nth-child(3) {
        display: none;
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

    @if (session('success'))
    <div style="
        margin-bottom:15px;
        padding:10px 14px;
        background:#e8f5e9;
        color:#2e7d32;
        border-radius:8px;
        font-size:13px;
        font-weight:600;
    ">
        {{ session('success') }}
    </div>
@endif

    {{-- ── FILTER BAR ─────────────────────────────────────────── --}}
    <div class="st-filter-bar">
      <div class="st-search-wrap">
        <i class="fa fa-search"></i>
        <input type="text" class="st-search-input" id="studentSearch"
          placeholder="Search by name or email...">
      </div>

      <select class="st-select" id="statusFilter">
        <option value="">All Status</option>
        <option value="enrolled">Enrolled</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="dropped">Dropped</option>
      </select>
    </div>

    {{-- ── SUMMARY ─────────────────────────────────────────────── --}}
    <div class="st-summary">
      Showing <span id="visibleCount">{{ $students->count() }}</span>
      of <span>{{ $students->count() }}</span> students
    </div>

    {{-- ── TABLE ──────────────────────────────────────────────── --}}
    <div class="st-table-wrap">
      <table class="st-table" id="studentsTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Enrolled At</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Hands-On Evaluation</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $student)
            @php
              $status = strtolower($student->status ?? 'pending');
              $handsOn = $handsOnEvaluations->get($student->id);
              $cls = in_array($status, [
                  'enrolled',
                  'pending',
                  'completed',
                  'dropped',
              ])
                  ? $status
                  : 'pending';
              $progress = $student->progress ?? 0;
              $initials =
                  strtoupper(substr($student->firstname ?? '', 0, 1)) .
                  strtoupper(substr($student->lastname ?? '', 0, 1));
            @endphp
            <tr
              data-name="{{ strtolower($student->firstname . ' ' . $student->lastname) }}"
              data-email="{{ strtolower($student->email) }}"
              data-status="{{ $status }}">

              <td>
                <div class="st-name-cell">
                  <div class="st-avatar">{{ $initials }}</div>
                  <div>
                    <div class="st-name">{{ $student->firstname }}
                      {{ $student->lastname }}</div>
                  </div>
                </div>
              </td>

              <td style="color:#aaa;font-size:12px;">{{ $student->email }}</td>

              <td style="color:#aaa;font-size:12px;">
                {{ $student->enrolled_at ? \Carbon\Carbon::parse($student->enrolled_at)->format('M d, Y') : '—' }}
              </td>

              <td>
                <div class="st-progress-wrap">
                  <div class="st-progress-bg">
                    <div class="st-progress-fill"
                      style="width: {{ $progress }}%"></div>
                  </div>
                  <span class="st-progress-pct">{{ $progress }}%</span>
                </div>
              </td>
<td>
    <form method="POST" action="{{ route('trainer.hands-on.store') }}"
        style="display:flex;align-items:center;gap:6px;">

        @csrf

        <input type="hidden" name="user_id" value="{{ $student->id }}">

        <select
            name="result"
            class="st-select"
            style="padding:7px 9px;font-size:12px;min-width:110px;"
        >
            <option value="">Not Evaluated</option>

            <option value="passed"
                {{ $handsOn && $handsOn->result === 'passed' ? 'selected' : '' }}>
                Passed
            </option>

            <option value="failed"
                {{ $handsOn && $handsOn->result === 'failed' ? 'selected' : '' }}>
                Failed
            </option>
        </select>

        <button
            type="submit"
            style="
                padding:7px 10px;
                border:none;
                border-radius:7px;
                background:#025628;
                color:#fff;
                font-size:11px;
                font-weight:600;
                cursor:pointer;
            "
        >
            Save
        </button>

    </form>
</td>
            </tr>
          @empty
            <tr>
              <td colspan="6">
                <div class="st-empty">
                  <i class="fa fa-users"></i>
                  <p>No students enrolled yet.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
@endsection

@section('scripts')
  <script>
    const searchInput = document.getElementById('studentSearch');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#studentsTable tbody tr[data-name]');
    const countEl = document.getElementById('visibleCount');

    function filterTable() {
      const name = searchInput.value.toLowerCase();
      const status = statusFilter.value.toLowerCase();
      let visible = 0;

      rows.forEach(row => {
        const matchName = !name || row.dataset.name.includes(name) || row
          .dataset.email.includes(name);
        const matchStatus = !status || row.dataset.status === status;
        const show = matchName && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (countEl) countEl.textContent = visible;
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
  </script>
@endsection
