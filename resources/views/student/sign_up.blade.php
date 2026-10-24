<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - LEDIPO</title>

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="{{ asset('stylesheet/sign_up.css') }}">
  <link rel="stylesheet"
    href="{{ asset('bootstrap_folder/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('font-awesome-icon/css/all.min.css') }}">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">

  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(rgba(20, 60, 36, 0.85), rgba(15, 45, 28, 0.9)), url('{{ asset('images/3.jpg') }}') center/cover no-repeat fixed;
      font-family: 'Poppins', sans-serif;
      padding: 20px;
    }

    .parent-login {
      display: flex;
      width: 100%;
      max-width: 950px;
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      position: relative;
    }

    .login-image {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.25)), url('{{ asset('images/3.jpg') }}') center/cover no-repeat;
      min-height: 560px;
      position: relative;
    }

    .login-form {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      padding: 30px 40px !important;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: #ffffff;
      box-sizing: border-box;
    }

    .forms {
      width: 100%;
    }

    /* Back to Homepage Button Overlay */
    .back-home-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: #fef08a;
      color: #1a4d2e;
      padding: 6px 14px;
      border-radius: 20px;
      font-weight: 700;
      font-size: 12px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      transition: background-color 0.2s, transform 0.2s;
      z-index: 10;
    }

    .back-home-btn:hover {
      background-color: #fde047;
      color: #143c24;
      transform: translateY(-1px);
    }

    .card-logo {
      text-align: center;
      margin-bottom: 10px;
    }

    .card-logo img {
      width: 55px;
      height: 55px;
      object-fit: contain;
    }

    .card-logo h2 {
      font-size: 20px;
      font-weight: 700;
      color: #1a4d2e;
      margin-top: 6px;
      margin-bottom: 0px;
    }

    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: #1a4d2e;
      margin-bottom: 2px;
    }

    .form-control {
      border-radius: 8px;
      padding: 8px 12px;
      border: 1px solid #1a4d2e;
      font-size: 12px;
    }

    .form-control:focus {
      border-color: #1a4d2e;
      box-shadow: 0 0 0 3px rgba(26, 77, 46, 0.12);
    }

    .password-wrapper {
      position: relative;
    }

    .password-toggle-icon {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #1a4d2e;
      font-size: 13px;
      z-index: 5;
    }

    .btn-submit {
      background-color: #0d471d;
      color: #ffffff;
      font-weight: 700;
      padding: 10px;
      border-radius: 8px;
      border: none;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.2s;
      font-size: 14px;
    }

    .btn-submit:hover {
      background-color: #143c24;
    }

    .login-link {
      text-align: center;
      margin-top: 14px;
      font-size: 12px;
      color: #4a5568;
    }

    .login-link a {
      color: #1a4d2e;
      font-weight: 700;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .error-box {
      background: #fed7d7;
      color: #9b2c2c;
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 11px;
      margin-bottom: 10px;
    }

    .error-box div {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .error-box div+div {
      margin-top: 4px;
    }


    /* =========================================================
       RESPONSIVE LAYOUT OVERRIDES
       Existing form fields, routes, validation and scripts kept.
       ========================================================= */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html {
      width: 100%;
      min-height: 100%;
      overflow-x: hidden;
      -webkit-text-size-adjust: 100%;
    }

    body {
      width: 100%;
      min-width: 0;
      min-height: 100vh;
      min-height: 100dvh;
      padding: clamp(12px, 2.5vw, 28px);
      overflow-x: hidden;
      background-attachment: scroll;
    }

    body > .container-fluid {
      width: 100%;
      min-width: 0;
      min-height: calc(100vh - 40px);
      min-height: calc(100dvh - 40px);
      padding: 0 !important;
    }

    .parent-login {
      width: 100%;
      max-width: 950px;
      min-width: 0;
      margin: auto;
    }

    .login-image,
    .login-form {
      min-width: 0;
    }

    .login-image {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      min-height: 560px;
      background-position: center;
      background-size: cover;
    }

    .login-form {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      padding: clamp(22px, 3.2vw, 40px) !important;
    }

    .forms,
    .forms form,
    .forms .row,
    .forms .row > div {
      min-width: 0;
    }

    .forms .row {
      --bs-gutter-x: 0.75rem;
    }

    .form-control {
      width: 100%;
      min-width: 0;
      min-height: 42px;
      font-size: 14px;
    }

    .form-label {
      display: block;
      line-height: 1.4;
      overflow-wrap: anywhere;
    }

    .password-wrapper .form-control {
      padding-right: 38px !important;
    }

    .password-toggle-icon {
      width: 20px;
      min-height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      touch-action: manipulation;
    }

    .btn-submit {
      min-height: 44px;
    }

    .error-box {
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .error-box div {
      align-items: flex-start;
    }

    .error-box i {
      flex: 0 0 auto;
      margin-top: 2px;
    }

    .back-home-btn {
      max-width: calc(100% - 32px);
      white-space: normal;
    }

    @media (max-width: 991.98px) {
      body {
        padding: 18px;
      }

      .parent-login {
        max-width: 760px;
      }

      .login-image {
        min-height: 520px;
      }

      .login-form {
        padding: 28px 24px !important;
      }

      .card-logo img {
        width: 52px;
        height: 52px;
      }

      .card-logo h2 {
        font-size: clamp(18px, 2.4vw, 20px);
      }
    }

    @media (max-width: 767.98px) {
      body {
        align-items: flex-start;
        padding: 16px;
        background-attachment: scroll;
      }

      body > .container-fluid {
        min-height: calc(100vh - 32px);
        min-height: calc(100dvh - 32px);
        align-items: flex-start !important;
      }

      .parent-login {
        max-width: 560px;
        flex-direction: column;
        border-radius: 16px;
      }

      .login-image {
        flex: 0 0 auto !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: 150px;
        height: 150px;
        background-position: center 45%;
      }

      .login-form {
        flex: 1 1 auto !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 26px clamp(18px, 5vw, 32px) !important;
      }

      .back-home-btn {
        top: 12px;
        left: 12px;
        padding: 8px 12px;
        font-size: 12px;
      }

      .card-logo {
        margin-bottom: 14px;
      }

      .card-logo img {
        width: 50px;
        height: 50px;
      }

      .card-logo h2 {
        font-size: 21px;
      }

      .form-label {
        font-size: 13px;
        margin-bottom: 5px;
      }

      .form-control {
        min-height: 44px;
        padding: 10px 12px;
        font-size: 14px;
      }

      .btn-submit {
        padding: 12px;
        font-size: 14px;
      }

      .login-link {
        font-size: 13px;
        line-height: 1.6;
      }

      .error-box {
        font-size: 12px;
        line-height: 1.5;
      }
    }

    @media (max-width: 575.98px) {
      body {
        padding: 10px;
      }

      body > .container-fluid {
        min-height: calc(100vh - 20px);
        min-height: calc(100dvh - 20px);
      }

      .parent-login {
        max-width: 100%;
        border-radius: 14px;
      }

      .login-image {
        height: 118px;
        min-height: 118px;
      }

      .login-form {
        padding: 22px 18px !important;
      }

      /* Keep every field readable on narrow phones. */
      .forms .row.gx-2 > .col-6 {
        flex: 0 0 100%;
        width: 100%;
        max-width: 100%;
      }

      .forms .row.gx-2 > .col-6 + .col-6 {
        margin-top: 10px;
      }

      .forms .row.gx-2 {
        row-gap: 0;
      }

      .forms .mt-2 {
        margin-top: 12px !important;
      }

      .card-logo img {
        width: 46px;
        height: 46px;
      }

      .card-logo h2 {
        font-size: 20px;
      }

      .form-control {
        font-size: 16px;
      }

      .password-toggle-icon {
        right: 9px;
        font-size: 14px;
      }

      .back-home-btn {
        top: 10px;
        left: 10px;
        gap: 5px;
        padding: 8px 10px;
        font-size: 11px;
      }

      .error-box {
        padding: 9px 10px;
      }
    }

    @media (max-width: 360px) {
      body {
        padding: 7px;
      }

      .login-form {
        padding: 18px 13px !important;
      }

      .card-logo h2 {
        font-size: 18px;
      }

      .back-home-btn {
        font-size: 10px;
        padding: 7px 9px;
      }

      .login-link {
        font-size: 12px;
      }
    }

    @media (max-height: 650px) and (min-width: 768px) {
      body {
        align-items: flex-start;
      }

      body > .container-fluid {
        min-height: auto;
      }

      .parent-login {
        margin-top: 12px;
        margin-bottom: 12px;
      }

      .login-image {
        min-height: 500px;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        scroll-behavior: auto !important;
        transition-duration: 0.01ms !important;
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
      }
    }

  </style>
</head>

<body>

  <div
    class="container-fluid p-0 d-flex justify-content-center align-items-center">
    <div class="parent-login">

      <!-- Background / Graphic Section with Back to Homepage Button -->
      <div class="login-image">
        <a href="{{ route('index') }}" class="back-home-btn">
          <i class="fa-solid fa-arrow-left"></i> Back to Homepage
        </a>
      </div>

      <!-- Form Container -->
      <div class="login-form">
        <div class="forms">

          <div class="card-logo">
            <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo">
            <h2>Create an Account</h2>
          </div>

          <!-- Validation Errors -->
          @if ($errors->any())
            <div class="error-box">
              @foreach ($errors->all() as $error)
                <div>
                  <i class="fa-solid fa-triangle-exclamation"></i>
                  <span>{{ $error }}</span>
                </div>
              @endforeach
            </div>
          @endif

          <form action="{{ route('SignUp') }}" method="POST">
            @csrf

            <!-- Hidden Role & Preserved Course ID -->
            <input type="hidden" name="role" value="student">
            <input type="hidden" name="course_id"
              value="{{ request('course_id', session('enroll_course_id')) }}">

            <!-- First Name & Last Name -->
            <div class="row gx-2 mt-2">
              <div class="col-6">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" id="firstname" name="firstname"
                  class="form-control" placeholder="Juan" required
                  value="{{ old('firstname') }}" autocomplete="given-name">
              </div>
              <div class="col-6">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" id="lastname" name="lastname"
                  class="form-control" placeholder="Dela Cruz" required
                  value="{{ old('lastname') }}" autocomplete="family-name">
              </div>
            </div>

            <!-- Email Address -->
            <div class="mt-2">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" id="email" name="email"
                class="form-control" placeholder="juandelacruz@gmail.com"
                required value="{{ old('email') }}" autocomplete="email">
            </div>

            <!-- Username -->
            <div class="mt-2">
              <label for="username" class="form-label">Username</label>
              <input type="text" id="username" name="username"
                class="form-control" placeholder="juan_dc12" required
                value="{{ old('username') }}" autocomplete="username">
            </div>

            <!-- Password & Confirm Password -->
            <div class="row gx-2 mt-2">
              <div class="col-6">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                  <input type="password" id="password" name="password"
                    class="form-control" placeholder="New password" required
                    autocomplete="new-password" style="padding-right: 32px;">
                  <i class="fa-solid fa-eye password-toggle-icon"
                    id="togglePassword"
                    onclick="toggleVisibility('password', 'togglePassword')"></i>
                </div>
              </div>
              <div class="col-6">
                <label for="password_confirmation" class="form-label">Confirm
                  Password</label>
                <div class="password-wrapper">
                  <input type="password" id="password_confirmation"
                    name="password_confirmation" class="form-control"
                    placeholder="Re-enter password" required
                    autocomplete="new-password" style="padding-right: 32px;">
                  <i class="fa-solid fa-eye password-toggle-icon"
                    id="toggleConfirm"
                    onclick="toggleVisibility('password_confirmation', 'toggleConfirm')"></i>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-3">
              <button type="submit" class="btn-submit">
                <i class="fa-solid fa-user-plus me-1"></i> Create Account
              </button>
            </div>
          </form>

          <!-- Login Link (Forwarding Course ID) -->
          <div class="login-link">
            Already have an account?
            <a
              href="{{ route('Login', request('course_id') ? ['course_id' => request('course_id')] : []) }}">
              Login here
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- JavaScript for Password Toggles -->
  <script>
    function toggleVisibility(fieldId, iconId) {
      const field = document.getElementById(fieldId);
      const icon = document.getElementById(iconId);

      if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>

  <!-- JavaScript Files -->
  <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}">
  </script>
</body>

</html>
