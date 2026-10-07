@extends('student.layout')

@section('title', 'My Courses')

@section('css')
<style>
/* ── Container ─────────────────────────────────────────────── */
.courses-overview-wrap {
    padding: 32px 40px;
    font-family: 'Open Sans', system-ui, -apple-system, sans-serif;
}

.page-title {
    font-size: 24px;
    font-weight: 800;
    color: #025628;
    margin-bottom: 24px;
}

/* ── Grid Container ────────────────────────────────────────── */
.courses-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
}

/* ── Course Card ───────────────────────────────────────────── */
.course-card {
    width: 340px;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    overflow: hidden;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: transform 0.2s, box-shadow 0.2s;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.card-banner {
    background-color: #025628;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    position: relative;
    padding: 16px;
}

.card-banner svg {
    width: 64px;
    height: 64px;
    fill: currentColor;
    opacity: 0.85;
}

.category-tag-overlay {
    position: absolute;
    top: 16px;
    left: 16px;
    background-color: #D4D120;
    color: #1A1A1A;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 14px;
    border-radius: 20px;
    text-transform: capitalize;
}

.card-body {
    padding: 22px 20px 24px 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.course-card-title {
    font-size: 18px;
    font-weight: 800;
    color: #025628;
    margin-bottom: 8px;
    line-height: 1.35;
}

.course-card-desc {
    font-size: 13px;
    color: #4A5568;
    line-height: 1.5;
    margin-bottom: 20px;
}

.progress-bar-bg {
    width: 100%;
    height: 8px;
    background-color: #EDF2F7;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-bar-fill {
    height: 100%;
    background-color: #025628;
    border-radius: 10px;
}

.progress-text {
    font-size: 12px;
    font-weight: 700;
    color: #025628;
    margin-bottom: 20px;
}

.btn-start-course {
    width: 100%;
    background-color: #025628;
    color: #ffffff;
    border: none;
    padding: 12px 0;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: auto;
    display: block;
}

.btn-start-course:hover {
    background-color: #013d1c;
    color: #ffffff;
}

/* ── COURSE CONTENT & UNIT STYLES ───────────────────────── */
.course-detail-container {
    max-width: 900px;
    color: #2D3748;
}

.course-main-header {
    font-size: 26px;
    font-weight: 800;
    color: #025628;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 24px;
}

.course-top-banner {
    background-color: #025628;
    color: #ffffff;
    border-radius: 20px;
    padding: 24px 32px;
    margin-bottom: 28px;
}

.banner-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 12px;
}

.banner-divider {
    color: rgba(255, 255, 255, 0.4);
}

.banner-progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 8px;
}

.banner-progress-fill {
    height: 100%;
    background-color: #ffffff;
    border-radius: 10px;
}

.banner-progress-text {
    text-align: right;
    font-size: 12px;
    font-weight: 700;
}

.welcome-title {
    font-size: 15px;
    font-weight: 700;
    color: #025628;
    font-style: italic;
    margin-bottom: 12px;
}

.welcome-desc {
    font-size: 13px;
    line-height: 1.6;
    color: #4A5568;
    margin-bottom: 24px;
}

.section-label {
    font-size: 13px;
    font-weight: 700;
    color: #2D3748;
    margin-top: 16px;
    margin-bottom: 10px;
}

.bullet-list {
    list-style: none;
    padding-left: 0;
    margin-bottom: 24px;
}

.bullet-list li {
    font-size: 12px;
    color: #4A5568;
    line-height: 1.6;
    margin-bottom: 4px;
    position: relative;
    padding-left: 16px;
}

.bullet-list li::before {
    content: "•";
    color: #4A5568;
    position: absolute;
    left: 0;
    font-weight: bold;
}

.content-hr {
    border: none;
    border-top: 1px solid #CBD5E0;
    margin: 28px 0;
}

.yellow-notice-box {
    background-color: #D4D120;
    color: #1A1A1A;
    border-radius: 16px;
    padding: 18px 24px;
    font-size: 12px;
    line-height: 1.5;
    font-weight: 600;
    margin-bottom: 28px;
}

.pretest-action-bar {
    background: #CBD5E0;
    border-radius: 12px;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.pretest-btn {
    background: transparent;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #025628;
    cursor: pointer;
    padding: 0;
}

.pretest-score {
    font-size: 13px;
    font-weight: 700;
    color: #2D3748;
}

.btn-back-overview {
    background: transparent;
    border: 1px solid #025628;
    color: #025628;
    padding: 6px 16px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    margin-bottom: 20px;
}

.unit-title-header {
    font-size: 22px;
    font-weight: 800;
    color: #025628;
    margin-bottom: 16px;
}

.module-action-row {
    background: #CBD5E0;
    border-radius: 12px;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.module-action-title { font-size: 14px; font-weight: 700; color: #2D3748; }
.module-buttons-group { display: flex; gap: 12px; }

.btn-module-done {
    background: transparent;
    border: 1.5px solid #025628;
    color: #025628;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-module-done.completed { background: #025628; color: #ffffff; }

.btn-module-view {
    background: transparent;
    border: 1.5px solid #025628;
    color: #025628;
    padding: 6px 24px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-module-view:hover { background: #025628; color: #ffffff; }

/* ── COMPLETION SCREEN STYLES ───────────────────────────── */
.mayor-message-card {
    border: 2px solid #025628;
    border-radius: 20px;
    padding: 28px;
    background: #ffffff;
    position: relative;
    overflow: hidden;
    margin-bottom: 32px;
    box-shadow: 0 4px 12px rgba(2, 86, 40, 0.08);
}

.mayor-seal-watermark {
    position: absolute;
    right: -20px;
    top: -20px;
    width: 280px;
    height: 280px;
    opacity: 0.12;
    pointer-events: none;
}

.mayor-card-title { font-size: 15px; font-weight: 800; color: #025628; margin-bottom: 2px; }
.mayor-card-sub { font-size: 11px; color: #718096; margin-bottom: 18px; }
.mayor-card-body { font-size: 12.5px; line-height: 1.65; color: #2D3748; }
.mayor-card-sign { margin-top: 18px; font-size: 12px; font-weight: 700; color: #025628; }

.evaluation-block-label { font-size: 13px; font-weight: 800; color: #025628; margin-bottom: 4px; }
.evaluation-block-desc { font-size: 11.5px; color: #718096; margin-bottom: 12px; }

/* Document Viewer Modal */
.doc-viewer-modal {
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.65); backdrop-filter: blur(4px); z-index: 10000;
    align-items: center; justify-content: center;
}

.doc-viewer-card {
    background: #ffffff; width: 92%; max-width: 780px; height: 85vh;
    border-radius: 20px; overflow: hidden; display: flex; flex-direction: column;
    box-shadow: 0 12px 36px rgba(0, 0, 0, 0.25);
}

.doc-viewer-header { background: #025628; color: #ffffff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; }
.doc-viewer-title { font-size: 16px; font-weight: 800; }
.doc-viewer-sub { font-size: 11px; color: rgba(255, 255, 255, 0.8); }

.doc-viewer-close {
    background: rgba(255, 255, 255, 0.2); border: none; color: #ffffff;
    width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 16px;
    display: flex; align-items: center; justify-content: center;
}

.doc-viewer-body { flex: 1; overflow-y: auto; padding: 32px; background: #F8FAFC; line-height: 1.7; color: #334155; font-size: 13px; }
.doc-page-sheet { background: #ffffff; border: 1px solid #E2E8F0; border-radius: 12px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
.doc-viewer-footer { padding: 16px 24px; border-top: 1px solid #E2E8F0; background: #ffffff; display: flex; justify-content: flex-end; align-items: center; }

@media (max-width: 640px) {
    .courses-overview-wrap { padding: 16px; }
    .course-card { width: 100%; }
}
</style>
@endsection

@section('content')
<div class="courses-overview-wrap">

    @if ($enrollment && request('course_id'))
        {{-- ── REAL COURSE CONTENT VIEW ────────────────────────────── --}}
        <a href="{{ route('student.modules') }}" class="btn-back-overview">
            ← Back to Overview
        </a>

        <div class="course-detail-container">
            <h1 class="course-main-header">
                {{ strtoupper($enrollment->course->title) }}
            </h1>

            <div class="course-top-banner">
                <div class="banner-meta-row">
                    <span>Duration: {{ $enrollment->course->duration ?? 'TBA' }} Days</span>
                    <span class="banner-divider">|</span>
                    <span>Schedule: {{ $enrollment->course->schedule ?? 'TBA' }}</span>
                </div>
                <div class="banner-progress-bar">
                    <div class="banner-progress-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                </div>
                <div class="banner-progress-text">
                    {{ $enrollment->progress ?? 0 }}% Complete
                </div>
            </div>

            <div id="intro-section">
                @if ($enrollment->course->description)
                    <p class="welcome-desc">{{ $enrollment->course->description }}</p>
                @endif

                @if ($enrollment->course->objectives)
                    <div class="section-label">Objectives</div>
                    <p class="welcome-desc">{{ $enrollment->course->objectives }}</p>
                @endif
            </div>

            @php
                $groupedModules = $modules->groupBy('unit_number');
            @endphp

            @foreach ($groupedModules as $unitNum => $unitModules)
                <div id="unit-{{ $unitNum }}-section" style="display:none;">
                    <div style="font-weight:700;font-size:15px;color:#025628;margin:16px 0 8px;">
                        Unit {{ $unitNum }}
                    </div>

                    @foreach ($unitModules as $i => $module)
                        @php
                            $isDone = in_array($module->id, $completedModuleIds);
                        @endphp
                        <div class="module-action-row" id="module-{{ $module->id }}">
                            <div>
                                <div class="module-action-title">{{ $i + 1 }}. {{ $module->title }}</div>
                                @if ($module->description)
                                    <div style="font-size:12px;color:#718096;margin-top:2px;">{{ $module->description }}</div>
                                @endif
                            </div>
                            <div class="module-buttons-group">
                                @if ($module->file_path)
                                    <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="btn-module-view">
                                        View
                                    </a>
                                @else
                                    <span style="font-size:12px;color:#a0aec0;">No file</span>
                                @endif

                                @if ($isDone)
                                    <span class="btn-module-done completed">✓ Done</span>
                                @else
                                    <button onclick="markDone({{ $module->id }}, this)" class="btn-module-done">
                                        Mark as Done
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            @if ($modules->isEmpty())
                <div class="no-data" style="text-align:center;color:#a0aec0;padding:24px 0;">
                    No modules available yet for this course.
                </div>
            @endif

<div id="completion-section" style="display:none;">

    {{-- Completion Header --}}
    <div style="margin-top:18px;">
        <div style="font-size:13px;font-weight:800;color:#025628;text-transform:uppercase;letter-spacing:.3px;">
            Congratulations! You Have Reached the End of the Modules.
        </div>

        <div style="font-size:12px;color:#718096;margin-top:5px;">
            You are just one step away from completing the training program.
        </div>
    </div>

    {{-- Course Progress --}}
    <div style="
        background:#025628;
        color:#fff;
        border-radius:14px;
        padding:18px 20px;
        margin-top:18px;
    ">
        <div style="font-size:12px;font-weight:700;">
            Total Course Duration:
            {{ $enrollment->course->duration ?? 'TBA' }} Days
            @if ($enrollment->course->schedule)
                | {{ $enrollment->course->schedule }}
            @endif
        </div>

        <div style="
            height:8px;
            background:rgba(255,255,255,.25);
            border-radius:20px;
            overflow:hidden;
            margin-top:14px;
        ">
            <div style="
                height:100%;
                width:{{ $enrollment->progress ?? 0 }}%;
                background:#fff;
                border-radius:20px;
            "></div>
        </div>

        <div style="
            text-align:right;
            font-size:11px;
            font-weight:700;
            margin-top:6px;
        ">
            {{ $enrollment->progress ?? 0 }}% Complete
        </div>
    </div>

    {{-- Mayor's Message --}}
    <div style="margin-top:18px;">
        <div style="
            border:1px solid #025628;
            border-radius:14px;
            padding:18px;
            background:#fff;
        ">
            <div style="
                font-size:13px;
                font-weight:800;
                color:#025628;
            ">
                A Special Message from the Mayor
            </div>

            <div style="
                font-size:12px;
                font-weight:700;
                color:#2d3748;
                margin-top:12px;
            ">
                Mabuhay at Maligayang Bati!
            </div>

            <p style="
                font-size:12px;
                line-height:1.6;
                color:#4a5568;
                margin:8px 0 0;
            ">
                Congratulations on reaching the final stage of your training program.
                We hope that the knowledge and skills you gained throughout this training
                will help you in your personal growth, livelihood, and future opportunities.
            </p>

            <div style="
                margin-top:12px;
                font-size:12px;
                font-weight:700;
                color:#025628;
            ">
                — Mayor's Office
            </div>
        </div>
    </div>

    {{-- Final Evaluations --}}
    <div style="margin-top:28px;">

        <div style="
            font-size:13px;
            font-weight:800;
            color:#025628;
        ">
            Complete your final evaluations to finish the program
        </div>

        <div style="
            font-size:11px;
            color:#718096;
            margin-top:4px;
        ">
            Complete the required final assessments to proceed to course completion.
        </div>

{{-- Post-Test --}}
@php
    $postTestResult = $postTest
        ? $quizResults->firstWhere('quiz_id', $postTest->id)
        : null;
@endphp

@if ($postTest)
    <div id="post-test-section" style="
        margin-top:14px;
        background:#edf2f7;
        border-radius:8px;
        padding:12px 14px;
        display:flex;
        align-items:center;
        justify-content:space-between;
    ">
        <div>
            <div style="
                font-size:12px;
                font-weight:800;
                color:#2d3748;
            ">
                {{ $postTest->title }}
            </div>

            <div style="
                font-size:11px;
                color:#718096;
                margin-top:3px;
            ">
                {{ $postTest->questions->count() }} Questions
                &nbsp;·&nbsp;
                {{ $postTest->passing_score }}% Passing
                &nbsp;·&nbsp;
                {{ $postTest->time_limit }} mins
            </div>
        </div>

        @if ($postTestResult)
            <span style="
                font-size:11px;
                font-weight:700;
                color:{{ $postTestResult->status === 'passed' ? '#276749' : '#c53030' }};
            ">
                {{ ucfirst($postTestResult->status) }}
                · {{ $postTestResult->percentage }}%
            </span>
        @else
            <button
                onclick="openQuiz({{ $postTest->id }}, '{{ addslashes($postTest->title) }}')"
                class="pretest-btn">
                Take Post-Test
            </button>
        @endif
    </div>
@else
    <div style="
        margin-top:14px;
        background:#edf2f7;
        border-radius:8px;
        padding:12px 14px;
        display:flex;
        align-items:center;
        justify-content:space-between;
    ">
        <div>
            <div style="
                font-size:12px;
                font-weight:800;
                color:#2d3748;
            ">
                Post-Test
            </div>

            <div style="
                font-size:11px;
                color:#718096;
                margin-top:3px;
            ">
                Final written assessment
            </div>
        </div>

        <span style="
            font-size:11px;
            font-weight:700;
            color:#718096;
        ">
            Not Available Yet
        </span>
    </div>
@endif

{{-- Final Hands-On Test --}}
<div id="hands-on-section" style="
    margin-top:10px;
    background:#edf2f7;
    border-radius:8px;
    padding:12px 14px;
    display:flex;
    align-items:center;
    justify-content:space-between;
">
    <div>
        <div style="
            font-size:12px;
            font-weight:800;
            color:#2d3748;
        ">
            Final Hands-On Test
        </div>

        <div style="
            font-size:11px;
            color:#718096;
            margin-top:3px;
        ">
            Practical evaluation
        </div>
    </div>

    @if ($handsOnEvaluation)
        <span style="
            font-size:11px;
            font-weight:700;
            color:{{ $handsOnEvaluation->result === 'passed' ? '#276749' : '#c53030' }};
        ">
            {{ ucfirst($handsOnEvaluation->result) }}
        </span>
    @else
        <span style="
            font-size:11px;
            font-weight:700;
            color:#718096;
        ">
            Not Evaluated
        </span>
    @endif
</div>

    </div>

    {{-- Certificate --}}
<div id="certificate-section" style="
    margin-top:20px;
    padding:18px;
    border:1px solid #dfe7e2;
    border-radius:14px;
    background:#fff;
">

    <div style="
        font-size:12px;
        font-weight:800;
        color:#025628;
        margin-bottom:6px;
    ">
        Certificate of Completion
    </div>

    @if ($certificate && $enrollment->status === 'completed')

        <div style="
            font-size:12px;
            color:#555;
            margin-bottom:12px;
        ">
            Congratulations! You have completed all course requirements.
        </div>

        <button
            type="button"
            onclick="openCertificateModal()"
            class="pretest-btn"
        >
            View Certificate
        </button>

    @else

        <div style="
            font-size:12px;
            color:#888;
        ">
            Your certificate will become available after you complete
            all required evaluations.
        </div>

    @endif

</div>

</div>
        </div>

    @else
        {{-- ── COURSE OVERVIEW GRID ──────────────────────────────────── --}}
        <h1 class="page-title">Course Overview</h1>

        <div class="courses-grid">
            @forelse($enrollments as $e)
                <div class="course-card">
                    <div class="card-banner">
                        <span class="category-tag-overlay">
                            {{ $e->course->category ?? 'Livelihood' }}
                        </span>
                        <svg viewBox="0 0 24 24">
                            <path d="M19 2H6c-1.2 0-2 .8-2 2v16c0 1.2.8 2 2 2h13c1.1 0 2-.9 2-2V4c0-1.2-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4zm13 16H6c-.6 0-1-.4-1-1s.4-1 1-1h13v2zm0-3H6c-.2 0-.4 0-.6.1V14h13.6v3z"/>
                        </svg>
                    </div>

                    <div class="card-body">
                        <div class="course-card-title">
                            {{ $e->course->title ?? 'Untitled Course' }}
                        </div>

                        <div class="course-card-desc">
                            {{ Str::limit($e->course->description ?? 'No description available.', 90) }}
                        </div>

                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ $e->progress ?? 0 }}%;"></div>
                        </div>
                        <div class="progress-text">
                            {{ $e->progress ?? 0 }}% Complete
                        </div>

                        <a href="{{ route('student.modules', ['course_id' => $e->course_id]) }}" class="btn-start-course">
                            Start this Course
                        </a>
                    </div>
                </div>
            @empty
                <div class="no-data" style="text-align:center;color:#a0aec0;padding:24px 0;">
                    You are not enrolled in any courses yet.
                </div>
            @endforelse
        </div>
    @endif

    {{-- Certificate Modal --}}
<div id="certificateModal"
     style="
        display:none;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.65);
        z-index:10000;
        align-items:center;
        justify-content:center;
     ">

    <div style="
        background:#fff;
        width:92%;
        max-width:900px;
        max-height:90vh;
        overflow:auto;
        border-radius:18px;
        padding:24px;
    ">

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:18px;
        ">
            <h2 style="
                margin:0;
                font-size:18px;
                color:#025628;
            ">
                Certificate of Completion
            </h2>

            <button
                type="button"
                onclick="closeCertModal()"
                style="
                    border:none;
                    background:#edf2f7;
                    width:32px;
                    height:32px;
                    border-radius:50%;
                    cursor:pointer;
                    font-size:16px;
                "
            >
                ✕
            </button>
        </div>

        {{-- Certificate Preview --}}
        <div id="printableCert" style="
            background:#fff;
            border:4px double #025628;
            padding:30px;
            text-align:center;
        ">

            <div style="
                display:flex;
                justify-content:center;
                gap:18px;
                margin-bottom:14px;
            ">
                <img src="{{ asset('images/logo.png') }}"
                     alt="City Government Logo"
                     style="height:45px;">

                <img src="{{ asset('images/tesda.png') }}"
                     alt="TESDA Logo"
                     style="height:45px;">

                <img src="{{ asset('images/logo_ledipo.png') }}"
                     alt="LEDIPO Logo"
                     style="height:45px;">
            </div>

            <div style="
                font-size:10px;
                color:#666;
                line-height:1.5;
            ">
                TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
                CITY GOVERNMENT OF DASMARIÑAS — LEDIPO
            </div>

            <div style="
                margin-top:20px;
                font-size:22px;
                font-weight:800;
                color:#025628;
            ">
                CERTIFICATE OF COMPLETION
            </div>

            <div style="
                margin-top:18px;
                font-size:11px;
                color:#666;
            ">
                THIS CERTIFIES THAT
            </div>

            <div id="vName" style="
                margin-top:8px;
                font-size:24px;
                font-weight:800;
                color:#025628;
            ">
                {{ $certificate->user->firstname ?? '' }}
                {{ $certificate->user->lastname ?? '' }}
            </div>

            <div style="
                margin-top:16px;
                font-size:11px;
                color:#666;
            ">
                HAS SUCCESSFULLY COMPLETED THE TRAINING IN
            </div>

            <div id="vCourse" style="
                margin-top:8px;
                font-size:17px;
                font-weight:800;
                color:#2d3748;
            ">
                {{ $certificate->course->title ?? $enrollment->course->title }}
            </div>

            <div style="
                margin-top:40px;
                display:flex;
                justify-content:space-around;
                gap:30px;
            ">
                <div>
                    <div style="font-weight:700;font-size:11px;">
                        HON. JENNIFER A. BARZAGA
                    </div>
                    <div style="font-size:10px;color:#718096;">
                        City Mayor
                    </div>
                </div>

                <div>
                    <div style="font-weight:700;font-size:11px;">
                        MR. CARLOS H. LEGASPI
                    </div>
                    <div style="font-size:10px;color:#718096;">
                        LEDIPO Head
                    </div>
                </div>
            </div>

            <div style="
                margin-top:30px;
                padding-top:12px;
                border-top:1px solid #ddd;
                display:flex;
                justify-content:space-between;
                font-size:10px;
                color:#718096;
            ">
                <span>
                    CERT. NO.:
                    <strong id="vID">
                        {{ $certificate->certificate_no ?? '—' }}
                    </strong>
                </span>

                <span>
                    TRAINING ID:
                    <strong id="vTrainingID">
                        {{ $certificate->training_id ?? '—' }}
                    </strong>
                </span>
            </div>

        </div>

        {{-- Certificate Details --}}
        <div style="
            margin-top:20px;
            padding-top:18px;
            border-top:1px solid #e2e8f0;
        ">

            <div style="
                font-size:12px;
                color:#718096;
            ">
                Issue Date
            </div>

            <div id="vIssueDate" style="
                font-size:13px;
                font-weight:700;
                color:#2d3748;
            ">
                {{ $certificate->issue_date ?? '—' }}
            </div>

            <div style="
                margin-top:12px;
                font-size:12px;
                color:#718096;
            ">
                Status
            </div>

            <div id="vStatusBadge" style="
                font-size:13px;
                font-weight:700;
                color:#276749;
            ">
                {{ ucfirst($certificate->status ?? 'issued') }}
            </div>

            <div style="
                margin-top:12px;
                font-size:12px;
                color:#718096;
            ">
                Trainee Performance
            </div>

            <div id="vGrade" style="
                font-size:13px;
                font-weight:700;
                color:#276749;
            ">
                {{ $certificate->grade ?? 'Completed' }}
            </div>

        </div>

        {{-- Actions --}}
        <div style="
            margin-top:20px;
            display:flex;
            justify-content:flex-end;
            gap:10px;
        ">
            <button
                type="button"
                onclick="window.print()"
                style="
                    background:#025628;
                    color:#fff;
                    border:none;
                    padding:10px 18px;
                    border-radius:8px;
                    font-weight:700;
                    cursor:pointer;
                "
            >
                Print Certificate
            </button>

            <button
                type="button"
                onclick="closeCertModal()"
                style="
                    background:#edf2f7;
                    color:#2d3748;
                    border:none;
                    padding:10px 18px;
                    border-radius:8px;
                    font-weight:700;
                    cursor:pointer;
                "
            >
                Close
            </button>
        </div>

    </div>
</div>

            <div id="quizModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:#fff;width:90%;max-width:600px;max-height:85vh;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;">
                <div style="background:#025628;color:#fff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center;">
                    <div id="quizModalTitle" style="font-size:16px;font-weight:800;"></div>
                    <button onclick="closeQuiz()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:28px;height:28px;border-radius:50%;cursor:pointer;">✕</button>
                </div>
                <div id="quizModalBody" style="padding:24px;overflow-y:auto;flex:1;"></div>
                <div style="padding:16px 24px;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;">
                    <button onclick="submitQuizAnswers()" style="background:#025628;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-weight:700;cursor:pointer;">
                        Submit Quiz
                    </button>
                </div>
            </div>
        </div>

</div>
@endsection

    @section('scripts')
    <script>

        function showIntroScreen() {
    document.getElementById('intro-section').style.display = 'block';
    document.querySelectorAll('[id^="unit-"][id$="-section"]').forEach(el => el.style.display = 'none');
    document.getElementById('completion-section').style.display = 'none';
    }

    function openCertificateModal() {
    const modal = document.getElementById('certificateModal');

        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeCertModal() {
        const modal = document.getElementById('certificateModal');

        if (modal) {
            modal.style.display = 'none';
        }
    }

    function showUnitScreen(unitNum) {
        document.getElementById('intro-section').style.display = 'none';
        document.querySelectorAll('[id^="unit-"][id$="-section"]').forEach(el => el.style.display = 'none');
        document.getElementById('completion-section').style.display = 'none';

        const target = document.getElementById(`unit-${unitNum}-section`);
        if (target) target.style.display = 'block';
    }

    function showCompletionScreen() {
        document.getElementById('intro-section').style.display = 'none';
        document.querySelectorAll('[id^="unit-"][id$="-section"]').forEach(el => el.style.display = 'none');
        document.getElementById('completion-section').style.display = 'block';
    }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        let _currentQuizId = null;

        function openQuiz(quizId, quizTitle) {
            _currentQuizId = quizId;
            document.getElementById('quizModalTitle').textContent = quizTitle;
            document.getElementById('quizModalBody').innerHTML = '<p>Loading questions...</p>';
            document.getElementById('quizModal').style.display = 'flex';

            fetch(`/student/quiz/${quizId}`, {
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.taken) {
                    document.getElementById('quizModalBody').innerHTML = '<p>You have already taken this quiz.</p>';
                    return;
                }
                renderQuizQuestions(data.quiz.questions);
            })
            .catch(() => {
                document.getElementById('quizModalBody').innerHTML = '<p>Failed to load quiz. Please try again.</p>';
            });
        }

        function renderQuizQuestions(questions) {
            const body = document.getElementById('quizModalBody');
            body.innerHTML = questions.map(q => `
                <div style="margin-bottom:20px;">
                    <div style="font-weight:700;font-size:14px;margin-bottom:8px;">${q.question}</div>
                    <label style="display:block;margin-bottom:4px;font-size:13px;"><input type="radio" name="q${q.id}" value="a"> A. ${q.choice_a}</label>
                    <label style="display:block;margin-bottom:4px;font-size:13px;"><input type="radio" name="q${q.id}" value="b"> B. ${q.choice_b}</label>
                    <label style="display:block;margin-bottom:4px;font-size:13px;"><input type="radio" name="q${q.id}" value="c"> C. ${q.choice_c}</label>
                    <label style="display:block;margin-bottom:4px;font-size:13px;"><input type="radio" name="q${q.id}" value="d"> D. ${q.choice_d}</label>
                </div>
            `).join('');
        }

        function closeQuiz() {
            document.getElementById('quizModal').style.display = 'none';
            _currentQuizId = null;
        }

        function submitQuizAnswers() {
            const inputs = document.querySelectorAll('#quizModalBody input[type=radio]:checked');
            const answers = {};
            inputs.forEach(input => {
                const qId = input.name.replace('q', '');
                answers[qId] = input.value;
            });

            fetch('/student/quiz/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quiz_id: _currentQuizId, answers: answers })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert(`Score: ${data.score}/${data.total} (${data.percentage}%) - ${data.status.toUpperCase()}`);
                    closeQuiz();
                    location.reload();
                } else {
                    alert(data.message || 'Something went wrong.');
                }
            })
            .catch(() => alert('Something went wrong. Please try again.'));
        }

        function markDone(moduleId, btn) {
            fetch(`/student/module/${moduleId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    btn.outerHTML = '<span class="btn-module-done completed">✓ Done</span>';
                }
            })
            .catch(() => alert('Something went wrong. Please try again.'));
        }
        </script>
    @endsection

