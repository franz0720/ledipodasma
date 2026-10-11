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
