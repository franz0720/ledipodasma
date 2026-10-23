{{--
  registrationform1.blade.php
  Step 1 of 2 — Learner's Profile Form (TESDA MIS 03-01)
--}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Learner's Profile Form — TESDA</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    :root {
      --navy: #025628;
      --navy-deep: #013d1c;
      --paper: #f3f6f4;
      --line: #cfd9d3;
      --ink: #1c2420;
      --muted: #5c6b62;
      --field-bg: #ffffff;
      --accent-soft: #eaf2ec;
      --radius: 6px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Georgia", "Times New Roman", serif;
      background: var(--paper);
      color: var(--ink);
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Container wrapper to keep layout clean */
    .form-page-container {
      flex: 1;
      padding: 40px 16px 50px;
      max-width: 920px;
      margin: 0 auto;
      width: 100%;
    }

    /* Dashboard Button Top Style */
    .dashboard-btn-wrapper {
      max-width: 880px;
      margin: 0 auto 20px auto;
      display: flex;
      justify-content: flex-start;
    }

    .btn-dashboard-top {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #006b35;
      color: white;
      border: 1px solid var(--line);
      padding: 10px 20px;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
      transition: all 0.2s ease;
    }

    .btn-dashboard-top:hover {
      background: var(--accent-soft);
      border-color: var(--navy);
      color: var(--navy-deep);
      text-decoration: none;
    }

    .sheet {
      max-width: 880px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid var(--line);
      box-shadow: 0 2px 24px rgba(13, 47, 94, 0.08);
    }

    .letterhead {
      background: linear-gradient(135deg, var(--navy) 0%, var(--navy-deep) 100%);
      color: #fff;
      padding: 22px 28px;
      display: flex;
      align-items: center;
      gap: 18px;
      position: relative;
      overflow: hidden;
    }

    .letterhead::after {
      content: "";
      position: absolute;
      right: -40px;
      top: -40px;
      width: 160px;
      height: 160px;
      border: 2px solid rgba(255, 255, 255, 0.25);
      border-radius: 50%;
    }

    .crest {
      width: 56px;
      height: 56px;
      flex-shrink: 0;
      border-radius: 50%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--navy);
      font-family: "Trebuchet MS", sans-serif;
      font-weight: 700;
      font-size: 11px;
      text-align: center;
      border: 2px solid var(--navy);
    }

    .letterhead .agency {
      font-family: "Trebuchet MS", sans-serif;
    }

    .agency .en {
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .03em;
    }

    .agency .fil {
      font-size: 11px;
      color: #d7e6dc;
      margin-top: 2px;
    }

    .titlebar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 28px;
      border-bottom: 3px solid var(--navy);
      background: #fff;
    }

    .titlebar h1 {
      margin: 0;
      font-size: 26px;
      letter-spacing: .02em;
      color: var(--navy-deep);
      font-family: "Trebuchet MS", sans-serif;
    }

    .step-indicator {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      font-weight: 700;
      color: #fff;
      background: var(--navy);
      padding: 4px 12px;
      border-radius: 20px;
      letter-spacing: .03em;
    }

    .formcode {
      font-family: "Courier New", monospace;
      font-size: 11px;
      text-align: right;
      color: var(--muted);
      line-height: 1.4;
    }

    .section {
      padding: 20px 28px;
      border-bottom: 1px solid var(--line);
    }

    .section:last-of-type {
      border-bottom: none;
    }

    .section-head {
      display: flex;
      align-items: baseline;
      gap: 10px;
      margin-bottom: 14px;
    }

    .section-head .num {
      font-family: "Trebuchet MS", sans-serif;
      font-weight: 700;
      color: #fff;
      background: var(--navy);
      padding: 2px 10px;
      border-radius: 3px;
      font-size: 14px;
    }

    .section-head h2 {
      margin: 0;
      font-size: 16px;
      font-family: "Trebuchet MS", sans-serif;
      color: var(--navy-deep);
      text-transform: uppercase;
      letter-spacing: .04em;
    }

    .subhead {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--navy);
      margin: 16px 0 8px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .subhead .dot {
      width: 6px;
      height: 6px;
      background: var(--navy);
      border-radius: 50%;
      display: inline-block;
    }

    .subhead .req {
      color: #b3261e;
      font-weight: 700;
      font-size: 11px;
    }

    .subhead .optional,
    label .optional {
      color: var(--muted);
      font-weight: 400;
      font-style: italic;
      font-size: 10.5px;
      text-transform: none;
      letter-spacing: 0;
    }

    .row {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 12px;
    }

    .field {
      flex: 1 1 160px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .field.small {
      flex: 0 1 110px;
    }

    .field.wide {
      flex: 2 1 260px;
    }

    label {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .03em;
    }

    label .req {
      color: #b3261e;
      font-weight: 700;
    }

    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="tel"],
    select {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 8px 10px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 14px;
      background: var(--field-bg);
      color: var(--ink);
      width: 100%;
    }

    input:focus,
    select:focus {
      outline: 2px solid var(--navy);
      outline-offset: 1px;
      border-color: var(--navy);
    }

    .id-photo-wrap {
      display: flex;
      gap: 20px;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .id-photo {
      width: 130px;
      height: 150px;
      border: 2px dashed var(--line);
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      color: var(--muted);
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      text-align: center;
      cursor: pointer;
      background: var(--accent-soft);
      overflow: hidden;
      flex-shrink: 0;
    }

    .id-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .id-photo:hover {
      border-color: var(--navy);
    }

    .id-photo input {
      display: none;
    }

    .uli-block {
      flex: 1 1 260px;
    }

    .uli-single {
      font-family: "Courier New", monospace;
      letter-spacing: .03em;
    }

    .choice-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 10px 22px;
    }

    .choice {
      display: flex;
      align-items: center;
      gap: 6px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 13.5px;
    }

    .choice input {
      width: 15px;
      height: 15px;
      accent-color: var(--navy);
      cursor: pointer;
    }

    .two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0 32px;
    }

    @media (max-width:640px) {
      .two-col {
        grid-template-columns: 1fr;
      }
    }

    .edu-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 8px 24px;
    }

    @media (max-width:640px) {
      .edu-grid {
        grid-template-columns: 1fr;
      }
    }

    .footer-note {
      padding: 16px 28px 24px;
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: var(--muted);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .btn {
      background: var(--navy);
      color: #fff;
      border: none;
      padding: 12px 26px;
      border-radius: var(--radius);
      font-family: "Trebuchet MS", sans-serif;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: .02em;
      cursor: pointer;
      transition: background .15s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn:hover {
      background: var(--navy-deep);
    }

    .error-text {
      font-family: "Trebuchet MS", sans-serif;
      font-size: 11px;
      color: #b3261e;
      margin-top: 3px;
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
      padding: 40px 0 20px 0;
      width: 100%;
      box-sizing: border-box;
      margin-top: auto;
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
      margin: 25px auto 0 auto;
      padding: 15px 40px 0 40px;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      text-align: center;
      box-sizing: border-box;
    }

    .footer-bottom p {
      font-size: 12px;
      color: #cbd5e1;
      margin: 0;
    }

    /* POLICY MODALS & TOAST STYLING */
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

    .policy-btn-download,
    .policy-btn-decline,
    .policy-btn-accept {
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }

    .policy-btn-download { background: #1a4d2e; color: #fff; opacity: 0.4; cursor: not-allowed; }
    .policy-btn-download.unlocked { opacity: 1; cursor: pointer; }
    .policy-btn-decline { background: #cf2e2e; color: #fff; }
    .policy-btn-accept { background: #cbd5e1; color: #64748b; opacity: 0.5; cursor: not-allowed; }
    .policy-btn-accept.unlocked { opacity: 1; cursor: pointer; background: #06381e; color: #fff; }

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
    }

    .logout-modal-overlay {
      display: none;
      position: fixed;
      z-index: 999999;
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

    .logout-modal-box {
      background: #ffffff;
      width: 100%;
      max-width: 400px;
      border-radius: 16px;
      padding: 30px;
      text-align: center;
      border: 3px solid #1a4d2e;
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .logout-modal-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      margin-top: 20px;
    }

    .btn-logout-yes { background: #1a4d2e; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-logout-no { background: #e2e8f0; color: #334155; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; }
  </style>
</head>

<body>

  <!-- REGISTRATION FORM CONTAINER -->
  <div class="form-page-container">
    @php
      $step1 = $step1 ?? [];
    @endphp

    <!-- GO TO DASHBOARD BUTTON -->
    <div class="dashboard-btn-wrapper">
      <a href="{{ route('homepage') ?? '#' }}" class="btn-dashboard-top">
        &larr; Go to Dashboard
      </a>
    </div>

    <form class="sheet" id="learnerForm" method="POST"
      action="{{ route('registration.step2') }}" enctype="multipart/form-data" novalidate>
      @csrf

      @if ($errors->any())
        <div style="background:#fdecea;border:1px solid #f5c6cb;color:#b3261e;padding:14px 20px;margin:20px 28px 0;border-radius:6px;font-family:'Trebuchet MS',sans-serif;font-size:13px;">
          <strong>Please fix the following before continuing:</strong>
          <ul style="margin:8px 0 0;padding-left:18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="letterhead">
        <div class="crest">TESDA</div>
        <div class="agency">
          <div class="en">Technical Education and Skills Development Authority</div>
          <div class="fil">Pangasiwaan sa Edukasyong Teknikal at Pagpapaunlad ng Kaanyuan</div>
        </div>
      </div>

      <div class="titlebar">
        <h1>Registration Form</h1>
        <div style="display:flex; align-items:center; gap:14px;">
          <span class="step-indicator">Step 1 of 2</span>
          <div class="formcode">MIS 03&ndash;01<br>(rev. 2020)</div>
        </div>
      </div>

      <!-- 1. TE2MIS -->
      <div class="section">
        <div class="section-head">
          <span class="num">1</span>
          <h2>TE2MIS Auto Generated</h2>
        </div>
        <div class="id-photo-wrap">
          <div class="uli-block">
            <div class="row" style="margin-bottom:14px;">
              <div class="field wide">
                <label>1.1 Unique Learner Identifier (ULI) Number <span class="optional">(optional)</span></label>
                <input type="text" name="uli_number" class="uli-single"
                  value="{{ old('uli_number', $step1['uli_number'] ?? '') }}"
                  placeholder="e.g. 04-1234-56789012" maxlength="20">
              </div>
            </div>
            <div class="row">
              <div class="field small">
                <label>1.2 Entry Date <span class="optional">(optional)</span></label>
                <input type="date" name="entry_date"
                  value="{{ old('entry_date', $step1['entry_date'] ?? '') }}">
              </div>
            </div>
          </div>
          <label class="id-photo" for="idPhotoInput">
            <span id="idPhotoText">
              @if (!empty($step1['id_picture_path']))
                Photo already uploaded.<br>Click to replace.
              @else
                Click to<br>upload<br>ID Picture
              @endif
              <br><span class="req">*</span></span>
            <img id="idPhotoPreview" style="display:none;">
            <input type="file" id="idPhotoInput" name="id_picture"
              accept="image/*" required>
          </label>
        </div>
      </div>

      <!-- 2. Learner/Manpower Profile -->
      <div class="section">
        <div class="section-head">
          <span class="num">2</span>
          <h2>Learner / Manpower Profile</h2>
        </div>

        <div class="subhead"><span class="dot"></span>2.1 Name</div>
        <div class="row">
          <div class="field wide"><label>Last Name, Extension (Jr., Sr.) <span class="req">*</span></label><input type="text" name="last_name" value="{{ old('last_name', $step1['last_name'] ?? '') }}" required></div>
          <div class="field wide"><label>First Name <span class="req">*</span></label><input type="text" name="first_name" value="{{ old('first_name', $step1['first_name'] ?? '') }}" required></div>
          <div class="field wide"><label>Middle Name <span class="optional">(optional)</span></label><input type="text" name="middle_name" value="{{ old('middle_name', $step1['middle_name'] ?? '') }}"></div>
        </div>

        <div class="subhead"><span class="dot"></span>2.2 Complete Permanent Mailing Address</div>
        <div class="row">
          <div class="field wide"><label>Number, Street <span class="req">*</span></label><input type="text" name="address_street" value="{{ old('address_street', $step1['address_street'] ?? '') }}" required></div>
          <div class="field"><label>Barangay <span class="req">*</span></label><input type="text" name="address_barangay" value="{{ old('address_barangay', $step1['address_barangay'] ?? '') }}" required></div>
        </div>
        <div class="row">
          <div class="field"><label>City / Municipality <span class="req">*</span></label><input type="text" name="address_city" value="{{ old('address_city', $step1['address_city'] ?? '') }}" required></div>
          <div class="field"><label>Province <span class="req">*</span></label><input type="text" name="address_province" value="{{ old('address_province', $step1['address_province'] ?? '') }}" required></div>
          <div class="field"><label>District <span class="optional">(optional)</span></label><input type="text" name="address_district" value="{{ old('address_district', $step1['address_district'] ?? '') }}"></div>
          <div class="field"><label>Region <span class="optional">(optional)</span></label><input type="text" name="address_region" value="{{ old('address_region', $step1['address_region'] ?? '') }}"></div>
        </div>
        <div class="row">
          <div class="field wide"><label>Email Address / Facebook Account <span class="req">*</span></label><input type="email" name="email" value="{{ old('email', $step1['email'] ?? '') }}" required></div>
          <div class="field"><label>Contact No. <span class="req">*</span></label><input type="tel" name="contact_no" value="{{ old('contact_no', $step1['contact_no'] ?? '') }}" required></div>
          <div class="field"><label>Nationality <span class="req">*</span></label><input type="text" name="nationality" value="{{ old('nationality', $step1['nationality'] ?? '') }}" required></div>
        </div>

        <div class="subhead"><span class="dot"></span>2.3 Training Venue <span class="req">*</span></div>
        <div class="row">
          <div class="field wide"><input type="text" name="training_venue" value="{{ old('training_venue', $step1['training_venue'] ?? '') }}" placeholder="Name of training center / venue" required></div>
        </div>
      </div>

      <!-- 3. Personal Information -->
      <div class="section">
        <div class="section-head">
          <span class="num">3</span>
          <h2>Personal Information</h2>
        </div>

        <div class="two-col">
          <div>
            <div class="subhead"><span class="dot"></span>3.1 Sex <span class="req">*</span></div>
            <div class="choice-grid">
              <label class="choice"><input type="radio" name="sex" value="Male" required @if (old('sex', $step1['sex'] ?? '') == 'Male') checked @endif> Male</label>
              <label class="choice"><input type="radio" name="sex" value="Female" @if (old('sex', $step1['sex'] ?? '') == 'Female') checked @endif> Female</label>
            </div>
          </div>
          <div>
            <div class="subhead"><span class="dot"></span>3.3 Employment Status <span style="font-weight:400;color:var(--muted);text-transform:none;">(before training)</span> <span class="req">*</span></div>
            <div class="choice-grid">
              <label class="choice"><input type="radio" name="employment_status" value="Employed" required @if (old('employment_status', $step1['employment_status'] ?? '') == 'Employed') checked @endif> Employed</label>
              <label class="choice"><input type="radio" name="employment_status" value="Unemployed" @if (old('employment_status', $step1['employment_status'] ?? '') == 'Unemployed') checked @endif> Unemployed</label>
            </div>
          </div>
        </div>

        <div class="subhead"><span class="dot"></span>3.2 Civil Status <span class="req">*</span></div>
        <div class="choice-grid" style="margin-bottom:6px;">
          <label class="choice"><input type="radio" name="civil_status" value="Single" required @if (old('civil_status', $step1['civil_status'] ?? '') == 'Single') checked @endif> Single</label>
          <label class="choice"><input type="radio" name="civil_status" value="Married" @if (old('civil_status', $step1['civil_status'] ?? '') == 'Married') checked @endif> Married</label>
          <label class="choice"><input type="radio" name="civil_status" value="Widowed" @if (old('civil_status', $step1['civil_status'] ?? '') == 'Widowed') checked @endif> Widowed</label>
          <label class="choice"><input type="radio" name="civil_status" value="Separated" @if (old('civil_status', $step1['civil_status'] ?? '') == 'Separated') checked @endif> Separated</label>
          <label class="choice"><input type="radio" name="civil_status" value="Solo Parent" @if (old('civil_status', $step1['civil_status'] ?? '') == 'Solo Parent') checked @endif> Solo Parent</label>
        </div>

        <div class="subhead"><span class="dot"></span>3.4 Birthdate <span class="req">*</span></div>
        <div class="row">
          <div class="field"><label>Month of Birth</label><input type="text" name="birth_month" value="{{ old('birth_month', $step1['birth_month'] ?? '') }}" placeholder="e.g. January" required></div>
          <div class="field small"><label>Day of Birth</label><input type="text" name="birth_day" value="{{ old('birth_day', $step1['birth_day'] ?? '') }}" placeholder="DD" required></div>
          <div class="field small"><label>Year of Birth</label><input type="text" name="birth_year" value="{{ old('birth_year', $step1['birth_year'] ?? '') }}" placeholder="YYYY" required></div>
          <div class="field small"><label>Age</label><input type="text" name="age" value="{{ old('age', $step1['age'] ?? '') }}" required></div>
        </div>

        <div class="subhead"><span class="dot"></span>3.5 Birthplace <span class="optional">(optional)</span></div>
        <div class="row">
          <div class="field"><label>City / Municipality</label><input type="text" name="birthplace_city" value="{{ old('birthplace_city', $step1['birthplace_city'] ?? '') }}"></div>
          <div class="field"><label>Province</label><input type="text" name="birthplace_province" value="{{ old('birthplace_province', $step1['birthplace_province'] ?? '') }}"></div>
          <div class="field"><label>Region</label><input type="text" name="birthplace_region" value="{{ old('birthplace_region', $step1['birthplace_region'] ?? '') }}"></div>
        </div>

        <div class="subhead"><span class="dot"></span>3.6 Educational Attainment Before the Training (Trainee) <span class="req">*</span></div>
        @php
          $eduOptions = [
              'No Grade Completed',
              'Pre-School (Nursery/Kinder/Prep)',
              'High School Undergraduate',
              'High School Graduate',
              'Junior High School Graduate',
              'Senior High School Graduate',
              'Elementary Undergraduate',
              'Elementary Graduate',
              'Post Secondary Undergraduate',
              'Post Secondary Graduate',
              'College Undergraduate',
              'College Graduate or Higher',
          ];
        @endphp
        <div class="edu-grid">
          @foreach ($eduOptions as $i => $opt)
            <label class="choice">
              <input type="radio" name="education_attainment" value="{{ $opt }}" @if ($i === 0) required @endif @if (old('education_attainment', $step1['education_attainment'] ?? '') == $opt) checked @endif>
              {{ $opt }}
            </label>
          @endforeach
        </div>

        <div class="subhead"><span class="dot"></span>3.7 Parent / Guardian <span class="optional">(optional)</span></div>
        <div class="row">
          <div class="field wide"><label>Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name', $step1['guardian_name'] ?? '') }}"></div>
        </div>
        <div class="row">
          <div class="field wide"><label>Complete Permanent Mailing Address</label><input type="text" name="guardian_address" value="{{ old('guardian_address', $step1['guardian_address'] ?? '') }}"></div>
        </div>
      </div>

      <div class="footer-note">
        <span><span class="req">*</span> Required fields. Please review all entries before proceeding. Fields marked with a section number correspond to the official TESDA Registration Form (MIS 03-01).</span>
      </div>
      <div class="footer-note" style="padding-top:0;">
        <button type="submit" class="btn">Next Page &rarr;</button>
      </div>

    </form>
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
     MODALS: PRIVACY POLICY, TERMS OF USE, FAQ & LOGOUT
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
        
        <div class="policy-agreement-box">
          <input type="checkbox" id="privacyAgreeCheck" onchange="toggleAcceptButton('privacyModal')">
          <label for="privacyAgreeCheck">I have read, fully understand, and agree to the Privacy Policy terms and data collection practices.</label>
        </div>
      </div>
      <div class="policy-modal-footer">
        <button class="policy-btn-download" id="privacyDownloadBtn" onclick="downloadPolicyText('privacyModal')">
          <i class="fas fa-download"></i> Download Policy
        </button>
        <div style="display:flex; gap:10px;">
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
        
        <div class="policy-agreement-box">
          <input type="checkbox" id="termsAgreeCheck" onchange="toggleAcceptButton('termsModal')">
          <label for="termsAgreeCheck">I have read, fully understand, and agree to abide by these Terms of Use.</label>
        </div>
      </div>
      <div class="policy-modal-footer">
        <button class="policy-btn-download" id="termsDownloadBtn" onclick="downloadPolicyText('termsModal')">
          <i class="fas fa-download"></i> Download Terms
        </button>
        <div style="display:flex; gap:10px;">
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
        <button class="policy-btn-download" id="faqDownloadBtn" onclick="downloadPolicyText('faqModal')">
          <i class="fas fa-download"></i> Download FAQ
        </button>
        <button class="policy-btn-accept unlocked" id="faqAcceptBtn" onclick="closePolicyModal('faqModal')">Close</button>
      </div>
    </div>
  </div>

  <!-- LOGOUT CONFIRMATION MODAL -->
  <div class="logout-modal-overlay" id="logoutConfirmModal">
    <div class="logout-modal-box">
      <i class="fas fa-triangle-exclamation" style="font-size: 45px; color: #1a4d2e; margin-bottom: 15px;"></i>
      <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Confirm Logout</h3>
      <p style="font-size: 13px; color: #4b5563; margin-bottom: 24px;">Are you sure you want to log out of your account?</p>
      <div class="logout-modal-actions">
        <button type="button" class="btn-logout-yes" onclick="executeLogout()">Yes</button>
        <button type="button" class="btn-logout-no" onclick="closeLogoutModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    // ID Photo Preview & Validation
    const photoInput = document.getElementById('idPhotoInput');
    const photoPreview = document.getElementById('idPhotoPreview');
    const photoText = document.getElementById('idPhotoText');
    photoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = (ev) => {
        photoPreview.src = ev.target.result;
        photoPreview.style.display = 'block';
        photoText.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });

    const form = document.getElementById('learnerForm');
    form.addEventListener('submit', (e) => {
      if (!photoInput.files || photoInput.files.length === 0) {
        e.preventDefault();
        alert('Please upload an ID picture before proceeding.');
        document.querySelector('.id-photo').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }

      if (!form.checkValidity()) {
        e.preventDefault();
        form.reportValidity();
      }
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

    // Logout Modal Logic
    function openLogoutModal() {
      document.getElementById('logoutConfirmModal').style.display = 'flex';
    }

    function closeLogoutModal() {
      document.getElementById('logoutConfirmModal').style.display = 'none';
    }

    function executeLogout() {
      document.getElementById('landing-logout-form').submit();
    }

    // Enforced Policy Reading & Checkbox Modal Logic
    const modalStates = { privacyModal: false, termsModal: false, faqModal: false };
    const modalScrolledToBottom = { privacyModal: false, termsModal: false, faqModal: false };

    function openPolicyModal(modalId) {
      document.getElementById(modalId).style.display = 'flex';
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
      const docName = modalId === 'privacyModal' ? 'Privacy Policy' : 'Terms of Use';
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
      const body = document.getElementById(modalId.replace('Modal', 'Body'));
      if (body.scrollTop + body.clientHeight >= body.scrollHeight - 15) {
        modalScrolledToBottom[modalId] = true;
        checkIfReadyToUnlock(modalId);
      }
    }

    function toggleAcceptButton(modalId) {
      checkIfReadyToUnlock(modalId);
    }

    function checkIfReadyToUnlock(modalId) {
      const isScrolled = modalScrolledToBottom[modalId];
      let isChecked = modalId === 'faqModal' || document.getElementById(modalId.replace('Modal', 'AgreeCheck')).checked;

      if (isScrolled && isChecked) {
        modalStates[modalId] = true;
        const prefix = modalId.replace('Modal', '');
        document.getElementById(prefix + 'DownloadBtn').className = 'policy-btn-download unlocked';
        document.getElementById(prefix + 'AcceptBtn').className = 'policy-btn-accept unlocked';
      }
    }

    function downloadPolicyText(modalId) {
      if (!modalStates[modalId] && modalId !== 'faqModal') {
        alert('Please finish reading and check the agreement box before downloading.');
        return;
      }
      const prefix = modalId.replace('Modal', '');
      const body = document.getElementById(prefix + 'Body');
      const title = document.querySelector('#' + modalId + ' .policy-modal-header h2').innerText;
      const blob = new Blob([title + "\n\n" + body.innerText], { type: 'text/plain;charset=utf-8' });
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

    window.addEventListener('click', function(event) {
      ['privacyModal', 'termsModal', 'faqModal', 'logoutConfirmModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) modal.style.display = 'none';
      });
    });
  </script>
</body>

</html>