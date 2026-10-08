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
      grid-template-columns:
        repeat(3, 1fr);
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
           MOBILE
        ========================================================= */
    @media (max-width: 600px) {
      .availability-stats {
        grid-template-columns:
          repeat(3, 1fr);
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

      <img src="{{ asset('images/logo.png') }}" alt="Logo">

      <span>
        LEDIPO
      </span>

    </div>

    <ul class="nav-links">

      <li>

        <a href="{{ route('index') }}">

          Home

        </a>

      </li>

      <li>

        <a href="{{ route('landing.about') }}">

          About

        </a>

      </li>

      <li>

        <a href="{{ route('landing.courses') }}" class="active">

          Courses

        </a>

      </li>

      <li>

        <a href="{{ route('landing.contact') }}">

          Contact

        </a>

      </li>

    </ul>

    <div class="nav-actions">

      @auth

        <span
          style="
                        display:flex;
                        align-items:center;
                        gap:7px;
                        color:#198754;
                        font-size:14px;
                        font-weight:600;
                    ">

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

    {{-- =====================================================
             BREADCRUMB
        ===================================================== --}}

    <nav class="breadcrumb">

      <a href="{{ route('index') }}">

        Home

      </a>

      <span>/</span>

      <span>

        Courses

      </span>

    </nav>

    {{-- =====================================================
             HEADER
        ===================================================== --}}

    <div class="courses-header">

      <h2>

        Available Courses

      </h2>

      <p>

        Browse our free government-accredited
        livelihood training programs.

      </p>

    </div>

    {{-- =====================================================
             COURSES GRID
        ===================================================== --}}

    <div class="courses-grid">

      @forelse($courses as $course)

        {{-- =================================================
                     COURSE AVAILABILITY CALCULATION
                ================================================= --}}

        @php

          /*
                    |--------------------------------------------------------------------------
                    | Count current enrollments
                    |--------------------------------------------------------------------------
                    */

          $enrolledCount = \App\Models\Enrollment_tbl::where(
              'course_id',
              $course->id,
          )->count();

          /*
                    |--------------------------------------------------------------------------
                    | Remaining slots
                    |
                    | We use available_slots because this is what
                    | your course-detail page currently uses.
                    |--------------------------------------------------------------------------
                    */

          $remainingSlots = max(0, (int) $course->available_slots);

          /*
                    |--------------------------------------------------------------------------
                    | Total capacity
                    |--------------------------------------------------------------------------
                    */

          $totalSlots = $enrolledCount + $remainingSlots;

          /*
                    |--------------------------------------------------------------------------
                    | Enrollment percentage
                    |--------------------------------------------------------------------------
                    */

          $enrollmentPercentage =
              $totalSlots > 0 ? round(($enrolledCount / $totalSlots) * 100) : 0;
        @endphp

        {{-- =================================================
                     COURSE CARD
                ================================================= --}}

        <div class="course-card">

          {{-- COURSE THUMBNAIL --}}

          <div class="course-thumbnail">

            <i class="fas fa-book"></i>

            <span class="course-sector-badge">

              {{ $course->sector }}

            </span>

          </div>

          {{-- COURSE BODY --}}

          <div class="course-body">

            <h4>

              {{ $course->title }}

            </h4>

            <p>

              {{ Str::limit($course->description, 100) }}

            </p>

            {{-- COURSE META --}}

            <div class="course-meta">

              <p>

                <i class="fas fa-clock"></i>

                {{ $course->schedule }}

              </p>

              <p>

                <i class="fas fa-calendar"></i>

                {{ $course->duration }}

              </p>

              <p>

                <i class="fas fa-location-dot"></i>

                {{ Str::limit($course->location, 35) }}

              </p>

            </div>

            {{-- =================================================
                             AVAILABILITY
                        ================================================= --}}

            <div class="course-availability">

              {{-- HEADER --}}

              <div class="availability-title">

                <span>

                  <i class="fas fa-users"></i>

                  Course Availability

                </span>

                <span>

                  {{ $enrollmentPercentage }}%
                  filled

                </span>

              </div>

              {{-- PROGRESS BAR --}}

              <div class="availability-bar">

                <div
                  class="
                                        availability-fill
                                        {{ $remainingSlots <= 0 ? 'full' : '' }}
                                    "
                  style="
                                        width:
                                        {{ min(100, $enrollmentPercentage) }}%;
                                    ">

                </div>

              </div>

              {{-- =================================================
                                 STATISTICS
                            ================================================= --}}

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

                <div
                  class="
                                        availability-stat
                                        enrolled
                                    ">

                  <span class="availability-number">

                    {{ $enrolledCount }}

                  </span>

                  <span class="availability-label">

                    Enrolled

                  </span>

                </div>

                {{-- REMAINING --}}

                <div
                  class="
                                        availability-stat
                                        remaining
                                    ">

                  <span class="availability-number">

                    {{ $remainingSlots }}

                  </span>

                  <span class="availability-label">

                    Remaining

                  </span>

                </div>

              </div>

              {{-- FULL MESSAGE --}}

              @if ($remainingSlots <= 0)
                <div class="course-full-label">

                  <i class="fas fa-ban"></i>

                  Fully Booked

                </div>
              @endif

            </div>

            {{-- =================================================
                             VIEW COURSE
                        ================================================= --}}

            <a href="{{ route('landing.course.detail', $course->id) }}"
              class="btn-view-course">

              View Course

            </a>

          </div>

        </div>

      @empty

        {{-- NO COURSES --}}

        <p class="courses-empty">

          No courses available at the moment.

        </p>
      @endforelse

    </div>

  </div>

</body>

</html>
