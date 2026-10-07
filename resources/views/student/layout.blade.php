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

    /* ── SIDEBAR INDEX STYLES ──────────────────────────── */
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

    /* ── PRE-TEST & POST-TEST MODAL STYLES ──────────────────── */
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

    /* ── A4 LANDSCAPE CERTIFICATE MODAL ─────────────────── */
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

    /* ==========================================================
   STUDENT NOTIFICATION BELL
   ========================================================== */
    .notification-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      flex-shrink: 0;
      margin-right: 10px;
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

    /* ==========================================================
   STUDENT NOTIFICATION BELL
   ========================================================== */
    .notification-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      flex-shrink: 0;
      margin-right: 10px;
    }

    .notification-btn {
      position: relative;
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      background: transparent;
      color: #ffffff;
      font-size: 18px;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .notification-btn:hover {
      background: rgba(2, 86, 40, 0.08);
    }

    .notification-badge {
      position: absolute;
      top: 0;
      right: -1px;
      min-width: 17px;
      height: 17px;
      padding: 0 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 20px;
      background: #D4D120;
      color: #025628;
      border: 2px solid #ffffff;
      font-size: 9px;
      font-weight: 800;
    }

    /* ==========================================================
   NOTIFICATION DROPDOWN
   ========================================================== */
    .notification-dropdown {
      position: absolute;
      top: calc(100% + 12px);
      right: 0;
      width: 360px;
      max-width: calc(100vw - 24px);
      background: #ffffff;
      border-radius: 14px;
      box-shadow:
        0 14px 35px rgba(0, 0, 0, 0.18),
        0 3px 10px rgba(0, 0, 0, 0.08);
      border: 1px solid #e7e7e7;
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-8px);
      transition:
        opacity 0.2s ease,
        transform 0.2s ease,
        visibility 0.2s ease;
      z-index: 2000;
    }

    .notification-dropdown.open {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .notification-header {
      padding: 17px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .notification-header h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 800;
      color: #025628;
    }

    .notification-header span {
      display: block;
      margin-top: 3px;
      font-size: 11px;
      color: #777;
    }

    .notification-header-icon {
      font-size: 18px;
      color: #025628;
    }

    .notification-divider {
      height: 1px;
      background: #eeeeee;
    }

    /* ==========================================================
   NOTIFICATION ITEMS
   ========================================================== */
    .notification-list {
      max-height: 350px;
      overflow-y: auto;
    }

    .notification-item {
      display: flex;
      gap: 12px;
      padding: 14px 16px;
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.2s ease;
    }

    .notification-item:hover {
      background: #f8faf8;
    }

    /* ==========================================================
   READ / UNREAD VISUAL STATE
   ========================================================== */
    .notification-item.notification-unread {
      background: #f3faf6;
      box-shadow: inset 4px 0 0 #025628;
    }

    .notification-item.notification-unread .notification-item-title {
      font-weight: 900;
      color: #025628;
    }

    .notification-item:not(.notification-unread) .notification-item-title {
      font-weight: 500;
      color: #555;
    }

    .notification-item:not(.notification-unread) .notification-item-message {
      color: #888;
    }

    .notification-item:not(.notification-unread) .notification-item-icon {
      opacity: 0.65;
    }

    .notification-item:last-child {
      border-bottom: none;
    }

    .notification-item-icon {
      flex: 0 0 36px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #e6f4eb;
      color: #025628;
      font-size: 14px;
    }

    .notification-type-urgent {
      background: #fff0f0;
      color: #c62828;
    }

    .notification-type-reminder {
      background: #fff8df;
      color: #8a6d00;
    }

    .notification-item-content {
      min-width: 0;
      flex: 1;
    }

    .notification-item-title {
      font-size: 13px;
      font-weight: 500;
      color: #222;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .notification-item-message {
      margin-top: 3px;
      font-size: 12px;
      line-height: 1.45;
      color: #666;
    }

    .notification-item-meta {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: 7px;
      font-size: 10px;
      color: #8a8a8a;
    }

    /* ==========================================================
   EMPTY STATE
   ========================================================== */
    .notification-empty {
      padding: 35px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .notification-empty-icon {
      width: 48px;
      height: 48px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: #f1f5f2;
      color: #777;
      font-size: 18px;
    }

    .notification-empty strong {
      font-size: 13px;
      color: #333;
    }

    .notification-empty span {
      margin-top: 4px;
      font-size: 11px;
      color: #888;
    }

    /* ==========================================================
   FOOTER
   ========================================================== */
    .notification-footer {
      padding: 12px 16px;
      border-top: 1px solid #eeeeee;
      background: #fafafa;
      text-align: center;
    }

    .notification-footer a {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: #025628;
      font-size: 12px;
      font-weight: 800;
      text-decoration: none;
    }

    .notification-footer a:hover {
      text-decoration: underline;
    }

    .notification-footer i {
      font-size: 10px;
    }

    /* ==========================================================
   TOPBAR ALIGNMENT
   ========================================================== */
    .topbar-right {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      flex-wrap: nowrap;
    }

    .topbar-right .avatar-btn {
      flex-shrink: 0;
    }

    /* Keep profile dropdown anchored to the right */
    .topbar-right .dropdown {
      position: absolute !important;
      top: calc(100% + 10px) !important;
      right: 0 !important;
      left: auto !important;
      margin: 0 !important;
      z-index: 3000 !important;
    }

    /* ==========================================================
   MOBILE
   ========================================================== */
    @media (max-width: 600px) {
      .notification-dropdown {
        position: fixed;
        top: 70px;
        right: 10px;
        left: 10px;
        width: auto;
        max-width: none;
      }

      .notification-item.notification-unread {
        background: #f7fbf8;
      }

      .notification-item.notification-unread .notification-item-title {
        font-weight: 800;
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

      <<<<<<< HEAD <a href="{{ route('homepage') }}" class="topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="logo"
          class="topbar-logo">
        <span>LEDIPO</span>
        </a>
        =======
        <a href="{{ route('homepage') }}" class="topbar-brand">
          <img src="{{ asset('images/logo.png') }}" alt="logo"
            class="topbar-logo">
          <span>LEDIPO</span>
        </a>
    </div>

    <div class="topbar-right">

      <!-- Notification Bell -->
      <div class="notification-wrapper" id="notificationWrapper">

        <button type="button" class="notification-btn" id="notificationBtn"
          aria-label="Open notifications" aria-expanded="false">

          <i class="fa-solid fa-bell"></i>

          @php
            // Student receives General and Student announcements.
            $notificationAllAnnouncements = \App\Models\Announcement::where(
                'is_active',
                1,
            )
                ->whereIn('audience', ['general', 'student'])
                ->latest()
                ->get();

            // Read announcements for the currently logged-in Student.
            $notificationReadIds = \DB::table('announcement_reads')
                ->where('user_id', \Auth::id())
                ->whereIn(
                    'announcement_id',
                    $notificationAllAnnouncements->pluck('id'),
                )
                ->pluck('announcement_id')
                ->toArray();

            /*
    |--------------------------------------------------------------------------
    | Student Notification Dropdown
    |--------------------------------------------------------------------------
    | Show the latest 5 announcements PLUS any older unread
    | announcements so unread notifications are never hidden.
    |--------------------------------------------------------------------------
    */

            $notificationLatest = $notificationAllAnnouncements->take(5);

            $notificationUnread = $notificationAllAnnouncements->whereNotIn(
                'id',
                $notificationReadIds,
            );

            $notificationAnnouncements = $notificationLatest
                ->concat($notificationUnread)
                ->unique('id')
                ->sortByDesc('created_at')
                ->values();

            $notificationCount = $notificationUnread->count();
          @endphp

          @if ($notificationCount > 0)
            <span class="notification-badge">
              {{ $notificationCount > 99 ? '99+' : $notificationCount }}
            </span>
          @endif

        </button>

        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">

          <div class="notification-header">
            <div>
              <h3>Notifications</h3>

              <span>
                {{ $notificationCount }} unread
                notification{{ $notificationCount != 1 ? 's' : '' }}
              </span>
            </div>

            <i class="fa-solid fa-bell notification-header-icon"></i>
          </div>

          <div class="notification-divider"></div>

          <div class="notification-list">

            @forelse($notificationAnnouncements as $announcement)
              <div
                class="notification-item {{ in_array($announcement->id, $notificationReadIds, true) ? '' : 'notification-unread' }}"
                data-announcement-id="{{ $announcement->id }}" role="button"
                tabindex="0">

                <div
                  class="notification-item-icon
                            notification-type-{{ strtolower($announcement->type) }}">

                  @if (strtolower($announcement->type) === 'urgent')
                    <i class="fa-solid fa-triangle-exclamation"></i>
                  @elseif(strtolower($announcement->type) === 'reminder')
                    <i class="fa-solid fa-clock"></i>
                  @else
                    <i class="fa-solid fa-bell"></i>
                  @endif

                </div>

                <div class="notification-item-content">

                  <div class="notification-item-title">
                    {{ $announcement->title }}
                  </div>

                  <div class="notification-item-message">
                    {{ \Illuminate\Support\Str::limit($announcement->message, 75) }}
                  </div>

                  <div class="notification-item-meta">
                    <span>
                      {{ ucfirst($announcement->type) }}
                    </span>

                    <span>•</span>

                    <span>
                      {{ $announcement->created_at?->diffForHumans() }}
                    </span>
                  </div>

                </div>

              </div>

            @empty

              <div class="notification-empty">

                <div class="notification-empty-icon">
                  <i class="fa-regular fa-bell-slash"></i>
                </div>

                <strong>No notifications</strong>

                <span>
                  You're all caught up.
                </span>

              </div>
            @endforelse

          </div>

          <div class="notification-footer">

            <a
              href="{{ Route::has('student.announcements') ? route('student.announcements') : '/student/announcements' }}">

              View All Announcements

              <i class="fa-solid fa-arrow-right"></i>

            </a>

          </div>

        </div>

      </div>

      <!-- Student Profile -->
      <button class="avatar-btn" id="avatarBtn" aria-label="Open profile menu">

        {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

      </button>

      <div class="dropdown" id="dropdown">

        <div class="dropdown-header">

          <div class="dd-avatar">

            {{ strtoupper(substr(Auth::user()->firstname ?? 'M', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? 'B', 0, 1)) }}

          </div>

          <div>

            <div class="dh-name">
              {{ Auth::user()->firstname ?? 'Student' }}
              {{ Auth::user()->lastname ?? '' }}
            </div>

            <div class="dh-role">
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
    >>>>>>> 384453d6cf3600e14902e811c88999cfb75c91ce
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
              {{ ucfirst(Auth::user()->role ?? 'Student') }}</div>
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

          <<<<<<< HEAD <a href="{{ url('/') }}" class="dd-item">
            <i class="fa fa-house dd-icon"></i>
            Home
            =======
            {{-- ── INDEX DROPDOWN MENU ── --}}
            @if (isset($unitNumbers) && $unitNumbers->isNotEmpty())
              <div id="sidebarIndexMenu">
                <div class="sidebar-index-title">INDEX</div>

                {{-- Introduction --}}
                <div class="index-item"
                  onclick="toggleSubMenu('introSub'); if(typeof showIntroScreen === 'function') showIntroScreen();">
                  <span>Introduction</span>
                  <i class="fa fa-chevron-down" style="font-size:10px;"></i>
                </div>
                <div class="index-sub-menu show" id="introSub">
                  <span
                    onclick="if(typeof showIntroScreen === 'function') showIntroScreen()"
                    class="index-sub-link active">• Welcome & Course
                    Overview</span>
                </div>

                {{-- Real Units --}}
                @foreach ($unitNumbers as $unitNum)
                  <div class="index-item"
                    onclick="toggleSubMenu('unit{{ $unitNum }}Sub'); if(typeof showUnitScreen === 'function') showUnitScreen({{ $unitNum }});">
                    <span>Unit {{ $unitNum }}</span>
                    <i class="fa fa-chevron-down" style="font-size:10px;"></i>
                  </div>
                  <div class="index-sub-menu"
                    id="unit{{ $unitNum }}Sub">
                    @foreach ($modules->where('unit_number', $unitNum) as $module)
                      <span class="index-sub-link"
                        onclick="showUnitScreen({{ $unitNum }}); setTimeout(() => document.getElementById('module-{{ $module->id }}')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);">
                        • {{ $module->title }}
                      </span>
                    @endforeach
                  </div>
                @endforeach

                {{-- Completion --}}
                <div class="index-item"
                  onclick="toggleSubMenu('completionSub'); if(typeof showCompletionScreen === 'function') showCompletionScreen();">
                  <span>Completion</span>
                  <i class="fa fa-chevron-down" style="font-size:10px;"></i>
                </div>
                <div class="index-sub-menu" id="completionSub">
                  <span class="index-sub-link"
                    onclick="showCompletionScreen(); setTimeout(() => document.getElementById('post-test-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);">
                    • Post-Test
                  </span>

                  <span class="index-sub-link"
                    onclick="showCompletionScreen(); setTimeout(() => document.getElementById('hands-on-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);">
                    • Hands-On Evaluation
                  </span>

                  <span class="index-sub-link"
                    onclick="showCompletionScreen(); setTimeout(() => document.getElementById('certificate-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);">
                    • Certificate
                  </span>
                </div>
              </div>
            @endif

            <a href="{{ Route::has('student.announcements') ? route('student.announcements') : '/student/announcements' }}"
              class="nav-item {{ request()->routeIs('student.announcements') ? 'active' : '' }}">
              <span>Announcement</span>
              >>>>>>> 384453d6cf3600e14902e811c88999cfb75c91ce
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

      {{-- ── INDEX DROPDOWN MENU ── --}}
      @if (isset($unitNumbers) && $unitNumbers->isNotEmpty())
        <div id="sidebarIndexMenu">
          <div class="sidebar-index-title">INDEX</div>

          {{-- Introduction --}}
          <div class="index-item"
            onclick="toggleSubMenu('introSub'); if(typeof showIntroScreen === 'function') showIntroScreen();">
            <span>Introduction</span>
            <i class="fa fa-chevron-down" style="font-size:10px;"></i>
          </div>
          <div class="index-sub-menu show" id="introSub">
            <span
              onclick="if(typeof showIntroScreen === 'function') showIntroScreen()"
              class="index-sub-link active">• Welcome & Course Overview</span>
          </div>

          {{-- Real Units --}}
          @foreach ($unitNumbers as $unitNum)
            <div class="index-item"
              onclick="toggleSubMenu('unit{{ $unitNum }}Sub'); if(typeof showUnitScreen === 'function') showUnitScreen({{ $unitNum }});">
              <span>Unit {{ $unitNum }}</span>
              <i class="fa fa-chevron-down" style="font-size:10px;"></i>
            </div>
            <div class="index-sub-menu" id="unit{{ $unitNum }}Sub">
            </div>
          @endforeach

          {{-- Completion --}}
          <div class="index-item"
            onclick="toggleSubMenu('completionSub'); if(typeof showCompletionScreen === 'function') showCompletionScreen();">
            <span>Completion</span>
            <i class="fa fa-chevron-down" style="font-size:10px;"></i>
          </div>
          <div class="index-sub-menu" id="completionSub"></div>
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
            <li>Ensure a stable internet connection before starting.</li>
            <li>You only have <strong>1 attempt</strong> to take this test.</li>
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
            <li>This is the final written evaluation containing <strong>80
                questions</strong>.</li>
            <li>You need a score of <strong>80% or higher</strong> to pass.</li>
            <li>Ensure you complete all items before submitting.</li>
          </ul>
        </div>

        <button onclick="triggerStartPosttest()" class="btn-start-pretest">
          Start Post-Test
        </button>
      </div>
    </div>
  </div>

  {{-- ── A4 LANDSCAPE CERTIFICATE MODAL ─────────────────────── --}}
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
              TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
              CITY GOVERNMENT OF DASMARIÑAS - LEDIPO
            </div>

            <div class="cert-main-heading">CERTIFICATE OF COMPLETION</div>
            <div class="cert-certify-text">THIS COMS CERTIFIY THAT</div>
          </div>

          <div>
            <div class="cert-student-name">
              {{ Auth::user()->firstname ?? 'Samira' }}
              {{ Auth::user()->lastname ?? 'Hadid' }}
            </div>
            <div class="cert-completion-desc">HAS SUCCESSFULLY COMPLETED THE
              TRAINING IN</div>

            <div class="cert-course-title" id="certDynamicCourseTitle">
              DRESSMAKING
            </div>

            <div class="cert-conducted-by">Conducted by</div>
            <div class="cert-office-name">
              DASMARIÑAS LOCAL ECONOMIC DEVELOPMENT AND INVESTMENT PROMOTION
              OFFICE (LEDIPO)
            </div>

            <div class="cert-date-range">
              from April 15, 2026 to May 11, 2026
            </div>

            <div class="cert-given-text">
              Given this 18th day of April 2026 at Dasmariñas City, Cavite,
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
                <div class="cert-sig-name">HON. JENNIFER A. BARZAGA</div>
                <div class="cert-sig-title">City Mayor</div>
              </div>

              <div class="cert-sig-block">
                <div class="cert-sig-line"></div>
                <div class="cert-sig-name">MR. CARLOS H. LEGASPI</div>
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
          <button onclick="window.print()"
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

  <<<<<<< HEAD <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('dropdown'); ===
    ===
    =

    <
    script >
      document.addEventListener('DOMContentLoaded', function() {

        const notificationDropdown =
          document.getElementById('notificationDropdown');

        if (!notificationDropdown) return;

        function markNotificationRead(item) {

          if (!item) return;

          if (item.dataset.read === '1') return;

          const announcementId = item.dataset.announcementId;

          if (!announcementId) {
            console.error(
              'Student notification is missing data-announcement-id.'
            );
            return;
          }

          fetch(
              `{{ url('/notifications/announcements') }}/${announcementId}/read`, {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json',
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
              })
            .then(response => {

              if (!response.ok) {
                throw new Error(
                  `Notification read request failed: ${response.status}`
                );
              }

              return response.json();
            })
            .then(data => {

              if (!data || !data.success) {
                throw new Error(
                  'Server did not confirm the notification as read.'
                );
              }

              item.dataset.read = '1';
              item.classList.remove('notification-unread');

              const notificationBadge =
                document.querySelector('.notification-badge');

              if (notificationBadge) {

                const currentCount = parseInt(
                  notificationBadge.textContent
                  .replace('+', '')
                  .trim(),
                  10
                ) || 0;

                const nextCount = Math.max(currentCount - 1, 0);

                if (nextCount === 0) {
                  notificationBadge.remove();
                } else {
                  notificationBadge.textContent =
                    nextCount > 99 ? '99+' : String(nextCount);
                }
              }

              const unreadItems =
                notificationDropdown.querySelectorAll(
                  '.notification-item.notification-unread'
                ).length;

              const headerUnread =
                document.querySelector(
                  '.notification-header > div > span'
                );

              if (headerUnread) {
                headerUnread.textContent =
                  `${unreadItems} unread notification${unreadItems !== 1 ? 's' : ''}`;
              }

            })
            .catch(error => {
              console.error(
                'Student notification read error:',
                error
              );
            });
        }

        notificationDropdown
          .querySelectorAll(
            '.notification-item[data-announcement-id]'
          )
          .forEach(item => {

            item.addEventListener('click', function() {
              markNotificationRead(this);
            });

            item.addEventListener('keydown', function(event) {

              if (event.key === 'Enter' || event.key === ' ') {

                event.preventDefault();

                markNotificationRead(this);
              }
            });
          });

      });
  </script>
  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('dropdown');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById(
      'notificationDropdown'); >>>
    >>>
    > 384453 d6cf3600e14902e811c88999cfb75c91ce

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

    <<
    <<
    << < HEAD
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
      if (el) {
        el.classList.toggle('show');
      }
    } ===
    ===
    =
    if (avatarBtn) {
      avatarBtn.addEventListener('click', function(e) {
        e.stopPropagation();

        dropdown.classList.toggle('open');

        // Close notification dropdown
        if (notificationDropdown) {
          notificationDropdown.classList.remove('open');
        }

        if (notificationBtn) {
          notificationBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    document.addEventListener('click', function(e) {

      if (!e.target.closest('.topbar-right')) {

        if (dropdown) {
          dropdown.classList.remove('open');
        }

        if (notificationDropdown) {
          notificationDropdown.classList.remove('open');
        }

        if (notificationBtn) {
          notificationBtn.setAttribute('aria-expanded', 'false');
        }

      }

    });

    if (notificationBtn) {

      notificationBtn.addEventListener('click', function(e) {

        e.stopPropagation();

        notificationDropdown.classList.toggle('open');

        notificationBtn.setAttribute(
          'aria-expanded',
          notificationDropdown.classList.contains('open') ?
          'true' :
          'false'
        );

        // Close profile dropdown
        if (dropdown) {
          dropdown.classList.remove('open');
        }

      });

    }

    function toggleSubMenu(id) {
      const el = document.getElementById(id);
      if (el) {
        el.classList.toggle('show');
      }
    } >>>
    >>>
    > 384453 d6cf3600e14902e811c88999cfb75c91ce

    /* ── Pre-test Modal Handlers ────────────────────────── */
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

    /* ── Post-test Modal Handlers ────────────────────────── */
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

    /* ── Certificate Modal Handlers ──────────────────────── */
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

