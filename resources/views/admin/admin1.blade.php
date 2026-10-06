<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - System Overview</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('stylesheet/admin-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('stylesheet/certificates.css') }}">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Scripts -->
  <script
    src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js">
  </script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js">
  </script>
  <style>

    @keyframes mcToastIn {
  from { transform: translateX(30px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
  }

    /* ========================================================== */
    /* GENERAL & COMPONENT STYLES                                 */
    /* ========================================================== */
    .input-wrapper.input-with-suffix {
      display: flex;
      align-items: center;
    }

    .input-wrapper.input-with-suffix input {
      flex: 1;
      border: none;
      outline: none;
      background: transparent;
    }

    .input-suffix {
      padding-right: 10px;
      font-size: 0.85rem;
      color: #6c757d;
      font-weight: 500;
      user-select: none;
    }

    .assign-trainer-section {
      background: #f0faf3;
      border: 1px solid rgba(2, 86, 40, 0.15);
      border-radius: 10px;
      padding: 14px 16px;
      margin-top: 14px;
    }

    .assign-trainer-label {
      font-size: 12px;
      font-weight: 700;
      color: #025628;
      text-transform: uppercase;
      letter-spacing: .04em;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .assign-trainer-row {
      display: flex;
      gap: 8px;
      align-items: center;
      margin-bottom: 10px;
    }

    .assign-trainer-row select {
      flex: 1;
      border: 1px solid rgba(0, 0, 0, 0.12);
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 13px;
      font-family: inherit;
      outline: none;
      background: #fff;
    }

    .assign-trainer-row select:focus {
      border-color: #025628;
    }

    .btn-assign {
      background: #025628;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      white-space: nowrap;
    }

    .btn-assign:hover {
      background: #014d20;
    }

    .current-trainer-box {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, 0.08);
      border-radius: 8px;
      padding: 10px 12px;
    }

    .trainer-avatar-sm {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #e8f5e9;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 700;
      color: #025628;
      flex-shrink: 0;
    }

    .trainer-details {
      flex: 1;
    }

    .trainer-fullname {
      font-size: 13px;
      font-weight: 700;
      color: #1a1a1a;
    }

    .trainer-sub {
      font-size: 11px;
      color: #888;
    }

    .btn-remove-trainer {
      font-size: 11px;
      padding: 4px 10px;
      border-radius: 6px;
      background: #FCEBEB;
      color: #A32D2D;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-weight: 700;
      white-space: nowrap;
    }

    .no-trainer-box {
      font-size: 12px;
      color: #aaa;
      text-align: center;
      padding: 8px 0;
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }

    #courseModal .modal-content {
      max-height: 90vh !important;
      margin: 3vh auto !important;
      display: flex !important;
      flex-direction: column !important;
      overflow: hidden !important;
      max-width: 540px !important;
      width: 95% !important;
    }

    #courseModal #courseForm {
      overflow-y: auto !important;
      max-height: calc(90vh - 120px);
      padding-right: 6px;
    }

    .modal-actions-centered button {
      margin: 10px;
      padding: 8px 15px;
    }

    /* Inactive Status Badge (Red Styling) */
    .course-badge.inactive {
      background-color: #FCEBEB;
      color: #A32D2D;
      border: 1px solid rgba(163, 45, 45, 0.2);
    }

    /* Active Status Badge (Green Styling) */
    .course-badge.active {
      background-color: #e8f5e9;
      color: #025628;
      border: 1px solid rgba(2, 86, 40, 0.2);
    }

    /* ========================================================== */
    /* ANNOUNCEMENT MODAL STYLES                                  */
    /* ========================================================== */
    #announcementModal .modal-content.card {
      max-width: 520px !important;
      width: 95% !important;
      text-align: left !important;
    }

    #announcementModal .modal-body {
      display: flex !important;
      flex-direction: column !important;
      gap: 16px !important;
      padding: 20px !important;
      text-align: left !important;
    }

    #announcementModal .form-group {
      display: flex !important;
      flex-direction: column !important;
      align-items: stretch !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }

    #announcementModal .label-row {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      margin-bottom: 6px !important;
      width: 100% !important;
    }

    #announcementModal .label-row label,
    #announcementModal .form-group label {
      font-size: 13px !important;
      font-weight: 600 !important;
      color: #333 !important;
      margin: 0 !important;
      text-align: left !important;
    }

    #announcementModal .required {
      color: #a32d2d !important;
    }

    #announcementModal .char-counter {
      font-size: 11px !important;
      color: #888 !important;
    }

    #announcementModal .input-container {
      position: relative !important;
      display: flex !important;
      align-items: center !important;
      width: 100% !important;
      box-sizing: border-box !important;
    }

    #announcementModal .input-icon {
      position: absolute !important;
      left: 12px !important;
      color: #888 !important;
      font-size: 14px !important;
      pointer-events: none !important;
      z-index: 2 !important;
    }

    #announcementModal .input-container input[type="text"],
    #announcementModal .input-container select {
      width: 100% !important;
      height: 42px !important;
      padding: 0 12px 0 36px !important;
      border: 1px solid #ccc !important;
      border-radius: 6px !important;
      font-size: 14px !important;
      color: #333 !important;
      background-color: #fff !important;
      box-sizing: border-box !important;
      outline: none !important;
    }

    #announcementModal .textarea-container {
      align-items: flex-start !important;
    }

    #announcementModal .textarea-icon {
      top: 12px !important;
    }

    #announcementModal .textarea-container textarea {
      width: 100% !important;
      min-height: 95px !important;
      padding: 10px 12px 10px 36px !important;
      border: 1px solid #ccc !important;
      border-radius: 6px !important;
      font-size: 14px !important;
      font-family: inherit !important;
      color: #333 !important;
      background-color: #fff !important;
      box-sizing: border-box !important;
      resize: vertical !important;
      outline: none !important;
    }

    #announcementModal .input-container input:focus,
    #announcementModal .input-container select:focus,
    #announcementModal .input-container textarea:focus,
    #announcementModal .datetime-input:focus {
      border-color: #025628 !important;
      box-shadow: 0 0 0 2px rgba(2, 86, 40, 0.1) !important;
    }

    #announcementModal .datetime-container {
      position: relative !important;
      width: 100% !important;
    }

    #announcementModal .datetime-input {
      width: 100% !important;
      height: 42px !important;
      padding: 8px 12px !important;
      border: 1px solid #ccc !important;
      border-radius: 6px !important;
      font-size: 13px !important;
      font-family: inherit !important;
      color: #333 !important;
      background-color: #fff !important;
      box-sizing: border-box !important;
      outline: none !important;
      cursor: pointer !important;
    }

    #announcementModal input[type="datetime-local"]::-webkit-calendar-picker-indicator {
      cursor: pointer !important;
      opacity: 0.6;
      filter: invert(0.3);
    }

    #announcementModal input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
      opacity: 1;
    }

    #announcementModal .form-row {
      display: flex !important;
      gap: 16px !important;
      align-items: flex-end !important;
      width: 100% !important;
    }

    #announcementModal .flex-1 {
      flex: 1 !important;
    }

    #announcementModal .status-group {
      width: auto !important;
    }

    #announcementModal .checkbox-card {
      display: inline-flex !important;
      align-items: center !important;
      gap: 8px !important;
      height: 42px !important;
      padding: 0 14px !important;
      border: 1px solid #ccc !important;
      border-radius: 6px !important;
      background: #fdfdfd !important;
      cursor: pointer !important;
      box-sizing: border-box !important;
    }

    #announcementModal .checkbox-card input[type="checkbox"] {
      accent-color: #025628 !important;
      width: 16px !important;
      height: 16px !important;
      cursor: pointer !important;
    }

    #announcementModal .checkbox-text {
      font-size: 13px !important;
      color: #333 !important;
      font-weight: 500 !important;
      user-select: none !important;
    }

    #announcementModal .modal-footer {
      display: flex !important;
      justify-content: flex-end !important;
      align-items: center !important;
      gap: 10px !important;

      flex-shrink: 0 !important;

      margin-top: 0 !important;
      padding: 14px 20px !important;

      background: #ffffff !important;
      border-top: 1px solid #eee !important;

      box-sizing: border-box !important;
    }

    /* ========================================================== */
    /* ANNOUNCEMENT MODAL - RESPONSIVE HEIGHT & VISIBLE FOOTER   */
    /* ========================================================== */

    #announcementModal {
      align-items: center !important;
      justify-content: center !important;
      padding: 16px !important;
      box-sizing: border-box !important;
      overflow-y: auto !important;
      z-index: 1000 !important;
    }

    #announcementModal .modal-content.card {
      max-width: 520px !important;
      width: 95% !important;
      max-height: 90vh !important;

      display: flex !important;
      flex-direction: column !important;

      margin: auto !important;
      overflow: hidden !important;
      box-sizing: border-box !important;
    }

    /* Form becomes the scrollable area */
    #announcementModal #announcementForm {
      display: flex !important;
      flex-direction: column !important;

      flex: 1 1 auto !important;
      min-height: 0 !important;

      overflow-y: auto !important;
      overflow-x: hidden !important;

      max-height: none !important;

      padding: 20px !important;
      box-sizing: border-box !important;
    }

    /* Keep action buttons visible while scrolling */
    #announcementModal #announcementForm .modal-footer {

      background: #ffffff !important;

      flex-shrink: 0 !important;

      margin-top: 8px !important;
      padding: 14px 0 4px 0 !important;

      border-top: 1px solid #eee !important;
    }

    /* Make sure buttons remain visible */
    #announcementModal .modal-footer .btn-cancel,
    #announcementModal .modal-footer .btn-save-main {
      flex-shrink: 0 !important;
    }

    /* Smaller screens */
    @media (max-height: 700px) {
      #announcementModal .modal-content.card {
        max-height: 94vh !important;
      }

      #announcementModal #announcementForm {
        max-height: calc(94vh - 70px) !important;
      }
    }

    /* Very small screens */
    @media (max-width: 600px) {
      #announcementModal {
        padding: 10px !important;
      }

      #announcementModal .modal-content.card {
        width: 100% !important;
        max-height: 94vh !important;
      }

      #announcementModal #announcementForm {
        padding: 16px !important;
      }

      #announcementModal .form-row {
        flex-direction: column !important;
        align-items: stretch !important;
      }

      #announcementModal .status-group {
        width: 100% !important;
      }
    }

    /* ========================================================== */
    /* CERTIFICATES VIEW & TABLE STYLES                           */
    /* ========================================================== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: #025628;
      color: #ffffff;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
    }

    .stat-card.urgent {
      background: #025628;
    }

    .stat-card h3,
    .stat-card .stat-number {
      font-size: 28px;
      font-weight: 700;
      margin: 0 0 6px 0;
    }

    .stat-card p,
    .stat-card .stat-label {
      font-size: 13px;
      opacity: 0.9;
      margin: 0;
    }

    .filter-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 14px;
      margin-bottom: 16px;
    }

    .dropdown-group {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .filter-dropdown {
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 13px;
      background-color: #fff;
      color: #333;
      outline: none;
      cursor: pointer;
    }

    .filter-dropdown:focus {
      border-color: #025628;
    }

    .selection-group {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .custom-checkbox {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      color: #333;
      cursor: pointer;
      user-select: none;
    }

    .custom-checkbox input[type="checkbox"] {
      accent-color: #025628;
      cursor: pointer;
    }

    .table-outline {
      border: 1px solid #d4edda;
      border-radius: 12px;
      overflow: hidden;
      background: #ffffff;
    }

    .trainee-data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    .trainee-data-table thead {
      background-color: #f7ea72;
    }

    .trainee-data-table th {
      padding: 12px 16px;
      font-size: 14px;
      font-weight: 700;
      color: #1a1a1a;
      border-bottom: 1px solid #e0e0e0;
    }

    .trainee-data-table td {
      padding: 12px 16px;
      font-size: 13px;
      color: #333;
      border-bottom: 1px solid #f0f0f0;
      vertical-align: middle;
    }

    .trainee-data-table tbody tr:hover {
      background-color: #fcfcfc;
    }

    /* Badges */
    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-success {
      background-color: #e8f5e9;
      color: #025628;
    }

    .badge-warning {
      background-color: #fff8e1;
      color: #b78103;
    }

    /* Action Buttons Inside Table */
    .action-icons {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-icon {
      background: transparent;
      border: none;
      padding: 4px 6px;
      cursor: pointer;
      font-size: 15px;
      color: #025628;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.15s ease;
    }

    .btn-icon:hover {
      background-color: #f0faf3;
    }

    .btn-icon.btn-danger {
      color: #dc3545;
    }

    .btn-icon.btn-danger:hover {
      background-color: #fcebeb;
    }

    /* Bottom Action Footer */
    .action-footer {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 14px;
      margin-top: 20px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
      border: none;
      cursor: pointer;
      transition: opacity 0.2s ease;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .btn-primary {
      background-color: #025628;
      color: #ffffff;
    }

    .btn-secondary {
      background-color: #e8f5e9;
      color: #025628;
    }

    .text-btn-add {
      background: none;
      border: none;
      color: #025628;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .pill-btn-export {
      background-color: #025628;
      color: #fff;
      border: none;
      padding: 8px 20px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
    }

    /* ========================================================== */
    /* CERTIFICATE MODAL PREVIEWS & FORMS                         */
    /* ========================================================== */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.55);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1050;
      padding: 16px;
    }

    .modal-box-fixed {
      background: #ffffff;
      border-radius: 12px;
      max-width: 900px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-split {
      display: flex;
      flex-wrap: wrap;
    }

    .split-left-preview {
      flex: 1.2;
      min-width: 320px;
      padding: 24px;
      background-color: #f9fbf9;
    }

    .split-right-info {
      flex: 1;
      min-width: 280px;
      padding: 24px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
    }

    .border-right {
      border-right: 1px solid #ececec;
    }

    .modal-section-header,
    .modal-title {
      font-size: 18px;
      color: #025628;
      font-weight: 700;
      margin-top: 0;
      margin-bottom: 16px;
    }

    /* Certificate Preview Certificate Layout */
    .ui-cert-frame {
      box-sizing: border-box;
      width: 100%;
      max-width: 580px;
      aspect-ratio: 1 / 1;
      /* Keeps it square */
      border: 8px solid #025628;
      background: #ffffff;
      padding: 18px;
      margin: 0 auto;
      text-align: center;
      display: flex;
      flex-direction: column;
    }

    .ui-cert-inner {
      box-sizing: border-box;
      border: 1px dashed #025628;
      padding: 16px;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .cert-logos-header {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 16px;
      margin-bottom: 10px;
    }

    .cert-logo-img {
      height: 36px;
      object-fit: contain;
    }

    .cert-authority-text {
      font-size: 9px;
      font-weight: 700;
      line-height: 1.3;
      color: #333;
      margin: 6px 0;
    }

    .cert-title-primary {
      font-size: 16px;
      font-weight: 800;
      color: #025628;
      margin: 10px 0 4px 0;
      letter-spacing: 0.05em;
    }

    .cert-certify-line {
      font-size: 8px;
      letter-spacing: 0.08em;
      color: #555;
      margin-bottom: 4px;
    }

    .cert-recipient-name {
      font-size: 30px;
      font-weight: 700;
      text-decoration: underline;
      margin: 6px 0;
      color: #1a1a1a;
      text-transform: none !important;
      /* Disables any automatic text conversion */
    }

    .cert-training-msg {
      font-size: 8px;
      margin: 4px 0;
      color: #555;
    }

    .cert-course-name {
      font-size: 14px;
      font-weight: 700;
      color: #025628;
      margin: 4px 0 16px 0;
      text-transform: uppercase;
    }

    .cert-signatures {
      display: flex;
      justify-content: space-around;
      margin-top: 18px;
    }

    .sig-item {
      width: 110px;
      border-top: 1px solid #333;
      padding-top: 4px;
    }

    .sig-name {
      font-size: 8px;
      font-weight: 700;
      margin: 0;
    }

    .sig-rank {
      font-size: 7px;
      color: #666;
      margin: 0;
    }

    .cert-serial-footer {
      display: flex;
      justify-content: space-between;
      margin-top: 14px;
      font-size: 8px;
      font-weight: 600;
      color: #555;
    }

    .info-block {
      margin-bottom: 16px;
    }

    .info-label {
      font-size: 12px;
      color: #777;
      display: block;
      margin-bottom: 4px;
    }

    .info-value.grade-success {
      font-size: 15px;
      font-weight: 700;
      color: #025628;
      margin: 0;
    }

    .sig-list {
      list-style: none;
      padding: 0;
      margin: 0;
      font-size: 13px;
      color: #333;
    }

    .sig-list li {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 4px;
    }

    .sig-list i {
      color: #025628;
    }

    .modal-actions-container {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: auto;
      padding-top: 16px;
    }

    .btn-pdf {
      background-color: #dc3545;
      color: #fff;
    }

    .btn-print {
      background-color: #025628;
      color: #fff;
    }

    /* Modal Form Inputs */
    .ui-form-group {
      border: none;
      padding: 0;
      margin: 0 0 14px 0;
    }

    .form-label {
      font-size: 12px;
      font-weight: 700;
      color: #025628;
      display: block;
      margin-bottom: 6px;
    }

    .form-control,
    .ui-select {
      width: 100%;
      height: 38px;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 6px 10px;
      font-size: 13px;
      box-sizing: border-box;
      outline: none;
    }

    .form-control:focus,
    .ui-select:focus {
      border-color: #025628;
    }

    .resize-none {
      resize: none;
      height: 60px;
    }

    /* Utility Classes */
    .hidden {
      display: none !important;
    }

    .w-full {
      width: 100%;
    }

    .mt-2 {
      margin-top: 8px;
    }

    .mt-4 {
      margin-top: 16px;
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      border: 0;
    }

    .notification-item.notification-unread {
    background: #f7fbf8;
    }

    .notification-item.notification-unread .notification-item-title {
        font-weight: 800;
    }

    /* Print View */
    @media print {

      /* 1. Hide everything on the page */
      body * {
        visibility: hidden !important;
      }

      /* 2. Reset modal wrappers */
      html,
      body,
      .modal-overlay,
      .modal-box-fixed,
      .modal-split,
      .split-left-preview {
        visibility: hidden !important;
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
      }

      /* 3. Remove non-printable elements */
      .split-right-info,
      .modal-section-header,
      .modal-actions-container,
      .action-footer {
        display: none !important;
      }

      /* 4. Make ONLY the certificate visible */
      #printableCert,
      #printableCert * {
        visibility: visible !important;
      }

      /* 5. Force Square Dimensions & Center on Page */
      #printableCert {
        position: fixed !important;
        left: 50% !important;
        top: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 185mm !important;
        height: 185mm !important;
        aspect-ratio: 1 / 1 !important;
        box-sizing: border-box !important;
        margin: 0 auto !important;
        padding: 16px !important;
        box-shadow: none !important;
        page-break-inside: avoid !important;
      }

      /* 6. Distribute content evenly across the full height of the square */
      #printableCert .ui-cert-inner {
        height: 100% !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        padding: 24px 20px !important;
      }

      @page {
        size: auto;
        margin: 10mm;
      }
    }

  .notification-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  margin-right: 10px;
  }

  </style>
</head>

<body>

  <!-- ========================================================== -->
  <!-- TOPBAR                                                     -->
  <!-- ========================================================== -->
  <nav class="topbar">
    <div class="topbar-left">
      <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <a href="{{ route('admin1') }}" class="topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="logo"
          class="topbar-logo">
        <span>LEDIPO</span>
      </a>
    </div>

<div class="topbar-right">

  <!-- Notification Bell -->
  <div class="notification-wrapper" id="notificationWrapper">

    <button
      type="button"
      class="notification-btn"
      id="notificationBtn"
      aria-label="Open notifications"
      aria-expanded="false">

      <i class="fa-solid fa-bell"></i>

      @php
          // Admin can see all active announcements.
          $notificationAllAnnouncements = \App\Models\Announcement::where('is_active', 1)
              ->latest()
              ->get();

          $notificationReadIds = \DB::table('announcement_reads')
              ->where('user_id', \Auth::id())
              ->whereIn('announcement_id', $notificationAllAnnouncements->pluck('id'))
              ->pluck('announcement_id')
              ->toArray();

          /*
          |--------------------------------------------------------------------------
          | Notification Dropdown
          |--------------------------------------------------------------------------
          | Show:
          | 1. The latest 5 announcements
          | 2. PLUS any unread announcements that are older than those 5
          |
          | This ensures unread notifications represented by the badge
          | are still visible inside the notification dropdown.
          |--------------------------------------------------------------------------
          */

          $notificationLatest = $notificationAllAnnouncements->take(5);

          $notificationUnread = $notificationAllAnnouncements
              ->whereNotIn('id', $notificationReadIds);

          $notificationAnnouncements = $notificationLatest
              ->concat($notificationUnread)
              ->unique('id')
              ->sortByDesc('created_at')
              ->values();

          $notificationCount = $notificationUnread->count();

      @endphp

      @if($notificationCount > 0)
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
            {{ $notificationCount }} unread notification{{ $notificationCount != 1 ? 's' : '' }}
        </span>
        </div>

        <i class="fa-solid fa-bell notification-header-icon"></i>
      </div>

      <div class="notification-divider"></div>

      <div class="notification-list">

        @forelse($notificationAnnouncements as $announcement)

          <div
          class="notification-item {{ in_array($announcement->id, $notificationReadIds, true) ? '' : 'notification-unread' }}"
          data-announcement-id="{{ $announcement->id }}"
          role="button"
          tabindex="0"
          >

            <div class="notification-item-icon
              notification-type-{{ strtolower($announcement->type) }}">

              @if(strtolower($announcement->type) === 'urgent')
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
        <a href="{{ route('admin1') }}?view=announcements">
          View All Announcements
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

    </div>

  </div>

  <!-- Admin Profile -->
  <button class="avatar-btn" id="avatarBtn"
    aria-label="Open profile menu">AD</button>

      <div class="dropdown" id="dropdown">
        <div class="dropdown-header">
          <div class="dh-name">Administrator</div>
          <div class="dh-role">Admin</div>
        </div>

        <div class="dd-divider"></div>
        <a href="#" class="dd-item dd-logout"
          onclick="event.preventDefault(); openLogoutModal();">
          <i class="fa fa-right-from-bracket dd-icon"></i>
          Log out
        </a>
        <form id="logout-form" action="{{ route('Logout') }}" method="POST"
          style="display:none;">
          @csrf
        </form>
      </div>
    </div>
  </nav>

  <!-- Custom Alert Modal (replaces native alert()) -->
<div id="mcAlertModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:380px;">
    <div class="modal-header">
      <h3><i class="fa-solid fa-circle-info"></i> Notice</h3>
      <span class="close-modal" onclick="closeAlertModal()">&times;</span>
    </div>
    <div style="padding:24px; text-align:center;">
      <p id="mcAlertMessage" style="font-size:14px; color:#333; margin:0;"></p>
    </div>
    <div class="modal-actions-centered" style="text-align:center; padding-bottom:16px;">
      <button onclick="closeAlertModal()"
        style="background:#025628;color:#fff;border:none;border-radius:8px;padding:9px 24px;font-size:13px;font-weight:700;cursor:pointer;">
        OK
      </button>
    </div>
  </div>
</div>

<!-- Toast Notification Container -->
<div id="toastContainer" style="position:fixed; top:20px; right:20px; z-index:5000; display:flex; flex-direction:column; gap:10px; max-width:360px;"></div>

<!-- Custom Confirm Modal (replaces native confirm()) -->
<div id="mcConfirmModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:380px;">
    <div class="modal-header">
      <h3><i class="fa-solid fa-triangle-exclamation"></i> Confirm</h3>
      <span class="close-modal" onclick="closeConfirmModal()">&times;</span>
    </div>
    <div style="padding:24px; text-align:center;">
      <p id="mcConfirmMessage" style="font-size:14px; color:#333; margin:0;"></p>
    </div>
    <div class="modal-actions-centered" style="text-align:center; padding-bottom:16px; display:flex; justify-content:center; gap:10px;">
      <button onclick="closeConfirmModal()"
        style="background:#fff;color:#777;border:1px solid #ddd;border-radius:8px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;">
        Cancel
      </button>
      <button onclick="_confirmYes()"
        style="background:#A32D2D;color:#fff;border:none;border-radius:8px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;">
        Yes, Remove
      </button>
    </div>
  </div>
</div>

  <!-- Logout Confirmation Modal -->
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

  <!-- ========================================================== -->
  <!-- APP BODY & SIDEBAR                                         -->
  <!-- ========================================================== -->
  <div class="app-body">
    <div class="sidebar-overlay" id="overlay"></div>

    <aside class="sidebar" id="sidebar">
      <div class="sidebar-section-label">Menu</div>

      <a href="?view=overview"
        class="nav-item <?= !isset($_GET['view']) || $_GET['view'] === 'overview' ? 'active' : '' ?>"
        id="nav-overview"
        onclick="showView('overview'); setActive(this); return false;">
        <i class="fa fa-gauge-high nav-icon"></i>
        <span>Overview</span>
      </a>

      <div class="sidebar-section-label">Manage</div>

      <a href="?view=all-trainees"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'all-trainees' ? 'active' : '' ?>"
        id="nav-trainees"
        onclick="showView('all-trainees'); setActive(this); return false;">
        <i class="fa fa-user-graduate nav-icon"></i>
        <span>Trainees</span>
      </a>

      <a href="?view=all-trainers"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'all-trainers' ? 'active' : '' ?>"
        id="nav-trainers"
        onclick="showView('all-trainers'); setActive(this); return false;">
        <i class="fa fa-chalkboard-user nav-icon"></i>
        <span>Trainers</span>
      </a>

      <a href="?view=registrations"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'registrations' ? 'active' : '' ?>"
        id="nav-registrations"
        onclick="showView('registrations'); setActive(this); return false;">
        <i class="fa fa-clipboard-list nav-icon"></i>
        <span>Registrations</span>
      </a>

      <a href="?view=courses"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'courses' ? 'active' : '' ?>"
        id="nav-courses"
        onclick="showView('courses'); setActive(this); return false;">
        <i class="fa fa-book nav-icon"></i>
        <span>Courses</span>
      </a>

      <a href="?view=facilities"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'facilities' ? 'active' : '' ?>"
        id="nav-facilities"
        onclick="showView('facilities'); setActive(this); return false;">
        <i class="fa fa-building nav-icon"></i>
        <span>Facilities</span>
      </a>

      <div class="sidebar-section-label">System</div>

      <a href="?view=announcements"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'announcements' ? 'active' : '' ?>"
        id="nav-announcements"
        onclick="showView('announcements'); setActive(this); return false;">
        <i class="fa fa-bell nav-icon"></i>
        <span>Announcements</span>
      </a>

      <a href="/admin/landingpage" class="nav-item">
        <i class="fa fa-layer-group nav-icon"></i>
        <span>Landing Page</span>
      </a>

      <a href="?view=analytics"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'analytics' ? 'active' : '' ?>"
        id="nav-analytics"
        onclick="showView('analytics'); setActive(this); return false;">
        <i class="fa fa-chart-line nav-icon"></i>
        <span>Reports</span>
      </a>

      <a href="?view=settings"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'settings' ? 'active' : '' ?>"
        id="nav-settings"
        onclick="showView('settings'); setActive(this); return false;">
        <i class="fa fa-gear nav-icon"></i>
        <span>Settings</span>
      </a>

      <a href="?view=certificate"
        class="nav-item <?= isset($_GET['view']) && $_GET['view'] === 'certificate' ? 'active' : '' ?>"
        id="nav-certificate"
        onclick="showView('certificate'); setActive(this); return false;">
        <i class="fa fa-award nav-icon"></i>
        <span>Certificate</span>
      </a>
    </aside>

    <!-- ========================================================== -->
    <!-- MAIN CONTENT AREA                                          -->
    <!-- ========================================================== -->
    <main class="admin-main">
      <nav class="breadcrumb">
        <a href="#"
          onclick="showView('overview'); return false;">Home</a> /
        <span id="breadcrumb-current">System Overview</span>
      </nav>
      <h1 class="page-title" id="main-title">System Overview</h1>

      <!-- 1. OVERVIEW VIEW CONTAINER -->
      <div id="view-overview" style="width: 100%;">

        <!-- 2-COLUMN MAIN DASHBOARD GRID -->
        <div class="overview-dashboard-layout"
          style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; width: 100%;">

          <!-- LEFT COLUMN: Analytics Charts Section -->
          <div class="charts-section"
            style="display: flex; flex-direction: column; gap: 20px; min-width: 0;">

            <div class="charts-row"
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; align-items: stretch;">

              <!-- Trainees Chart Card -->
              <div class="card chart-card"
                style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-width: 0;">

                <!-- Card Header -->
                <div
                  style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                  <h3
                    style="margin: 0; font-size: 15px; font-weight: 700; color: #004d26; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-users" style="color: #004d26;"></i>
                    Trainees Enrollment
                  </h3>
                  <span
                    style="font-size: 10.5px; background: #e8f5e9; color: #004d26; padding: 3px 10px; border-radius: 12px; font-weight: 700; border: 1px solid #c8e6c9;">
                    Course Breakdown
                  </span>
                </div>

                <!-- Canvas Container -->
                <div
                  style="position: relative; height: 260px; width: 100%; min-width: 0;"
                  title="Click to enlarge chart">
                  <canvas id="traineeChart" style="cursor: pointer;"></canvas>
                </div>

                <!-- Card Footer -->
                <div
                  style="border-top: 1px solid #f2f2f2; margin-top: 14px; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-size: 11px; color: #888888;">
                    <i class="fa-solid fa-chart-simple"
                      style="margin-right: 4px; color: #004d26;"></i> Enrolled
                    distribution
                  </span>
                  <a href="#" class="view-more"
                    onclick="openExpandedChartModal('trainees'); return false;"
                    style="color: #004d26; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    View details <i class="fa-solid fa-arrow-right"
                      style="font-size: 11px;"></i>
                  </a>
                </div>

              </div>

              <!-- Courses Chart Card: Completion vs Active Status -->
              <div class="card chart-card"
                style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-width: 0;">

                <!-- Card Header -->
                <div
                  style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                  <h3
                    style="margin: 0; font-size: 15px; font-weight: 700; color: #004d26; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-chart-pie"
                      style="color: #004d26;"></i> Completion vs Active Status
                  </h3>
                  <span
                    style="font-size: 10.5px; background: #e8f5e9; color: #004d26; padding: 3px 10px; border-radius: 12px; font-weight: 700; border: 1px solid #c8e6c9;">
                    Overall Ratio
                  </span>
                </div>

                <!-- Canvas Container -->
                <div
                  style="position: relative; height: 260px; width: 100%; display: flex; justify-content: center; align-items: center; min-width: 0;"
                  title="Click to view enlarged chart">
                  <canvas id="courseChart" style="cursor: pointer;"></canvas>
                </div>

                <!-- Card Footer -->
                <div
                  style="border-top: 1px solid #f2f2f2; margin-top: 14px; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-size: 11px; color: #888888;">
                    <i class="fa-solid fa-graduation-cap"
                      style="margin-right: 4px; color: #004d26;"></i> Active vs
                    Graduated trainees
                  </span>
                  <a href="#" class="view-more"
                    onclick="openExpandedChartModal('courses'); return false;"
                    style="color: #004d26; font-weight: 700; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    View details <i class="fa-solid fa-arrow-right"
                      style="font-size: 11px;"></i>
                  </a>
                </div>

              </div>

            </div>

          </div>

          <!-- RIGHT COLUMN: Updates & Calendar Sidebar (Isolated Container) -->
          <div class="card updates-card"
            style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); width: 100%; box-sizing: border-box;">

            <h3
              style="margin-top: 0; margin-bottom: 14px; font-size: 15px; font-weight: 700; color: #004d26; display: flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-bell"></i> Updates
            </h3>

            <!-- Primary 3 Announcements -->
            <ul class="update-list" id="updateList"
              style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
              @forelse($announcements->take(3) as $ann)
                @php
                  $icon = match ($ann->type) {
                      'urgent' => 'fa-circle-exclamation',
                      'notice' => 'fa-bullhorn',
                      default => 'fa-bell',
                  };
                  $badgeColor = match ($ann->type) {
                      'urgent' => '#A32D2D',
                      'notice' => '#854F0B',
                      default => '#025628',
                  };
                  $bgColor = match ($ann->type) {
                      'urgent' => '#FCEBEB',
                      'notice' => '#FFF8E1',
                      default => '#E8F5E9',
                  };
                @endphp
                <li
                  style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; background: #fff; border: 1px solid #f0f0f0; border-radius: 8px;">
                  <div
                    style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i class="fa-solid {{ $icon }}"
                      style="color: {{ $badgeColor }}; font-size: 13px;"></i>
                  </div>

                  <div style="flex: 1; min-width: 0;">
                    <strong
                      style="font-size: 13px; color: #1a1a1a; display: block; margin-bottom: 2px;">{{ $ann->title }}</strong>
                    <small
                      style="color: #666; font-size: 12px; display: block; line-height: 1.4; margin-bottom: 4px;">{{ $ann->message }}</small>
                    <small
                      style="color: #aaa; font-size: 10px; display: inline-flex; align-items: center; gap: 4px;">
                      <i class="fa-regular fa-clock"
                        style="font-size: 9px;"></i>
                      {{ $ann->created_at->format('M j, Y h:i A') }}
                    </small>
                  </div>
                </li>
              @empty
                <li
                  style="text-align: center; color: #aaa; padding: 20px 0; font-size: 13px;">
                  <i class="fa-solid fa-bell-slash"
                    style="font-size: 20px; display: block; margin-bottom: 6px; color: #ccc;"></i>
                  No recent updates or announcements.
                </li>
              @endforelse
            </ul>

            <!-- Collapsible Extra Announcements -->
            @if ($announcements->count() > 3)
              <div id="extra-updates"
                style="display: none; margin-top: 12px;">
                <ul
                  style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                  @foreach ($announcements->slice(3) as $ann)
                    @php
                      $icon = match ($ann->type) {
                          'urgent' => 'fa-circle-exclamation',
                          'notice' => 'fa-bullhorn',
                          default => 'fa-bell',
                      };
                      $badgeColor = match ($ann->type) {
                          'urgent' => '#A32D2D',
                          'notice' => '#854F0B',
                          default => '#025628',
                      };
                      $bgColor = match ($ann->type) {
                          'urgent' => '#FCEBEB',
                          'notice' => '#FFF8E1',
                          default => '#E8F5E9',
                      };
                    @endphp
                    <li
                      style="display: flex; align-items: flex-start; gap: 12px; padding: 10px 12px; background: #fff; border: 1px solid #f0f0f0; border-radius: 8px;">
                      <div
                        style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <i class="fa-solid {{ $icon }}"
                          style="color: {{ $badgeColor }}; font-size: 13px;"></i>
                      </div>

                      <div style="flex: 1; min-width: 0;">
                        <strong
                          style="font-size: 13px; color: #1a1a1a; display: block; margin-bottom: 2px;">{{ $ann->title }}</strong>
                        <small
                          style="color: #666; font-size: 12px; display: block; line-height: 1.4; margin-bottom: 4px;">{{ $ann->message }}</small>
                        <small
                          style="color: #aaa; font-size: 10px;">{{ $ann->created_at->diffForHumans() }}</small>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>

              <div style="text-align: center; margin-top: 15px;">
                <button class="view-more-btn" id="viewMoreBtn"
                  onclick="toggleUpdates()"
                  style="background: none; border: none; color: #004d26; font-weight: 700; font-size: 12px; cursor: pointer;">
                  View More <i class="fa-solid fa-chevron-down"></i>
                </button>
              </div>
            @endif

            <!-- Sidebar Calendar Component -->
            <div class="sidebar-calendar" style="margin-top: 20px;">
              <div id="calendar"></div>
            </div>

          </div>

        </div>

      </div>

      <!-- EXPANDED CHART MODAL (PLACED OUTSIDE OVERVIEW VIEW TO PREVENT Z-INDEX OVERLAPS) -->
      <div id="expandedChartModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
        <div
          style="background: #ffffff; width: 100%; max-width: 920px; border-radius: 12px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; max-height: 92vh; overflow-y: auto;">

          <!-- Modal Header -->
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #ececec; padding-bottom: 12px;">
            <h3 id="expandedModalTitle"
              style="margin: 0; color: #004d26; font-size: 18px; font-weight: 700;">
              Analytics View
            </h3>
            <button onclick="closeExpandedChartModal()"
              style="background: none; border: none; font-size: 20px; color: #888888; cursor: pointer; padding: 4px;">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <!-- Dynamic KPI Cards Row -->
          <div id="modalKpiRow"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
            <div
              style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
              <span id="kpiLabel1"
                style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Total
                Trainees</span>
              <strong id="kpiVal1"
                style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">0</strong>
            </div>

            <div
              style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
              <span id="kpiLabel2"
                style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Top
                Performing Course</span>
              <strong id="kpiVal2"
                style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">N/A</strong>
            </div>

            <div
              style="background: #f8faf8; border: 1px solid #e0eae2; border-radius: 8px; padding: 12px; text-align: center;">
              <span id="kpiLabel3"
                style="font-size: 11px; color: #666666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">Active
                Modules</span>
              <strong id="kpiVal3"
                style="display: block; font-size: 20px; color: #004d26; font-weight: 800; margin-top: 2px;">0
                / 0</strong>
            </div>
          </div>

          <!-- Scaled Chart Canvas Container -->
          <div
            style="position: relative; height: 480px; width: 100%; display: flex; justify-content: center; align-items: center; margin-bottom: 16px;">
            <canvas id="expandedCanvas"></canvas>

            <!-- Centered Doughnut Text Overlay -->
            <div id="doughnutCenterText"
              style="position: absolute; top: 43%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none; display: none;">
              <span
                style="font-size: 42px; font-weight: 800; color: #004d26; display: block; line-height: 1;">33%</span>
              <span
                style="font-size: 13px; font-weight: 700; color: #666666; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; display: block;">Completed</span>
            </div>
          </div>

          <!-- Bottom Action Bar -->
          <div
            style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #ececec; padding-top: 14px; flex-wrap: wrap; gap: 10px;">
            <span style="font-size: 12px; color: #666666;">
              <i class="fa-solid fa-circle-info"
                style="margin-right: 4px; color: #004d26;"></i> Metrics reflect
              real-time active enrollments and module completion data.
            </span>

            <button onclick="window.print()"
              style="background: #ffffff; border: 1px solid #004d26; color: #004d26; padding: 7px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.15s ease;">
              <i class="fa-solid fa-print"></i> Print Report
            </button>
          </div>

        </div>
      </div>
      <!-- 2. ANALYTICS VIEW -->
      <div id="view-analytics" style="display: none;">
        <div class="analytics-header-row">
          <h3><i class="fa-solid fa-chart-line"></i> Detailed System Analytics
          </h3>
          <button class="btn-cancel"
            onclick="showView('overview'); setActive(document.getElementById('nav-overview'));">Back
            to Overview</button>
        </div>
        <div class="analytics-grid">
          <div class="card chart-card-full">
            <div class="card-header">
              <h4><i class="fa-solid fa-user-graduate"></i> Trainee Enrollment
                (Monthly Volume)</h4>
            </div>
            <div class="full-chart-container">
              <canvas id="traineeHistoryChart"></canvas>
            </div>
          </div>
          <div class="card chart-card-full">
            <div class="card-header">
              <h4><i class="fa-solid fa-book"></i> Course Growth (Yearly Trend)
              </h4>
            </div>
            <div class="full-chart-container">
              <canvas id="courseHistoryChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. TRAINEE MANAGEMENT VIEW -->
      <div id="view-trainee-list" style="display: none;">
        <!-- View A: Course Cards Grid -->
        <div id="course-cards-main-view">
          <!-- Top Filter & Search Control Bar -->
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
            <h3
              style="margin: 0; font-size: 15px; font-weight: 700; color: #025628; display: flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-chalkboard-user"
                style="color: #025628;"></i>
              Courses & Enrolled Trainees
            </h3>

            <!-- Live Search Field -->
            <div style="position: relative; width: 280px; max-width: 100%;">
              <i class="fa-solid fa-magnifying-glass"
                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; font-size: 13px;"></i>
              <input type="text" id="traineeCourseSearch"
                placeholder="Search course by title..."
                onkeyup="filterTraineeCards()"
                style="width: 100%; padding: 9px 12px 9px 34px; font-size: 12px; border: 1px solid #dcdcdc; border-radius: 8px; outline: none; box-sizing: border-box; background: #ffffff; transition: border-color 0.2s ease;">
            </div>
          </div>

          <!-- Course Cards Grid -->
          <div id="traineeCardsContainer"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px;">
            <!-- Empty Filter State -->
            <div id="noTraineeCardResults"
              style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 20px; font-size: 13px; display: none; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0;">
              <i class="fa-solid fa-magnifying-glass"
                style="font-size: 28px; display: block; margin-bottom: 10px; color: #ccc;"></i>
              No matching courses found.
            </div>

            @forelse($courses as $course)
              @php
                // CRITICAL FIX: Explicitly select user_tbls fields to avoid ID collisions from enrollment_tbls
                $enrolledTrainees = Illuminate\Support\Facades\DB::table(
                    'enrollment_tbls',
                )
                    ->join(
                        'user_tbls',
                        'user_tbls.id',
                        '=',
                        'enrollment_tbls.user_id',
                    )
                    ->where('enrollment_tbls.course_id', $course->id)
                    ->select(
                        'user_tbls.id as user_id',
                        'user_tbls.firstname',
                        'user_tbls.lastname',
                        'user_tbls.email',
                        'user_tbls.contact',
                        'user_tbls.id_number',
                        'user_tbls.status',
                        'user_tbls.remarks',
                        'user_tbls.role',
                        'user_tbls.created_at as member_since',
                    )
                    ->get();

                $enrolledCount = $enrolledTrainees->count();
                $remainingSlots = max(0, $course->slots - $enrolledCount);

                $percent =
                    $course->slots > 0
                        ? min(
                            100,
                            round(($enrolledCount / $course->slots) * 100),
                        )
                        : 0;
                $barColor =
                    $percent >= 100
                        ? '#A32D2D'
                        : ($percent >= 80
                            ? '#854F0B'
                            : '#025628');

                $assignedTrainer = $course->trainer;
                $trainerName = $assignedTrainer
                    ? $assignedTrainer->firstname .
                        ' ' .
                        $assignedTrainer->lastname
                    : 'Unassigned';
                $hasTrainer = !is_null($assignedTrainer);
              @endphp

              <div class="card trainee-course-card"
                data-title="{{ strtolower($course->title) }}"
                style="background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: background-color 0.15s ease;">
                <div>
                  <!-- Title & Enrollment Badge -->
                  <div
                    style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 12px;">
                    <h4
                      style="margin: 0; color: #111111; font-size: 14.5px; font-weight: 700; line-height: 1.3; display: flex; align-items: center; gap: 6px;">
                      <i class="fa-solid fa-book"
                        style="color: #025628; font-size: 14px;"></i>
                      {{ $course->title }}
                    </h4>
                    <span
                      style="font-size: 10.5px; background: #e8f5e9; color: #025628; padding: 3px 10px; border-radius: 12px; font-weight: 700; white-space: nowrap; border: 1px solid #c8e6c9;">
                      {{ $enrolledCount }} / {{ $course->slots }} Enrolled
                    </span>
                  </div>

                  <!-- Course Metadata -->
                  <div
                    style="display: flex; flex-direction: column; gap: 7px; font-size: 11.5px; color: #555;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <i class="fa-solid fa-calendar-day"
                        style="color: #888; width: 14px;"></i>
                      <span><strong>Duration:</strong> {{ $course->duration }}
                        {{ \Illuminate\Support\Str::plural('Day', $course->duration) }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <i class="fa-solid fa-user-tie"
                        style="color: #888; width: 14px;"></i>
                      <span><strong>Trainer:</strong>
                        <span
                          style="background: {{ $hasTrainer ? '#e8f5e9' : '#f0f0f0' }}; color: {{ $hasTrainer ? '#025628' : '#666' }}; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 10.5px; display: inline-block; margin-left: 2px;">
                          {{ $trainerName }}
                        </span>
                      </span>
                    </div>
                  </div>

                  <!-- Capacity Progress Bar -->
                  <div style="margin-top: 14px;">
                    <div
                      style="display: flex; justify-content: space-between; font-size: 10.5px; color: #666; font-weight: 600; margin-bottom: 5px;">
                      <span>Capacity Utilization</span>
                      <span
                        style="color: {{ $barColor }}; font-weight: 700;">{{ $percent }}%</span>
                    </div>
                    <div
                      style="background: #eeeeee; height: 6px; border-radius: 10px; overflow: hidden;">
                      <div
                        style="width: {{ $percent }}%; background: {{ $barColor }}; height: 100%; transition: width 0.3s ease;">
                      </div>
                    </div>
                  </div>
                </div>

                <hr
                  style="border: none; border-top: 1px solid #f2f2f2; margin: 0;">

                <!-- Action Button -->
                <button class="btn-all"
                  style="width: 100%; padding: 8px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;"
                  onclick="openFullCourseRoster('{{ addslashes($course->title) }}', {{ $enrolledTrainees->toJson() }})">
                  <i class="fa-solid fa-users"></i> View Trainees
                </button>
              </div>
            @empty
              <div
                style="grid-column: 1 / -1; text-align: center; color: #888; padding: 40px 20px; font-size: 13px; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0;">
                <i class="fa-solid fa-book-open"
                  style="font-size: 28px; display: block; margin-bottom: 10px; color: #ccc;"></i>
                No active courses found.
              </div>
            @endforelse
          </div>
        </div>

        <!-- View B: Full Page Roster View -->
        <div id="full-course-roster-view"
          style="display: none; background: #ffffff; border-radius: 10px; border: 1px solid #e0e0e0; padding: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02); overflow: hidden;">
          <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #ececec; background: #fafafa; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
              <button onclick="backToCourseCards()"
                style="background: #ffffff; border: 1px solid #dcdcdc; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; color: #444; display: flex; align-items: center; gap: 6px; transition: background 0.15s ease;">
                <i class="fa-solid fa-arrow-left"></i> Back to Courses
              </button>
              <h3 id="rosterCourseTitle"
                style="margin: 0; font-size: 15px; font-weight: 700; color: #025628;">
                Course Roster
              </h3>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
              <span id="rosterCountBadge"
                style="font-size: 11px; background: #e8f5e9; color: #025628; padding: 4px 12px; border-radius: 20px; font-weight: 700; border: 1px solid #c8e6c9;">
                0 Enrolled
              </span>
            </div>
          </div>

          <!-- Container for Trainee Roster Items -->
          <div id="fullRosterContainer"
            style="padding: 14px 18px; display: flex; flex-direction: column; gap: 8px; max-height: 550px; overflow-y: auto;">
            <!-- Populated via JavaScript -->
          </div>
        </div>
      </div>

      <!-- 4. TRAINER LIST VIEW -->
      <div id="view-trainer-list" style="display: none; width: 100%;">

        <!-- Header Controls Bar -->
        <div
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;">
          <!-- Search Input -->
          <div style="position: relative; width: 280px; max-width: 100%;">
            <i class="fa-solid fa-magnifying-glass"
              style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888; font-size: 13px;"></i>
            <input type="text" id="trainerSearchInput"
              onkeyup="filterTrainerList()"
              placeholder="Search trainer name, ID, or email..."
              style="width: 100%; padding: 8px 12px 8px 34px; font-size: 12.5px; border: 1px solid #dcdcdc; border-radius: 6px; outline: none; box-sizing: border-box; background: #ffffff; transition: border-color 0.2s ease;">
          </div>

          <!-- Add Trainer Button -->
          <button class="btn-save-main" onclick="openAddTrainerModal()"
            style="background: #025628; color: #ffffff; padding: 8px 16px; font-size: 12.5px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
            <i class="fa-solid fa-user-plus"></i> Add Trainer
          </button>
        </div>

        <!-- Main Card Container -->
        <div class="card list-card"
          style="background: #ffffff; border-radius: 10px; border: 1px solid #dcdcdc; padding: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.02); overflow: hidden;">

          <!-- Card Header -->
          <div class="card-header"
            style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 1px solid #ececec; background: #ffffff;">
            <h3
              style="margin: 0; font-size: 15px; font-weight: 700; color: #025628; display: flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-chalkboard-user"></i> Trainer Directory
            </h3>
            <span
              style="font-size: 11px; background: #e8f5e9; color: #025628; padding: 3px 12px; border-radius: 12px; font-weight: 700;">
              Total: {{ count($trainersList) }}
            </span>
          </div>

          <!-- List Body -->
          <div class="user-list-body" id="trainer-list-content"
            style="display: flex; flex-direction: column;">
            @forelse($trainersList as $trainer)
              @php
                $assignedCourse = isset($courses)
                    ? $courses->firstWhere('trainer_id', $trainer->id)
                    : null;
                $courseTitle = $assignedCourse
                    ? $assignedCourse->title
                    : 'No course assigned';
                $status = $trainer->status ?? 'Active';
                $isInactive = strtolower($status) === 'inactive';
                $fullName = trim(
                    ($trainer->firstname ?? '') .
                        ' ' .
                        ($trainer->lastname ?? ''),
                );
              @endphp

              <!-- Single Trainer Row -->
              <div class="user-item" data-user-id="{{ $trainer->id }}"
                style="display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-bottom: 1px solid #f2f2f2; gap: 16px; background: #ffffff; transition: background-color 0.15s ease;">

                <div
                  style="display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0;">
                  <!-- Avatar Icon -->
                  <div
                    style="width: 38px; height: 38px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; flex-shrink: 0;">
                    <i class="fa-solid fa-user-tie"
                      style="font-size: 15px;"></i>
                  </div>

                  <div class="user-info" style="min-width: 0; flex: 1;">
                    <!-- Primary Row: Name & Status Badge -->
                    <div
                      style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                      <strong class="user-name-text"
                        style="font-size: 13.5px; font-weight: 700; color: #111111; letter-spacing: 0.2px;">
                        {{ strtoupper($fullName) }}
                      </strong>

                      <!-- Status Badge -->
                      <span class="roster-status-badge"
                        data-email="{{ $trainer->email }}"
                        style="font-size: 10.5px; font-weight: 700; padding: 2px 9px; border-radius: 12px; background: {{ $isInactive ? '#FCEBEB' : '#e8f5e9' }}; color: {{ $isInactive ? '#A32D2D' : '#025628' }}; display: inline-block;">
                        {{ $status }}
                      </span>
                    </div>

                    <!-- Secondary Details Bar -->
                    <div
                      style="display: flex; align-items: center; gap: 16px; margin-top: 4px; font-size: 11.5px; color: #666666; flex-wrap: wrap;">
                      <!-- ID Number -->
                      <span class="user-id-text"
                        style="color: #025628; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-id-card"></i>
                        <span
                          class="user-id-value">{{ $trainer->id_number ?? 'N/A' }}</span>
                      </span>

                      <!-- Email -->
                      <span class="user-email-text"
                        style="display: inline-flex; align-items: center; gap: 6px; color: #666666;">
                        <i class="fa-regular fa-envelope"></i>
                        <span
                          class="user-email-value">{{ $trainer->email }}</span>
                      </span>

                      <!-- Assigned Course -->
                      <span class="user-course-text"
                        style="color: #025628; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-book-open"></i>
                        <span
                          class="user-course-value">{{ $courseTitle }}</span>
                      </span>

                      <!-- Contact Number -->
                      <span class="user-contact-text"
                        style="color: #888888; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-phone"></i>
                        <span
                          class="user-contact-value">{{ $trainer->contact ?? 'Not provided' }}</span>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- View Profile Action Button -->
                <button class="btn-view"
                  onclick="openUserModal(
            '{{ $trainer->id }}',
            '{{ addslashes(e($fullName)) }}',
            '{{ addslashes(e($trainer->email)) }}',
            'trainer',
            '{{ addslashes(e($status)) }}',
            '{{ addslashes(e($courseTitle)) }}',
            '{{ addslashes(e($trainer->contact ?? '')) }}',
            '{{ addslashes(e($trainer->id_number ?? '')) }}',
            '{{ \Carbon\Carbon::parse($trainer->created_at ?? now())->format('F Y') }}',
            '{{ addslashes(e($trainer->remarks ?? '')) }}'
          )"
                  style="background: #025628; color: #ffffff; border: none; padding: 7px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: background-color 0.15s ease; white-space: nowrap; flex-shrink: 0;">
                  View Profile
                </button>

              </div>
            @empty
              <div
                style="text-align: center; color: #888888; padding: 40px 20px; font-size: 13px;">
                <i class="fa-solid fa-user-slash"
                  style="font-size: 26px; display: block; margin-bottom: 8px; color: #cccccc;"></i>
                No trainers registered yet.
              </div>
            @endforelse
          </div>

        </div>
      </div>

      <!-- 5. REGISTRATIONS VIEW -->
      <div id="view-registrations" style="display: none;">
        <div class="card list-card">
          <div class="card-header"
            style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Submitted Registrations</h3>
            <a href="{{ route('admin.registrations.export') }}"
              class="btn-save-main"
              style="width:auto; padding:8px 16px; text-decoration:none;">
              <i class="fa-solid fa-file-excel"></i> Export to Excel
            </a>
          </div>
          <div class="user-list-body" id="registrations-list-content">
            @forelse($registrations as $reg)
              <div class="user-item">
                <i class="fa-solid fa-id-card profile-icon"></i>
                <div class="user-info">
                  <strong>{{ $reg->last_name }}, {{ $reg->first_name }}
                    {{ $reg->middle_name }}</strong><br>
                  <small>
                    ULI: {{ $reg->uli_number ?? '—' }} &nbsp;·&nbsp;
                    Course: {{ $reg->course_name }} &nbsp;·&nbsp;
                    {{ $reg->created_at->format('M j, Y g:i A') }}
                  </small>
                </div>
                <a href="{{ route('admin.registrations.show', $reg->id) }}"
                  target="_blank" class="btn-view">View</a>
                <a href="{{ route('admin.registrations.pdf', $reg->id) }}"
                  class="btn-view">
                  <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
              </div>
            @empty
              <div
                style="text-align:center; color:#aaa; padding:20px; font-size:13px;">
                <i class="fa-solid fa-clipboard-list"></i> No registrations
                found.
              </div>
            @endforelse
          </div>
          <div class="pagination-container">
            @if ($registrations->onFirstPage())
              <button class="page-btn" disabled><i
                  class="fa-solid fa-chevron-left"></i></button>
            @else
              <a href="{{ $registrations->previousPageUrl() }}"
                class="page-btn">
                <i class="fa-solid fa-chevron-left"></i>
              </a>
            @endif
            <div class="page-numbers">
              @for ($i = 1; $i <= $registrations->lastPage(); $i++)
                @if ($i == $registrations->currentPage())
                  <button class="page-btn active">{{ $i }}</button>
                @else
                  <a href="{{ $registrations->url($i) }}"
                    class="page-btn">{{ $i }}</a>
                @endif
              @endfor
            </div>
            @if ($registrations->hasMorePages())
              <a href="{{ $registrations->nextPageUrl() }}" class="page-btn">
                <i class="fa-solid fa-chevron-right"></i>
              </a>
            @else
              <button class="page-btn" disabled><i
                  class="fa-solid fa-chevron-right"></i></button>
            @endif
          </div>
        </div>
      </div>

      <!-- 6. FACILITIES VIEW -->
      <div id="view-facilities" style="display: none;">

        <!-- Top Control Bar (Search & Action Button) -->
        <div
          style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px; gap: 12px; flex-wrap: wrap;">
          <div class="input-wrapper"
            style="width: 320px; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 6px 12px; display: flex; align-items: center;">
            <i class="fa-solid fa-magnifying-glass"
              style="color: #888; margin-right: 8px;"></i>
            <input type="text" id="searchFacilityInput"
              placeholder="Search facility name or location..."
              onkeyup="filterFacilities()"
              style="border: none; outline: none; width: 100%; font-size: 13px;">
          </div>

          <button class="btn-save-main" onclick="openAddFacilityModal()"
            style="width: auto; padding: 9px 18px; font-size: 13px; white-space: nowrap;">
            <i class="fa-solid fa-plus"></i> Add New Facility
          </button>
        </div>

        <!-- Facility Grid -->
        <div class="facility-grid" id="facilityGrid"
          style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 16px;">

          @forelse ($facilities as $facility)
            <div class="card facility-card"
              data-name="{{ strtolower($facility->name) }}"
              data-location="{{ strtolower($facility->address) }}"
              style="display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 20px; background: #fff;">
              <div>
                <!-- Header & Status Badge -->
                <div
                  style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                  <div style="display: flex; gap: 12px; align-items: center;">
                    <div
                      style="width: 42px; height: 42px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; font-size: 18px; flex-shrink: 0;">
                      <i class="fa-solid fa-building-circle-check"></i>
                    </div>
                    <div>
                      <strong
                        style="font-size: 15px; color: #1a1a1a; display: block;">{{ $facility->name }}</strong>
                      <small style="color: #666; font-size: 12px;">
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $facility->address }}
                      </small>
                    </div>
                  </div>
                  <span class="course-badge active"
                    style="font-size: 10px; padding: 2px 8px; border-radius: 12px;">Active</span>
                </div>

                <hr
                  style="border: none; border-top: 1px solid #f0f0f0; margin: 12px 0;">

                <!-- Dynamic Multi-Course Badges -->
                <div style="margin-bottom: 16px;">
                  <div
                    style="font-size: 12px; color: #555; margin-bottom: 6px;">
                    <i class="fa-solid fa-book-open"
                      style="color: #854F0B;"></i>
                    <strong>Assigned Courses
                      ({{ $facility->courses->count() }})
                      :</strong>
                  </div>

                  @if ($facility->courses->isNotEmpty())
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                      @foreach ($facility->courses as $course)
                        <span
                          style="font-size: 11px; font-weight: 600; background: #e8f5e9; color: #025628; padding: 3px 10px; border-radius: 12px; border: 1px solid #c8e6c9;">
                          {{ $course->title }}
                        </span>
                      @endforeach
                    </div>
                  @else
                    <span
                      style="font-size: 11px; color: #9ca3af; font-style: italic;">No
                      courses assigned</span>
                  @endif
                </div>
              </div>

              <!-- Button with proper parameter alignment -->
              <button class="btn-all"
                onclick="openFacilityModal(
          {{ $facility->id }}, 
          '{{ addslashes($facility->name) }}', 
          '{{ addslashes($facility->address) }}', 
          {{ json_encode($facility->courses->pluck('id')) }}
        )"
                style="width: 100%;">
                <i class="fa-solid fa-pen-to-square"></i> Manage Facility
              </button>
            </div>
          @empty
            <div
              style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">
              <i class="fa-solid fa-building-circle-xmark"
                style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
              No facilities found. Click "Add New Facility" to create one.
            </div>
          @endforelse

        </div>

        <!-- Pagination -->
        <div class="pagination-container" style="margin-top: 20px;">
          <button class="page-btn prev" disabled><i
              class="fa-solid fa-chevron-left"></i></button>
          <div class="page-numbers">
            <button class="page-btn active">1</button>
          </div>
          <button class="page-btn next" disabled><i
              class="fa-solid fa-chevron-right"></i></button>
        </div>

      </div>

      <!-- 7. COURSES VIEW -->
      <div id="view-courses" style="display: none;">
        <!-- Top Control Bar (Search Bar & Add Button Aligned Right) -->
        <div
          style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px; gap: 12px; flex-wrap: wrap;">
          <div class="input-wrapper"
            style="width: 320px; background: #fff; border: 1px solid #d1d5db; border-radius: 8px; padding: 7px 12px; display: flex; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <i class="fa-solid fa-magnifying-glass"
              style="color: #9ca3af; margin-right: 8px; font-size: 13px;"></i>
            <input type="text" id="searchCourseInput"
              placeholder="Search course title or code..."
              onkeyup="filterCourses()"
              style="border: none; outline: none; width: 100%; font-size: 13px; color: #1f2937;">
          </div>

          <button class="btn-save-main" onclick="openAddCourseModal()"
            style="width: auto; padding: 9px 18px; font-size: 13px; white-space: nowrap; border-radius: 8px; cursor: pointer; font-weight: 600;">
            <i class="fa-solid fa-plus"></i> Add New Course
          </button>
        </div>

        <!-- Courses Grid -->
        <div class="courses-grid" id="coursesGrid">
          <!-- Live Search No Results Message -->
          <div id="noFilterResults"
            style="grid-column: 1 / -1; text-align: center; color: #9ca3af; padding: 50px 0; font-size: 13px; display: none;">
            <i class="fa-solid fa-magnifying-glass"
              style="font-size: 32px; display: block; margin-bottom: 10px; color: #d1d5db;"></i>
            No courses found.
          </div>

          @forelse($courses as $course)
            @php
              // 1. Trainer Information
              $trainerName = $course->trainer
                  ? trim(
                      $course->trainer->firstname .
                          ' ' .
                          $course->trainer->lastname,
                  )
                  : null;

              // 2. Facility Information
              $facilityName = $course->facility
                  ? $course->facility->name
                  : null;

              // 3. Enrollment Count & Calculations
              $enrolledCount =
                  $course->enrolled_count ?? ($course->enrollments_count ?? 0);
              $remainingSlots = max(0, $course->slots - $enrolledCount);

              // 4. Module & Quiz Counts
              $moduleCount =
                  $course->modules_count ??
                  Illuminate\Support\Facades\DB::table('modules')
                      ->where('course_id', $course->id)
                      ->count();
              $quizCount =
                  $course->quizzes_count ??
                  Illuminate\Support\Facades\DB::table('quizzes')
                      ->where('course_id', $course->id)
                      ->count();

              // 5. Dynamic Progress Bar Percentage & Color
              $percent =
                  $course->slots > 0
                      ? min(100, round(($enrolledCount / $course->slots) * 100))
                      : 0;
              $barColor =
                  $percent >= 100
                      ? '#dc2626'
                      : ($percent >= 80
                          ? '#d97706'
                          : '#025628');
            @endphp

            <div class="card course-card"
              style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.03); text-align: left;"
              data-title="{{ strtolower($course->title) }}"
              data-code="{{ strtolower($course->course_code) }}">

              <div>
                <!-- Top Bar: Course Code & Status Badge -->
                <div
                  style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                  <span
                    style="font-size: 11px; font-weight: 700; color: #4b5563; background: #f3f4f6; padding: 4px 9px; border-radius: 6px;">
                    <i class="fa-solid fa-barcode"
                      style="color: #6b7280; margin-right: 4px;"></i>
                    {{ $course->course_code ?? 'CRS-000' }}
                  </span>
                  <div
                    class="course-badge {{ strtolower($course->status) === 'active' ? 'active' : 'inactive' }}"
                    style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 12px;">
                    {{ ucfirst($course->status) }}
                  </div>
                </div>

                <!-- Title & Icon Side-by-Side Header -->
                <div
                  style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; text-align: left;">
                  <div
                    style="background: #e8f5e9; color: #025628; border-radius: 10px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa-solid fa-book" style="font-size: 18px;"></i>
                  </div>
                  <div style="flex-grow: 1; text-align: left;">
                    <h4
                      style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: #111827; line-height: 1.35; text-align: left;">
                      {{ $course->title }}
                    </h4>
                    <span
                      style="font-size: 12px; color: #6b7280; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; text-align: left;">
                      <i class="fa-regular fa-clock"
                        style="color: #9ca3af;"></i>
                      {{ $course->duration }}
                      Days Duration
                    </span>
                  </div>
                </div>

                <!-- Grouped Metadata Card (Trainer & Facility) -->
                <div
                  style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 10px 12px; margin-bottom: 12px; display: flex; flex-direction: column; gap: 6px; font-size: 12px; text-align: left;">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-chalkboard-user"
                      style="color: #025628; width: 14px; text-align: center;"></i>
                    <span
                      style="color: #6b7280; font-weight: 500;">Trainer:</span>
                    <strong
                      style="color: {{ $trainerName ? '#111827' : '#9ca3af' }}; font-weight: 600;">
                      {{ $trainerName ?? 'No trainer assigned' }}
                    </strong>
                  </div>

                  <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-building"
                      style="color: #025628; width: 14px; text-align: center;"></i>
                    <span
                      style="color: #6b7280; font-weight: 500;">Facility:</span>
                    <strong
                      style="color: {{ $facilityName ? '#111827' : '#9ca3af' }}; font-weight: 600;">
                      {{ $facilityName ?? 'No facility assigned' }}
                    </strong>
                  </div>
                </div>

                <!-- Modules & Quizzes Counter Badges -->
                <div
                  style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                  <span
                    style="background: #f3f4f6; color: #374151; font-size: 11px; font-weight: 600; padding: 4px 9px; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-cubes" style="color: #025628;"></i>
                    <span
                      id="course-module-count-{{ $course->id }}">{{ $moduleCount }}</span>
                    <span
                      id="course-module-label-{{ $course->id }}">{{ Illuminate\Support\Str::plural('Module', $moduleCount) }}</span>
                  </span>

                  <span
                    style="background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 600; padding: 4px 9px; border-radius: 6px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-clipboard-question"
                      style="color: #b45309;"></i>
                    <span
                      id="course-quiz-count-{{ $course->id }}">{{ $quizCount }}</span>
                    <span
                      id="course-quiz-label-{{ $course->id }}">{{ Illuminate\Support\Str::plural('Quiz', $quizCount) }}</span>
                  </span>
                </div>
              </div>

              <div>
                <!-- Capacity Progress Bar -->
                <div style="margin-bottom: 14px; text-align: left;">
                  <div
                    style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; font-weight: 600; color: #4b5563; margin-bottom: 6px;">
                    <span>Enrolled Capacity</span>
                    <span
                      style="color: {{ $barColor }}; font-weight: 700;">{{ $enrolledCount }}
                      / {{ $course->slots }} Enrolled</span>
                  </div>
                  <div class="progress-container"
                    style="background: #e5e7eb; height: 6px; border-radius: 10px; overflow: hidden;">
                    <div class="progress-bar"
                      style="width: {{ $percent }}%; background: {{ $barColor }}; height: 100%; border-radius: 10px; transition: width 0.3s ease;">
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 8px;">
                  <button class="btn-all"
                    style="flex: 1; border: 1px solid #d1d5db; background: #ffffff; color: #374151; font-weight: 600; padding: 8px; border-radius: 8px; font-size: 12px; cursor: pointer;"
                    onclick="openCourseModal(
                {{ $course->id }},
                '{{ addslashes($course->course_code) }}',
                '{{ addslashes($course->title) }}',
                '{{ addslashes($course->duration) }}',
                {{ $course->slots }},
                {{ $course->trainer_id ?? 'null' }},
                '{{ addslashes($trainerName ?? '') }}',
                '{{ addslashes($course->training_id ?? '') }}',
                '{{ $course->status }}'
              )">
                    Course Details
                  </button>

                  <button class="btn-all"
                    style="flex: 1; border: none; background: #025628; color: #ffffff; font-weight: 600; padding: 8px; border-radius: 8px; font-size: 12px; cursor: pointer;"
                    onclick="openContentModal({{ $course->id }}, '{{ addslashes($course->title) }}')">
                    <i class="fa-solid fa-layer-group"></i> Modules
                  </button>
                </div>
              </div>

            </div>
          @empty
            <div
              style="grid-column: 1 / -1; text-align: center; color: #9ca3af; padding: 50px 0; font-size: 13px;">
              <i class="fa-solid fa-book-open"
                style="font-size: 32px; display: block; margin-bottom: 10px; color: #d1d5db;"></i>
              No courses found.
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if (
            $courses instanceof \Illuminate\Pagination\LengthAwarePaginator &&
                $courses->hasPages())
          <div class="pagination-container"
            style="margin-top: 24px; display: flex; justify-content: center; align-items: center; gap: 6px;">
            @if ($courses->onFirstPage())
              <button class="page-btn" disabled
                style="opacity: 0.5; cursor: not-allowed;"><i
                  class="fa-solid fa-chevron-left"></i></button>
            @else
              <a href="{{ $courses->previousPageUrl() }}&view=courses"
                onclick="setActive(document.getElementById('nav-courses'))"
                class="page-btn">
                <i class="fa-solid fa-chevron-left"></i>
              </a>
            @endif

            <div class="page-numbers" style="display: flex; gap: 4px;">
              @for ($i = 1; $i <= $courses->lastPage(); $i++)
                @if ($i == $courses->currentPage())
                  <button
                    class="page-btn active">{{ $i }}</button>
                @else
                  <a href="{{ $courses->url($i) }}&view=courses"
                    onclick="setActive(document.getElementById('nav-courses'))"
                    class="page-btn">{{ $i }}</a>
                @endif
              @endfor
            </div>

            @if ($courses->hasMorePages())
              <a href="{{ $courses->nextPageUrl() }}&view=courses"
                onclick="setActive(document.getElementById('nav-courses'))"
                class="page-btn">
                <i class="fa-solid fa-chevron-right"></i>
              </a>
            @else
              <button class="page-btn" disabled
                style="opacity: 0.5; cursor: not-allowed;"><i
                  class="fa-solid fa-chevron-right"></i></button>
            @endif
          </div>
        @endif
      </div>

      <!-- 8. ANNOUNCEMENTS VIEW -->
      <div id="view-announcements" style="display: none;">
        <!-- Header Bar -->
        <div class="view-header"
          style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
          <div>
            <span style="font-size: 13px; color: #666;">Manage system updates,
              reminders, and public notices.</span>
          </div>

          <button class="btn-save-main" onclick="openAnnouncementModal()"
            style="width: auto; padding: 8px 16px; font-weight: 600;">
            <i class="fa-solid fa-plus"></i> Add Announcement
          </button>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="filter-toolbar"
          style="display: flex; align-items: center; justify-content: space-between; gap: 12px; background: #fff; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 16px;">
          <!-- Search Input -->
          <div style="position: relative; flex: 1; max-width: 320px;">
            <i class="fa-solid fa-magnifying-glass"
              style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px;"></i>
            <input type="text" id="annSearchInput"
              placeholder="Search announcements..."
              onkeyup="filterAnnouncements()"
              style="width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; outline: none; background: #f8fafc;">
          </div>

          <!-- Dropdown Filters -->
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 6px;">
              <label for="annTypeFilter"
                style="font-size: 12px; font-weight: 600; color: #64748b;">Type:</label>
              <select id="annTypeFilter" onchange="filterAnnouncements()"
                style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; color: #1e293b; background: #fff; cursor: pointer; outline: none;">
                <option value="">All Types</option>
                <option value="urgent">Urgent</option>
                <option value="notice">Notice</option>
                <option value="reminder">Reminder</option>
              </select>
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
              <label for="annStatusFilter"
                style="font-size: 12px; font-weight: 600; color: #64748b;">Status:</label>
              <select id="annStatusFilter" onchange="filterAnnouncements()"
                style="padding: 7px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; color: #1e293b; background: #fff; cursor: pointer; outline: none;">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="draft">Draft</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Announcement List Card -->
        <div class="card list-card"
          style="display: flex; flex-direction: column; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
          @forelse($announcements as $ann)
            @php
              $typeConfig = match ($ann->type) {
                  'urgent' => [
                      'badge' => '#A32D2D',
                      'bg' => '#FCEBEB',
                      'icon' => 'fa-triangle-exclamation',
                  ],
                  'notice' => [
                      'badge' => '#854F0B',
                      'bg' => '#FFF8E1',
                      'icon' => 'fa-circle-info',
                  ],
                  default => [
                      'badge' => '#025628',
                      'bg' => '#E8F5E9',
                      'icon' => 'fa-bell',
                  ],
              };
            @endphp

            <div class="user-item" data-type="{{ strtolower($ann->type) }}"
              data-status="{{ $ann->is_active ? 'active' : 'draft' }}"
              style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">

              <!-- Icon -->
              <div
                style="width: 38px; height: 38px; border-radius: 50%; background: {{ $typeConfig['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fa-solid {{ $typeConfig['icon'] }}"
                  style="color: {{ $typeConfig['badge'] }}; font-size: 14px;"></i>
              </div>

              <!-- Content Body -->
              <div class="user-info" style="flex: 1; min-width: 0;">
                <div
                  style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                  <strong class="ann-title-text"
                    style="font-size: 14px; font-weight: 600; color: #0f172a;">{{ $ann->title }}</strong>

                  <span
                    style="padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; background: {{ $typeConfig['bg'] }}; color: {{ $typeConfig['badge'] }};">
                    {{ ucfirst($ann->type) }}
                  </span>

                  @if ($ann->is_active)
                    <span
                      style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: #e6f4ea; color: #137333;">
                      Active
                    </span>
                  @else
                    <span
                      style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: #f1f3f4; color: #5f6368;">
                      Draft
                    </span>
                  @endif
                </div>

                <p class="ann-msg-text"
                  style="margin: 0 0 10px 0; font-size: 13px; color: #475569; line-height: 1.5;">
                  {{ $ann->message }}
                </p>

                <!-- Timestamps Metadata Tags -->
                <div
                  style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; font-size: 11px; color: #64748b;">
                  <span title="Created At">
                    <i class="fa-regular fa-clock"
                      style="color: #94a3b8;"></i>
                    <strong>Created:</strong>
                    {{ $ann->created_at->format('M j, Y h:i A') }}
                  </span>

                  <span title="Publish Date">
                    <i class="fa-solid fa-calendar-check"
                      style="color: {{ $ann->publish_at ? '#025628' : '#94a3b8' }};"></i>
                    <strong>Publishes:</strong>
                    {{ $ann->publish_at ? $ann->publish_at->format('M j, Y h:i A') : 'Immediately' }}
                  </span>

                  <span title="Expiration Date">
                    <i class="fa-solid fa-calendar-xmark"
                      style="color: {{ $ann->expires_at ? '#A32D2D' : '#94a3b8' }};"></i>
                    <strong>Expires:</strong>
                    {{ $ann->expires_at ? $ann->expires_at->format('M j, Y h:i A') : 'Never' }}
                  </span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div
                style="display: flex; gap: 8px; flex-shrink: 0; align-self: center;">
                <button class="btn-view" data-id="{{ $ann->id }}"
                  data-title="{{ $ann->title }}"
                  data-message="{{ $ann->message }}"
                  data-type="{{ $ann->type }}"
                  data-audience="{{ $ann->audience }}"
                  data-active="{{ $ann->is_active ? '1' : '0' }}"
                  data-publish-at="{{ $ann->publish_at ? $ann->publish_at->format('Y-m-d H:i:s') : '' }}"
                  data-expires-at="{{ $ann->expires_at ? $ann->expires_at->format('Y-m-d H:i:s') : '' }}"
                  onclick="handleEditAnnouncement(this)"
                  style="padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #025628; background: #025628; color: #fff;">
                  Edit
                </button>

                <button
                  onclick="deleteAnnouncement({{ $ann->id }}, this)"
                  style="padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #fecdd3; background: #fff1f2; color: #9f1239;">
                  Delete
                </button>
              </div>
            </div>
          @empty
            <div
              style="text-align: center; color: #64748b; padding: 48px 20px; font-size: 13px;">
              <i class="fa-solid fa-bell-slash"
                style="font-size: 28px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
              <strong>No announcements found.</strong>
              <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 12px;">
                Try adjusting your filters or click "+ Add Announcement" to
                create one.</p>
            </div>
          @endforelse
        </div>

        @if ($announcements->total() > 0)
          <div class="pagination-container"
            style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
            @if ($announcements->onFirstPage())
              <button class="page-btn" disabled><i
                  class="fa-solid fa-chevron-left"></i></button>
            @else
              <a href="{{ $announcements->previousPageUrl() }}&view=announcements"
                class="page-btn"><i class="fa-solid fa-chevron-left"></i></a>
            @endif

            <div class="page-numbers" style="display: flex; gap: 4px;">
              @for ($i = 1; $i <= $announcements->lastPage(); $i++)
                @if ($i == $announcements->currentPage())
                  <button
                    class="page-btn active">{{ $i }}</button>
                @else
                  <a href="{{ $announcements->url($i) }}&view=announcements"
                    class="page-btn">{{ $i }}</a>
                @endif
              @endfor
            </div>

            @if ($announcements->hasMorePages())
              <a href="{{ $announcements->nextPageUrl() }}&view=announcements"
                class="page-btn"><i class="fa-solid fa-chevron-right"></i></a>
            @else
              <button class="page-btn" disabled><i
                  class="fa-solid fa-chevron-right"></i></button>
            @endif
          </div>
        @endif
      </div>

      <!-- 9. SETTINGS VIEW -->
      <div id="view-settings" style="display: none;">
        <div class="card settings-card">
          <h3>General Settings</h3>
          <div class="settings-row">
            <div class="settings-info">
              <strong>Admin Email</strong>
              <p>The primary email for system recovery and alerts.</p>
            </div>
            <input type="email" value="ledipoadmin@gmail.com"
              class="settings-input">
          </div>
          <hr class="settings-divider">
          <h3>Security</h3>
          <div class="settings-row">
            <div class="settings-info">
              <strong>Password</strong>
              <p>Last changed: 2 months ago.</p>
            </div>
            <button class="btn-view">Update Password</button>
          </div>
          <div class="settings-row">
            <div class="settings-info">
              <strong>Database Backup</strong>
              <p>Download a copy of all trainees, trainers, and courses.</p>
            </div>
            <button class="btn-all"
              style="width: auto; padding: 10px 20px;">Backup Now</button>
          </div>
        </div>
      </div>
      <!-- 10. CERTIFICATE VIEW -->
      <div id="view-certificate" class="view-panel" style="display: none;">

        <!-- 1. Statistics Cards -->
        <section class="stats-grid" aria-label="Certificate Overview">
          <div class="stat-card">
            <h3 class="stat-number">
              {{ isset($certificates) ? str_pad($certificates->count(), 2, '0', STR_PAD_LEFT) : '00' }}
            </h3>
            <p class="stat-label">Certificates Issued</p>
          </div>
          <div class="stat-card">
            <h3 class="stat-number">
              {{ isset($certificates) ? str_pad($certificates->where('status', 'Pending')->count(), 2, '0', STR_PAD_LEFT) : '00' }}
            </h3>
            <p class="stat-label">Pending Claim</p>
          </div>
          <div class="stat-card">
            <h3 class="stat-number">
              {{ isset($certificates) ? str_pad($certificates->where('status', 'Claimed')->count(), 2, '0', STR_PAD_LEFT) : '00' }}
            </h3>
            <p class="stat-label">Monthly Graduates</p>
          </div>
          <div class="stat-card urgent">
            <h3 class="stat-number">
              {{ isset($certificates) ? str_pad($certificates->count(), 2, '0', STR_PAD_LEFT) : '00' }}
            </h3>
            <p class="stat-label">Archive Size</p>
          </div>
        </section>

        <!-- 2. Search, Filters & Batch Selection -->
        <div class="filter-controls">
          <div class="dropdown-group">
            <!-- Live Search Bar -->
            <div class="input-wrapper"
              style="position: relative; min-width: 240px;">
              <i class="fas fa-search"
                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #888; font-size: 12px;"
                aria-hidden="true"></i>
              <input type="text" id="certSearchInput"
                placeholder="Search trainee or control no..."
                onkeyup="filterCertTable()" class="filter-dropdown"
                style="padding-left: 30px; width: 100%; box-sizing: border-box;">
            </div>

            <!-- Dropdown Filters -->
            <label for="filterCourse" class="sr-only">Filter by Course</label>
            <select id="filterCourse" class="filter-dropdown"
              onchange="filterCertTable()" aria-label="Filter by Course">
              <option value="">All Courses</option>
              @if (isset($allCourses))
                @foreach ($allCourses as $c)
                  <option value="{{ strtolower($c->title) }}">
                    {{ $c->title }}</option>
                @endforeach
              @endif
            </select>

            <label for="filterMonth" class="sr-only">Filter by Month</label>
            <select id="filterMonth" class="filter-dropdown"
              onchange="filterCertTable()" aria-label="Filter by Month">
              <option value="">All Months</option>
              <option value="january">January</option>
              <option value="february">February</option>
              <option value="march">March</option>
              <option value="april">April</option>
              <option value="may">May</option>
              <option value="june">June</option>
              <option value="july">July</option>
              <option value="august">August</option>
              <option value="september">September</option>
              <option value="october">October</option>
              <option value="november">November</option>
              <option value="december">December</option>
            </select>

            <label for="filterStatus" class="sr-only">Filter by Status</label>
            <select id="filterStatus" class="filter-dropdown"
              onchange="filterCertTable()" aria-label="Filter by Status">
              <option value="">All Statuses</option>
              <option value="claimed">Claimed</option>
              <option value="pending">Pending</option>
            </select>
          </div>

          <div class="selection-group">
            <label class="custom-checkbox" for="toggleMultiple">
              <input type="checkbox" id="toggleMultiple"
                onchange="toggleMultiSelectMode(this)">
              <span>Select Multiple</span>
            </label>
            <label class="custom-checkbox" for="selectAll">
              <input type="checkbox" id="selectAll"
                onchange="toggleSelectAllRows(this)">
              <span>Select All</span>
            </label>
          </div>
        </div>

        <!-- 3. Trainee Certificate Records Table -->
        <div class="table-responsive table-outline">
          <table class="trainee-data-table" id="certTable">
            <thead>
              <tr>
                <th class="select-col hidden" scope="col">
                  <span class="sr-only">Batch Selection</span>
                  <i class="fas fa-check-square" aria-hidden="true"></i>
                </th>
                <th scope="col">Full Name</th>
                <th scope="col">Course</th>
                <th scope="col">Date Issued</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="certTableBody">
              @forelse($certificates ?? [] as $cert)
                @php
                  $fullName = ucwords(
                      strtolower(
                          trim(
                              ($cert->user->firstname ?? '') .
                                  ' ' .
                                  ($cert->user->lastname ?? ''),
                          ),
                      ),
                  );
                  if (empty($fullName)) {
                      $fullName = 'Registered Trainee';
                  }
                  $courseTitle = $cert->course->title ?? 'General Program';
                  $issueDate = \Carbon\Carbon::parse($cert->issue_date)->format(
                      'F j, Y',
                  );
                  $isClaimed = strtolower($cert->status) === 'claimed';
                @endphp
                <tr data-cert-id="{{ $cert->id }}"
                  data-cert-no="{{ $cert->certificate_no }}">
                  <td class="select-col hidden">
                    <input type="checkbox" class="row-checkbox"
                      value="{{ $cert->id }}"
                      aria-label="Select {{ $fullName }}">
                  </td>
                  <td class="font-medium">{{ $fullName }}</td>
                  <td>{{ $courseTitle }}</td>
                  <td>{{ $issueDate }}</td>
                  <td>
                    <span
                      class="badge badge-toggle {{ $isClaimed ? 'badge-success' : 'badge-warning' }}"
                      onclick="toggleCertStatus(this, {{ $cert->id }})"
                      style="cursor: pointer;" title="Click to toggle status">
                      <i
                        class="fas {{ $isClaimed ? 'fa-check-circle' : 'fa-clock' }}"></i>
                      <span
                        class="status-label">{{ ucfirst($cert->status) }}</span>
                    </span>
                  </td>
                  <td class="action-icons">
                    <button type="button" class="btn-icon"
                      title="View Certificate"
                      onclick="openCertModal('{{ addslashes($fullName) }}', '{{ addslashes($courseTitle) }}', '{{ $cert->certificate_no }}', '{{ $issueDate }}', '{{ $cert->status }}', '{{ $cert->grade ?? '94%' }}', '{{ $cert->document_type ?? 'completion' }}')">
                      <i class="fas fa-eye" aria-hidden="true"></i>
                      <span class="sr-only">View Certificate</span>
                    </button>
                    <button type="button" class="btn-icon"
                      title="Edit Record"
                      onclick="openEditCertModal('{{ $cert->certificate_no }}')">
                      <i class="fas fa-edit" aria-hidden="true"></i>
                      <span class="sr-only">Edit Record</span>
                    </button>
                    <button type="button" class="btn-icon btn-danger"
                      title="Delete Record"
                      onclick="deleteCert(this, {{ $cert->id }})">
                      <i class="fas fa-trash-alt" aria-hidden="true"></i>
                      <span class="sr-only">Delete Record</span>
                    </button>
                  </td>
                </tr>
              @empty
                <tr id="emptyCertRow">
                  <td colspan="6"
                    style="text-align: center; color: #888; padding: 36px 16px; font-size: 13px;">
                    <i class="fas fa-certificate"
                      style="font-size: 26px; display: block; margin-bottom: 8px; color: #ccc;"
                      aria-hidden="true"></i>
                    No certificates issued yet. Click "Issue New Certificate" to
                    create one.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>

          <!-- Empty Results Fallback -->
          <div id="noCertResults"
            style="display: none; text-align: center; color: #888; padding: 32px 16px; font-size: 13px;">
            <i class="fas fa-award"
              style="font-size: 24px; display: block; margin-bottom: 8px; color: #ccc;"
              aria-hidden="true"></i>
            No matching certificate records found.
          </div>
        </div>

        <!-- 4. Action Footer -->
        <footer class="action-footer">
          <button type="button" class="btn btn-primary"
            onclick="openAddModal()">
            <i class="fas fa-plus-square" aria-hidden="true"></i>
            <span>Issue New Certificate</span>
          </button>
          <button type="button" class="btn btn-secondary"
            onclick="exportCertificates()">
            <i class="fas fa-file-export" aria-hidden="true"></i>
            <span>Export Certificates</span>
          </button>
        </footer>

      </div>
    </main>
  </div>

  <!-- ========================================================== -->
  <!-- MODALS SECTION                                             -->
  <!-- ========================================================== -->

  <!-- ==========================================
     1. CERTIFICATE VIEW MODAL (DYNAMIC DATA)
========================================== -->
  <div id="certificateModal" class="modal-overlay" role="dialog"
    aria-modal="true" aria-labelledby="viewModalTitle"
    style="display: none;">
    <div class="modal-box-fixed">
      <div class="modal-split">

        <!-- Left: Certificate Preview Frame -->
        <div class="split-left-preview">
          <h3 class="modal-section-header">Certificate Preview</h3>

          <div class="ui-cert-frame" id="printableCert">
            <div class="ui-cert-inner">
              <header class="cert-logos-header">
                <img src="{{ asset('images/logo.png') }}"
                  alt="City Government Logo" class="cert-logo-img">
                <img src="{{ asset('images/tesda.png') }}" alt="TESDA Logo"
                  class="cert-logo-img">
                <img src="{{ asset('images/logo_ledipo.png') }}"
                  alt="LEDIPO Logo" class="cert-logo-img">
              </header>

              <p class="cert-authority-text">
                TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
                CITY GOVERNMENT OF DASMARIÑAS — LEDIPO
              </p>

              <h1 class="cert-title-primary" id="vDocTitle">CERTIFICATE OF
                COMPLETION</h1>
              <p class="cert-certify-line">THIS CERTIFIES THAT</p>
              <h2 id="vName" class="cert-recipient-name">[Recipient Name]
              </h2>

              <p class="cert-training-msg">HAS SUCCESSFULLY COMPLETED THE
                TRAINING IN</p>
              <h3 id="vCourse" class="cert-course-name">[Course Name]</h3>

              <div class="cert-signatures">
                <div class="sig-item">
                  <p class="sig-name">HON. JENNIFER A. BARZAGA</p>
                  <p class="sig-rank">City Mayor</p>
                </div>
                <div class="sig-item">
                  <p class="sig-name">MR. CARLOS H. LEGASPI</p>
                  <p class="sig-rank">LEDIPO Head</p>
                </div>
              </div>

              <footer class="cert-serial-footer">
                <span id="vID">CERT. NO.: [ID]</span>
                <span id="vTrainingID">TRAINING ID: NCIIDRM-26-032</span>
              </footer>
            </div>
          </div>
        </div>

        <!-- Right: Certificate Details & Actions -->
        <div class="split-right-info">
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 id="viewModalTitle" class="modal-title" style="margin: 0;">
              Certificate Details</h2>
            <span id="vStatusBadge" class="badge badge-success">Pending</span>
          </div>

          <div class="info-block">
            <span class="info-label">Issue Date &amp; Record ID</span>
            <p class="info-value"
              style="font-size: 13px; margin: 0; color: #333;">
              <i class="fa-regular fa-calendar-check"
                style="color: #025628; margin-right: 4px;"></i>
              <span id="vIssueDate">-</span> &bull; <strong
                id="vControlNo">-</strong>
            </p>
          </div>

          <div class="info-block">
            <span class="info-label">Trainee Performance</span>
            <p id="vGrade" class="info-value grade-success">94% — Passed
            </p>
          </div>

          <div class="info-block">
            <span class="info-label">Official Signatories</span>
            <ul class="sig-list">
              <li><i class="fas fa-check-circle" aria-hidden="true"></i> Hon.
                Jennifer Austria-Barzaga</li>
              <li><i class="fas fa-check-circle" aria-hidden="true"></i> Mr.
                Carlos H. Legaspi</li>
            </ul>
          </div>

          <div class="modal-actions-container"
            style="margin-top: auto; display: flex; flex-direction: column; gap: 8px;">
            <button type="button" class="btn btn-primary"
              onclick="handleDownload('printableCert')">
              <i class="fas fa-file-pdf" aria-hidden="true"></i> Download PDF
            </button>
            <button type="button" class="btn btn-print"
              onclick="handlePrint()">
              <i class="fas fa-print" aria-hidden="true"></i> Print
              Certificate
            </button>
            <button type="button" class="btn btn-secondary"
              onclick="closeCertModal()">
              Close View
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- ==========================================
     2. ISSUE NEW CERTIFICATE MODAL
========================================== -->
  <div id="addTraineeModal" class="modal-overlay" role="dialog"
    aria-modal="true" aria-labelledby="issueModalTitle"
    style="display: none;">
    <div class="modal-box-fixed">
      <div class="modal-split">

        <!-- Left: Issue Form -->
        <div class="split-right-info border-right">
          <h2 id="issueModalTitle" class="modal-title">Issue New Certificate
          </h2>

          <form id="issueForm" autocomplete="off"
            onsubmit="event.preventDefault(); submitIssueCertificate();">

            <!-- 1. Trainee Selection with Dynamic Database Course Filter -->
            <fieldset class="ui-form-group">
              <label class="form-label">1. Trainee Selection</label>

              <!-- Dynamic Course Filter Select -->
              <select class="form-control mb-2" id="modalCourseFilter"
                onchange="filterModalTrainees(this.value)"
                style="border-left: 3px solid #025628; background-color: #f8faf9;">
                <option value="">-- All Courses (Show All Trainees) --
                </option>
                @if (isset($allCourses) && count($allCourses) > 0)
                  @foreach ($allCourses as $crs)
                    <option value="{{ $crs->title }}">{{ $crs->title }}
                    </option>
                  @endforeach
                @elseif (isset($courses) && count($courses) > 0)
                  @foreach ($courses as $crs)
                    <option value="{{ $crs->title }}">{{ $crs->title }}
                    </option>
                  @endforeach
                @endif
              </select>

              <!-- Dynamic Trainees Dropdown -->
              <select class="form-control" id="traineeSelect"
                name="trainee_id" onchange="updateLivePreview()" required>
                <option value="" disabled selected>Search / Select
                  Trainee...</option>
                @if (isset($eligibleTrainees) && count($eligibleTrainees) > 0)
                  @foreach ($eligibleTrainees as $trainee)
                    @php
                      $traineeName = trim(
                          ($trainee->firstname ?? '') .
                              ' ' .
                              ($trainee->lastname ?? ''),
                      );
                      $traineeCourse =
                          $trainee->course_title ?? 'General Training';
                    @endphp
                    <option value="{{ $trainee->id }}"
                      data-name="{{ $traineeName }}"
                      data-course="{{ $traineeCourse }}">
                      {{ $trainee->lastname ?? '' }},
                      {{ $trainee->firstname ?? '' }} — {{ $traineeCourse }}
                    </option>
                  @endforeach
                @else
                  <option value="" disabled>No enrolled trainees found in
                    database.</option>
                @endif
              </select>
            </fieldset>

            <!-- 2. Record Details -->
            <fieldset class="ui-form-group">
              <label for="certIDInput" class="form-label">2. Record
                Details</label>
              <input type="text" id="certIDInput" name="certificate_no"
                class="form-control" autocomplete="off"
                placeholder="Control Number (e.g. D-LED-TES-2026-082)"
                oninput="updateLivePreview()" required>
              <input type="date" id="issueDateInput" name="issue_date"
                class="form-control mt-2" value="{{ date('Y-m-d') }}">
            </fieldset>

            <!-- 3. Document Options -->
            <fieldset class="ui-form-group">
              <label for="docTypeSelect" class="form-label">3. Document
                Options</label>
              <select class="form-control" id="docTypeSelect"
                name="document_type" onchange="updateLivePreview()">
                <option value="completion">Certificate of Completion</option>
                <option value="participation">Certificate of Participation
                </option>
              </select>
              <textarea id="certRemarks" name="remarks"
                class="form-control resize-none mt-2" rows="3"
                placeholder="Optional Remarks..."></textarea>
            </fieldset>
          </form>
        </div>

        <!-- Right: Live Preview Panel -->
        <div class="split-left-preview bg-preview">
          <h3 class="modal-section-header">Live Preview</h3>

          <div class="ui-cert-frame" id="livePreviewCert">
            <div class="ui-cert-inner">
              <header class="cert-logos-header">
                <img src="{{ asset('images/logo.png') }}"
                  alt="City Government Logo" class="cert-logo-img">
                <img src="{{ asset('images/tesda.png') }}"
                  alt="TESDA Logo" class="cert-logo-img">
                <img src="{{ asset('images/logo_ledipo.png') }}"
                  alt="LEDIPO Logo" class="cert-logo-img">
              </header>

              <p class="cert-authority-text">
                TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY<br>
                CITY GOVERNMENT OF DASMARIÑAS — LEDIPO
              </p>

              <h1 class="cert-title-primary" id="pDocTitle">CERTIFICATE OF
                COMPLETION</h1>
              <p class="cert-certify-line">THIS CERTIFIES THAT</p>
              <h2 id="pName" class="cert-recipient-name">[Recipient
                Name]</h2>

              <p class="cert-training-msg">HAS SUCCESSFULLY COMPLETED THE
                TRAINING IN</p>
              <h3 id="pCourse" class="cert-course-name">[Course Name]</h3>

              <div class="cert-signatures">
                <div class="sig-item">
                  <p class="sig-name">HON. JENNIFER A. BARZAGA</p>
                  <p class="sig-rank">City Mayor</p>
                </div>
                <div class="sig-item">
                  <p class="sig-name">MR. CARLOS H. LEGASPI</p>
                  <p class="sig-rank">LEDIPO Head</p>
                </div>
              </div>

              <footer class="cert-serial-footer">
                <span id="pID">CERT. NO.: [ID]</span>
                <span>TRAINING ID: NCIIDRM-26-032</span>
              </footer>
            </div>
          </div>

          <div class="modal-actions-container mt-4"
            style="display: flex; flex-direction: column; gap: 8px;">
            <button type="button" class="btn btn-primary w-full"
              onclick="submitIssueCertificate()">
              <i class="fas fa-check" aria-hidden="true"></i> Save &amp;
              Issue
            </button>
            <button type="button" class="btn btn-pdf w-full"
              onclick="handleDownload('livePreviewCert')">
              <i class="fas fa-file-pdf" aria-hidden="true"></i> Download
              PDF
            </button>
            <button type="button" class="btn btn-secondary w-full"
              onclick="closeAddModal()">
              Cancel
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Logout Modal Alternative Overlay -->
  <div id="logoutModalOverlay" class="modal-overlay"
    style="display:none;">
    <div class="modal-box">
      <p>Are you sure you want to log out?</p>
      <div class="modal-actions-centered">
        <a href="login.php" class="btn-modal-yes">Yes</a>
        <button type="button" class="btn-modal-cancel"
          onclick="hideLogoutModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Course Modal -->
  <div id="courseModal" class="modal">
    <div class="modal-content card">
      <div class="modal-header">
        <h3><i class="fa-solid fa-pen-to-square"></i> Manage Course</h3>
        <span class="close-modal" onclick="closeModal()">&times;</span>
      </div>

      <form id="courseForm" class="modal-body" method="POST"
        action="">
        @csrf
        <input type="hidden" id="editCourseId" name="id">
        <input type="hidden" id="editTrainerId" name="trainer_id">

        <!-- Row 1: Course Code & Course Status -->
        <div class="modal-row">
          <div class="input-field">
            <label for="editCourseCode">Course Code</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-barcode"></i>
              <input type="text" id="editCourseCode" name="course_code"
                placeholder="e.g. CRS-001" required>
            </div>
          </div>

          <div class="input-field">
            <label for="editTrainingId">Training ID</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card"></i>
              <input
                type="text"
                id="editTrainingId"
                name="training_id"
                placeholder="e.g. NCIIDRM-26-032"
                required>
            </div>
          </div>

          <div class="input-field">
            <label for="editStatus">Course Status</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-circle-check"></i>
              <select id="editStatus" name="status"
                class="modal-input-select">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Row 2: Course Name -->
        <div class="input-field">
          <label for="editCourseName">Course Name</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-bookmark"></i>
            <input type="text" id="editCourseName" name="course_name"
              placeholder="e.g. Computer Literacy" required>
          </div>
        </div>

        <!-- Row 3: Duration & Slots -->
        <div class="modal-row">
          <div class="input-field">
            <label for="editDuration">Duration</label>
            <div class="input-wrapper input-with-suffix">
              <i class="fa-solid fa-calendar-day"></i>
              <input type="number" id="editDuration" name="duration"
                min="1" max="365" placeholder="e.g. 5"
                required>
              <span class="input-suffix">Days</span>
            </div>
          </div>
          <div class="input-field">
            <label for="editSlots">Slots</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user-graduate"></i>
              <input type="number" id="editSlots" name="slots"
                placeholder="e.g. 30" required>
            </div>
          </div>
        </div>

        <!-- Row 4: Assign Trainer Section -->
        <div class="assign-trainer-section">
          <div class="assign-trainer-label">
            <i class="fa-solid fa-chalkboard-user"></i> Assign Trainer
          </div>
          <div class="assign-trainer-row">
            <select id="trainerDropdown">
              <option value="">— Select a trainer —</option>
              @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}">
                  {{ $trainer->firstname }} {{ $trainer->lastname }}
                </option>
              @endforeach
            </select>
            <button type="button" class="btn-assign"
              onclick="assignTrainer()">
              <i class="fa-solid fa-check"></i> Assign
            </button>
          </div>

          <div id="currentTrainerBox" style="display:none;"
            class="current-trainer-box">
            <div class="trainer-avatar-sm" id="trainerInitials">JD</div>
            <div class="trainer-details">
              <div class="trainer-fullname" id="trainerFullName"></div>
              <div class="trainer-sub">Currently assigned trainer</div>
            </div>
            <button type="button" class="btn-remove-trainer"
              onclick="removeTrainer()">
              <i class="fa-solid fa-xmark"></i> Remove
            </button>
          </div>

          <div id="noTrainerBox" class="no-trainer-box">
            <i class="fa-solid fa-circle-info"></i> No trainer assigned yet.
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-delete-text"
            onclick="confirmDelete()">
            <i class="fa-solid fa-trash"></i> Delete Course
          </button>
          <div class="action-buttons">
            <button type="button" class="btn-cancel"
              onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn-save-main">Save
              Changes</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Announcement Modal -->
  <div id="announcementModal" class="modal" style="display: none;">
    <div class="modal-content card">
      <div class="modal-header">
        <h3 id="annModalTitle"><i class="fa-solid fa-bell"></i> Add
          Announcement</h3>
        <span class="close-modal"
          onclick="closeAnnouncementModal()">&times;</span>
      </div>

      <form id="announcementForm" class="modal-body">
        <input type="hidden" id="annId">

        <!-- Title Field -->
        <div class="form-group">
          <div class="label-row">
            <label for="annTitle">Title <span
                class="required">*</span></label>
            <span class="char-counter" id="titleCounter">0/100</span>
          </div>
          <div class="input-container">
            <i class="fa-solid fa-pen input-icon"></i>
            <input type="text" id="annTitle" maxlength="100"
              placeholder="e.g., System Maintenance Schedule" required>
          </div>
        </div>

        <!-- Message Field -->
        <div class="form-group">
          <div class="label-row">
            <label for="annMessage">Message <span
                class="required">*</span></label>
            <span class="char-counter" id="messageCounter">0/500</span>
          </div>
          <div class="input-container textarea-container">
            <i class="fa-solid fa-align-left input-icon textarea-icon"></i>
            <textarea id="annMessage" maxlength="500"
              placeholder="Enter detailed announcement message..." required></textarea>
          </div>
        </div>

<!-- Row 1: Type & Status (Kill Switch) -->
<div class="form-row">
  <div class="form-group flex-1">
    <label for="annType">Type <span class="required">*</span></label>

    <div class="input-container">
      <i class="fa-solid fa-tag input-icon"></i>

      <select id="annType" class="modal-input-select">
        <option value="reminder">Reminder</option>
        <option value="notice">Notice</option>
        <option value="urgent">Urgent</option>
      </select>
    </div>
  </div>

  <div class="form-group status-group">
    <label>Status Switch</label>

    <label class="checkbox-card">
      <input type="checkbox" id="annIsActive" checked>
      <span class="checkbox-text" id="statusLabel">Active</span>
    </label>
  </div>
</div>

<!-- Row 2: Audience -->
<div class="form-row">
  <div class="form-group flex-1">
    <label for="annAudience">
      Audience <span class="required">*</span>
    </label>

    <div class="input-container">
      <i class="fa-solid fa-users input-icon"></i>

      <select id="annAudience" class="modal-input-select">
        <option value="general">General — Everyone</option>
        <option value="student">Students</option>
        <option value="trainer">Trainers</option>
      </select>
    </div>
  </div>
</div>


        <!-- Row 2: Schedule & Expiration Timestamps -->
        <div class="form-row">
          <div class="form-group flex-1">
            <div class="label-row">
              <label for="annPublishAt">Publish At <small
                  style="color:#888;">(Optional)</small></label>
            </div>
            <div class="input-container datetime-container">
              <input type="datetime-local" id="annPublishAt"
                class="datetime-input">
            </div>
          </div>

          <div class="form-group flex-1">
            <div class="label-row">
              <label for="annExpiresAt">Expires At <small
                  style="color:#888;">(Optional)</small></label>
            </div>
            <div class="input-container datetime-container">
              <input type="datetime-local" id="annExpiresAt"
                class="datetime-input">
            </div>
          </div>
        </div>

      </form>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn-cancel"
          onclick="closeAnnouncementModal()">Cancel</button>

        <button type="submit"
          id="btnSubmitAnn"
          class="btn-save-main"
          form="announcementForm">
          Save
        </button>
      </div>
    </div>
  </div>

  <!-- Add Trainer Modal -->
  <div id="addTrainerModal" class="modal">
    <style>
      #addTrainerModal .modal-content.card {
        max-height: 90vh !important;
        display: flex !important;
        flex-direction: column !important;
        margin: 3vh auto !important;
        overflow: hidden !important;
        max-width: 600px !important;
        width: 95% !important;
      }

      #addTrainerModal .modal-body {
        overflow-y: auto !important;
        max-height: calc(90vh - 120px);
        padding-right: 4px;
      }

      #addTrainerModal .input-wrapper input,
      #addTrainerModal .input-wrapper select,
      #addTrainerModal textarea {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        background-color: #ffffff !important;
      }

      #addTrainerModal .input-field {
        margin-bottom: 12px !important;
      }

      /* ==========================================================
   ADMIN NOTIFICATION BELL
   ========================================================== */

.notification-wrapper {
  position: relative;
  display: flex;
  align-items: center;
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
  font-size: 19px;

  border-radius: 50%;
  cursor: pointer;

  transition: all 0.2s ease;
}

.notification-btn:hover {
  background: rgba(255, 255, 255, 0.12);
  transform: translateY(-1px);
}

.notification-btn:active {
  transform: scale(0.96);
}

/* Notification count */
.notification-badge {
  position: absolute;

  top: 1px;
  right: 0px;

  min-width: 17px;
  height: 17px;

  padding: 0 4px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 20px;

  background: #f4d900;
  color: #025628;

  border: 2px solid #025628;

  font-size: 9px;
  font-weight: 800;

  line-height: 1;
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

/* Header */

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

/* Unread notification */
.notification-item.notification-unread {
  background: #f3faf6;
  box-shadow: inset 4px 0 0 #025628;
}

/* Make unread title clearly stronger */
.notification-item.notification-unread .notification-item-title {
  font-weight: 900;
  color: #025628;
}

/* Read notification */
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

  background: #e9f6ee;
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
   RESPONSIVE
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

}

    </style>

    <div class="modal-content card">
      <div class="modal-header">
        <h3><i class="fa-solid fa-user-plus" style="margin-right: 6px;"></i>
          Register New Trainer</h3>
        <span class="close-modal"
          onclick="closeAddTrainerModal()">&times;</span>
      </div>
      <form id="addTrainerForm" class="modal-body">
        <div class="input-field">
          <label>Full Name</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-signature"></i>
            <input type="text" id="newTrainerName"
              placeholder="e.g. Juan Dela Cruz" required>
          </div>
        </div>

        <div class="modal-row">
          <div class="input-field">
            <label>Email Address</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope"></i>
              <input type="email" id="newTrainerEmail"
                placeholder="trainer@example.com" required>
            </div>
          </div>
          <div class="input-field">
            <label>Contact Number (Optional)</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-phone"></i>
              <input type="text" id="newTrainerContact"
                placeholder="0912 345 6789">
            </div>
          </div>
        </div>

        <div class="modal-row">
          <div class="input-field">
            <label>Temporary Password</label>
            <div style="position: relative; width: 100%;">
              <i class="fa-solid fa-key"
                style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6b7280; z-index: 3; pointer-events: none;"></i>
              <input type="password" id="newTrainerPass"
                placeholder="e.g. Welcome2026"
                style="width: 100%; height: 42px; padding-left: 38px; padding-right: 42px; border: 1px solid #d1d5db !important; border-radius: 8px !important; background-color: #ffffff !important; box-sizing: border-box; font-family: inherit; font-size: 13px;"
                required>
              <i class="fa-solid fa-eye" id="togglePasswordIcon"
                onclick="togglePassword()"
                style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280; z-index: 3;"></i>
            </div>
          </div>
          <div class="input-field">
            <label>Reference / ID Number</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-id-card"></i>
              <input type="text" id="newTrainerIdNum"
                placeholder="e.g. TR-2026-001">
            </div>
          </div>
        </div>

        <div class="input-field">
          <label>Assigned Course</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-book-open"></i>
            <select id="newTrainerCourse" class="modal-input-select">
              <option value="">— Select a course —</option>
              @foreach ($allCourses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="input-field" style="margin-top: 4px;">
          <label>Admin Remarks / Internal Notes</label>
          <textarea id="newTrainerRemarks"
            placeholder="Add confidential notes or operational remarks regarding this profile..."
            rows="2"
            style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:13px; font-family:inherit; resize:vertical;"></textarea>
        </div>

        <div class="modal-footer"
          style="margin-top: 16px; padding-bottom: 5px;">
          <button type="button" class="btn-cancel"
            onclick="closeAddTrainerModal(); return false;">Cancel</button>
          <button type="submit" class="btn-save-main">Create
            Account</button>
        </div>
      </form>
    </div>
  </div>

  <!-- User Profile Modal -->
  <div id="userModal" class="modal">
    <style>
      #userModal .modal-content.card {
        max-height: 90vh !important;
        display: flex !important;
        flex-direction: column !important;
        margin: 3vh auto !important;
        overflow: hidden !important;
      }

      #userModal .modal-body {
        overflow-y: auto !important;
        max-height: calc(90vh - 120px);
        padding-right: 6px;
      }

      #userModal .input-wrapper input,
      #userModal .input-wrapper select,
      #userModal textarea {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        background-color: #ffffff !important;
      }

      #userModal .input-wrapper input[readonly] {
        background-color: #f9fafb !important;
        color: #4b5563;
      }

      #userModal .input-field {
        margin-bottom: 12px !important;
      }

      #userModal .modal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
      }

      #userModal .action-buttons {
        display: flex;
        gap: 8px;
      }
    </style>

    <div class="modal-content card" style="max-width: 600px; width: 95%;">
      <div class="modal-header">
        <h3><i class="fa-solid fa-user-gear"></i> Manage User Profile</h3>
        <span class="close-modal" onclick="closeUserModal()">&times;</span>
      </div>

      <form id="userForm" class="modal-body">
        <!-- Hidden User ID Field (Critical for deleteUser & update AJAX) -->
        <input type="hidden" id="editUserId" name="id"
          value="">

        <!-- Full Name -->
        <div class="input-field">
          <label for="editUserName">Full Name</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-signature"></i>
            <input type="text" id="editUserName" name="name"
              placeholder="Full Name" required>
          </div>
        </div>

        <!-- Email & Member Since -->
        <div class="modal-row">
          <div class="input-field">
            <label for="editUserEmail">Email Address</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope"></i>
              <input type="email" id="editUserEmail" name="email"
                readonly class="readonly-input">
            </div>
          </div>

          <div class="input-field">
            <label for="editUserCreated">Member Since</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-calendar-days"></i>
              <input type="text" id="editUserCreated" readonly
                class="readonly-input" style="background: #f9f9f9;">
            </div>
          </div>
        </div>

        <!-- Contact & Reference / ID Number -->
        <div id="trainerFieldsContainer" style="display: none;">
          <div class="modal-row">
            <div class="input-field">
              <label for="editUserContact">Contact Number</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="editUserContact" name="contact"
                  maxlength="11"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                  placeholder="09XXXXXXXXX">
              </div>
            </div>

            <div class="input-field">
              <label for="editUserIdNum">Reference / ID Number</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-id-card"></i>
                <input type="text" id="editUserIdNum" name="id_number"
                  placeholder="N/A">
              </div>
            </div>
          </div>
        </div>

        <!-- Assigned Course -->
        <div class="input-field" id="trainerCourseField"
          style="display: none;">
          <label for="editTrainerCourse">Assigned Course (Teaching)</label>
          <div class="input-wrapper">
            <i class="fa-solid fa-book-open"></i>
            <input type="text" id="editTrainerCourse" readonly
              class="readonly-input" style="background: #f9f9f9;">
          </div>
        </div>

        <!-- Role & Status -->
        <div class="modal-row">
          <div class="input-field">
            <label for="editUserRole">Account Role</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-user-tag"></i>
              <select id="editUserRole" class="modal-input-select" disabled
                style="background: #f5f5f5; cursor: not-allowed;">
                <option value="student">Trainee</option>
                <option value="trainer">Trainer</option>
                <option value="admin">Admin</option>
              </select>
              <input type="hidden" name="role" id="hiddenUserRole"
                value="">
            </div>
          </div>

          <div class="input-field">
            <label for="editUserStatus">Status</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-circle-check"></i>
              <select id="editUserStatus" name="status"
                class="modal-input-select">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Pending">Pending</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Admin Remarks -->
        <div class="input-field" style="margin-top: 4px;">
          <label for="editUserRemarks">Admin Remarks / Internal Notes</label>
          <textarea id="editUserRemarks" name="remarks"
            placeholder="Add confidential notes or operational remarks regarding this profile..."
            rows="2"
            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn-delete-text"
            onclick="deleteUser()">
            <i class="fa-solid fa-user-slash"></i> Remove User
          </button>
          <div class="action-buttons">
            <button type="button" class="btn-cancel"
              onclick="closeUserModal();">Cancel</button>
            <button type="submit" class="btn-save-main">Update
              User</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Facility Modal -->
  <div id="facilityModal" class="modal"
    style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 16px;">

    <div class="modal-content card"
      style="background: #ffffff; width: 100%; max-width: 460px; max-height: 88vh; border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">

      <!-- Header -->
      <div class="modal-header"
        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding: 14px 20px; background: #fff; flex-shrink: 0;">
        <h3 id="facilityModalTitle"
          style="margin: 0; font-size: 17px; color: #025628; font-weight: 700; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-building-circle-gear"></i> Manage Facility
        </h3>
        <span class="close-modal" onclick="closeFacilityModal()"
          style="font-size: 20px; cursor: pointer; color: #888; line-height: 1;">&times;</span>
      </div>

      <!-- Body Form -->
      <form id="facilityForm" class="modal-body"
        style="overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1;">

        <input type="hidden" id="editFacId" name="id"
          value="">

        <div class="input-field" style="margin: 0;">
          <label
            style="display: block; font-size: 11px; font-weight: 600; color: #4b5563; text-align: left; margin-bottom: 4px;">
            Facility / Center Name
          </label>
          <div class="input-wrapper"
            style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; background: #fff;">
            <i class="fa-solid fa-hotel"
              style="color: #6b9e7c; margin-right: 8px; font-size: 13px;"></i>
            <input type="text" id="editFacName" required
              placeholder="e.g. LEDIPO Main"
              style="border: none; outline: none; width: 100%; font-size: 13px; background: transparent;">
          </div>
        </div>

        <div class="input-field" style="margin: 0;">
          <label
            style="display: block; font-size: 11px; font-weight: 600; color: #4b5563; text-align: left; margin-bottom: 4px;">
            Full Address
          </label>
          <div class="input-wrapper"
            style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; background: #fff;">
            <i class="fa-solid fa-location-dot"
              style="color: #6b9e7c; margin-right: 8px; font-size: 13px;"></i>
            <input type="text" id="editFacAddress" required
              placeholder="Zone 4, Dasmariñas, Cavite"
              style="border: none; outline: none; width: 100%; font-size: 13px; background: transparent;">
          </div>
        </div>

        <div class="input-field" style="margin: 0;">
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label
              style="font-size: 11px; font-weight: 600; color: #1f2937; display: flex; align-items: center; gap: 6px; margin: 0;">
              <i class="fa-solid fa-book-open-reader"
                style="color: #025628;"></i> Assigned Courses
              <span id="selectedCourseBadge"
                style="background: #e8f5e9; color: #025628; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">
                0 Selected
              </span>
            </label>
            <button type="button" onclick="toggleSelectAllCourses()"
              style="background: none; border: none; color: #025628; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0;">
              Select All
            </button>
          </div>

          <div id="facilityCoursesContainer"
            style="max-height: 120px; overflow-y: auto; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 10px; background: #ffffff; display: flex; flex-direction: column; gap: 6px;">
            @foreach ($allCourses as $course)
              <label
                style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #1f2937; cursor: pointer; user-select: none;">
                <input type="checkbox" name="courses[]"
                  value="{{ $course->id }}" class="facility-course-cb"
                  onchange="updateCourseBadgeCount()"
                  style="width: 15px; height: 15px; accent-color: #025628; cursor: pointer;">
                <span>{{ $course->title }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <div class="modal-footer"
          style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0f0f0; padding-top: 12px; margin-top: 4px; flex-shrink: 0;">
          <button type="button" class="btn-delete-text"
            onclick="deleteFacility()"
            style="background: none; border: none; color: #dc2626; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; padding: 0;">
            <i class="fa-solid fa-trash-can"></i> Delete Facility
          </button>
          <div class="action-buttons" style="display: flex; gap: 8px;">
            <button type="button" onclick="closeFacilityModal()"
              style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer;">
              Cancel
            </button>
            <button type="submit" class="btn-save-main"
              style="background: #025628; color: #fff; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer;">
              Save Changes
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <!-- Course Content Modal (Modules & Quizzes) -->
  <div id="contentModal" class="modal">
    <div class="modal-content card" style="max-width:680px; width:95%;">
      <div class="modal-header">
        <h3><i class="fa-solid fa-layer-group"></i> Manage: <span
            id="contentModalCourseTitle"></span></h3>
        <span class="close-modal"
          onclick="closeContentModal()">&times;</span>
      </div>

      <div class="modal-body" style="padding-bottom:0;">
        <!-- Tab Navigation -->
        <div
          style="display:flex; gap:0; border-bottom:2px solid #e5e5e5; margin-bottom:16px;">
          <button id="tab-btn-modules" onclick="switchContentTab('modules')"
            style="flex:1; padding:10px; border:none; background:none; font-weight:700; font-size:13px; border-bottom:2px solid #025628; margin-bottom:-2px; color:#025628; cursor:pointer;">
            <i class="fa-solid fa-cubes"></i> Modules
          </button>
          <button id="tab-btn-quizzes" onclick="switchContentTab('quizzes')"
            style="flex:1; padding:10px; border:none; background:none; font-weight:600; font-size:13px; color:#aaa; cursor:pointer;">
            <i class="fa-solid fa-clipboard-question"></i> Quizzes
          </button>
        </div>

        <!-- MODULES TAB -->
        <div id="content-tab-modules">
          <div id="moduleAlert"
            style="display:none; padding:8px 12px; border-radius:6px; font-size:12px; margin-bottom:10px; font-weight:600;">
          </div>

          <div
            style="display:flex; flex-direction:column; gap:8px; margin-bottom:14px;">
            <div style="display:flex; gap:8px;">
              <input type="text" id="newModuleTitle"
                placeholder="Module title" required
                style="flex:1; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
              <input type="text" id="newModuleDesc"
                placeholder="Description (optional)"
                style="flex:2; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
              <label
                style="font-size:12px; color:#666; white-space:nowrap;">📎
                PDF File:</label>
              <input type="file" id="newModuleFile"
                accept=".pdf,.doc,.docx"
                style="flex:1; border:1px solid #ddd; border-radius:8px; padding:6px 12px; font-size:13px; font-family:inherit; background:#fff;">
              <button type="button" onclick="addModule()"
                style="background:#025628; color:#fff; border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; font-family:inherit;">
                <i class="fa-solid fa-plus"></i> Add
              </button>
            </div>
          </div>

          <div id="moduleListContainer"
            style="display:flex; flex-direction:column; gap:8px; max-height:320px; overflow-y:auto;">
            <div
              style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;"
              id="modulesEmptyState">
              <i class="fa-solid fa-inbox"
                style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
              No modules created yet.
            </div>
          </div>
        </div>

        <!-- QUIZZES TAB -->
        <div id="content-tab-quizzes" style="display:none;">
          <div id="quizAlert"
            style="display:none; padding:8px 12px; border-radius:6px; font-size:12px; margin-bottom:10px; font-weight:600;">
          </div>

          <div
            style="background:#f9f9f9; border:1px solid #eee; border-radius:10px; padding:14px; margin-bottom:14px;">
            <div
              style="font-size:12px; font-weight:700; color:#025628; margin-bottom:10px; text-transform:uppercase; letter-spacing:.04em;">
              <i class="fa-solid fa-plus-circle"></i> New Quiz
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <input type="text" id="newQuizTitle"
                placeholder="Quiz title" required
                style="flex:2; min-width:140px; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit;">
              <select id="newQuizModule"
                style="flex:1.5; min-width:130px; border:1px solid #ddd; border-radius:8px; padding:8px 12px; font-size:13px; font-family:inherit; background:#fff;">
                <option value="">— Link to module (optional) —</option>
              </select>
            </div>
            <div
              style="display:flex; gap:8px; margin-top:8px; flex-wrap:wrap; align-items:center;">
              <div style="flex:1; min-width:100px;">
                <label
                  style="font-size:11px; color:#888; display:block; margin-bottom:2px;">Passing
                  score (%)</label>
                <input type="number" id="newQuizPass" value="75"
                  min="1" max="100"
                  style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px 10px; font-size:13px; font-family:inherit;">
              </div>
              <div style="flex:1; min-width:100px;">
                <label
                  style="font-size:11px; color:#888; display:block; margin-bottom:2px;">Time
                  limit (mins)</label>
                <input type="number" id="newQuizTime" value="30"
                  min="1"
                  style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px 10px; font-size:13px; font-family:inherit;">
              </div>
              <button type="button" onclick="addQuiz()"
                style="background:#025628; color:#fff; border:none; border-radius:8px; padding:9px 20px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; margin-top:14px;">
                <i class="fa-solid fa-plus"></i> Add Quiz
              </button>
            </div>
          </div>

          <div id="quizListContainer"
            style="display:flex; flex-direction:column; gap:8px; max-height:280px; overflow-y:auto;">
            <div
              style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;"
              id="quizzesEmptyState">
              <i class="fa-solid fa-inbox"
                style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
              No quizzes created yet.
            </div>
          </div>
        </div>

      </div>

      <div class="modal-footer"
        style="margin-top:20px; padding-top:12px; border-top:1px solid #eee; display:flex; justify-content:flex-end;">
        <button class="btn-cancel" onclick="closeContentModal()"
          style="padding:8px 20px; font-size:13px;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================== -->
  <!-- JAVASCRIPT SCRIPTS & HANDLERS                              -->
  <!-- ========================================================== -->
  <script src="js/logout.js"></script>
  <script>
    const urlParams = new URLSearchParams(window.location.search);

    function showAlert(message) {
  document.getElementById('mcAlertMessage').textContent = message;
  document.getElementById('mcAlertModal').style.display = 'block';
}

function closeAlertModal() {
  document.getElementById('mcAlertModal').style.display = 'none';
}

let _confirmCallback = null;

function showConfirm(message, onYes) {
  document.getElementById('mcConfirmMessage').textContent = message;
  _confirmCallback = onYes;
  document.getElementById('mcConfirmModal').style.display = 'block';
}

function closeConfirmModal() {
  document.getElementById('mcConfirmModal').style.display = 'none';
  _confirmCallback = null;
}

function showToast(message, type = 'info') {
  const styles = {
    success: { bg: '#e8f5e9', border: '#025628', color: '#025628', icon: 'fa-circle-check' },
    error:   { bg: '#FCEBEB', border: '#A32D2D', color: '#A32D2D', icon: 'fa-circle-xmark' },
    warning: { bg: '#FFF8E1', border: '#854F0B', color: '#854F0B', icon: 'fa-triangle-exclamation' },
    info:    { bg: '#E3F2FD', border: '#0d47a1', color: '#0d47a1', icon: 'fa-circle-info' }
  };
  const s = styles[type] || styles.info;

  const toast = document.createElement('div');
  toast.style.cssText = `
    background:${s.bg}; border:1px solid ${s.border}; color:${s.color};
    padding:12px 16px; border-radius:8px; font-size:13px; font-weight:600;
    display:flex; align-items:center; gap:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);
    animation: mcToastIn 0.25s ease;
  `;
  toast.innerHTML = `
    <i class="fa-solid ${s.icon}"></i>
    <span style="flex:1;">${message}</span>
    <span onclick="this.parentElement.remove()" style="cursor:pointer; font-weight:700; opacity:0.6;">&times;</span>
  `;

  document.getElementById('toastContainer').appendChild(toast);

  setTimeout(() => {
    toast.style.transition = 'opacity 0.3s ease';
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

function _confirmYes() {
  const cb = _confirmCallback;
  closeConfirmModal();
  if (cb) cb();
}

    const csrfToken = document.querySelector('meta[name="csrf-token"]')
      .getAttribute('content');

    let traineeHistoryInstance = null;
    let courseHistoryInstance = null;
    let currentCourseId = null;

    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdown = document.getElementById('dropdown');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    hamburger.addEventListener('click', function() {
      sidebar.classList.toggle('sidebar-open');
      overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function() {
      sidebar.classList.remove('sidebar-open');
      overlay.classList.remove('show');
    });

    avatarBtn.addEventListener('click', function(e) {
      e.stopPropagation();

      dropdown.classList.toggle('open');

      // Close notification dropdown if it is open
      notificationDropdown.classList.remove('open');
      notificationBtn.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('click', function(e) {

      if (!e.target.closest('.topbar-right')) {
        dropdown.classList.remove('open');
        notificationDropdown.classList.remove('open');

        notificationBtn.setAttribute('aria-expanded', 'false');
      }

    });

    notificationBtn.addEventListener('click', function(e) {
      e.stopPropagation();

      notificationDropdown.classList.toggle('open');

      notificationBtn.setAttribute(
        'aria-expanded',
        notificationDropdown.classList.contains('open') ? 'true' : 'false'
      );

      // Close profile dropdown if it is open
      dropdown.classList.remove('open');
    });

    // ==========================================================
    // ADMIN NOTIFICATION READ STATE
    // ==========================================================
    function markNotificationRead(item) {
        if (!item || item.dataset.read === '1') return;
        if (!item.classList.contains('notification-unread')) return;

        const announcementId = item.dataset.announcementId;
        if (!announcementId) return;

        fetch(`{{ url('/notifications/announcements') }}/${announcementId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Unable to mark notification as read.');
            }

            return response.json();
        })
        .then(data => {
            if (!data || !data.success) return;

            item.dataset.read = '1';
            item.classList.remove('notification-unread');

            const notificationBadge = document.querySelector('.notification-badge');

            if (notificationBadge) {
                const currentCount = parseInt(
                    notificationBadge.textContent.replace('+', '').trim(),
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

            const unreadItems = notificationDropdown
                ? notificationDropdown.querySelectorAll(
                    '.notification-item.notification-unread'
                  ).length
                : 0;

            const headerUnread =
                document.querySelector('.notification-header > div > span');

            if (headerUnread) {
                headerUnread.textContent =
                    `${unreadItems} unread notification${unreadItems !== 1 ? 's' : ''}`;
            }
        })
        .catch(error => {
            console.error('Mark notification read error:', error);
        });
    }

    if (notificationDropdown) {
        notificationDropdown
            .querySelectorAll('.notification-item[data-announcement-id]')
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
    }

    window.traineeCourseLabels = @json($traineeCourseLabels);
    window.traineeCourseCounts = @json($traineeCourseCounts);
    window.overviewMonths = @json($months);
    window.overviewCourseDatasets = @json($overviewCourseDatasets);

    let traineeChartInstance = null;

    function setActive(el) {
      if (!el) return;
      document.querySelectorAll('.sidebar .nav-item').forEach(i => i.classList
        .remove('active'));
      el.classList.add('active');
    }

    function initHistoryChart(traineeData = [], courseData = []) {
      // 1. TRAINEE HISTORY CHART
      const traineeCanvas = document.getElementById('traineeHistoryChart');
      if (traineeCanvas) {
        traineeCanvas.style.height = '500px';
        const traineeCtx = traineeCanvas.getContext('2d');

        if (traineeHistoryInstance) {
          traineeHistoryInstance.destroy();
        }

        traineeHistoryInstance = new Chart(traineeCtx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
              'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: [{
              label: 'Trainees',
              data: traineeData,
              backgroundColor: '#7fb092',
              borderRadius: 5
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                position: 'top'
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        });
      }

      // 2. COURSE HISTORY CHART
      const courseCanvas = document.getElementById('courseHistoryChart');
      if (courseCanvas) {
        courseCanvas.style.height = '500px';
        const courseCtx = courseCanvas.getContext('2d');

        if (courseHistoryInstance) {
          courseHistoryInstance.destroy();
        }

        courseHistoryInstance = new Chart(courseCtx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug',
              'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: [{
              label: 'Courses',
              data: courseData,
              borderColor: '#004d26',
              backgroundColor: 'rgba(0,77,38,0.1)',
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        });
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      // 1. Announcement Modal Character Counters & Status Toggle
      const titleInput = document.getElementById('annTitle');
      const messageInput = document.getElementById('annMessage');
      const titleCounter = document.getElementById('titleCounter');
      const messageCounter = document.getElementById('messageCounter');
      const isActiveCb = document.getElementById('annIsActive');
      const statusLabel = document.getElementById('statusLabel');

      if (titleInput && titleCounter) {
        titleInput.addEventListener('input', () => {
          titleCounter.textContent = `${titleInput.value.length}/100`;
        });
      }

      if (messageInput && messageCounter) {
        messageInput.addEventListener('input', () => {
          messageCounter.textContent = `${messageInput.value.length}/500`;
        });
      }

      if (isActiveCb && statusLabel) {
        isActiveCb.addEventListener('change', (e) => {
          statusLabel.textContent = e.target.checked ? 'Publish Now' :
            'Save as Draft';
        });
      }

      // 2. FullCalendar Sidebar Initialization
      const calendarEl = document.getElementById('calendar');
      if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          fixedWeekCount: true,
          headerToolbar: {
            left: 'prev',
            center: 'title',
            right: 'next'
          },
          eventColor: '#004d26',
          height: 280,
          aspectRatio: 1.0,
          contentHeight: 'auto',
          handleWindowResize: true
        });
        calendar.render();
      }

      // 3. Trainees Enrolled Per Course Bar Chart (Overview View)
      const traineeCanvas = document.getElementById('traineeChart');
      if (traineeCanvas) {
        const ctxBar = traineeCanvas.getContext('2d');

        if (window.traineeChartInstance) {
          window.traineeChartInstance.destroy();
        }

        // Safely retrieve global variables initialized by Blade
        const labels = (typeof window.traineeCourseLabels !== 'undefined' &&
            window.traineeCourseLabels.length) ?
          window.traineeCourseLabels :
          (typeof traineeCourseLabels !== 'undefined' ? traineeCourseLabels :
            []);

        const dataCounts = (typeof window.traineeCourseCounts !==
            'undefined' && window.traineeCourseCounts.length) ?
          window.traineeCourseCounts :
          (typeof traineeCourseCounts !== 'undefined' ? traineeCourseCounts :
            []);

        window.traineeChartInstance = new Chart(ctxBar, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Enrolled Trainees',
              data: dataCounts,
              backgroundColor: '#004d26',
              borderRadius: 4,
              barPercentage: 0.65
            }]
          },
          options: {
            indexAxis: 'y', // Makes bars horizontal so long course names render cleanly
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return `Trainees: ${context.raw}`;
                  }
                }
              }
            },
            scales: {
              x: {
                beginAtZero: true,
                grid: {
                  color: '#f0f0f0'
                },
                ticks: {
                  precision: 0
                }
              },
              y: {
                grid: {
                  display: false
                },
                ticks: {
                  font: {
                    size: 11,
                    weight: '600'
                  },
                  color: '#333333'
                }
              }
            },
            layout: {
              padding: {
                left: 10,
                right: 20,
                top: 10,
                bottom: 10
              }
            }
          }
        });
      }

      // 4. Trainee Status Ratio Pie Chart (Overview View)
      const courseCanvas = document.getElementById('courseChart');
      if (courseCanvas) {
        const ctxPie = courseCanvas.getContext('2d');

        // Destroy previous instance to prevent rendering overlap or chart type caching issues
        if (window.courseChartInstance) {
          window.courseChartInstance.destroy();
          window.courseChartInstance = null;
        }

        // Retrieve active and completed array data or fallback to safe defaults
        const activeData = (typeof window.courseActiveCounts !==
            'undefined' && window.courseActiveCounts.length) ?
          window.courseActiveCounts :
          (typeof courseActiveCounts !== 'undefined' ? courseActiveCounts : [
            10, 2, 3, 1, 4
          ]);

        const completedData = (typeof window.courseCompletedCounts !==
            'undefined' && window.courseCompletedCounts.length) ?
          window.courseCompletedCounts :
          (typeof courseCompletedCounts !== 'undefined' ?
            courseCompletedCounts : [4, 1, 2, 0, 3]);

        // Aggregate total active and completed counts
        const totalActive = activeData.reduce((a, b) => a + b, 0);
        const totalCompleted = completedData.reduce((a, b) => a + b, 0);

        window.courseChartInstance = new Chart(ctxPie, {
          type: 'pie',
          data: {
            labels: ['Active / Enrolled', 'Completed / Graduated'],
            datasets: [{
              data: [totalActive, totalCompleted],
              backgroundColor: ['#004d26',
                '#eab308'
              ], // Dark Green for Active, Yellow for Completed
              borderWidth: 2,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            onClick: function() {
              if (typeof openExpandedChartModal === 'function') {
                openExpandedChartModal('courses');
              }
            },
            plugins: {
              legend: {
                display: true,
                position: 'bottom',
                labels: {
                  boxWidth: 12,
                  padding: 14,
                  font: {
                    size: 11,
                    weight: '600'
                  },
                  color: '#333333'
                }
              },
              tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                padding: 10,
                cornerRadius: 6,
                callbacks: {
                  label: (ctx) => {
                    const total = totalActive + totalCompleted;
                    const val = ctx.raw;
                    const pct = total > 0 ? Math.round((val / total) *
                      100) : 0;
                    return ` ${ctx.label}: ${val} (${pct}%)`;
                  }
                }
              }
            },
            layout: {
              padding: {
                top: 5,
                bottom: 5
              }
            }
          }
        });
      }

      // 5. URL Parameter & LocalStorage View Restoration
      const urlParams = new URLSearchParams(window.location.search);
      const savedTab = localStorage.getItem('activeAdminTab');

      if (urlParams.get('view') === 'certificate' || savedTab ===
        'view-certificate') {
        showView('certificate');
        setActive(document.getElementById('nav-certificate'));
        localStorage.removeItem('activeAdminTab');
      } else if (urlParams.get('view') === 'facilities' || savedTab ===
        'view-facilities') {
        showView('facilities');
        setActive(document.getElementById('nav-facilities'));
        localStorage.removeItem('activeAdminTab');
      } else if (urlParams.get('view') === 'courses' || urlParams.get(
          'page')) {
        showView('courses');
        setActive(document.getElementById('nav-courses'));
      } else if (urlParams.get('trainee_page') || window.location.pathname
        .includes('trainees')) {
        showView('all-trainees');
        setActive(document.getElementById('nav-trainees'));
      } else if (urlParams.get('trainer_page') || urlParams.get('trainer') ||
        urlParams.has('trainer_page')) {
        showView('all-trainers');
        setActive(document.getElementById('nav-trainers'));
      } else if (urlParams.get('view') === 'announcements' || urlParams.get(
          'announcement_page')) {
        showView('announcements');
        setActive(document.getElementById('nav-announcements'));
      } else if (urlParams.get('view') === 'registrations' || urlParams.get(
          'registration_page')) {
        showView('registrations');
        setActive(document.getElementById('nav-registrations'));
      } else if (savedTab && savedTab !== 'view-overview') {
        showView(savedTab.replace('view-', ''));
        localStorage.removeItem('activeAdminTab');
      } else {
        showView('overview');
        setActive(document.getElementById('nav-overview'));
      }
    });

    function showView(viewName) {
      const allViews = [
        'view-overview', 'view-trainee-list', 'view-trainer-list',
        'view-facilities', 'view-courses', 'view-settings', 'view-analytics',
        'view-announcements', 'view-certificate', 'view-registrations'
      ];

      allViews.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
      });

      const title = document.getElementById('main-title');
      const breadcrumb = document.getElementById('breadcrumb-current');

      const map = {
        overview: ['view-overview', 'System Overview', 'System Overview'],
        analytics: ['view-analytics', 'Detailed Analytics',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Analytics`
        ],
        'all-trainees': ['view-trainee-list', 'Trainee Management',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Trainees`
        ],
        'all-trainers': ['view-trainer-list', 'Trainer Management',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Trainers`
        ],
        facilities: ['view-facilities', 'Facilities',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Facilities`
        ],
        courses: ['view-courses', 'Available Courses',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Courses`
        ],
        settings: ['view-settings', 'System Settings',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Settings`
        ],
        announcements: ['view-announcements', 'Announcements',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Announcements`
        ],
        certificate: ['view-certificate', 'Certificates',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Certificates`
        ],
        registrations: ['view-registrations', 'Registrations',
          `<a href="#" onclick="showView('overview');return false;">System Overview</a> / Registrations`
        ]
      };

      const entry = map[viewName] || map['overview'];
      const el = document.getElementById(entry[0]);
      if (el) el.style.display = 'block';
      if (title) title.innerText = entry[1];
      if (breadcrumb) breadcrumb.innerHTML = entry[2];

      // Save active tab state persistently
      localStorage.setItem('activeAdminTab', 'view-' + viewName);

      const currentParams = new URLSearchParams(window.location.search);
      if (currentParams.get('view') !== viewName) {
        const newUrl = window.location.pathname + '?view=' + viewName;
        window.history.pushState({
          view: viewName
        }, '', newUrl);
      }

      document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove(
        'active'));
      const activeNav = document.getElementById(`nav-${viewName}`);
      if (activeNav) {
        activeNav.classList.add('active');
      }

      if (viewName === 'all-trainees' && typeof backToCourseCards ===
        'function') {
        backToCourseCards();
      }

      if (viewName === 'analytics' && typeof initHistoryChart === 'function') {
        setTimeout(initHistoryChart, 100);
      }

      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      if (window.innerWidth <= 768 && sidebar && overlay) {
        sidebar.classList.remove('sidebar-open');
        overlay.classList.remove('show');
      }
    }

    window.addEventListener('popstate', function() {
      const params = new URLSearchParams(window.location.search);
      const view = params.get('view') || 'overview';
      showView(view);
    });

    function toggleUpdates() {
      const extra = document.getElementById("extra-updates");
      const btn = document.getElementById("viewMoreBtn");
      if (extra && btn) {
        if (extra.style.display === "none" || extra.style.display === "") {
          extra.style.display = "flex";
          btn.innerHTML = `View Less <i class="fa-solid fa-chevron-up"></i>`;
        } else {
          extra.style.display = "none";
          btn.innerHTML = `View More <i class="fa-solid fa-chevron-down"></i>`;
        }
      }
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

      function openCourseModal(id, code, name, duration, slots, trainerId,
      trainerName, trainingId, status = 'active') {
      currentCourseId = id;
      document.getElementById('courseModal').style.display = 'block';
      document.querySelector('#courseModal h3').innerHTML =
        '<i class="fa-solid fa-pen-to-square"></i> Manage Course';
      document.querySelector('#courseModal .btn-delete-text').style.display =
        'inline-block';
      document.querySelector('.assign-trainer-section').style.display = 'block';

      document.getElementById('editCourseId').value = id;
      document.getElementById('editCourseCode').value = code || '';
      document.getElementById('editTrainingId').value = trainingId || '';
      document.getElementById('editCourseName').value = name;
      document.getElementById('editDuration').value = duration;
      document.getElementById('editSlots').value = slots;

      const statusSelect = document.getElementById('editStatus');
      if (statusSelect) {
        statusSelect.value = (status || 'active').toLowerCase();
      }

      document.getElementById('trainerDropdown').value = trainerId || '';
      trainerId && trainerName ? showCurrentTrainer(trainerName) :
        showNoTrainer();
    }

    function openAddCourseModal() {
      currentCourseId = null;
      document.getElementById('courseModal').style.display = 'block';
      document.querySelector('#courseModal h3').innerHTML =
        '<i class="fa-solid fa-folder-plus"></i> Create New Course';
      document.getElementById('courseForm').reset();
      document.querySelector('#courseModal .btn-delete-text').style.display =
        'none';

      document.querySelector('.assign-trainer-section').style.display = 'none';

      showNoTrainer();
    }

    function closeModal() {
      document.getElementById('courseModal').style.display = 'none';
      currentCourseId = null;
      document.querySelector('.assign-trainer-section').style.display = 'block';
    }

    function showCurrentTrainer(name) {
      const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2)
        .toUpperCase();
      document.getElementById('trainerInitials').textContent = initials;
      document.getElementById('trainerFullName').textContent = name;
      document.getElementById('currentTrainerBox').style.display = 'flex';
      document.getElementById('noTrainerBox').style.display = 'none';
    }

    function showNoTrainer() {
      document.getElementById('currentTrainerBox').style.display = 'none';
      document.getElementById('noTrainerBox').style.display = 'block';
    }

    function assignTrainer() {
      const dropdown = document.getElementById('trainerDropdown');
      const trainerId = dropdown.value;
      const trainerName = dropdown.selectedOptions[0].text;

      if (!trainerId) {
        alert('Please select a trainer first.');
        return;
      }
      if (!currentCourseId) {
        alert('Please save the course first before assigning a trainer.');
        return;
      }

      fetch(`/admin/course/${currentCourseId}/assign-trainer`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            trainer_id: trainerId
          }),
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showCurrentTrainer(trainerName);
            alert('Trainer assigned successfully!');
          }
        })
        .catch(() => alert('Something went wrong. Please try again.'));
    }

    function removeTrainer() {
      if (!currentCourseId) return;
      if (!confirm('Remove the assigned trainer from this course?')) return;

      fetch(`/admin/course/${currentCourseId}/remove-trainer`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showNoTrainer();
            document.getElementById('trainerDropdown').value = '';
            alert('Trainer removed successfully!');
          }
        })
        .catch(() => alert('Something went wrong. Please try again.'));
    }

    function confirmDelete() {
      const courseId = document.getElementById('editCourseId').value;

      if (!courseId) {
        alert('No course selected to delete.');
        return;
      }

      if (!confirm(
          'Are you sure you want to delete this course? This action cannot be undone.'
        )) {
        return;
      }

      fetch(`/admin/course/${courseId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(async res => {
          const data = await res.json();
          if (data.success) {
            alert('Course deleted successfully!');
            closeModal();
            window.location.href = window.location.pathname + '?view=courses';
          } else {
            alert(data.message ||
              'Could not delete course. Make sure to unassign any active trainers or enrolled trainees first.'
            );
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred while attempting to delete the course.');
        });
    }

    function openUserModal(idOrUser, name, email, role, status, courseTitle = '',
      contact = '', idNum = '', created = '', remarks = '') {
      // 1. Support both Object payload and positional parameters
      const isObj = typeof idOrUser === 'object' && idOrUser !== null;
      const data = isObj ? idOrUser : {
        id: idOrUser,
        name,
        email,
        role,
        status,
        courseTitle,
        contact,
        idNum,
        created,
        remarks
      };

      // Helper function to sanitize input strings
      const sanitize = (val, fallback = '') => {
        if (val === null || val === undefined) return fallback;
        const str = String(val).trim();
        if (str === '' || str === 'null' || str === 'undefined')
          return fallback;
        return str;
      };

      // 2. Display Modal
      const modal = document.getElementById('userModal');
      if (modal) {
        modal.style.display = 'block';
        modal.style.setProperty('display', 'block', 'important');
      }

      // 3. Set Inputs
      const fields = {
        'editUserId': sanitize(data.id),
        'editUserName': sanitize(data.name),
        'editUserEmail': sanitize(data.email),
        'editUserContact': sanitize(data.contact),
        'editUserIdNum': sanitize(data.idNum || data.id_number),
        'editUserCreated': sanitize(data.created, 'August 2026'),
        'editUserRemarks': sanitize(data.remarks)
      };

      Object.entries(fields).forEach(([fieldId, value]) => {
        const el = document.getElementById(fieldId);
        if (el) el.value = value;
      });

      // 4. Normalize & Assign Role Dropdown
      const cleanRole = sanitize(data.role, 'student').toLowerCase();
      const userRoleEl = document.getElementById('editUserRole');

      if (userRoleEl) {
        const matchedOption = Array.from(userRoleEl.options).find(
          opt => opt.value.toLowerCase() === cleanRole
        );
        userRoleEl.value = matchedOption ? matchedOption.value : (userRoleEl
          .options[0]?.value || cleanRole);
      }

      const hiddenRoleEl = document.getElementById('hiddenUserRole');
      if (hiddenRoleEl) hiddenRoleEl.value = cleanRole;

      // 5. Normalize & Assign Status Dropdown (Role-Specific Defaulting)
      const isTraineeRole = ['student', 'trainee', 'trainees'].includes(
        cleanRole);
      const defaultStatusFallback = isTraineeRole ? 'Pending' : 'Active';

      const statusSelect = document.getElementById('editUserStatus');
      if (statusSelect) {
        const rawStatus = sanitize(data.status, defaultStatusFallback)
          .toLowerCase();
        let cleanStatus = defaultStatusFallback;

        if (rawStatus.includes('inactive')) {
          cleanStatus = 'Inactive';
        } else if (rawStatus.includes('pending')) {
          cleanStatus = 'Pending';
        } else if (rawStatus.includes('active')) {
          cleanStatus = 'Active';
        }

        const matchedStatus = Array.from(statusSelect.options).find(
          opt => opt.value.toLowerCase() === cleanStatus.toLowerCase()
        );
        statusSelect.value = matchedStatus ? matchedStatus.value : cleanStatus;
      }

      // 6. Dynamic Label Logic: Trainee vs. Trainer / Admin
      const idNumLabel = document.getElementById('idNumLabel') ||
        document.querySelector('label[for="editUserIdNum"]');

      if (idNumLabel) {
        idNumLabel.textContent = isTraineeRole ? 'Unique Learner Identifier' :
          'Reference / ID Number';
      }

      // 7. Toggle Course/Trainer Visibility Fields
      const courseFieldContainer = document.getElementById('trainerCourseField');
      const courseInput = document.getElementById('editTrainerCourse');
      const trainerFieldsContainer = document.getElementById(
        'trainerFieldsContainer');

      const isTrainer = cleanRole === 'trainer';

      if (courseFieldContainer) {
        courseFieldContainer.style.display = isTrainer ? 'block' : 'none';
      }

      if (courseInput) {
        courseInput.value = isTrainer ? sanitize(data.courseTitle,
          'No course assigned') : '';
      }

      if (trainerFieldsContainer) {
        trainerFieldsContainer.style.display = 'block';
      }
    }

    function openFacilityModal(id, name, address, courseIds = []) {
      const modal = document.getElementById('facilityModal');
      const title = document.getElementById('facilityModalTitle') || document
        .querySelector('#facilityModal h3');
      const deleteBtn = document.querySelector('#facilityModal .btn-delete-text');

      if (modal) modal.style.display = 'flex';

      if (title) {
        title.innerHTML =
          '<i class="fa-solid fa-building-circle-gear"></i> Manage Facility';
      }
      if (deleteBtn) {
        deleteBtn.style.display = 'flex';
      }

      const idInput = document.getElementById('editFacId');
      if (idInput) idInput.value = id || '';

      const nameInput = document.getElementById('editFacName');
      const addrInput = document.getElementById('editFacAddress');
      if (nameInput) nameInput.value = name || '';
      if (addrInput) addrInput.value = address || '';

      const targetIds = Array.isArray(courseIds) ?
        courseIds.map(String) :
        (courseIds ? [String(courseIds)] : []);

      document.querySelectorAll('.facility-course-cb').forEach(cb => {
        cb.checked = targetIds.includes(String(cb.value));
      });

      if (typeof updateCourseBadgeCount === 'function') {
        updateCourseBadgeCount();
      }
    }

    function openAddFacilityModal() {
      const modal = document.getElementById('facilityModal');
      const title = document.getElementById('facilityModalTitle') || document
        .querySelector('#facilityModal h3');
      const form = document.getElementById('facilityForm');
      const deleteBtn = document.querySelector('#facilityModal .btn-delete-text');

      if (modal) modal.style.display = 'flex';

      if (title) {
        title.innerHTML =
          '<i class="fa-solid fa-building-circle-plus"></i> Add New Facility';
      }

      if (form) form.reset();

      const idInput = document.getElementById('editFacId');
      if (idInput) idInput.value = '';

      document.querySelectorAll('.facility-course-cb').forEach(cb => {
        cb.checked = false;
      });

      if (deleteBtn) {
        deleteBtn.style.display = 'none';
      }

      if (typeof updateCourseBadgeCount === 'function') {
        updateCourseBadgeCount();
      }
    }

function deleteFacility() {
  const id = document.getElementById('editFacId')?.value?.trim();

  if (!id) {
    showAlert('Cannot delete: Invalid or missing Facility ID.');
    return;
  }

  showConfirm('Are you sure you want to delete this facility? Any assigned courses will be unlinked.', function() {
    const csrfToken = typeof getCsrfToken === 'function' ?
      getCsrfToken() :
      document.querySelector('meta[name="csrf-token"]')?.getAttribute(
        'content');

    fetch('/admin/facility/delete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          id: id
        })
      })
      .then(async response => {
        const data = await response.json().catch(() => null);

        if (response.ok && data && data.success) {
          showAlert(data.message || 'Facility deleted successfully!');
          closeFacilityModal();

          localStorage.setItem('activeAdminTab', 'view-facilities');
          location.reload();
        } else {
          showAlert((data && data.message) ? data.message :
            'Failed to delete facility.');
        }
      })
      .catch(error => {
        console.error('Delete facility error:', error);
        showAlert('An error occurred while deleting the facility.');
      });
  });
}

    function closeFacilityModal() {
      const modal = document.getElementById('facilityModal');
      const form = document.getElementById('facilityForm');

      if (modal) {
        modal.style.display = 'none';
      }

      if (form) form.reset();
    }

    function openAddTrainerModal() {
      const modal = document.getElementById('addTrainerModal');
      if (modal) {
        modal.style.display = 'block';
      }
    }

    function closeAddTrainerModal() {
      const modal = document.getElementById('addTrainerModal');
      if (modal) {
        modal.style.display = 'none';
        modal.style.setProperty('display', 'none', 'important');
      }
      const form = document.getElementById('addTrainerForm');
      if (form) {
        form.reset();
      }
    }

    window.onclick = function(event) {
      if (event.target && event.target.classList.contains('modal')) {
        if (typeof closeModal === 'function') closeModal();
        if (typeof closeUserModal === 'function') closeUserModal();
        if (typeof closeFacilityModal === 'function') closeFacilityModal();
        if (typeof closeAddTrainerModal === 'function') closeAddTrainerModal();
        if (typeof closeAnnouncementModal === 'function')
          closeAnnouncementModal();
      }
    };

    document.getElementById('addTrainerForm').onsubmit = function(e) {
      e.preventDefault();

      const name = document.getElementById('newTrainerName').value.trim();
      const email = document.getElementById('newTrainerEmail').value.trim();
      const password = document.getElementById('newTrainerPass').value.trim();

      // Grab all the optional/extra fields from the modal
      const contactNumber = document.getElementById('newTrainerContact')?.value
        .trim() || null;
      const referenceId = document.getElementById('newTrainerReference')?.value
        .trim() || null;
      const role = document.getElementById('newTrainerRole')?.value ||
        'trainer';
      const status = document.getElementById('newTrainerStatus')?.value ||
        'Active';
      const remarks = document.getElementById('newTrainerRemarks')?.value
        .trim() || null;
      const courseId = document.getElementById('newTrainerCourse')?.value ||
        null;

      if (!name || !email || !password) {
        alert('Please fill in all required fields.');
        return;
      }

      fetch('/admin/trainer/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            name: name,
            email: email,
            password: password,
            contact_number: contactNumber,
            reference_id: referenceId,
            role: role,
            status: status,
            remarks: remarks,
            course_id: courseId,
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            alert('Trainer account created successfully!');
            closeAddTrainerModal();
            location.reload();
          } else {
            alert(data.message || 'An error occurred. Please try again.');
          }
        })
        .catch((err) => {
          console.error(err);
          alert('An error occurred. Please try again.');
        });
    };

    document.getElementById('courseForm').onsubmit = function(e) {
      e.preventDefault();

      const courseId = document.getElementById('editCourseId').value;
      const courseCode = document.getElementById('editCourseCode').value.trim();
      const trainingId = document.getElementById('editTrainingId').value.trim();
      const title = document.getElementById('editCourseName').value.trim();
      const duration = document.getElementById('editDuration').value.trim();
      const slots = document.getElementById('editSlots').value;
      const status = document.getElementById('editStatus').value;

      const isEdit = courseId !== '' && courseId !== null && courseId !==
        undefined;
      const url = isEdit ? `/admin/course/${courseId}` : '/admin/course/store';
      const method = isEdit ? 'PUT' : 'POST';

      fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
        body: JSON.stringify({
          course_code: courseCode,
          training_id: trainingId,
          title: title,
          duration: duration,
          slots: slots,
          status: status
        })
        })
        .then(async r => {
          const text = await r.text();
          try {
            return JSON.parse(text);
          } catch (err) {
            console.error("Server response was not JSON:", text);
            throw new Error("Server returned an invalid response.");
          }
        })
        .then(data => {
          if (data.success) {
            alert(isEdit ? 'Course updated successfully!' :
              'Course created successfully!');
            closeModal();
            window.location.href = window.location.pathname + '?view=courses';
          } else {
            alert(data.message || 'An error occurred while updating.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('An error occurred. Please try again.');
        });
    };

document.getElementById('facilityForm').onsubmit = function(e) {
  e.preventDefault();

  const idVal = document.getElementById('editFacId')?.value?.trim();
  const id = idVal ? idVal : null;

  const name = document.getElementById('editFacName').value.trim();
  const address = document.getElementById('editFacAddress').value.trim();

  const selectedCourseIds = Array.from(
    document.querySelectorAll('.facility-course-cb:checked')
  ).map(cb => cb.value);

  if (!name || !address) {
    showAlert('Please enter a facility name and address.');
    return;
  }

  const csrfToken = typeof getCsrfToken === 'function' ?
    getCsrfToken() :
    document.querySelector('meta[name="csrf-token"]')?.getAttribute(
      'content');

  fetch('/admin/facility/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        id: id,
        name: name,
        address: address,
        course_ids: selectedCourseIds
      })
    })
    .then(async response => {
      const data = await response.json().catch(() => null);

      if (response.ok && data && data.success) {
        showAlert(data.message || 'Facility details saved successfully!');
        closeFacilityModal();

        localStorage.setItem('activeAdminTab', 'view-facilities');
        location.reload();
      } else {
        let errorMsg = 'Failed to save facility details.';
        if (data && data.errors) {
          errorMsg = Object.values(data.errors).flat().join('\n');
        } else if (data && data.message) {
          errorMsg = data.message;
        }
        showAlert(errorMsg);
      }
    })
    .catch(error => {
      console.error('Facility save error:', error);
      showAlert('An error occurred while saving the facility.');
    });
};

    /* ========================================================== */
    /* ANNOUNCEMENT MODAL & ACTIONS HANDLERS                      */
    /* ========================================================== */
    // Utility helper to format database ISO/SQL timestamps for datetime-local inputs (YYYY-MM-DDTHH:mm)
    function formatForDateTimeInput(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      if (isNaN(date.getTime())) return '';

      const pad = (num) => String(num).padStart(2, '0');

      const year = date.getFullYear();
      const month = pad(date.getMonth() + 1);
      const day = pad(date.getDate());
      const hours = pad(date.getHours());
      const minutes = pad(date.getMinutes());

      return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Open Modal (New Announcement)
    function openAnnouncementModal() {
      document.getElementById('annId').value = '';
      document.getElementById('annTitle').value = '';
      document.getElementById('annMessage').value = '';
      document.getElementById('annType').value = 'reminder';
      document.getElementById('annAudience').value = 'general';

      const isActiveCb = document.getElementById('annIsActive');
      if (isActiveCb) isActiveCb.checked = true;

      document.getElementById('annPublishAt').value = '';
      document.getElementById('annExpiresAt').value = '';

      document.getElementById('titleCounter').textContent = '0/100';
      document.getElementById('messageCounter').textContent = '0/500';

      const statusLabel = document.getElementById('statusLabel');
      if (statusLabel) statusLabel.textContent = 'Active';

      document.getElementById('annModalTitle').innerHTML =
        '<i class="fa-solid fa-bell"></i> Add Announcement';
      document.getElementById('announcementModal').style.display = 'flex';
    }

    // Open Modal (Edit Announcement)
    function handleEditAnnouncement(button) {
      const id = button.getAttribute('data-id');
      const title = button.getAttribute('data-title') || '';
      const message = button.getAttribute('data-message') || '';
      const type = button.getAttribute('data-type') || 'reminder';
      const audience = button.getAttribute('data-audience') || 'general';
      const active = button.getAttribute('data-active') === '1';
      const publishAt = button.getAttribute('data-publish-at') || '';
      const expiresAt = button.getAttribute('data-expires-at') || '';

      document.getElementById('annId').value = id;
      document.getElementById('annTitle').value = title;
      document.getElementById('annMessage').value = message;
      document.getElementById('annType').value = type;
      document.getElementById('annAudience').value = audience;

      const isActiveCb = document.getElementById('annIsActive');
      if (isActiveCb) isActiveCb.checked = active;

      document.getElementById('annPublishAt').value = formatForDateTimeInput(
        publishAt);
      document.getElementById('annExpiresAt').value = formatForDateTimeInput(
        expiresAt);

      document.getElementById('titleCounter').textContent = `${title.length}/100`;
      document.getElementById('messageCounter').textContent =
        `${message.length}/500`;

      const statusLabel = document.getElementById('statusLabel');
      if (statusLabel) statusLabel.textContent = active ? 'Active' : 'Inactive';

      document.getElementById('annModalTitle').innerHTML =
        '<i class="fa-solid fa-pen-to-square"></i> Edit Announcement';
      document.getElementById('announcementModal').style.display = 'flex';
    }

    // Close Modal
    function closeAnnouncementModal() {
      const modal = document.getElementById('announcementModal');
      const form = document.getElementById('announcementForm');
      const btnSubmit = document.getElementById('btnSubmitAnn');

      if (modal) modal.style.display = 'none';
      if (form) form.reset();

      const titleCounter = document.getElementById('titleCounter');
      const messageCounter = document.getElementById('messageCounter');
      if (titleCounter) titleCounter.textContent = '0/100';
      if (messageCounter) messageCounter.textContent = '0/500';

      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = 'Save';
      }
    }

    // Submit Form Handler (Save/Update with Success & Error Feedback)
    document.getElementById('announcementForm').onsubmit = function(e) {
      e.preventDefault();

      const id = document.getElementById('annId')?.value || '';
      const title = document.getElementById('annTitle')?.value.trim() || '';
      const message = document.getElementById('annMessage')?.value.trim() || '';
      const type = document.getElementById('annType')?.value || 'reminder';
      const isActive = document.getElementById('annIsActive')?.checked ? 1 : 0;

      // Read SQL formatted datetime directly from input elements
      const publishAt = document.getElementById('annPublishAt')?.value || null;
      const expiresAt = document.getElementById('annExpiresAt')?.value || null;

      const btnSubmit = document.getElementById('btnSubmitAnn');
      const originalBtnText = btnSubmit ? btnSubmit.innerHTML : 'Save';

      if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
      }

      const isEdit = id !== '' && id !== null;
      const url = isEdit ? `/admin/announcement/${id}` : '/admin/announcement';

      const token = typeof csrfToken !== 'undefined' ?
        csrfToken :
        document.querySelector('meta[name="csrf-token"]')?.getAttribute(
          'content') || '';

      const payload = {
        title: title,
        message: message,
        type: type,
        is_active: isActive,
        publish_at: publishAt ? publishAt : null,
        expires_at: expiresAt ? expiresAt : null
      };

      if (isEdit) {
        payload._method = 'PUT';
      }

      fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok && data && data.success) {
            // Visual button feedback on success
            if (btnSubmit) {
              btnSubmit.style.backgroundColor = '#025628';
              btnSubmit.innerHTML = isEdit ?
                '<i class="fa-solid fa-check"></i> Updated Successfully!' :
                '<i class="fa-solid fa-check"></i> Created Successfully!';
            }

            setTimeout(() => {
              alert(data.message || (isEdit ?
                'Announcement updated successfully!' :
                'Announcement created successfully!'));
              closeAnnouncementModal();
              window.location.href = window.location.pathname +
                '?view=announcements';
            }, 200);

          } else {
            let errorMsg = 'Validation error. Check your inputs.';
            if (data && data.errors) {
              errorMsg = Object.values(data.errors).flat().join('\n');
            } else if (data && data.message) {
              errorMsg = data.message;
            }

            alert('Error saving announcement:\n' + errorMsg);

            if (btnSubmit) {
              btnSubmit.disabled = false;
              btnSubmit.style.backgroundColor = '';
              btnSubmit.innerHTML = originalBtnText;
            }
          }
        })
        .catch(err => {
          console.error('Save announcement error details:', err);
          alert(
            'An error occurred while saving. Check F12 Console for exact details.'
          );

          if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.style.backgroundColor = '';
            btnSubmit.innerHTML = originalBtnText;
          }
        });
    };

    // Delete Announcement Handler
    function deleteAnnouncement(id, btn = null) {
      if (!confirm(
          'Are you sure you want to delete this announcement? This action cannot be undone.'
        )) {
        return;
      }

      if (btn) btn.disabled = true;

      const token = typeof csrfToken !== 'undefined' ?
        csrfToken :
        document.querySelector('meta[name="csrf-token"]')?.getAttribute(
          'content') || '';

      fetch(`/admin/announcement/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          }
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok && data && data.success) {
            alert(data.message || 'Announcement deleted successfully!');
            window.location.href = window.location.pathname +
              '?view=announcements';
          } else {
            alert('Failed to delete announcement: ' + ((data && data
              .message) ? data.message : 'Server error'));
            if (btn) btn.disabled = false;
          }
        })
        .catch(err => {
          console.error('Delete announcement error:', err);
          alert('An error occurred while deleting the announcement.');
          if (btn) btn.disabled = false;
        });
    }

    // DOM Event Listeners Initialization
    document.addEventListener('DOMContentLoaded', function() {
      const isActiveCb = document.getElementById('annIsActive');
      const statusLabel = document.getElementById('statusLabel');
      const titleInput = document.getElementById('annTitle');
      const messageInput = document.getElementById('annMessage');
      const modal = document.getElementById('announcementModal');

      // Live Status Label Sync
      if (isActiveCb && statusLabel) {
        isActiveCb.addEventListener('change', function() {
          statusLabel.textContent = this.checked ? 'Active' : 'Inactive';
        });
      }

      // Live Character Counters
      if (titleInput) {
        titleInput.addEventListener('input', function() {
          document.getElementById('titleCounter').textContent =
            `${this.value.length}/100`;
        });
      }

      if (messageInput) {
        messageInput.addEventListener('input', function() {
          document.getElementById('messageCounter').textContent =
            `${this.value.length}/500`;
        });
      }

      // Close modal when clicking on dark backdrop
      window.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeAnnouncementModal();
        }
      });
    });

    // Helper: Format raw database ISO string to "YYYY-MM-DDTHH:mm" for input[type="datetime-local"]
    function formatForDateTimeInput(dateStr) {
      if (!dateStr) return '';
      const formatted = dateStr.trim().replace(' ', 'T');
      if (formatted.length >= 16) {
        return formatted.substring(0, 16);
      }
      const date = new Date(dateStr);
      if (isNaN(date.getTime())) return '';
      const pad = (num) => String(num).padStart(2, '0');
      return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    // Helper: Convert "YYYY-MM-DDTHH:mm" datetime-local value to SQL format "YYYY-MM-DD HH:mm:00" or null
    function cleanDateTimeInput(val) {
      if (!val || typeof val !== 'string' || val.trim() === '') return null;
      let formatted = val.trim().replace('T', ' ');
      if (formatted.length === 16) {
        formatted += ':00';
      }
      return formatted;
    }

    // Submit Form Handler
    document.addEventListener('DOMContentLoaded', function() {
      const annForm = document.getElementById('announcementForm');

      if (annForm) {
        // Clear any existing inline submit handlers to avoid duplicate triggers
        annForm.onsubmit = null;

        annForm.addEventListener('submit', function(e) {
          e.preventDefault();
          e
            .stopImmediatePropagation(); // Block duplicate event listeners from executing

          const btnSubmit = document.getElementById('btnSubmitAnn');

          // Double-submit protection: ignore if a request is already in progress
          if (btnSubmit && btnSubmit.disabled) {
            return;
          }

          const id = document.getElementById('annId')?.value || '';
          const title = document.getElementById('annTitle')?.value
            .trim() || '';
          const message = document.getElementById('annMessage')?.value
            .trim() || '';
          const type = document.getElementById('annType')?.value ||
            'reminder';
          const isActive = document.getElementById('annIsActive')
            ?.checked ? 1 : 0;
          const audience = document.getElementById('annAudience')?.value ||
            'general';

          const rawPublish = document.getElementById('annPublishAt')
            ?.value || '';
          const rawExpires = document.getElementById('annExpiresAt')
            ?.value || '';

          const originalBtnText = btnSubmit ? btnSubmit.innerHTML :
            'Save';

          // Immediately disable button to prevent double-click submissions
          if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML =
              '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
          }

          const isEdit = id !== '' && id !== null;
          const url = isEdit ? `/admin/announcement/${id}` :
            '/admin/announcement';

          const token = typeof csrfToken !== 'undefined' ?
            csrfToken :
            document.querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || '';

          const payload = {
            title: title,
            message: message,
            type: type,
            audience: audience,
            is_active: isActive,
            publish_at: typeof cleanDateTimeInput === 'function' ?
              cleanDateTimeInput(rawPublish) : (rawPublish || null),
            expires_at: typeof cleanDateTimeInput === 'function' ?
              cleanDateTimeInput(rawExpires) : (rawExpires || null)
          };

          if (isEdit) {
            payload._method = 'PUT';
          }

          fetch(url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
              },
              body: JSON.stringify(payload)
            })
            .then(async response => {
              const data = await response.json().catch(() => null);

              if (response.ok && data && data.success) {
                // Immediate visual button feedback
                if (btnSubmit) {
                  btnSubmit.style.backgroundColor = '#025628';
                  btnSubmit.innerHTML = isEdit ?
                    '<i class="fa-solid fa-check"></i> Updated Successfully!' :
                    '<i class="fa-solid fa-check"></i> Created Successfully!';
                }

                setTimeout(() => {
                  alert(data.message || (isEdit ?
                    'Announcement updated successfully!' :
                    'Announcement created successfully!'));
                  closeAnnouncementModal();
                  window.location.href = window.location
                    .pathname + '?view=announcements';
                }, 200);

              } else {
                let errorMsg =
                  'Validation error. Please check your form fields.';
                if (data && data.errors) {
                  errorMsg = Object.values(data.errors).flat().join(
                    '\n');
                } else if (data && data.message) {
                  errorMsg = data.message;
                }

                alert('Error saving announcement:\n' + errorMsg);

                if (btnSubmit) {
                  btnSubmit.disabled = false;
                  btnSubmit.style.backgroundColor = '';
                  btnSubmit.innerHTML = originalBtnText;
                }
              }
            })
            .catch(err => {
              console.error('Save announcement error details:', err);
              alert(
                'An error occurred while saving. Check Console (F12) for details.'
              );

              if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.style.backgroundColor = '';
                btnSubmit.innerHTML = originalBtnText;
              }
            });
        });
      }
    });

    function deleteAnn(id) {
      if (!confirm('Are you sure you want to delete this announcement?')) return;
      fetch(`/admin/announcement/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) location.reload();
        })
        .catch(() => alert('An error occurred. Please try again.'));
    }

    let _contentCourseId = null;
    let _contentModules = [];
    let _contentQuizzes = [];

    function openContentModal(courseId, courseTitle) {
      _contentCourseId = courseId;
      document.getElementById('contentModalCourseTitle').textContent =
        courseTitle;
      document.getElementById('contentModal').style.display = 'block';
      switchContentTab('modules');
      fetchCourseContent(courseId);
    }

    function closeContentModal() {
      document.getElementById('contentModal').style.display = 'none';
      _contentCourseId = null;
      _contentModules = [];
      _contentQuizzes = [];
    }

    function switchContentTab(tab) {
      const isMod = tab === 'modules';
      document.getElementById('content-tab-modules').style.display = isMod ?
        'block' : 'none';
      document.getElementById('content-tab-quizzes').style.display = isMod ?
        'none' : 'block';

      const styleActive =
        'border-bottom:2px solid #025628; margin-bottom:-2px; color:#025628;';
      const styleInactive = 'border-bottom:none; color:#aaa;';
      document.getElementById('tab-btn-modules').style.cssText += isMod ?
        styleActive : styleInactive;
      document.getElementById('tab-btn-quizzes').style.cssText += isMod ?
        styleInactive : styleActive;

      if (tab === 'quizzes') populateQuizModuleDropdown();
    }

    function fetchCourseContent(courseId) {
      fetch(`/admin/course/${courseId}/content`, {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(r => r.json())
        .then(data => {
          _contentModules = data.modules || [];
          _contentQuizzes = data.quizzes || [];
          renderModules();
          renderQuizzes();
          updateCourseCardCounts();
        })
        .catch(() => alert('Failed to load course content.'));
    }

    function renderModules() {
      const container = document.getElementById('moduleListContainer');
      if (!container) return;

      if (!_contentModules || !_contentModules.length) {
        container.innerHTML = `
      <div style="text-align:center; color:#bbb; font-size:13px; padding:20px 0;" id="modulesEmptyState">
        <i class="fa-solid fa-inbox" style="font-size:24px; display:block; margin-bottom:6px; color:#ccc;"></i>
        No modules created yet.
      </div>
    `;
        return;
      }

      container.innerHTML = _contentModules.map((m, i) => `
    <div id="module-card-${m.id}" style="display:flex; align-items:center; justify-content:space-between; gap:12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:12px 16px; margin-bottom:8px; box-shadow:0 1px 2px rgba(0,0,0,0.03);">
      
      <div style="display:flex; align-items:center; gap:12px; flex:1; min-width:0;">
        <div style="width:30px; height:30px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#025628; flex-shrink:0;">
          ${i + 1}
        </div>

        <div style="flex:1; min-width:0;">
          <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <strong style="font-size:14px; color:#1f2937;">${escHtml(m.title)}</strong>
            
            <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:12px; background:${(m.is_active !== false && m.is_published !== false) ? '#e8f5e9' : '#fff8e1'}; color:${(m.is_active !== false && m.is_published !== false) ? '#025628' : '#854F0B'};">
              ${(m.is_active !== false && m.is_published !== false) ? 'Published' : 'Draft'}
            </span>
          </div>
          
          <div style="font-size:12px; color:#6b7280; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            ${m.description ? escHtml(m.description) : '<span style="color:#b0b0b0; font-style:italic;">No description</span>'}
          </div>
        </div>
      </div>

      <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
        ${m.file_path ? `
                                                                                                                                                                                                                                                                                                                                                                                                  <a href="/admin/module/file/${m.id}/${encodeURIComponent(m.title)}.pdf" target="_blank" 
                                                                                                                                                                                                                                                                                                                                                                                                     style="font-size:11px; padding:6px 12px; border-radius:6px; background:#e8f5e9; color:#025628; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; transition: background 0.2s;">
                                                                                                                                                                                                                                                                                                                                                                                                    <i class="fa-solid fa-file-pdf"></i> View File
                                                                                                                                                                                                                                                                                                                                                                                                  </a>
                                                                                                                                                                                                                                                                                                                                                                                                ` : `
                                                                                                                                                                                                                                                                                                                                                                                                  <span style="font-size:11px; color:#9ca3af; padding:4px 8px; font-style:italic;">No PDF</span>
                                                                                                                                                                                                                                                                                                                                                                                                `}

        <button type="button" onclick="deleteModule(${m.id})"
          style="font-size:11px; padding:6px 12px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap; display:inline-flex; align-items:center; gap:4px;">
          <i class="fa-solid fa-trash"></i> Remove
        </button>
      </div>
    </div>
  `).join('');
    }

    function renderQuizzes() {
      const container = document.getElementById('quizListContainer');
      const empty = document.getElementById('quizzesEmptyState');

      const validQuizzes = (_contentQuizzes || []).filter(q => q && typeof q ===
        'object');

      if (!validQuizzes.length) {
        if (empty) empty.style.display = 'block';
        if (container) {
          container.innerHTML = '';
          if (empty) container.appendChild(empty);
        }
        return;
      }
      if (empty) empty.style.display = 'none';

      if (container) {
        container.innerHTML = validQuizzes.map(q => `
      <div style="display:flex; flex-direction:column; gap:0; background:#fff; border:1px solid #eee; border-radius:10px; overflow:hidden;">
        <div style="display:flex; align-items:center; gap:10px; padding:10px 14px;">
          <div style="width:32px; height:32px; border-radius:8px; background:#fff8e1; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0;">
            📝
          </div>
          <div style="flex:1; min-width:0;">
            <div style="font-size:13px; font-weight:700; color:#1a1a1a;">${escHtml(q.title)}</div>
            <div style="font-size:11px; color:#888;">
              ${q.module ? `<i class="fa-solid fa-cube"></i> ${escHtml(q.module.title)} &nbsp;·&nbsp;` : ''}
              <i class="fa-solid fa-clock"></i> ${q.time_limit || 30}m &nbsp;·&nbsp;
              <i class="fa-solid fa-star"></i> ${q.passing_score || 75}% passing
            </div>
          </div>
          <button onclick="toggleQuizQuestions(${q.id}, this)"
            style="font-size:11px; padding:4px 10px; border-radius:6px; background:#e8f5e9; color:#025628; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap;">
            <i class="fa-solid fa-list"></i> Questions
          </button>
          <button onclick="deleteQuiz(${q.id})"
            style="font-size:11px; padding:4px 10px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; white-space:nowrap;">
            <i class="fa-solid fa-trash"></i> Remove
          </button>
        </div>
        <div id="quiz-questions-${q.id}" style="display:none; border-top:1px solid #eee; padding:12px 14px; background:#fafafa;">
          <div id="qlist-${q.id}" style="display:flex; flex-direction:column; gap:6px; margin-bottom:10px;"></div>
          <div style="background:#fff; border:1px solid #eee; border-radius:8px; padding:12px;">
            <div style="font-size:12px; font-weight:700; color:#025628; margin-bottom:8px; text-transform:uppercase;">
              <i class="fa-solid fa-plus"></i> Add Question
            </div>
            <textarea id="qtext-${q.id}" placeholder="Question text..." rows="2"
              style="width:100%; border:1px solid #ddd; border-radius:8px; padding:8px; font-size:13px; font-family:inherit; margin-bottom:8px; resize:vertical;"></textarea>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:8px;">
              <input type="text" id="qa-${q.id}" placeholder="A. Choice A" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
              <input type="text" id="qb-${q.id}" placeholder="B. Choice B" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
              <input type="text" id="qc-${q.id}" placeholder="C. Choice C" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
              <input type="text" id="qd-${q.id}" placeholder="D. Choice D" style="border:1px solid #ddd; border-radius:8px; padding:7px 10px; font-size:13px; font-family:inherit;">
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
              <label style="font-size:12px; color:#666;">Correct answer:</label>
              <select id="qans-${q.id}" style="border:1px solid #ddd; border-radius:8px; padding:6px 10px; font-size:13px; font-family:inherit; background:#fff;">
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
              </select>
              <button onclick="addQuestion(${q.id})"
                style="background:#025628; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; margin-left:auto;">
                <i class="fa-solid fa-plus"></i> Add
              </button>
            </div>
          </div>
        </div>
      </div>
    `).join('');
      }
    }

    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.content ||
        (typeof csrfToken !== 'undefined' ? csrfToken : '');
    }

    function addModule(event) {
      if (event && typeof event.preventDefault === 'function') {
        event.preventDefault();
      }

      const titleInput = document.getElementById('newModuleTitle');
      const descInput = document.getElementById('newModuleDesc');
      const fileInput = document.getElementById('newModuleFile');

      const title = titleInput?.value.trim() || '';
      const desc = descInput?.value.trim() || '';
      const file = fileInput?.files[0];

      if (!title) {
        alert('Please enter a module title.');
        titleInput?.focus();
        return;
      }

      if (typeof _contentCourseId === 'undefined' || !_contentCourseId) {
        alert('No active course selected.');
        return;
      }

      const formData = new FormData();
      formData.append('course_id', _contentCourseId);
      formData.append('title', title);
      formData.append('description', desc);
      if (file) {
        formData.append('file', file);
      }

      fetch('/admin/module', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok && data && data.success) {
            if (!Array.isArray(_contentModules)) {
              _contentModules = [];
            }

            const rawModule = data.module || data.data || data;

            const newModule = {
              id: rawModule.id || Date.now(),
              title: rawModule.title || title,
              description: rawModule.description || desc,
              file_path: rawModule.file_path || null,
              is_active: rawModule.is_active ?? true
            };

            _contentModules.push(newModule);

            if (titleInput) titleInput.value = '';
            if (descInput) descInput.value = '';
            if (fileInput) fileInput.value = '';

            renderModules();
            updateCourseCardCounts();
            if (typeof populateQuizModuleDropdown === 'function') {
              populateQuizModuleDropdown();
            }
          } else {
            let errorMsg = 'Failed to create module.';
            if (data && data.errors) {
              errorMsg = Object.values(data.errors).flat().join('\n');
            } else if (data && data.message) {
              errorMsg = data.message;
            }
            alert(errorMsg);
          }
        })
        .catch(error => {
          console.error('Add module request error:', error);
          alert(
            'An error occurred while uploading the module. Please try again.');
        });
    }

    function deleteModule(id) {
      if (!confirm('Are you sure you want to remove this module?')) return;

      fetch(`/admin/module/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
          }
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok || response.status === 404) {
            if (Array.isArray(_contentModules)) {
              _contentModules = _contentModules.filter(m => m && String(m
                .id) !== String(id));
            }

            try {
              renderModules();
            } catch (e) {
              console.error('Render modules error:', e);
            }
            try {
              updateCourseCardCounts();
            } catch (e) {
              console.error('Update counts error:', e);
            }
            try {
              if (typeof populateQuizModuleDropdown === 'function') {
                populateQuizModuleDropdown();
              }
            } catch (e) {
              console.error('Update dropdown error:', e);
            }
          } else {
            const errorMsg = (data && data.message) ? data.message :
              `Server error (${response.status})`;
            alert(errorMsg);
          }
        })
        .catch(error => {
          console.error('Delete module fetch request failed:', error);
          if (Array.isArray(_contentModules)) {
            _contentModules = _contentModules.filter(m => m && String(m.id) !==
              String(id));
            try {
              renderModules();
            } catch (e) {}
            try {
              updateCourseCardCounts();
            } catch (e) {}
          }
          alert('Network error while attempting to delete the module.');
        });
    }

    function addQuiz(event) {
      if (event && typeof event.preventDefault === 'function') {
        event.preventDefault();
      }

      const titleInput = document.getElementById('newQuizTitle');
      const title = titleInput?.value.trim() || '';
      const moduleId = document.getElementById('newQuizModule')?.value || null;
      const passing = parseInt(document.getElementById('newQuizPass')?.value) ||
        75;
      const time = parseInt(document.getElementById('newQuizTime')?.value) || 30;

      if (!title) {
        alert('Please enter a quiz title.');
        titleInput?.focus();
        return;
      }

      fetch('/admin/quiz', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            course_id: _contentCourseId,
            module_id: moduleId,
            title: title,
            passing_score: passing,
            time_limit: time
          })
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok && data && data.success) {
            if (!Array.isArray(_contentQuizzes)) _contentQuizzes = [];
            const newQuiz = data.quiz || data.data || {
              id: Date.now(),
              title: title,
              time_limit: time,
              passing_score: passing
            };
            _contentQuizzes.push(newQuiz);

            if (titleInput) titleInput.value = '';

            renderQuizzes();
            updateCourseCardCounts();
          } else {
            alert((data && data.message) ? data.message :
              'Failed to create quiz.');
          }
        })
        .catch(error => {
          console.error('Add quiz request error:', error);
          alert('An error occurred while creating the quiz.');
        });
    }

    function deleteQuiz(id) {
      if (!confirm('Are you sure you want to remove this quiz?')) return;

      fetch(`/admin/quiz/${id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            _method: 'DELETE'
          })
        })
        .then(async response => {
          const data = await response.json().catch(() => null);

          if (response.ok || response.status === 404) {
            if (Array.isArray(_contentQuizzes)) {
              _contentQuizzes = _contentQuizzes.filter(q => q && String(q
                .id) !== String(id));
            }

            renderQuizzes();
            updateCourseCardCounts();
          } else {
            alert((data && data.message) ? data.message :
              `Server error (${response.status})`);
          }
        })
        .catch(error => {
          console.error('Delete quiz error:', error);
          if (Array.isArray(_contentQuizzes)) {
            _contentQuizzes = _contentQuizzes.filter(q => q && String(q.id) !==
              String(id));
            renderQuizzes();
            updateCourseCardCounts();
          }
        });
    }

    function populateQuizModuleDropdown() {
      const sel = document.getElementById('newQuizModule');
      if (!sel) return;
      sel.innerHTML = '<option value="">— Link to module (optional) —</option>';

      if (Array.isArray(_contentModules)) {
        _contentModules.forEach(m => {
          const opt = document.createElement('option');
          opt.value = m.id;
          opt.textContent = m.title;
          sel.appendChild(opt);
        });
      }
    }

    function escHtml(str) {
      const div = document.createElement('div');
      div.appendChild(document.createTextNode(str || ''));
      return div.innerHTML;
    }

    function toggleQuizQuestions(quizId, btn) {
      const panel = document.getElementById(`quiz-questions-${quizId}`);
      const isOpen = panel.style.display !== 'none';
      panel.style.display = isOpen ? 'none' : 'block';
      if (!isOpen) loadQuizQuestions(quizId);
    }

    function loadQuizQuestions(quizId) {
      fetch(`/admin/quiz/${quizId}/questions`, {
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(r => r.json())
        .then(data => renderQuizQuestions(quizId, data.questions || []))
        .catch(() => alert('Failed to load questions.'));
    }

    function renderQuizQuestions(quizId, questions) {
      const container = document.getElementById(`qlist-${quizId}`);
      if (!questions.length) {
        container.innerHTML =
          '<div style="font-size:12px; color:#aaa; text-align:center; padding:8px;">No questions created yet.</div>';
        return;
      }
      container.innerHTML = questions.map((q, i) => `
        <div style="display:flex; align-items:flex-start; gap:8px; background:#fff; border:1px solid #eee; border-radius:8px; padding:8px 12px;">
            <div style="width:22px; height:22px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#025628; flex-shrink:0; margin-top:1px;">
                ${i+1}
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:600; color:#1a1a1a; margin-bottom:4px;">${escHtml(q.question)}</div>
                <div style="font-size:11px; color:#555; display:grid; grid-template-columns:1fr 1fr; gap:2px;">
                    <span ${q.correct_answer==='a' ? 'style="color:#025628; font-weight:700;"' : ''}>A. ${escHtml(q.choice_a)}</span>
                    <span ${q.correct_answer==='b' ? 'style="color:#025628; font-weight:700;"' : ''}>B. ${escHtml(q.choice_b)}</span>
                    <span ${q.correct_answer==='c' ? 'style="color:#025628; font-weight:700;"' : ''}>C. ${escHtml(q.choice_c)}</span>
                    <span ${q.correct_answer==='d' ? 'style="color:#025628; font-weight:700;"' : ''}>D. ${escHtml(q.choice_d)}</span>
                </div>
            </div>
            <button onclick="deleteQuestion(${q.id}, ${quizId})"
                style="font-size:11px; padding:3px 8px; border-radius:6px; background:#FCEBEB; color:#A32D2D; border:none; cursor:pointer; font-family:inherit; font-weight:700; flex-shrink:0;">
                ✕
            </button>
        </div>
      `).join('');
    }

    function addQuestion(quizId) {
      const question = document.getElementById(`qtext-${quizId}`).value.trim();
      const a = document.getElementById(`qa-${quizId}`).value.trim();
      const b = document.getElementById(`qb-${quizId}`).value.trim();
      const c = document.getElementById(`qc-${quizId}`).value.trim();
      const d = document.getElementById(`qd-${quizId}`).value.trim();
      const ans = document.getElementById(`qans-${quizId}`).value;

      if (!question || !a || !b || !c || !d) {
        alert('Please fill in all question fields.');
        return;
      }

      fetch('/admin/quiz-question', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            quiz_id: quizId,
            question,
            choice_a: a,
            choice_b: b,
            choice_c: c,
            choice_d: d,
            correct_answer: ans
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.getElementById(`qtext-${quizId}`).value = '';
            document.getElementById(`qa-${quizId}`).value = '';
            document.getElementById(`qb-${quizId}`).value = '';
            document.getElementById(`qc-${quizId}`).value = '';
            document.getElementById(`qd-${quizId}`).value = '';
            loadQuizQuestions(quizId);
          }
        })
        .catch(() => alert('An error occurred. Please try again.'));
    }

    function deleteQuestion(id, quizId) {
      if (!confirm('Remove this question?')) return;
      fetch(`/admin/quiz-question/${id}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            _method: 'DELETE'
          })
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) loadQuizQuestions(quizId);
        })
        .catch(() => alert('An error occurred. Please try again.'));
    }

    function toggleSelectCol() {
      const show = document.getElementById('toggleMultiple').checked;
      document.querySelectorAll('.cert-select-col').forEach(el => {
        el.style.display = show ? '' : 'none';
      });
      if (!show) document.getElementById('selectAll').checked = false;
    }

    function toggleSelectAll(cb) {
      document.querySelectorAll('.row-checkbox').forEach(c => c.checked = cb
        .checked);
    }

    function deleteCertRow(btn) {
      if (!confirm('Delete this certificate record?')) return;
      btn.closest('tr').remove();
    }

    function openCertViewModal(name, course, certNo) {
      document.getElementById('certViewName').textContent = name;
      document.getElementById('certViewCourse').textContent = course
        .toUpperCase();
      document.getElementById('certViewNo').textContent = 'CERT. NO.: ' + certNo;
      document.getElementById('certViewModal').style.display = 'flex';
    }

    function closeCertViewModal() {
      document.getElementById('certViewModal').style.display = 'none';
    }

    function openIssueCertModal() {
      document.getElementById('issueCertModal').style.display = 'flex';
      updateCertPreview();
    }

    function closeIssueCertModal() {
      document.getElementById('issueCertModal').style.display = 'none';
    }

    function updateCertPreview() {
      const sel = document.getElementById('issueTraineeSelect');
      const nameEl = document.getElementById('previewName');
      const crsEl = document.getElementById('previewCourse');
      if (!sel || !nameEl) return;
      const rawName = sel.value ? sel.value.split(' (')[0] : '[NAME]';
      const course = sel.value && sel.selectedIndex > 0 ?
        sel.options[sel.selectedIndex].getAttribute('data-course') || '[COURSE]' :
        '[COURSE]';
      nameEl.textContent = rawName.toUpperCase();
      crsEl.textContent = course.toUpperCase();
    }

    function generateCertPDF({
      name,
      course,
      controlNumber,
      dateLabel,
      docType,
      remarks
    }) {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'mm',
        format: 'a4'
      });
      const W = 297,
        H = 210;

      doc.setFillColor(255, 255, 255);
      doc.rect(0, 0, W, H, 'F');

      doc.setDrawColor(2, 86, 40);
      doc.setLineWidth(4);
      doc.rect(8, 8, W - 16, H - 16);

      doc.setDrawColor(180, 150, 50);
      doc.setLineWidth(1);
      doc.rect(12, 12, W - 24, H - 24);

      doc.setFont('helvetica', 'normal');
      doc.setFontSize(8);
      doc.setTextColor(100, 100, 100);
      doc.text('TECHNICAL EDUCATION AND SKILLS DEVELOPMENT AUTHORITY', W / 2,
        36, {
          align: 'center'
        });

      doc.setFontSize(9);
      doc.setTextColor(60, 60, 60);
      doc.text('CITY GOVERNMENT OF DASMARIÑAS – LEDIPO', W / 2, 43, {
        align: 'center'
      });

      doc.setDrawColor(180, 150, 50);
      doc.setLineWidth(0.8);
      doc.line(55, 47, W - 55, 47);

      doc.setFont('helvetica', 'bold');
      doc.setFontSize(22);
      doc.setTextColor(2, 86, 40);
      doc.text(docType.toUpperCase(), W / 2, 63, {
        align: 'center'
      });

      doc.setFont('helvetica', 'italic');
      doc.setFontSize(10);
      doc.setTextColor(90, 90, 90);
      doc.text('This is to certify that', W / 2, 75, {
        align: 'center'
      });

      doc.setFont('helvetica', 'bold');
      doc.setFontSize(26);
      doc.setTextColor(15, 15, 15);
      doc.text(name.toUpperCase(), W / 2, 91, {
        align: 'center'
      });

      const nameW = doc.getTextWidth(name.toUpperCase());
      doc.setDrawColor(2, 86, 40);
      doc.setLineWidth(0.5);
      doc.line(W / 2 - nameW / 2, 94, W / 2 + nameW / 2, 94);

      doc.setFont('helvetica', 'normal');
      doc.setFontSize(10);
      doc.setTextColor(80, 80, 80);
      doc.text('has successfully completed the training in', W / 2, 105, {
        align: 'center'
      });

      doc.setFont('helvetica', 'bold');
      doc.setFontSize(15);
      doc.setTextColor(2, 86, 40);
      doc.text(course.toUpperCase(), W / 2, 117, {
        align: 'center'
      });

      doc.setFont('helvetica', 'normal');
      doc.setFontSize(9);
      doc.setTextColor(90, 90, 90);
      doc.text(`held on ${dateLabel}`, W / 2, 126, {
        align: 'center'
      });

      if (remarks && remarks.trim()) {
        doc.setFontSize(9);
        doc.setTextColor(110, 110, 110);
        doc.text(`Remarks: ${remarks}`, W / 2, 134, {
          align: 'center'
        });
      }

      if (controlNumber && controlNumber.trim()) {
        doc.setFontSize(8);
        doc.setTextColor(160, 160, 160);
        doc.text(`Control No.: ${controlNumber}`, W - 18, H - 15, {
          align: 'right'
        });
      }

      const sig1X = 80,
        sig2X = W - 80,
        sigY = H - 38;
      doc.setDrawColor(50, 50, 50);
      doc.setLineWidth(0.5);
      doc.line(sig1X - 35, sigY, sig1X + 35, sigY);
      doc.line(sig2X - 35, sigY, sig2X + 35, sigY);

      doc.setFont('helvetica', 'bold');
      doc.setFontSize(9);
      doc.setTextColor(20, 20, 20);
      doc.text('HON. JENNIFER A. BARZAGA', sig1X, sigY + 6, {
        align: 'center'
      });
      doc.text('MR. CARLOS H. LEGASPI', sig2X, sigY + 6, {
        align: 'center'
      });

      doc.setFont('helvetica', 'normal');
      doc.setFontSize(8);
      doc.setTextColor(100, 100, 100);
      doc.text('City Mayor', sig1X, sigY + 11, {
        align: 'center'
      });
      doc.text('LEDIPO Head', sig2X, sigY + 11, {
        align: 'center'
      });

      return doc;
    }

    function saveAndIssueCert() {
      const sel = document.getElementById('issueTraineeSelect');
      const controlNum = document.getElementById('issueControlNum').value.trim();
      const dateInput = document.getElementById('issueDate').value;
      const docType = document.getElementById('issueDocType').value;
      const remarks = document.getElementById('issueRemarks').value.trim();

      if (!sel.value) {
        alert('Please select a trainee first.');
        return;
      }

      const name = sel.value.split(' (')[0];
      const course = sel.options[sel.selectedIndex].getAttribute('data-course') ||
        '';

      const dateLabel = dateInput ?
        new Date(dateInput + 'T12:00:00').toLocaleDateString('en-PH', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        }) :
        new Date().toLocaleDateString('en-PH', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

      const doc = generateCertPDF({
        name,
        course,
        controlNumber: controlNum,
        dateLabel,
        docType,
        remarks
      });
      const safeName = name.replace(/[^a-zA-Z0-9]/g, '_');
      doc.save(`LEDIPO_Certificate_${safeName}.pdf`);

      addCertTableRow(name, course, dateLabel, controlNum);
      closeIssueCertModal();
      alert('Certificate issued and downloaded successfully!');
    }

    function downloadExistingCert() {
      const name = document.getElementById('certViewName').textContent;
      const course = document.getElementById('certViewCourse').textContent;
      const certNo = document.getElementById('certViewNo').textContent.replace(
        'CERT. NO.: ', '');

      const dateLabel = new Date().toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      const doc = generateCertPDF({
        name,
        course,
        controlNumber: certNo,
        dateLabel,
        docType: 'Certificate of Completion',
        remarks: ''
      });

      const safeName = name.replace(/[^a-zA-Z0-9]/g, '_');
      doc.save(`LEDIPO_Certificate_${safeName}.pdf`);
    }

    function addCertTableRow(name, course, dateLabel, controlNum) {
      const tbody = document.getElementById('certTableBody');
      if (!tbody) return;

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="cert-select-col" style="display:none;"><input type="checkbox" class="row-checkbox"></td>
        <td>${name}</td>
        <td>${course}</td>
        <td>${dateLabel}</td>
        <td><span class="cert-badge claimed">Claimed</span></td>
        <td class="cert-action-icons">
            <i class="fa fa-eye" onclick="openCertViewModal('${name}','${course}','${controlNum || 'N/A'}')" title="View"></i>
            <i class="fa fa-trash-alt" onclick="deleteCertRow(this)" title="Delete"></i>
        </td>
      `;
      tbody.insertBefore(tr, tbody.firstChild);
    }

    function toggleCourseAccordion(courseId) {
      const body = document.getElementById(`accordion-body-${courseId}`);
      const chevron = document.getElementById(`chevron-${courseId}`);

      if (body.style.display === 'none' || body.style.display === '') {
        body.style.display = 'block';
        chevron.style.transform = 'rotate(180deg)';
      } else {
        body.style.display = 'none';
        chevron.style.transform = 'rotate(0deg)';
      }
    }

    function toggleCourseTrainees(courseId) {
      const listEl = document.getElementById('trainee-list-' + courseId);
      if (listEl) {
        if (listEl.style.display === 'none' || listEl.style.display === '') {
          listEl.style.display = 'flex';
        } else {
          listEl.style.display = 'none';
        }
      }
    }

    function openFullCourseRoster(courseTitle, trainees) {
      document.getElementById('course-cards-main-view').style.display = 'none';
      document.getElementById('full-course-roster-view').style.display = 'block';

      document.getElementById('rosterCourseTitle').textContent = courseTitle +
        " - Enrolled Trainees";
      document.getElementById('rosterCountBadge').textContent = (trainees ?
        trainees.length : 0) + " Enrolled";

      const container = document.getElementById('fullRosterContainer');

      if (!trainees || trainees.length === 0) {
        container.innerHTML = `
      <div style="text-align: center; color: #aaa; padding: 40px 0; font-size: 13px; font-style: italic;">
          <i class="fa-solid fa-users-slash" style="font-size: 28px; display: block; margin-bottom: 8px; color: #ccc;"></i>
          No trainees enrolled in this course yet.
      </div>`;
      } else {
        let html = `
      <div style="display: flex; align-items: center; justify-content: space-between; background: #e8f5e9; padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; color: #025628; margin-bottom: 4px;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input type="checkbox" id="selectAllTrainees" onclick="toggleSelectAllTrainees(this)" style="width: 16px; height: 16px; accent-color: #025628; cursor: pointer;">
              Select All Trainees
          </label>
          <span id="selectedCountLabel">0 selected</span>
      </div>
    `;

        html += trainees.map(t => {
          // Map all required attributes from DB payload
          const userId = t.user_id || t.id || '';
          const firstName = t.firstname || '';
          const lastName = t.lastname || '';
          const fullName = (firstName + ' ' + lastName).trim()
            .toUpperCase() || 'UNKNOWN TRAINEE';
          const initials = ((firstName[0] || '') + (lastName[0] || ''))
            .toUpperCase() || 'TR';
          const email = t.email || 'No email provided';
          const contact = t.contact || '';
          const idNum = t.id_number || 'N/A'; // Learner ID
          const status = t.status ||
            'Pending'; // Default to Pending for trainees
          const remarks = t.remarks || '';
          const role = t.role || 'student';

          // Safe Date Parsing
          let created = 'August 2026';
          if (t.member_since) {
            const parsedDate = new Date(t.member_since);
            if (!isNaN(parsedDate.getTime())) {
              created = parsedDate.toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
              });
            }
          } else if (t.created) {
            created = t.created;
          }

          // Role & Status Badge Color Logic
          const cleanStatus = status.toLowerCase();
          let statusBg = '#f0f0f0'; // Gray Background
          let statusColor = '#555555'; // Dark Gray Text

          if (cleanStatus === 'inactive') {
            statusBg = '#FCEBEB'; // Soft Red
            statusColor = '#A32D2D';
          } else if (cleanStatus === 'active') {
            statusBg = '#e8f5e9'; // Active Green
            statusColor = '#025628';
          }

          const safeEmailId = email.replace(/[^a-zA-Z0-9]/g, '_');

          // String escape helper for inline onclick attributes
          const escapeAttr = (str) => String(str || '').replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'").replace(/\n/g, ' ');

          return `
        <div id="roster-row-${safeEmailId}" class="user-item" data-user-id="${userId}" style="display: flex; justify-content: space-between; align-items: center; background: #f9f9f9; padding: 12px 16px; border-radius: 8px; border: 1px solid #eee;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <input type="checkbox" class="roster-checkbox" onclick="updateSelectedCount()" style="width: 16px; height: 16px; accent-color: #025628; cursor: pointer;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #025628; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                    ${initials}
                </div>
                <div>
                    <strong class="user-name-text" style="color: #1a1a1a; display: block; font-size: 13px;">${fullName}</strong>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                        <small class="user-email-value" style="color: #888; font-size: 11.5px;">${email}</small>
                        <span style="color: #ccc; font-size: 10px;">•</span>
                        <small style="color: #025628; font-size: 11px; font-weight: 600; background: #e8f5e9; padding: 1px 6px; border-radius: 4px;">
                          ULI: <span class="user-id-value">${idNum}</span>
                        </small>
                    </div>
                    <span class="user-contact-value" style="display:none;">${contact}</span>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="roster-status-badge" data-email="${email}" style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; background: ${statusBg}; color: ${statusColor};">
                    ${status.charAt(0).toUpperCase() + status.slice(1)}
                </span>
                <button class="btn-view" onclick="openUserModal(
                  '${escapeAttr(userId)}',
                  '${escapeAttr(fullName)}',
                  '${escapeAttr(email)}',
                  '${escapeAttr(role)}',
                  '${escapeAttr(status)}',
                  '',
                  '${escapeAttr(contact)}',
                  '${escapeAttr(idNum)}',
                  '${escapeAttr(created)}',
                  '${escapeAttr(remarks)}'
                )">View Profile</button>  
            </div>
        </div>
      `;
        }).join('');

        container.innerHTML = html;
      }
    }

    function toggleSelectAllTrainees(masterCheckbox) {
      const checkboxes = document.querySelectorAll('.roster-checkbox');
      checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
      updateSelectedCount();
    }

    function updateSelectedCount() {
      const checkboxes = document.querySelectorAll('.roster-checkbox');
      const checkedCount = document.querySelectorAll('.roster-checkbox:checked')
        .length;
      const label = document.getElementById('selectedCountLabel');
      if (label) {
        label.textContent = checkedCount + " selected";
      }
      const master = document.getElementById('selectAllTrainees');
      if (master) {
        master.checked = checkboxes.length > 0 && checkedCount === checkboxes
          .length;
        master.indeterminate = checkedCount > 0 && checkedCount < checkboxes
          .length;
      }
    }

    function backToCourseCards() {
      document.getElementById('full-course-roster-view').style.display = 'none';
      document.getElementById('course-cards-main-view').style.display = 'block';
    }

    function addslashes(str) {
      return String(str).replace(/['"]/g, '\\$&');
    }
    /* ========================================================== */
    /* USER PROFILE: UPDATE & DELETE HANDLERS                     */
    /* ========================================================== */

    // 1. UPDATE USER FORM SUBMISSION
    document.getElementById('userForm').onsubmit = function(e) {
      e.preventDefault();

      const submitBtn = this.querySelector('button[type="submit"]');
      const originalSubmitHtml = submitBtn ? submitBtn.innerHTML :
        'Update User';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
      }

      const id = document.getElementById('editUserId')?.value || '';
      const name = document.getElementById('editUserName')?.value.trim() || '';
      const email = document.getElementById('editUserEmail')?.value.trim() ||
        '';
      const status = document.getElementById('editUserStatus')?.value ||
        'Pending';
      const remarks = document.getElementById('editUserRemarks')?.value
        .trim() || '';

      const rawContact = document.getElementById('editUserContact')?.value
        .trim() || '';
      const contact = rawContact.replace(/[^0-9]/g, '').slice(0, 11) || '';

      const rawIdNum = document.getElementById('editUserIdNum')?.value.trim() ||
        '';
      const id_number = (rawIdNum && rawIdNum !== 'N/A') ? rawIdNum : '';

      const role = document.getElementById('hiddenUserRole')?.value ||
        document.getElementById('editUserRole')?.value ||
        'student';

      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') ||
        (typeof csrfToken !== 'undefined' ? csrfToken : '');

      fetch('/admin/user/update', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id,
            name,
            email,
            status,
            remarks,
            contact,
            id_number,
            role
          })
        })
        .then(async r => {
          const text = await r.text();
          let data;
          try {
            data = JSON.parse(text);
          } catch (err) {
            console.error("Server response was not JSON:", text);
            throw new Error("Server returned an invalid response.");
          }

          if (!r.ok) {
            throw new Error(data.message || `Server error (${r.status})`);
          }

          return data;
        })
        .then(data => {
          if (data && data.success) {
            const updatedUser = data.user || {};
            const freshName = updatedUser.fullname || name;
            const freshEmail = updatedUser.email || email;
            const freshStatus = updatedUser.status || status;
            const freshContact = updatedUser.contact || contact;
            const freshIdNum = updatedUser.id_number || id_number;
            const freshRemarks = updatedUser.remarks || remarks;
            const freshRole = updatedUser.role || role;
            const freshCourse = updatedUser.course_title || '';

            const row = document.querySelector(`[data-user-id="${id}"]`) ||
              document.querySelector(`.user-item[data-user-id="${id}"]`) ||
              document.querySelector(`.user-card[data-user-id="${id}"]`) ||
              document.querySelector(
                `.user-item:has(.roster-status-badge[data-email="${email}"])`
              ) ||
              Array.from(document.querySelectorAll('button')).find(b => b
                .getAttribute('onclick')?.includes(`'${id}'`))?.closest(
                'div');

            const btn = (row ? row.querySelector('.btn-view') : null) ||
              Array.from(document.querySelectorAll('button')).find(b => b
                .getAttribute('onclick')?.includes(`'${id}'`));

            if (row) {
              const nameEl = row.querySelector('.user-name-text') || row
                .querySelector('strong');
              const emailVal = row.querySelector('.user-email-value');
              const contactVal = row.querySelector('.user-contact-value');
              const idNumVal = row.querySelector('.user-id-value');
              const badgeEl = row.querySelector('.roster-status-badge') || row
                .querySelector('.badge');

              if (nameEl) nameEl.textContent = freshName.toUpperCase();
              if (emailVal) emailVal.textContent = freshEmail;
              if (contactVal) contactVal.textContent = freshContact || '';
              if (idNumVal) idNumVal.textContent = freshIdNum || '';

              // Live Badge Color Update Logic
              if (badgeEl) {
                const cleanStatus = freshStatus.toLowerCase();
                badgeEl.textContent = cleanStatus.charAt(0).toUpperCase() +
                  cleanStatus.slice(1);
                badgeEl.setAttribute('data-email', freshEmail);

                if (cleanStatus === 'pending') {
                  badgeEl.style.background = '#f0f0f0'; // Gray Background
                  badgeEl.style.color = '#555555'; // Dark Gray Text
                } else if (cleanStatus === 'inactive') {
                  badgeEl.style.background = '#FCEBEB'; // Soft Red
                  badgeEl.style.color = '#A32D2D';
                } else {
                  badgeEl.style.background = '#e8f5e9'; // Active Green
                  badgeEl.style.color = '#025628';
                }
              }
            }

            if (btn) {
              const created = document.getElementById('editUserCreated')
                ?.value || 'August 2026';

              btn.dataset.id = id;
              btn.dataset.name = freshName;
              btn.dataset.email = freshEmail;
              btn.dataset.role = freshRole;
              btn.dataset.status = freshStatus;
              btn.dataset.courseTitle = freshCourse;
              btn.dataset.contact = freshContact;
              btn.dataset.idNum = freshIdNum;
              btn.dataset.created = created;
              btn.dataset.remarks = freshRemarks;

              const escapeAttr = (str) => String(str || '').replace(/\\/g,
                '\\\\').replace(/'/g, "\\'").replace(/\n/g, ' ');

              btn.setAttribute('onclick', `openUserModal(
          '${escapeAttr(id)}',
          '${escapeAttr(freshName)}',
          '${escapeAttr(freshEmail)}',
          '${escapeAttr(freshRole)}',
          '${escapeAttr(freshStatus)}',
          '${escapeAttr(freshCourse)}',
          '${escapeAttr(freshContact)}',
          '${escapeAttr(freshIdNum)}',
          '${escapeAttr(created)}',
          '${escapeAttr(freshRemarks)}'
        )`);
            }

        showToast(data.message || 'User profile updated successfully!', 'success');
                if (typeof closeUserModal === 'function') closeUserModal();
              } else {
                showToast(data?.message || 'An error occurred while updating.', 'error');
              }
            })
            .catch(err => {
              console.error("Update error:", err);
              showToast(err.message ||
                'An error occurred while updating. Please try again.', 'error');
            })
            .finally(() => {
              if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.innerHTML = originalSubmitHtml;
              }
            });
        };

    // 2. DELETE USER FUNCTION
    function deleteUser() {
      const idInput = document.getElementById('editUserId');
      const id = idInput ? idInput.value : null;

      if (!id) {
        alert('Error: Missing User ID.');
        return;
      }

      if (!confirm(
          'Are you sure you want to remove this user? This action cannot be undone.'
        )) {
        return;
      }

      const deleteBtn = document.querySelector('.btn-delete-text');
      const originalDeleteHtml = deleteBtn ? deleteBtn.innerHTML :
        '<i class="fa-solid fa-user-slash"></i> Remove User';

      if (deleteBtn) {
        deleteBtn.disabled = true;
        deleteBtn.style.opacity = '0.7';
        deleteBtn.style.cursor = 'not-allowed';
        deleteBtn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';
      }

      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') ||
        (typeof csrfToken !== 'undefined' ? csrfToken : '');

      fetch('/admin/user/delete', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id: id
          })
        })
        .then(async r => {
          const text = await r.text();
          try {
            return JSON.parse(text);
          } catch (err) {
            console.error("Server response was not JSON:", text);
            throw new Error("Server returned an invalid response.");
          }
        })
        .then(data => {
          if (data.success) {
            const row = document.querySelector(
                `.user-item[data-user-id="${id}"]`) ||
              document.querySelector(`[data-user-id="${id}"]`);
            if (row) {
              row.remove();
            }

            alert(data.message || 'User removed successfully!');
            if (typeof closeUserModal === 'function') closeUserModal();
          } else {
            alert(data.message || 'Failed to remove user.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Network error occurred while attempting to delete user.');
        })
        .finally(() => {
          if (deleteBtn) {
            deleteBtn.disabled = false;
            deleteBtn.style.opacity = '1';
            deleteBtn.style.cursor = 'pointer';
            deleteBtn.innerHTML = originalDeleteHtml;
          }
        });
    }

    // 2. DELETE USER FUNCTION
    function deleteUser() {
      const idInput = document.getElementById('editUserId');
      const id = idInput ? idInput.value : null;

      if (!id) {
        alert('Error: Missing User ID.');
        return;
      }

      if (!confirm(
          'Are you sure you want to remove this user? This action cannot be undone.'
        )) {
        return;
      }

      const deleteBtn = document.querySelector('.btn-delete-text');
      const originalDeleteHtml = deleteBtn ? deleteBtn.innerHTML :
        '<i class="fa-solid fa-user-slash"></i> Remove User';

      if (deleteBtn) {
        deleteBtn.disabled = true;
        deleteBtn.style.opacity = '0.7';
        deleteBtn.style.cursor = 'not-allowed';
        deleteBtn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';
      }

      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') ||
        (typeof csrfToken !== 'undefined' ? csrfToken : '');

      fetch('/admin/user/delete', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            id: id
          })
        })
        .then(async r => {
          const text = await r.text();
          try {
            return JSON.parse(text);
          } catch (err) {
            console.error("Server response was not JSON:", text);
            throw new Error("Server returned an invalid response.");
          }
        })
        .then(data => {
          if (data.success) {
            const row = document.querySelector(
              `.user-item[data-user-id="${id}"]`);
            if (row) {
              row.remove();
            }

            alert(data.message || 'User removed successfully!');
            closeUserModal();
          } else {
            alert(data.message || 'Failed to remove user.');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Network error occurred while attempting to delete user.');
        })
        .finally(() => {
          if (deleteBtn) {
            deleteBtn.disabled = false;
            deleteBtn.style.opacity = '1';
            deleteBtn.style.cursor = 'pointer';
            deleteBtn.innerHTML = originalDeleteHtml;
          }
        });
    }

    function togglePassword() {
      const passwordInput = document.getElementById('newTrainerPass');
      const icon = document.getElementById('togglePasswordIcon');

      if (passwordInput && icon) {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        } else {
          passwordInput.type = 'password';
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      }
    }

    function closeUserModal() {
      const modal = document.getElementById('userModal');
      if (modal) {
        modal.style.display = 'none';
        modal.style.setProperty('display', 'none', 'important');
      }
    }

    function filterCourses() {
      const searchVal = document.getElementById('searchCourseInput').value
        .toLowerCase().trim();
      const cards = document.querySelectorAll('#coursesGrid .course-card');
      let visibleCount = 0;

      cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const code = card.getAttribute('data-code') || '';

        const matchesSearch = title.includes(searchVal) || code.includes(
          searchVal);

        if (matchesSearch) {
          card.style.display = '';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      const noResults = document.getElementById('noFilterResults');
      if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    }

    function showModuleAlert(message, isError = false) {
      const alertEl = document.getElementById('moduleAlert');
      if (!alertEl) return;
      alertEl.style.display = 'block';
      alertEl.style.background = isError ? '#FCEBEB' : '#E8F5E9';
      alertEl.style.color = isError ? '#A32D2D' : '#025628';
      alertEl.style.border = isError ? '1px solid #F7C6C6' : '1px solid #C8E6C9';
      alertEl.textContent = message;
      setTimeout(() => {
        alertEl.style.display = 'none';
      }, 4000);
    }

    function updateCourseCardCounts() {
      if (typeof _contentCourseId === 'undefined' || !_contentCourseId) return;

      const validModules = Array.isArray(_contentModules) ? _contentModules
        .filter(Boolean) : [];
      const validQuizzes = Array.isArray(_contentQuizzes) ? _contentQuizzes
        .filter(Boolean) : [];

      const modCountEl = document.getElementById(
        `course-module-count-${_contentCourseId}`);
      const modLabelEl = document.getElementById(
        `course-module-label-${_contentCourseId}`);
      if (modCountEl) {
        modCountEl.textContent = validModules.length;
        if (modLabelEl) {
          modLabelEl.textContent = validModules.length === 1 ? 'Module' :
            'Modules';
        }
      }

      const quizCountEl = document.getElementById(
        `course-quiz-count-${_contentCourseId}`);
      const quizLabelEl = document.getElementById(
        `course-quiz-label-${_contentCourseId}`);
      if (quizCountEl) {
        quizCountEl.textContent = validQuizzes.length;
        if (quizLabelEl) {
          quizLabelEl.textContent = validQuizzes.length === 1 ? 'Quiz' :
            'Quizzes';
        }
      }
    }

    function filterFacilities() {
      const query = document.getElementById('searchFacilityInput').value
        .toLowerCase().trim();
      const cards = document.querySelectorAll('#facilityGrid .facility-card');

      cards.forEach(card => {
        const name = card.getAttribute('data-name') || '';
        const location = card.getAttribute('data-location') || '';
        const match = name.includes(query) || location.includes(query);
        card.style.display = match ? 'flex' : 'none';
      });
    }

    function updateCourseBadgeCount() {
      const selectedCount = document.querySelectorAll(
        '.facility-course-cb:checked').length;
      const badge = document.getElementById('selectedCourseBadge');
      if (badge) {
        badge.innerText = `${selectedCount} Selected`;
      }
    }

    function toggleSelectAllCourses() {
      const checkboxes = document.querySelectorAll('.facility-course-cb');
      const allChecked = Array.from(checkboxes).every(cb => cb.checked);

      checkboxes.forEach(cb => {
        cb.checked = !allChecked;
      });

      updateCourseBadgeCount();
    }

    function filterAnnouncements() {
      const searchVal = document.getElementById('annSearchInput')?.value
        .toLowerCase().trim() || '';
      const typeVal = document.getElementById('annTypeFilter')?.value
        .toLowerCase() || '';
      const statusVal = document.getElementById('annStatusFilter')?.value
        .toLowerCase() || '';

      const items = document.querySelectorAll('#view-announcements .user-item');

      items.forEach(item => {
        const titleText = item.querySelector('.ann-title-text')?.textContent
          .toLowerCase() || '';
        const msgText = item.querySelector('.ann-msg-text')?.textContent
          .toLowerCase() || '';

        const itemType = item.getAttribute('data-type') || '';
        const itemStatus = item.getAttribute('data-status') || '';

        const matchesSearch = searchVal === '' || titleText.includes(
          searchVal) || msgText.includes(searchVal);
        const matchesType = typeVal === '' || itemType === typeVal;
        const matchesStatus = statusVal === '' || itemStatus === statusVal;

        if (matchesSearch && matchesType && matchesStatus) {
          item.style.setProperty('display', 'flex', 'important');
        } else {
          item.style.setProperty('display', 'none', 'important');
        }
      });
    }

    function filterTraineeCards() {
      const searchVal = document.getElementById('traineeCourseSearch')?.value
        .toLowerCase().trim() || '';
      const cards = document.querySelectorAll('.trainee-course-card');
      let visibleCount = 0;

      cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        if (searchVal === '' || title.includes(searchVal)) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      const noResults = document.getElementById('noTraineeCardResults');
      if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    }

    // Live search filter for Trainer Directory
    function filterTrainerList() {
      const input = document.getElementById('trainerSearchInput').value
        .toLowerCase();
      const items = document.querySelectorAll('#trainer-list-content .user-item');

      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? 'flex' : 'none';
      });
    }

    function filterTrainerList() {
      const input = document.getElementById('trainerSearchInput').value
        .toLowerCase();
      const items = document.querySelectorAll('#trainer-list-content .user-item');

      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? 'flex' : 'none';
      });
    }

    let expandedChartInstance = null;

    // Custom Chart.js plugin to render value labels next to horizontal bars (used for bar charts)
    const barValueLabelsPlugin = {
      id: 'barValueLabels',
      afterDatasetsDraw(chart) {
        if (chart.config.options.indexAxis !== 'y') return;
        const {
          ctx
        } = chart;
        ctx.save();
        ctx.font = '600 11px sans-serif';
        ctx.textAlign = 'left';
        ctx.textBaseline = 'middle';

        chart.data.datasets.forEach((dataset, datasetIdx) => {
          const meta = chart.getDatasetMeta(datasetIdx);
          meta.data.forEach((bar, index) => {
            const val = dataset.data[index];
            if (val > 0) {
              ctx.fillStyle = dataset.backgroundColor || '#004d26';
              ctx.fillText(val, bar.x + 6, bar.y);
            }
          });
        });
        ctx.restore();
      }
    };

    function openExpandedChartModal(type) {
      const modal = document.getElementById('expandedChartModal');
      const canvas = document.getElementById('expandedCanvas');
      const title = document.getElementById('expandedModalTitle');
      const centerTextObj = document.getElementById('doughnutCenterText');
      if (!modal || !canvas) return;

      modal.style.display = 'flex';
      const ctx = canvas.getContext('2d');

      if (expandedChartInstance) {
        expandedChartInstance.destroy();
      }

      const courseLabels = (window.traineeCourseLabels && window
          .traineeCourseLabels.length) ?
        window.traineeCourseLabels : ['Baking', 'Street Food', 'Basic Sewing',
          'Candle Making', 'Carpentry'
        ];

      // Cache element references for KPI Badges
      const kpi1Label = document.getElementById('kpiLabel1');
      const kpi1Val = document.getElementById('kpiVal1') || document
        .getElementById('kpiTotalTrainees');
      const kpi2Label = document.getElementById('kpiLabel2');
      const kpi2Val = document.getElementById('kpiVal2') || document
        .getElementById('kpiTopCourse');
      const kpi3Label = document.getElementById('kpiLabel3');
      const kpi3Val = document.getElementById('kpiVal3') || document
        .getElementById('kpiActiveCourses');

      if (type === 'trainees') {
        if (title) title.textContent = 'Enrolled Trainees Breakdown per Course';
        if (centerTextObj) centerTextObj.style.display = 'none';

        const dataCounts = (window.traineeCourseCounts && window
            .traineeCourseCounts.length) ?
          window.traineeCourseCounts : [10, 2, 3, 1, 4];

        // Compute Trainee KPIs
        const totalTrainees = dataCounts.reduce((a, b) => a + b, 0);
        const maxVal = dataCounts.length ? Math.max(...dataCounts) : 0;
        const topIdx = dataCounts.indexOf(maxVal);
        const topCourse = (topIdx !== -1 && courseLabels[topIdx]) ? courseLabels[
          topIdx] : 'N/A';
        const activeCount = dataCounts.filter(c => c > 0).length;

        if (kpi1Label) kpi1Label.textContent = 'TOTAL TRAINEES';
        if (kpi1Val) {
          kpi1Val.textContent = totalTrainees;
          kpi1Val.style.color = '#004d26';
          kpi1Val.style.fontSize = '20px';
          kpi1Val.style.fontWeight = '800';
          kpi1Val.style.display = 'block';
        }
        if (kpi2Label) kpi2Label.textContent = 'TOP ENROLLED COURSE';
        if (kpi2Val) {
          kpi2Val.textContent = topCourse;
          kpi2Val.style.color = '#004d26';
          kpi2Val.style.fontSize =
            '14px'; // Kept slightly smaller to prevent text wrapping on long course names
          kpi2Val.style.fontWeight = '700';
          kpi2Val.style.display = 'block';
        }
        if (kpi3Label) kpi3Label.textContent = 'ACTIVE MODULES';
        if (kpi3Val) {
          kpi3Val.textContent = `${activeCount} / ${courseLabels.length}`;
          kpi3Val.style.color = '#004d26';
          kpi3Val.style.fontSize = '20px';
          kpi3Val.style.fontWeight = '800';
          kpi3Val.style.display = 'block';
        }

        expandedChartInstance = new Chart(ctx, {
          type: 'bar',
          plugins: [barValueLabelsPlugin],
          data: {
            labels: courseLabels,
            datasets: [{
              label: 'Enrolled Trainees',
              data: dataCounts,
              backgroundColor: '#004d26',
              borderRadius: 4,
              barPercentage: 0.6
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                padding: 10,
                cornerRadius: 6,
                callbacks: {
                  title: (items) => items[0]?.label || '',
                  label: (ctx) => ` Total Enrolled: ${ctx.raw} Trainees`
                }
              }
            },
            scales: {
              x: {
                beginAtZero: true,
                grid: {
                  color: '#f2f2f2'
                },
                ticks: {
                  precision: 0
                }
              },
              y: {
                grid: {
                  display: false
                },
                ticks: {
                  font: {
                    size: 12,
                    weight: '600'
                  },
                  color: '#111'
                }
              }
            },
            layout: {
              padding: {
                right: 35
              }
            }
          }
        });

      } else if (type === 'courses') {
        if (title) title.textContent =
          'Overall Trainee Status: Completion vs Active';

        const activeData = window.courseActiveCounts || [10, 2, 3, 1, 4];
        const completedData = window.courseCompletedCounts || [4, 1, 2, 0, 3];

        const totalActive = activeData.reduce((a, b) => a + b, 0);
        const totalCompleted = completedData.reduce((a, b) => a + b, 0);
        const grandTotal = totalActive + totalCompleted;
        const completionRate = grandTotal > 0 ? Math.round((totalCompleted /
          grandTotal) * 100) : 0;

        // Apply specific theme colors, font sizes, and styles to KPI cards
        if (kpi1Label) kpi1Label.textContent = 'ACTIVE ENROLLED';
        if (kpi1Val) {
          kpi1Val.textContent = totalActive;
          kpi1Val.style.color = '#004d26'; // Green
          kpi1Val.style.fontSize = '20px';
          kpi1Val.style.fontWeight = '800';
          kpi1Val.style.display = 'block';
        }

        if (kpi2Label) kpi2Label.textContent = 'TOTAL GRADUATED';
        if (kpi2Val) {
          kpi2Val.textContent = totalCompleted;
          kpi2Val.style.color = '#ca8a04'; // Warm Yellow/Gold Accent
          kpi2Val.style.fontSize = '20px'; // Matching 20px size
          kpi2Val.style.fontWeight = '800';
          kpi2Val.style.display = 'block';
        }

        if (kpi3Label) kpi3Label.textContent = 'COMPLETION RATE';
        if (kpi3Val) {
          kpi3Val.textContent = `${completionRate}%`;
          kpi3Val.style.color = '#004d26'; // Green
          kpi3Val.style.fontSize = '20px';
          kpi3Val.style.fontWeight = '800';
          kpi3Val.style.display = 'block';
        }

        // Render scaled center text overlay for enlarged doughnut chart
        if (centerTextObj) {
          centerTextObj.style.display = 'block';
          centerTextObj.innerHTML = `
        <span style="font-size: 32px; font-weight: 800; color: #004d26; display: block; line-height: 1;">${completionRate}%</span>
        <span style="font-size: 11px; font-weight: 700; color: #666666; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Completed</span>
      `;
        }

        expandedChartInstance = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Active / Enrolled', 'Completed / Graduated'],
            datasets: [{
              data: [totalActive, totalCompleted],
              backgroundColor: ['#004d26',
                '#eab308'
              ], // Brand Green & Bright Yellow
              borderWidth: 3,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%', // Thicker ring for larger overall chart display
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  font: {
                    size: 12,
                    weight: '600'
                  },
                  color: '#333333',
                  padding: 8,
                  boxWidth: 12,
                  boxHeight: 12
                }
              },
              tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                padding: 12,
                cornerRadius: 6,
                callbacks: {
                  label: (ctx) => {
                    const val = ctx.raw;
                    const pct = grandTotal > 0 ? Math.round((val /
                      grandTotal) * 100) : 0;
                    return ` ${ctx.label}: ${val} Trainees (${pct}%)`;
                  }
                }
              }
            },
            layout: {
              padding: {
                top: 0,
                bottom: 0,
                left: 0,
                right: 0
              }
            }
          }
        });
      }
    }

    function closeExpandedChartModal() {
      const modal = document.getElementById('expandedChartModal');
      const centerTextObj = document.getElementById('doughnutCenterText');

      if (modal) modal.style.display = 'none';
      if (centerTextObj) centerTextObj.style.display = 'none';

      if (expandedChartInstance) {
        expandedChartInstance.destroy();
        expandedChartInstance = null;
      }
    }

    // Close when clicking outside the modal box
    document.getElementById('expandedChartModal')?.addEventListener('click',
      function(e) {
        if (e.target === this) {
          closeExpandedChartModal();
        }
      });

    // ==========================================
    // 1. MODAL CONTROLLERS (OPEN / CLOSE)
    // ==========================================
    function openCertModal(name, course, certId, issueDate = 'April 3, 2026',
      status = 'Pending', grade = '94%', docType = 'completion') {
      const modal = document.getElementById('certificateModal');
      if (!modal) return;

      document.getElementById('vName').textContent = name;
      document.getElementById('vCourse').textContent = course.toUpperCase();
      document.getElementById('vID').textContent = `CERT. NO.: ${certId}`;

      const docTitle = document.getElementById('vDocTitle');
      if (docTitle) {
        docTitle.textContent = docType === 'participation' ?
          'CERTIFICATE OF PARTICIPATION' :
          'CERTIFICATE OF COMPLETION';
      }

      const statusBadge = document.getElementById('vStatusBadge');
      if (statusBadge) {
        const isClaimed = status.toLowerCase() === 'claimed';
        statusBadge.className =
          `badge ${isClaimed ? 'badge-success' : 'badge-warning'}`;
        statusBadge.textContent = status;
      }

      const issueDateEl = document.getElementById('vIssueDate');
      if (issueDateEl) issueDateEl.textContent = issueDate;

      const controlNoEl = document.getElementById('vControlNo');
      if (controlNoEl) controlNoEl.textContent = certId;

      const gradeEl = document.getElementById('vGrade');
      if (gradeEl) gradeEl.textContent = `${grade} — Passed`;

      modal.style.display = 'flex';
    }

    function closeCertModal() {
      document.getElementById('certificateModal').style.display = 'none';
    }

    function openAddModal() {
      const modal = document.getElementById('addTraineeModal');
      const courseFilter = document.getElementById('modalCourseFilter');

      if (courseFilter) {
        courseFilter.value = '';
        filterModalTrainees('');
      }

      if (modal) {
        modal.style.display = 'flex';
        updateLivePreview();
      }
    }

    function closeAddModal() {
      document.getElementById('addTraineeModal').style.display = 'none';
    }

    window.addEventListener('click', (e) => {
      if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
      }
    });


    // ==========================================
    // 2. LIVE PREVIEW SYNCHRONIZATION
    // ==========================================
    function updateLivePreview() {
      const traineeSelect = document.getElementById('traineeSelect');
      const certInput = document.getElementById('certIDInput');

      const pName = document.getElementById('pName');
      const pCourse = document.getElementById('pCourse');
      const pID = document.getElementById('pID');

      // Helper for Standard Title Case (Capitalizes the first letter of each word)
      function toTitleCase(str) {
        if (!str) return '';
        return str
          .toLowerCase()
          .split(' ')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1))
          .join(' ');
      }

      if (traineeSelect && traineeSelect.selectedIndex > 0) {
        const selectedOption = traineeSelect.options[traineeSelect.selectedIndex];
        let rawText = selectedOption.getAttribute('data-name') || selectedOption
          .text.split(' (')[0].trim();
        const course = selectedOption.getAttribute('data-course') ||
          'TRAINING PROGRAM';

        // Applies proper Title Case instead of lowercase
        pName.textContent = toTitleCase(rawText);
        pCourse.textContent = course.toUpperCase();
      } else {
        pName.textContent = '[Recipient Name]';
        pCourse.textContent = '[COURSE NAME]';
      }

      if (certInput && certInput.value.trim() !== '') {
        pID.textContent = `CERT. NO.: ${certInput.value.trim()}`;
      } else {
        pID.textContent = 'CERT. NO.: [ID]';
      }
    }

    // ==========================================
    // 3. TABLE ROW ACTIONS, INSERTION & FILTERS
    // ==========================================
    async function submitIssueCertificate() {
      const traineeSelect = document.getElementById('traineeSelect');
      const certInput = document.getElementById('certIDInput');
      const dateInput = document.getElementById('issueDateInput');
      const docTypeSelect = document.getElementById('docTypeSelect');
      const remarksInput = document.getElementById('certRemarks');

      if (!traineeSelect || traineeSelect.selectedIndex <= 0 || !traineeSelect
        .value) {
        alert('Please select a trainee from the dropdown.');
        traineeSelect?.focus();
        return;
      }

      const certNo = certInput?.value.trim();
      if (!certNo) {
        alert('Please enter a Control / Certificate Number.');
        certInput?.focus();
        return;
      }

      const selectedOption = traineeSelect.options[traineeSelect.selectedIndex];
      const courseId = selectedOption.getAttribute('data-course-id') || null;

      const payload = {
        trainee_id: traineeSelect.value,
        course_id: courseId,
        certificate_no: certNo,
        issue_date: dateInput?.value || new Date().toISOString().slice(0, 10),
        document_type: docTypeSelect ? docTypeSelect.value : 'completion',
        remarks: remarksInput ? remarksInput.value.trim() : null
      };

      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || '';

      try {
        const response = await fetch('/admin/certificate/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (response.ok && data.success) {
          alert(data.message || 'Certificate saved successfully to database!');
          closeAddModal();
          localStorage.setItem('activeAdminTab', 'view-certificate');
          window.location.href = window.location.pathname + '?view=certificate';
        } else {
          let errorMsg = data.message || 'Failed to save certificate.';
          if (data.errors) {
            errorMsg = Object.values(data.errors).flat().join('\n');
          }
          alert('Error Saving:\n' + errorMsg);
        }
      } catch (error) {
        console.error('AJAX Error:', error);
        alert('Network error. Check Console (F12) for server details.');
      }
    }

    async function toggleCertStatus(badgeEl, certId) {
      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || '';
      try {
        const response = await fetch(
          `/admin/certificate/${certId}/toggle-status`, {
            method: 'PATCH',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            }
          });

        const data = await response.json();
        if (response.ok && data.success) {
          const isClaimed = data.new_status.toLowerCase() === 'claimed';
          badgeEl.className =
            `badge badge-toggle ${isClaimed ? 'badge-success' : 'badge-warning'}`;
          badgeEl.innerHTML =
            `<i class="fas ${isClaimed ? 'fa-check-circle' : 'fa-clock'}"></i> <span class="status-label">${data.new_status}</span>`;

          localStorage.setItem('activeAdminTab', 'view-certificate');
          window.location.href = window.location.pathname + '?view=certificate';
        }
      } catch (err) {
        console.error('Toggle status error:', err);
      }
    }

    async function deleteCert(buttonElement, certId) {
      if (!confirm('Are you sure you want to delete this certificate record?'))
        return;
      const token = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content') || '';

      try {
        const response = await fetch(`/admin/certificate/${certId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          }
        });

        const data = await response.json();
        if (response.ok && data.success) {
          buttonElement.closest('tr').remove();
          localStorage.setItem('activeAdminTab', 'view-certificate');
          window.location.href = window.location.pathname + '?view=certificate';
        } else {
          alert(data.message || 'Could not delete record.');
        }
      } catch (err) {
        console.error('Delete error:', err);
      }
    }

    function openEditCertModal(certId) {
      alert(`Editing record for Certificate No: ${certId}`);
    }

    function filterCertTable() {
      const search = document.getElementById('certSearchInput')?.value
        .toLowerCase().trim() || '';
      const course = document.getElementById('filterCourse')?.value.toLowerCase()
        .trim() || '';
      const month = document.getElementById('filterMonth')?.value.toLowerCase()
        .trim() || '';
      const status = document.getElementById('filterStatus')?.value.toLowerCase()
        .trim() || '';

      const rows = document.querySelectorAll('#certTableBody tr');
      let matchCount = 0;

      rows.forEach(row => {
        if (row.id === 'emptyCertRow') return;

        const nameText = row.children[1]?.textContent.toLowerCase().trim() ||
          '';
        const courseText = row.children[2]?.textContent.toLowerCase()
          .trim() || '';
        const dateText = row.children[3]?.textContent.toLowerCase().trim() ||
          '';
        const statusText = row.children[4]?.textContent.toLowerCase()
          .trim() || '';
        const certIdText = row.getAttribute('data-cert-no')?.toLowerCase()
          .trim() || '';

        const matchSearch = !search || nameText.includes(search) || certIdText
          .includes(search);
        const matchCourse = !course || courseText.includes(course);
        const matchMonth = !month || dateText.includes(month);
        const matchStatus = !status || statusText.includes(status);

        if (matchSearch && matchCourse && matchMonth && matchStatus) {
          row.style.display = '';
          matchCount++;
        } else {
          row.style.display = 'none';
        }
      });

      const noResults = document.getElementById('noCertResults');
      if (noResults) {
        noResults.style.display = matchCount === 0 ? 'block' : 'none';
      }
    }


    // ==========================================
    // 4. BATCH CHECKBOX CONTROLS
    // ==========================================
    function toggleMultiSelectMode(checkbox) {
      const selectCols = document.querySelectorAll('#certTable .select-col');
      selectCols.forEach(col => col.classList.toggle('hidden', !checkbox
        .checked));

      if (!checkbox.checked) {
        const selectAll = document.getElementById('selectAll');
        if (selectAll) selectAll.checked = false;
        document.querySelectorAll('#certTable .row-checkbox').forEach(cb => (cb
          .checked = false));
      }
    }

    function toggleSelectAllRows(masterCheckbox) {
      document.querySelectorAll('#certTable .row-checkbox').forEach(cb => {
        cb.checked = masterCheckbox.checked;
      });
    }

    function exportCertificates() {
      const selectedRows = document.querySelectorAll(
        '#certTable .row-checkbox:checked');
      if (selectedRows.length === 0) {
        alert('Please select at least one certificate to export.');
        return;
      }
      alert(`Exporting ${selectedRows.length} certificate(s)...`);
    }


    // ==========================================
    // 5. PRINT & DIRECT 1-PAGE SQUARE PDF EXPORT
    // ==========================================
    function handlePrint() {
      window.print();
    }

    async function handleDownload(targetElementId = 'printableCert') {
      const original = document.getElementById(targetElementId);
      if (!original) return;

      let recipientName = 'Certificate';
      const nameEl = targetElementId === 'printableCert' ?
        document.getElementById('vName') :
        document.getElementById('pName');

      if (nameEl && nameEl.textContent.trim() && !nameEl.textContent.includes(
          '[')) {
        recipientName = nameEl.textContent.trim();
      }

      const cleanFileName =
        `Certificate_${recipientName.replace(/[^a-zA-Z0-9_-]/g, '_')}.pdf`;

      const tempWrapper = document.createElement('div');
      Object.assign(tempWrapper.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '750px',
        height: '750px',
        zIndex: '999999',
        background: '#ffffff',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        overflow: 'hidden'
      });

      const clone = original.cloneNode(true);
      Object.assign(clone.style, {
        width: '750px',
        height: '750px',
        minWidth: '750px',
        minHeight: '750px',
        maxWidth: '750px',
        maxHeight: '750px',
        margin: '0',
        padding: '20px',
        border: '10px solid #025628',
        boxSizing: 'border-box',
        transform: 'none',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'space-between',
        background: '#ffffff'
      });

      const inner = clone.querySelector('.ui-cert-inner');
      if (inner) {
        Object.assign(inner.style, {
          height: '100%',
          boxSizing: 'border-box',
          border: '1.5px dashed #025628',
          padding: '22px 18px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          textAlign: 'center',
          background: 'radial-gradient(circle at center, #ffffff 60%, #fafcf9 100%)'
        });
      }

      // Balanced Logo Dimensions for PDF Export
      const logoHeader = clone.querySelector('.cert-logos-header');
      if (logoHeader) {
        Object.assign(logoHeader.style, {
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          gap: '24px',
          marginBottom: '8px',
          width: '100%'
        });

        logoHeader.querySelectorAll('.cert-logo-img').forEach(img => {
          Object.assign(img.style, {
            height: '60px',
            width: 'auto',
            maxHeight: '60px',
            maxWidth: '100px',
            objectFit: 'contain',
            display: 'inline-block'
          });
        });
      }

      // Harmonized Recipient Name Sizing for PDF Export
      const recipName = clone.querySelector('.cert-recipient-name');
      if (recipName) {
        recipName.style.fontSize = '70px';
        recipName.style.fontWeight = '700';
        recipName.style.textDecoration = 'underline';
        recipName.style.margin = '6px 0';
        recipName.style.color = '#1a1a1a';
        recipName.style.textTransform = 'capitalize';
      }

      tempWrapper.appendChild(clone);
      document.body.appendChild(tempWrapper);

      if (document.fonts) {
        await document.fonts.ready;
      }

      try {
        const canvas = await html2canvas(clone, {
          scale: 3,
          useCORS: true,
          allowTaint: true,
          backgroundColor: '#ffffff',
          logging: false,
          scrollX: 0,
          scrollY: 0,
          windowWidth: 750,
          windowHeight: 750
        });

        document.body.removeChild(tempWrapper);

        const jsPDFConstructor = window.jspdf ? window.jspdf.jsPDF : (window
          .jsPDF || jsPDF);
        const pdf = new jsPDFConstructor({
          orientation: 'portrait',
          unit: 'mm',
          format: [200, 200]
        });

        const imgData = canvas.toDataURL('image/png', 1.0);
        pdf.addImage(imgData, 'PNG', 0, 0, 200, 200);
        pdf.save(cleanFileName);

      } catch (err) {
        console.error('PDF Generation Error:', err);
        if (document.body.contains(tempWrapper)) {
          document.body.removeChild(tempWrapper);
        }
        alert('An error occurred while generating the PDF.');
      }
    }

    function filterModalTrainees(selectedCourse) {
      const traineeSelect = document.getElementById('traineeSelect');
      if (!traineeSelect) return;

      const options = traineeSelect.querySelectorAll('option');
      const targetCourse = selectedCourse.toLowerCase().trim();

      options.forEach((opt, index) => {
        if (index === 0) {
          opt.style.display = '';
          return;
        }

        const optCourse = (opt.getAttribute('data-course') || '')
          .toLowerCase().trim();

        if (!targetCourse || optCourse.includes(targetCourse) || targetCourse
          .includes(optCourse)) {
          opt.style.display = '';
          opt.disabled = false;
        } else {
          opt.style.display = 'none';
          opt.disabled = true;
        }
      });

      traineeSelect.value = '';
      updateLivePreview();
    }
  </script>
</body>

</html>

