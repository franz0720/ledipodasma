{{-- resources/views/trainer/students.blade.php --}}


@extends('trainer.layout')


@section('title', 'Students')


@section('css')


<style>


    .st-wrap {


        padding: 24px;


        color: #183329;


    }


    .st-page-title {


        margin: 0 0 6px;


        color: #075b35;


        font-size: 22px;


        font-weight: 750;


    }


    .st-page-sub {


        margin-bottom: 24px;


        color: #7b8b82;


        font-size: 13px;


    }


    .st-page-sub strong {


        color: #075b35;


    }


    .st-filter-bar {


        display: grid;


        grid-template-columns: minmax(220px, 1.7fr) minmax(180px, 1fr) minmax(180px, 1fr) 110px;


        align-items: end;


        gap: 14px;


        margin-bottom: 18px;


        padding: 20px;


        background: #fff;


        border: 1px solid #e2ebe5;


        border-radius: 16px;


        box-shadow: 0 4px 18px rgba(20, 65, 40, .035);


    }


    .st-filter-field {


        display: flex;


        flex-direction: column;


        gap: 8px;


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


        width: 100%;


    }


    .st-search-wrap i {


        position: absolute;


        top: 50%;


        left: 14px;


        transform: translateY(-50%);


        color: #91a49a;


        pointer-events: none;


    }


    .st-search-input,


    .st-select {


        box-sizing: border-box;


        width: 100%;


        min-width: 0;


        height: 46px;


        padding: 0 12px;


        border: 1px solid #dfe9e2;


        border-radius: 10px;


        background: #fff;


        color: #24382d;


        font: inherit;


        font-size: 13px;


        outline: none;


        transition: border-color .18s, box-shadow .18s;


    }


    .st-search-input {


        padding-left: 38px;


    }


    .st-select {


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


        box-sizing: border-box;


        height: 46px;


        padding: 0 15px;


        border: 1px solid #d8e7dc;


        border-radius: 10px;


        background: #fff;


        color: #075b35;


        font-size: 13px;


        font-weight: 700;


        white-space: nowrap;


        cursor: pointer;


        transition: background .18s, border-color .18s;


    }


    .st-reset:hover {


        background: #f0f7f2;


        border-color: #a9cbb5;


    }


    .st-reset:focus-visible {


        outline: 2px solid #075b35;


        outline-offset: 3px;


    }


    .st-export-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:40px; padding:0 14px; border:1px solid #075b35; border-radius:10px; background:#075b35; color:#fff; font-size:12px; font-weight:750; cursor:pointer; white-space:nowrap; }


    .st-export-btn:hover { background:#043f26; }


    .st-export-btn:focus-visible { outline:2px solid #e1dc36; outline-offset:3px; }


    .st-table-head-actions { display:flex; align-items:center; justify-content:flex-end; gap:14px; flex-wrap:wrap; }


    @media (max-width:600px) { .st-table-head-actions { justify-content:flex-start; width:100%; } }


    .st-table-wrap {


        width: 100%;


        margin-bottom: 24px;


        overflow: hidden;


        border: 1px solid #e2e8f0;


        border-radius: 14px;


        background: #fff;


    }


    .st-table-head {


        display: flex;


        align-items: center;


        justify-content: space-between;


        gap: 12px;


        padding: 16px 20px;


        border-bottom: 1px solid #edf2f7;


    }


    .st-table-head-title {


        color: #34445a;


        font-size: 14px;


        font-weight: 650;


    }


    .st-table-head-meta {


        color: #89968e;


        font-size: 12px;


        white-space: nowrap;


    }


    .st-table-head-meta strong {


        color: #075b35;


        font-weight: 750;


    }


    .st-table-scroll {


        width: 100%;


        overflow-x: auto;


    }


    .st-table {


        width: 100%;


        min-width: 940px;


        border-collapse: collapse;


        background: #fff;


        font-size: 13px;


    }


    .st-table th {


        padding: 13px 18px;


        border-bottom: 1px solid #edf2f7;


        background: #fff;


        color: #91a0b4;


        font-size: 11px;


        font-weight: 700;


        letter-spacing: .035em;


        text-align: left;


        text-transform: uppercase;


        white-space: nowrap;


    }


    .st-table td {


        padding: 15px 18px;


        border-bottom: 1px solid #f0f3f5;


        color: #4a5568;


        vertical-align: middle;


    }


    .st-table tbody tr:last-child td {


        border-bottom: none;


    }


    .st-table tbody tr[data-name]:hover {


        background: #fafcfa;


    }


    .st-table th:first-child,


    .st-table td:first-child {


        min-width: 230px;


    }


    .st-table th:nth-child(2),


    .st-table td:nth-child(2) {


        min-width: 180px;


    }


    .st-trainee-name {


        display: block;


        color: #34445a;


        font-size: 13px;


        font-weight: 700;


        line-height: 1.5;


    }


    .st-trainee-meta {


        display: block;


        margin-top: 4px;


        color: #87948d;


        font-size: 11px;


        line-height: 1.5;


    }


    .st-course-name {


        color: #183329 !important;


        font-weight: 600;


        white-space: normal;


    }


    .st-course-label {


        display: inline-block;


        max-width: 240px;


        line-height: 1.5;


        overflow-wrap: anywhere;


    }


    .st-score {


        color: #4a5568;


        white-space: nowrap;


    }


    .st-overall-cell {


        display: flex;


        align-items: center;


        gap: 10px;


        white-space: nowrap;


    }


    .st-progress-bg {


        width: 90px;


        height: 7px;


        flex: 0 0 90px;


        overflow: hidden;


        border-radius: 999px;


        background: #e8f5e9;


    }


    .st-progress-fill {


        height: 100%;


        border-radius: 999px;


        background: #075b35;


        transition: width .2s ease;


    }


    .st-progress-fill.is-low {


        background: #e05c6e;


    }


    .st-overall-value {


        color: #34445a;


        font-size: 13px;


        font-weight: 750;


    }


    .st-badge {


        display: inline-flex;


        align-items: center;


        padding: 5px 10px;


        border-radius: 999px;


        font-size: 11px;


        font-weight: 700;


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


    .st-badge.pending {


        background: #fff8e1;


        color: #8a6500;


    }


    .st-empty {


        padding: 34px 20px;


        color: #87948d;


        text-align: center;


    }


    .st-empty i {


        display: block;


        margin-bottom: 10px;


        color: #9aafa2;


        font-size: 26px;


    }


    .st-empty p {


        margin: 0;


        font-size: 13px;


        line-height: 1.6;


    }


    @media (max-width: 1100px) {


        .st-filter-bar {


            grid-template-columns: repeat(2, minmax(0, 1fr));


        }


    }


    @media (max-width: 600px) {


        .st-wrap {


            padding: 16px;


        }


        .st-filter-bar {


            grid-template-columns: minmax(0, 1fr);


            padding: 14px;


        }


        .st-table-head {


            align-items: flex-start;


            flex-direction: column;


            gap: 6px;


            padding: 15px;


        }


        .st-table-head-meta {


            white-space: normal;


        }


    }


    .st-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin: 0 0 20px;
    }
    .st-summary-card {
        min-width: 0;
        padding: 18px;
        border: 1px solid #e2ebe5;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(20, 65, 40, .035);
    }
    .st-summary-label {
        margin-bottom: 9px;
        color: #718278;
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .st-summary-value {
        display: block;
        overflow-wrap: anywhere;
        color: #075b35;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.25;
    }
    .st-summary-detail {
        margin-top: 6px;
        color: #87948d;
        font-size: 11px;
        line-height: 1.45;
    }
    .st-summary-card.top-performer {
        background: #f3faf5;
        border-color: #cfe5d5;
    }
    @media (max-width: 900px) {
        .st-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 480px) {
        .st-summary-grid { grid-template-columns: minmax(0, 1fr); }
    }
    .st-top-three {
        margin: 0 0 20px;
        overflow: hidden;
        border: 1px solid #e2ebe5;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(20, 65, 40, .035);
    }
    .st-top-three-head {
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f7;
        color: #183329;
        font-size: 14px;
        font-weight: 750;
    }
    .st-top-three-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        padding: 14px;
    }
    .st-rank-card {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        padding: 14px;
        border: 1px solid #e2ebe5;
        border-radius: 12px;
        background: #fbfdfb;
    }
    .st-rank-number {
        display: flex;
        flex: 0 0 36px;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #eaf5ee;
        color: #075b35;
        font-size: 14px;
        font-weight: 800;
    }
    .st-rank-info { min-width: 0; }
    .st-rank-name {
        display: block;
        overflow-wrap: anywhere;
        color: #183329;
        font-size: 13px;
        font-weight: 750;
    }
    .st-rank-score {
        display: block;
        margin-top: 4px;
        color: #075b35;
        font-size: 13px;
        font-weight: 800;
    }
    .st-rank-empty { padding: 18px; color: #87948d; font-size: 13px; }
    @media (max-width: 760px) {
        .st-top-three-list { grid-template-columns: minmax(0, 1fr); }
    }

</style>


@endsection


@section('content')


<div class="st-wrap">


    <h1 class="st-page-title">Students</h1>


    <div class="st-page-sub">


        Students enrolled in


        <strong>


            @if(isset($courses) && $courses->count() === 1)


                {{ $courses->first()->title }}


            @elseif(isset($courses) && $courses->count() > 1)


                your assigned courses


            @else


                your course


            @endif


        </strong>


    </div>


    {{-- Performance overview --}}
    @php
        $stAssessed = [];
        $stCompetentCount = 0;
        $stPendingCount = 0;

        foreach ($students as $stStudent) {
            $stQuiz = is_numeric($stStudent->quiz_average ?? null)
                ? max(0, min(100, (float) $stStudent->quiz_average))
                : null;
            $stOnsite = is_numeric($stStudent->onsite_average ?? null)
                ? max(0, min(100, (float) $stStudent->onsite_average))
                : null;

            if ($stQuiz !== null && $stOnsite !== null) {
                $stOverall = round(($stQuiz + $stOnsite) / 2, 2);
                $stAssessed[] = [
                    'name' => trim(($stStudent->firstname ?? '') . ' ' . ($stStudent->lastname ?? '')),
                    'score' => $stOverall,
                ];

                if ($stOverall >= 75) {
                    $stCompetentCount++;
                }
            } else {
                $stPendingCount++;
            }
        }

        usort($stAssessed, function ($a, $b) {
            return $b['score'] <=> $a['score'] ?: strcasecmp($a['name'], $b['name']);
        });

        $stTopPerformer = $stAssessed[0] ?? null;
        $stAssessedCount = count($stAssessed);
        $stClassAverage = $stAssessedCount
            ? round(array_sum(array_column($stAssessed, 'score')) / $stAssessedCount, 2)
            : null;
    @endphp

    <div class="st-summary-grid">
        <div class="st-summary-card top-performer">
            <div class="st-summary-label">Top Performer</div>
            <strong class="st-summary-value">
                {{ $stTopPerformer ? $stTopPerformer['score'] . '%' : '—' }}
            </strong>
            <div class="st-summary-detail">
                {{ $stTopPerformer ? $stTopPerformer['name'] : 'No completed assessments yet' }}
            </div>
        </div>

        <div class="st-summary-card">
            <div class="st-summary-label">Total Trainees</div>
            <strong class="st-summary-value">{{ $students->count() }}</strong>
            <div class="st-summary-detail">Enrolled in your assigned courses</div>
        </div>

        <div class="st-summary-card">
            <div class="st-summary-label">Competent</div>
            <strong class="st-summary-value">{{ $stCompetentCount }}</strong>
            <div class="st-summary-detail">Overall assessment of 75% or higher</div>
        </div>

        <div class="st-summary-card">
            <div class="st-summary-label">Class Average</div>
            <strong class="st-summary-value">
                {{ $stClassAverage !== null ? number_format($stClassAverage, 2) . '%' : '—' }}
            </strong>
            <div class="st-summary-detail">
                {{ $stAssessedCount }} assessed
                @if($stPendingCount > 0)
                    · {{ $stPendingCount }} pending assessment
                @endif
            </div>
        </div>
    </div>

    {{-- Top 3 trainees ranked by overall assessment --}}
    <section class="st-top-three" aria-labelledby="stTopThreeTitle">
        <div class="st-top-three-head" id="stTopThreeTitle">Top 3 Trainees</div>
        @if(count($stAssessed) > 0)
            <div class="st-top-three-list">
                @foreach(array_slice($stAssessed, 0, 3) as $stRank => $stTrainee)
                    <div class="st-rank-card">
                        <span class="st-rank-number">{{ $stRank + 1 }}</span>
                        <div class="st-rank-info">
                            <span class="st-rank-name">{{ $stTrainee['name'] !== '' ? $stTrainee['name'] : 'Unknown trainee' }}</span>
                            <span class="st-rank-score">{{ number_format($stTrainee['score'], 2) }}% overall</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="st-rank-empty">No completed assessments available for ranking yet.</div>
        @endif
    </section>

    {{-- Search, filter, sort and reset --}}


    <div class="st-filter-bar" role="search" aria-label="Filter students">


        <label class="st-filter-field">


            <span class="st-filter-label">Search students</span>


            <span class="st-search-wrap">


                <i class="fa fa-search" aria-hidden="true"></i>


                <input


                    type="search"


                    class="st-search-input"


                    id="studentSearch"


                    placeholder="Search by name or email..."


                    aria-label="Search by name or email">


            </span>


        </label>


        <label class="st-filter-field">


            <span class="st-filter-label">Competency status</span>


            <select class="st-select" id="statusFilter" aria-label="Filter by competency status">


                <option value="">All statuses</option>


                <option value="competent">Competent (75% and above)</option>


                <option value="not-yet-competent">Not Yet Competent (below 75%)</option>


                <option value="pending">Pending Assessment</option>


            </select>


        </label>


        <label class="st-filter-field">


            <span class="st-filter-label">Sort students</span>


            <select class="st-select" id="sortFilter" aria-label="Sort students">


                <option value="name">Name (A–Z)</option>


                <option value="progress-low">Lowest score first</option>


                <option value="progress-high">Highest score first</option>


                <option value="newest">Recently enrolled</option>


            </select>


        </label>


        <button type="button" class="st-reset" id="resetFilters">


            <i class="fa fa-rotate-left" aria-hidden="true"></i>


            Reset


        </button>


    </div>


    {{-- Student assessment table --}}


    <div class="st-table-wrap">


        <div class="st-table-head">


            <span class="st-table-head-title">Student Assessment Performance</span>


            <div class="st-table-head-actions">


                <span class="st-table-head-meta">Showing <strong id="visibleCount">{{ $students->count() }}</strong> of <strong id="totalStudentCount">{{ $students->count() }}</strong> students</span>


                <button type="button" class="st-export-btn" id="exportStudentsExcel"><i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel</button>


            </div>


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


                        $quizAverage = is_numeric($student->quiz_average ?? null)


                            ? max(0, min(100, (float) $student->quiz_average))


                            : null;


                        $onsiteAverage = is_numeric($student->onsite_average ?? null)


                            ? max(0, min(100, (float) $student->onsite_average))


                            : null;


                        $hasBothAssessments = $quizAverage !== null


                            && $onsiteAverage !== null;


                        $progress = $hasBothAssessments


                            ? round(($quizAverage + $onsiteAverage) / 2, 2)


                            : null;


                        if (!$hasBothAssessments) {


                            $competencyStatus = 'Pending Assessment';


                            $competencyClass = 'pending';


                            $statusFilterValue = 'pending';


                        } elseif ($progress >= 75) {


                            $competencyStatus = 'Competent';


                            $competencyClass = 'competent';


                            $statusFilterValue = 'competent';


                        } else {


                            $competencyStatus = 'Not Yet Competent';


                            $competencyClass = 'not-yet-competent';


                            $statusFilterValue = 'not-yet-competent';


                        }


                        $studentName = trim(


                            ($student->firstname ?? '') . ' ' . ($student->lastname ?? '')


                        );


                        $studentEmail = $student->email ?? '';


                        $courseTitle = trim((string) ($student->course_title ?? ''));


                        $enrolledTimestamp = !empty($student->enrolled_at)


                            ? \Carbon\Carbon::parse($student->enrolled_at)->timestamp


                            : 0;


                    @endphp


                    <tr


                        data-name="{{ strtolower($studentName) }}"


                        data-email="{{ strtolower($studentEmail) }}"


                        data-progress="{{ $progress ?? '' }}"


                        data-status="{{ $statusFilterValue }}"


                        data-enrolled-at="{{ $enrolledTimestamp }}">


                        <td>


                            <strong class="st-trainee-name">


                                {{ $studentName !== '' ? $studentName : 'Unknown trainee' }}


                            </strong>


                            <small class="st-trainee-meta">


                                {{ $studentEmail !== '' ? $studentEmail : 'No email provided' }}


                            </small>


                        </td>


                        <td class="st-course-name">


                            <span class="st-course-label">


                                {{ $courseTitle !== '' ? $courseTitle : 'Course not available' }}


                            </span>


                        </td>


                        <td class="st-score">


                            {{ $quizAverage !== null ? number_format($quizAverage, 2) . '%' : '—' }}


                        </td>


                        <td class="st-score">


                            {{ $onsiteAverage !== null ? number_format($onsiteAverage, 2) . '%' : '—' }}


                        </td>


                        <td>


                            @if($progress !== null)


                                <div class="st-overall-cell">


                                    <div class="st-progress-bg">


                                        <div


                                            class="st-progress-fill {{ $progress < 75 ? 'is-low' : '' }}"


                                            style="width: {{ $progress }}%;">


                                        </div>


                                    </div>


                                    <strong class="st-overall-value">


                                        {{ number_format($progress, 2) }}%


                                    </strong>


                                </div>


                            @else


                                <span class="st-score">Pending</span>


                            @endif


                        </td>


                        <td>


                            <span class="st-badge {{ $competencyClass }}">


                                {{ $competencyStatus }}


                            </span>


                        </td>


                    </tr>


                @empty


                    <tr>


                        <td colspan="6">


                            <div class="st-empty">


                                <i class="fa fa-users" aria-hidden="true"></i>


                                <p>No students enrolled in your assigned courses yet.</p>


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

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>


document.addEventListener('DOMContentLoaded', function () {


    const searchInput = document.getElementById('studentSearch');


    const statusFilter = document.getElementById('statusFilter');


    const sortFilter = document.getElementById('sortFilter');


    const resetButton = document.getElementById('resetFilters');


    const tableBody = document.querySelector('#studentsTable tbody');


    const countEl = document.getElementById('visibleCount');


    const totalCountEl = document.getElementById('totalStudentCount');


    if (!tableBody || !searchInput || !statusFilter || !sortFilter) {


        return;


    }


    const rows = Array.from(tableBody.querySelectorAll('tr[data-name]'));


        const originalOrder = new Map(rows.map((row, index) => [row, index]));


    const exportButton = document.getElementById('exportStudentsExcel');
    if (exportButton) {
        exportButton.addEventListener('click', function () {
            if (typeof XLSX === 'undefined') {
                alert('Excel export could not load. Check your internet connection and refresh the page.');
                return;
            }

            const visibleRows = rows.filter(row => row.style.display !== 'none');
            if (!visibleRows.length) {
                alert('There are no student records to export with the current filters.');
                return;
            }

            const allRecords = rows.map(row => {
                const cells = row.querySelectorAll('td');
                const rawProgress = row.dataset.progress;
                const score = rawProgress === '' || rawProgress == null ? null : Number(rawProgress);
                const name = cells[0]?.querySelector('.st-trainee-name')?.textContent.trim() || 'Unknown trainee';
                const status = cells[5]?.textContent.trim().replace(/\s+/g, ' ') || 'Pending Assessment';
                return {
                    name,
                    email: cells[0]?.querySelector('.st-trainee-meta')?.textContent.trim() || '',
                    course: cells[1]?.textContent.trim() || '',
                    quiz: cells[2]?.textContent.trim() || '—',
                    onsite: cells[3]?.textContent.trim() || '—',
                    overall: score,
                    overallText: cells[4]?.querySelector('.st-overall-value')?.textContent.trim() || 'Pending',
                    status,
                    enrolledAt: Number(row.dataset.enrolledAt || 0)
                };
            });

            const assessed = allRecords.filter(record => Number.isFinite(record.overall));
            assessed.sort((a, b) => b.overall - a.overall || a.name.localeCompare(b.name));
            const topThree = assessed.slice(0, 3);
            const competentCount = assessed.filter(record => record.overall >= 75).length;
            const notYetCompetentCount = assessed.filter(record => record.overall < 75).length;
            const pendingCount = allRecords.length - assessed.length;
            const classAverage = assessed.length
                ? assessed.reduce((sum, record) => sum + record.overall, 0) / assessed.length
                : null;

            // Overview worksheet summarizes all trainees, while Student Records respects current filters.
            const overviewData = [
                ['LEDIPO STUDENT PERFORMANCE OVERVIEW', ''],
                ['Report Date', new Date().toLocaleDateString()],
                ['Total Trainees', allRecords.length],
                ['Assessed Trainees', assessed.length],
                ['Competent (75% and above)', competentCount],
                ['Not Yet Competent (below 75%)', notYetCompetentCount],
                ['Pending Assessment', pendingCount],
                ['Class Average (%)', classAverage === null ? 'N/A' : Number(classAverage.toFixed(2))],
                [],
                ['TOP 3 TRAINEES', ''],
                ['Rank', 'Trainee Name', 'Course', 'Quiz Average', 'Onsite Average', 'Overall Assessment (%)', 'Competency Status']
            ];

            if (topThree.length) {
                topThree.forEach((record, index) => overviewData.push([
                    index + 1,
                    record.name,
                    record.course,
                    record.quiz,
                    record.onsite,
                    Number(record.overall.toFixed(2)),
                    record.overall >= 75 ? 'Competent' : 'Not Yet Competent'
                ]));
            } else {
                overviewData.push(['', 'No completed assessments available', '', '', '', '', '']);
            }

            overviewData.push(
                [],
                ['COMPETENCY BREAKDOWN', 'Number of Trainees'],
                ['Competent', competentCount],
                ['Not Yet Competent', notYetCompetentCount],
                ['Pending Assessment', pendingCount]
            );

            const overviewSheet = XLSX.utils.aoa_to_sheet(overviewData);
            overviewSheet['!cols'] = [
                { wch: 28 }, { wch: 30 }, { wch: 30 }, { wch: 18 },
                { wch: 18 }, { wch: 24 }, { wch: 24 }
            ];
            overviewSheet['!merges'] = [
                { s: { r: 0, c: 0 }, e: { r: 0, c: 1 } },
                { s: { r: 9, c: 0 }, e: { r: 9, c: 1 } }
            ];

            const recordsData = [[
                'Trainee Name', 'Email', 'Course', 'Quiz Average (%)',
                'Onsite Average (%)', 'Overall Assessment (%)', 'Competency Status'
            ]];
            visibleRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                recordsData.push([
                    cells[0]?.querySelector('.st-trainee-name')?.textContent.trim() || '',
                    cells[0]?.querySelector('.st-trainee-meta')?.textContent.trim() || '',
                    cells[1]?.textContent.trim() || '',
                    cells[2]?.textContent.trim() || '',
                    cells[3]?.textContent.trim() || '',
                    cells[4]?.querySelector('.st-overall-value')?.textContent.trim() || 'Pending',
                    cells[5]?.textContent.trim().replace(/\s+/g, ' ') || ''
                ]);
            });

            const recordsSheet = XLSX.utils.aoa_to_sheet(recordsData);
            recordsSheet['!cols'] = [
                { wch: 28 }, { wch: 32 }, { wch: 32 }, { wch: 18 },
                { wch: 19 }, { wch: 24 }, { wch: 24 }
            ];

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, overviewSheet, 'Overview');
            XLSX.utils.book_append_sheet(workbook, recordsSheet, 'Student Records');

            const date = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(workbook, 'LEDIPO_Student_Records_' + date + '.xlsx');
        });
    }

    function filterTable() {


        const query = searchInput.value.trim().toLocaleLowerCase();


        const statusChoice = statusFilter.value;


        let visible = 0;


        rows.forEach(function (row) {


            const name = (row.dataset.name || '').toLocaleLowerCase();


            const email = (row.dataset.email || '').toLocaleLowerCase();


            const status = row.dataset.status || '';


            const matchesSearch =


                !query || name.includes(query) || email.includes(query);


            const matchesStatus =


                !statusChoice || status === statusChoice;


            const show = matchesSearch && matchesStatus;


            row.style.display = show ? '' : 'none';


            if (show) {


                visible++;


            }


        });


        const sortedRows = [...rows].sort(function (a, b) {


            const rawA = a.dataset.progress;


            const rawB = b.dataset.progress;


            const scoreA = rawA === '' ? null : Number(rawA);


            const scoreB = rawB === '' ? null : Number(rawB);


            const dateA = Number(a.dataset.enrolledAt || 0);


            const dateB = Number(b.dataset.enrolledAt || 0);


            switch (sortFilter.value) {


                case 'progress-low':


                    if (scoreA === null) return scoreB === null ? originalOrder.get(a) - originalOrder.get(b) : 1;


                    if (scoreB === null) return -1;


                    return scoreA - scoreB || originalOrder.get(a) - originalOrder.get(b);


                case 'progress-high':


                    if (scoreA === null) return scoreB === null ? originalOrder.get(a) - originalOrder.get(b) : 1;


                    if (scoreB === null) return -1;


                    return scoreB - scoreA || originalOrder.get(a) - originalOrder.get(b);


                case 'newest':


                    return dateB - dateA || originalOrder.get(a) - originalOrder.get(b);


                default:


                    return (a.dataset.name || '').localeCompare(b.dataset.name || '')


                        || originalOrder.get(a) - originalOrder.get(b);


            }


        });


        sortedRows.forEach(function (row) {


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


                    '<td colspan="6"><div class="st-empty">' +


                    '<i class="fa fa-filter" aria-hidden="true"></i>' +


                    '<p>No students match these filters. Try changing or resetting your filters.</p>' +


                    '</div></td>';


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


        resetButton.addEventListener('click', function () {


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
