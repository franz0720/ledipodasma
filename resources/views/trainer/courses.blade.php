@extends('trainer.layout')

@section('title', 'My Courses')

@section('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --mc-green: #075b35;
      --mc-green-dark: #043f26;
      --mc-green-soft: #eaf5ee;
      --mc-yellow: #e1dc36;
      --mc-ink: #183329;
      --mc-muted: #6b7c73;
      --mc-border: #e2ebe5;
      --mc-surface: #fff;
    }

    .mc-wrap {
      padding: 28px;
      color: var(--mc-ink);
    }

    .mc-heading {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 16px;
      margin-bottom: 24px;
    }

    .mc-page-title {
      font-size: 24px;
      font-weight: 800;
      color: var(--mc-green);
      margin: 0 0 5px;
    }

    .mc-page-sub {
      font-size: 13px;
      color: var(--mc-muted);
      margin: 0;
    }

    .mc-course-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 340px));
      gap: 22px;
      align-items: stretch;
    }

    .mc-course-card {
      background: var(--mc-surface);
      border: 1px solid var(--mc-border);
      border-radius: 16px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 8px 24px rgba(4, 63, 38, .06);
      transition: transform .2s, box-shadow .2s;
    }

    .mc-course-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 30px rgba(4, 63, 38, .10);
    }

    .mc-thumb-wrap {
      height: 180px;
      background: var(--mc-green-soft);
      position: relative;
    }

    .mc-thumb {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .mc-thumb-fallback {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--mc-green), var(--mc-green-dark));
      color: rgba(255, 255, 255, .65);
      font-size: 54px;
    }

    .mc-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: var(--mc-green);
      color: #fff;
      padding: 5px 11px;
      font-size: 11px;
      font-weight: 800;
      border-radius: 999px;
      text-transform: capitalize;
    }

    .mc-badge.inactive {
      background: #68756e;
    }

    .mc-card-body {
      padding: 20px;
    }

    .mc-card-title {
      font-size: 17px;
      font-weight: 800;
      color: var(--mc-ink);
      margin: 0 0 5px;
      line-height: 1.35;
      overflow-wrap: anywhere;
    }

    .mc-course-code {
      font-size: 11px;
      color: var(--mc-muted);
      margin-bottom: 16px;
    }

    .mc-card-meta {
      display: flex;
      flex-direction: column;
      gap: 11px;
      margin-bottom: 16px;
    }

    .mc-meta-row {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 12px;
      line-height: 1.5;
      color: #52645a;
      margin: 0;
    }

    .mc-meta-row>i {
      color: var(--mc-green);
      width: 16px;
      text-align: center;
      margin-top: 2px;
      flex-shrink: 0;
    }

    .mc-meta-row span {
      min-width: 0;
      overflow-wrap: anywhere;
    }

    .mc-meta-label {
      display: block;
      color: var(--mc-muted);
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: 2px;
    }

    .mc-day-list {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: 5px;
    }

    .mc-day-chip {
      background: var(--mc-green-soft);
      color: var(--mc-green);
      border: 1px solid #d7e9dc;
      border-radius: 6px;
      padding: 3px 7px;
      font-size: 10px;
      font-weight: 800;
    }

    .mc-trainer-row {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 12px;
      background: #f7faf7;
      border-radius: 10px;
      color: var(--mc-green);
      font-size: 12px;
      font-weight: 700;
      margin: 16px 0;
    }

    .mc-progress-head {
      display: flex;
      justify-content: space-between;
      gap: 8px;
      font-size: 11px;
      color: var(--mc-muted);
      margin-bottom: 7px;
    }

    .mc-progress-bg {
      background: #edf1ed;
      height: 8px;
      border-radius: 99px;
      overflow: hidden;
      margin-bottom: 18px;
    }

    .mc-progress-fill {
      height: 100%;
      background: var(--mc-yellow);
      border-radius: 99px;
      transition: width .25s ease;
    }

    .mc-btn-row {
      display: flex;
      gap: 8px;
    }

    .mc-btn {
      flex: 1;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      gap: 7px;
      padding: 10px 12px;
      border-radius: 9px;
      font-size: 12px;
      font-weight: 800;
      cursor: pointer;
      border: 1px solid transparent;
      text-decoration: none;
      transition: background .2s, color .2s;
    }

    .mc-btn.primary {
      background: var(--mc-green);
      color: #fff;
    }

    .mc-btn.primary:hover {
      background: var(--mc-green-dark);
      color: #fff;
    }

    .mc-btn.secondary {
      background: #fff;
      color: var(--mc-green);
      border-color: #cfe0d3;
    }

    .mc-btn.secondary:hover {
      background: var(--mc-green-soft);
    }

    .mc-empty {
      text-align: center;
      padding: 64px 24px;
      color: var(--mc-muted);
      background: #fff;
      border: 1px dashed #cbd9ce;
      border-radius: 16px;
    }

    .mc-empty i {
      font-size: 42px;
      color: #9bb8a3;
      display: block;
      margin-bottom: 14px;
    }

    .mc-empty h3 {
      color: var(--mc-ink);
      margin: 0 0 7px;
      font-size: 17px;
    }

    .mc-empty p {
      margin: 0;
      font-size: 13px;
    }

    .mc-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 3000;
      background: rgba(9, 27, 17, .54);
      backdrop-filter: blur(4px);
      overflow-y: auto;
      padding: 24px 12px;
    }

    .mc-modal-content {
      background: #fff;
      margin: 3vh auto;
      border-radius: 16px;
      width: 100%;
      max-width: 600px;
      overflow: hidden;
      box-shadow: 0 22px 60px rgba(0, 0, 0, .22);
      animation: mcModalIn .2s ease;
    }

    .mc-modal-content.wide {
      max-width: 850px;
    }

    @keyframes mcModalIn {
      from {
        transform: translateY(-12px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .mc-modal-header {
      background: #fbfdfb;
      padding: 18px 22px;
      border-bottom: 1px solid var(--mc-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .mc-modal-header h3 {
      color: var(--mc-green);
      margin: 0;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 9px;
    }

    .mc-close {
      border: 0;
      background: transparent;
      font-size: 26px;
      color: #8a9990;
      cursor: pointer;
      line-height: 1;
    }

    .mc-close:hover {
      color: #b42318;
    }

    .mc-modal-body {
      padding: 22px;
      max-height: 70vh;
      overflow-y: auto;
    }

    .mc-modal-footer {
      padding: 14px 22px;
      background: #fbfdfb;
      border-top: 1px solid var(--mc-border);
      display: flex;
      justify-content: flex-end;
      gap: 8px;
    }

    .mc-btn-close {
      background: transparent;
      border: 1px solid var(--mc-border);
      color: #5e7065;
      font-weight: 700;
      cursor: pointer;
      font-size: 12px;
      padding: 9px 16px;
      border-radius: 8px;
    }

    .mc-detail-row {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 13px 0;
      border-bottom: 1px solid #eef3ef;
    }

    .mc-detail-row:last-child {
      border-bottom: 0;
    }

    .mc-detail-icon {
      width: 35px;
      height: 35px;
      border-radius: 10px;
      background: var(--mc-green-soft);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      color: var(--mc-green);
    }

    .mc-detail-label {
      font-size: 10px;
      color: var(--mc-muted);
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: .06em;
      font-weight: 800;
    }

    .mc-detail-value {
      font-size: 13px;
      color: var(--mc-ink);
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .mc-input,
    .mc-textarea,
    .mc-select {
      width: 100%;
      border: 1px solid #d9e4dc;
      border-radius: 8px;
      padding: 10px 11px;
      font: inherit;
      font-size: 12px;
      background: #fff;
      color: var(--mc-ink);
    }

    .mc-input:focus,
    .mc-textarea:focus,
    .mc-select:focus {
      outline: 2px solid #c7e3cf;
      border-color: var(--mc-green);
    }

    .mc-tabs {
      display: flex;
      gap: 6px;
      border-bottom: 1px solid var(--mc-border);
      margin-bottom: 16px;
    }

    .mc-tab-btn {
      border: 0;
      background: transparent;
      padding: 10px 13px;
      color: var(--mc-muted);
      font-weight: 800;
      font-size: 12px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
    }

    .mc-tab-btn.active {
      color: var(--mc-green);
      border-bottom-color: var(--mc-green);
    }

    .mc-panel {
      background: #f8fbf8;
      border: 1px solid var(--mc-border);
      border-radius: 12px;
      padding: 14px;
      margin-bottom: 14px;
    }

    .mc-panel-title {
      font-size: 12px;
      font-weight: 800;
      color: var(--mc-green);
      margin: 0 0 10px;
    }

    .mc-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 14px;
    }

    .mc-form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 8px;
    }

    .mc-alert-message {
      font-size: 13px;
      color: #35483c;
      line-height: 1.6;
      margin: 0;
    }

    @media (max-width:700px) {
      .mc-wrap {
        padding: 18px 14px;
      }

      .mc-heading {
        align-items: flex-start;
        flex-direction: column;
      }

      .mc-course-grid {
        grid-template-columns: 1fr;
      }

      .mc-course-card {
        max-width: none;
      }

      .mc-modal-body {
        padding: 16px;
      }

      .mc-form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section('content')
  @php
    $trainingDays = $course->training_days ?? [];
    if (is_string($trainingDays)) {
        $trainingDays = json_decode($trainingDays, true) ?? [];
    }
    if (!is_array($trainingDays)) {
        $trainingDays = [];
    }
    $trainingDays = array_values(
        array_filter(
            $trainingDays,
            fn($day) => is_string($day) && trim($day) !== '',
        ),
    );

    $progress = (int) ($course->progress ?? 0);
    $progress = max(0, min(100, $progress));

    $trainer = Auth::user();
    $trainerName = trim(
        ($trainer->firstname ?? '') . ' ' . ($trainer->lastname ?? ''),
    );
    if ($trainerName === '') {
        $trainerName = $trainer->name ?? 'Assigned trainer';
    }

    $startDate = $course?->start_date
        ? \Carbon\Carbon::parse($course->start_date)->format('M d, Y')
        : null;
    $endDate = $course?->end_date
        ? \Carbon\Carbon::parse($course->end_date)->format('M d, Y')
        : null;
    $scheduleDate =
        $startDate && $endDate
            ? $startDate . ' – ' . $endDate
            : ($startDate ?:
            ($endDate ?:
            'Schedule to be announced'));
    $startTime = $course?->start_time
        ? \Carbon\Carbon::parse($course->start_time)->format('g:i A')
        : null;
    $endTime = $course?->end_time
        ? \Carbon\Carbon::parse($course->end_time)->format('g:i A')
        : null;
    $timeRange =
        $startTime && $endTime
            ? $startTime . ' – ' . $endTime
            : 'Time to be announced';
    // Resolve the assigned facility ID to its related facility name.
    $facilityId = $course?->facility_id;
    $facilityName = $course?->facility?->name ?? null;
    $facilityLabel =
        $facilityName ?:
        ($facilityId
            ? 'Facility name unavailable'
            : 'No facility assigned');
    $durationLabel =
        $course?->duration ?:
        ($course?->duration_days
            ? $course->duration_days . ' days'
            : 'To be announced');
  @endphp

  <div class="mc-wrap">
    <div class="mc-heading">
      <div>
        <h1 class="mc-page-title">My Courses</h1>
        <p class="mc-page-sub">Manage your assigned training course and its
          learning content.</p>
      </div>
    </div>

    @if ($course)
      <div class="mc-course-grid">
        <article class="mc-course-card">
          <div class="mc-thumb-wrap">
            @php
              // Prefer the course banner image; use the thumbnail only as a fallback.
              $courseImage = $course->banner_image ?: $course->thumbnail;
              $courseImageUrl = $courseImage
                  ? (str_starts_with($courseImage, 'storage/')
                      ? asset($courseImage)
                      : asset('storage/' . ltrim($courseImage, '/')))
                  : null;
            @endphp

            @if ($courseImageUrl)
              <img src="{{ $courseImageUrl }}" alt="{{ $course->title }}"
                class="mc-thumb"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="mc-thumb-fallback" style="display:none;"><i
                  class="fa fa-book-open"></i></div>
            @else
              <div class="mc-thumb-fallback"><i class="fa fa-book-open"></i></div>
            @endif
            <span
              class="mc-badge {{ ($course->status ?? 'active') === 'active' ? '' : 'inactive' }}">{{ ucfirst($course->status ?? 'active') }}</span>
          </div>

          <div class="mc-card-body">
            <h2 class="mc-card-title">{{ $course->title }}</h2>
            <div class="mc-course-code">
              {{ $course->course_code ?: 'Course code not set' }}</div>

            <div class="mc-card-meta">
              <p class="mc-meta-row"><i
                  class="fa fa-hourglass-half"></i><span><span
                    class="mc-meta-label">Duration</span>{{ $durationLabel }}</span>
              </p>
              <p class="mc-meta-row"><i class="fa fa-users"></i><span><span
                    class="mc-meta-label">Enrolled trainees /
                    slots</span>{{ $totalStudents ?? 0 }} /
                  {{ $course->slots ?? '—' }}</span></p>
              <p class="mc-meta-row"><i
                  class="fa fa-calendar-days"></i><span><span
                    class="mc-meta-label">Training
                    dates</span>{{ $scheduleDate }}</span></p>
              <div class="mc-meta-row"><i
                  class="fa fa-calendar-week"></i><span><span
                    class="mc-meta-label">Training days</span>
                  @if (count($trainingDays))
                    <span class="mc-day-list">
                      @foreach ($trainingDays as $day)
                        <span class="mc-day-chip">{{ $day }}</span>
                      @endforeach
                    </span>
                  @else
                    To be announced
                  @endif
                </span></div>
              <p class="mc-meta-row"><i class="fa fa-clock"></i><span><span
                    class="mc-meta-label">Training
                    hours</span>{{ $timeRange }}</span></p>
              <p class="mc-meta-row"><i class="fa fa-location-dot"></i><span><span
                    class="mc-meta-label">Facility</span>{{ $facilityLabel }}</span>
              </p>
            </div>

            <div class="mc-trainer-row"><i
                class="fa fa-chalkboard-user"></i><span>{{ $trainerName }}</span>
            </div>

            <div class="mc-progress-head"><span>Course
                progress</span><strong>{{ $progress }}%</strong></div>
            <div class="mc-progress-bg" role="progressbar"
              aria-label="Course progress" aria-valuemin="0" aria-valuemax="100"
              aria-valuenow="{{ $progress }}">
              <div class="mc-progress-fill" style="width:{{ $progress }}%">
              </div>
            </div>

            <div class="mc-btn-row">
              <a href="{{ route('trainer.course.preview', $course->id) }}"
                class="mc-btn primary">
                <i class="fa fa-arrow-up-right-from-square"></i> Course Details
              </a>
            </div>
          </div>
        </article>
      </div>
    @else
      <div class="mc-empty">
        <i class="fa fa-book-open"></i>
        <h3>No course assigned yet</h3>
        <p>Please contact the administrator if you believe a course should be
          assigned to you.</p>
      </div>
    @endif
  </div>

  @if ($course)
    <div id="courseDetailsModal" class="mc-modal" aria-hidden="true">
      <div class="mc-modal-content" role="dialog" aria-modal="true"
        aria-labelledby="cd-modal-title">
        <div class="mc-modal-header">
          <h3 id="cd-modal-title"><i class="fa fa-book-open"></i> Course Details
          </h3>
          <button type="button" class="mc-close" onclick="closeCourseDetails()"
            aria-label="Close">&times;</button>
        </div>
        <div class="mc-modal-body">
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-bookmark"></i></div>
            <div>
              <div class="mc-detail-label">Course title</div>
              <div class="mc-detail-value">{{ $course->title }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-barcode"></i></div>
            <div>
              <div class="mc-detail-label">Course code</div>
              <div class="mc-detail-value">{{ $course->course_code ?: '—' }}
              </div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-align-left"></i></div>
            <div style="flex:1;">
              <div class="mc-detail-label">Description</div>
              <textarea id="cd-description-input" class="mc-textarea" rows="4"
                placeholder="Write a short description trainees will see">{{ $course->description }}</textarea>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-tag"></i></div>
            <div>
              <div class="mc-detail-label">Sector</div>
              <div class="mc-detail-value">{{ $course->sector ?: '—' }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-hourglass-half"></i>
            </div>
            <div>
              <div class="mc-detail-label">Duration</div>
              <div class="mc-detail-value">{{ $durationLabel }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-calendar"></i></div>
            <div>
              <div class="mc-detail-label">Training dates</div>
              <div class="mc-detail-value">{{ $scheduleDate }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-calendar-week"></i>
            </div>
            <div>
              <div class="mc-detail-label">Training days</div>
              <div class="mc-detail-value">
                {{ count($trainingDays) ? implode(', ', $trainingDays) : 'To be announced' }}
              </div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-clock"></i></div>
            <div>
              <div class="mc-detail-label">Training hours</div>
              <div class="mc-detail-value">{{ $timeRange }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-location-dot"></i></div>
            <div>
              <div class="mc-detail-label">Facility</div>
              <div class="mc-detail-value">{{ $facilityLabel }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-users"></i></div>
            <div>
              <div class="mc-detail-label">Available slots</div>
              <div class="mc-detail-value">{{ $course->slots ?? '—' }}</div>
            </div>
          </div>
          <div class="mc-detail-row">
            <div class="mc-detail-icon"><i class="fa fa-circle-check"></i></div>
            <div>
              <div class="mc-detail-label">Status</div>
              <div class="mc-detail-value">
                {{ ucfirst($course->status ?? 'active') }}</div>
            </div>
          </div>
        </div>
        <div class="mc-modal-footer">
          <button type="button" class="mc-btn-close"
            onclick="closeCourseDetails()">Close</button>
          <button type="button" class="mc-btn primary" style="flex:0 0 auto;"
            onclick="saveDescription()"><i class="fa fa-floppy-disk"></i> Save
            description</button>
        </div>
      </div>
    </div>

    <div id="contentModal" class="mc-modal" aria-hidden="true">
      <div class="mc-modal-content wide" role="dialog" aria-modal="true"
        aria-labelledby="content-modal-title">
        <div class="mc-modal-header">
          <h3 id="content-modal-title"><i class="fa fa-folder-open"></i> Manage
            Course Content</h3>
          <button type="button" class="mc-close" onclick="closeContentModal()"
            aria-label="Close">&times;</button>
        </div>
        <div class="mc-modal-body">
          <div class="mc-tabs">
            <button type="button" id="tab-btn-modules"
              class="mc-tab-btn active" onclick="switchContentTab('modules')"><i
                class="fa fa-cube"></i> Modules</button>
            <button type="button" id="tab-btn-quizzes" class="mc-tab-btn"
              onclick="switchContentTab('quizzes')"><i
                class="fa fa-clipboard-question"></i> Quizzes</button>
          </div>

          <div id="content-tab-modules">
            <div id="moduleListContainer" class="mc-list">
              <div id="modulesEmptyState" class="mc-empty"
                style="padding:24px;">
                <p>No modules added yet.</p>
              </div>
            </div>
            <div class="mc-panel">
              <h4 class="mc-panel-title"><i class="fa fa-plus"></i> Add module
              </h4>
              <div class="mc-form-grid">
                <input id="newModuleTitle" class="mc-input" type="text"
                  placeholder="Module title" maxlength="255">
                <input id="newModuleUnit" class="mc-input" type="number"
                  min="1" value="1" placeholder="Unit number">
              </div>
              <textarea id="newModuleDesc" class="mc-textarea" rows="2"
                style="margin-top:8px;" placeholder="Module description (optional)"></textarea>
              <label
                style="display:block;font-size:11px;font-weight:800;color:var(--mc-muted);margin:10px 0 5px;">Module
                PDF</label>
              <input id="newModuleFile" class="mc-input" type="file"
                accept="application/pdf,.pdf">
              <button type="button" class="mc-btn primary"
                style="margin-top:10px;flex:0 0 auto;" onclick="addModule()"><i
                  class="fa fa-plus"></i> Add module</button>
            </div>
          </div>

          <div id="content-tab-quizzes" style="display:none;">
            <div id="quizListContainer" class="mc-list">
              <div id="quizzesEmptyState" class="mc-empty"
                style="padding:24px;">
                <p>No quizzes added yet.</p>
              </div>
            </div>
            <div class="mc-panel">
              <h4 class="mc-panel-title"><i class="fa fa-plus"></i> Create quiz
              </h4>
              <input id="newQuizTitle" class="mc-input" type="text"
                maxlength="255" placeholder="Quiz title">
              <div class="mc-form-grid" style="margin-top:8px;">
                <select id="newQuizType" class="mc-select">
                  <option value="multiple_choice">Multiple choice</option>
                  <option value="true_false">True or false</option>
                </select>
                <select id="newQuizModule" class="mc-select">
                  <option value="">Link to module (optional)</option>
                </select>
                <input id="newQuizPass" class="mc-input" type="number"
                  min="0" max="100" value="75"
                  placeholder="Passing score (%)">
                <input id="newQuizTime" class="mc-input" type="number"
                  min="1" value="30"
                  placeholder="Time limit (minutes)">
              </div>
              <button type="button" class="mc-btn primary"
                style="margin-top:10px;flex:0 0 auto;" onclick="addQuiz()"><i
                  class="fa fa-plus"></i> Create quiz</button>
            </div>
          </div>
        </div>
        <div class="mc-modal-footer"><button type="button"
            class="mc-btn-close" onclick="closeContentModal()">Close</button>
        </div>
      </div>
    </div>
  @endif

  <div id="mcAlertModal" class="mc-modal" aria-hidden="true">
    <div class="mc-modal-content" style="max-width:380px;" role="dialog"
      aria-modal="true" aria-labelledby="mc-alert-title">
      <div class="mc-modal-header">
        <h3 id="mc-alert-title"><i class="fa fa-circle-info"></i> Notice</h3>
        <button type="button" class="mc-close" onclick="closeAlertModal()"
          aria-label="Close">&times;</button>
      </div>
      <div class="mc-modal-body">
        <p id="mcAlertMessage" class="mc-alert-message"></p>
      </div>
      <div class="mc-modal-footer"><button type="button"
          class="mc-btn primary" style="flex:0 0 auto;"
          onclick="closeAlertModal()">OK</button></div>
    </div>
  </div>

  <div id="mcConfirmModal" class="mc-modal" aria-hidden="true">
    <div class="mc-modal-content" style="max-width:380px;" role="dialog"
      aria-modal="true" aria-labelledby="mc-confirm-title">
      <div class="mc-modal-header">
        <h3 id="mc-confirm-title"><i class="fa fa-circle-question"></i> Confirm
          action</h3><button type="button" class="mc-close"
          onclick="closeConfirm(false)" aria-label="Close">&times;</button>
      </div>
      <div class="mc-modal-body">
        <p id="mcConfirmMessage" class="mc-alert-message"></p>
      </div>
      <div class="mc-modal-footer"><button type="button" class="mc-btn-close"
          onclick="closeConfirm(false)">Cancel</button><button type="button"
          class="mc-btn primary" style="flex:0 0 auto;"
          onclick="closeConfirm(true)">Confirm</button></div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    (() => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || '';
      let contentCourseId = null;
      let contentModules = [];
      let contentQuizzes = [];
      let confirmCallback = null;

      const byId = (id) => document.getElementById(id);
      const safeJson = async (response) => {
        let data = {};
        try {
          data = await response.json();
        } catch (_) {}
        if (!response.ok) throw new Error(data.message ||
          `Request failed (${response.status})`);
        return data;
      };
      const requestHeaders = {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      };

      function showModal(id) {
        const modal = byId(id);
        if (modal) {
          modal.style.display = 'block';
          modal.setAttribute('aria-hidden', 'false');
        }
      }

      function hideModal(id) {
        const modal = byId(id);
        if (modal) {
          modal.style.display = 'none';
          modal.setAttribute('aria-hidden', 'true');
        }
      }

      window.openCourseDetails = function() {
        showModal('courseDetailsModal');
      };
      window.closeCourseDetails = function() {
        hideModal('courseDetailsModal');
      };
      window.showAlert = function(message) {
        byId('mcAlertMessage').textContent = message ||
          'Something went wrong.';
        showModal('mcAlertModal');
      };
      window.closeAlertModal = function() {
        hideModal('mcAlertModal');
      };

      window.saveDescription = async function() {
        const description = byId('cd-description-input')?.value ?? '';
        const courseId = @json($course->id ?? null);
        if (!courseId) return showAlert('No course is selected.');
        const button = document.querySelector(
          '#courseDetailsModal .mc-modal-footer .mc-btn.primary');
        if (button) {
          button.disabled = true;
          button.style.opacity = '.65';
        }
        try {
          const response = await fetch(
            `/trainer/course/${courseId}/description`, {
              method: 'POST',
              headers: {
                ...requestHeaders,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                description
              })
            });
          const data = await safeJson(response);
          if (data.success) {
            closeCourseDetails();
            showAlert(data.message ||
              'Course description updated successfully.');
          } else {
            showAlert(data.message ||
            'The description could not be saved.');
          }
        } catch (error) {
          showAlert(error.message ||
            'Something went wrong while saving the description.');
        } finally {
          if (button) {
            button.disabled = false;
            button.style.opacity = '1';
          }
        }
      };

      window.openContentModal = function(courseId) {
        contentCourseId = courseId;
        contentModules = [];
        contentQuizzes = [];
        showModal('contentModal');
        fetchCourseContent(courseId);
      };
      window.closeContentModal = function() {
        hideModal('contentModal');
        contentCourseId = null;
        contentModules = [];
        contentQuizzes = [];
      };
      window.switchContentTab = function(tab) {
        const modulesTab = tab === 'modules';
        byId('content-tab-modules').style.display = modulesTab ? 'block' :
          'none';
        byId('content-tab-quizzes').style.display = modulesTab ? 'none' :
          'block';
        byId('tab-btn-modules').classList.toggle('active', modulesTab);
        byId('tab-btn-quizzes').classList.toggle('active', !modulesTab);
        if (!modulesTab) populateQuizModuleDropdown();
      };

      async function fetchCourseContent(courseId) {
        try {
          const response = await fetch(
          `/trainer/course/${courseId}/content`, {
            headers: requestHeaders
          });
          const data = await safeJson(response);
          contentModules = Array.isArray(data.modules) ? data.modules : [];
          contentQuizzes = Array.isArray(data.quizzes) ? data.quizzes : [];
          renderModules();
          renderQuizzes();
          populateQuizModuleDropdown();
        } catch (error) {
          showAlert(error.message ||
            'Could not load course content. Please try again.');
        }
      }

      function renderModules() {
        const container = byId('moduleListContainer');
        const empty = byId('modulesEmptyState');
        container.innerHTML = '';
        if (!contentModules.length) {
          empty.style.display = 'block';
          container.appendChild(empty);
          return;
        }
        empty.style.display = 'none';
        contentModules.forEach((module, index) => {
          const row = document.createElement('div');
          row.className = 'mc-panel';
          row.style.cssText =
            'display:flex;align-items:center;gap:10px;margin:0;background:#fff;';
          const number = document.createElement('div');
          number.style.cssText =
            'width:30px;height:30px;border-radius:9px;background:#eaf5ee;color:#075b35;display:grid;place-items:center;font-size:12px;font-weight:800;flex-shrink:0;';
          number.textContent = index + 1;
          const info = document.createElement('div');
          info.style.cssText = 'flex:1;min-width:0;';
          const title = document.createElement('div');
          title.style.cssText =
            'font-size:13px;font-weight:800;color:#183329;overflow-wrap:anywhere;';
          title.textContent = module.title || 'Untitled module';
          info.appendChild(title);
          if (module.description) {
            const description = document.createElement('div');
            description.style.cssText =
              'font-size:11px;color:#6b7c73;margin-top:3px;';
            description.textContent = module.description;
            info.appendChild(description);
          }
          const remove = document.createElement('button');
          remove.type = 'button';
          remove.className = 'mc-btn';
          remove.style.cssText =
            'flex:0 0 auto;background:#fff1f0;color:#a32d2d;padding:7px 9px;';
          remove.innerHTML = '<i class="fa fa-trash"></i> Remove';
          remove.addEventListener('click', () => deleteModule(module.id));
          row.append(number, info, remove);
          container.appendChild(row);
        });
      }

      function renderQuizzes() {
        const container = byId('quizListContainer');
        const empty = byId('quizzesEmptyState');
        container.innerHTML = '';
        if (!contentQuizzes.length) {
          empty.style.display = 'block';
          container.appendChild(empty);
          return;
        }
        empty.style.display = 'none';
        contentQuizzes.forEach((quiz) => {
          const card = document.createElement('div');
          card.className = 'mc-panel';
          card.style.cssText =
            'background:#fff;margin:0;padding:0;overflow:hidden;';
          const header = document.createElement('div');
          header.style.cssText =
            'display:flex;align-items:center;gap:8px;padding:12px;flex-wrap:wrap;';
          const info = document.createElement('div');
          info.style.cssText = 'flex:1;min-width:140px;';
          const title = document.createElement('div');
          title.style.cssText =
            'font-size:13px;font-weight:800;color:#183329;';
          title.textContent = quiz.title || 'Untitled quiz';
          const meta = document.createElement('div');
          meta.style.cssText =
            'font-size:11px;color:#6b7c73;margin-top:4px;';
          meta.textContent =
            `${quiz.module?.title ? quiz.module.title + ' · ' : ''}${quiz.time_limit ?? '—'} min · ${quiz.passing_score ?? '—'}% passing`;
          info.append(title, meta);
          const questionsButton = document.createElement('button');
          questionsButton.type = 'button';
          questionsButton.className = 'mc-btn secondary';
          questionsButton.style.flex = '0 0 auto';
          questionsButton.innerHTML =
          '<i class="fa fa-list"></i> Questions';
          questionsButton.addEventListener('click', () =>
            toggleQuizQuestions(quiz.id));
          const remove = document.createElement('button');
          remove.type = 'button';
          remove.className = 'mc-btn';
          remove.style.cssText =
            'flex:0 0 auto;background:#fff1f0;color:#a32d2d;';
          remove.innerHTML = '<i class="fa fa-trash"></i> Remove';
          remove.addEventListener('click', () => deleteQuiz(quiz.id));
          header.append(info, questionsButton, remove);
          const panel = document.createElement('div');
          panel.id = `quiz-questions-${quiz.id}`;
          panel.style.cssText =
            'display:none;border-top:1px solid #e2ebe5;padding:12px;background:#f8fbf8;';
          const list = document.createElement('div');
          list.id = `qlist-${quiz.id}`;
          list.className = 'mc-list';
          list.style.marginBottom = '10px';
          panel.appendChild(list);
          panel.appendChild(questionForm(quiz.id));
          card.append(header, panel);
          container.appendChild(card);
        });
      }

      function questionForm(quizId) {
        const wrapper = document.createElement('div');
        wrapper.className = 'mc-panel';
        wrapper.style.margin = '0';
        const heading = document.createElement('h4');
        heading.className = 'mc-panel-title';
        heading.textContent = 'Add multiple-choice question';
        const question = document.createElement('textarea');
        question.id = `qtext-${quizId}`;
        question.className = 'mc-textarea';
        question.rows = 2;
        question.placeholder = 'Question text';
        wrapper.append(heading, question);
        const choices = document.createElement('div');
        choices.className = 'mc-form-grid';
        ['a', 'b', 'c', 'd'].forEach((letter) => {
          const input = document.createElement('input');
          input.id = `q${letter}-${quizId}`;
          input.className = 'mc-input';
          input.placeholder =
            `${letter.toUpperCase()}. Choice ${letter.toUpperCase()}`;
          input.type = 'text';
          choices.appendChild(input);
        });
        choices.style.marginTop = '8px';
        wrapper.appendChild(choices);
        const footer = document.createElement('div');
        footer.style.cssText =
          'display:flex;align-items:center;gap:8px;margin-top:8px;flex-wrap:wrap;';
        const label = document.createElement('label');
        label.textContent = 'Correct answer';
        label.style.cssText = 'font-size:11px;font-weight:800;color:#6b7c73;';
        const select = document.createElement('select');
        select.id = `qans-${quizId}`;
        select.className = 'mc-select';
        select.style.width = '100px';
        ['a', 'b', 'c', 'd'].forEach(letter => {
          const option = document.createElement('option');
          option.value = letter;
          option.textContent = letter.toUpperCase();
          select.appendChild(option);
        });
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'mc-btn primary';
        button.style.flex = '0 0 auto';
        button.innerHTML = '<i class="fa fa-plus"></i> Add question';
        button.addEventListener('click', () => addQuestion(quizId));
        footer.append(label, select, button);
        wrapper.appendChild(footer);
        return wrapper;
      }

      async function addModule() {
        const title = byId('newModuleTitle').value.trim();
        const description = byId('newModuleDesc').value.trim();
        const unit = byId('newModuleUnit').value || 1;
        const file = byId('newModuleFile').files[0];
        if (!title) return showAlert('Please enter a module title.');
        if (!file) return showAlert('Please upload a PDF file.');
        if (!/\.pdf$/i.test(file.name)) return showAlert(
          'The module file must be a PDF.');
        const formData = new FormData();
        formData.append('course_id', contentCourseId);
        formData.append('title', title);
        formData.append('description', description);
        formData.append('unit_number', unit);
        formData.append('file', file);
        try {
          const response = await fetch('/trainer/module', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: formData
          });
          const data = await safeJson(response);
          if (!data.success) return showAlert(data.message ||
            'Could not add module.');
          byId('newModuleTitle').value = '';
          byId('newModuleDesc').value = '';
          byId('newModuleFile').value = '';
          await fetchCourseContent(contentCourseId);
          switchContentTab('modules');
        } catch (error) {
          showAlert(error.message || 'Could not add module.');
        }
      }
      window.addModule = addModule;

      async function deleteModule(id) {
        confirmAction(
          'Remove this module? Any linked content may be affected.',
        async () => {
            try {
              const response = await fetch(`/trainer/module/${id}`, {
                method: 'POST',
                headers: {
                  ...requestHeaders,
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  _method: 'DELETE'
                })
              });
              const data = await safeJson(response);
              if (!data.success) return showAlert(data.message ||
                'Could not remove module.');
              await fetchCourseContent(contentCourseId);
            } catch (error) {
              showAlert(error.message || 'Could not remove module.');
            }
          });
      }
      window.deleteModule = deleteModule;

      async function addQuiz() {
        const title = byId('newQuizTitle').value.trim();
        if (!title) return showAlert('Please enter a quiz title.');
        const passing = Number.parseInt(byId('newQuizPass').value, 10);
        const time = Number.parseInt(byId('newQuizTime').value, 10);
        if (!Number.isInteger(passing) || passing < 0 || passing > 100)
        return showAlert('Passing score must be between 0 and 100.');
        if (!Number.isInteger(time) || time < 1) return showAlert(
          'Time limit must be at least 1 minute.');
        const payload = {
          course_id: contentCourseId,
          module_id: byId('newQuizModule').value || null,
          title,
          quiz_type: byId('newQuizType').value,
          passing_score: passing,
          time_limit: time
        };
        try {
          const response = await fetch('/trainer/quiz', {
            method: 'POST',
            headers: {
              ...requestHeaders,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          });
          const data = await safeJson(response);
          if (!data.success) return showAlert(data.message ||
            'Could not create quiz.');
          byId('newQuizTitle').value = '';
          await fetchCourseContent(contentCourseId);
          switchContentTab('quizzes');
        } catch (error) {
          showAlert(error.message || 'Could not create quiz.');
        }
      }
      window.addQuiz = addQuiz;

      async function deleteQuiz(id) {
        confirmAction('Remove this quiz?', async () => {
          try {
            const response = await fetch(`/trainer/quiz/${id}`, {
              method: 'POST',
              headers: {
                ...requestHeaders,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                _method: 'DELETE'
              })
            });
            const data = await safeJson(response);
            if (!data.success) return showAlert(data.message ||
              'Could not remove quiz.');
            await fetchCourseContent(contentCourseId);
          } catch (error) {
            showAlert(error.message || 'Could not remove quiz.');
          }
        });
      }
      window.deleteQuiz = deleteQuiz;

      async function toggleQuizQuestions(quizId) {
        const panel = byId(`quiz-questions-${quizId}`);
        if (!panel) return;
        const opening = panel.style.display === 'none';
        panel.style.display = opening ? 'block' : 'none';
        if (opening) await loadQuizQuestions(quizId);
      }
      window.toggleQuizQuestions = toggleQuizQuestions;

      async function loadQuizQuestions(quizId) {
        try {
          const response = await fetch(`/trainer/quiz/${quizId}/questions`, {
            headers: requestHeaders
          });
          const data = await safeJson(response);
          renderQuizQuestions(quizId, Array.isArray(data.questions) ? data
            .questions : []);
        } catch (error) {
          showAlert(error.message || 'Could not load quiz questions.');
        }
      }

      function renderQuizQuestions(quizId, questions) {
        const container = byId(`qlist-${quizId}`);
        if (!container) return;
        container.innerHTML = '';
        if (!questions.length) {
          const empty = document.createElement('div');
          empty.style.cssText =
            'font-size:12px;color:#6b7c73;text-align:center;padding:8px;';
          empty.textContent = 'No questions added yet.';
          container.appendChild(empty);
          return;
        }
        questions.forEach((question, index) => {
          const row = document.createElement('div');
          row.className = 'mc-panel';
          row.style.cssText =
            'display:flex;gap:10px;align-items:flex-start;margin:0;background:#fff;';
          const number = document.createElement('strong');
          number.textContent = `${index + 1}.`;
          number.style.color = 'var(--mc-green)';
          const details = document.createElement('div');
          details.style.cssText = 'flex:1;min-width:0;font-size:12px;';
          const title = document.createElement('div');
          title.style.cssText =
            'font-weight:800;color:#183329;margin-bottom:6px;';
          title.textContent = question.question || '';
          details.appendChild(title);
          const choices = document.createElement('div');
          choices.style.cssText =
            'display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:5px;color:#52645a;';
          ['a', 'b', 'c', 'd'].forEach(letter => {
            const choice = document.createElement('div');
            choice.textContent =
              `${letter.toUpperCase()}. ${question[`choice_${letter}`] || ''}`;
            if (question.correct_answer === letter) {
              choice.style.color = '#075b35';
              choice.style.fontWeight = '800';
            }
            choices.appendChild(choice);
          });
          details.appendChild(choices);
          const remove = document.createElement('button');
          remove.type = 'button';
          remove.className = 'mc-btn';
          remove.style.cssText =
            'flex:0 0 auto;background:#fff1f0;color:#a32d2d;padding:6px 8px;';
          remove.textContent = 'Remove';
          remove.addEventListener('click', () => deleteQuestion(question.id,
            quizId));
          row.append(number, details, remove);
          container.appendChild(row);
        });
      }

      async function addQuestion(quizId) {
        const question = byId(`qtext-${quizId}`).value.trim();
        const a = byId(`qa-${quizId}`).value.trim();
        const b = byId(`qb-${quizId}`).value.trim();
        const c = byId(`qc-${quizId}`).value.trim();
        const d = byId(`qd-${quizId}`).value.trim();
        const correctAnswer = byId(`qans-${quizId}`).value;
        if (![question, a, b, c, d].every(Boolean)) return showAlert(
          'Please complete the question and all four choices.');
        try {
          const response = await fetch('/trainer/quiz-question', {
            method: 'POST',
            headers: {
              ...requestHeaders,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              quiz_id: quizId,
              question,
              choice_a: a,
              choice_b: b,
              choice_c: c,
              choice_d: d,
              correct_answer: correctAnswer
            })
          });
          const data = await safeJson(response);
          if (!data.success) return showAlert(data.message ||
            'Could not add question.');
          ['qtext', 'qa', 'qb', 'qc', 'qd'].forEach(id => {
            const field = byId(`${id}-${quizId}`);
            if (field) field.value = '';
          });
          await loadQuizQuestions(quizId);
        } catch (error) {
          showAlert(error.message || 'Could not add question.');
        }
      }
      window.addQuestion = addQuestion;

      async function deleteQuestion(id, quizId) {
        confirmAction('Remove this question?', async () => {
          try {
            const response = await fetch(
            `/trainer/quiz-question/${id}`, {
              method: 'POST',
              headers: {
                ...requestHeaders,
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                _method: 'DELETE'
              })
            });
            const data = await safeJson(response);
            if (!data.success) return showAlert(data.message ||
              'Could not remove question.');
            await loadQuizQuestions(quizId);
          } catch (error) {
            showAlert(error.message || 'Could not remove question.');
          }
        });
      }
      window.deleteQuestion = deleteQuestion;

      function populateQuizModuleDropdown() {
        const select = byId('newQuizModule');
        if (!select) return;
        const currentValue = select.value;
        select.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Link to module (optional)';
        select.appendChild(placeholder);
        contentModules.forEach(module => {
          const option = document.createElement('option');
          option.value = module.id;
          option.textContent = module.title || `Module ${module.id}`;
          select.appendChild(option);
        });
        if ([...select.options].some(option => option.value === currentValue))
          select.value = currentValue;
      }

      function confirmAction(message, callback) {
        byId('mcConfirmMessage').textContent = message;
        confirmCallback = callback;
        showModal('mcConfirmModal');
      }
      window.closeConfirm = function(confirmed) {
        hideModal('mcConfirmModal');
        const callback = confirmCallback;
        confirmCallback = null;
        if (confirmed && typeof callback === 'function') callback();
      };

      document.querySelectorAll('.mc-modal').forEach(modal => {
        modal.addEventListener('click', event => {
          if (event.target !== modal) return;
          if (modal.id === 'courseDetailsModal') closeCourseDetails();
          else if (modal.id === 'contentModal') closeContentModal();
          else if (modal.id === 'mcAlertModal') closeAlertModal();
          else if (modal.id === 'mcConfirmModal') closeConfirm(false);
        });
      });
      document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        ['courseDetailsModal', 'contentModal', 'mcAlertModal',
          'mcConfirmModal'
        ].forEach(hideModal);
      });
    })();
  </script>
@endsection

