<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dasmariñas Livelihood Training</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('stylesheet/landingpage.css') }}">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo.png') }}">

  <style>
    /* =========================================================
       LOGGED-IN PROFILE AVATAR / DROPDOWN
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
      color:  #c9bf03;
    }

    /* =========================================================
       MOBILE PROFILE DROPDOWN FIX
       Keep the avatar above the mobile navigation and prevent the
       dropdown from being clipped by the navbar or its containers.
       ========================================================= */
    .main-nav {
      position: relative;
      z-index: 10000;
      overflow: visible !important;
    }

    .nav-actions {
      position: relative;
      z-index: 10002;
      flex-shrink: 0;
      overflow: visible !important;
    }

    .landing-profile {
      position: relative;
      z-index: 10003;
    }

    .landing-profile-dropdown {
      z-index: 10010 !important;
      max-width: calc(100vw - 24px);
    }

    @media (max-width: 768px) {
      .main-nav,
      .nav-actions,
      .landing-profile {
        overflow: visible !important;
      }

      .landing-profile-dropdown {
        position: fixed !important;
        top: 72px;
        right: 12px !important;
        left: auto !important;
        width: min(250px, calc(100vw - 24px));
        max-height: calc(100dvh - 90px);
        overflow-y: auto;
        z-index: 2147483000 !important;
      }

      .landing-profile-dropdown.open {
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
      }

      .landing-avatar-btn {
        position: relative;
        z-index: 10004;
        touch-action: manipulation;
      }
    }

    /* =========================================================
       SCHEDULE & REQUIREMENTS SECTION
       ========================================================= */
    .schedule-wrapper-section {
      background: #1a4d2e;
      padding: 30px 20px 20px;
      margin: 0;
    }

    .schedule-table-card {
      background: #1a4d2e;
      border-radius: 16px;
      padding: 20px;
      color: #fff;
      margin: 0 auto;
      max-width: 1100px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border: 2px solid #276749;
    }

    .schedule-table-card h3 {
      text-align: center;
      color: #f5c842;
      font-size: 16px;
      font-weight: 800;
      margin: 0 0 16px;
      text-transform: uppercase;
    }

    .schedule-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      background: #143c24;
      border-radius: 8px;
      overflow: hidden;
    }

    .schedule-table th,
    .schedule-table td {
      padding: 10px 12px;
      border: 1px solid rgba(255, 255, 255, 0.15);
      text-align: left;
    }

    .schedule-table th {
      background: #0f2d1e;
      color: #f5c842;
      text-transform: uppercase;
    }

    .requirements-box {
      margin-top: 16px;
      background: #0f2d1e;
      padding: 16px;
      border-radius: 8px;
      font-size: 12.5px;
      line-height: 1.5;
    }

    /* =========================================================
       INSTRUCTORS & MODALS
       ========================================================= */
    .instructors-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      position: relative;
      margin-top: 20px;
      max-width: 900px;
      margin-left: auto;
      margin-right: auto;
    }

    .instructor-nav-btn {
      background: #ffffff;
      border: 2px solid #1a4d2e;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      font-size: 18px;
      color: #1a4d2e;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.2s ease;
      flex-shrink: 0;
      z-index: 10;
    }

    .instructor-nav-btn:hover {
      background: #1a4d2e;
      color: #ffffff;
      transform: scale(1.1);
    }

    .instructors-track {
      display: flex;
      gap: 32px;
      overflow-x: auto;
      scroll-behavior: smooth;
      scrollbar-width: none;
      padding: 10px;
      width: 100%;
      justify-content: flex-start;
    }

    .instructors-track::-webkit-scrollbar {
      display: none;
    }

    .instructor-card {
      text-align: center;
      cursor: pointer;
      flex: 0 0 240px;
      max-width: 240px;
      background: #ffffff;
      padding: 15px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      border: 1px solid #e2e8f0;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .instructor-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .instructor-card img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1a4d2e;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin: 0 auto;
    }

    .instructor-modal,
    .video-modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);
      align-items: center;
      justify-content: center;
    }

    .instructor-modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      position: relative;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .close-modal {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 20px;
      cursor: pointer;
      color: #718096;
    }

    .modal-circle-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1a4d2e;
      margin: 0 auto 12px auto;
      display: block;
    }

    .modal-cert-list {
      text-align: left;
      padding-left: 20px;
      margin: 10px 0;
      font-size: 13px;
      color: #4a5568;
    }

    /* Course Image Cover Styling */
    .course-thumbnail {
      width: 100%;
      height: 180px;
      overflow: hidden;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      background: #e2e8f0;
      position: relative;
    }

    .course-thumbnail img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .course-card:hover .course-thumbnail img {
      transform: scale(1.05);
    }

    .course-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: #1a4d2e;
      color: #f5c842;
      font-size: 14px;
      font-weight: 600;
      gap: 8px;
    }

    .course-placeholder i {
      font-size: 28px;
    }

    /* =========================================================
       CUSTOM MATCHED FOOTER STYLING (PERFECTLY BALANCED SIDE SPACING)
       ========================================================= */
    .footer {
      background: #06381e;
      color: #ffffff;
      font-family: 'Poppins', sans-serif;
      padding: 50px 0 25px 0;
      width: 100%;
      box-sizing: border-box;
    }

    .footer-content {
      display: grid;
      grid-template-columns: 1.4fr 1fr 1fr 1fr;
      gap: 50px;
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

    /* =========================================================
       THEME-MATCHED ENFORCED READ MODAL STYLING
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

    /* Agreement Checkbox Box */
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

    .policy-footer-left-group {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .policy-footer-right-group {
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

    /* =========================================================
       CUSTOM TOAST NOTIFICATION & LOGOUT MODAL STYLES
       ========================================================= */
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
      animation: modalPopIn 0.3s ease forwards;
    }

    .logout-modal-box i {
      font-size: 45px;
      color: #1a4d2e;
      margin-bottom: 15px;
    }

    .logout-modal-box h3 {
      font-size: 18px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 8px;
    }

    .logout-modal-box p {
      font-size: 13px;
      color: #4b5563;
      margin-bottom: 24px;
    }

    .logout-modal-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
    }

    .btn-logout-yes {
      background: #1a4d2e;
      color: #ffffff;
      border: none;
      padding: 10px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-logout-yes:hover {
      background: #c9bf03;
      color: #1a4d2e;
    }

    .btn-logout-no {
      background: #e2e8f0;
      color: #334155;
      border: none;
      padding: 10px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-logout-no:hover {
      background: #cbd5e1;
    }
    

    @media (max-width: 992px) {
      .footer-content {
        grid-template-columns: 1fr 1fr;
        gap: 25px;
      }
    }

    @media (max-width: 768px) {
      .landing-profile-dropdown {
        right: -5px;
      }

      .landing-avatar-btn {
        width: 42px;
        height: 42px;
        font-size: 13px;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 0 20px;
      }

      .footer-bottom {
        padding: 20px 20px 0 20px;
      }
    }

    
/* ==========================================
   CONTACT SECTION - RESPONSIVE, POLISHED LAYOUT
========================================== */
.contact-section,
.contact-section * {
    box-sizing: border-box;
}

.contact-section {
    --contact-green: #075b35;
    --contact-deep-green: #06381e;
    --contact-yellow: #e1dc36;
    --contact-muted: #64748b;
    width: 100%;
    padding: clamp(42px, 6vw, 84px) clamp(14px, 3vw, 28px);
    background: #f6f8f6;
    overflow: clip;
}

.contact-page-wrapper {
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
}

.contact-section-heading {
    max-width: 720px;
    margin: 0 auto 38px;
    text-align: center;
}

.contact-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 12px;
    color: var(--contact-green);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.contact-eyebrow::before {
    content: "";
    display: inline-block;
    width: 26px;
    height: 3px;
    border-radius: 99px;
    background: var(--contact-yellow);
}

.contact-section-heading h2 {
    margin: 0 0 12px;
    color: #123524;
    font-size: clamp(28px, 3.4vw, 42px);
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -0.8px;
}

.contact-section-heading p {
    max-width: 600px;
    margin: 0 auto;
    color: #64748b;
    font-size: 15px;
    line-height: 1.75;
}

.contact-container {
    display: grid;
    grid-template-columns: minmax(0, .82fr) minmax(0, 1.18fr);
    gap: clamp(22px, 3.5vw, 42px);
    align-items: stretch;
    width: 100%;
}

.contact-info-list,
.contact-form-card {
    min-width: 0;
    width: 100%;
}

.contact-info-list {
    padding: clamp(22px, 3vw, 34px);
    color: #fff;
    background: var(--contact-deep-green);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 22px;
    box-shadow: 0 14px 34px rgba(6, 56, 30, .12);
}

.contact-info-heading {
    margin: 0 0 8px;
    color: #fff;
    font-size: clamp(20px, 2vw, 25px);
    line-height: 1.3;
    font-weight: 800;
}

.contact-info-intro {
    margin: 0 0 24px;
    color: #d7e6dc;
    font-size: 13px;
    line-height: 1.7;
}

.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-width: 0;
    padding: 17px 0;
}

.contact-icon-wrapper {
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--contact-deep-green);
    background: var(--contact-yellow);
    border-radius: 13px;
    font-size: 16px;
}

.contact-info-item > div:last-child {
    flex: 1;
    min-width: 0;
}

.contact-info-title {
    margin: 0 0 6px;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.contact-info-subtitle,
.contact-info-detail {
    overflow-wrap: anywhere;
    word-break: normal;
    line-height: 1.65;
}

.contact-info-subtitle {
    margin: 0 0 6px;
    color: #c6d8cc;
    font-size: 12.5px;
}

.contact-info-detail {
    margin: 3px 0;
    color: #fff;
    font-size: 13px;
}

.contact-info-detail a {
    color: #fff;
    text-decoration: underline;
    text-decoration-color: rgba(255,255,255,.45);
    text-underline-offset: 3px;
}

.contact-info-detail a:hover {
    color: var(--contact-yellow);
}

.contact-divider {
    width: 100%;
    margin: 0;
    border: 0;
    border-top: 1px solid rgba(255,255,255,.15);
}

.contact-form-card {
    padding: clamp(22px, 3.5vw, 40px);
    background: #fff;
    border: 1px solid #e4ebe6;
    border-radius: 22px;
    box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
}

.contact-form-heading {
    margin: 0 0 8px;
    color: #123524;
    font-size: clamp(21px, 2.2vw, 27px);
    line-height: 1.3;
    font-weight: 800;
}

.contact-form-description {
    margin: 0 0 25px;
    color: var(--contact-muted);
    font-size: 13px;
    line-height: 1.7;
}

.contact-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 16px;
    width: 100%;
}

.contact-form-grid > div {
    min-width: 0;
}

.contact-form-grid > div:nth-child(3) {
    grid-column: 1 / -1;
}

.contact-field-label {
    display: block;
    margin: 0 0 8px;
    color: #263b30;
    font-size: 12px;
    font-weight: 700;
}

.contact-form-input,
.contact-form-textarea {
    display: block;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    padding: 13px 14px;
    color: #172b20;
    background: #fbfcfb;
    border: 1px solid #d8e1db;
    border-radius: 10px;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    line-height: 1.5;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.contact-form-input {
    min-height: 50px;
}

.contact-form-input::placeholder,
.contact-form-textarea::placeholder {
    color: #89968e;
    opacity: 1;
}

.contact-form-input:hover,
.contact-form-textarea:hover {
    border-color: #a9bdb0;
}

.contact-form-input:focus,
.contact-form-textarea:focus {
    background: #fff;
    border-color: var(--contact-green);
    box-shadow: 0 0 0 4px rgba(7, 91, 53, .10);
}

.contact-form-textarea-wrapper {
    width: 100%;
    margin-top: 18px;
}

.contact-form-textarea {
    min-height: 155px;
    resize: vertical;
}

.contact-form-submit-wrapper {
    display: flex;
    justify-content: flex-end;
    width: 100%;
    margin-top: 22px;
}

.btn-submit-contact {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    max-width: 100%;
    min-height: 50px;
    padding: 13px 24px;
    color: #fff;
    background: var(--contact-green);
    border: 2px solid var(--contact-green);
    border-radius: 10px;
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.3;
    transition: transform .2s ease, background .2s ease, border-color .2s ease, box-shadow .2s ease;
}

.btn-submit-contact:hover {
    background: var(--contact-deep-green);
    border-color: var(--contact-deep-green);
    box-shadow: 0 8px 18px rgba(7, 91, 53, .18);
    transform: translateY(-1px);
}

.btn-submit-contact:focus-visible {
    outline: 3px solid rgba(225, 220, 54, .8);
    outline-offset: 3px;
}

.alertSuccess {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    width: 100%;
    margin-bottom: 24px;
    padding: 15px 18px;
    color: #14532d;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #16a34a;
    border-radius: 12px;
    overflow-wrap: anywhere;
    font-size: 14px;
    line-height: 1.6;
}

/* Tablets and compact laptops */
@media (max-width: 900px) {
    .contact-container {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
        gap: 20px;
    }

    .contact-info-list,
    .contact-form-card {
        padding: 24px;
        border-radius: 18px;
    }

    .contact-form-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 15px;
    }

    .contact-form-grid > div:nth-child(3) {
        grid-column: auto;
    }
}

/* Phones and narrow tablets */
@media (max-width: 680px) {
    .contact-section {
        padding: 42px 16px;
    }

    .contact-section-heading {
        margin-bottom: 26px;
        text-align: left;
    }

    .contact-section-heading p {
        margin-left: 0;
        font-size: 14px;
    }

    .contact-container {
        grid-template-columns: minmax(0, 1fr);
        gap: 18px;
    }

    .contact-info-list {
        padding: 23px 21px;
    }

    .contact-info-intro {
        margin-bottom: 14px;
    }

    .contact-info-item {
        gap: 12px;
        padding: 15px 0;
    }

    .contact-icon-wrapper {
        flex-basis: 42px;
        width: 42px;
        height: 42px;
        border-radius: 12px;
    }

    .contact-form-card {
        padding: 24px 21px;
    }

    .contact-form-description {
        margin-bottom: 20px;
    }

    .contact-form-input,
    .contact-form-textarea {
        font-size: 16px; /* Prevents automatic zoom on iOS. */
    }

    .contact-form-textarea {
        min-height: 145px;
    }

    .contact-form-submit-wrapper {
        margin-top: 18px;
    }

    .btn-submit-contact {
        width: 100%;
        min-height: 50px;
    }
}

/* Small phones */
@media (max-width: 380px) {
    .contact-section {
        padding: 32px 12px;
    }

    .contact-section-heading h2 {
        font-size: 27px;
    }

    .contact-info-list,
    .contact-form-card {
        padding: 19px 16px;
        border-radius: 15px;
    }

    .contact-info-item {
        gap: 10px;
    }

    .contact-icon-wrapper {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 14px;
    }

    .contact-info-title {
        font-size: 14px;
    }

    .contact-info-subtitle,
    .contact-info-detail {
        font-size: 12px;
    }

    .contact-form-input,
    .contact-form-textarea {
        padding: 12px;
    }
}

/* ==========================================
   COMMUNITY CAROUSEL - RESPONSIVE
========================================== */

#communityCarousel {
    width: 100%;
    touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
    max-width: 1400px;
    margin: 30px auto 0 !important;
    padding: 0 50px !important;
    box-sizing: border-box;
}

#communityCarousel *,
.about-story * {
    box-sizing: border-box;
}

#communityCarousel .carousel-inner {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
}

#communityCarousel .carousel-item img {
    display: block;
    -webkit-user-drag: none;
    user-select: none;
    width: 100%;
    height: 450px;
    object-fit: cover;
    object-position: center;
}

#communityCarousel .carousel-indicators {
    margin-bottom: 16px;
}

#communityCarousel .carousel-caption {
    max-width: 85%;
    left: 50%;
    right: auto;
    bottom: 20px;
    transform: translateX(-50%);
    width: max-content;
    min-width: 40%;
    max-width: 85%;
    padding: 12px 20px;
    background: rgba(0, 0, 0, 0.58);
    border-radius: 8px;
    overflow-wrap: anywhere;
}

#communityCarousel .carousel-caption h5 {
    margin-bottom: 6px;
    font-weight: 700;
}

#communityCarousel .carousel-caption p {
    margin-bottom: 0;
    line-height: 1.5;
}

#communityCarousel .carousel-control-prev,
#communityCarousel .carousel-control-next {
    width: 50px;
}

#communityCarousel .carousel-control-prev {
    left: 0;
}

#communityCarousel .carousel-control-next {
    right: 0;
}

/* Carousel heading */
.community-carousel-title {
    max-width: 100%;
    padding: 0 16px;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

/* Empty carousel state */
#communityCarousel .carousel-item > div[style*="height: 450px"] {
    min-height: 200px;
    padding: 20px;
    text-align: center;
}

/* ==========================================
   ANNOUNCEMENT / ABOUT STORY
========================================== */

.about-story {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    align-items: center;
    gap: 30px;
    width: 100%;
    max-width: 1200px;
    margin: 20px auto 0;
    padding: 30px;
    overflow: hidden;
}

.about-story > div {
    min-width: 0;
}

.about-story h2 {
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.about-story p {
    line-height: 1.8;
    overflow-wrap: anywhere;
}

.about-story img {
    display: block;
    width: 100%;
    max-width: 100%;
    height: auto;
    max-height: 420px;
    object-fit: cover;
    border-radius: 12px;
}

/* ==========================================
   TABLET - 768px to 1024px
========================================== */

@media (max-width: 1024px) {
    #communityCarousel {
        padding: 0 35px !important;
    }

    #communityCarousel .carousel-item img {
        height: 360px !important;
    }

    #communityCarousel .carousel-item > div[style*="height: 450px"] {
        height: 360px !important;
    }

    #communityCarousel .carousel-control-prev,
    #communityCarousel .carousel-control-next {
        width: 40px;
    }

    .about-story {
        gap: 24px;
        padding: 24px;
    }

    .about-story img {
        max-height: 350px;
    }
}

/* ==========================================
   MOBILE - 767px and below
========================================== */

@media (max-width: 767px) {
    #communityCarousel {
        padding: 0 28px !important;
        margin-top: 20px !important;
    }

    #communityCarousel .carousel-item img {
        height: 280px !important;
        object-fit: cover;
    }

    #communityCarousel .carousel-item > div[style*="height: 450px"] {
        height: 280px !important;
    }

    #communityCarousel .carousel-inner {
        border-radius: 10px;
    }

    #communityCarousel .carousel-indicators {
        margin-bottom: 10px;
    }

    #communityCarousel .carousel-control-prev,
    #communityCarousel .carousel-control-next {
        width: 30px;
    }

    #communityCarousel .carousel-caption {
        bottom: 10px;
        min-width: 0;
        width: 85%;
        max-width: 85%;
        padding: 8px 10px;
    }

    #communityCarousel .carousel-caption h5 {
        font-size: 15px;
    }

    #communityCarousel .carousel-caption p {
        font-size: 12px;
    }

    .community-carousel-title {
        margin: 28px 0 12px !important;
        font-size: 21px;
        padding: 0 12px;
    }

    .about-story {
        grid-template-columns: minmax(0, 1fr);
        gap: 20px;
        padding: 22px 18px;
        margin-top: 18px !important;
    }

    .about-story > div:first-child {
        order: 1;
    }

    .about-story > div:last-child {
        order: 2;
    }

    .about-story h2 {
        font-size: 23px;
        margin-bottom: 12px;
    }

    .about-story p {
        font-size: 14px;
        line-height: 1.7;
    }

    .about-story img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
}

/* ==========================================
   SMALL PHONES - 480px and below
========================================== */

@media (max-width: 480px) {
    #communityCarousel {
        padding: 0 22px !important;
    }

    #communityCarousel .carousel-item img {
        height: 220px !important;
    }

    #communityCarousel .carousel-item > div[style*="height: 450px"] {
        height: 220px !important;
    }

    #communityCarousel .carousel-control-prev,
    #communityCarousel .carousel-control-next {
        width: 24px;
    }

    #communityCarousel .carousel-caption {
        bottom: 8px;
        padding: 6px 8px;
    }

    #communityCarousel .carousel-caption h5 {
        font-size: 13px;
        margin-bottom: 3px;
    }

    #communityCarousel .carousel-caption p {
        font-size: 11px;
        line-height: 1.3;
    }

    .community-carousel-title {
        font-size: 18px;
        line-height: 1.4;
    }

    .about-story {
        padding: 18px 14px;
        gap: 16px;
    }

    .about-story h2 {
        font-size: 20px;
    }

    .about-story p {
        font-size: 13px;
    }

    .about-story img {
        max-height: 230px;
    }
}


  

    /* FINAL MOBILE HEADER SPACING: logo stays left, avatar stays right */
    @media (max-width: 768px) {
      .main-nav {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        box-sizing: border-box !important;
        width: 100% !important;
        padding-left: 24px !important;
        padding-right: 24px !important;
        position: relative !important;
        overflow: visible !important;
      }

      .main-nav .nav-logo {
        margin-left: 0 !important;
        margin-right: auto !important;
        flex: 0 1 auto !important;
        min-width: 0 !important;
      }

      .main-nav .nav-actions {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: static !important;
        width: auto !important;
        height: auto !important;
        margin-left: 16px !important;
        margin-right: 0 !important;
        flex: 0 0 auto !important;
        overflow: visible !important;
        z-index: 2147483000 !important;
      }

      .main-nav .landing-profile {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        overflow: visible !important;
      }

      .main-nav .landing-avatar-btn {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 38px !important;
        height: 38px !important;
        min-width: 38px !important;
        font-size: 12px !important;
      }

      /* Hide the hamburger button on mobile; keep the profile avatar visible. */
      .main-nav .hamburger {
        display: none !important;
      }

      .main-nav .landing-profile-dropdown {
        position: fixed !important;
        top: 78px !important;
        right: 12px !important;
        left: auto !important;
        width: min(250px, calc(100vw - 24px)) !important;
        max-height: calc(100dvh - 94px) !important;
        overflow-y: auto !important;
        z-index: 2147483647 !important;
      }

      .main-nav .landing-profile-dropdown.open {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
        transform: translateY(0) !important;
      }
    }


    /* Authentication links are duplicated inside the collapsed menu for touch layouts. */
    .main-nav .mobile-auth-links {
      display: none;
    }

    .main-nav .mobile-auth-link {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      color: #ffffff;
      text-decoration: none;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 600;
      line-height: 1.4;
      box-sizing: border-box;
      transition: background-color .2s ease, color .2s ease;
    }

    .main-nav .mobile-auth-link i {
      width: 18px;
      text-align: center;
    }

    .main-nav .mobile-auth-login {
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .28);
    }

    .main-nav .mobile-auth-signup {
      margin-top: 4px;
      background: #fff200;
      color: #06381e;
      border: 1px solid #fff200;
    }

    .main-nav .mobile-auth-link:hover,
    .main-nav .mobile-auth-link:focus-visible {
      background: #e1dc36;
      color: #06381e;
      text-decoration: none;
      outline: none;
    }

    @media (max-width: 768px) {
      /* On phones and tablets, show Login and Sign Up only in the hamburger menu. */
      .main-nav .nav-actions > .btn-signin,
      .main-nav .nav-actions > .btn-signup {
        display: none !important;
      }

      .main-nav .nav-links .mobile-auth-links {
        display: block !important;
        width: 100%;
        padding: 4px 0;
        border-top: 1px solid rgba(255, 255, 255, .12);
      }

      .main-nav .nav-links .mobile-auth-links:first-of-type {
        margin-top: 6px;
      }

      .main-nav .nav-links .mobile-auth-links a {
        display: flex !important;
        width: 100%;
        min-height: 44px;
        box-sizing: border-box;
      }
    }

    /* =========================================================
       FINAL RESPONSIVE SAFETY LAYER
       Added without removing existing styles, content, or scripts.
       ========================================================= */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html {
      width: 100%;
      max-width: 100%;
      scroll-behavior: smooth;
      scroll-padding-top: 84px;
      -webkit-text-size-adjust: 100%;
    }

    body {
      width: 100%;
      max-width: 100%;
      margin: 0;
      overflow-x: clip;
    }

    img,
    video,
    iframe,
    svg {
      max-width: 100%;
    }

    img {
      height: auto;
    }

    button,
    input,
    textarea,
    select {
      max-width: 100%;
      font: inherit;
    }

    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    a,
    td,
    th,
    label,
    span {
      overflow-wrap: break-word;
    }

    section[id],
    div[id="schedule"] {
      scroll-margin-top: 84px;
    }

    .main-nav {
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
    }

    .main-nav .nav-logo,
    .main-nav .nav-actions,
    .main-nav .nav-links,
    .hero-content,
    .courses-wrapper,
    .courses-grid,
    .course-card,
    .course-body,
    .course-meta,
    .footer-content,
    .footer-col,
    .contact-container,
    .contact-info-list,
    .contact-container > div {
      min-width: 0;
    }

    .main-nav .nav-logo img,
    .footer-brand-top img,
    .footer-badges img {
      flex-shrink: 0;
    }

    .schedule-wrapper-section {
      padding-left: clamp(12px, 3vw, 24px);
      padding-right: clamp(12px, 3vw, 24px);
    }

    .schedule-table-card {
      width: 100%;
      min-width: 0;
      padding: clamp(12px, 2.5vw, 20px);
    }

    /* Keep every schedule column available on narrow screens. */
    .schedule-table-card {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .schedule-table {
      width: 100%;
      min-width: 560px;
    }

    .schedule-table th,
    .schedule-table td {
      vertical-align: top;
      overflow-wrap: anywhere;
    }

    .instructors-container {
      width: 100%;
      min-width: 0;
      gap: clamp(6px, 2vw, 20px);
    }

    .instructors-track {
      min-width: 0;
      overscroll-behavior-x: contain;
      -webkit-overflow-scrolling: touch;
      scroll-snap-type: x proximity;
    }

    .instructor-card {
      scroll-snap-align: center;
    }

    .contact-section {
      overflow: visible;
    }

    .contact-form-grid,
    .contact-form-grid > div,
    .contact-form-textarea-wrapper {
      min-width: 0;
    }

    .policy-modal-overlay,
    .logout-modal-overlay {
      padding: max(10px, env(safe-area-inset-top)) 12px max(10px, env(safe-area-inset-bottom));
      overflow-y: auto;
      overscroll-behavior: contain;
    }

    .policy-modal-container,
    .logout-modal-box,
    .instructor-modal-content {
      width: 100%;
      min-width: 0;
      max-height: calc(100dvh - 24px);
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
    }

    .policy-modal-header,
    .policy-modal-footer,
    .policy-footer-left-group,
    .policy-footer-right-group,
    .logout-modal-actions {
      flex-wrap: wrap;
    }

    .policy-modal-header h2 {
      min-width: 0;
      overflow-wrap: anywhere;
    }

    .custom-toast {
      left: max(12px, env(safe-area-inset-left));
      right: max(12px, env(safe-area-inset-right));
      bottom: max(12px, env(safe-area-inset-bottom));
      width: auto;
      max-width: 560px;
      margin-left: auto;
      padding: 12px 16px;
      overflow-wrap: anywhere;
    }

    @media (max-width: 1024px) {
      .footer-content {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px;
        padding-left: 24px;
        padding-right: 24px;
      }

      .contact-container {
        gap: 24px;
      }

      .about-story {
        margin-left: auto;
        margin-right: auto;
      }

      .hero-content {
        width: 100%;
        max-width: 100%;
      }
    }

    @media (max-width: 768px) {
      html {
        scroll-padding-top: 72px;
      }

      .main-nav {
        gap: 10px;
        padding-left: 14px !important;
        padding-right: 14px !important;
      }

      .main-nav .nav-logo {
        gap: 8px;
        max-width: calc(100% - 100px);
      }

      .main-nav .nav-logo span {
        font-size: clamp(12px, 3.2vw, 16px);
      }

      .main-nav .nav-logo .logo {
        width: clamp(32px, 8vw, 44px);
        height: auto;
      }

      /* Keep the mobile navigation accessible instead of hiding its toggle. */
      .main-nav .hamburger {
        display: flex !important;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 2147483001;
        touch-action: manipulation;
      }

      .main-nav .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 10px;
        right: 10px;
        width: auto;
        max-height: calc(100dvh - 100px);
        overflow-y: auto;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 8px !important;
        margin: 0 !important;
        background: #06381e;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0,0,0,.22);
        z-index: 2147483000;
      }

      .main-nav .nav-links.active {
        display: flex !important;
      }

      .main-nav .nav-links li {
        width: 100%;
        list-style: none;
      }

      .main-nav .nav-links a {
        display: block;
        width: 100%;
        padding: 12px 14px;
        line-height: 1.4;
      }

      .main-nav .nav-actions {
        margin-left: auto !important;
        gap: 8px;
      }

      .main-nav .nav-actions .btn-signin,
      .main-nav .nav-actions .btn-signup {
        padding: 8px 10px;
        font-size: 12px;
        white-space: nowrap;
      }

      .hero {
        min-height: 0;
        padding: 64px 18px 44px;
        background-position: center;
      }

      .hero-content {
        padding-left: 0;
        padding-right: 0;
      }

      .hero-content h1 {
        font-size: clamp(30px, 6.5vw, 46px);
        line-height: 1.2;
        overflow-wrap: anywhere;
      }

      .hero-content > p {
        font-size: clamp(14px, 2.2vw, 16px);
        line-height: 1.7;
      }

      .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }

      .hero-actions a {
        max-width: 100%;
      }

      .hero-stats {
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
      }

      .hero-stats .stat-divider {
        display: none;
      }

      .schedule-table-card h3 {
        font-size: clamp(14px, 3vw, 16px);
        line-height: 1.5;
      }

      .instructor-nav-btn {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
      }

      .footer-content {
        grid-template-columns: minmax(0, 1fr);
        padding-left: 20px;
        padding-right: 20px;
        gap: 24px;
      }

      .footer-partnership {
        flex-wrap: wrap;
      }

      .footer-bottom {
        padding-left: 18px;
        padding-right: 18px;
      }

      .policy-modal-container {
        max-height: calc(100dvh - 20px);
        border-width: 2px;
      }

      .policy-modal-header {
        padding: 14px 16px;
      }

      .policy-modal-header h2 {
        font-size: 16px;
      }

      .policy-modal-body {
        padding: 16px;
        font-size: 13px;
      }

      .policy-modal-footer {
        padding: 12px 14px;
        align-items: stretch;
      }

      .policy-footer-left-group,
      .policy-footer-right-group {
        width: 100%;
      }

      .policy-footer-left-group > *,
      .policy-footer-right-group > * {
        flex: 1 1 auto;
        justify-content: center;
      }

      .logout-modal-box {
        padding: 22px 18px;
      }
    }

    @media (max-width: 480px) {
      .main-nav {
        padding-left: 10px !important;
        padding-right: 10px !important;
      }

      .main-nav .nav-logo {
        gap: 5px;
        max-width: calc(100% - 88px);
      }

      .main-nav .nav-logo span {
        font-size: 11px;
      }

      .main-nav .nav-logo .logo {
        width: 30px;
      }

      .main-nav .nav-actions {
        margin-left: auto !important;
      }

      .main-nav .landing-avatar-btn {
        width: 34px !important;
        height: 34px !important;
        min-width: 34px !important;
        font-size: 11px !important;
      }

      .main-nav .landing-profile-dropdown {
        top: 70px !important;
        right: 8px !important;
        width: min(250px, calc(100vw - 16px)) !important;
      }

      .hero {
        padding: 48px 14px 34px;
      }

      .hero-content h1 {
        font-size: clamp(27px, 8vw, 34px);
      }

      .hero-badge {
        max-width: 100%;
        font-size: 11px;
        line-height: 1.5;
      }

      .hero-actions {
        flex-direction: column;
        align-items: stretch;
      }

      .hero-actions a {
        width: 100%;
        text-align: center;
      }

      .schedule-wrapper-section {
        padding: 18px 10px 14px;
      }

      .schedule-table-card {
        padding: 12px;
        border-radius: 12px;
      }

      .schedule-table {
        min-width: 520px;
        font-size: 11px;
      }

      .schedule-table th,
      .schedule-table td {
        padding: 8px;
      }

      .instructors-container {
        gap: 4px;
      }

      .instructor-nav-btn {
        width: 30px;
        height: 30px;
        flex-basis: 30px;
        font-size: 14px;
      }

      .instructor-card {
        flex-basis: 210px;
        max-width: 210px;
      }

      .contact-info-item {
        align-items: flex-start;
      }

      .footer-brand-top {
        align-items: flex-start;
      }

      .footer-badges {
        flex-wrap: wrap;
      }

      .policy-modal-overlay,
      .logout-modal-overlay {
        padding-left: 8px;
        padding-right: 8px;
      }

      .policy-btn-download,
      .policy-btn-decline,
      .policy-btn-accept,
      .btn-logout-yes,
      .btn-logout-no {
        padding: 10px 12px;
        font-size: 12px;
      }

      .logout-modal-actions {
        flex-wrap: wrap;
      }

      .logout-modal-actions button {
        flex: 1 1 110px;
      }
    }

    @media (max-width: 360px) {
      .main-nav .nav-logo span {
        display: none;
      }

      .main-nav .nav-actions .btn-signin,
      .main-nav .nav-actions .btn-signup {
        padding: 7px 8px;
        font-size: 11px;
      }

      .contact-section {
        padding-left: 10px;
        padding-right: 10px;
      }

      .contact-icon-wrapper {
        flex-basis: 32px;
        width: 32px;
        height: 32px;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      html {
        scroll-behavior: auto;
      }

      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
      }
    }


    /* =========================================================
       MOBILE RIGHT-SIDE WHITESPACE / HORIZONTAL OVERFLOW FIX
       Keeps intentional inner carousels and schedule scrolling usable.
       ========================================================= */
    html,
    body {
      width: 100%;
      max-width: 100%;
      margin-left: 0;
      margin-right: 0;
      overflow-x: hidden !important;
    }

    body {
      position: relative;
    }

    @supports (overflow: clip) {
      html,
      body {
        overflow-x: clip !important;
      }
    }

    @media (max-width: 768px) {
      /* Prevent full-width sections and flex/grid children from widening the page. */
      body > *,
      main,
      header,
      footer,
      section,
      .main-nav,
      .hero,
      .about-section,
      .courses-section,
      .courses-wrapper,
      .courses-grid,
      .schedule-wrapper-section,
      .instructors-section,
      .contact-section,
      .contact-container,
      .footer-content {
        max-width: 100%;
        min-width: 0;
      }

      .main-nav {
        left: 0;
        right: 0;
      }

      .hero,
      .about-section,
      .courses-section,
      .schedule-wrapper-section,
      .instructors-section,
      .contact-section,
      footer {
        box-sizing: border-box;
      }

      .hero-content,
      .hero-actions,
      .hero-stats,
      .courses-grid,
      .course-card,
      .course-body,
      .course-meta,
      .about-story,
      .contact-container,
      .contact-form-grid,
      .footer-content,
      .footer-col,
      .footer-partnership {
        min-width: 0;
        max-width: 100%;
      }

      .hero-actions a,
      .course-card,
      .contact-container > *,
      .footer-content > * {
        box-sizing: border-box;
      }

      /* The schedule and instructor carousel may scroll internally,
         but must not increase the document's overall width. */
      .schedule-wrapper-section,
      .schedule-table-card,
      .instructors-container,
      .instructors-track {
        max-width: 100%;
      }

      .schedule-table-card {
        overflow-x: auto;
        overscroll-behavior-x: contain;
      }

      .instructors-track {
        overflow-x: auto;
        overscroll-behavior-x: contain;
      }

      .main-nav .nav-links {
        left: 10px !important;
        right: 10px !important;
        width: auto !important;
        max-width: calc(100vw - 20px) !important;
        box-sizing: border-box;
      }

      .main-nav .nav-links li,
      .main-nav .nav-links a {
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
        overflow-wrap: anywhere;
      }

      /* Avoid wide media or long labels forcing the viewport wider. */
      img,
      video,
      iframe,
      canvas,
      table {
        max-width: 100%;
      }

      .schedule-table {
        width: max-content;
        max-width: none;
      }

      .footer-partnership img,
      .footer-badges img {
        max-width: 100%;
        object-fit: contain;
      }
    }

    @media (max-width: 480px) {
      .main-nav .nav-links {
        left: 8px !important;
        right: 8px !important;
        max-width: calc(100vw - 16px) !important;
      }

      .hero,
      .about-section,
      .courses-section,
      .schedule-wrapper-section,
      .instructors-section,
      .contact-section {
        padding-left: max(10px, env(safe-area-inset-left));
        padding-right: max(10px, env(safe-area-inset-right));
      }
    }



    /* MOBILE MENU: make standard navigation links white for clear contrast. */
    @media (max-width: 768px) {
      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link,
      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link:link,
      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link:visited {
        color: #ffffff !important;
      }

      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link.active {
        color: #ffffff !important;
      }

      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link:hover,
      .main-nav .nav-links > li:not(.mobile-auth-links) > a.nav-link:focus-visible {
        color: #ffffff !important;
      }
    }


    /* =========================================================
       MOBILE STATS CARDS
       Prevent the 3-card row from overflowing on small screens.
       ========================================================= */
    @media screen and (max-width: 767px) {
      .about-stats {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 22px auto !important;
        padding: 0 12px !important;
        box-sizing: border-box !important;
        align-items: stretch !important;
        justify-content: center !important;
      }

      .about-stats > .stat-card {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        min-height: 0 !important;
        height: auto !important;
        margin: 0 !important;
        padding: 22px 12px !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: flex-start !important;
        border-radius: 16px !important;
        overflow-wrap: anywhere;
      }

      .about-stats > .stat-card .stat-icon {
        width: 64px !important;
        height: 64px !important;
        min-width: 64px !important;
        margin: 0 auto 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
      }

      .about-stats > .stat-card .stat-icon i {
        font-size: 27px !important;
      }

      .about-stats > .stat-card h3 {
        margin: 0 0 8px !important;
        font-size: clamp(30px, 8vw, 40px) !important;
        line-height: 1.1 !important;
        text-align: center !important;
        overflow-wrap: normal !important;
      }

      .about-stats > .stat-card p {
        max-width: 18ch !important;
        margin: 0 auto !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        text-align: center !important;
        overflow-wrap: break-word !important;
      }
    }

    @media screen and (max-width: 360px) {
      .about-stats {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 12px !important;
        padding: 0 16px !important;
      }

      .about-stats > .stat-card {
        padding: 20px 14px !important;
      }

      .about-stats > .stat-card .stat-icon {
        width: 58px !important;
        height: 58px !important;
        min-width: 58px !important;
      }

      .about-stats > .stat-card h3 {
        font-size: 34px !important;
      }
    }


    /* MOBILE STATS: one full-width card per row. */
    @media screen and (max-width: 767px) {
      .about-stats {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 14px !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 14px !important;
        box-sizing: border-box !important;
      }

      .about-stats > .stat-card {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 22px 16px !important;
        box-sizing: border-box !important;
      }

      .about-stats > .stat-card .stat-icon {
        margin-bottom: 12px !important;
      }

      .about-stats > .stat-card p {
        max-width: 28ch !important;
      }
    }


/* =========================================================
   CONTACT SECTION — FINAL RESPONSIVE DESIGN
   Consolidated overrides for desktop, tablet, and phone layouts.
   ========================================================= */
html body #contact.contact-section {
    --contact-green: #075b35;
    --contact-deep-green: #06381e;
    --contact-yellow: #e1dc36;
    --contact-muted: #64748b;
    width: 100% !important;
    max-width: 100% !important;
    padding: clamp(44px, 5.5vw, 78px) clamp(16px, 3.5vw, 30px) !important;
    background: #f4f7f4 !important;
    overflow-x: clip !important;
    scroll-margin-top: 84px;
    box-sizing: border-box !important;
}

html body #contact.contact-section,
html body #contact.contact-section * {
    box-sizing: border-box !important;
}

html body #contact.contact-section .contact-page-wrapper {
    width: 100% !important;
    max-width: 1160px !important;
    margin: 0 auto !important;
}

html body #contact.contact-section .contact-section-heading {
    width: 100% !important;
    max-width: 700px !important;
    margin: 0 auto 34px !important;
    text-align: center !important;
}

html body #contact.contact-section .contact-eyebrow {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 9px !important;
    margin: 0 0 12px !important;
    color: #075b35 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    letter-spacing: 1.8px !important;
    line-height: 1.5 !important;
    text-transform: uppercase !important;
}

html body #contact.contact-section .contact-section-heading h2 {
    margin: 0 0 12px !important;
    color: #123524 !important;
    font-size: clamp(30px, 3.5vw, 42px) !important;
    font-weight: 800 !important;
    line-height: 1.16 !important;
    letter-spacing: -0.7px !important;
    overflow-wrap: normal !important;
    word-break: normal !important;
}

html body #contact.contact-section .contact-section-heading > p:last-child {
    max-width: 590px !important;
    margin: 0 auto !important;
    color: #64748b !important;
    font-size: 15px !important;
    line-height: 1.75 !important;
    overflow-wrap: break-word !important;
}

html body #contact.contact-section .contact-container {
    display: grid !important;
    grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr) !important;
    align-items: stretch !important;
    gap: clamp(18px, 2.6vw, 30px) !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
}

html body #contact.contact-section .contact-info-list,
html body #contact.contact-section .contact-form-card {
    display: block !important;
    float: none !important;
    position: relative !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    margin: 0 !important;
    border-radius: 20px !important;
    box-sizing: border-box !important;
}

html body #contact.contact-section .contact-info-list {
    padding: clamp(24px, 3vw, 34px) !important;
    color: #fff !important;
    background: #06381e !important;
    border: 1px solid rgba(255,255,255,.1) !important;
    box-shadow: 0 14px 34px rgba(6,56,30,.12) !important;
}

html body #contact.contact-section .contact-info-heading {
    margin: 0 0 9px !important;
    color: #fff !important;
    font-size: clamp(21px, 2vw, 25px) !important;
    font-weight: 800 !important;
    line-height: 1.3 !important;
    overflow-wrap: break-word !important;
    word-break: normal !important;
}

html body #contact.contact-section .contact-info-intro {
    margin: 0 0 18px !important;
    color: #d7e6dc !important;
    font-size: 13px !important;
    line-height: 1.7 !important;
}

html body #contact.contact-section .contact-info-item {
    display: flex !important;
    align-items: flex-start !important;
    gap: 13px !important;
    width: 100% !important;
    min-width: 0 !important;
    padding: 16px 0 !important;
}

html body #contact.contact-section .contact-icon-wrapper {
    display: flex !important;
    flex: 0 0 42px !important;
    align-items: center !important;
    justify-content: center !important;
    width: 42px !important;
    height: 42px !important;
    color: #06381e !important;
    background: #e1dc36 !important;
    border-radius: 12px !important;
    font-size: 15px !important;
}

html body #contact.contact-section .contact-info-item > div:last-child {
    flex: 1 1 0% !important;
    width: auto !important;
    min-width: 0 !important;
    max-width: 100% !important;
}

html body #contact.contact-section .contact-info-title {
    margin: 0 0 5px !important;
    color: #fff !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: 1.45 !important;
}

html body #contact.contact-section .contact-info-subtitle {
    margin: 0 0 6px !important;
    color: #c6d8cc !important;
    font-size: 12px !important;
    line-height: 1.65 !important;
}

html body #contact.contact-section .contact-info-detail,
html body #contact.contact-section .contact-info-detail a {
    max-width: 100% !important;
    color: #fff !important;
    font-size: 12.5px !important;
    line-height: 1.7 !important;
    overflow-wrap: anywhere !important;
    word-break: normal !important;
}

html body #contact.contact-section .contact-info-detail {
    margin: 3px 0 !important;
}

html body #contact.contact-section .contact-info-detail a {
    text-decoration: underline !important;
    text-decoration-color: rgba(255,255,255,.45) !important;
    text-underline-offset: 3px !important;
}

html body #contact.contact-section .contact-info-detail a:hover {
    color: #e1dc36 !important;
}

html body #contact.contact-section .contact-divider {
    width: 100% !important;
    margin: 0 !important;
    border: 0 !important;
    border-top: 1px solid rgba(255,255,255,.15) !important;
}

html body #contact.contact-section .contact-form-card {
    padding: clamp(24px, 3.2vw, 38px) !important;
    background: #fff !important;
    border: 1px solid #e2e9e4 !important;
    box-shadow: 0 14px 34px rgba(15,23,42,.055) !important;
}

html body #contact.contact-section .contact-form-heading {
    margin: 0 0 8px !important;
    color: #123524 !important;
    font-size: clamp(22px, 2.2vw, 27px) !important;
    font-weight: 800 !important;
    line-height: 1.3 !important;
}

html body #contact.contact-section .contact-form-description {
    margin: 0 0 24px !important;
    color: #64748b !important;
    font-size: 13px !important;
    line-height: 1.7 !important;
}

html body #contact.contact-section form {
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
}

html body #contact.contact-section .contact-form-grid {
    display: grid !important;
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    gap: 17px 15px !important;
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
}

html body #contact.contact-section .contact-form-grid > div {
    width: 100% !important;
    min-width: 0 !important;
    margin: 0 !important;
}

html body #contact.contact-section .contact-form-grid > div:nth-child(3) {
    grid-column: 1 / -1 !important;
}

html body #contact.contact-section .contact-field-label {
    display: block !important;
    margin: 0 0 7px !important;
    color: #263b30 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    line-height: 1.5 !important;
}

html body #contact.contact-section .contact-form-input,
html body #contact.contact-section .contact-form-textarea {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 13px 14px !important;
    color: #172b20 !important;
    background: #fbfcfb !important;
    border: 1px solid #d8e1db !important;
    border-radius: 11px !important;
    box-shadow: none !important;
    font-family: 'Poppins', sans-serif !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease !important;
}

html body #contact.contact-section .contact-form-input {
    min-height: 50px !important;
}

html body #contact.contact-section .contact-form-input::placeholder,
html body #contact.contact-section .contact-form-textarea::placeholder {
    color: #89968e !important;
    opacity: 1 !important;
}

html body #contact.contact-section .contact-form-input:focus,
html body #contact.contact-section .contact-form-textarea:focus {
    background: #fff !important;
    border-color: #075b35 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(7,91,53,.12) !important;
}

html body #contact.contact-section .contact-form-textarea-wrapper {
    width: 100% !important;
    min-width: 0 !important;
    margin: 18px 0 0 !important;
}

html body #contact.contact-section .contact-form-textarea {
    min-height: 145px !important;
    resize: vertical !important;
}

html body #contact.contact-section .contact-form-submit-wrapper {
    display: flex !important;
    justify-content: flex-end !important;
    width: 100% !important;
    margin: 20px 0 0 !important;
}

html body #contact.contact-section .btn-submit-contact {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    width: auto !important;
    max-width: 100% !important;
    min-height: 50px !important;
    margin: 0 !important;
    padding: 13px 23px !important;
    color: #fff !important;
    background: #075b35 !important;
    border: 1px solid #075b35 !important;
    border-radius: 11px !important;
    box-shadow: none !important;
    font-family: 'Poppins', sans-serif !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    line-height: 1.4 !important;
    white-space: normal !important;
    cursor: pointer !important;
    transition: background .18s ease, transform .18s ease, box-shadow .18s ease !important;
}

html body #contact.contact-section .btn-submit-contact:hover {
    color: #fff !important;
    background: #06381e !important;
    border-color: #06381e !important;
    box-shadow: 0 7px 16px rgba(7,91,53,.16) !important;
    transform: translateY(-1px) !important;
}

html body #contact.contact-section .btn-submit-contact:focus-visible {
    outline: 3px solid rgba(225,220,54,.9) !important;
    outline-offset: 3px !important;
}

html body #contact.contact-section .alertSuccess {
    display: flex !important;
    align-items: flex-start !important;
    gap: 10px !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 0 22px !important;
    padding: 14px 16px !important;
    color: #14532d !important;
    background: #f0fdf4 !important;
    border: 1px solid #bbf7d0 !important;
    border-left: 4px solid #16a34a !important;
    border-radius: 12px !important;
    font-size: 13px !important;
    line-height: 1.6 !important;
    overflow-wrap: anywhere !important;
}

/* Tablet and compact desktop: stack the panels before the form becomes cramped. */
@media (max-width: 900px) {
    html body #contact.contact-section {
        padding: 44px 20px !important;
    }

    html body #contact.contact-section .contact-container {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 18px !important;
        max-width: 720px !important;
        margin-inline: auto !important;
    }

    html body #contact.contact-section .contact-info-list,
    html body #contact.contact-section .contact-form-card {
        padding: 27px !important;
        border-radius: 18px !important;
    }

    html body #contact.contact-section .contact-form-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    html body #contact.contact-section .contact-form-grid > div:nth-child(3) {
        grid-column: 1 / -1 !important;
    }
}

/* Phones: make the form the first thing visitors can act on. */
@media (max-width: 600px) {
    html body #contact.contact-section {
        padding: 36px 15px !important;
    }

    html body #contact.contact-section .contact-section-heading {
        margin: 0 auto 24px !important;
        text-align: center !important;
    }

    html body #contact.contact-section .contact-eyebrow {
        margin-bottom: 9px !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
    }

    html body #contact.contact-section .contact-section-heading h2 {
        margin-bottom: 10px !important;
        font-size: clamp(29px, 8vw, 35px) !important;
        line-height: 1.18 !important;
        letter-spacing: -.5px !important;
    }

    html body #contact.contact-section .contact-section-heading > p:last-child {
        max-width: 36ch !important;
        font-size: 13.5px !important;
        line-height: 1.7 !important;
    }

    html body #contact.contact-section .contact-container {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 15px !important;
        width: 100% !important;
    }

    html body #contact.contact-section .contact-form-card {
        order: 1 !important;
        padding: 23px 20px !important;
        border-radius: 17px !important;
    }

    html body #contact.contact-section .contact-info-list {
        order: 2 !important;
        padding: 23px 20px !important;
        border-radius: 17px !important;
    }

    html body #contact.contact-section .contact-form-heading {
        margin-bottom: 7px !important;
        font-size: 22px !important;
    }

    html body #contact.contact-section .contact-form-description {
        margin-bottom: 20px !important;
        font-size: 12.5px !important;
        line-height: 1.65 !important;
    }

    html body #contact.contact-section .contact-form-grid {
        grid-template-columns: minmax(0, 1fr) !important;
        gap: 15px !important;
    }

    html body #contact.contact-section .contact-form-grid > div:nth-child(3) {
        grid-column: auto !important;
    }

    html body #contact.contact-section .contact-field-label {
        margin-bottom: 6px !important;
        font-size: 12px !important;
    }

    html body #contact.contact-section .contact-form-input,
    html body #contact.contact-section .contact-form-textarea {
        padding: 13px 13px !important;
        font-size: 16px !important; /* Prevents iOS form-field zoom. */
        border-radius: 10px !important;
    }

    html body #contact.contact-section .contact-form-input {
        min-height: 50px !important;
    }

    html body #contact.contact-section .contact-form-textarea-wrapper {
        margin-top: 16px !important;
    }

    html body #contact.contact-section .contact-form-textarea {
        min-height: 135px !important;
    }

    html body #contact.contact-section .contact-form-submit-wrapper {
        margin-top: 17px !important;
    }

    html body #contact.contact-section .btn-submit-contact {
        width: 100% !important;
        min-height: 51px !important;
        padding: 13px 16px !important;
        font-size: 13px !important;
    }

    html body #contact.contact-section .contact-info-heading {
        font-size: 21px !important;
    }

    html body #contact.contact-section .contact-info-intro {
        margin-bottom: 12px !important;
        font-size: 13px !important;
    }

    html body #contact.contact-section .contact-info-item {
        gap: 11px !important;
        padding: 14px 0 !important;
    }

    html body #contact.contact-section .contact-icon-wrapper {
        flex-basis: 39px !important;
        width: 39px !important;
        height: 39px !important;
        border-radius: 11px !important;
        font-size: 14px !important;
    }

    html body #contact.contact-section .contact-info-title {
        font-size: 13.5px !important;
    }

    html body #contact.contact-section .contact-info-subtitle,
    html body #contact.contact-section .contact-info-detail,
    html body #contact.contact-section .contact-info-detail a {
        font-size: 12px !important;
    }
}

/* Very small phones. */
@media (max-width: 380px) {
    html body #contact.contact-section {
        padding: 30px 12px !important;
    }

    html body #contact.contact-section .contact-section-heading {
        margin-bottom: 20px !important;
    }

    html body #contact.contact-section .contact-container {
        gap: 13px !important;
    }

    html body #contact.contact-section .contact-info-list,
    html body #contact.contact-section .contact-form-card {
        padding: 19px 15px !important;
        border-radius: 15px !important;
    }

    html body #contact.contact-section .contact-section-heading h2 {
        font-size: 29px !important;
    }

    html body #contact.contact-section .contact-form-heading {
        font-size: 20px !important;
    }

    html body #contact.contact-section .contact-info-heading {
        font-size: 20px !important;
    }
}

@media (prefers-reduced-motion: reduce) {
    html body #contact.contact-section .contact-form-input,
    html body #contact.contact-section .contact-form-textarea,
    html body #contact.contact-section .btn-submit-contact {
        transition: none !important;
    }
}



    /* =========================================================
       CONTACT WIDTH ALIGNMENT PATCH
       Keep the section full-width like the other landing sections,
       and remove accidental nested card styling from the form itself.
       ========================================================= */
    html body section#contact.contact-section {
      display: block !important;
      width: 100% !important;
      max-width: none !important;
      min-width: 0 !important;
      margin-left: 0 !important;
      margin-right: 0 !important;
      padding-left: clamp(12px, 3vw, 30px) !important;
      padding-right: clamp(12px, 3vw, 30px) !important;
      background: #f9f9f9 !important;
      box-sizing: border-box !important;
      overflow-x: clip !important;
    }

    html body section#contact.contact-section > .contact-page-wrapper {
      display: block !important;
      width: 100% !important;
      max-width: 1160px !important;
      min-width: 0 !important;
      margin: 0 auto !important;
      padding: 0 !important;
      background: transparent !important;
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      box-sizing: border-box !important;
    }

    html body section#contact.contact-section .contact-container {
      display: grid !important;
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      margin: 0 !important;
      padding: 0 !important;
      background: transparent !important;
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      box-sizing: border-box !important;
    }

    html body section#contact.contact-section .contact-info-list,
    html body section#contact.contact-section .contact-form-card {
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      margin: 0 !important;
      box-sizing: border-box !important;
    }

    /* The form is a content area inside the white card, not another card. */
    html body section#contact.contact-section form {
      display: block !important;
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      margin: 0 !important;
      padding: 0 !important;
      background: transparent !important;
      border: 0 !important;
      border-radius: 0 !important;
      box-shadow: none !important;
    }

    html body section#contact.contact-section .contact-form-grid,
    html body section#contact.contact-section .contact-form-textarea-wrapper,
    html body section#contact.contact-section .contact-form-input,
    html body section#contact.contact-section .contact-form-textarea {
      width: 100% !important;
      max-width: 100% !important;
      min-width: 0 !important;
      box-sizing: border-box !important;
    }

    @media (max-width: 900px) {
      html body section#contact.contact-section .contact-container {
        grid-template-columns: minmax(0, 1fr) !important;
        max-width: 100% !important;
        margin: 0 !important;
      }
    }

    @media (max-width: 600px) {
      html body section#contact.contact-section {
        padding: 36px 12px !important;
      }

      html body section#contact.contact-section .contact-page-wrapper,
      html body section#contact.contact-section .contact-container {
        width: 100% !important;
        max-width: 100% !important;
      }

      html body section#contact.contact-section .contact-form-card,
      html body section#contact.contact-section .contact-info-list {
        padding: 22px 18px !important;
        border-radius: 16px !important;
      }
    }

    @media (max-width: 360px) {
      html body section#contact.contact-section {
        padding-left: 10px !important;
        padding-right: 10px !important;
      }

      html body section#contact.contact-section .contact-form-card,
      html body section#contact.contact-section .contact-info-list {
        padding: 18px 14px !important;
      }
    }



    /* =========================================================
       MOBILE HERO CTA ALIGNMENT
       Center button labels/icons and keep both buttons inside the
       available phone width. Desktop styles remain unchanged.
       ========================================================= */
    @media (max-width: 600px) {
      html body #home.hero .hero-content .hero-actions {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 12px !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin-left: auto !important;
        margin-right: auto !important;
        box-sizing: border-box !important;
      }

      html body #home.hero .hero-content .hero-actions > a.btn-primary,
      html body #home.hero .hero-content .hero-actions > a.btn-secondary {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        float: none !important;
        position: relative !important;
        inset: auto !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        flex: 0 1 auto !important;
        margin-left: auto !important;
        margin-right: auto !important;
        box-sizing: border-box !important;
        text-align: center !important;
        white-space: normal !important;
        padding-left: 18px !important;
        padding-right: 18px !important;
      }

      html body #home.hero .hero-content .hero-actions > a.btn-primary i,
      html body #home.hero .hero-content .hero-actions > a.btn-secondary i {
        position: static !important;
        inset: auto !important;
        float: none !important;
        margin: 0 !important;
        transform: none !important;
        flex: 0 0 auto !important;
      }
    }

</style>
</head>

<body>

  <!-- NAVIGATION -->
  <nav class="main-nav">
    <div class="nav-logo">
      <a href="{{ Route::has('index') ? route('index') : url('/') }}"
        class="logo-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
      </a>
      <span>LEDIPO</span>
    </div>

    <ul class="nav-links m-0 p-0">
      <li><a href="#home" class="nav-link active">Home</a></li>
      <li><a href="#schedule" class="nav-link">Schedule</a></li>
      <li><a href="#about" class="nav-link">About</a></li>
      <li><a href="#courses" class="nav-link">Courses</a></li>
      <li><a href="#contact" class="nav-link">Contact</a></li>

      {{-- Show authentication links inside the hamburger menu on mobile/tablet. --}}
      @guest
        <li class="mobile-auth-links">
          <a href="{{ route('Login') }}" class="mobile-auth-link mobile-auth-login">
            <i class="fas fa-right-to-bracket" aria-hidden="true"></i>
            <span>Login</span>
          </a>
        </li>
        <li class="mobile-auth-links">
          <a href="{{ route('SignupPage') }}" class="mobile-auth-link mobile-auth-signup">
            <i class="fas fa-user-plus" aria-hidden="true"></i>
            <span>Sign Up</span>
          </a>
        </li>
      @endguest
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
                onclick="event.preventDefault(); openLogoutModal();">
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

  <!-- HERO -->
  <section id="home" class="hero"
    style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('{{ asset('images/landingpage.jpg') }}') center/cover no-repeat; margin-bottom: 0;">
    <div class="hero-overlay"></div>

    <div class="hero-content">
      <div class="hero-badge">
        <i class="fas fa-award"></i>
        Dasmariñas City Livelihood Program
      </div>

      @php
        $heroTitle = $heroSection->title ?? 'Start Your Training Journey';
        $heroWords = explode(' ', $heroTitle);
        $heroLastWord = array_pop($heroWords);
        $heroRest = implode(' ', $heroWords);
      @endphp

      <h1>{{ $heroRest }} <span>{{ $heroLastWord }}</span></h1>
      <p>{{ $heroSection->body ?? '' }}</p>

      <div class="hero-actions">
        <a href="{{ route('SignupPage') }}" class="btn-primary">
          Get Started <i class="fas fa-arrow-right"></i>
        </a>
        <a href="{{ route('Login') }}" class="btn-secondary">
          Login
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat">
          <h3 class="stat-num">{{ $totalCourses }}+</h3>
          <span class="stat-label">Courses</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
          <h3 class="stat-num">{{ $totalStudents }}+</h3>
          <span class="stat-label">Students</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
          <span class="stat-num">Free</span>
          <span class="stat-label">Registration</span>
        </div>
      </div>
    </div>
  </section>

  <!-- SCHEDULE & REQUIREMENTS SECTION -->
  <div id="schedule" class="schedule-wrapper-section">
    <div class="schedule-table-card">
      <h3>(2026) 3rd QUARTER FREE LIVELIHOOD AT THE DASMARIÑAS CITY TRAINING
        CENTER-MAIN</h3>
      <p
        style="font-size: 11.5px; text-align: center; margin-bottom: 14px; opacity: 0.9;">
        Monday to Thursday (Except Holidays) 8:00 AM - 5:00 PM<br>
        Training Venue: Dasmariñas City Training Center-Main, B-077 LS Brgy. San
        Andres 1, City of Dasmariñas, Cavite
      </p>

      <table class="schedule-table">
        <thead>
          <tr>
            <th>Title of Program</th>
            <th>Duration</th>
            <th>Date of Trainings</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Candle Making</strong><br><small>CBTP for CANDLE
                MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 01-Sept 08, 2026</td>
          </tr>
          <tr>
            <td><strong>Hilot Wellness Massage</strong><br><small>CBTP for
                MASSAGE THERAPIST (BASIC)</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 14-21, 2026</td>
          </tr>
          <tr>
            <td><strong>Hilot Wellness Massage</strong><br><small>CBTP for
                MASSAGE THERAPIST (HILOT WELLNESS)</small></td>
            <td>80 Hours (10 Days)</td>
            <td>2nd Batch: September 22-Oct 7, 2026</td>
          </tr>
          <tr>
            <td><strong>Basic Sewing</strong><br><small>CBTP for SEWERS</small>
            </td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 21-28, 2026</td>
          </tr>
          <tr>
            <td><strong>Dress Making</strong><br><small>CBTP for PATTERN
                MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>2nd Batch: September 29-Oct 14, 2026</td>
          </tr>
          <tr>
            <td><strong>Fashion Bayong Bag</strong><br><small>CBTP for FASHION
                BAYONG BAG MAKERS</small></td>
            <td>40 Hours (5 Days)</td>
            <td>October 05 - 12, 2026</td>
          </tr>
        </tbody>
      </table>

      <div class="requirements-box">
        <strong
          style="color: #f5c842; display: block; margin-bottom: 6px;">REQUIREMENTS
          FOR ADMISSION:</strong>
        • Open to all residents of the City of Dasmariñas. Limited slots
        available – First Come, First Served!<br>
        • Residence Certificate (Barangay-issued) &middot; Voter's ID or Voter's
        Certification<br>
        • Two (2) pieces 2x2 ID photos &middot; One (1) valid government-issued
        ID &middot; Must be 18 years old and above.<br>
        <strong>Additional Requirement (Dressmaking):</strong> With knowledge in
        basic sewing.<br>
        <span
          style="font-size: 11px; opacity: 0.8; display: block; margin-top: 8px;">For
          inquiries: Livelihood and Microenterprise Development Office (LEDIPO)
          · 0917 311 2871</span>
      </div>
    </div>
  </div>

  <!-- ABOUT SECTION -->
  <section id="about" class="about-section" style="padding-bottom: 20px;">
    {{-- Our Story Video Section --}}
    <div class="about-story"
      style="margin-top: 10px; margin-bottom: 0; align-items: flex-start;">
      <div>
        <h2>{{ $aboutSection->title ?? 'OUR STORY' }}</h2>
        <p>{!! nl2br(e($aboutSection->body ?? '')) !!}</p>
      </div>

     <div>
        <img src="{{ $aboutSection->image_path ? asset($aboutSection->image_path) : asset('images/ourstory.jpg') }}" alt="About Us">
      </div> 
    </div>

    {{-- Partnerships and Initiatives Title --}}
    <h2 style="color: #1a4d2e; font-weight: 800; text-align: center; margin: 40px 0 20px;">Partnerships and Initiatives</h2>

    {{-- Partner Grid Cards (Partnerships and Initiatives) --}}
    <div
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; max-width: 1100px; margin: 0 auto; padding: 0 20px;">
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/dti.jpg') }}" alt="DTI Partnership"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          Department of Trade and Industry (DTI) Cavite:</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">
          Collaborates directly to strengthen MSMEs through the One Town, One
          Product (OTOP) framework, offering consumer protection and product
          innovation support.</p>
      </div>
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/cdcci.jpg') }}" alt="CDCCI Business Expo"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          City of Dasmariñas Chamber of Commerce & Industry, Inc. (CDCCI):</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">Partners
          on local economic initiatives, joint technical meetings, and
          delegations to major national gatherings like the Philippine Chamber
          of Commerce and Industry (PCCI) business conferences.</p>
      </div>
      <div
        style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
        <img src="{{ asset('images/bazaar.jpg') }}" alt="Trade Fairs"
          style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
        <h4
          style="font-size: 14px; font-weight: 700; color: #1a4d2e; margin-bottom: 6px;">
          Dasmariñas Business Expo & Food Bazaar:</h4>
        <p style="font-size: 12px; color: #718096; line-height: 1.5;">Alongside
          these partnerships, LEDIPO spearheads major initiatives such as
          provincial trade fairs at hubs like SM City Dasmariñas, and free
          skills development tracks at the Dasmariñas City Training Center.</p>
      </div>
    </div>

    {{-- TRAINING JOURNEY SECTION --}}
    <div
      style="max-width: 1100px; margin: 40px auto 20px; padding: 0 20px; text-align: center;">
      <h2 style="color: #1a4d2e; font-weight: 800; margin-bottom: 16px;">
        Training Journey</h2>
      <p
        style="font-size: 13.5px; color: #4a5568; max-width: 900px; margin: 0 auto 24px; line-height: 1.6;">
        To ensure our community programs translate into real-world employment
        and entrepreneurship, LEDIPO structures its training tracks in strict
        alignment with national competency standards, incorporating recognized
        frameworks such as TESDA (Technical Education and Skills Development
        Authority). When you enroll and complete a course through our platform,
        you gain:
      </p>
      <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; text-align: left;">
        <div>
          <img src="{{ asset('images/tj1.jpg') }}" alt="Cooking Class"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Industry-Aligned
              Knowledge:</strong> Practical, hands-on skills designed to meet
            current market demands, whether you want to enter the workforce or
            launch a local micro-enterprise.
          </p>
        </div>
        <div>
          <img src="{{ asset('images/tj2.png') }}"
            alt="Certificate of Completion"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Official Credentials:</strong>
            Verified digital and printable certificates of completion featuring
            official city and national markers to validate your new skills to
            employers.
          </p>
        </div>
        <div>
          <img src="{{ asset('images/tj3.png') }}"
            alt="Community Presentation"
            style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); margin-bottom: 12px;">
          <p
            style="font-size: 12.5px; color: #2d3748; line-height: 1.5; margin: 0;">
            <strong style="color: #1a4d2e;">Direct Support Pathways:</strong> A
            transparent, streamlined journey from online registration all the
            way to barangay-level practical assessments and graduation.
          </p>
        </div>
      </div>
    </div>

    {{-- CAROUSEL --}}
    <h3 class="community-carousel-title" style="color: #1a4d2e; font-weight: 800; text-align: center; margin: 40px 0 15px;">Barangay-Based Livelihood and Community Skills Training</h3>
    <div id="communityCarousel" class="carousel slide"
      data-bs-ride="carousel" data-bs-touch="true" data-bs-keyboard="true"
      data-bs-interval="5000" data-bs-pause="hover"
      aria-label="Barangay-Based Livelihood and Community Skills Training carousel"
      style="padding: 0 50px; margin-top: 30px;">

      {{-- INDICATORS --}}
      <div class="carousel-indicators">
        @foreach ($carouselSlides as $index => $slide)
          <button type="button" data-bs-target="#communityCarousel"
            data-bs-slide-to="{{ $index }}"
            aria-label="Go to slide {{ $index + 1 }}"
            class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
      </div>

      {{-- SLIDES --}}
      <div class="carousel-inner"
        style="border-radius: 12px; overflow: hidden;">
        @forelse ($carouselSlides as $index => $slide)
          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ asset($slide->image_path) }}" class="d-block w-100"
              alt="{{ $slide->title ?: 'Community Photo' }}"
              style="height: 450px; object-fit: cover;">
            <div class="carousel-caption"
              style="background: rgba(0,0,0,0.4); border-radius: 8px; padding: 10px 20px;">
              <h5>{{ $slide->title }}</h5>
              <p>{{ $slide->caption }}</p>
            </div>
          </div>
        @empty
          <div class="carousel-item active">
            <div
              style="height: 450px; display:flex; align-items:center; justify-content:center; background:#eee; color:#999;">
              No carousel slides yet.
            </div>
          </div>
        @endforelse
      </div>

      {{-- CONTROLS --}}
      <button class="carousel-control-prev" type="button"
        data-bs-target="#communityCarousel" data-bs-slide="prev"
        aria-label="Previous slide">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button"
        data-bs-target="#communityCarousel" data-bs-slide="next"
        aria-label="Next slide">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>

    {{-- ANNOUNCEMENT SECTION (CONGRATULATIONS) --}}
    @if (isset($announcementSection))
      <div class="about-story" style="margin-top: 20px; margin-bottom: 0;">
        <div>
          <h2>{{ $announcementSection->title ?? 'CONGRATULATIONS' }}</h2>
          <p>{!! nl2br(e($announcementSection->body ?? '')) !!}</p>
        </div>
        <div>
          <img
            src="{{ $announcementSection->image_path ? asset($announcementSection->image_path) : asset('images/8.jpg') }}"
            alt="Announcement">
        </div>
      </div>
    @endif
  </section>

  <!-- MEET OUR INSTRUCTORS SECTION -->
  <section class="about-section"
    style="text-align: center; background: #fafbfc; padding: 30px 20px 40px; margin-top: 0;">
    <h2 style="color: #1a4d2e; font-weight: 800; margin-bottom: 24px;">MEET OUR
      INSTRUCTORS</h2>

    <div class="instructors-container">
      <button type="button" class="instructor-nav-btn"
        onclick="scrollInstructors(-1)" aria-label="Previous Instructor"><i
          class="fas fa-arrow-left"></i></button>

      <div class="instructors-track" id="instructorTrack">
        <div class="instructor-card"
          onclick="openInstructorModal('Ms. Geraldine P. San Luis', '{{ asset('images/t1.png') }}', 'Community-Based Training (CBT) Trainer · Candle Making', ['CBT Recognition No. 240421000047', 'NC II Candle Making Holder', 'Certified Community Trainer (TM1)'], 'Specialized in artisanal candle production and micro-enterprise development for Dasmariñas residents.')">
          <img src="{{ asset('images/t1.png') }}"
            alt="Ms. Geraldine P. San Luis">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Ms. Geraldine P. San Luis</p>
          <span style="font-size: 11.5px; color: #718096; display: block;">CBT Trainer · Candle Making</span>
        </div>

        <div class="instructor-card"
          onclick="openInstructorModal('Ms. Catherine S. Laudato', '{{ asset('images/T2.png') }}', 'Community-Based Training (CBT) Trainer · Bread and Pastry', ['CBT Recognition No. 240421000044', 'NC II Bread and Pastry Production Holder', 'TESDA TM1 Certified Trainer'], 'Expert technical instructor leading professional culinary and baking programs across various community barangays.')">
          <img src="{{ asset('images/T2.png') }}" alt="Ms. Catherine S. Laudato">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Ms. Catherine S. Laudato</p>
          <span
            style="font-size: 11.5px; color: #718096; display: block;">CBT Trainer · Bread & Pastry</span>
        </div>

        <div class="instructor-card"
          onclick="openInstructorModal('Mr. Enrique L. Salonga', '{{ asset('images/T3.png') }}', 'Community-Based Training (CBT) Trainer · Computer Literacy', ['CBT Recognition No. 260421000086', 'NC II Computer Systems Servicing', 'TESDA Accredited Assessor / TM1'], 'Passionate IT and digital literacy trainer dedicated to equipping local community learners with essential modern digital skills.')">
          <img src="{{ asset('images/T3.png') }}" alt="Mr. Enrique L. Salonga">
          <p
            style="font-weight: 700; margin-top: 10px; color: #2d3748; margin-bottom: 2px;">
            Mr. Enrique L. Salonga</p>
          <span
            style="font-size: 11.5px; color: #718096; display: block;">CBT Trainer · Computer Literacy</span>
        </div>
      </div>

      <button type="button" class="instructor-nav-btn"
        onclick="scrollInstructors(1)" aria-label="Next Instructor"><i
          class="fas fa-arrow-right"></i></button>
    </div>

    <!-- MISSION & VISION -->
    <div
      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; max-width: 1000px; margin: 30px auto 0; text-align: left;">
      <div
        style="background: #fef08a; border-radius: 12px; padding: 24px; color: #1a4d2e;">
        <h3 style="font-weight: 800; margin-bottom: 12px;">OUR MISSION</h3>
        <p style="font-size: 13.5px; color: #1e293b; line-height: 1.6;">To
          empower Dasmariñas residents through accessible, industry-aligned
          livelihood and technical training programs, fostering self-reliance,
          employability, and sustainable economic growth across all barangays.
        </p>
      </div>
      <div
        style="background: #fef08a; border-radius: 12px; padding: 24px; color: #1a4d2e;">
        <h3 style="font-weight: 800; margin-bottom: 12px;">OUR VISION</h3>
        <p style="font-size: 13.5px; color: #1e293b; line-height: 1.6;">A
          digitally empowered and economically resilient City of Dasmariñas
          where every individual has equal access to quality skill-building
          opportunities, paving the way for thriving local enterprises and an
          improved quality of life.</p>
      </div>
    </div>
  </section>

  <!-- Instructor Details Modal -->
  <div class="instructor-modal" id="instructorModal">
    <div class="instructor-modal-content">
      <span class="close-modal"
        onclick="closeInstructorModal()">&times;</span>
      <img id="modalImg" src="" alt="Instructor Photo"
        class="modal-circle-img">
      <h3 id="modalName"
        style="color: #1a4d2e; font-weight: 800; margin-bottom: 5px;"></h3>
      <p id="modalRole"
        style="font-weight: 600; color: #276749; font-size: 13px; margin-bottom: 12px;">
      </p>

      <div style="text-align: left; margin-bottom: 12px;">
        <strong
          style="font-size: 13px; color: #1a4d2e; display: block; margin-bottom: 4px;">Certificates
          & Credentials:</strong>
        <ul id="modalCertList" class="modal-cert-list"></ul>
      </div>

      <div style="text-align: left;">
        <strong
          style="font-size: 13px; color: #1a4d2e; display: block; margin-bottom: 4px;">Experience
          & Background:</strong>
        <p id="modalBio"
          style="font-size: 12.5px; color: #4a5568; line-height: 1.5; margin: 0;">
        </p>
      </div>
    </div>
  </div>

  {{-- STATS CARDS --}}
  <div class="about-stats">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-user-graduate"></i>
      </div>
      <h3>{{ $totalStudents }}+</h3>
      <p>Students active on our site</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-book"></i>
      </div>
      <h3>{{ $totalCourses }}+</h3>
      <p>Courses available on our site</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <h3>{{ $totalTrainers }}+</h3>
      <p>Trainers active on our site</p>
    </div>
  </div>

<!-- COURSES SECTION -->
<section id="courses" class="courses-section">
    <div class="courses-wrapper">

        <div class="courses-header">
            <h2>Available Courses</h2>
            <p>
                Browse our livelihood training programs and view
                their facilities, schedules, and available slots.
            </p>
        </div>

        <div class="courses-grid">
            @forelse($courses as $course)

                @php
                    $banner = $course->banner_image ?: $course->thumbnail;
                    
                    if (!$banner) {
                        $titleLower = strtolower($course->title);
                        if (str_contains($titleLower, 'candle')) {
                            $banner = 'images/tj1.jpg';
                        } elseif (str_contains($titleLower, 'massage') || str_contains($titleLower, 'hilot')) {
                            $banner = 'images/tj1.jpg';
                        } elseif (str_contains($titleLower, 'sew') || str_contains($titleLower, 'dress') || str_contains($titleLower, 'garment')) {
                            $banner = 'images/tj3.png';
                        } elseif (str_contains($titleLower, 'computer') || str_contains($titleLower, 'literacy')) {
                            $banner = 'images/tj2.png';
                        } else {
                            $banner = 'images/ourstory.jpg';
                        }
                    }

                    $courseCode = $course->course_code ?: $course->training_id;
                    $facility = $course->facility ?? null;
                    $days = $course->training_days ?? [];

                    if (is_string($days)) {
                        $days = json_decode($days, true) ?? [];
                    }
                    if (!is_array($days)) {
                        $days = [];
                    }

                    if (empty($days) && $course->schedule) {
                        $dayMap = [
                            'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday',
                            'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday', 'Sun' => 'Sunday',
                        ];
                        foreach ($dayMap as $short => $full) {
                            if (preg_match('/\b' . $short . '\b/i', $course->schedule)) {
                                $days[] = $full;
                            }
                        }
                    }

                    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $days = array_values(array_unique($days));
                    $days = array_values(array_intersect($dayOrder, $days));
                    $daysLabel = !empty($days) ? implode(', ', $days) : 'Not specified';

                    $trainingDates = 'Not yet scheduled';
                    if ($course->start_date && $course->end_date) {
                        $trainingDates = \Carbon\Carbon::parse($course->start_date)->format('M d, Y') . ' – ' . \Carbon\Carbon::parse($course->end_date)->format('M d, Y');
                    } elseif ($course->start_date) {
                        $trainingDates = \Carbon\Carbon::parse($course->start_date)->format('M d, Y') . ' onwards';
                    } elseif ($course->end_date) {
                        $trainingDates = 'Until ' . \Carbon\Carbon::parse($course->end_date)->format('M d, Y');
                    }

                    $trainingTime = 'Not specified';
                    if ($course->start_time && $course->end_time) {
                        $trainingTime = \Carbon\Carbon::parse($course->start_time)->format('g:i A') . ' – ' . \Carbon\Carbon::parse($course->end_time)->format('g:i A');
                    } elseif ($course->start_time) {
                        $trainingTime = \Carbon\Carbon::parse($course->start_time)->format('g:i A') . ' onwards';
                    } elseif ($course->schedule) {
                        $trainingTime = $course->schedule;
                    }

                    $duration = $course->duration ?: 'Not specified';
                    if (is_numeric($duration)) {
                        $duration .= ' days';
                    }

                    $availableSlots = (int) ($course->available_slots ?? 0);
                @endphp

                <article class="course-card">
                    {{-- COURSE BANNER / IMAGE COVER --}}
                    <div class="course-thumbnail">
                        @if($banner)
                            <img src="{{ asset($banner) }}" alt="{{ $course->title }}" loading="lazy">
                        @else
                            <div class="course-placeholder">
                                <i class="fas fa-book-open"></i>
                                <span>Livelihood Training</span>
                            </div>
                        @endif
                    </div>

                    {{-- COURSE INFORMATION --}}
                    <div class="course-body">
                        @if($courseCode)
                            <span class="course-code">{{ $courseCode }}</span>
                        @endif

                        <h4>{{ $course->title }}</h4>

                        <p class="course-description">
                            {{ \Illuminate\Support\Str::limit($course->description ?: 'No course description available.', 120) }}
                        </p>

                        <div class="course-meta">
                            <div class="course-meta-item">
                                <i class="fas fa-building"></i>
                                <div>
                                    <small>Training Facility</small>
                                    <strong>{{ $facility?->name ?: 'Facility not assigned' }}</strong>
                                    @if($facility?->address)
                                        <span>{{ $facility->address }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="course-meta-item">
                                <i class="fas fa-calendar-week"></i>
                                <div>
                                    <small>Training Days</small>
                                    <strong>{{ $daysLabel }}</strong>
                                </div>
                            </div>

                            <div class="course-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <small>Training Period</small>
                                    <strong>{{ $trainingDates }}</strong>
                                </div>
                            </div>

                            <div class="course-meta-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <small>Daily Schedule</small>
                                    <strong>{{ $trainingTime }}</strong>
                                </div>
                            </div>

                            <div class="course-meta-item">
                                <i class="fas fa-hourglass-half"></i>
                                <div>
                                    <small>Duration</small>
                                    <strong>{{ $duration }}</strong>
                                </div>
                            </div>

                            <div class="course-meta-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <small>Available Slots</small>
                                    <strong>
                                        {{ $availableSlots }} {{ $availableSlots === 1 ? 'slot available' : 'slots available' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('landing.course.detail', $course->id) }}" class="btn-view-course">
                            View Course <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>

            @empty
                <p class="courses-empty">No courses available at the moment.</p>
            @endforelse
        </div>
    </div>
</section>

  <!-- CONTACT SECTION -->
  <section id="contact" class="contact-section" aria-labelledby="contactSectionTitle">
    <div class="contact-page-wrapper">
      <div class="contact-section-heading">
        <p class="contact-eyebrow">Get in touch</p>
        <h2 id="contactSectionTitle">Contact Us</h2>
        <p>Have a question about our livelihood training programs? Send us a message or reach out using the details below.</p>
      </div>

      @if (session('success'))
        <div class="alertSuccess" role="status" aria-live="polite">
          <i class="fas fa-check-circle" aria-hidden="true"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <div class="contact-container">
        <div class="contact-info-list">
          <h3 class="contact-info-heading">Contact information</h3>
          <p class="contact-info-intro">We’re here to help. Choose the most convenient way to reach our team.</p>

          <div class="contact-info-item">
            <div class="contact-icon-wrapper" aria-hidden="true"><i class="fas fa-phone"></i></div>
            <div>
              <h4 class="contact-info-title">Call us</h4>
              <p class="contact-info-subtitle">We are available 24/7, 7 days a week.</p>
              <p class="contact-info-detail">Mobile: <a href="tel:09695228600">0969-522-8600</a></p>
              <p class="contact-info-detail">Landline: <a href="tel:0464164445">(046) 416-4445</a></p>
            </div>
          </div>

          <hr class="contact-divider">

          <div class="contact-info-item">
            <div class="contact-icon-wrapper" aria-hidden="true"><i class="fas fa-envelope"></i></div>
            <div>
              <h4 class="contact-info-title">Write to us</h4>
              <p class="contact-info-subtitle">Send your inquiry and our team will get back to you.</p>
              <p class="contact-info-detail"><a href="mailto:support@ledipo.gov.ph">support@ledipo.gov.ph</a></p>
            </div>
          </div>

          <hr class="contact-divider">

          <div class="contact-info-item">
            <div class="contact-icon-wrapper" aria-hidden="true"><i class="fas fa-location-dot"></i></div>
            <div>
              <h4 class="contact-info-title">Visit us</h4>
              <p class="contact-info-subtitle">Come visit our office.</p>
              <p class="contact-info-detail">Block O-77, Lot 5, San Andres I, City of Dasmariñas, Cavite</p>
            </div>
          </div>
        </div>

        <div class="contact-form-card">
          <h3 class="contact-form-heading">Send us a message</h3>
          <p class="contact-form-description">Complete the fields below and we’ll be happy to assist you.</p>

          <form action="{{ route('contact.send') }}" method="POST">
            @csrf
            <div class="contact-form-grid">
              <div>
                <label class="contact-field-label" for="contactName">Full name <span aria-hidden="true">*</span></label>
                <input id="contactName" type="text" name="name" placeholder="Enter your full name" class="contact-form-input" autocomplete="name" value="{{ old('name') }}" required>
              </div>
              <div>
                <label class="contact-field-label" for="contactEmail">Email address <span aria-hidden="true">*</span></label>
                <input id="contactEmail" type="email" name="email" placeholder="you@example.com" class="contact-form-input" autocomplete="email" value="{{ old('email') }}" required>
              </div>
              <div>
                <label class="contact-field-label" for="contactPhone">Mobile number <span aria-hidden="true">*</span></label>
                <input id="contactPhone" type="tel" name="phone" placeholder="09XXXXXXXXX" class="contact-form-input" autocomplete="tel" inputmode="numeric" maxlength="11" pattern="09[0-9]{9}" title="Please enter a valid 11-digit mobile number starting with 09." value="{{ old('phone') }}" required>
              </div>
            </div>

            <div class="contact-form-textarea-wrapper">
              <label class="contact-field-label" for="contactMessage">Your message <span aria-hidden="true">*</span></label>
              <textarea id="contactMessage" name="message" placeholder="Tell us how we can help..." class="contact-form-textarea" rows="5" required>{{ old('message') }}</textarea>
            </div>

            <div class="contact-form-submit-wrapper">
              <button type="submit" class="btn-submit-contact">
                Send Message <i class="fas fa-arrow-right" aria-hidden="true"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

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
          <li><a href="javascript:void(0);" onclick="confirmExternalLink('https://www.facebook.com/dasmarinas.livelihood.52')">Facebook</a></li>
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
          <button class="policy-btn-accept unlocked" id="faqAcceptBtn" onclick="closePolicyModal('faqModal')">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- LOGOUT CONFIRMATION MODAL -->
  <div class="logout-modal-overlay" id="logoutConfirmModal">
    <div class="logout-modal-box">
      <i class="fas fa-triangle-exclamation"></i>
      <h3>Confirm Logout</h3>
      <p>Are you sure you want to log out of your account?</p>
      <div class="logout-modal-actions">
        <button type="button" class="btn-logout-yes" onclick="executeLogout()">Yes</button>
        <button type="button" class="btn-logout-no" onclick="closeLogoutModal()">Cancel</button>
      </div>
    </div>
  </div>


  <!-- EXTERNAL LINK WARNING MODAL -->
  <div class="logout-modal-overlay" id="externalLinkModal">
    <div class="logout-modal-box">
      <i class="fas fa-triangle-exclamation" style="color: #1a4d2e;"></i>
      <h3>Leaving Website</h3>
      <p>Are you sure you want to go to this link? It is outside the official LEDIPO website.</p>
      <div class="logout-modal-actions">
        <button type="button" class="btn-logout-yes" id="proceedExternalBtn">Yes</button>
        <button type="button" class="btn-logout-no" onclick="closeExternalLinkModal()">Cancel</button>
      </div>
    </div>
  </div>


  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    // Community carousel: initialize Bootstrap when available, with a small
    // native fallback so controls and swipe gestures still work if the CDN fails.
    (function () {
      const initCommunityCarousel = function () {
        const carousel = document.getElementById('communityCarousel');
        if (!carousel) return;

        const slides = Array.from(carousel.querySelectorAll('.carousel-inner > .carousel-item'));
        const indicators = Array.from(carousel.querySelectorAll('.carousel-indicators [data-bs-slide-to]'));
        const prevButton = carousel.querySelector('.carousel-control-prev');
        const nextButton = carousel.querySelector('.carousel-control-next');
        if (slides.length <= 1) {
          if (prevButton) prevButton.hidden = true;
          if (nextButton) nextButton.hidden = true;
          const indicatorBox = carousel.querySelector('.carousel-indicators');
          if (indicatorBox) indicatorBox.hidden = true;
          return;
        }

        // Bootstrap 5 handles cycling, keyboard navigation, indicators, and touch swipes.
        if (window.bootstrap && window.bootstrap.Carousel) {
          const instance = window.bootstrap.Carousel.getOrCreateInstance(carousel, {
            interval: 5000,
            keyboard: true,
            pause: 'hover',
            ride: 'carousel',
            touch: true,
            wrap: true
          });
          instance.cycle();
          return;
        }

        // Vanilla fallback if Bootstrap's JavaScript did not load.
        let currentIndex = Math.max(0, slides.findIndex(slide => slide.classList.contains('active')));
        let startX = null;
        let startY = null;
        let cycleTimer = null;

        const showSlide = function (index) {
          currentIndex = (index + slides.length) % slides.length;
          slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === currentIndex);
            slide.setAttribute('aria-hidden', i === currentIndex ? 'false' : 'true');
          });
          indicators.forEach((button, i) => {
            button.classList.toggle('active', i === currentIndex);
            if (i === currentIndex) button.setAttribute('aria-current', 'true');
            else button.removeAttribute('aria-current');
          });
        };
        const restartCycle = function () {
          if (cycleTimer) window.clearInterval(cycleTimer);
          cycleTimer = window.setInterval(() => showSlide(currentIndex + 1), 5000);
        };

        if (prevButton) prevButton.addEventListener('click', function (event) {
          event.preventDefault(); showSlide(currentIndex - 1); restartCycle();
        });
        if (nextButton) nextButton.addEventListener('click', function (event) {
          event.preventDefault(); showSlide(currentIndex + 1); restartCycle();
        });
        indicators.forEach(button => button.addEventListener('click', function (event) {
          event.preventDefault();
          const targetIndex = Number(button.getAttribute('data-bs-slide-to'));
          if (Number.isInteger(targetIndex)) showSlide(targetIndex);
          restartCycle();
        }));
        carousel.addEventListener('keydown', function (event) {
          if (event.key === 'ArrowLeft') { showSlide(currentIndex - 1); restartCycle(); }
          if (event.key === 'ArrowRight') { showSlide(currentIndex + 1); restartCycle(); }
        });
        carousel.addEventListener('touchstart', function (event) {
          if (!event.changedTouches || !event.changedTouches.length) return;
          startX = event.changedTouches[0].clientX;
          startY = event.changedTouches[0].clientY;
        }, { passive: true });
        carousel.addEventListener('touchend', function (event) {
          if (startX === null || !event.changedTouches || !event.changedTouches.length) return;
          const deltaX = event.changedTouches[0].clientX - startX;
          const deltaY = event.changedTouches[0].clientY - startY;
          if (Math.abs(deltaX) > 45 && Math.abs(deltaX) > Math.abs(deltaY)) {
            showSlide(currentIndex + (deltaX < 0 ? 1 : -1));
            restartCycle();
          }
          startX = null; startY = null;
        }, { passive: true });
        carousel.addEventListener('mouseenter', () => { if (cycleTimer) window.clearInterval(cycleTimer); });
        carousel.addEventListener('mouseleave', restartCycle);
        showSlide(currentIndex);
        restartCycle();
      };

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCommunityCarousel, { once: true });
      } else {
        initCommunityCarousel();
      }
    })();
  </script>

  <script>
    // =========================================================
    // Working Instructors Carousel Scroll Buttons
    // =========================================================
    function scrollInstructors(direction) {
      const track = document.getElementById('instructorTrack');
      const scrollAmount = 280; // card width + gap
      track.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }

    function openInstructorModal(name, imgSrc, role, certificates, bio) {
      document.getElementById('modalName').innerText = name;
      document.getElementById('modalImg').src = imgSrc;
      document.getElementById('modalRole').innerText = role;
      document.getElementById('modalBio').innerText = bio;

      const certList = document.getElementById('modalCertList');
      certList.innerHTML = '';
      certificates.forEach(cert => {
        const li = document.createElement('li');
        li.innerText = cert;
        certList.appendChild(li);
      });

      document.getElementById('instructorModal').style.display = 'flex';
    }

    function closeInstructorModal() {
      document.getElementById('instructorModal').style.display = 'none';
    }

    window.addEventListener('click', function(event) {
      const instructorModal = document.getElementById('instructorModal');
      if (event.target === instructorModal) {
        instructorModal.style.display = 'none';
      }
    });

    // =========================================================
    // Toast Notification System
    // =========================================================
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

    // =========================================================
    // Logout Confirmation Logic
    // =========================================================
    function openLogoutModal() {
      document.getElementById('logoutConfirmModal').style.display = 'flex';
    }

    function closeLogoutModal() {
      document.getElementById('logoutConfirmModal').style.display = 'none';
    }

    function executeLogout() {
      document.getElementById('landing-logout-form').submit();
    }

    // =========================================================
    // External Link Warning Modal Logic
    // =========================================================
    let targetExternalUrl = '';

    function confirmExternalLink(url) {
      targetExternalUrl = url;
      document.getElementById('externalLinkModal').style.display = 'flex';
    }

    function closeExternalLinkModal() {
      document.getElementById('externalLinkModal').style.display = 'none';
      targetExternalUrl = '';
    }

    document.getElementById('proceedExternalBtn').addEventListener('click', function() {
      if (targetExternalUrl) {
        window.open(targetExternalUrl, '_blank', 'noopener,noreferrer');
      }
      closeExternalLinkModal();
    });

    // =========================================================
    // Enforced Reading & Checkbox Modal Logic
    // =========================================================
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
      ['privacyModal', 'termsModal', 'faqModal', 'logoutConfirmModal', 'externalLinkModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
    });

    // =========================================================
    // Smooth Scroll & Active Nav Tracking
    // =========================================================
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
          e.preventDefault();
          const targetSection = document.getElementById(href.substring(1));
          if (targetSection) {
            targetSection.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
          }
        }
      });
    });

    window.addEventListener('scroll', function() {
      const sections = document.querySelectorAll('section[id], div[id="schedule"]');
      const navLinks = document.querySelectorAll('.nav-link');
      let current = '';

      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (window.pageYOffset >= sectionTop - 120) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
          link.classList.add('active');
        }
      });
    });

    // Hamburger Menu Toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');
    if (hamburger && navLinks) {
      hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
      });
    }

    // =========================================================
    // Profile Dropdown - desktop and mobile/touch support
    // =========================================================
    (function initLandingProfileDropdown() {
      function initialize() {
        const avatarBtn = document.getElementById('landingAvatarBtn');
        const dropdown = document.getElementById('landingProfileDropdown');
        if (!avatarBtn || !dropdown || avatarBtn.dataset.dropdownReady === 'true') return;
        avatarBtn.dataset.dropdownReady = 'true';

        function closeDropdown() {
          dropdown.classList.remove('open');
          avatarBtn.setAttribute('aria-expanded', 'false');
        }

        avatarBtn.addEventListener('click', function(event) {
          event.preventDefault();
          event.stopPropagation();
          const isOpen = !dropdown.classList.contains('open');
          dropdown.classList.toggle('open', isOpen);
          avatarBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        dropdown.addEventListener('click', function(event) {
          event.stopPropagation();
        });

        document.addEventListener('click', function(event) {
          if (!dropdown.contains(event.target) && !avatarBtn.contains(event.target)) {
            closeDropdown();
          }
        });

        document.addEventListener('keydown', function(event) {
          if (event.key === 'Escape') {
            closeDropdown();
            ['privacyModal', 'termsModal', 'faqModal', 'logoutConfirmModal', 'externalLinkModal'].forEach(id => {
              const modal = document.getElementById(id);
              if (modal) modal.style.display = 'none';
            });
          }
        });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, { once: true });
      } else {
        initialize();
      }
    })();
  </script>
</body>

</html>