<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | LEDIPO</title>

    <link rel="stylesheet" href="{{ asset('stylesheet/login.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap_folder/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome-icon/css/all.min.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo_ledipo.png') }}">
</head>

<body>

    <div class="container-fluid p-0">
        <div class="parent-login">

            <div class="login-image"></div>

            <div class="login-form">
                <div class="forms">

                    {{-- Success message --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Error message --}}
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('SendResetLink') }}" method="POST">
                        @csrf

                        <span>FORGOT PASSWORD</span>

                        <h2><b>Reset</b> your <b>password</b></h2>

                        <p class="text-muted mt-2">
                            Enter your email address and we'll send you
                            a link to reset your password.
                        </p>

                        <div class="mt-4">
                            <label for="email">Email address</label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control mt-1 @error('email') is-invalid @enderror"
                                placeholder="Enter your email address"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mt-4 login-btn">
                            <button class="btn w-100" type="submit">
                                Send Reset Link
                            </button>
                        </div>

                        <div class="mt-4 signup text-center">
                            <label>
                                Remember your password?
                                <a href="{{ route('Login') }}">Back to login</a>
                            </label>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script defer src="{{ asset('bootstrap_folder/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
```