<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - LEDIPO</title>

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="{{ asset('stylesheet/login.css') }}">
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

    /* Even 50/50 Split */
    .login-image {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.25)), url('{{ asset('images/3.jpg') }}') center/cover no-repeat;
      min-height: 480px;
      position: relative;
    }

    .login-form {
      flex: 0 0 50% !important;
      width: 50% !important;
      max-width: 50% !important;
      padding: 25px 35px !important;
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
      margin-bottom: 4px;
    }

    .card-logo img {
      width: 50px;
      height: 50px;
      object-fit: contain;
    }

    .login-form span.welcome-sub {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.05em;
      color: #1a4d2e;
      text-transform: uppercase;
      display: block;
      text-align: center;
    }

    .login-form h2 {
      font-size: 18px !important;
      font-weight: 700;
      color: #1a4d2e;
      text-align: center;
      margin-top: 4px;
      margin-bottom: 14px;
    }

    .login-form h2 span {
      font-style: italic;
      font-weight: 400;
      text-transform: none;
      display: inline;
      font-size: 18px !important;
      letter-spacing: normal;
      color: #1a4d2e;
    }

    .form-label {
      font-size: 11px;
      font-weight: 600;
      color: #1a4d2e;
      margin-bottom: 2px;
    }

    .form-control {
      border-radius: 8px;
      padding: 7px 10px;
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
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 40px;
      padding: 0;
      border: 0;
      border-radius: 6px;
      background: transparent;
      color: #1a4d2e;
      font-size: 15px;
      line-height: 1;
      cursor: pointer;
      touch-action: manipulation;
      z-index: 5;
    }

    .password-toggle-icon:focus-visible {
      outline: 2px solid #1a4d2e;
      outline-offset: 2px;
    }

    .btn-submit {
      background-color: #0d471d;
      color: #ffffff;
      font-weight: 700;
      padding: 9px;
      border-radius: 8px;
      border: none;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.2s;
      font-size: 13px;
    }

    .btn-submit:hover {
      background-color: #143c24;
    }

    .login-link {
      text-align: center;
      margin-top: 10px;
      font-size: 11px;
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
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 11px;
      margin-bottom: 8px;
    }

    .error-box ul {
      margin: 0;
      padding-left: 16px;
    }

    /* Remember Me & Forgot Password Row */
    .remember-forgot-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 12px;
    }

    .remember-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      user-select: none;
      margin: 0;
      font-size: 11.5px;
      font-weight: 500;
      color: #4a5568;
      transition: color 0.15s ease-in-out;
    }

    .remember-label:hover {
      color: #1a4d2e;
    }

    /* Custom Checkbox styled to match the green theme */
    .remember-label input[type="checkbox"] {
      appearance: none;
      -webkit-appearance: none;
      width: 16px;
      height: 16px;
      border: 1.5px solid #1a4d2e;
      border-radius: 4px;
      background-color: #ffffff;
      cursor: pointer;
      margin: 0;
      display: grid;
      place-content: center;
      transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .remember-label input[type="checkbox"]:hover {
      border-color: #0d471d;
    }

    .remember-label input[type="checkbox"]:focus {
      box-shadow: 0 0 0 2px rgba(26, 77, 46, 0.2);
    }

    .remember-label input[type="checkbox"]:checked {
      background-color: #1a4d2e;
      border-color: #1a4d2e;
    }

    .remember-label input[type="checkbox"]:checked::before {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      font-size: 10px;
      color: #ffffff;
    }

    /* Forgot Password Link */
    .forgot-link {
      font-size: 11.5px;
      font-weight: 600;
      color: #1a4d2e;
      text-decoration: none;
      transition: color 0.15s ease;
    }

    .forgot-link:hover {
      color: #0d471d;
      text-decoration: underline;
    }


    /* =========================================================
       RESPONSIVE LOGIN LAYOUT: TABLETS AND PHONES
       Keep desktop split layout, use a more spacious form on tablets,
       and stack the image banner above the form on small screens.
       ========================================================= */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html {
      min-height: 100%;
      -webkit-text-size-adjust: 100%;
    }

    body {
      width: 100%;
      min-width: 0;
    }

    .parent-login {
      min-width: 0;
    }

    .login-image,
    .login-form,
    .forms {
      min-width: 0;
    }

    .form-control {
      width: 100%;
      min-width: 0;
      min-height: 42px;
      box-sizing: border-box;
    }

    .remember-forgot-row {
      gap: 10px 14px;
    }

    .remember-label,
    .forgot-link {
      overflow-wrap: anywhere;
    }

    .password-wrapper .form-control {
      padding-right: 42px !important;
    }

    .password-toggle-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 30px;
      height: 40px;
      right: 5px;
      touch-action: manipulation;
    }

    /* Tablet landscape and smaller laptops */
    @media (min-width: 769px) and (max-width: 1024px) {
      body {
        padding: 24px !important;
        background-attachment: scroll;
      }

      .parent-login {
        max-width: 900px;
        border-radius: 18px;
      }

      .login-image {
        flex: 0 0 40% !important;
        width: 40% !important;
        max-width: 40% !important;
        min-height: 520px;
      }

      .login-form {
        flex: 0 0 60% !important;
        width: 60% !important;
        max-width: 60% !important;
        padding: 32px clamp(24px, 4vw, 42px) !important;
      }

      .card-logo img {
        width: 56px;
        height: 56px;
      }

      .login-form h2,
      .login-form h2 span {
        font-size: 21px !important;
      }

      .form-label {
        font-size: 12px;
        margin-bottom: 5px;
      }

      .form-control {
        min-height: 46px;
        padding: 10px 12px;
        font-size: 14px;
      }

      .btn-submit {
        min-height: 46px;
        font-size: 14px;
      }
    }

    /* Phones and portrait tablets */
    @media (max-width: 768px) {
      body {
        display: block !important;
        min-height: 100vh;
        min-height: 100dvh;
        padding: 12px !important;
        background-attachment: scroll !important;
      }

      .container-fluid.p-0.d-flex.justify-content-center.align-items-center {
        display: flex !important;
        align-items: flex-start !important;
        justify-content: center !important;
        width: 100% !important;
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 auto !important;
      }

      .parent-login {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        max-width: 480px !important;
        margin: 0 auto !important;
        border-radius: 18px !important;
        overflow: hidden !important;
      }

      /* Retain the image as a compact banner so Back to Homepage stays visible. */
      .login-image {
        display: block !important;
        flex: 0 0 112px !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: 112px !important;
        height: 112px !important;
        background-position: center 45% !important;
      }

      .back-home-btn {
        top: 14px !important;
        left: 14px !important;
        max-width: calc(100% - 28px);
        padding: 9px 13px !important;
        font-size: 12px !important;
        line-height: 1.3;
      }

      .login-form {
        display: flex !important;
        flex: 1 1 auto !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 26px clamp(20px, 6vw, 30px) 28px !important;
        justify-content: flex-start !important;
      }

      .forms {
        width: 100% !important;
        max-width: 420px;
        margin: 0 auto;
      }

      .card-logo {
        margin-bottom: 8px;
      }

      .card-logo img {
        width: 58px;
        height: 58px;
      }

      .login-form span.welcome-sub {
        font-size: 11px;
        letter-spacing: 0.08em;
      }

      .login-form h2,
      .login-form h2 span {
        font-size: clamp(21px, 5vw, 25px) !important;
        line-height: 1.35;
      }

      .login-form h2 {
        margin-top: 6px;
        margin-bottom: 20px;
      }

      .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
      }

      .form-control {
        min-height: 48px;
        padding: 12px 13px;
        border-radius: 10px;
        font-size: 16px !important; /* Prevents iOS focus zoom. */
        line-height: 1.4;
      }

      .password-wrapper .form-control {
        padding-right: 44px !important;
      }

      .password-toggle-icon {
        width: 36px;
        height: 44px;
        right: 4px;
        font-size: 16px;
      }

      .remember-forgot-row {
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        gap: 12px;
      }

      .remember-label,
      .forgot-link {
        font-size: 12px;
      }

      .remember-label {
        gap: 8px;
      }

      .remember-label input[type="checkbox"] {
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
      }

      .forgot-link {
        text-align: right;
      }

      .btn-submit {
        min-height: 49px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 15px;
      }

      .login-link {
        margin-top: 18px;
        font-size: 13px;
        line-height: 1.6;
      }

      .error-box {
        padding: 10px 12px;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
      }

      .alert[role="alert"] {
        font-size: 13px !important;
        line-height: 1.5;
        overflow-wrap: anywhere;
      }
    }

    /* Very narrow phones */
    @media (max-width: 380px) {
      body {
        padding: 8px !important;
      }

      .parent-login {
        border-radius: 15px !important;
      }

      .login-image {
        flex-basis: 100px !important;
        min-height: 100px !important;
        height: 100px !important;
      }

      .login-form {
        padding: 22px 16px 24px !important;
      }

      .remember-forgot-row {
        align-items: flex-start;
      }

      .remember-label,
      .forgot-link {
        font-size: 11.5px;
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
          </div>

          <span class="welcome-sub">WELCOME BACK</span>
          <h2>Sign <span>in to your</span> account</h2>

          <!-- Status Message -->
          @if (session('status'))
            <div class="alert alert-success py-1 px-2 small mt-1 mb-2"
              role="alert">
              {{ session('status') }}
            </div>
          @endif

          <!-- Single Session Error -->
          @if (session('error'))
            <div class="error-box mt-1">
              <div><i class="fa-solid fa-triangle-exclamation"></i>
                {{ session('error') }}</div>
            </div>
          @endif

          <!-- General Validation Errors -->
          @if ($errors->any())
            <div class="error-box mt-1">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('LoginUser') }}" method="POST">
            @csrf

            <!-- Preserved Hidden Course ID -->
            <input type="hidden" name="course_id"
              value="{{ request('course_id', session('enroll_course_id')) }}">

            <!-- Email Address -->
            <div class="mt-2">
              <label for="email" class="form-label">Email address</label>
              <input type="email" id="email" name="email"
                class="form-control" placeholder="Enter email address" required
                autocomplete="email" value="{{ old('email') }}">
              @error('email')
                <div class="text-danger small mt-1" style="font-size: 11px;">
                  {{ $message }}</div>
              @enderror
            </div>

            <!-- Password with Toggle -->
            <div class="mt-2">
              <label for="password" class="form-label">Password</label>
              <div class="password-wrapper">
                <input type="password" id="password" name="password"
                  class="form-control" placeholder="Enter password" required
                  autocomplete="current-password">
                <button type="button" class="password-toggle-icon"
                  id="togglePassword" aria-label="Show password"
                  aria-controls="password"
                  onclick="toggleVisibility('password', 'togglePassword')">
                  <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
              </div>
              @error('password')
                <div class="text-danger small mt-1" style="font-size: 11px;">
                  {{ $message }}</div>
              @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="remember-forgot-row">
              <label class="remember-label" for="remember">
                <input type="checkbox" name="remember" id="remember"
                  value="1" @checked(old('remember'))>
                <span>Remember Me</span>
              </label>

              <a href="{{ route('ForgotPassword') }}" class="forgot-link">
                Forgot Password?
              </a>
            </div>

            <!-- Submit Button -->
            <div class="mt-3">
              <button type="submit" class="btn-submit">Login</button>
            </div>

          </form>

          <!-- Signup Redirect: preserve the course ID from the URL or enrollment session. -->
          <div class="login-link">
            Don't have an account?
            @php
              $signupCourseId = request('course_id', session('enroll_course_id'));
            @endphp
            <a href="{{ route('SignupPage', $signupCourseId ? ['course_id' => $signupCourseId] : []) }}">Signup</a>
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- JavaScript for Password Toggle -->
  <script>
    function toggleVisibility(fieldId, toggleId) {
      const field = document.getElementById(fieldId);
      const toggle = document.getElementById(toggleId);
      const icon = toggle ? toggle.querySelector('i') : null;

      if (!field || !toggle || !icon) return;

      const shouldShowPassword = field.type === 'password';
      field.type = shouldShowPassword ? 'text' : 'password';
      toggle.setAttribute(
        'aria-label',
        shouldShowPassword ? 'Hide password' : 'Show password'
      );
      icon.classList.toggle('fa-eye', !shouldShowPassword);
      icon.classList.toggle('fa-eye-slash', shouldShowPassword);
    }
  </script>

  <!-- Bootstrap Bundle Script -->
  <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}">
  </script>
</body>

</html>

