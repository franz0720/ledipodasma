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
      cursor: pointer;
      color: #1a4d2e;
      font-size: 13px;
      z-index: 5;
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
                  autocomplete="current-password" style="padding-right: 32px;">
                <i class="fa-solid fa-eye password-toggle-icon"
                  id="togglePassword"
                  onclick="toggleVisibility('password', 'togglePassword')"></i>
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
                  value="1">
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

          <!-- Signup Redirect (with preserved course_id query) -->
          <div class="login-link">
            Don't have an account?
            <a
              href="{{ route('SignupPage', request('course_id') ? ['course_id' => request('course_id')] : []) }}">Signup</a>
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- JavaScript for Password Toggle -->
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

  <!-- Bootstrap Bundle Script -->
  <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}">
  </script>
</body>

</html>

