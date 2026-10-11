<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Dasmariñas Training')</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/homepage.css') }}">

  <link rel="stylesheet" href="{{ asset('stylesheet/layout.css') }}">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Montserrat:wght@400;600;700;800;900&family=Poppins:wght@600;700;800&display=swap"
    rel="stylesheet">

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

  <!-- Tab Icon -->

  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">

  @yield('css')

  <style>
    /* =========================================================
      LOGGED-IN PROFILE AVATAR / DROPDOWN
      Matches the student homepage profile-circle design
      ========================================================= */
    .landing-profile {
      position: relative;
      display: flex;
      align-items: center;
    }

    .landing-avatar-btn {
      width: 46px;
      height: 46px;
      padding: 0;
      border: 2px solid #ffffff;
      border-radius: 50%;
      background: #fff200;
      color: #006b35;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 800;
      line-height: 1;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .landing-avatar-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 14px rgba(0, 0, 0, 0.16);
    }

    .landing-avatar-btn:focus-visible {
      outline: 3px solid rgba(212, 209, 32, 0.45);
      outline-offset: 2px;
    }

    .landing-profile-dropdown {
      position: absolute;
      top: calc(100% + 12px);
      right: 0;
      width: 250px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      transform: translateY(-8px);
      transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
      z-index: 9999;
    }

    .landing-profile-dropdown.open {
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
      transform: translateY(0);
    }

    .landing-dropdown-header {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      background: #f8faf9;
      border-bottom: 1px solid #eef1ef;
    }

    .landing-dd-avatar {
      width: 42px;
      height: 42px;
      min-width: 42px;
      border-radius: 50%;
      background: #fff200;
      color: #006b35;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 800;
    }

    .landing-user-info {
      min-width: 0;
    }

    .landing-dh-name {
      color: #1f2937;
      font-size: 13px;
      font-weight: 700;
      line-height: 1.35;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .landing-dh-role {
      margin-top: 3px;
      color: #718096;
      font-size: 11px;
      font-weight: 600;
      text-transform: capitalize;
    }

    /* ── MODAL STYLES ────────────────────────────────────── */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1050;
    }

    .modal-content {
      background: white;
      padding: 20px;
      width: 300px;
      margin: 15% auto;
      text-align: center;
      border-radius: 10px;
    }

    .modal-actions-centered button {
      margin: 10px;
      padding: 8px 15px;
    }

    /* ── SIDEBAR INDEX STYLES ────────────────────────────────────── */
    .sidebar-index-title {
      font-size: 10px;
      font-weight: 700;
      color: #A0AEC0;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin: 12px 0 6px 0;
      padding-left: 12px;
    }

    .index-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
      font-weight: 700;
      color: #025628;
      padding: 6px 12px;
      cursor: pointer;
      text-decoration: none;
    }

    .index-sub-menu {
      display: none;
      flex-direction: column;
      gap: 4px;
      padding-left: 20px;
      margin-bottom: 6px;
    }

    .index-sub-menu.show {
      display: flex;
    }

    .index-sub-link {
      font-size: 11px;
      font-weight: 600;
      color: #718096;
      text-decoration: none;
      padding: 2px 0;
      cursor: pointer;
    }

    .index-sub-link:hover,
    .index-sub-link.active {
      color: #025628;
    }

    .index-item {
      border-radius: 8px;
      transition: background .18s ease;
    }

    .index-item:hover,
    .index-item.active {
      background: #eaf5ee;
    }

    .index-item>span {
      display: flex;
      align-items: center;
      gap: 9px;
    }

    .index-item>span .fa {
      width: 15px;
      text-align: center;
    }

    .index-chevron {
      font-size: 10px;
      transition: transform .18s ease;
    }

    .index-item[aria-expanded="true"] .index-chevron {
      transform: rotate(180deg);
    }

    .index-sub-menu {
      padding: 5px 0 8px 16px;
    }

    .index-sub-link {
      display: flex;
      align-items: flex-start;
      gap: 7px;
      padding: 7px 9px;
      margin: 1px 6px 1px 0;
      border-radius: 7px;
      line-height: 1.45;
      overflow-wrap: anywhere;
    }

    .index-sub-link .fa {
      flex: 0 0 13px;
      margin-top: 2px;
      font-size: 11px;
    }

    .index-sub-link:hover,
    .index-sub-link.active {
      background: #eaf5ee;
      color: #025628;
    }

    .index-quiz-link {
      font-size: 10px;
      padding-left: 16px;
    }

    .index-empty {
      cursor: default;
      font-style: italic;
    }

    /* ── MODAL STYLES ────────────────────────────────────── */
    .pretest-modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.65);
      backdrop-filter: blur(3px);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    .pretest-modal-card {
      background: #ffffff;
      width: 90%;
      max-width: 480px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      animation: modalSlideUp 0.25s ease-out;
    }

    @keyframes modalSlideUp {
      from {
        transform: translateY(20px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .pretest-modal-header {
      background: #025628;
      color: #ffffff;
      padding: 24px;
      position: relative;
      text-align: center;
    }

    .pretest-modal-header h3 {
      font-size: 20px;
      font-weight: 800;
      margin: 0;
      color: #ffffff;
    }

    .pretest-modal-header p {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.8);
      margin: 4px 0 0 0;
    }

    .pretest-modal-close {
      position: absolute;
      top: 16px;
      right: 18px;
      background: rgba(255, 255, 255, 0.15);
      border: none;
      color: #ffffff;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }

    .pretest-modal-close:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .pretest-modal-body {
      padding: 24px;
    }

    .pretest-grid-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 20px;
    }

    .pretest-info-box {
      background: #F7FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      padding: 12px;
      text-align: center;
    }

    .pretest-info-box .val {
      font-size: 16px;
      font-weight: 800;
      color: #025628;
    }

    .pretest-info-box .lbl {
      font-size: 10px;
      font-weight: 700;
      color: #718096;
      text-transform: uppercase;
      margin-top: 2px;
    }

    .pretest-rules-list {
      background: #FFFDF0;
      border: 1px solid #E2E07B;
      border-radius: 12px;
      padding: 14px 18px;
      margin-bottom: 24px;
      font-size: 12px;
      color: #2D3748;
      line-height: 1.6;
    }

    .pretest-rules-list ul {
      margin: 6px 0 0 0;
      padding-left: 18px;
    }

    .btn-start-pretest {
      width: 100%;
      background: #025628;
      color: #ffffff;
      border: none;
      padding: 12px 0;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      transition: background 0.2s;
      display: block;
      text-align: center;
      text-decoration: none;
    }

    .btn-start-pretest:hover {
      background: #013d1c;
      color: #ffffff;
    }

    .a4-cert-modal-wrapper {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(5px);
      z-index: 10000;
      align-items: center;
      justify-content: center;
      overflow-y: auto;
      padding: 16px;
    }

    .a4-cert-container {
      width: 780px;
      max-width: 95vw;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow: hidden;
    }

    .a4-cert-sheet {
      width: 100%;
      aspect-ratio: 1.414 / 1;
      background: #ffffff;
      position: relative;
      box-sizing: border-box;
      padding: 24px 36px;
      font-family: 'Montserrat', sans-serif;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-align: center;
    }

    /* Scaled Geometric Corner Accents */
    .cert-corner-top-left {
      position: absolute;
      top: 0;
      left: 0;
      width: 0;
      height: 0;
      border-top: 130px solid #025628;
      border-right: 240px solid transparent;
      z-index: 1;
    }

    .cert-corner-top-left-accent {
      position: absolute;
      top: 0;
      left: 0;
      width: 0;
      height: 0;
      border-top: 18px solid #D4D120;
      border-right: 320px solid transparent;
      z-index: 2;
    }

    .cert-corner-bottom-right {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 0;
      height: 0;
      border-bottom: 130px solid #025628;
      border-left: 240px solid transparent;
      z-index: 1;
    }

    .cert-corner-bottom-right-accent {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 0;
      height: 0;
      border-bottom: 18px solid #D4D120;
      border-left: 320px solid transparent;
      z-index: 2;
    }

    .cert-inner-content {
      position: relative;
      z-index: 10;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .cert-header-logos {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      margin-bottom: 4px;
    }

    .cert-logo-img {
      height: 38px;
      width: auto;
      object-fit: contain;
    }

    .cert-agency-title {
      font-size: 9.5px;
      font-weight: 800;
      letter-spacing: 0.8px;
      color: #000000;
      text-transform: uppercase;
      margin-bottom: 10px;
      line-height: 1.3;
    }

    .cert-main-heading {
      font-size: 22px;
      font-weight: 900;
      letter-spacing: 2px;
      color: #000000;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .cert-certify-text {
      font-size: 9.5px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: #333333;
      text-transform: uppercase;
      margin-bottom: 6px;
    }

    .cert-student-name {
      font-family: 'Alex Brush', cursive;
      font-size: 42px;
      color: #000000;
      line-height: 1;
      margin-bottom: 2px;
      font-weight: 400;
      border-bottom: 1.5px solid #000000;
      display: inline-block;
      padding: 0 30px 2px 30px;
    }

    .cert-completion-desc {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: #333333;
      text-transform: uppercase;
      margin-top: 6px;
      margin-bottom: 4px;
    }

    .cert-course-title {
      font-size: 22px;
      font-weight: 900;
      letter-spacing: 1.5px;
      color: #000000;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .cert-conducted-by {
      font-size: 9.5px;
      font-weight: 600;
      color: #333333;
      margin-bottom: 2px;
    }

    .cert-office-name {
      font-size: 10.5px;
      font-weight: 800;
      letter-spacing: 0.5px;
      color: #000000;
      text-transform: uppercase;
      max-width: 520px;
      margin: 0 auto 4px auto;
      line-height: 1.25;
    }

    .cert-date-range {
      font-size: 10px;
      font-weight: 700;
      color: #000000;
      margin-bottom: 2px;
    }

    .cert-given-text {
      font-size: 9.5px;
      font-weight: 500;
      color: #444444;
      margin-bottom: 12px;
    }

    .cert-meta-ids-row {
      display: flex;
      justify-content: space-between;
      padding: 0 24px;
      font-size: 10px;
      font-weight: 800;
      color: #000000;
      letter-spacing: 0.5px;
      margin-bottom: 16px;
    }

    .cert-signatures-row {
      display: flex;
      justify-content: space-between;
      padding: 0 40px;
      align-items: flex-end;
    }

    .cert-sig-block {
      text-align: center;
      width: 200px;
    }

    .cert-sig-line {
      width: 100%;
      height: 1.5px;
      background-color: #000000;
      margin-bottom: 6px;
    }

    .cert-sig-name {
      font-size: 11.5px;
      font-weight: 800;
      color: #000000;
      text-transform: uppercase;
    }

    .cert-sig-title {
      font-size: 9.5px;
      font-weight: 600;
      color: #444444;
    }

    .cert-modal-actions {
      padding: 12px 20px;
      background: #2D3748;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    @media print {
      body * {
        visibility: hidden !important;
      }

      .a4-cert-modal-wrapper,
      .a4-cert-modal-wrapper * {
        visibility: visible !important;
      }

      .a4-cert-modal-wrapper {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: #ffffff !important;
        padding: 0 !important;
      }

      .cert-modal-actions,
      .pretest-modal-close {
        display: none !important;
      }

      .a4-cert-container {
        box-shadow: none !important;
        width: 100% !important;
        max-width: 100% !important;
      }

      .a4-cert-sheet {
        width: 100% !important;
        height: 100vh !important;
        padding: 20mm 25mm !important;
      }

      @page {
        size: A4 landscape;
        margin: 0;
      }
    }
  </style>

</head>

<body>

  <nav class="topbar">

    <div class="topbar-left">

      <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">

        <span></span>

        <span></span>

        <span></span>

      </button>

      <a href="{{ route('homepage') }}" class="topbar-brand">

        <img src="{{ asset('images/logo.png') }}" alt="logo"
          class="topbar-logo">

        <span>LEDIPO</span>

      </a>

    </div>

    <div class="topbar-right">

      {{-- Updated Profile Trigger & Container --}}

      <div class="landing-profile">

        <button class="landing-avatar-btn" id="avatarBtn"
          aria-label="Open profile menu">

          {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

        </button>

        <div class="landing-profile-dropdown" id="dropdown">

          <div class="landing-dropdown-header">

            <div class="landing-dd-avatar">

              {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

            </div>

            <div class="landing-user-info">

              <div class="landing-dh-name">

                {{ Auth::user()->firstname ?? 'Student' }}

                {{ Auth::user()->lastname ?? '' }}</div>

              <div class="landing-dh-role">

                {{ ucfirst(Auth::user()->role ?? 'Student') }}

              </div>

            </div>

          </div>

          <div class="dd-items">

            <a href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}"
              class="dd-item">

              <i class="fa fa-user dd-icon"></i>

              Profile

            </a>

            <a href="{{ Route::has('homepage') ? route('homepage') : url('/homepage') }}"
              class="dd-item">

              <i class="fa fa-gauge-high dd-icon"></i>

              Dashboard

            </a>

            <a href="{{ url('/') }}" class="dd-item">

              <i class="fa fa-house dd-icon"></i>

              Home

            </a>

            <div class="dd-divider"></div>

            <a href="#" class="dd-item dd-logout"
              onclick="event.preventDefault(); openLogoutModal();">

              <i class="fa fa-right-from-bracket dd-icon"></i>

              Log out

            </a>

          </div>

          <form id="logout-form"
            action="{{ Route::has('Logout') ? route('Logout') : '#' }}"
            method="POST" style="display:none;">

            @csrf

          </form>

        </div>

      </div>

    </div>

  </nav>

  <div id="logoutModal" class="modal" style="display:none;">

    <div class="modal-content">

      <p>Are you sure you want to log out?</p>

      <div class="modal-actions-centered">

        <button onclick="confirmLogout()" class="btn-modal-yes">Yes</button>

        <button onclick="closeLogoutModal()"
          class="btn-modal-no">Cancel</button>

      </div>

    </div>

  </div>

  <div class="app-body">

    <div class="sidebar-overlay" id="overlay"></div>

    {{-- ── SIDEBAR ────────────────────────────────────────── --}}

    <aside class="sidebar" id="sidebar">

      <div class="sidebar-section-label">MENU</div>

      <a href="{{ Route::has('homepage') ? route('homepage') : '/student/dashboard' }}"
        class="nav-item {{ request()->routeIs('homepage') ? 'active' : '' }}">

        <span>Dashboard</span>

      </a>

      <a href="{{ Route::has('student.modules') ? route('student.modules') : '/student/modules' }}"
        class="nav-item {{ request()->routeIs('student.modules*') ? 'active' : '' }}">

        <span>My Courses</span>

      </a>

      {{-- ── COURSE INDEX / MODULE SIDEBAR ── --}}

      @php

        $sidebarModules = isset($modules) ? $modules : collect();

        $sidebarUnitNumbers =
            isset($unitNumbers) && $unitNumbers->isNotEmpty()
                ? $unitNumbers
                : $sidebarModules

                    ->pluck('unit_number')

                    ->filter(fn($number) => $number !== null)

                    ->unique()

                    ->sort()

                    ->values();

      @endphp

      @if ($sidebarUnitNumbers->isNotEmpty())

        <div id="sidebarIndexMenu">

          <div class="sidebar-index-title">COURSE CONTENT</div>

          <div class="index-item" role="button" tabindex="0"
            onclick="toggleSubMenu('introSub'); showIntroScreen();">

            <span><i class="fa fa-book-open"></i> Introduction</span>

            <i class="fa fa-chevron-down index-chevron"></i>

          </div>

          <div class="index-sub-menu show" id="introSub">

            <a href="#courseOverview" class="index-sub-link"
              onclick="showIntroScreen();">• Welcome &amp; Course

              Overview</a>

          </div>

          @foreach ($sidebarUnitNumbers as $unitNum)
            <div class="index-item" role="button" tabindex="0"
              onclick="toggleSubMenu('unit{{ $unitNum }}Sub'); showUnitScreen({{ $unitNum }});">

              <span><i class="fa fa-layer-group"></i> Unit

                {{ $unitNum }}</span>

              <i class="fa fa-chevron-down index-chevron"></i>

            </div>

            <div class="index-sub-menu" id="unit{{ $unitNum }}Sub">

              @foreach ($sidebarModules->where('unit_number', $unitNum) as $sidebarModule)
                <a href="#module-item-{{ $sidebarModule->id }}"
                  class="index-sub-link"
                  onclick="openSidebarModule(event, {{ $sidebarModule->id }}, {{ $unitNum }});">

                  <i
                    class="fa fa-file-lines"></i><span>{{ $sidebarModule->title }}</span>

                </a>

                @if (isset($quizzes))
                  @foreach ($quizzes->where('module_id', $sidebarModule->id) as $sidebarQuiz)
                    <a href="#quiz-item-{{ $sidebarQuiz->id }}"
                      class="index-sub-link index-quiz-link"
                      onclick="openSidebarQuiz(event, {{ $sidebarQuiz->id }}, @js($sidebarQuiz->title), {{ $unitNum }});">

                      <i
                        class="fa fa-circle-question"></i><span>{{ $sidebarQuiz->title }}</span>

                    </a>
                  @endforeach
                @endif
              @endforeach

              @if ($sidebarModules->where('unit_number', $unitNum)->isEmpty())
                <span class="index-sub-link index-empty">No modules added

                  yet</span>
              @endif

            </div>
          @endforeach

          <div class="index-item" role="button" tabindex="0"
            onclick="toggleSubMenu('completionSub'); showAllCourseContent();">

            <span><i class="fa fa-graduation-cap"></i> Completion</span>

            <i class="fa fa-chevron-down index-chevron"></i>

          </div>

          <div class="index-sub-menu" id="completionSub">

            <a href="#courseOverview" class="index-sub-link"
              onclick="showIntroScreen();">• Course progress</a>

          </div>

        </div>

      @endif

      <a href="{{ Route::has('student.announcements') ? route('student.announcements') : '/student/announcements' }}"
        class="nav-item {{ request()->routeIs('student.announcements') ? 'active' : '' }}">

        <span>Announcement</span>

      </a>

    </aside>

    <main class="main-content">

      @yield('content')

    </main>

  </div>

  {{-- ── PRE-TEST MODAL POPUP ───────────────────────────────── --}}

  <div id="pretestSidebarModal" class="pretest-modal-overlay">

    <div class="pretest-modal-card">

      <div class="pretest-modal-header">

        <button onclick="closePretestSidebarModal()"
          class="pretest-modal-close">✕</button>

        <h3>Baseline Pre-test</h3>

        <p>Course Assessment</p>

      </div>

      <div class="pretest-modal-body">

        <div class="pretest-grid-info">

          <div class="pretest-info-box">

            <div class="val">20</div>

            <div class="lbl">Questions</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">1 Attempt</div>

            <div class="lbl">Limit Allowed</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">30 Mins</div>

            <div class="lbl">Time Duration</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">70%</div>

            <div class="lbl">Passing Grade</div>

          </div>

        </div>

        <div class="pretest-rules-list">

          <strong>Instructions:</strong>

          <ul>

            <li>Ensure a stable internet connection before starting.

            </li>

            <li>You only have <strong>1 attempt</strong> to take

              this test.</li>

            <li>Once started, the timer cannot be paused.</li>

          </ul>

        </div>

        <button onclick="triggerStartPretest()" class="btn-start-pretest">

          Start Pre-test

        </button>

      </div>

    </div>

  </div>

  {{-- ── POST-TEST MODAL POPUP ─────────────────────────────── --}}

  <div id="posttestSidebarModal" class="pretest-modal-overlay">

    <div class="pretest-modal-card">

      <div class="pretest-modal-header">

        <button onclick="closePosttestSidebarModal()"
          class="pretest-modal-close">✕</button>

        <h3>Final Post-Test</h3>

        <p>Official Written Evaluation</p>

      </div>

      <div class="pretest-modal-body">

        <div class="pretest-grid-info">

          <div class="pretest-info-box">

            <div class="val">80</div>

            <div class="lbl">Questions</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">1 Attempt</div>

            <div class="lbl">Limit Allowed</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">60 Mins</div>

            <div class="lbl">Time Duration</div>

          </div>

          <div class="pretest-info-box">

            <div class="val">80%</div>

            <div class="lbl">Passing Grade</div>

          </div>

        </div>

        <div class="pretest-rules-list">

          <strong>Instructions:</strong>

          <ul>

            <li>This is the final written evaluation containing

              <strong>80

                questions</strong>.

            </li>

            <li>You need a score of <strong>80% or higher</strong>

              to pass.</li>

            <li>Ensure you complete all items before submitting.

            </li>

          </ul>

        </div>

        <button onclick="triggerStartPosttest()" class="btn-start-pretest">

          Start Post-Test

        </button>

      </div>

    </div>

  </div>

  <div id="certificateModal" class="a4-cert-modal-wrapper">

    <div class="a4-cert-container">

      <div class="a4-cert-sheet">

        <div class="cert-corner-top-left-accent"></div>

        <div class="cert-corner-top-left"></div>

        <div class="cert-corner-bottom-right-accent"></div>

        <div class="cert-corner-bottom-right"></div>

        <div class="cert-inner-content">

          <div>

            <div class="cert-header-logos">

              <img src="{{ asset('images/logo.png') }}" alt="Dasma Logo"
                class="cert-logo-img"
                onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/4/41/Seal_of_Dasmari%C3%B1as.png'">

              <img
                src="https://upload.wikimedia.org/wikipedia/commons/b/b2/TESDA_logo.svg"
                alt="TESDA Logo" class="cert-logo-img" style="height:36px;">

              <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo"
                class="cert-logo-img">

            </div>

            <div class="cert-agency-title">

              TECHNICAL EDUCATION AND SKILLS DEVELOPMENT

              AUTHORITY<br>

              CITY GOVERNMENT OF DASMARIÑAS - LEDIPO

            </div>

            <div class="cert-main-heading">CERTIFICATE OF

              COMPLETION</div>

            <div class="cert-certify-text">THIS COMS CERTIFIY THAT

            </div>

          </div>

          <div>

            <div class="cert-student-name">

              {{ Auth::user()->firstname ?? 'Samira' }}

              {{ Auth::user()->lastname ?? 'Hadid' }}

            </div>

            <div class="cert-completion-desc">HAS SUCCESSFULLY

              COMPLETED THE

              TRAINING IN</div>

            <div class="cert-course-title" id="certDynamicCourseTitle">

              DRESSMAKING

            </div>

            <div class="cert-conducted-by">Conducted by</div>

            <div class="cert-office-name">

              DASMARIÑAS LOCAL ECONOMIC DEVELOPMENT AND INVESTMENT

              PROMOTION

              OFFICE (LEDIPO)

            </div>

            <div class="cert-date-range">

              from April 15, 2026 to May 11, 2026

            </div>

            <div class="cert-given-text">

              Given this 18th day of April 2026 at Dasmariñas

              City, Cavite,

              Philippines.

            </div>

          </div>

          <div>

            <div class="cert-meta-ids-row">

              <span>CERT. NO.: D-LED-TES-2026-081</span>

              <span>TRAINING ID: NCIIDRM-26-032</span>

            </div>

            <div class="cert-signatures-row">

              <div class="cert-sig-block">

                <div class="cert-sig-line"></div>

                <div class="cert-sig-name">HON. JENNIFER A.

                  BARZAGA</div>

                <div class="cert-sig-title">City Mayor</div>

              </div>

              <div class="cert-sig-block">

                <div class="cert-sig-line"></div>

                <div class="cert-sig-name">MR. CARLOS H.

                  LEGASPI</div>

                <div class="cert-sig-title">LEDIPO Head</div>

              </div>

            </div>

          </div>

        </div>

      </div>

      <div class="cert-modal-actions">

        <span
          style="color:#ffffff; font-size:11.5px; font-weight:600;">Official

          LEDIPO E-Certificate Preview</span>

        <div>

          <button onclick="closeCertificateModal()"
            style="background:#4A5568; color:#fff; border:none; padding:6px 16px; border-radius:6px; font-size:11px; font-weight:700; cursor:pointer; margin-right:8px;">

            Close

          </button>

          <button onclick="window\.print()"
            style="background:#D4D120; color:#1A1A1A; border:none; padding:6px 18px; border-radius:6px; font-size:11px; font-weight:800; cursor:pointer;">

            <i class="fa fa-print"></i> Print / Download PDF

          </button>

        </div>

      </div>

    </div>

  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
  </script>

  <script>
    const hamburger = document.getElementById('hamburger');







    const sidebar = document.getElementById('sidebar');







    const overlay = document.getElementById('overlay');







    const avatarBtn = document.getElementById('avatarBtn');







    const dropdown = document.getElementById('dropdown');















    if (hamburger) {







      hamburger.addEventListener('click', function() {







        sidebar.classList.toggle('sidebar-open');







        overlay.classList.toggle('show');







      });







    }















    if (overlay) {







      overlay.addEventListener('click', function() {







        sidebar.classList.remove('sidebar-open');







        overlay.classList.remove('show');







      });







    }















    if (avatarBtn) {







      avatarBtn.addEventListener('click', function(e) {







        e.stopPropagation();







        dropdown.classList.toggle('open');







      });







    }















    document.addEventListener('click', function(e) {







      if (dropdown && !e.target.closest('.landing-profile')) {







        dropdown.classList.remove('open');







      }







    });















    function toggleSubMenu(id) {



      const el = document.getElementById(id);



      if (!el) return;



      el.classList.toggle('show');



      const heading = el.previousElementSibling;



      if (heading && heading.classList.contains('index-item')) {



        heading.setAttribute('aria-expanded', el.classList.contains('show') ?



          'true' : 'false');



      }



    }







    function hideAllModuleContent() {
      document.querySelectorAll(
        '.sidebar-unit-heading, .module-action-row, .module-quizzes-wrap, .module-pdf-preview, .course-level-quizzes-wrap'
      ).forEach((element) => {
        element.style.display = 'none';
      });
    }

    function setIntroContentVisibility(show) {
      const selectors = [
        '#courseOverview',
        '#courseSyllabus',
        '.course-syllabus',
        '.syllabus-section',
        '[data-content-screen="introduction"]'
      ];

      selectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((element) => {
          element.style.display = show ? '' : 'none';
        });
      });

      // Introduction and module content are separate screens.
      document.querySelectorAll('#modulesSection').forEach((element) => {
        element.style.display = show ? 'none' : '';
      });
    }

    function clearCourseIndexActiveState() {
      document.querySelectorAll(
        '#sidebarIndexMenu .index-item, #sidebarIndexMenu .index-sub-link'
      ).forEach((element) => element.classList.remove('active'));
    }

    function showIntroScreen() {
      // Remember the current screen in the URL
      saveLmsPosition('screen', 'intro');
      saveLmsPosition('unit', null);
      saveLmsPosition('module', null);
      saveLmsPosition('quiz', null);

      hideAllModuleContent();
      setIntroContentVisibility(true);

      document.querySelectorAll('.course-level-quizzes-wrap').forEach(
        (element) => {
          element.style.display = '';
        }
      );

      clearCourseIndexActiveState();

      const introMenu = document.getElementById('introSub');
      if (introMenu) introMenu.classList.add('show');

      const introHeading = introMenu ?
        introMenu.previousElementSibling :
        null;

      if (introHeading) introHeading.classList.add('active');

      const introLink = document.querySelector('#introSub .index-sub-link');
      if (introLink) introLink.classList.add('active');
    }

    function showUnitScreen(unitNumber) {
      const selectedUnit = String(unitNumber);
      hideAllModuleContent();
      setIntroContentVisibility(false);
      clearCourseIndexActiveState();

      document.querySelectorAll('.sidebar-unit-heading').forEach((heading) => {
        if (String(heading.dataset.unitNumber) === selectedUnit) {
          heading.style.display = '';
        }
      });

      document.querySelectorAll('.module-action-row').forEach((module) => {
        if (String(module.dataset.unitNumber) === selectedUnit) {
          module.style.display = '';
        }
      });

      // Show only previews/quizzes that belong to the selected unit. If a child
      // element has no unit number, resolve its unit from its linked module.
      document.querySelectorAll('.module-quizzes-wrap, .module-pdf-preview')
        .forEach((element) => {
          let elementUnit = element.dataset.unitNumber;
          if (!elementUnit && element.dataset.moduleId) {
            const linkedModule = document.getElementById('module-item-' +
              element.dataset.moduleId);
            elementUnit = linkedModule ? linkedModule.dataset.unitNumber : '';
          }
          if (String(elementUnit) === selectedUnit) element.style.display = '';
        });

      const unitMenu = document.getElementById('unit' + selectedUnit + 'Sub');
      if (unitMenu) {
        unitMenu.classList.add('show');
        if (unitMenu.previousElementSibling) {
          unitMenu.previousElementSibling.classList.add('active');
        }
      }
    }

    function showAllCourseContent() {
      showIntroScreen();
    }

    function showOnlySelectedModule(moduleId, unitNumber) {
      const selectedId = String(moduleId);
      const selectedUnit = String(unitNumber);
      hideAllModuleContent();
      setIntroContentVisibility(false);
      clearCourseIndexActiveState();

      document.querySelectorAll('.sidebar-unit-heading').forEach((heading) => {
        if (String(heading.dataset.unitNumber) === selectedUnit) {
          heading.style.display = '';
        }
      });

      const moduleElement = document.getElementById('module-item-' + selectedId);
      if (moduleElement) moduleElement.style.display = '';

      document.querySelectorAll('.module-pdf-preview, .module-quizzes-wrap')
        .forEach((element) => {
          if (String(element.dataset.moduleId) === selectedId) {
            element.style.display = '';
          }
        });

      const unitMenu = document.getElementById('unit' + selectedUnit + 'Sub');
      if (unitMenu) unitMenu.classList.add('show');

      const selectedLink = document.querySelector(
        '#unit' + selectedUnit + 'Sub a[href="#module-item-' + selectedId + '"]'
      );
      if (selectedLink) selectedLink.classList.add('active');
    }

    function openSidebarModule(event, moduleId, unitNumber) {
      if (event) event.preventDefault();
      showOnlySelectedModule(moduleId, unitNumber);

      const target = document.getElementById('module-item-' + moduleId);
      if (target) target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }

    function openSidebarQuiz(event, quizId, quizTitle, unitNumber) {
      if (event) event.preventDefault();

      const quizElement = document.getElementById('quiz-item-' + quizId);
      const moduleId = quizElement ? quizElement.dataset.moduleId : null;

      if (moduleId) {
        showOnlySelectedModule(moduleId, unitNumber);
      } else {
        hideAllModuleContent();
        setIntroContentVisibility(false);
        clearCourseIndexActiveState();
      }

      if (event && event.currentTarget) event.currentTarget.classList.add(
        'active');

      if (typeof openQuiz === 'function') {
        openQuiz(quizId, quizTitle);
      } else if (quizElement) {
        quizElement.style.display = '';
        quizElement.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
      }
    }


    document.addEventListener('DOMContentLoaded', function() {
      const params = new URLSearchParams(window.location.search);

      const screen = params.get('screen') || 'intro';
      const unit = params.get('unit');
      const moduleId = params.get('module');

      if (screen === 'module' && moduleId && unit) {
        showOnlySelectedModule(moduleId, unit);
      } else if (screen === 'unit' && unit) {
        showUnitScreen(unit);
      } else {
        showIntroScreen();
      }
    });


    function openPretestSidebarModal() {







      document.getElementById('pretestSidebarModal').style.display = 'flex';







    }















    function closePretestSidebarModal() {







      document.getElementById('pretestSidebarModal').style.display = 'none';







    }















    function triggerStartPretest() {







      closePretestSidebarModal();







      if (typeof openQuizModal === 'function') {







        openQuizModal(1, 'Baseline Pre-test');







      } else {







        alert('Starting Pre-test...');







      }







    }















    // ── Post-test Modal Handlers ──────────────────────────







    function openPosttestSidebarModal() {







      document.getElementById('posttestSidebarModal').style.display = 'flex';







    }















    function closePosttestSidebarModal() {







      document.getElementById('posttestSidebarModal').style.display = 'none';







    }















    function triggerStartPosttest() {







      closePosttestSidebarModal();







      if (typeof openQuizModal === 'function') {







        openQuizModal(2, 'Final Post-Test (80 Items)');







      } else {







        alert('Starting 80-Item Post-Test...');







      }







    }















    // ── Certificate Modal Handlers ────────────────────────







    function openCertificateModal() {







      const activeTitleEl = document.getElementById('activeCourseTitle') ||







        document.getElementById('certProgramName');







      const certCourseEl = document.getElementById('certDynamicCourseTitle');















      if (activeTitleEl && certCourseEl) {







        certCourseEl.textContent = activeTitleEl.textContent.trim();







      }















      document.getElementById('certificateModal').style.display = 'flex';







    }















    function closeCertificateModal() {







      document.getElementById('certificateModal').style.display = 'none';







    }















    function openLogoutModal() {







      document.getElementById('logoutModal').style.display = 'block';







    }















    function closeLogoutModal() {







      document.getElementById('logoutModal').style.display = 'none';







    }















    function confirmLogout() {







      document.getElementById('logout-form').submit();







    }
  </script>

  @yield('scripts')

</body>

</html>

