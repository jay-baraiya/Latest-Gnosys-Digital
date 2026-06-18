<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">

    <style>
        /* Updated styles for the frosted glassmorphism effect */
        .login-card {
            background: transparent !important; /* Lowered opacity to let background show */
            backdrop-filter: blur(15px); /* Increased blur */
            -webkit-backdrop-filter: blur(15px); /* Safari support */
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37); /* Deeper shadow for depth */
            border: 0px solid rgba(255, 255, 255, 0.4); /* Subtle border to define the glass edge */
            max-width: 450px;
            width: 100%;
        }

        /* Added to ensure input fields remain legible on the glass */
        .login-card .form-control, .login-card .input-group-text {
            background: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
</head>

<body class="account-page position-relative">

    <div id="vanta-canvas" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1; position: fixed;"></div>

    <div class="d-flex justify-content-center align-items-center min-vh-100 w-100 p-3">

        <div class="card login-card p-4 p-sm-5">

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="text-center mb-4 auth-logo">
                    <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" class="img-fluid" alt="Logo" style="max-height: 50px;">
                </div>

                <div class="text-center mb-4">
                    <h3 class="mb-2 fw-bold text-white">Welcome Back</h3> <p class="text-white-50 mb-0">Access the admin panel securely.</p> </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-semibold" for="email">Email Address</label>
                    <div class="input-group input-group-flat">
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                        <span class="input-group-text">
                            <i class="ti ti-mail text-muted"></i>
                        </span>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-white fw-semibold" for="password">Password</label>
                    <div class="input-group input-group-flat pass-group">
                        <input type="password" id="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="••••••••">
                        <span class="input-group-text toggle-password cursor-pointer">
                            <i class="ti ti-eye-off text-muted"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check form-check-md d-flex align-items-center">
                        <input class="form-check-input mt-0 cursor-pointer" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label text-white ms-2 cursor-pointer" for="remember_me">
                            <small>Remember Me</small>
                        </label>
                    </div>
                    <div class="text-end">
                        @if (Route::has('admin.password.request'))
                            <a href="{{ route('admin.password.request') }}" class="text-white text-decoration-underline fw-medium"><small>Forgot Password?</small></a>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">Sign In</button>
                </div>

                <div class="text-center mb-3">
                    <p class="mb-0 text-white-50"><small>New on our platform? <a href="{{ route('admin.register') }}" class="text-white fw-bold text-decoration-underline">Create an account</a></small></p>
                </div>

            </form>

            <div class="text-center mt-4">
                <p class="text-white-50 mb-0"><small>Copyright &copy; {{ date('Y') }} - {{ config('app.name', 'Laravel') }}</small></p>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            VANTA.NET({
                el: "#vanta-canvas",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0xff3f81,
                backgroundColor: 0x23153c
            });
        });
    </script>

</body>
</html>
