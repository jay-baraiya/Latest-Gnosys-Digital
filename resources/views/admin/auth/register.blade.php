<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register | {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">

    <style>
        /* Frosted glassmorphism effect matching the login page */
        .login-card {
            background: transparent !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            border: 0px solid rgba(255, 255, 255, 0.4);
            max-width: 450px;
            width: 100%;
        }

        /* Ensure input fields remain legible on the glass */
        .login-card .form-control, .login-card .input-group-text {
            background: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
</head>

<body class="account-page position-relative">

    <div id="vanta-canvas" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: -1; position: fixed;"></div>

    <div class="d-flex justify-content-center align-items-center min-vh-100 w-100 p-3">

        <div class="card login-card border-0 p-4 p-sm-5">

            <form method="POST" action="{{ route('admin.register') }}">
                @csrf

                <div class="text-center mb-4 auth-logo">
                    <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" class="img-fluid" alt="Logo" style="max-height: 50px;">
                </div>

                <div class="text-center mb-4">
                    <h3 class="mb-2 fw-bold text-white">Register</h3>
                    <p class="text-white-50 mb-0">Create your admin account.</p>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-semibold" for="name">Name</label>
                    <div class="input-group input-group-flat">
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                        <span class="input-group-text">
                            <i class="ti ti-user text-muted"></i>
                        </span>
                    </div>
                    @error('name')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-semibold" for="email">Email Address</label>
                    <div class="input-group input-group-flat">
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
                        <span class="input-group-text">
                            <i class="ti ti-mail text-muted"></i>
                        </span>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-semibold" for="password">Password</label>
                    <div class="input-group input-group-flat pass-group">
                        <input type="password" id="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="••••••••">
                        <span class="input-group-text toggle-password cursor-pointer">
                            <i class="ti ti-eye-off text-muted"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white fw-semibold" for="password_confirmation">Confirm Password</label>
                    <div class="input-group input-group-flat pass-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control pass-input" required autocomplete="new-password" placeholder="••••••••">
                        <span class="input-group-text toggle-password cursor-pointer">
                            <i class="ti ti-eye-off text-muted"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check form-check-md d-flex align-items-center">
                        <input class="form-check-input mt-0 cursor-pointer" type="checkbox" value="" id="checkebox-md" required>
                        <label class="form-check-label text-white ms-2 cursor-pointer" for="checkebox-md">
                            <small>I agree to the <a href="javascript:void(0);" class="text-white fw-bold text-decoration-underline">Terms & Privacy</a></small>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">Sign Up</button>
                </div>

                <div class="text-center mb-3">
                    <p class="mb-0 text-white-50"><small>Already have an account? <a href="{{ route('admin.login') }}" class="text-white fw-bold text-decoration-underline">Sign In Instead</a></small></p>
                </div>

            </form>

            <div class="text-center mt-3">
                <p class="text-white-50 mb-0"><small>Copyright &copy; {{ date('Y') }} - {{ config('app.name', 'Laravel') }}</small></p>
            </div>

        </div> </div>
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
