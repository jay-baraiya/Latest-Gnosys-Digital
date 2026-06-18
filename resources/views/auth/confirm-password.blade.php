<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirm Password | {{ config('app.name', 'Laravel') }}</title>
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

                            <form method="POST" action="{{ route('password.confirm') }}" class=" vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                @csrf

                                <div class="text-center mb-4 auth-logo">
                                    <img src="{{ asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Confirm Password</h3>
                                        <p class="mb-0">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password" id="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror" required autocomplete="current-password">
                                            <span class="input-group-text toggle-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">{{ __('Confirm') }}</button>
                                    </div>
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">Copyright &copy; {{ date('Y') }} - {{ config('app.name', 'Laravel') }}</p>
                                </div>
                            </form>
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
