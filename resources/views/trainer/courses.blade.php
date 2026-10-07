@extends('trainer.layout')

@section('title', 'My Courses')

@section('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* ── Page wrap ────────────────────────────────────────────── */
    .mc-wrap {
      padding: 28px;
    }

    .mc-page-title {
      font-size: 20px;
      font-weight: 700;
      color: #025628;
      margin-bottom: 6px;
    }

    .mc-page-sub {
      font-size: 13px;
      color: #aaa;
      margin-bottom: 24px;
    }

    /* ── Course card ──────────────────────────────────────────── */
    .mc-course-card {
      background: #fff;
      border: 2px solid #7fb092;
      border-radius: 12px;
      overflow: hidden;
      max-width: 340px;
      position: relative;
    }

    .mc-thumb {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }

    .mc-thumb-fallback {
      width: 100%;
      height: 180px;
      background: #025628;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .mc-thumb-fallback i {
      font-size: 60px;
      color: rgba(255, 255, 255, 0.2);
    }

    .mc-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #025628;
      color: #fff;
      padding: 2px 10px;
      font-size: 11px;
      font-weight: 700;
      border-radius: 20px;
    }

    .mc-badge.inactive {
      background: #aaa;
    }

    .mc-card-body {
      padding: 20px;
      text-align: center;
    }

    .mc-card-title {
      font-size: 16px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 14px;
    }

    .mc-card-meta {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 14px;
      text-align: left;
    }

    .mc-card-meta p {
      font-size: 13px;
      color: #555;
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0;
    }

    .mc-card-meta i {
      color: #025628;
      width: 14px;
      font-size: 13px;
    }

    .mc-trainer-row {
      font-size: 12px;
      font-weight: 600;
      color: #025628;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-bottom: 14px;
    }

    .mc-progress-bg {
      background: #eee;
      height: 8px;
      border-radius: 10px;
      margin-bottom: 16px;
      overflow: hidden;
    }

    .mc-progress-fill {
      background: #025628;
      height: 100%;
    }

    .mc-btn-row {
      display: flex;
      gap: 8px;
    }

    .mc-btn {
      flex: 1;
      padding: 10px;
      border-radius: 5px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      border: none;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      transition: background 0.2s;
    }

    .mc-btn.primary {
      background: #025628;
      color: #fff;
    }

    .mc-btn.primary:hover {
      background: #013d1c;
      color: #fff;
    }

    /* ── MODALS ───────────────────────────────────────────────── */
    .mc-modal {
      display: none;
      position: fixed;
      z-index: 3000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }

    .mc-modal-content {
      background: #fff;
      margin: 4% auto;
      border-radius: 14px;
      width: 90%;
      max-width: 560px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      animation: mcModalIn 0.25s ease;
    }

    @keyframes mcModalIn {
      from {
        transform: translateY(-24px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .mc-modal-header {
      background: #fcfcfc;
      padding: 18px 24px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .mc-modal-header h3 {
      color: #025628;
      margin: 0;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .mc-close {
      font-size: 22px;
      color: #aaa;
      cursor: pointer;
      line-height: 1;
    }

    .mc-close:hover {
      color: #d9534f;
    }

    .mc-modal-body {
      padding: 24px;
      overflow-y: auto;
      flex: 1;
    }

    .mc-modal-footer {
      padding: 16px 24px;
      background: #fcfcfc;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: flex-end;
    }

    .mc-btn-close {
      background: transparent;
      border: none;
      color: #777;
      font-weight: 600;
      cursor: pointer;
      font-size: 13px;
    }

    /* ── Course Details modal rows ────────────────────────────── */
    .mc-detail-row {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #f0f4f0;
    }

    .mc-detail-row:last-child {
      border-bottom: none;
    }

    .mc-detail-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: #e8f5e9;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .mc-detail-icon i {
      font-size: 14px;
      color: #025628;
    }

    .mc-detail-label {
      font-size: 11px;
      color: #aaa;
      margin-bottom: 2px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .mc-detail-value {
      font-size: 14px;
      color: #1a1a1a;
      font-weight: 500;
    }

    /* Empty state */
    .mc-empty {
      text-align: center;
      padding: 60px 24px;
      color: #aaa;
    }

    .mc-empty i {
      font-size: 48px;
      opacity: 0.3;
      display: block;
      margin-bottom: 14px;
      color: #025628;
    }

    .mc-empty p {
      font-size: 14px;
    }
  </style>
@endsection

@section('content')
  <div class="mc-wrap">

    <div class="mc-page-title">My Courses</div>
    <div class="mc-page-sub">Your assigned training course.</div>

    @if ($course)
      <div class="mc-course-card">

        <div class="mc-badge {{ $course->status === 'active' ? '' : 'inactive' }}">
          {{ ucfirst($course->status ?? 'Active') }}
        </div>

        @if ($course->thumbnail)
          <img src="{{ asset('storage/' . $course->thumbnail) }}"
            alt="{{ $course->title }}" class="mc-thumb">
        @else
          <div class="mc-thumb-fallback">
            <i class="fa fa-book"></i>
          </div>
        @endif

        <div class="mc-card-body">
          <div class="mc-card-title">{{ $course->title }}</div>

          <div class="mc-card-meta">
            <p><i class="fa fa-calendar-day"></i> Duration:
              {{ $course->duration ?? 'TBA' }}</p>
            <p><i class="fa fa-users"></i> Slots:
              {{ $totalStudents }}/{{ $course->slots ?? '—' }}</p>
            <p><i class="fa fa-clock"></i> Schedule:
              {{ $course->schedule ?? 'TBA' }}</p>
            <p><i class="fa fa-location-dot"></i>
              {{ Str::limit($course->location ?? 'TBA', 38) }}</p>
          </div>

          <div class="mc-trainer-row">
            <i class="fa fa-chalkboard-user"></i>
            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
          </div>

          <div class="mc-progress-bg">
            <div class="mc-progress-fill"
              style="width: {{ $course->progress ?? 0 }}%"></div>
          </div>

          <div class="mc-btn-row">
            <a href="{{ route('trainer.course.preview', $course->id) }}"
              class="mc-btn primary" style="text-decoration:none;">
              Course Details
            </a>
          </div>
        </div>
      </div>
    @else
      <div class="mc-empty">
        <i class="fa fa-book-open"></i>
        <p>No course assigned to you yet.<br>Please contact the administrator.</p>
      </div>
    @endif

  </div>

  {{-- ============================================================
      MODAL 1: Course Details (read-only)
      ============================================================ --}}
  <div id="courseDetailsModal" class="mc-modal">
    <div class="mc-modal-content">
      <div class="mc-modal-header">
        <h3><i class="fa fa-book-open"></i> Course Details</h3>
        <span class="mc-close" onclick="closeCourseDetails()">&times;</span>
      </div>
      <div class="mc-modal-body">
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-bookmark"></i></div>
          <div>
            <div class="mc-detail-label">Course Title</div>
            <div class="mc-detail-value" id="cd-title"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-align-left"></i></div>
          <div style="flex:1;">
            <div class="mc-detail-label">Description</div>
            <textarea id="cd-description-input" rows="3"
              placeholder="Write a short description trainees will see"
              style="width:100%;font-size:13px;color:#333;font-family:inherit;border:1px solid #ddd;border-radius:8px;padding:8px 10px;resize:vertical;margin-top:4px;"></textarea>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-tag"></i></div>
          <div>
            <div class="mc-detail-label">Sector</div>
            <div class="mc-detail-value" id="cd-sector"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-calendar-day"></i></div>
          <div>
            <div class="mc-detail-label">Duration</div>
            <div class="mc-detail-value" id="cd-duration"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-clock"></i></div>
          <div>
            <div class="mc-detail-label">Schedule</div>
            <div class="mc-detail-value" id="cd-schedule"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-users"></i></div>
          <div>
            <div class="mc-detail-label">Slots</div>
            <div class="mc-detail-value" id="cd-slots"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-location-dot"></i></div>
          <div>
            <div class="mc-detail-label">Location</div>
            <div class="mc-detail-value" id="cd-location"></div>
          </div>
        </div>
        <div class="mc-detail-row">
          <div class="mc-detail-icon"><i class="fa fa-circle-check"></i></div>
          <div>
            <div class="mc-detail-label">Status</div>
            <div class="mc-detail-value" id="cd-status"></div>
          </div>
        </div>
      </div>
      <div class="mc-modal-footer">
        <button class="mc-btn-close" onclick="closeCourseDetails()">Close</button>
        <button onclick="saveDescription()"
          style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:700;cursor:pointer;margin-left:8px;">
          Save
        </button>
      </div>
    </div>
  </div>

  {{-- ============================================================
<<<<<<< HEAD
      MODAL: Custom Alert Card (replaces native alert())
      ============================================================ --}}
  <div id="mcAlertModal" class="mc-modal">
=======
     MODAL 2: Modules & Quizzes (same as admin)
     ============================================================ --}}
  <div id="contentModal" class="mc-modal">
    <div class="mc-modal-content wide">
      <div class="mc-modal-header">
        <h3><i class="fa fa-layer-group"></i> <span
            id="contentModalCourseTitle"></span></h3>
        <span class="mc-close" onclick="closeContentModal()">&times;</span>
      </div>
      <div class="mc-modal-body" style="padding-bottom:0;">

        {{-- Tabs --}}
        <div class="mc-tabs">
          <button class="mc-tab-btn active" id="tab-btn-modules"
            onclick="switchContentTab('modules')">
            <i class="fa fa-cubes"></i> Modules
          </button>
          <button class="mc-tab-btn" id="tab-btn-quizzes"
            onclick="switchContentTab('quizzes')">
            <i class="fa fa-clipboard-question"></i> Quizzes
          </button>
        </div>

        {{-- MODULES TAB --}}
        <div id="content-tab-modules">
          <div
            style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
            <div style="display:flex;gap:8px;">
              <input type="text" id="newModuleTitle"
                placeholder="Module title"
                style="flex:1;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;">
              <input type="text" id="newModuleDesc"
                placeholder="Description (optional)"
                style="flex:2;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;">
              <input type="number" id="newModuleUnit"
                placeholder="Unit #" min="1" value="1"
                style="width:80px;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;">
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
              <label style="font-size:12px;color:#666;white-space:nowrap;">📎 PDF
                File:</label>
              <input type="file" id="newModuleFile" accept=".pdf,.doc,.docx"
                style="flex:1;border:1px solid #ddd;border-radius:8px;padding:6px 12px;font-size:13px;font-family:inherit;background:#fff;">
              <button onclick="addModule()"
                style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 16px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;font-family:inherit;">
                <i class="fa fa-plus"></i> Add
              </button>
            </div>
          </div>
          <div id="moduleListContainer"
            style="display:flex;flex-direction:column;gap:8px;max-height:320px;overflow-y:auto;">
            <div
              style="text-align:center;color:#bbb;font-size:13px;padding:20px 0;"
              id="modulesEmptyState">
              <i class="fa fa-inbox"></i> Walang modules pa.
            </div>
          </div>
        </div>

        {{-- QUIZZES TAB --}}
        <div id="content-tab-quizzes" style="display:none;">
          <div
            style="background:#f9f9f9;border:1px solid #eee;border-radius:10px;padding:14px;margin-bottom:14px;">
            <div
              style="font-size:12px;font-weight:700;color:#025628;margin-bottom:10px;text-transform:uppercase;letter-spacing:.04em;">
              <i class="fa fa-plus-circle"></i> New Quiz
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              <input type="text" id="newQuizTitle" placeholder="Quiz title"
                style="flex:2;min-width:140px;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;">
                <select id="newQuizType"
              style="flex:1;min-width:130px;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;background:#fff;">
                <option value="regular">Regular Quiz</option>
                <option value="post_test">Post-Test</option>
              </select>
              <select id="newQuizModule"
                style="flex:1.5;min-width:130px;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;background:#fff;">
                <option value="">— Link to module (optional) —</option>
              </select>
            </div>
            <div
              style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;align-items:center;">
              <div style="flex:1;min-width:100px;">
                <label
                  style="font-size:11px;color:#888;display:block;margin-bottom:2px;">Passing
                  score (%)</label>
                <input type="number" id="newQuizPass" value="75"
                  min="1" max="100"
                  style="width:100%;border:1px solid #ddd;border-radius:8px;padding:8px 10px;font-size:13px;font-family:inherit;">
              </div>
              <div style="flex:1;min-width:100px;">
                <label
                  style="font-size:11px;color:#888;display:block;margin-bottom:2px;">Time
                  limit (mins)</label>
                <input type="number" id="newQuizTime" value="30"
                  min="1"
                  style="width:100%;border:1px solid #ddd;border-radius:8px;padding:8px 10px;font-size:13px;font-family:inherit;">
              </div>
              <button onclick="addQuiz()"
                style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;margin-top:14px;">
                <i class="fa fa-plus"></i> Add Quiz
              </button>
            </div>
          </div>
          <div id="quizListContainer"
            style="display:flex;flex-direction:column;gap:8px;max-height:100%;overflow-y:visible;">
            <div
              style="text-align:center;color:#bbb;font-size:13px;padding:20px 0;"
              id="quizzesEmptyState">
              <i class="fa fa-inbox"></i> Walang quizzes pa.
            </div>
          </div>
        </div>

      </div>
      <div class="mc-modal-footer">
        <button class="mc-btn-close"
          onclick="closeContentModal()">Close</button>
      </div>
    </div>
  </div>

  {{-- ============================================================
     MODAL: Custom Confirm Card (replaces native confirm())
     ============================================================ --}}
  <div id="mcConfirmModal" class="mc-modal">
>>>>>>> 384453d6cf3600e14902e811c88999cfb75c91ce
    <div class="mc-modal-content" style="max-width:380px;">
      <div class="mc-modal-header">
        <h3><i class="fa fa-circle-info"></i> Notice</h3>
        <span class="mc-close" onclick="closeAlertModal()">&times;</span>
      </div>
      <div class="mc-modal-body" style="text-align:center; padding:24px;">
        <p id="mcAlertMessage" style="font-size:14px; color:#333; margin:0;">
        </p>
      </div>
      <div class="mc-modal-footer" style="justify-content:center;">
        <button onclick="closeAlertModal()"
          style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 24px;font-size:13px;font-weight:700;cursor:pointer;">
          OK
        </button>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
      .getAttribute('content');

    // ── COURSE DETAILS MODAL ───────────────────────────────────────────────────
    function openCourseDetails(id, title, description, duration, slots, schedule,
      location, sector, status) {
      document.getElementById('cd-title').textContent = title;
      document.getElementById('cd-description-input').value = description || '';
      document.getElementById('cd-sector').textContent = sector || '—';
      document.getElementById('cd-duration').textContent = duration || '—';
      document.getElementById('cd-schedule').textContent = schedule || '—';
      document.getElementById('cd-slots').textContent = slots || '—';
      document.getElementById('cd-location').textContent = location || '—';
      document.getElementById('cd-status').textContent = status || '—';
      document.getElementById('courseDetailsModal').style.display = 'block';

      window._currentDetailsCourseId = id;
    }

    function saveDescription() {
      const newDescription = document.getElementById('cd-description-input')
      .value;
      const courseId = window._currentDetailsCourseId;

      fetch(`/trainer/course/${courseId}/description`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            description: newDescription
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showAlert('Description updated!');
            closeCourseDetails();
          }
        })
        .catch(() => showAlert('Something went wrong. Please try again.'));
    }

    function closeCourseDetails() {
      document.getElementById('courseDetailsModal').style.display = 'none';
    }

    function showAlert(message) {
      document.getElementById('mcAlertMessage').textContent = message;
      document.getElementById('mcAlertModal').style.display = 'block';
    }

    function closeAlertModal() {
      document.getElementById('mcAlertModal').style.display = 'none';
    }

<<<<<<< HEAD
=======
    function closeContentModal() {
      document.getElementById('contentModal').style.display = 'none';
      _contentCourseId = null;
      _contentModules = [];
      _contentQuizzes = [];
    }

    // ── TABS ───────────────────────────────────────────────────────────────────
    function switchContentTab(tab) {
      const isMod = tab === 'modules';
      document.getElementById('content-tab-modules').style.display = isMod ?
        'block' : 'none';
      document.getElementById('content-tab-quizzes').style.display = isMod ?
        'none' : 'block';
      document.getElementById('tab-btn-modules').className = 'mc-tab-btn' + (
        isMod ? ' active' : '');
      document.getElementById('tab-btn-quizzes').className = 'mc-tab-btn' + (!
        isMod ? ' active' : '');
      if (tab === 'quizzes') populateQuizModuleDropdown();
    }

    // ── FETCH ──────────────────────────────────────────────────────────────────
    function fetchCourseContent(courseId) {
      fetch(`/trainer/course/${courseId}/content`, {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(r => r.json())
        .then(data => {
          _contentModules = data.modules || [];
          _contentQuizzes = data.quizzes || [];
          renderModules();
          renderQuizzes();
        })
        .catch(() => showAlert('Hindi ma-load ang content. Subukan ulit.'));
    }

    // ── RENDER MODULES ─────────────────────────────────────────────────────────
    function renderModules() {
      const container = document.getElementById('moduleListContainer');
      const empty = document.getElementById('modulesEmptyState');
      if (!_contentModules.length) {
        if (empty) empty.style.display = 'block';
        container.innerHTML = '';
        if (empty) container.appendChild(empty);
        return;
      }
      if (empty) empty.style.display = 'none';
      container.innerHTML = _contentModules.map((m, i) => `
        <div style="display:flex;align-items:center;gap:10px;background:#fff;border:1px solid #eee;border-radius:10px;padding:10px 14px;">
            <div style="width:28px;height:28px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#025628;flex-shrink:0;">
                ${i + 1}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:700;color:#1a1a1a;">${escHtml(m.title)}</div>
                ${m.description ? `<div style="font-size:11px;color:#888;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(m.description)}</div>` : ''}
            </div>
            <button onclick="deleteModule(${m.id})"
                style="font-size:11px;padding:4px 10px;border-radius:6px;background:#FCEBEB;color:#A32D2D;border:none;cursor:pointer;font-family:inherit;font-weight:700;white-space:nowrap;">
                <i class="fa fa-trash"></i> Remove
            </button>
        </div>
    `).join('');
    }

    // ── RENDER QUIZZES ─────────────────────────────────────────────────────────
    function renderQuizzes() {
      const container = document.getElementById('quizListContainer');
      const empty = document.getElementById('quizzesEmptyState');
      if (!_contentQuizzes.length) {
        empty.style.display = 'block';
        container.innerHTML = '';
        container.appendChild(empty);
        return;
      }
      empty.style.display = 'none';
      container.innerHTML = _contentQuizzes.map(q => `
        <div style="display:flex;flex-direction:column;background:#fff;border:1px solid #eee;border-radius:10px;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;">
                <div style="width:32px;height:32px;border-radius:8px;background:#fff8e1;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">📝</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:700;color:#1a1a1a;">${escHtml(q.title)}</div>
                    <div style="font-size:11px;color:#888;">
                        ${q.module ? `<i class="fa fa-cube"></i> ${escHtml(q.module.title)} &nbsp;·&nbsp;` : ''}
                        <i class="fa fa-clock"></i> ${q.time_limit}m &nbsp;·&nbsp;
                        <i class="fa fa-star"></i> ${q.passing_score}% passing
                    </div>
                </div>
                <button onclick="toggleQuizQuestions(${q.id})"
                    style="font-size:11px;padding:4px 10px;border-radius:6px;background:#e8f5e9;color:#025628;border:none;cursor:pointer;font-family:inherit;font-weight:700;white-space:nowrap;">
                    <i class="fa fa-list"></i> Questions
                </button>
                <button onclick="deleteQuiz(${q.id})"
                    style="font-size:11px;padding:4px 10px;border-radius:6px;background:#FCEBEB;color:#A32D2D;border:none;cursor:pointer;font-family:inherit;font-weight:700;white-space:nowrap;">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </div>
            <div id="quiz-questions-${q.id}" style="display:none;border-top:1px solid #eee;padding:12px 14px;background:#fafafa;max-height:340px;overflow-y:auto;">
                <div id="qlist-${q.id}" style="display:flex;flex-direction:column;gap:6px;margin-bottom:10px;"></div>
                <div style="background:#fff;border:1px solid #eee;border-radius:8px;padding:12px;">
                    <div style="font-size:12px;font-weight:700;color:#025628;margin-bottom:8px;text-transform:uppercase;">
                        <i class="fa fa-plus"></i> Add Question
                    </div>
                    <textarea id="qtext-${q.id}" placeholder="Question text..." rows="2"
                        style="width:100%;border:1px solid #ddd;border-radius:8px;padding:8px;font-size:13px;font-family:inherit;margin-bottom:8px;resize:vertical;"></textarea>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">
                        <input type="text" id="qa-${q.id}" placeholder="A. Choice A" style="border:1px solid #ddd;border-radius:8px;padding:7px 10px;font-size:13px;font-family:inherit;">
                        <input type="text" id="qb-${q.id}" placeholder="B. Choice B" style="border:1px solid #ddd;border-radius:8px;padding:7px 10px;font-size:13px;font-family:inherit;">
                        <input type="text" id="qc-${q.id}" placeholder="C. Choice C" style="border:1px solid #ddd;border-radius:8px;padding:7px 10px;font-size:13px;font-family:inherit;">
                        <input type="text" id="qd-${q.id}" placeholder="D. Choice D" style="border:1px solid #ddd;border-radius:8px;padding:7px 10px;font-size:13px;font-family:inherit;">
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <label style="font-size:12px;color:#666;">Correct answer:</label>
                        <select id="qans-${q.id}" style="border:1px solid #ddd;border-radius:8px;padding:6px 10px;font-size:13px;font-family:inherit;background:#fff;">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                        <button onclick="addQuestion(${q.id})"
                            style="background:#025628;color:#fff;border:none;border-radius:8px;padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;font-family:inherit;margin-left:auto;">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    }

    // ── ADD MODULE ─────────────────────────────────────────────────────────────
      function addModule() {
        const title = document.getElementById('newModuleTitle').value.trim();
        const desc = document.getElementById('newModuleDesc').value.trim();
        const unit = document.getElementById('newModuleUnit').value || 1;
        const file = document.getElementById('newModuleFile').files[0];
        if (!title) {
          showAlert('Lagyan ng title ang module.');
          return;
        }
        if (!file) {
          showAlert('Kailangan mag-upload ng PDF file.');
          return;
        }
        const formData = new FormData();
        formData.append('course_id', _contentCourseId);
        formData.append('title', title);
        formData.append('description', desc);
        formData.append('unit_number', unit);
        if (file) formData.append('file', file);
      fetch('/trainer/module', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            _contentModules.push(data.module);
            document.getElementById('newModuleTitle').value = '';
            document.getElementById('newModuleDesc').value = '';
            document.getElementById('newModuleFile').value = '';
            renderModules();
            populateQuizModuleDropdown();
          }
        })
        .catch(err => {
          console.error('Add module error:', err);
         showAlert('May error. Subukan ulit.');
        });
    }

    // ── DELETE MODULE ──────────────────────────────────────────────────────────
    function deleteModule(id) {
      showConfirm('I-remove ang module na ito?', function() {
        fetch(`/trainer/module/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              _method: 'DELETE'
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              _contentModules = _contentModules.filter(m => m.id !== id);
              renderModules();
              populateQuizModuleDropdown();
            }
          })
          .catch(() => showAlert('May error. Subukan ulit.'));
      });
    }

    // ── ADD QUIZ ───────────────────────────────────────────────────────────────
    function addQuiz() {
      const title = document.getElementById('newQuizTitle').value.trim();
      const quizType = document.getElementById('newQuizType').value;
      const moduleId = document.getElementById('newQuizModule').value || null;
      const passing = parseInt(document.getElementById('newQuizPass').value);
      const time = parseInt(document.getElementById('newQuizTime').value);
      if (!title) {
        showAlert('Lagyan ng title ang quiz.');
        return;
      }
      fetch('/trainer/quiz', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            course_id: _contentCourseId,
            module_id: moduleId,
            title,
            quiz_type: quizType,
            passing_score: passing,
            time_limit: time
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            _contentQuizzes.push(data.quiz);
            document.getElementById('newQuizTitle').value = '';
            renderQuizzes();
          }
        })
        .catch(() => showAlert('May error. Subukan ulit.'));
    }

    // ── DELETE QUIZ ────────────────────────────────────────────────────────────
    function deleteQuiz(id) {
      showConfirm('I-remove ang quiz na ito?', function() {
        fetch(`/trainer/quiz/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              _method: 'DELETE'
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              _contentQuizzes = _contentQuizzes.filter(q => q.id !== id);
              renderQuizzes();
            }
          })
          .catch(() => showAlert('May error. Subukan ulit.'));
      });
    }

    // ── QUIZ QUESTIONS ─────────────────────────────────────────────────────────
    function toggleQuizQuestions(quizId) {
      const panel = document.getElementById(`quiz-questions-${quizId}`);
      const isOpen = panel.style.display !== 'none';
      panel.style.display = isOpen ? 'none' : 'block';
      if (!isOpen) loadQuizQuestions(quizId);
    }

    function loadQuizQuestions(quizId) {
      fetch(`/trainer/quiz/${quizId}/questions`, {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(r => r.json())
        .then(data => renderQuizQuestions(quizId, data.questions || []))
        .catch(() => showAlert('Hindi ma-load ang questions.'));
    }

    function renderQuizQuestions(quizId, questions) {
      const container = document.getElementById(`qlist-${quizId}`);
      if (!questions.length) {
        container.innerHTML =
          '<div style="font-size:12px;color:#aaa;text-align:center;padding:8px;">Walang questions pa.</div>';
        return;
      }
      container.innerHTML = questions.map((q, i) => `
        <div style="display:flex;align-items:flex-start;gap:8px;background:#fff;border:1px solid #eee;border-radius:8px;padding:8px 12px;">
            <div style="width:22px;height:22px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#025628;flex-shrink:0;margin-top:1px;">${i+1}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:600;color:#1a1a1a;margin-bottom:4px;">${escHtml(q.question)}</div>
                <div style="font-size:11px;color:#555;display:grid;grid-template-columns:1fr 1fr;gap:2px;">
                    <span ${q.correct_answer==='a' ? 'style="color:#025628;font-weight:700;"' : ''}>A. ${escHtml(q.choice_a)}</span>
                    <span ${q.correct_answer==='b' ? 'style="color:#025628;font-weight:700;"' : ''}>B. ${escHtml(q.choice_b)}</span>
                    <span ${q.correct_answer==='c' ? 'style="color:#025628;font-weight:700;"' : ''}>C. ${escHtml(q.choice_c)}</span>
                    <span ${q.correct_answer==='d' ? 'style="color:#025628;font-weight:700;"' : ''}>D. ${escHtml(q.choice_d)}</span>
                </div>
            </div>
            <button onclick="deleteQuestion(${q.id}, ${quizId})"
                style="font-size:11px;padding:3px 8px;border-radius:6px;background:#FCEBEB;color:#A32D2D;border:none;cursor:pointer;font-family:inherit;font-weight:700;flex-shrink:0;">✕</button>
        </div>
    `).join('');
    }

    function addQuestion(quizId) {
      const question = document.getElementById(`qtext-${quizId}`).value.trim();
      const a = document.getElementById(`qa-${quizId}`).value.trim();
      const b = document.getElementById(`qb-${quizId}`).value.trim();
      const c = document.getElementById(`qc-${quizId}`).value.trim();
      const d = document.getElementById(`qd-${quizId}`).value.trim();
      const ans = document.getElementById(`qans-${quizId}`).value;
      if (!question || !a || !b || !c || !d) {
        showAlert('Punan ang lahat ng fields.');
        return;
      }
      fetch('/trainer/quiz-question', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            quiz_id: quizId,
            question,
            choice_a: a,
            choice_b: b,
            choice_c: c,
            choice_d: d,
            correct_answer: ans
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            ['qtext', 'qa', 'qb', 'qc', 'qd'].forEach(id => document
              .getElementById(`${id}-${quizId}`).value = '');
            loadQuizQuestions(quizId);
          }
        })
        .catch(() => showAlert('May error. Subukan ulit.'));
    }

    function deleteQuestion(id, quizId) {
      showConfirm('I-remove ang question na ito?', function() {
        fetch(`/trainer/quiz-question/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              _method: 'DELETE'
            })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) loadQuizQuestions(quizId);
          })
          .catch(() => showAlert('May error. Subukan ulit.'));
      });
    }

    // ── HELPERS ────────────────────────────────────────────────────────────────
    function populateQuizModuleDropdown() {
      const sel = document.getElementById('newQuizModule');
      sel.innerHTML = '<option value="">— Link to module (optional) —</option>';
      _contentModules.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.title;
        sel.appendChild(opt);
      });
    }

    function escHtml(str) {
      const div = document.createElement('div');
      div.appendChild(document.createTextNode(str || ''));
      return div.innerHTML;
    }

>>>>>>> 384453d6cf3600e14902e811c88999cfb75c91ce
    // ── CLOSE ON OUTSIDE CLICK ─────────────────────────────────────────────────
    window.addEventListener('click', function(e) {
      if (e.target.id === 'courseDetailsModal') closeCourseDetails();
    });
  </script>
@endsection
