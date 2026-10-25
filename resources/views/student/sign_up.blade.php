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


    /* Password Strength Indicator */
    .password-strength {
      font-size: 10px;
    }

    .strength-bars {
      display: flex;
      gap: 4px;
      margin-bottom: 4px;
    }

    .strength-bar {
      height: 4px;
      flex: 1;
      background: #e2e8e4;
      border-radius: 4px;
      transition: background-color 0.2s ease;
    }

    .strength-label {
      font-weight: 700;
      color: #64748b;
      margin-bottom: 2px;
    }

    .strength-hint {
      display: block;
      color: #64748b;
      line-height: 1.4;
    }

    .password-strength.low .strength-bar:nth-child(1) {
      background: #dc2626;
    }

    .password-strength.low .strength-label {
      color: #dc2626;
    }

    .password-strength.medium .strength-bar:nth-child(-n+2) {
      background: #d97706;
    }

    .password-strength.medium .strength-label {
      color: #d97706;
    }

    .password-strength.strong .strength-bar {
      background: #16a34a;
    }

    .password-strength.strong .strength-label {
      color: #16a34a;
    }


.strength-hint {
  display: block;
  text-align: left;
  margin-top: 6px;
  line-height: 1.6;
  font-size: 10px;
}

.requirement {
  color: #dc2626;
  transition: color 0.2s ease;
}

 .requirement.met {
  color: #16a34a;
}

.otp-panel { text-align: center; padding-top: 8px; }
.otp-panel p { color: #4a5568; font-size: 12px; line-height: 1.6; margin: 10px 0 16px; }
.otp-input { text-align: center; font-size: 22px; font-weight: 700; letter-spacing: 8px; padding: 12px !important; margin-bottom: 14px; }
.status-box { background: #dcfce7; color: #166534; padding: 9px 12px; border-radius: 6px; font-size: 11px; margin-bottom: 10px; }
.success-icon { color: #16a34a; font-size: 48px; margin: 10px 0 14px; }
.secondary-action { display: block; width: 100%; margin-top: 12px; border: 1px solid #0d471d; background: #fff; color: #0d471d; border-radius: 8px; padding: 9px; font-weight: 700; font-size: 12px; text-decoration: none; cursor: pointer; }
.secondary-action:hover { background: #f0fdf4; color: #143c24; }

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

          @if (session('status'))
            <div class="status-box" role="status">
              <i class="fa-solid fa-circle-check me-1"></i> {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="error-box" role="alert">
              @foreach ($errors->all() as $error)
                <div><i class="fa-solid fa-triangle-exclamation"></i><span>{{ $error }}</span></div>
              @endforeach
            </div>
          @endif

          @if (session('signup_success'))
            <div class="otp-panel">
              <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
              <h3 style="color:#1a4d2e;font-size:18px;font-weight:700;">Account Created!</h3>
              <p>Your email has been verified and your LEDIPO student account has been created successfully.</p>
              <a class="btn-submit d-block text-decoration-none" href="{{ route('Login', request('course_id') ? ['course_id' => request('course_id')] : []) }}">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Go to Login
              </a>
            </div>
          @elseif (session()->has('signup_pending'))
            <div class="otp-panel">
              <div style="color:#1a4d2e;font-size:36px;margin:8px 0 10px;"><i class="fa-solid fa-envelope-circle-check"></i></div>
              <h3 style="color:#1a4d2e;font-size:18px;font-weight:700;">Verify Your Email</h3>
              <p>Enter the six-digit verification code sent to<br><strong>{{ session('signup_pending.email') }}</strong>.<br>The code expires in 10 minutes.</p>

              <form action="{{ route('VerifySignupOtp') }}" method="POST">
                @csrf
                <label for="otp" class="form-label d-block text-start">Verification Code</label>
                <input type="text" id="otp" name="otp" class="form-control otp-input" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" placeholder="000000" aria-label="Six-digit verification code" required autofocus>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-shield-halved me-1"></i> Verify Code</button>
              </form>

              <form action="{{ route('ResendSignupOtp') }}" method="POST">
                @csrf
                <button type="submit" class="secondary-action"><i class="fa-solid fa-rotate me-1"></i> Resend Verification Code</button>
              </form>

            </div>
          @else
            <form action="{{ route('SignUp') }}" method="POST">
              @csrf
              <input type="hidden" name="role" value="student">
              <input type="hidden" name="course_id" value="{{ request('course_id', session('enroll_course_id')) }}">

              <div class="row gx-2 mt-2">
                <div class="col-6">
                  <label for="firstname" class="form-label">First Name</label>
                  <input type="text" id="firstname" name="firstname" class="form-control name-letters-only" placeholder="Juan" required value="{{ old('firstname') }}" autocomplete="given-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[ '-][A-Za-zÀ-ÖØ-öø-ÿ]+)*" title="Use letters only. Spaces, hyphens, and apostrophes are allowed between name parts." maxlength="100">
                </div>
                <div class="col-6">
                  <label for="lastname" class="form-label">Last Name</label>
                  <input type="text" id="lastname" name="lastname" class="form-control name-letters-only" placeholder="Dela Cruz" required value="{{ old('lastname') }}" autocomplete="family-name" pattern="[A-Za-zÀ-ÖØ-öø-ÿ]+(?:[ '-][A-Za-zÀ-ÖØ-öø-ÿ]+)*" title="Use letters only. Spaces, hyphens, and apostrophes are allowed between name parts." maxlength="100">
                </div>
              </div>

              <div class="mt-2">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="juandelacruz@gmail.com" required value="{{ old('email') }}" autocomplete="email">
              </div>

              <div class="mt-2">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="juan_dc12" required value="{{ old('username') }}" autocomplete="username">
              </div>

              <div class="row gx-2 mt-2">
                <div class="col-6">
                  <label for="password" class="form-label">Password</label>
                  <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="New password" required autocomplete="new-password" style="padding-right:32px;">
                    <i class="fa-solid fa-eye password-toggle-icon" id="togglePassword" onclick="toggleVisibility('password', 'togglePassword')"></i>
                  </div>
                  <div class="password-strength mt-2" id="passwordStrength" aria-live="polite">
                    <div class="strength-bars"><span class="strength-bar"></span><span class="strength-bar"></span><span class="strength-bar"></span></div>
                    <div class="strength-label" id="passwordStrengthText">Enter a password</div>
                    <div class="strength-hint" id="passwordRequirements">
                      <div class="requirement" data-rule="length">✗ At least 8 characters</div>
                      <div class="requirement" data-rule="uppercase">✗ One uppercase letter (A–Z)</div>
                      <div class="requirement" data-rule="lowercase">✗ One lowercase letter (a–z)</div>
                      <div class="requirement" data-rule="number">✗ One number (0–9)</div>
                      <div class="requirement" data-rule="symbol">✗ One special character (!@#$...)</div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <label for="password_confirmation" class="form-label">Confirm Password</label>
                  <div class="password-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-enter password" required autocomplete="new-password" style="padding-right:32px;">
                    <i class="fa-solid fa-eye password-toggle-icon" id="toggleConfirm" onclick="toggleVisibility('password_confirmation', 'toggleConfirm')"></i>
                  </div>
                </div>
              </div>

              <div class="mt-3"><button type="submit" class="btn-submit"><i class="fa-solid fa-user-plus me-1"></i> Create Account</button></div>
            </form>

            <div class="login-link">Already have an account? <a href="{{ route('Login', request('course_id') ? ['course_id' => request('course_id')] : []) }}">Login here</a></div>
          @endif

        </div>
      </div>

    </div>
  </div>

  <!-- Name fields: allow letters with spaces, hyphens, and apostrophes between name parts -->
  <script>
    (function () {
      const nameFields = document.querySelectorAll('.name-letters-only');

      nameFields.forEach(function (field) {
        field.addEventListener('input', function () {
          this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ' -]/g, '');
        });

        field.addEventListener('blur', function () {
          this.value = this.value.trim().replace(/\s+/g, ' ');
        });
      });
    })();
  </script>

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

    
    const passwordInput = document.getElementById('password');
    const strengthContainer = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');

    if (passwordInput && strengthContainer && strengthText) {
      passwordInput.addEventListener('input', function () {
      const password = this.value;

      const checks = [
        password.length >= 8,
        /[A-Z]/.test(password),
        /[a-z]/.test(password),
        /[0-9]/.test(password),
        /[^A-Za-z0-9]/.test(password)
      ];

      const score = checks.filter(Boolean).length;

      
const requirementRules = [
  { rule: 'length', passed: checks[0] },
  { rule: 'uppercase', passed: checks[1] },
  { rule: 'lowercase', passed: checks[2] },
  { rule: 'number', passed: checks[3] },
  { rule: 'symbol', passed: checks[4] }
];

requirementRules.forEach(function (item) {
  const requirement = document.querySelector(
    '[data-rule="' + item.rule + '"]'
  );

  if (!requirement) return;

  requirement.classList.toggle('met', item.passed);

  const originalText = requirement.textContent
    .replace(/^✓ |^✗ /, '');

  requirement.textContent =
    (item.passed ? '✓ ' : '✗ ') + originalText;
});


      strengthContainer.classList.remove('low', 'medium', 'strong');

      if (password.length === 0) {
        strengthText.textContent = 'Enter a password';
        return;
      }

      if (score === 5) {
        strengthContainer.classList.add('strong');
        strengthText.textContent = 'Strong password';
      } else if (password.length >= 8 && score >= 3) {
        strengthContainer.classList.add('medium');
        strengthText.textContent = 'Medium password';
      } else {
        strengthContainer.classList.add('low');
        strengthText.textContent = 'Low password strength';
      }
      });
    }

  </script>

  

  <!-- JavaScript Files -->
  <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}">
  </script>
</body>

</html>
