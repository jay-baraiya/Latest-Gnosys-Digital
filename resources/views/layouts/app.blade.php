<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gnosys Digital - Expert-Built Digital Solutions') }}</title>

         <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}">

        <!-- Apple Icon -->
        <link rel="apple-touch-icon" href="{{ asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tabler Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <!-- Sweet Alerts CSS -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">

        <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Initialize Lenis
                const lenis = new Lenis({
                    duration: 1.2,       // Speed of the scroll (default is 1.2)
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // Default easing
                    direction: 'vertical',
                    gestureDirection: 'vertical',
                    smooth: true,
                    smoothTouch: false,  // Usually best to leave false for native mobile feel
                    touchMultiplier: 2,
                });

                // Setup the animation frame loop
                function raf(time) {
                    lenis.raf(time);
                    requestAnimationFrame(raf);
                }

                requestAnimationFrame(raf);
            });

            document.addEventListener("DOMContentLoaded", () => {
                // Desktop User Dropdown Toggle
                const userMenuBtn = document.getElementById('user-menu-button');
                const userDropdown = document.getElementById('user-dropdown');

                if (userMenuBtn && userDropdown) {
                    userMenuBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        userDropdown.classList.toggle('hidden');
                    });

                    document.addEventListener('click', (e) => {
                        if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                            userDropdown.classList.add('hidden');
                        }
                    });
                }
            });
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            html.lenis, html.lenis body {
                height: auto;
            }

            .lenis.lenis-smooth {
                scroll-behavior: auto !important;
            }

            .lenis.lenis-smooth [data-lenis-prevent] {
                overscroll-behavior: contain;
            }

            .lenis.lenis-stopped {
                overflow: hidden;
            }

            .lenis.lenis-scrolling iframe {
                pointer-events: none;
            }

            html {
                scrollbar-color: rgb(42 58 236) #f8fafc;
                scrollbar-width: thin;
            }

            ::-webkit-scrollbar {
                width: 10px;
                height: 10px;
            }

            ::-webkit-scrollbar-track {
                background: #f8fafc;
                border-radius: 8px;
            }

            ::-webkit-scrollbar-thumb {
                background: rgb(42 58 236);
                border-radius: 8px;
                border: 2px solid #f8fafc;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: rgb(32 48 210);
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-600 selection:bg-brand selection:text-white">

        @include('particles.users.header')

        <main>
            {{ $slot }}
        </main>

        @include('particles.users.footer')

        {{-- login modal --}}
        <div id="loginModal" class="fixed inset-0 z-50 hidden" aria-labelledby="login-modal-title" role="dialog" aria-modal="true">
            <div class="modal-backdrop fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity cursor-pointer"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md pointer-events-auto">

                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900" id="login-modal-title">Login to Your Account</h3>
                            <button type="button" class="close-login-modal text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6" id="loginFormContainer">
                            <form id="loginForm" action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition-colors" placeholder="user@example.com">
                                </div>

                                <div class="mb-5">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition-colors" placeholder="••••••••">
                                </div>

                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                                    </div>
                                    {{-- <div class="text-sm">
                                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                                    </div> --}}
                                </div>

                                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Sign In
                                </button>
                            </form>

                            <div class="mt-6 text-center text-sm text-gray-600">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Register now</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {

                // 1. Open / Close Mobile Menu
                const btnOpen = document.getElementById('mobile-menu-btn');
                const btnClose = document.getElementById('close-mobile-menu');
                const mobileMenu = document.getElementById('mobile-menu');

                if (btnOpen && btnClose && mobileMenu) {
                    // Open the menu
                    btnOpen.addEventListener('click', () => {
                        mobileMenu.classList.remove('hidden');
                        document.body.style.overflow = 'hidden'; // Prevents background scrolling
                    });

                    // Close the menu via close button
                    btnClose.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                        document.body.style.overflow = 'auto'; // Restores background scrolling
                    });

                    // Close the menu if user clicks the dark background overlay
                    mobileMenu.addEventListener('click', (e) => {
                        if(e.target === mobileMenu.firstElementChild) {
                            mobileMenu.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }
                    });
                }

                // 2. Open / Close the Dropdowns INSIDE the mobile menu
                const dropdownBtns = document.querySelectorAll('.mobile-dropdown-btn');

                dropdownBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Find the ID of the dropdown this button controls
                        const targetId = this.getAttribute('aria-controls');
                        const target = document.getElementById(targetId);
                        const icon = this.querySelector('svg'); // The arrow icon

                        // Toggle logic
                        if (target.classList.contains('hidden')) {
                            target.classList.remove('hidden');
                            icon.classList.add('rotate-180'); // Flip the arrow
                        } else {
                            target.classList.add('hidden');
                            icon.classList.remove('rotate-180'); // Reset the arrow
                        }
                    });
                });
            });
            </script>

            <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>

            <!-- Sweet Alerts js -->
            <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

            @if(session('success'))
                <script>
                    $.notify("{{ session('success') }}", "success");
                </script>
            @endif

            @if(session('error'))
                <script>
                    $.notify("{{ session('error') }}", "error");
                </script>
            @endif

            @if(session('warning'))
                <script>
                    $.notify("{{ session('warning') }}", "warning");
                </script>
            @endif

            <script>

                $(document).ready(function() {
                    const $loginModal = $('#loginModal');
                    const $loginForm = $('#loginForm');

                    window.openLoginModal = function() {
                        $loginModal.removeClass('hidden');
                        $('body').addClass('overflow-hidden');
                    };

                    $('#openLoginBtn').on('click', function() {
                        openLoginModal();
                    });

                    function closeLoginModal() {
                        $loginModal.addClass('hidden');
                        $('body').removeClass('overflow-hidden');

                        $loginForm[0].reset();
                        $loginForm.validate().resetForm();
                        $loginForm.find('.border-red-500').removeClass('border-red-500 focus:border-red-500 focus:ring-red-500').addClass('border-gray-300');
                    }

                    $('.close-login-modal, .modal-backdrop').on('click', function(e) {
                        closeLoginModal();
                    });

                    $loginForm.validate({
                        rules: {
                            email: {
                                required: true,
                                email: true
                            },
                            password: {
                                required: true,
                                // minlength: 8
                            }
                        },
                        messages: {
                            email: {
                                required: "Please enter your email address",
                                email: "Please enter a valid email format"
                            },
                            password: {
                                required: "Please enter your password",
                                minlength: "Password must be at least 6 characters long"
                            }
                        },
                        errorElement: 'span',
                        errorPlacement: function (error, element) {
                            error.addClass('text-red-500 text-xs mt-1 block font-medium');
                            error.insertAfter(element);
                        },
                        highlight: function (element, errorClass, validClass) {
                            $(element).addClass('border-red-500 focus:border-red-500 focus:ring-red-500').removeClass('border-gray-300 focus:border-blue-500 focus:ring-blue-500');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                            $(element).removeClass('border-red-500 focus:border-red-500 focus:ring-red-500').addClass('border-gray-300 focus:border-blue-500 focus:ring-blue-500');
                        },
                        submitHandler: function(form) {
                            form.submit();
                        }
                    });
                });

                $(document).ready(function() {

                    $('#wallet-trigger-btn').on('click', function(e) {
                        e.preventDefault();
                        $('#wallet-modal-wrapper').fadeIn(200);
                        $('body').css('overflow', 'hidden');
                    });

                    $('#close-wallet-modal').on('click', function() {
                        $('#wallet-modal-wrapper').fadeOut(200); // Smooth fade out
                        $('body').css('overflow', ''); // Unlocks background scrolling
                    });

                    // 3. CLOSE MODAL EVENT (Clicking the blurred background outside the card)
                    $('#wallet-modal-backdrop').on('click', function() {
                        $('#wallet-modal-wrapper').fadeOut(200);
                        $('body').css('overflow', '');
                    });

                });
            </script>

            @yield('script')
            @stack('script')
    </body>
</html>
