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

    .course-banner.course-banner--image {

        position: relative;

        isolation: isolate;

        display: flex;

        align-items: flex-end;

        min-height: 360px;

        padding: clamp(28px, 5vw, 58px);

        margin-bottom: 30px;

        overflow: hidden;

        border-radius: 22px;

        color: #fff;

        background-color: #075b35;

        background-image:

            linear-gradient(90deg, rgba(3, 35, 23, .94) 0%, rgba(4, 63, 38, .78) 48%, rgba(4, 63, 38, .22) 100%),

            linear-gradient(0deg, rgba(3, 35, 23, .55), transparent 70%),

            var(--course-banner-image, none);

        background-size: cover;

        background-position: center;

        box-shadow: 0 18px 42px rgba(4, 63, 38, .16);

    }

    .course-banner.course-banner--image::after {

        content: "";

        position: absolute;

        z-index: -1;

        inset: 0;

        border: 1px solid rgba(255,255,255,.15);

        border-radius: inherit;

        pointer-events: none;

    }

    .course-banner-content {

        width: min(100%, 900px);

        position: relative;

        z-index: 1;

    }

    .course-banner--image .course-banner-sector {

        display: inline-flex;

        align-items: center;

        gap: 8px;

        margin-bottom: 18px;

        padding: 8px 13px;

        border: 1px solid rgba(255,255,255,.28);

        border-radius: 999px;

        background: rgba(255,255,255,.13);

        color: #fff;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: .08em;

        text-transform: uppercase;

        backdrop-filter: blur(8px);

    }

    .course-banner--image h1 {

        max-width: 800px;

        margin: 0 0 14px;

        color: #fff;

        font-size: clamp(30px, 4.2vw, 52px);

        font-weight: 800;

        line-height: 1.12;

        letter-spacing: -.035em;

        text-wrap: balance;

    }

    .course-banner--image .course-banner-content > p {

        max-width: 760px;

        margin: 0;

        color: rgba(255,255,255,.88);

        font-size: 14px;

        line-height: 1.8;

        display: -webkit-box;

        -webkit-line-clamp: 3;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

    .course-banner--image .course-banner-meta {

        display: flex;

        flex-wrap: wrap;

        gap: 10px;

        margin-top: 24px;

    }

    .course-banner--image .course-banner-meta span {

        display: inline-flex;

        align-items: center;

        gap: 9px;

        max-width: 100%;

        padding: 10px 13px;

        border: 1px solid rgba(255,255,255,.2);

        border-radius: 10px;

        background: rgba(255,255,255,.12);

        color: #fff;

        font-size: 12px;

        line-height: 1.45;

        backdrop-filter: blur(8px);

    }

    .course-banner--image .course-banner-meta i {

        color: #e1dc36;

    }

    @media (max-width: 700px) {

        .course-banner.course-banner--image {

            min-height: 390px;

            padding: 26px 20px;

            border-radius: 16px;

            background-position: center;

            background-image:

                linear-gradient(0deg, rgba(3, 35, 23, .95) 0%, rgba(4, 63, 38, .70) 68%, rgba(4, 63, 38, .25) 100%),

                var(--course-banner-image, none);

        }

        .course-banner--image .course-banner-meta {

            gap: 8px;

        }

        .course-banner--image .course-banner-meta span {

            font-size: 11px;

            padding: 9px 10px;

        }

    }

    /* ===== FIX SIDEBAR AND BUTTON OVERLAP ON SCROLL ===== */

    .course-detail-grid > div:last-child {

        position: sticky;

        top: 100px;

        align-self: start;

        min-width: 0;

        z-index: 2;

    }

    .course-detail-grid > div:last-child .sidebar-card {

        position: static;

        top: auto;

    }

    .course-detail-grid > div:last-child .btn-enroll,

    .course-detail-grid > div:last-child .btn-signin-outline {

        position: relative;

        z-index: auto;

        transform: none;

    }

    @media (max-width: 992px) {

        .course-detail-grid > div:last-child {

            position: static;

            top: auto;

        }

    }

    /* =========================================================

       LOGGED-IN PROFILE AVATAR / DROPDOWN & FOOTER STYLES

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

    .landing-dd-items {

      padding: 7px 0;

    }

    .landing-dd-item {

      display: flex;

      align-items: center;

      gap: 11px;

      width: 100%;

      box-sizing: border-box;

      padding: 11px 16px;

      color: #374151;

      background: #ffffff;

      font-family: 'Poppins', sans-serif;

      font-size: 13px;

      font-weight: 600;

      text-decoration: none;

      transition: background 0.15s ease, color 0.15s ease;

    }

    .landing-dd-item i {

      width: 18px;

      text-align: center;

      font-size: 14px;

    }

    .landing-dd-item:hover {

      background: #f0fdf4;

      color: #025628;

      text-decoration: none;

    }

    .landing-dd-divider {

      height: 1px;

      background: #e5e7eb;

      margin: 5px 12px;

    }

    .landing-dd-logout {

      color: #1a4d2e;

    }

    .landing-dd-logout:hover {

      background: #fef2f2;

      color: #c9bf03;

    }

    /* FOOTER STYLING */

    .footer {

      background: #06381e;

      color: #ffffff;

      font-family: 'Poppins', sans-serif;

      padding: 50px 0 25px 0;

      width: 100%;

      box-sizing: border-box;

      margin-top: 50px;

    }

    .footer-content {

      display: grid;

      grid-template-columns: 2fr 1fr 1fr 1fr;

      gap: 30px;

      max-width: 1200px;

      margin: 0 auto;

      padding: 0 40px;

      align-items: start;

      box-sizing: border-box;

    }

    .footer-brand-col {

      display: flex;

      flex-direction: column;

      gap: 20px;

    }

    .footer-brand-top {

      display: flex;

      align-items: center;

      gap: 14px;

    }

    .footer-brand-top img {

      width: 55px;

      height: 55px;

      object-fit: contain;

    }

    .footer-brand-top span {

      font-size: 13px;

      font-weight: 500;

      line-height: 1.4;

      color: #ffffff;

    }

    .footer-partnership {

      display: flex;

      align-items: center;

      gap: 15px;

    }

    .footer-partnership p {

      font-size: 11.5px;

      font-weight: 600;

      line-height: 1.3;

      color: #ffffff;

      margin: 0;

      width: 85px;

    }

    .footer-badges {

      display: flex;

      gap: 12px;

      align-items: center;

    }

    .footer-badges img {

      width: 60px;

      height: 60px;

      border-radius: 50%;

      object-fit: cover;

      border: 2px solid rgba(255, 255, 255, 0.3);

      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);

    }

    .footer-col h3 {

      font-size: 15px;

      font-weight: 700;

      color: #ffffff;

      margin-bottom: 15px;

      letter-spacing: 0.3px;

    }

    .footer-col p,

    .footer-col ul li a {

      font-size: 12.5px;

      color: #e2e8f0;

      text-decoration: none;

      line-height: 1.6;

      cursor: pointer;

    }

    .footer-col ul {

      list-style: none;

      padding: 0;

      margin: 0;

    }

    .footer-col ul li {

      margin-bottom: 8px;

    }

    .footer-col ul li a:hover {

      color: #fff200;

      text-decoration: underline;

    }

    .footer-bottom {

      max-width: 1200px;

      margin: 35px auto 0 auto;

      padding: 20px 40px 0 40px;

      border-top: 1px solid rgba(255, 255, 255, 0.15);

      text-align: center;

      box-sizing: border-box;

    }

    .footer-bottom p {

      font-size: 12px;

      color: #cbd5e1;

      margin: 0;

    }

    /* BACK TO COURSES BUTTON */

    .btn-back-courses {

      display: inline-flex;

      align-items: center;

      gap: 8px;

      margin-bottom: 20px;

      padding: 10px 18px;

      background: #d4d120;

      color: #06381e;

      font-family: 'Poppins', sans-serif;

      font-size: 13px;

      font-weight: 700;

      text-decoration: none;

      border-radius: 999px;

      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);

      transition: all 0.2s ease;

    }

    .btn-back-courses:hover {

      background: #c5c219;

      color: #06381e;

      transform: translateX(-3px);

      text-decoration: none;

    }

    /* =========================================================

       THEME-MATCHED ENFORCED READ MODAL STYLING & TOASTS

       ========================================================= */

    .policy-modal-overlay {

      display: none;

      position: fixed;

      z-index: 99999;

      left: 0;

      top: 0;

      width: 100%;

      height: 100%;

      background: rgba(0, 0, 0, 0.75);

      backdrop-filter: blur(4px);

      align-items: center;

      justify-content: center;

      padding: 20px;

    }

    .policy-modal-container {

      background: #ffffff;

      width: 100%;

      max-width: 700px;

      border-radius: 16px;

      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);

      display: flex;

      flex-direction: column;

      max-height: 85vh;

      overflow: hidden;

      border: 3px solid #1a4d2e;

      animation: modalPopIn 0.3s ease forwards;

    }

    @keyframes modalPopIn {

      from { transform: scale(0.95); opacity: 0; }

      to { transform: scale(1); opacity: 1; }

    }

    .policy-modal-header {

      background: #06381e;

      color: #ffffff;

      padding: 20px 24px;

      display: flex;

      align-items: center;

      justify-content: space-between;

      border-bottom: 2px solid #1a4d2e;

    }

    .policy-modal-header h2 {

      margin: 0;

      font-size: 18px;

      font-weight: 700;

      color: #ffffff;

      display: flex;

      align-items: center;

      gap: 10px;

    }

    .policy-modal-body {

      padding: 24px;

      overflow-y: auto;

      font-family: 'Poppins', sans-serif;

      font-size: 13.5px;

      color: #2d3748;

      line-height: 1.7;

      flex-grow: 1;

    }

    .policy-modal-body h4 {

      color: #1a4d2e;

      font-weight: 700;

      margin-top: 18px;

      margin-bottom: 8px;

      font-size: 15px;

    }

    .policy-modal-body p {

      margin-bottom: 12px;

    }

    .policy-agreement-box {

      margin-top: 25px;

      padding: 14px 16px;

      background: #f0fdf4;

      border: 1px solid #bbf7d0;

      border-radius: 8px;

      display: flex;

      align-items: flex-start;

      gap: 12px;

    }

    .policy-agreement-box input[type="checkbox"] {

      width: 18px;

      height: 18px;

      accent-color: #1a4d2e;

      cursor: pointer;

      margin-top: 2px;

      flex-shrink: 0;

    }

    .policy-agreement-box label {

      font-size: 12.5px;

      font-weight: 600;

      color: #166534;

      cursor: pointer;

      line-height: 1.5;

    }

    .policy-modal-footer {

      padding: 16px 24px;

      background: #f8fafc;

      border-top: 1px solid #e2e8f0;

      display: flex;

      align-items: center;

      justify-content: space-between;

      gap: 10px;

    }

    .policy-footer-left-group, .policy-footer-right-group {

      display: flex;

      gap: 10px;

      align-items: center;

    }

    .policy-btn-download {

      background: #1a4d2e;

      color: #ffffff;

      border: none;

      padding: 10px 18px;

      border-radius: 8px;

      font-size: 13px;

      font-weight: 600;

      cursor: not-allowed;

      opacity: 0.4;

      display: flex;

      align-items: center;

      gap: 8px;

      transition: all 0.2s ease;

    }

    .policy-btn-download.unlocked {

      cursor: pointer;

      opacity: 1;

      background: #06381e;

    }

    .policy-btn-download.unlocked:hover {

      background: #1a4d2e;

      color: #c9bf03;

    }

    .policy-btn-decline {

      background: #cf2e2e;

      color: #ffffff;

      border: none;

      padding: 10px 18px;

      border-radius: 8px;

      font-size: 13px;

      font-weight: 600;

      cursor: pointer;

      transition: all 0.2s ease;

    }

    .policy-btn-decline:hover {

      background: #b91c1c;

    }

    .policy-btn-accept {

      background: #cbd5e1;

      color: #64748b;

      border: none;

      padding: 10px 22px;

      border-radius: 8px;

      font-size: 13px;

      font-weight: 600;

      cursor: not-allowed;

      opacity: 0.5;

      transition: all 0.2s ease;

    }

    .policy-btn-accept.unlocked {

      cursor: pointer;

      opacity: 1;

      background: #06381e;

      color: #ffffff;

    }

    .policy-btn-accept.unlocked:hover {

      background: #c9bf03;

      color: #1a4d2e;

    }

    .custom-toast {

      position: fixed;

      bottom: 30px;

      right: 30px;

      background: #06381e;

      color: #ffffff;

      padding: 14px 22px;

      border-radius: 10px;

      box-shadow: 0 10px 25px rgba(0,0,0,0.25);

      border-left: 5px solid #f5c842;

      font-family: 'Poppins', sans-serif;

      font-size: 13.5px;

      font-weight: 600;

      z-index: 999999;

      display: flex;

      align-items: center;

      gap: 12px;

      animation: toastSlideIn 0.3s ease, toastFadeOut 0.3s ease 2.7s forwards;

    }

    @keyframes toastSlideIn {

      from { transform: translateY(50px); opacity: 0; }

      to { transform: translateY(0); opacity: 1; }

    }

    @keyframes toastFadeOut {

      from { transform: translateY(0); opacity: 1; }

      to { transform: translateY(50px); opacity: 0; }

    }

  </style>

</head>

<body>

  <!-- NAVIGATION -->

  <nav class="main-nav">

    <div class="nav-logo">

      <a href="{{ Route::has('index') ? route('index') : url('/') }}" class="logo-link">

        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

      </a>

      <span>LEDIPO</span>

    </div>

    <ul class="nav-links m-0 p-0">

      <li><a href="{{ route('index') }}#home" class="nav-link">Home</a></li>

      <li><a href="{{ route('index') }}#schedule" class="nav-link">Schedule</a></li>

      <li><a href="{{ route('index') }}#about" class="nav-link">About</a></li>

      <li><a href="{{ route('index') }}#courses" class="nav-link active">Courses</a></li>

      <li><a href="{{ route('index') }}#contact" class="nav-link">Contact</a></li>

    </ul>

    <div class="nav-actions">

      @auth

        {{-- LOGGED-IN USER PROFILE --}}

        <div class="landing-profile">

          <button type="button" class="landing-avatar-btn" id="landingAvatarBtn"

            aria-label="Open profile menu" aria-expanded="false">

            {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

          </button>

          {{-- PROFILE DROPDOWN --}}

          <div class="landing-profile-dropdown" id="landingProfileDropdown">

            <div class="landing-dropdown-header">

              <div class="landing-dd-avatar">

                {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

              </div>

              <div class="landing-user-info">

                <div class="landing-dh-name">

                  {{ Auth::user()->firstname ?? 'Student' }}

                  {{ Auth::user()->lastname ?? '' }}

                </div>

                <div class="landing-dh-role">

                  {{ ucfirst(Auth::user()->role ?? 'Student') }}

                </div>

              </div>

            </div>

            <div class="landing-dd-items">

              <a href="{{ Route::has('student.profile') ? route('student.profile') : route('homepage') }}"

                class="landing-dd-item">

                <i class="fa fa-user"></i>

                <span>Profile</span>

              </a>

              <a href="{{ route('homepage') }}" class="landing-dd-item">

                <i class="fa fa-house"></i>

                <span>Dashboard</span>

              </a>

              <div class="landing-dd-divider"></div>

              <a href="#" class="landing-dd-item landing-dd-logout"

                onclick="event.preventDefault(); document.getElementById('landing-logout-form').submit();">

                <i class="fa fa-right-from-bracket"></i>

                <span>Log out</span>

              </a>

            </div>

          </div>

        </div>

        {{-- LOGOUT FORM --}}

        <form id="landing-logout-form"

          action="{{ Route::has('Logout') ? route('Logout') : '#' }}"

          method="POST" style="display:none;">

          @csrf

        </form>

      @else

        {{-- GUEST --}}

        <a href="{{ route('Login') }}" class="btn-signin">Login</a>

        <a href="{{ route('SignupPage') }}" class="btn-signup">Sign Up</a>

      @endauth

    </div>

    <button class="hamburger" id="hamburger">

      <i class="fas fa-bars"></i>

    </button>

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

    @php
    $userId = auth()->id();

    // Capacity is stored in course_tbls.slots, not available_slots.
    $totalSlots = max(0, (int) ($course->slots ?? 0));

    // Only active enrollments occupy current slots.
    $activeEnrolledCount = \App\Models\Enrollment_tbl::where('course_id', $course->id)
        ->where('status', 'active')
        ->count();

    $enrolledCount = $activeEnrolledCount;
    $availableSlots = max(0, $totalSlots - $activeEnrolledCount);

    // Enrollment closes on the training start date.
    $enrollmentClosed = $course->start_date
        ? \Carbon\Carbon::today()->greaterThanOrEqualTo(
            \Carbon\Carbon::parse($course->start_date)->startOfDay()
        )
        : false;

    $enrollmentPercentage = $totalSlots > 0
        ? min(100, (int) round(($activeEnrolledCount / $totalSlots) * 100))
        : 0;

    // Check whether the trainee already has a record for this course.
    $isEnrolled = auth()->check()
        ? \App\Models\Enrollment_tbl::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists()
        : false;

    // The latest enrollment determines whether another course is allowed.
    $latestEnrollment = auth()->check()
        ? \App\Models\Enrollment_tbl::where('user_id', $userId)
            ->orderByDesc('id')
            ->first()
        : null;

    $activeEnrollment = auth()->check()
        ? \App\Models\Enrollment_tbl::where('user_id', $userId)
            ->where('status', 'active')
            ->first()
        : null;

    $canEnrollAfterPreviousCourse = true;
    $enrollmentBlockMessage = '';

    if ($activeEnrollment) {
        $canEnrollAfterPreviousCourse = false;
        $enrollmentBlockMessage = 'You must complete your current course before enrolling in another course.';
    } elseif ($latestEnrollment) {
        if ($latestEnrollment->status !== 'completed') {
            // A dropped/pending enrollment is not completion.
            $canEnrollAfterPreviousCourse = false;
            $enrollmentBlockMessage = $latestEnrollment->status === 'dropped'
                ? 'Your previous course was dropped. You must complete a course before enrolling in another.'
                : 'You must complete your previous course before enrolling in another.';
        } elseif (!$latestEnrollment->completed_at) {
            $canEnrollAfterPreviousCourse = false;
            $enrollmentBlockMessage = 'Your course completion date is missing. Please contact the administrator.';
        } else {
            $eligibleDate = \Carbon\Carbon::parse($latestEnrollment->completed_at)
                ->startOfMonth()
                ->addMonth();

            $canEnrollAfterPreviousCourse = \Carbon\Carbon::now()->startOfMonth()
                ->greaterThanOrEqualTo($eligibleDate);

            if (!$canEnrollAfterPreviousCourse) {
                $enrollmentBlockMessage = 'You can enroll in another course starting next calendar month after completing your previous course.';
            }
        }
    }

    $atLimit = auth()->check()
        && !$isEnrolled
        && !$canEnrollAfterPreviousCourse;

    // Format course schedule and facility details.
    $trainingDays = $course->training_days ?? [];

    if (is_string($trainingDays)) {
        $trainingDays = json_decode($trainingDays, true) ?: [];
    }

    if (!is_array($trainingDays)) {
        $trainingDays = [];
    }

    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $trainingDays = array_values(array_intersect($dayOrder, array_unique($trainingDays)));

    if (empty($trainingDays) && $course->schedule) {
        $dayMap = [
            'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday',
            'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ];

        foreach ($dayMap as $short => $full) {
            if (preg_match('/\b' . $short . '\b/i', $course->schedule)) {
                $trainingDays[] = $full;
            }
        }
    }

    $trainingDaysLabel = $trainingDays ? implode(', ', $trainingDays) : 'To be announced';

    $trainingStartDate = $course->start_date
        ? \Carbon\Carbon::parse($course->start_date)->format('M d, Y')
        : null;

    $trainingEndDate = $course->end_date
        ? \Carbon\Carbon::parse($course->end_date)->format('M d, Y')
        : null;

    $trainingDatesLabel = $trainingStartDate && $trainingEndDate
        ? $trainingStartDate . ' – ' . $trainingEndDate
        : ($trainingStartDate
            ? 'Starts ' . $trainingStartDate
            : ($trainingEndDate ? 'Ends ' . $trainingEndDate : 'To be announced'));

    $trainingStartTime = $course->start_time
        ? \Carbon\Carbon::parse($course->start_time)->format('g:i A')
        : null;

    $trainingEndTime = $course->end_time
        ? \Carbon\Carbon::parse($course->end_time)->format('g:i A')
        : null;

    $trainingTimeLabel = $trainingStartTime && $trainingEndTime
        ? $trainingStartTime . ' – ' . $trainingEndTime
        : ($course->schedule ?: 'To be announced');

    $durationValue = $course->duration_days ?: $course->duration;
    $durationLabel = $durationValue
        ? $durationValue . (is_numeric($durationValue)
            ? ((int) $durationValue === 1 ? ' day' : ' days')
            : '')
        : 'To be announced';

    $facilityName = $course->facility?->name ?? 'Facility not assigned';
    $facilityAddress = $course->facility?->address ?? $course->location ?? 'Address not available';
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

    {{-- BACK TO COURSES BUTTON --}}

    <a href="{{ route('index') }}#courses" class="btn-back-courses">

      <i class="fas fa-arrow-left"></i> Back to Courses

    </a>

    {{-- =====================================================

             COURSE BANNER

        ====================================================== --}}

    <div class="course-banner course-banner--image"

     @if(!empty($course->banner_image))

       style="--course-banner-image: url('{{ asset($course->banner_image) }}');"

     @endif>

    <div class="course-banner-content">

        <h1>{{ $course->title }}</h1>

        @if($course->description)

            <p>{{ $course->description }}</p>

        @endif

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

        {{-- TRAINING SCHEDULE & FACILITY --}}

        <div class="detail-card">

          <h4><i class="fas fa-calendar-days"></i> Training Schedule & Facility</h4>

          <div class="schedule-list">

            <div class="schedule-item">

              <div class="schedule-icon"><i class="fas fa-calendar-week"></i></div>

              <div class="schedule-info">

                <p>Training Days</p>

                <p>{{ $trainingDaysLabel }}</p>

              </div>

            </div>

            <div class="schedule-item">

              <div class="schedule-icon"><i class="fas fa-calendar-days"></i></div>

              <div class="schedule-info">

                <p>Training Dates</p>

                <p>{{ $trainingDatesLabel }}</p>

              </div>

            </div>

            <div class="schedule-item">

              <div class="schedule-icon"><i class="fas fa-clock"></i></div>

              <div class="schedule-info">

                <p>Training Time</p>

                <p>{{ $trainingTimeLabel }}</p>

              </div>

            </div>

            <div class="schedule-item">

              <div class="schedule-icon"><i class="fas fa-hourglass-half"></i></div>

              <div class="schedule-info">

                <p>Duration</p>

                <p>{{ $durationLabel }}</p>

              </div>

            </div>

            <div class="schedule-item">

              <div class="schedule-icon"><i class="fas fa-location-dot"></i></div>

              <div class="schedule-info">

                <p>Facility</p>

                <p>{{ $facilityName }}</p>

                <p>{{ $facilityAddress }}</p>

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

            @elseif ($enrollmentClosed)

              <p>

                Enrollment is closed because this training has already started.

              </p>

            @elseif ($atLimit)
              <p>{{ $enrollmentBlockMessage }}</p>
            @else
              <p>Enroll now to join this course for free!</p>

            @endif

          @else

            <p>

              @if ($enrollmentClosed)

                Enrollment is closed because this training has already started.

              @else

                Sign up or log in to enroll in this course for free!

              @endif

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

                {{ $durationLabel }}

              </span>

            </div>

            <div class="sidebar-meta-row">

              <span>

                Schedule

              </span>

              <span>

                {{ $trainingTimeLabel }}

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

            <button type="button" class="btn-enroll enrolled" disabled>
              <i class="fas fa-circle-check"></i>
              Already Enrolled
            </button>

            <a href="{{ route('homepage') }}" class="btn-signin-outline">
              <i class="fas fa-house"></i>
              Go to Dashboard
            </a>

          @elseif ($enrollmentClosed)

            <button type="button" class="btn-enroll full" disabled>
              <i class="fas fa-lock"></i>
              Enrollment Closed
            </button>

          @elseif ($atLimit)

            <p class="enrollment-alert error">{{ $enrollmentBlockMessage }}</p>

            <button type="button" class="btn-enroll full" disabled>
              <i class="fas fa-lock"></i>
              Enrollment Restricted
            </button>

          @elseif ($availableSlots <= 0)

            <button type="button" class="btn-enroll full" disabled>
              <i class="fas fa-ban"></i>
              No Slots Available
            </button>

          @else

            <form action="{{ route('course.enroll', $course->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn-enroll">
                <i class="fas fa-user-plus"></i>
                Enroll Now
              </button>
            </form>

          @endif

        @else

          {{-- =================================================

                         GUEST

                    ================================================== --}}

          @if (!$enrollmentClosed && $availableSlots > 0)

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

            {{-- ENROLLMENT CLOSED OR FULLY BOOKED --}}

            <button type="button" class="btn-enroll full" disabled>

              <i class="fas {{ $enrollmentClosed ? 'fa-lock' : 'fa-ban' }}"></i>

              {{ $enrollmentClosed ? 'Enrollment Closed' : 'Fully Booked' }}

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

<!-- FOOTER -->

  <footer class="footer">

    <div class="footer-content">

      <!-- Column 1: Brand & Partnership Badges -->

      <div class="footer-brand-col">

        <div class="footer-brand-top">

          <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo">

          <span>Local Economic Development and Investment Promotions Office of the City of Dasmariñas, Cavite</span>

        </div>

        <div class="footer-partnership">

          <p>In Partnership With</p>

          <div class="footer-badges">

            <img src="{{ asset('images/tesda.png') }}" alt="Partner 1">

            <img src="{{ asset('images/dti.png') }}" alt="Partner 2">

            <img src="{{ asset('images/cdcci.png') }}" alt="Partner 3">

          </div>

        </div>

      </div>

      <!-- Column 2: Support -->

      <div class="footer-col">

        <h3>Support</h3>

        <p>Block O-77, Lot 5, San Andres I, Dasmariñas, Cavite.</p>

        <p><a href="mailto:support@ledipo.gov.ph">support@ledipo.gov.ph</a></p>

        <p>0969-522-8600</p>

      </div>

      <!-- Column 3: Account -->

      <div class="footer-col">

        <h3>Account</h3>

        <ul>

          <li><a href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}">My Profile</a></li>

          <li><a href="{{ route('Login') }}">Login</a></li>

          <li><a href="{{ route('SignupPage') }}">Sign Up</a></li>

        </ul>

      </div>

      <!-- Column 4: Quick Link -->

      <div class="footer-col">

        <h3>Quick Link</h3>

        <ul>

          <li><a href="javascript:void(0);" onclick="openPolicyModal('privacyModal')">Privacy Policy</a></li>

          <li><a href="javascript:void(0);" onclick="openPolicyModal('termsModal')">Terms of Use</a></li>

          <li><a href="javascript:void(0);" onclick="openPolicyModal('faqModal')">FAQ</a></li>

          <li><a href="https://www.facebook.com/dasmarinas.livelihood.52" target="_blank" rel="noopener noreferrer">Facebook</a></li>

        </ul>

      </div>

    </div>

    <!-- Bottom Copyright -->

    <div class="footer-bottom">

      <p>Copyright 2026 City of Dasmariñas LEDIPO. All rights reserved.</p>

    </div>

  </footer>

  <!-- =========================================================

     MODALS: PRIVACY POLICY, TERMS OF USE, FAQ

     ========================================================= -->

  <!-- 1. PRIVACY POLICY MODAL -->

  <div class="policy-modal-overlay" id="privacyModal">

    <div class="policy-modal-container">

      <div class="policy-modal-header">

        <h2>Privacy Policy</h2>

      </div>

      <div class="policy-modal-body" id="privacyBody" onscroll="checkScrollProgress('privacyModal')">

        <h4>1. Introduction</h4>

        <p>The Local Economic Development and Investment Promotions Office (LEDIPO) of the City Government of Dasmariñas respects your privacy and is committed to protecting your personal data. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you register and participate in our community livelihood and training programs.</p>

        <h4>2. Information We Collect</h4>

        <p>We may collect personal information that you voluntarily provide to us when registering for courses, filling out contact inquiries, or updating your profile. This includes your full name, email address, residential address in Dasmariñas, contact phone number, government-issued IDs, and residency verification documents (such as Barangay Clearance or Voter's Certification).</p>

        <h4>3. Purpose of Data Collection</h4>

        <p>The collected data is strictly utilized for verifying resident eligibility, managing training attendance, evaluating competency assessments, issuing official certificates of completion in compliance with TESDA standards, and communicating official announcements regarding LEDIPO initiatives.</p>

        <h4>4. Data Sharing and Disclosure</h4>

        <p>We do not sell, trade, or rent your personal information to third parties. Your information may only be shared with authorized city departments and partner agencies (such as DTI and accredited training assessors) strictly for certification, livelihood placement, and program auditing purposes.</p>

        <h4>5. Data Security Measures</h4>

        <p>We implement appropriate technical, physical, and administrative security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. Access to your data is restricted strictly to authorized LEDIPO personnel.</p>

        <h4>6. Your Rights as a Data Subject</h4>

        <p>In accordance with the Data Privacy Act of 2012 (Republic Act No. 10173), you have the right to be informed, to access your personal data, to rectify inaccuracies, and to object to data processing. For inquiries or concerns, you may contact our office at support@ledipo.gov.ph or visit our main training facility.</p>

        <!-- Agreement Checkbox -->

        <div class="policy-agreement-box">

          <input type="checkbox" id="privacyAgreeCheck" onchange="toggleAcceptButton('privacyModal')">

          <label for="privacyAgreeCheck">I have read, fully understand, and agree to the Privacy Policy terms and data collection practices.</label>

        </div>

      </div>

      <div class="policy-modal-footer">

        <div class="policy-footer-left-group">

          <button class="policy-btn-download" id="privacyDownloadBtn" onclick="downloadPolicyText('privacyModal')">

            <i class="fas fa-download"></i> Download Policy

          </button>

        </div>

        <div class="policy-footer-right-group">

          <button class="policy-btn-decline" onclick="declinePolicyModal('privacyModal')">Decline</button>

          <button class="policy-btn-accept" id="privacyAcceptBtn" onclick="acceptPolicyModal('privacyModal')">Accept</button>

        </div>

      </div>

    </div>

  </div>

  <!-- 2. TERMS OF USE MODAL -->

  <div class="policy-modal-overlay" id="termsModal">

    <div class="policy-modal-container">

      <div class="policy-modal-header">

        <h2>Terms of Use</h2>

      </div>

      <div class="policy-modal-body" id="termsBody" onscroll="checkScrollProgress('termsModal')">

        <h4>1. Acceptance of Terms</h4>

        <p>Welcome to the official website of the Dasmariñas Livelihood Training Platform operated by LEDIPO. By accessing or registering on this platform, you agree to comply with and be bound by these Terms of Use. If you do not agree to these terms, please refrain from using our services.</p>

        <h4>2. Eligibility for Training Programs</h4>

        <p>All free livelihood and technical training courses are open primarily to verified residents of the City of Dasmariñas who are at least 18 years of age. Providing false residency information, invalid identification documents, or misrepresenting personal details will result in immediate disqualification and revocation of any issued certificates.</p>

        <h4>3. User Account Responsibilities</h4>

        <p>You are responsible for maintaining the confidentiality of your account login credentials. You agree to notify LEDIPO immediately of any unauthorized use of your account. The City Government of Dasmariñas shall not be held liable for any loss or damage arising from your failure to secure your account.</p>

        <h4>4. Code of Conduct</h4>

        <p>Users and trainees agree to conduct themselves with utmost respect and professionalism during online interactions and physical training sessions at the Dasmariñas City Training Center. Harassment, disruptive behavior, violation of facility safety rules, or misuse of training equipment is strictly prohibited and grounds for expulsion.</p>

        <h4>5. Intellectual Property Rights</h4>

        <p>All platform content, training materials, layout designs, logos, and software modules developed for LEDIPO are the intellectual property of the City Government of Dasmariñas. Unauthorized reproduction, distribution, or commercial exploitation of these materials is strictly prohibited.</p>

        <h4>6. Limitation of Liability</h4>

        <p>LEDIPO endeavors to provide accurate training schedules and course offerings. However, the office reserves the right to modify, postpone, or cancel schedules due to inclement weather, local holidays, or unforeseen administrative exigencies without prior formal notice.</p>

        <!-- Agreement Checkbox -->

        <div class="policy-agreement-box">

          <input type="checkbox" id="termsAgreeCheck" onchange="toggleAcceptButton('termsModal')">

          <label for="termsAgreeCheck">I have read, fully understand, and agree to abide by these Terms of Use.</label>

        </div>

      </div>

      <div class="policy-modal-footer">

        <div class="policy-footer-left-group">

          <button class="policy-btn-download" id="termsDownloadBtn" onclick="downloadPolicyText('termsModal')">

            <i class="fas fa-download"></i> Download Terms

          </button>

        </div>

        <div class="policy-footer-right-group">

          <button class="policy-btn-decline" onclick="declinePolicyModal('termsModal')">Decline</button>

          <button class="policy-btn-accept" id="termsAcceptBtn" onclick="acceptPolicyModal('termsModal')">Accept</button>

        </div>

      </div>

    </div>

  </div>

  <!-- 3. FAQ MODAL -->

  <div class="policy-modal-overlay" id="faqModal">

    <div class="policy-modal-container">

      <div class="policy-modal-header">

        <h2>Frequently Asked Questions</h2>

      </div>

      <div class="policy-modal-body" id="faqBody" onscroll="checkScrollProgress('faqModal')">

        <h4>Q1: Who is eligible to join the LEDIPO livelihood training programs?</h4>

        <p>A: All courses are open to official residents of the City of Dasmariñas who are 18 years old and above. Slots are limited and awarded on a first-come, first-served basis upon submission of complete admission requirements.</p>

        <h4>Q2: Are the training programs and certificates completely free?</h4>

        <p>A: Yes! All community-based training courses conducted at the Dasmariñas City Training Center Main are 100% free of charge, courtesy of the City Government of Dasmariñas under Mayor's livelihood initiatives.</p>

        <h4>Q3: What documents are required for enrollment?</h4>

        <p>A: Applicants must present a Barangay-issued Residence Certificate, a valid government ID or Voter's ID/Certification, two (2) pieces of 2x2 ID photos, and meet any specific prerequisites stated for specialized tracks (e.g., basic sewing knowledge for dressmaking).</p>

        <h4>Q4: Where are the training sessions held?</h4>

        <p>A: Training sessions take place at the Dasmariñas City Training Center-Main, located at Block O-77, Lot 5, San Andres I, City of Dasmariñas, Cavite, unless specified otherwise for designated barangay outreach sessions.</p>

        <h4>Q5: How can I verify my certificate upon completion?</h4>

        <p>A: Upon successful completion of the required training hours and practical evaluations, graduates receive verified digital certificates bearing official municipal markers and CBT recognition numbers viewable on their student dashboard.</p>

        <h4>Q6: Who can I contact for urgent inquiries?</h4>

        <p>A: You can reach the LEDIPO team directly via mobile at 0969-522-8600, landline at (046) 416-4445, or email at support@ledipo.gov.ph.</p>

      </div>

      <div class="policy-modal-footer">

        <div class="policy-footer-left-group">

          <button class="policy-btn-download" id="faqDownloadBtn" onclick="downloadPolicyText('faqModal')">

            <i class="fas fa-download"></i> Download FAQ

          </button>

        </div>

        <div class="policy-footer-right-group">

          <button class="policy-btn-accept unlocked" id="faqAcceptBtn" onclick="closePolicyModal('faqModal')">Close Window</button>

        </div>

      </div>

    </div>

  </div>

  <!-- Scripts -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>

    // Profile Dropdown Toggle Script

    document.addEventListener('DOMContentLoaded', function() {

      const avatarBtn = document.getElementById('landingAvatarBtn');

      const dropdown = document.getElementById('landingProfileDropdown');

      if (!avatarBtn || !dropdown) return;

      avatarBtn.addEventListener('click', function(event) {

        event.stopPropagation();

        const isOpen = dropdown.classList.toggle('open');

        avatarBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

      });

      document.addEventListener('click', function(event) {

        if (!event.target.closest('.landing-profile')) {

          dropdown.classList.remove('open');

          avatarBtn.setAttribute('aria-expanded', 'false');

        }

      });

    });

    // Toast Notification System

    function showToast(message) {

      const existingToast = document.getElementById('activeToast');

      if (existingToast) existingToast.remove();

      const toast = document.createElement('div');

      toast.id = 'activeToast';

      toast.className = 'custom-toast';

      toast.innerHTML = `<i class="fas fa-circle-check" style="color: #f5c842; font-size: 18px;"></i> <span>${message}</span>`;

      document.body.appendChild(toast);

      setTimeout(() => {

        if (toast.parentElement) toast.remove();

      }, 3000);

    }

    // Modal Control Logic

    const modalStates = {

      privacyModal: false,

      termsModal: false,

      faqModal: false

    };

    const modalScrolledToBottom = {

      privacyModal: false,

      termsModal: false,

      faqModal: false

    };

    function openPolicyModal(modalId) {

      const modal = document.getElementById(modalId);

      modal.style.display = 'flex';

      setTimeout(() => {

        const body = document.getElementById(modalId.replace('Modal', 'Body'));

        if (body.scrollHeight <= body.clientHeight + 10) {

          modalScrolledToBottom[modalId] = true;

          checkIfReadyToUnlock(modalId);

        }

      }, 100);

    }

    function closePolicyModal(modalId) {

      document.getElementById(modalId).style.display = 'none';

    }

    function acceptPolicyModal(modalId) {

      if (!modalStates[modalId] && modalId !== 'faqModal') {

        if (!modalScrolledToBottom[modalId]) {

          alert('Please scroll through and read the entire document first.');

        } else if (!document.getElementById(modalId.replace('Modal', 'AgreeCheck')).checked) {

          alert('Please check the box to confirm you understand and agree.');

        }

        return;

      }

      const docName = modalId === 'privacyModal' ? 'Privacy Policy' : (modalId === 'termsModal' ? 'Terms of Use' : 'FAQ');

      showToast(`You have successfully accepted the ${docName}.`);

      document.getElementById(modalId).style.display = 'none';

    }

    function declinePolicyModal(modalId) {

      const docName = modalId === 'privacyModal' ? 'Privacy Policy' : 'Terms of Use';

      showToast(`You have declined the ${docName}.`);

      document.getElementById(modalId).style.display = 'none';

    }

    function checkScrollProgress(modalId) {

      if (modalScrolledToBottom[modalId]) return;

      const bodyId = modalId.replace('Modal', 'Body');

      const body = document.getElementById(bodyId);

      const scrollTop = body.scrollTop;

      const scrollHeight = body.scrollHeight - body.clientHeight;

      if (scrollHeight <= 0 || scrollTop + body.clientHeight >= body.scrollHeight - 15) {

        modalScrolledToBottom[modalId] = true;

        checkIfReadyToUnlock(modalId);

      }

    }

    function toggleAcceptButton(modalId) {

      checkIfReadyToUnlock(modalId);

    }

    function checkIfReadyToUnlock(modalId) {

      const isScrolled = modalScrolledToBottom[modalId];

      let isChecked = true;

      if (modalId !== 'faqModal') {

        const checkbox = document.getElementById(modalId.replace('Modal', 'AgreeCheck'));

        isChecked = checkbox && checkbox.checked;

      }

      if (isScrolled && isChecked) {

        unlockModal(modalId);

      } else {

        lockModal(modalId);

      }

    }

    function unlockModal(modalId) {

      modalStates[modalId] = true;

      const prefix = modalId.replace('Modal', '');

      document.getElementById(prefix + 'DownloadBtn').className = 'policy-btn-download unlocked';

      document.getElementById(prefix + 'AcceptBtn').className = 'policy-btn-accept unlocked';

    }

    function lockModal(modalId) {

      modalStates[modalId] = false;

      const prefix = modalId.replace('Modal', '');

      document.getElementById(prefix + 'DownloadBtn').className = 'policy-btn-download';

      document.getElementById(prefix + 'AcceptBtn').className = 'policy-btn-accept';

    }

    function downloadPolicyText(modalId) {

      if (!modalStates[modalId] && modalId !== 'faqModal') {

        alert('Please finish reading and check the agreement box before downloading.');

        return;

      }

      const prefix = modalId.replace('Modal', '');

      const body = document.getElementById(prefix + 'Body');

      const title = document.querySelector('#' + modalId + ' .policy-modal-header h2').innerText.replace(/[^\w\s]/gi, '').trim();

      const textContent = title.toUpperCase() + "\n" + "========================================\n\n" + body.innerText;

      const blob = new Blob([textContent], { type: 'text/plain;charset=utf-8' });

      const url = URL.createObjectURL(blob);

      const a = document.createElement('a');

      a.href = url;

      a.download = title.toLowerCase().replace(/\s+/g, '_') + '.txt';

      document.body.appendChild(a);

      a.click();

      document.body.removeChild(a);

      URL.revokeObjectURL(url);

      showToast(`Successfully downloaded ${title}.txt`);

    }

    // Backdrop click handler

    window.addEventListener('click', function(event) {

      ['privacyModal', 'termsModal', 'faqModal'].forEach(modalId => {

        const modal = document.getElementById(modalId);

        if (event.target === modal) {

          modal.style.display = 'none';

        }

      });

    });

  </script>

</body>

</html>
