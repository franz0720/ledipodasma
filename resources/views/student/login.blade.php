<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="{{ asset('stylesheet/login.css') }}">
  <link rel="stylesheet"
    href="{{ asset('bootstrap_folder/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('font-awesome-icon/css/all.min.css') }}">

  <!-- Tab Icon -->
  <link rel="icon" type="image/png"
    href="{{ asset('images/logo_ledipo.png') }}">
</head>

<body>

  <div class="container-fluid p-0">
    <div class="parent-login">

      <!-- Background / Graphic Section -->
      <div class="login-image"></div>

      <!-- Form Container -->
      <div class="login-form">
        <div class="forms">

          <form action="{{ route('LoginUser') }}" method="POST">
            @csrf

            {{-- IMPORTANT:
                             Preserve the course the student wants to enroll in.
                             This works when the user came from a course page.
                        --}}
            <input type="hidden" name="course_id"
              value="{{ request('course_id', session('enroll_course_id')) }}">

            <!-- Logo -->
            <div class="login-logo">
              <img src="{{ asset('images/logo.png') }}" alt="Logo"
                class="logo-img">
            </div>

            <span>WELCOME BACK</span>

            <h2>
              <b>Sign</b> in to your <b>account</b>
            </h2>

            <!-- Success / Status Message -->
            @if (session('status'))
              <div class="alert alert-success mt-3 mb-0" role="alert">
                {{ session('status') }}
              </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
              <div class="alert alert-danger mt-3 mb-0" role="alert">
                {{ session('error') }}
              </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
              <div class="alert alert-danger mt-3 mb-0">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Email -->
            <div class="mt-4">
              <label for="email">Email address</label>

              <input type="email" id="email" name="email"
                value="{{ old('email') }}"
                class="form-control mt-1 @error('email') is-invalid @enderror"
                placeholder="Enter email address" required autocomplete="email">

              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
              <label for="password">Password</label>

              <input type="password" id="password" name="password"
                class="form-control mt-1 @error('password') is-invalid @enderror"
                placeholder="Enter password" required
                autocomplete="current-password">

              @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Remember Me / Forgot Password -->
            <div class="mt-3 d-flex justify-content-between align-items-center">

              <div class="form-check">
                <input type="checkbox" name="remember" id="remember"
                  class="form-check-input" value="1">

                <label class="form-check-label" for="remember">
                  Remember Me
                </label>
              </div>

              <div class="forgot">
                <a href="{{ route('ForgotPassword') }}">
                  Forgot Password?
                </a>
              </div>

            </div>

            <!-- Login Button -->
            <div class="mt-4 login-btn">
              <button class="btn w-100" type="submit">
                Login
              </button>
            </div>

            <!-- Signup Redirect -->
            <div class="mt-4 signup text-center">

              <label>
                Don't have an account?

                <a
                  href="{{ route('SignupPage', request('course_id') ? ['course_id' => request('course_id')] : []) }}">
                  Signup
                </a>
              </label>

            </div>

          </form>

        </div>
      </div>

    </div>
  </div>

  <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}">
  </script>

</body>

</html>
