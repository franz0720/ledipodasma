<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - LEDIPO</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/sign_up.css') }}">

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">
</head>

<body>

  <div class="signup-card">

    <div class="card-logo">
      <img src="{{ asset('images/logo.png') }}" alt="LEDIPO Logo">

      <h2>Create an Account</h2>

      <p>
        Start your learning journey with LEDIPO today.
      </p>
    </div>

    <div class="divider"></div>

    {{-- Validation Errors --}}
    @if ($errors->any())
      <div class="error-box">
        @foreach ($errors->all() as $error)
          <div>
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ $error }}
          </div>
        @endforeach
      </div>
    @endif

    <form action="{{ route('SignUp') }}" method="POST">
      @csrf

      {{-- Student role --}}
      <input type="hidden" name="role" value="student">

      {{-- IMPORTANT:
                 Preserve the course selected from the public course page.
                 Example: /signup?course_id=5
            --}}
      <input type="hidden" name="course_id"
        value="{{ request('course_id', session('enroll_course_id')) }}">

      <!-- First Name / Last Name -->
      <div class="form-row">

        <div class="form-group">
          <label for="firstname">
            First Name
          </label>

          <div class="input-wrap">
            <i class="fa-solid fa-user"></i>

            <input type="text" id="firstname" name="firstname"
              placeholder="Juan" required value="{{ old('firstname') }}"
              autocomplete="given-name">
          </div>
        </div>

        <div class="form-group">
          <label for="lastname">
            Last Name
          </label>

          <div class="input-wrap">
            <i class="fa-solid fa-user"></i>

            <input type="text" id="lastname" name="lastname"
              placeholder="Dela Cruz" required value="{{ old('lastname') }}"
              autocomplete="family-name">
          </div>
        </div>

      </div>

      <!-- Email -->
      <div class="form-group">

        <label for="email">
          Email Address
        </label>

        <div class="input-wrap">
          <i class="fa-solid fa-envelope"></i>

          <input type="email" id="email" name="email"
            placeholder="juandelacruz@gmail.com" required
            value="{{ old('email') }}" autocomplete="email">
        </div>

      </div>

      <!-- Username -->
      <div class="form-group">

        <label for="username">
          Username
        </label>

        <div class="input-wrap">
          <i class="fa-solid fa-at"></i>

          <input type="text" id="username" name="username"
            placeholder="juan_dc12" required value="{{ old('username') }}"
            autocomplete="username">
        </div>

      </div>

      <!-- Password / Confirm Password -->
      <div class="form-row">

        <div class="form-group">

          <label for="password">
            Password
          </label>

          <div class="input-wrap">
            <i class="fa-solid fa-lock"></i>

            <input type="password" id="password" name="password"
              placeholder="New password" required autocomplete="new-password">
          </div>

        </div>

        <div class="form-group">

          <label for="password_confirmation">
            Confirm Password
          </label>

          <div class="input-wrap">
            <i class="fa-solid fa-lock"></i>

            <input type="password" id="password_confirmation"
              name="password_confirmation" placeholder="Re-enter password"
              required autocomplete="new-password">
          </div>

        </div>

      </div>

      <!-- Create Account -->
      <button type="submit" class="btn-submit">
        <i class="fa-solid fa-user-plus"></i>
        Create Account
      </button>

    </form>

    <!-- Login Link -->
    <div class="login-link">

      Already have an account?

      <a
        href="{{ route('Login', request('course_id') ? ['course_id' => request('course_id')] : []) }}">
        Login here
      </a>

    </div>

  </div>

</body>

</html>
