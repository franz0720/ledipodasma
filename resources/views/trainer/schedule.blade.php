{{-- resources/views/trainer/schedule.blade.php --}}

@extends('trainer.layout')

@section('title', 'Schedule')

@section('css')
  <style>
    :root {
      --sc-green: #075b35;
      --sc-green-dark: #043f26;
      --sc-green-soft: #eaf5ee;
      --sc-yellow: #e1dc36;
      --sc-ink: #183329;
      --sc-muted: #718078;
      --sc-border: #e2ebe5;
      --sc-surface: #ffffff;
    }

    .sc-wrap {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 28px;
      box-sizing: border-box;
      color: var(--sc-ink);
    }

    .sc-page-title {
      margin: 0 0 6px;
      color: var(--sc-green);
      font-size: 24px;
      font-weight: 800;
    }

    .sc-page-sub {
      margin: 0 0 24px;
      color: var(--sc-muted);
      font-size: 13px;
      line-height: 1.7;
    }

    .sc-overview-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
      margin-bottom: 22px;
    }

    .sc-overview-card {
      min-width: 0;
      padding: 20px;
      background: var(--sc-surface);
      border: 1px solid var(--sc-border);
      border-radius: 15px;
      box-shadow: 0 4px 16px rgba(2, 86, 40, .035);
    }

    .sc-overview-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 14px;
    }

    .sc-overview-label {
      color: var(--sc-muted);
      font-size: 12px;
      font-weight: 700;
    }

    .sc-overview-icon {
      display: grid;
      place-items: center;
      width: 38px;
      height: 38px;
      flex-shrink: 0;
      border-radius: 11px;
      background: var(--sc-green-soft);
      color: var(--sc-green);
    }

    .sc-overview-value {
      color: var(--sc-ink);
      font-size: 28px;
      font-weight: 800;
      line-height: 1.3;
      overflow-wrap: anywhere;
    }

    .sc-overview-note {
      margin-top: 8px;
      color: var(--sc-muted);
      font-size: 11px;
      line-height: 1.6;
    }

    .sc-capacity-track {
      height: 7px;
      overflow: hidden;
      margin-top: 13px;
      border-radius: 20px;
      background: #eaf0eb;
    }

    .sc-capacity-fill {
      height: 100%;
      max-width: 100%;
      border-radius: inherit;
      background: var(--sc-yellow);
      transition: width .25s ease;
    }

    .sc-card {
      width: 100%;
      max-width: 100%;
      overflow: hidden;
      margin-bottom: 22px;
      background: var(--sc-surface);
      border: 1px solid var(--sc-border);
      border-radius: 15px;
      box-shadow: 0 4px 16px rgba(2, 86, 40, .04);
    }

    .sc-card-header {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 22px;
      background: var(--sc-green);
    }

    .sc-card-icon {
      display: grid;
      place-items: center;
      width: 46px;
      height: 46px;
      flex-shrink: 0;
      border-radius: 12px;
      background: rgba(255, 255, 255, .12);
      color: var(--sc-yellow);
      font-size: 19px;
    }

    .sc-card-heading {
      flex: 1;
      min-width: 0;
    }

    .sc-card-course {
      color: #fff;
      font-size: 17px;
      font-weight: 800;
      line-height: 1.5;
      overflow-wrap: anywhere;
    }

    .sc-card-subtitle {
      margin-top: 4px;
      color: rgba(255, 255, 255, .75);
      font-size: 11px;
      line-height: 1.6;
      overflow-wrap: anywhere;
    }

    .sc-info-list {
      padding: 6px 0;
    }

    .sc-info-row {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 17px 22px;
      border-bottom: 1px solid #edf2ee;
    }

    .sc-info-row:last-child {
      border-bottom: 0;
    }

    .sc-info-icon {
      display: grid;
      place-items: center;
      width: 38px;
      height: 38px;
      flex-shrink: 0;
      border-radius: 10px;
      background: var(--sc-green-soft);
      color: var(--sc-green);
    }

    .sc-info-content {
      flex: 1;
      min-width: 0;
    }

    .sc-info-label {
      margin-bottom: 5px;
      color: #829087;
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .07em;
    }

    .sc-info-value {
      color: var(--sc-ink);
      font-size: 14px;
      font-weight: 650;
      line-height: 1.6;
      overflow-wrap: anywhere;
    }

    .sc-facility-address {
      display: flex;
      align-items: flex-start;
      gap: 6px;
      margin-top: 5px;
      color: var(--sc-muted);
      font-size: 12px;
      line-height: 1.6;
      overflow-wrap: anywhere;
    }

    .sc-facility-address i {
      flex-shrink: 0;
      margin-top: 4px;
      color: #43835d;
    }

    .sc-days {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
    }

    .sc-day-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 5px 9px;
      border: 1px solid #c8e6c9;
      border-radius: 6px;
      background: var(--sc-green-soft);
      color: var(--sc-green);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .03em;
    }

    .sc-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 11px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 800;
    }

    .sc-status.active {
      background: #e8f5e9;
      color: var(--sc-green);
    }

    .sc-status.inactive {
      background: #fff1d6;
      color: #805600;
    }

    .sc-deadline-list {
      padding: 6px 0;
    }

    .sc-deadline-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px 22px;
      border-bottom: 1px solid #edf2ee;
    }

    .sc-deadline-item:last-child {
      border-bottom: 0;
    }

    .sc-deadline-date {
      min-width: 68px;
      padding: 9px 6px;
      border-radius: 9px;
      background: var(--sc-green-soft);
      color: var(--sc-green);
      text-align: center;
      font-size: 11px;
      font-weight: 800;
    }

    .sc-deadline-content {
      flex: 1;
      min-width: 0;
    }

    .sc-deadline-title {
      color: var(--sc-ink);
      font-size: 13px;
      font-weight: 750;
      overflow-wrap: anywhere;
    }

    .sc-deadline-meta {
      margin-top: 4px;
      color: var(--sc-muted);
      font-size: 11px;
      line-height: 1.6;
    }

    .sc-empty {
      padding: 40px 24px;
      border: 1px dashed #d7e5da;
      border-radius: 14px;
      background: #fbfdfb;
      text-align: center;
    }

    .sc-empty-icon {
      display: grid;
      place-items: center;
      width: 56px;
      height: 56px;
      margin: 0 auto 15px;
      border-radius: 16px;
      background: var(--sc-green-soft);
      color: #43835d;
      font-size: 23px;
    }

    .sc-empty-title {
      margin: 0 0 6px;
      color: var(--sc-ink);
      font-size: 15px;
      font-weight: 800;
    }

    .sc-empty p {
      margin: 0;
      color: #829087;
      font-size: 12px;
      line-height: 1.7;
    }

    @media (max-width: 768px) {
      .sc-wrap {
        padding: 22px 16px;
      }

      .sc-overview-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }

      .sc-overview-card {
        padding: 16px;
      }

      .sc-card-header {
        padding: 18px;
      }

      .sc-info-row,
      .sc-deadline-item {
        padding: 15px 16px;
        gap: 11px;
      }
    }

    @media (max-width: 420px) {
      .sc-wrap {
        padding: 18px 12px;
      }

      .sc-card-header {
        gap: 11px;
        padding: 16px;
      }

      .sc-card-icon {
        width: 40px;
        height: 40px;
      }

      .sc-card-course {
        font-size: 14px;
      }

      .sc-info-row,
      .sc-deadline-item {
        padding: 14px 12px;
      }

      .sc-info-value {
        font-size: 13px;
      }
    }
  </style>
@endsection

@section('content')
  <div class="sc-wrap">

    <h1 class="sc-page-title">Training Schedule</h1>

    <p class="sc-page-sub">
      View your assigned training course, training days, training period,
      facility, enrollment capacity, and deadlines.
    </p>

    @if ($course)

      @php
        /*
         * DATABASE VALUES
         * Course columns include:
         * slots, status, schedule, duration_days,
         * start_date, end_date, training_days, facility_id.
         */

        $slotLimit = max(0, (int) ($course->slots ?? 0));
        $enrolledCount = max(0, (int) ($totalStudents ?? 0));
        $remainingSlots = max(0, $slotLimit - $enrolledCount);

        $capacityRate =
            $slotLimit > 0
                ? min(100, round(($enrolledCount / $slotLimit) * 100))
                : null;

        $courseStatus = strtolower(
            trim((string) ($course->status ?? 'unknown')),
        );

        $isActive = $courseStatus === 'active';

        // Correct database column: duration_days
        $durationDays = max(0, (int) ($course->duration_days ?? 0));

        // Training days are stored as a JSON array in the database.
        $trainingDays = $course->training_days ?? [];

        if (is_string($trainingDays)) {
            $decodedDays = json_decode($trainingDays, true);

            if (is_array($decodedDays)) {
                $trainingDays = $decodedDays;
            } elseif (trim($trainingDays) !== '') {
                $trainingDays = explode(',', $trainingDays);
            } else {
                $trainingDays = [];
            }
        }

        if (!is_array($trainingDays)) {
            $trainingDays = [];
        }

        $dayOrder = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $normalizedDays = collect($trainingDays)
            ->map(fn($day) => strtolower(trim((string) $day)))
            ->filter(fn($day) => array_key_exists($day, $dayOrder))
            ->unique()
            ->all();

        $orderedDays = array_values(
            array_filter(
                array_keys($dayOrder),
                fn($day) => in_array($day, $normalizedDays, true),
            ),
        );

        // Format database dates safely.
        $startDate = $course->start_date
            ? \Illuminate\Support\Carbon::parse($course->start_date)
            : null;

        $endDate = $course->end_date
            ? \Illuminate\Support\Carbon::parse($course->end_date)
            : null;

        // Facility is loaded through the Course_tbl relationship.
        $facility = $course->facility ?? null;
      @endphp

      {{-- OVERVIEW CARDS --}}
      <div class="sc-overview-grid">

        {{-- ENROLLMENT OVERVIEW --}}
        <div class="sc-overview-card">
          <div class="sc-overview-top">
            <span class="sc-overview-label">Enrolled trainees</span>

            <span class="sc-overview-icon">
              <i class="fa fa-users"></i>
            </span>
          </div>

          <div class="sc-overview-value">
            {{ $enrolledCount }}

            <span style="font-size:14px;color:var(--sc-muted);font-weight:600;">
              / {{ $slotLimit > 0 ? $slotLimit : 'No limit' }}
            </span>
          </div>

          <div class="sc-overview-note">
            @if ($slotLimit > 0)
              {{ $remainingSlots }}
              {{ \Illuminate\Support\Str::plural('slot', $remainingSlots) }}
              remaining
            @else
              No slot limit configured
            @endif
          </div>

          @if ($capacityRate !== null)
            <div class="sc-capacity-track" role="progressbar"
              aria-label="Course enrollment capacity" aria-valuemin="0"
              aria-valuemax="100" aria-valuenow="{{ $capacityRate }}">

              <div class="sc-capacity-fill" style="width:{{ $capacityRate }}%;">
              </div>
            </div>

            <div class="sc-overview-note">
              {{ $capacityRate }}% of course capacity filled
            </div>
          @endif
        </div>

        {{-- COURSE STATUS --}}
        <div class="sc-overview-card">
          <div class="sc-overview-top">
            <span class="sc-overview-label">Course status</span>

            <span class="sc-overview-icon">
              <i class="fa fa-circle-info"></i>
            </span>
          </div>

          <div class="sc-overview-value" style="font-size:22px;">
            {{ ucfirst($courseStatus) }}
          </div>

          <div class="sc-overview-note">
            @if ($isActive)
              This course is currently active.
            @else
              Check with the administrator if you need clarification
              about this course's status.
            @endif
          </div>
        </div>

      </div>

      {{-- COURSE INFORMATION --}}
      <section class="sc-card" aria-labelledby="scCourseTitle">

        <div class="sc-card-header">
          <div class="sc-card-icon">
            <i class="fa fa-book"></i>
          </div>

          <div class="sc-card-heading">
            <div class="sc-card-course" id="scCourseTitle">
              {{ $course->title ?? 'Training course' }}
            </div>

            <div class="sc-card-subtitle">
              {{ $course->course_code ?? 'Training course' }}
            </div>
          </div>
        </div>

        <div class="sc-info-list">

          {{-- TRAINING DAYS --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-calendar-week"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Training days</div>

              @if (count($orderedDays) > 0)
                <div class="sc-days">
                  @foreach ($orderedDays as $day)
                    <span class="sc-day-badge">
                      {{ $dayOrder[$day] }}
                    </span>
                  @endforeach
                </div>
              @else
                <div class="sc-info-value" style="color:#829087;">
                  Training days not set
                </div>
              @endif
            </div>
          </div>

          {{-- TRAINING SCHEDULE --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-clock"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Training time</div>

              @php
                $startTime = $course->start_time
                    ? \Illuminate\Support\Carbon::parse(
                        $course->start_time,
                    )->format('g:i A')
                    : null;

                $endTime = $course->end_time
                    ? \Illuminate\Support\Carbon::parse(
                        $course->end_time,
                    )->format('g:i A')
                    : null;
              @endphp

              @if ($startTime && $endTime)
                <div class="sc-info-value">
                  {{ $startTime }} – {{ $endTime }}
                </div>
              @elseif ($startTime)
                <div class="sc-info-value">
                  Starts at {{ $startTime }}
                </div>
                <div class="sc-overview-note">End time not set</div>
              @elseif ($endTime)
                <div class="sc-info-value">
                  Ends at {{ $endTime }}
                </div>
                <div class="sc-overview-note">Start time not set</div>
              @else
                <div class="sc-info-value" style="color:#829087;">
                  Training time not set
                </div>
              @endif

              @if (!empty($course->schedule))
                <div class="sc-overview-note">
                  {{ $course->schedule }}
                </div>
              @endif
            </div>
          </div>

          {{-- COURSE DURATION --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-hourglass-half"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Course duration</div>

              <div class="sc-info-value">
                @if ($durationDays > 0)
                  {{ $durationDays }}
                  {{ \Illuminate\Support\Str::plural('day', $durationDays) }}
                @else
                  Duration not set
                @endif
              </div>
            </div>
          </div>

          {{-- TRAINING PERIOD --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-calendar-days"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Training period</div>

              <div class="sc-info-value">
                @if ($startDate && $endDate)
                  {{ $startDate->format('F d, Y') }}

                  <span style="color:#829087;font-weight:500;">
                    to
                  </span>

                  {{ $endDate->format('F d, Y') }}
                @elseif ($startDate)
                  Starts {{ $startDate->format('F d, Y') }}

                  <div style="color:#829087;font-size:12px;">
                    End date not set
                  </div>
                @elseif ($endDate)
                  <div style="color:#829087;font-size:12px;">
                    Start date not set
                  </div>

                  Ends {{ $endDate->format('F d, Y') }}
                @else
                  Training dates not set
                @endif
              </div>
            </div>
          </div>

          {{-- TRAINING FACILITY --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-building"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Training facility</div>

              <div class="sc-info-value">
                {{ $facility?->name ?? 'No facility assigned' }}
              </div>

              @if (!empty($facility?->address))
                <div class="sc-facility-address">
                  <i class="fa fa-location-dot"></i>

                  <span>
                    {{ $facility->address }}
                  </span>
                </div>
              @elseif (!empty($course->location))
                <div class="sc-facility-address">
                  <i class="fa fa-location-dot"></i>

                  <span>
                    {{ $course->location }}
                  </span>
                </div>
              @endif
            </div>
          </div>

          {{-- ENROLLMENT CAPACITY --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-users"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Enrollment capacity</div>

              <div class="sc-info-value">
                {{ $enrolledCount }} enrolled

                @if ($slotLimit > 0)
                  out of {{ $slotLimit }} slots
                @else
                  · No slot limit configured
                @endif
              </div>

              @if ($capacityRate !== null)
                <div class="sc-capacity-track">
                  <div class="sc-capacity-fill"
                    style="width:{{ $capacityRate }}%;">
                  </div>
                </div>
              @endif
            </div>
          </div>

          {{-- COURSE STATUS --}}
          <div class="sc-info-row">
            <div class="sc-info-icon">
              <i class="fa fa-circle-info"></i>
            </div>

            <div class="sc-info-content">
              <div class="sc-info-label">Course status</div>

              <div class="sc-info-value">
                <span class="sc-status {{ $isActive ? 'active' : 'inactive' }}">
                  <i
                    class="fa {{ $isActive ? 'fa-circle-check' : 'fa-circle-pause' }}"></i>

                  {{ ucfirst($courseStatus) }}
                </span>
              </div>
            </div>
          </div>

        </div>
      </section>
    @else
      {{-- NO ASSIGNED COURSE --}}
      <div class="sc-empty">
        <div class="sc-empty-icon">
          <i class="fa fa-calendar-xmark"></i>
        </div>

        <h3 class="sc-empty-title">No course assigned</h3>

        <p>
          You don't have an assigned training course yet.
          Please contact the administrator for assistance.
        </p>
      </div>

    @endif

  </div>
@endsection

