<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Courses – LEDIPO</title>

    <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">
    <link rel="stylesheet" href="{{ asset('stylesheet/landing_course.css') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* =========================================================
           COURSE AVAILABILITY
        ========================================================= */

        .course-availability {
            margin-top: 14px;
            padding: 12px;
            background: #f8faf9;
            border: 1px solid #e5ebe7;
            border-radius: 10px;
        }

        .availability-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        .availability-title span:last-child {
            color: #198754;
        }

        .availability-bar {
            width: 100%;
            height: 7px;
            overflow: hidden;
            background: #e5e7eb;
            border-radius: 999px;
        }

        .availability-fill {
            height: 100%;
            background: #198754;
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        .availability-fill.full {
            background: #dc3545;
        }

        .availability-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-top: 10px;
        }

        .availability-stat {
            text-align: center;
        }

        .availability-number {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: #1f2937;
        }

        .availability-label {
            display: block;
            margin-top: 2px;
            font-size: 9px;
            color: #6b7280;
        }

        .availability-stat.enrolled .availability-number {
            color: #2563eb;
        }

        .availability-stat.remaining .availability-number {
            color: #198754;
        }

        /* =========================================================
           FULL COURSE
        ========================================================= */

        .course-full-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
            padding: 5px 9px;
            border-radius: 20px;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 10px;
            font-weight: 600;
        }

        /* =========================================================
           COURSE BANNER
        ========================================================= */

        .course-thumbnail {
            position: relative;
            width: 100%;
            height: 190px;
            overflow: hidden;
            background: #eaf5ee;
            border-radius: 14px 14px 0 0;
        }

        .course-thumbnail img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .course-card:hover .course-thumbnail img {
            transform: scale(1.04);
        }

        .course-thumbnail-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: #075b35;
            font-size: 46px;
            background: linear-gradient(135deg, #eaf5ee, #d5eadb);
        }

        .course-sector-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            max-width: calc(100% - 24px);
            padding: 6px 10px;
            border-radius: 20px;
            background: #075b35;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* =========================================================
           DATABASE COURSE DETAILS
        ========================================================= */

        .course-body {
            display: flex;
            flex-direction: column;
            height: calc(100% - 190px);
        }

        .course-body h4 {
            margin-bottom: 8px;
            color: #183329;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.45;
        }

        .course-description {
            margin-bottom: 14px;
            color: #6b7c73;
            font-size: 12px;
            line-height: 1.7;
        }

        .course-meta {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-top: 4px;
        }

        .course-meta p {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 0;
            color: #475569;
            font-size: 11px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .course-meta p i {
            width: 15px;
            min-width: 15px;
            margin-top: 3px;
            color: #075b35;
            text-align: center;
        }

        .course-detail-label {
            display: block;
            margin-bottom: 2px;
            color: #6b7c73;
            font-size: 10px;
            font-weight: 500;
        }

        .course-detail-value {
            display: block;
            color: #183329;
            font-size: 11px;
            font-weight: 600;
        }

        .course-days {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 3px;
        }

        .course-day {
            padding: 4px 7px;
            border: 1px solid #d7e9dc;
            border-radius: 6px;
            background: #eaf5ee;
            color: #075b35;
            font-size: 9px;
            font-weight: 600;
        }

        .course-facility {
            padding: 10px;
            margin-top: 13px;
            border: 1px solid #e2ebe5;
            border-radius: 9px;
            background: #fbfdfb;
        }

        .course-facility-heading {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 5px;
            color: #075b35;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .course-facility-name {
            margin: 0;
            color: #183329;
            font-size: 12px;
            font-weight: 600;
        }

        .course-facility-address {
            margin: 4px 0 0;
            color: #6b7c73;
            font-size: 10px;
            line-height: 1.5;
        }

        .course-meta-divider {
            height: 1px;
            margin: 13px 0;
            background: #e2ebe5;
        }

        .btn-view-course {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 15px;
            padding: 11px 15px;
            border: 0;
            border-radius: 9px;
            background: #075b35;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-view-course:hover {
            background: #043f26;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-view-course.disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .courses-empty {
            grid-column: 1 / -1;
            padding: 35px 20px;
            border: 1px dashed #cbd5ce;
            border-radius: 12px;
            color: #6b7c73;
            text-align: center;
        }

        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 600px) {
            .availability-stats {
                grid-template-columns: repeat(3, 1fr);
            }

            .course-thumbnail {
                height: 175px;
            }

            .course-body {
                height: auto;
            }

            .course-body h4 {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    {{-- =========================================================
         NAVIGATION
    ========================================================= --}}

    <nav class="navbar">

        <div class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo">
            <span>LEDIPO</span>
        </div>

        <ul class="nav-links">
            <li>
                <a href="{{ route('index') }}">Home</a>
            </li>

            <li>
                <a href="{{ route('landing.about') }}">About</a>
            </li>

            <li>
                <a href="{{ route('landing.courses') }}" class="active">Courses</a>
            </li>

            <li>
                <a href="{{ route('landing.contact') }}">Contact</a>
            </li>
        </ul>

        <div class="nav-actions">
            @auth
                <span style="display:flex;align-items:center;gap:7px;color:#198754;font-size:14px;font-weight:600;">
                    <i class="fas fa-user-check"></i>
                    Logged In
                </span>
            @else
                <a href="{{ route('Login') }}" class="btn-signin">
                    Sign In
                </a>

                <a href="{{ route('SignupPage') }}" class="btn-signup">
                    Sign Up
                </a>
            @endauth
        </div>

    </nav>

    {{-- =========================================================
         MAIN CONTENT
    ========================================================= --}}

    <div class="courses-wrapper">

        {{-- BREADCRUMB --}}

        <nav class="breadcrumb">
            <a href="{{ route('index') }}">Home</a>
            <span>/</span>
            <span>Courses</span>
        </nav>

        {{-- HEADER --}}

        <div class="courses-header">
            <h2>Available Courses</h2>
            <p>
                Browse our free government-accredited livelihood training programs.
            </p>
        </div>

        {{-- =====================================================
             COURSES GRID
        ===================================================== --}}

        <div class="courses-grid">

            @forelse ($courses as $course)

                @php
                    /*
                    |--------------------------------------------------------------------------
                    | ENROLLMENT AND AVAILABILITY
                    |--------------------------------------------------------------------------
                    */

                    $enrolledCount = (int) ($course->enrolled_count
                        ?? \App\Models\Enrollment_tbl::where('course_id', $course->id)->count());

                    $remainingSlots = max(0, (int) ($course->available_slots
                        ?? ((int) ($course->slots ?? 0) - $enrolledCount)));

                    $totalSlots = max(0, (int) ($course->slots ?? ($enrolledCount + $remainingSlots)));

                    $enrollmentPercentage = $totalSlots > 0
                        ? min(100, (int) round(($enrolledCount / $totalSlots) * 100))
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | TRAINING DAYS
                    |--------------------------------------------------------------------------
                    */

                    $trainingDays = $course->training_days ?? [];

                    if (is_string($trainingDays)) {
                        $trainingDays = json_decode($trainingDays, true) ?: [];
                    }

                    if (!is_array($trainingDays)) {
                        $trainingDays = [];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TRAINING TIME
                    |--------------------------------------------------------------------------
                    */

                    try {
                        $formattedStartTime = !empty($course->start_time)
                            ? \Carbon\Carbon::parse($course->start_time)->format('g:i A')
                            : null;
                    } catch (\Throwable $e) {
                        $formattedStartTime = $course->start_time ?? null;
                    }

                    try {
                        $formattedEndTime = !empty($course->end_time)
                            ? \Carbon\Carbon::parse($course->end_time)->format('g:i A')
                            : null;
                    } catch (\Throwable $e) {
                        $formattedEndTime = $course->end_time ?? null;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | COURSE DATES
                    |--------------------------------------------------------------------------
                    */

                    try {
                        $formattedStartDate = !empty($course->start_date)
                            ? \Carbon\Carbon::parse($course->start_date)->format('M d, Y')
                            : null;
                    } catch (\Throwable $e) {
                        $formattedStartDate = $course->start_date ?? null;
                    }

                    try {
                        $formattedEndDate = !empty($course->end_date)
                            ? \Carbon\Carbon::parse($course->end_date)->format('M d, Y')
                            : null;
                    } catch (\Throwable $e) {
                        $formattedEndDate = $course->end_date ?? null;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DURATION
                    |--------------------------------------------------------------------------
                    */

                    $durationDays = $course->duration_days ?? null;

                    if (!empty($durationDays) && (int) $durationDays > 0) {
                        $durationLabel = (int) $durationDays . ' ' .
                            ((int) $durationDays === 1 ? 'day' : 'days');
                    } else {
                        $durationLabel = $course->duration ?: 'Not specified';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | FACILITY DETAILS
                    |--------------------------------------------------------------------------
                    */

                    $facilityName = $course->facility?->name
                        ?? 'Facility not assigned';

                    $facilityAddress = $course->facility?->address
                        ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | COURSE BANNER
                    |--------------------------------------------------------------------------
                    */

                    $bannerImage = $course->banner_image ?? null;

                    $bannerUrl = null;

                    if (!empty($bannerImage)) {
                        $bannerUrl = \Illuminate\Support\Str::startsWith(
                            $bannerImage,
                            ['http://', 'https://']
                        )
                            ? $bannerImage
                            : asset($bannerImage);
                    }
                @endphp

                {{-- =================================================
                     COURSE CARD
                ================================================= --}}

                <div class="course-card">

                    {{-- COURSE BANNER IMAGE --}}

                    <div class="course-thumbnail">

                        @if ($bannerUrl)
                            <img
                                src="{{ $bannerUrl }}"
                                alt="{{ $course->title }}"
                                loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <div class="course-thumbnail-placeholder" style="display:none;">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @else
                            <div class="course-thumbnail-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif

                        <span class="course-sector-badge">
                            {{ $course->sector ?: 'Livelihood Training' }}
                        </span>

                    </div>

                    {{-- COURSE BODY --}}

                    <div class="course-body">

                        {{-- COURSE TITLE --}}

                        <h4>
                            {{ $course->title }}
                        </h4>

                        {{-- COURSE DESCRIPTION --}}

                        <p class="course-description">
                            {{ \Illuminate\Support\Str::limit($course->description ?: 'No course description available.', 120) }}
                        </p>

                        {{-- COURSE DETAILS FROM DATABASE --}}

                        <div class="course-meta">

                            {{-- TRAINING DAYS --}}

                            <div>
                                <p>
                                    <i class="fas fa-calendar-week"></i>
                                    <span>
                                        <span class="course-detail-label">Training Days</span>

                                        @if (count($trainingDays))
                                            <span class="course-days">
                                                @foreach ($trainingDays as $day)
                                                    <span class="course-day">
                                                        {{ $day }}
                                                    </span>
                                                @endforeach
                                            </span>
                                        @else
                                            <span class="course-detail-value">
                                                {{ $course->schedule ?: 'Schedule not specified' }}
                                            </span>
                                        @endif
                                    </span>
                                </p>
                            </div>

                            {{-- TRAINING HOURS --}}

                            <p>
                                <i class="fas fa-clock"></i>
                                <span>
                                    <span class="course-detail-label">Training Hours</span>

                                    <span class="course-detail-value">
                                        @if ($formattedStartTime && $formattedEndTime)
                                            {{ $formattedStartTime }} – {{ $formattedEndTime }}
                                        @elseif ($formattedStartTime)
                                            Starts at {{ $formattedStartTime }}
                                        @elseif ($formattedEndTime)
                                            Ends at {{ $formattedEndTime }}
                                        @else
                                            {{ $course->schedule ?: 'Time not specified' }}
                                        @endif
                                    </span>
                                </span>
                            </p>

                            {{-- TRAINING DATES --}}

                            <p>
                                <i class="fas fa-calendar-alt"></i>
                                <span>
                                    <span class="course-detail-label">Training Period</span>

                                    <span class="course-detail-value">
                                        @if ($formattedStartDate && $formattedEndDate)
                                            {{ $formattedStartDate }} – {{ $formattedEndDate }}
                                        @elseif ($formattedStartDate)
                                            Starts {{ $formattedStartDate }}
                                        @elseif ($formattedEndDate)
                                            Ends {{ $formattedEndDate }}
                                        @else
                                            Dates not specified
                                        @endif
                                    </span>
                                </span>
                            </p>

                            {{-- DURATION --}}

                            <p>
                                <i class="fas fa-hourglass-half"></i>
                                <span>
                                    <span class="course-detail-label">Duration</span>

                                    <span class="course-detail-value">
                                        {{ $durationLabel }}
                                    </span>
                                </span>
                            </p>

                        </div>

                        {{-- FACILITY INFORMATION --}}

                        <div class="course-facility">

                            <div class="course-facility-heading">
                                <i class="fas fa-building"></i>
                                Training Facility
                            </div>

                            <p class="course-facility-name">
                                {{ $facilityName }}
                            </p>

                            @if ($facilityAddress)
                                <p class="course-facility-address">
                                    <i class="fas fa-location-dot"></i>
                                    {{ $facilityAddress }}
                                </p>
                            @endif

                        </div>

                        {{-- =================================================
                             COURSE AVAILABILITY
                        ================================================= --}}

                        <div class="course-availability">

                            {{-- AVAILABILITY HEADER --}}

                            <div class="availability-title">
                                <span>
                                    <i class="fas fa-users"></i>
                                    Course Availability
                                </span>

                                <span>
                                    {{ $enrollmentPercentage }}% filled
                                </span>
                            </div>

                            {{-- PROGRESS BAR --}}

                            <div class="availability-bar">
                                <div
                                    class="availability-fill {{ $remainingSlots <= 0 ? 'full' : '' }}"
                                    style="width: {{ min(100, $enrollmentPercentage) }}%;">
                                </div>
                            </div>

                            {{-- AVAILABILITY STATISTICS --}}

                            <div class="availability-stats">

                                {{-- CAPACITY --}}

                                <div class="availability-stat">
                                    <span class="availability-number">
                                        {{ $totalSlots }}
                                    </span>

                                    <span class="availability-label">
                                        Capacity
                                    </span>
                                </div>

                                {{-- ENROLLED --}}

                                <div class="availability-stat enrolled">
                                    <span class="availability-number">
                                        {{ $enrolledCount }}
                                    </span>

                                    <span class="availability-label">
                                        Enrolled
                                    </span>
                                </div>

                                {{-- REMAINING --}}

                                <div class="availability-stat remaining">
                                    <span class="availability-number">
                                        {{ $remainingSlots }}
                                    </span>

                                    <span class="availability-label">
                                        Remaining
                                    </span>
                                </div>

                            </div>

                            {{-- FULL COURSE MESSAGE --}}

                            @if ($remainingSlots <= 0)
                                <div class="course-full-label">
                                    <i class="fas fa-ban"></i>
                                    Fully Booked
                                </div>
                            @endif

                        </div>

                        {{-- VIEW COURSE BUTTON --}}

                        <a
                            href="{{ route('landing.course.detail', $course->id) }}"
                            class="btn-view-course">

                            View Course
                            <i class="fas fa-arrow-right"></i>

                        </a>

                    </div>

                </div>

            @empty

                {{-- NO COURSES AVAILABLE --}}

                <p class="courses-empty">
                    <i class="fas fa-book-open"></i>
                    <br>
                    No courses available at the moment.
                    Please check back later.
                </p>

            @endforelse

        </div>

    </div>

</body>
</html>
