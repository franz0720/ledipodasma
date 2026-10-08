<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{{ $course->title }} – LEDIPO</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">

  <link rel="stylesheet"
    href="{{ asset('stylesheet/landing_course_detail.css') }}">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  <style>
    /* =========================================================
           ENROLLMENT BUTTONS
        ========================================================= */
    .btn-enroll {
      width: 100%;
      min-height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      background: #198754;
      color: #ffffff;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      box-sizing: border-box;
    }

    .btn-enroll:hover {
      background: #157347;
      transform: translateY(-1px);
    }

    .btn-enroll:disabled {
      transform: none;
    }

    .btn-enroll.enrolled {
      background: #198754;
      cursor: not-allowed;
    }

    .btn-enroll.full {
      background: #9ca3af;
      opacity: 0.7;
      cursor: not-allowed;
    }

    .btn-signin-outline {
      width: 100%;
      min-height: 46px;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      margin-top: 10px;
      padding: 12px 18px;
      border: 1px solid #198754;
      border-radius: 10px;
      background: #ffffff;
      color: #198754;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-signin-outline:hover {
      background: #f0fdf4;
    }

    /* =========================================================
           SLOT SUMMARY
        ========================================================= */
    .slot-summary {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-top: 18px;
    }

    .slot-box {
      padding: 14px 8px;
      text-align: center;
      background: #f8faf9;
      border: 1px solid #e5ebe7;
      border-radius: 12px;
    }

    .slot-box-icon {
      width: 35px;
      height: 35px;
      margin: 0 auto 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: #e8f5ed;
      color: #198754;
    }

    .slot-box-number {
      display: block;
      font-size: 20px;
      font-weight: 700;
      line-height: 1.2;
      color: #1f2937;
    }

    .slot-box-label {
      display: block;
      margin-top: 4px;
      font-size: 10px;
      line-height: 1.3;
      color: #6b7280;
    }

    .slot-box.enrolled .slot-box-number {
      color: #2563eb;
    }

    .slot-box.remaining .slot-box-number {
      color: #198754;
    }

    /* =========================================================
           ENROLLMENT PROGRESS
        ========================================================= */
    .slot-progress-wrapper {
      margin-top: 18px;
    }

    .slot-progress-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 7px;
      font-size: 12px;
      color: #6b7280;
    }

    .slot-progress-header strong {
      color: #374151;
    }

    .slot-progress {
      width: 100%;
      height: 8px;
      overflow: hidden;
      background: #e5e7eb;
      border-radius: 999px;
    }

    .slot-progress-bar {
      height: 100%;
      background: #198754;
      border-radius: 999px;
      transition: width 0.3s ease;
    }

    .slot-progress-bar.full {
      background: #dc3545;
    }

    /* =========================================================
           ALERTS
        ========================================================= */
    .enrollment-alert {
      margin-bottom: 20px;
      padding: 14px 18px;
      border-radius: 10px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
    }

    .enrollment-alert.success {
      color: #166534;
      background: #dcfce7;
      border: 1px solid #86efac;
    }

    .enrollment-alert.error {
      color: #991b1b;
      background: #fee2e2;
      border: 1px solid #fca5a5;
    }

    .enrollment-alert i {
      margin-right: 7px;
    }

    /* =========================================================
           MOBILE
        ========================================================= */
    @media (max-width: 600px) {
      .slot-summary {
        grid-template-columns: repeat(3, 1fr);
        gap: 5px;
      }

      .slot-box {
        padding: 10px 4px;
      }

      .slot-box-number {
        font-size: 17px;
      }

      .slot-box-label {
        font-size: 9px;
      }
    }
  </style>

</head>

<body>

  {{-- =========================================================
         NAVIGATION
    ========================================================= --}}

  <nav class="main-nav">

    <div class="nav-logo">

      <a href="{{ route('index') }}" class="logo-link">
        <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo">
      </a>

      <span>LEDIPO</span>

    </div>

    <ul class="nav-links">

      <li>
        <a href="{{ route('index') }}#home">
          Home
        </a>
      </li>

      <li>
        <a href="{{ route('index') }}#about">
          About
        </a>
      </li>

      <li>
        <a href="{{ route('index') }}#courses" class="active">
          Courses
        </a>
      </li>

      <li>
        <a href="{{ route('index') }}#contact">
          Contact
        </a>
      </li>

    </ul>

    {{-- =====================================================
             NAVIGATION ACTIONS
        ====================================================== --}}

    <div class="nav-actions">

      @auth

        <a href="{{ route('homepage') }}" class="btn-signin">

          <i class="fas fa-house"></i>
          Homepage

        </a>
      @else
        <a href="{{ route('Login') }}" class="btn-signin">

          Login

        </a>

        <a href="{{ route('SignupPage') }}" class="btn-signup">

          Sign Up

        </a>

      @endauth

    </div>

  </nav>

  {{-- =========================================================
         COURSE DETAIL WRAPPER
    ========================================================= --}}

  <div class="course-detail-wrapper">

    {{-- =====================================================
             SUCCESS MESSAGE
        ====================================================== --}}

    @if (session('success'))
      <div class="enrollment-alert success">

        <i class="fas fa-circle-check"></i>

        {{ session('success') }}

      </div>
    @endif

    {{-- =====================================================
             ERROR MESSAGE
        ====================================================== --}}

    @if (session('error'))
      <div class="enrollment-alert error">

        <i class="fas fa-circle-exclamation"></i>

        {{ session('error') }}

      </div>
    @endif

    {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}

    @if ($errors->any())
      <div class="enrollment-alert error">

        <i class="fas fa-circle-exclamation"></i>

        {{ $errors->first() }}

      </div>
    @endif

    {{-- =====================================================
             ENROLLMENT DATA
        ====================================================== --}}

    @php

      /*
            |--------------------------------------------------------------------------
            | Count enrolled students
            |--------------------------------------------------------------------------
            */

      $enrolledCount = \App\Models\Enrollment_tbl::where(
          'course_id',
          $course->id,
      )->count();

      /*
            |--------------------------------------------------------------------------
            | Remaining slots
            |--------------------------------------------------------------------------
            */

      $availableSlots = max(0, (int) $course->available_slots);

      /*
            |--------------------------------------------------------------------------
            | Total capacity
            |--------------------------------------------------------------------------
            */

      $totalSlots = $enrolledCount + $availableSlots;

      /*
            |--------------------------------------------------------------------------
            | Enrollment percentage
            |--------------------------------------------------------------------------
            */

      $enrollmentPercentage =
          $totalSlots > 0 ? round(($enrolledCount / $totalSlots) * 100) : 0;

      /*
                  |--------------------------------------------------------------------------
                  | Check current student's enrollment
            |--------------------------------------------------------------------------
            */

$isEnrolled = false;

if (auth()->check()) {
    $isEnrolled = \App\Models\Enrollment_tbl::where(
        'user_id',
        auth()->id(),
    )
        ->where('course_id', $course->id)
              ->exists();
      }
    @endphp

    {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

    <nav class="breadcrumb">

      <a href="{{ route('index') }}#home">
        Home
      </a>

      <span>/</span>

      <a href="{{ route('index') }}#courses">
        Courses
      </a>

      <span>/</span>

      <span>
        {{ $course->title }}
      </span>

    </nav>

    {{-- =====================================================
             COURSE BANNER
        ====================================================== --}}

    <div class="course-banner">

      <span class="course-banner-sector">
        {{ $course->sector }}
      </span>

      <h1>
        {{ $course->title }}
      </h1>

      <p>
        {{ $course->description }}
      </p>

      <div class="course-banner-meta">

        <span>
          <i class="fas fa-clock"></i>
          {{ $course->schedule }}
        </span>

        <span>
          <i class="fas fa-calendar"></i>
          {{ $course->duration }}
        </span>

        <span>
          <i class="fas fa-location-dot"></i>
          {{ $course->location }}
        </span>

        <span>
          <i class="fas fa-users"></i>
          {{ $availableSlots }} slots remaining
        </span>

      </div>

    </div>

    {{-- =====================================================
             CONTENT GRID
        ====================================================== --}}

    <div class="course-detail-grid">

      {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

      <div>

        {{-- COURSE OBJECTIVES --}}

        <div class="detail-card">

          <h4>
            <i class="fas fa-bullseye"></i>
            Course Objectives
          </h4>

          <p>
            {{ $course->objectives }}
          </p>

        </div>

        {{-- SCHEDULE & LOCATION --}}

        <div class="detail-card">

          <h4>
            <i class="fas fa-calendar-days"></i>
            Schedule & Location
          </h4>

          <div class="schedule-list">

            {{-- SCHEDULE --}}

            <div class="schedule-item">

              <div class="schedule-icon">
                <i class="fas fa-clock"></i>
              </div>

              <div class="schedule-info">

                <p>Schedule</p>

                <p>
                  {{ $course->schedule }}
                </p>

              </div>

            </div>

            {{-- DURATION --}}

            <div class="schedule-item">

              <div class="schedule-icon">
                <i class="fas fa-hourglass"></i>
              </div>

              <div class="schedule-info">

                <p>Duration</p>

                <p>
                  {{ $course->duration }}
                </p>

              </div>

            </div>

            {{-- LOCATION --}}

            <div class="schedule-item">

              <div class="schedule-icon">
                <i class="fas fa-location-dot"></i>
              </div>

              <div class="schedule-info">

                <p>Location</p>

                <p>
                  {{ $course->location }}
                </p>

              </div>

            </div>

          </div>

        </div>

      </div>

      {{-- =================================================
                 RIGHT SIDEBAR
            ================================================== --}}

      <div>

        {{-- =================================================
                     JOIN CARD
                ================================================== --}}

        <div class="sidebar-card">

          <h4>
            Ready to Join?
          </h4>

          @auth

            @if ($isEnrolled)
              <p>
                You are already enrolled in this course.
              </p>
            @else
              <p>
                Enroll now to join this course for free!
              </p>
            @endif
          @else
            <p>
              Sign up or log in to enroll in this course
              for free!
            </p>

          @endauth

          {{-- =================================================
                         SLOT SUMMARY
                    ================================================== --}}

          <div class="slot-summary">

            {{-- CAPACITY --}}

            <div class="slot-box">

              <div class="slot-box-icon">
                <i class="fas fa-users"></i>
              </div>

              <span class="slot-box-number">
                {{ $totalSlots }}
              </span>

              <span class="slot-box-label">
                Course Capacity
              </span>

            </div>

            {{-- ENROLLED --}}

            <div class="slot-box enrolled">

              <div class="slot-box-icon">
                <i class="fas fa-user-check"></i>
              </div>

              <span class="slot-box-number">
                {{ $enrolledCount }}
              </span>

              <span class="slot-box-label">
                Already Enrolled
              </span>

            </div>

            {{-- REMAINING --}}

            <div class="slot-box remaining">

              <div class="slot-box-icon">
                <i class="fas fa-ticket"></i>
              </div>

              <span class="slot-box-number">
                {{ $availableSlots }}
              </span>

              <span class="slot-box-label">
                Slots Remaining
              </span>

            </div>

          </div>

          {{-- =================================================
                         PROGRESS
                    ================================================== --}}

          <div class="slot-progress-wrapper">

            <div class="slot-progress-header">

              <span>
                Enrollment
              </span>

              <strong>
                {{ $enrollmentPercentage }}%
              </strong>

            </div>

            <div class="slot-progress">

              <div
                class="slot-progress-bar {{ $availableSlots <= 0 ? 'full' : '' }}"
                style="width: {{ min(100, $enrollmentPercentage) }}%;">
              </div>

            </div>

          </div>

          {{-- =================================================
                         COURSE META
                    ================================================== --}}

          <div class="sidebar-meta" style="margin-top: 20px;">

            <div class="sidebar-meta-row">

              <span>
                Duration
              </span>

              <span>
                {{ $course->duration }}
              </span>

            </div>

            <div class="sidebar-meta-row">

              <span>
                Schedule
              </span>

              <span>
                {{ $course->schedule }}
              </span>

            </div>

            <div class="sidebar-meta-row">

              <span>
                Slots Available
              </span>

              <span class="highlight">
                {{ $availableSlots }}
              </span>

            </div>

            <div class="sidebar-meta-row">

              <span>
                Fee
              </span>

              <span class="highlight">
                FREE
              </span>

            </div>

          </div>

        </div>

        <hr class="sidebar-divider">

        {{-- =================================================
                     ENROLLMENT ACTIONS
                ================================================== --}}

        @auth

          {{-- =================================================
                         LOGGED-IN STUDENT
                    ================================================== --}}

          @if ($isEnrolled)
            {{-- ALREADY ENROLLED --}}

            <button type="button" class="btn-enroll enrolled" disabled>

              <i class="fas fa-circle-check"></i>

              Already Enrolled

            </button>

            {{-- HOMEPAGE --}}

            <a href="{{ route('homepage') }}" class="btn-signin-outline">

              <i class="fas fa-house"></i>

              Go to Homepage

            </a>
          @elseif ($availableSlots > 0)
            {{-- =================================================
                             ENROLL NOW
                        ================================================== --}}

            <form action="{{ route('course.enroll', $course->id) }}"
              method="POST">

              @csrf

              <button type="submit" class="btn-enroll">

                <i class="fas fa-user-plus"></i>

                Enroll Now

              </button>

            </form>
          @else
            {{-- FULLY BOOKED --}}

            <button type="button" class="btn-enroll full" disabled>

              <i class="fas fa-ban"></i>

              Fully Booked

            </button>
          @endif
        @else
          {{-- =================================================
                         GUEST
                    ================================================== --}}

          @if ($availableSlots > 0)
            {{-- SIGN UP --}}

            <a href="{{ route('SignupPage') }}" class="btn-enroll">

              <i class="fas fa-user-plus"></i>

              Sign Up to Enroll

            </a>

            {{-- LOGIN --}}

            <a href="{{ route('Login') }}" class="btn-signin-outline">

              <i class="fas fa-right-to-bracket"></i>

              Already have an account?
              Sign In

            </a>
          @else
            {{-- FULLY BOOKED --}}

            <button type="button" class="btn-enroll full" disabled>

              <i class="fas fa-ban"></i>

              Fully Booked

            </button>

            <a href="{{ route('Login') }}" class="btn-signin-outline">

              <i class="fas fa-right-to-bracket"></i>

              Already have an account?
              Sign In

            </a>
          @endif

        @endauth

      </div>

    </div>

  </div>

</body>

</html>
