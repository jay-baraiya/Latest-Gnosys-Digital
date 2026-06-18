<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Email | {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">

</head>

<body class="account-page bg-white">

    <!-- Begin Wrapper -->
    <div class="main-wrapper">

       <div class="overflow-hidden p-3 acc-vh">
            
            <!-- start row -->
            <div class="row vh-100 w-100 g-0"> 

                <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">

                     <!-- start row -->
                     <div class="row">

                        <div class="col-md-10 mx-auto">

                            <div class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                
                                <div class="text-center mb-4 auth-logo">
                                    <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Verify Email</h3>
                                        <p class="mb-0">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
                                    </div>
                                    
                                    @if (session('status') == 'verification-link-sent')
                                        <div class="alert alert-success fs-14">
                                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
                                        <form method="POST" action="{{ route('verification.send') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
                                        </form>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-dark fw-bold link-hover">{{ __('Log Out') }}</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">Copyright &copy; {{ date('Y') }} - {{ config('app.name', 'Laravel') }}</p>
                                </div>
                            </div>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div>

                <div class="col-lg-6 account-bg-01"></div> <!-- end col -->

            </div>
            <!-- end row -->

        </div>

    </div>
    <!-- End Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>    

    <!-- Main JS -->
    <script src="{{ asset('assets/js/script.js') }}"></script>

</body>

</html>
