<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login | {{ config('app.name', 'NexusB2B') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        .glass-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .login-gradient {
            background: linear-gradient(135deg, rgba(0, 74, 198, 0.9) 0%, rgba(17, 24, 39, 0.95) 100%);
        }
    </style>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed-variant": "#003ea8",
                        "tertiary-fixed": "#6ffbbe",
                        "surface": "#f9f9ff",
                        "inverse-on-surface": "#ebf1ff",
                        "on-surface-variant": "#434655",
                        "on-secondary": "#ffffff",
                        "text-primary": "#1F2937",
                        "on-primary-fixed": "#00174b",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#575e70",
                        "on-secondary-fixed-variant": "#404758",
                        "on-secondary-fixed": "#141b2b",
                        "surface-container-highest": "#dce2f3",
                        "surface-tint": "#0053db",
                        "primary-container": "#2563eb",
                        "on-surface": "#151c27",
                        "tertiary-container": "#007d55",
                        "surface-container-high": "#e2e8f8",
                        "background": "#f9f9ff",
                        "surface-dim": "#d3daea",
                        "secondary-fixed": "#dce2f7",
                        "on-error-container": "#93000a",
                        "primary-fixed": "#dbe1ff",
                        "inverse-surface": "#2a313d",
                        "primary": "#004ac6",
                        "on-tertiary-container": "#bdffdb",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed": "#002113",
                        "on-background": "#151c27",
                        "on-primary-container": "#eeefff",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "border-subtle": "#E5E7EB",
                        "bg-dark": "#111827",
                        "surface-container": "#e7eefe",
                        "primary-fixed-dim": "#b4c5ff",
                        "outline": "#737686",
                        "secondary-fixed-dim": "#c0c6db",
                        "secondary-container": "#d9dff5",
                        "tertiary": "#006242",
                        "outline-variant": "#c3c6d7",
                        "bg-light-alt": "#F9FAFB",
                        "surface-variant": "#dce2f3",
                        "surface-container-low": "#f0f3ff",
                        "inverse-primary": "#b4c5ff",
                        "on-tertiary-fixed-variant": "#005236",
                        "surface-bright": "#f9f9ff",
                        "brand-primary-lab": "lab(44.0605% 29.0279 -86.0352)",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-secondary-container": "#5c6274"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-sm": "8px",
                        "section-padding-desktop": "120px",
                        "gutter": "32px",
                        "section-padding-mobile": "64px",
                        "stack-md": "16px",
                        "stack-lg": "24px",
                        "container-max": "1280px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-md-medium": ["Inter"],
                        "label-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "body-md": ["Inter"],
                        "display-xl-mobile": ["Plus Jakarta Sans"],
                        "display-xl": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "headline-lg": ["36px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-md-medium": ["16px", { "lineHeight": "1.6", "fontWeight": "500" }],
                        "label-sm": ["14px", { "lineHeight": "1.4", "letterSpacing": "0.01em", "fontWeight": "500" }],
                        "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "1.3", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "display-xl-mobile": ["48px", { "lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "800" }],
                        "display-xl": ["60px", { "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "800" }]
                    }
                },
            },
        }
    </script>
</head>

<body class="bg-surface font-body-md text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">

    <header class="fixed top-0 w-full z-50 bg-surface/80 dark:bg-bg-dark/80 backdrop-blur-md border-b border-border-subtle dark:border-outline-variant">
        <div class="flex justify-between items-center h-20 px-gutter max-w-container-max mx-auto">
            <div class="flex items-center gap-2">
                <span class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed">{{ config('app.name', 'NexusB2B') }}</span>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Solutions</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Marketplace</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Enterprise</a>
                <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">About</a>
            </nav>
            <div class="flex items-center gap-stack-md">
                <a class="font-body-md text-body-md text-primary font-medium px-4 py-2 hover:bg-surface-container-low rounded-full transition-all" href="#">Sign In</a>
                <a href="{{ route('register') }}" class="bg-primary text-on-primary px-6 py-2.5 rounded-full font-body-md text-body-md font-medium hover:scale-105 transition-transform duration-200">Get Started</a>
            </div>
        </div>
    </header>

    <main class="min-h-screen pt-20 flex flex-col md:flex-row overflow-hidden">

        <section class="relative w-full md:w-[55%] min-h-[400px] md:min-h-0 bg-bg-dark flex items-center justify-center p-12 md:p-24 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img alt="Abstract tech visual" class="w-full h-full object-cover opacity-40" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAiiCJMrS4eCD9g6CwKKx5spgvF0Ho7V67UKxzdfXqR7Y7k2dxIspOf713w1bn2XE2WQBXT-T2tZbNNpDQTBW0QFGVS5OrJ9XXU2Cg4XspYiTL1t4NNdVK9pNg0veJ6Y9W6hCkwWVSZTCMfA7FaQLH418XoHBDpZ6mCJh_Iiys1Vw4Tctlyt_YJjIKCSMNs-E49a947kr0o4BQ7mVX_yM0zCSZlC0rwJ-b-9cPHj22yn0fBB1lztfulT1qDI2PpxP22UD916CKN1oU" />
                <div class="absolute inset-0 login-gradient"></div>
            </div>
            <div class="relative z-10 max-w-xl">
                <div class="mb-stack-lg">
                    <span class="material-symbols-outlined text-primary-fixed text-5xl mb-stack-md">verified</span>
                    <h1 class="font-display-xl text-display-xl text-white mb-stack-md">Expert-Built <br />Solutions.</h1>
                    <p class="font-body-lg text-body-lg text-surface-variant opacity-90 leading-relaxed">
                        "Reliability isn't just a feature; it's the foundation of everything we build. Join the network of enterprise leaders driving digital transformation."
                    </p>
                </div>
                <div class="flex items-center gap-4 mt-12 pt-12 border-t border-white/10">
                    <div class="flex -space-x-3">
                        <img alt="User 1" class="w-10 h-10 rounded-full border-2 border-bg-dark" src="https://lh3.googleusercontent.com/aida-public/AB6AXuANAGKIesIpChu2Vcl4K_WsZQla5kn2njis9ABLU4J3iTipHdIcEEiyX56Iw0lwBJAIR3qa2tJRiowhXv9nruhQJJo6a8451DaSBXQ8dwVmf5Hl22HC5eyyZgB54XgFRPzQRiNYM7kc7w_mZzBCKu2m15kF5OHwtqyW3y1cLM13hUaWRCGVXt6YLsSx583N8rw4TquVxJFnd8VAo3zKKAhdAVKjSPk2KXQlJCnnwSOa19K5NDPIAM8Q-xwoOj7NcZKJ8vyIZ0jtpGY" />
                        <img alt="User 2" class="w-10 h-10 rounded-full border-2 border-bg-dark" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC63HFUHpNzbeUGNcLHxc1G-lIBMhLeULPO7B7MIJep3-UvLmJSxpyhk10afOgZmgocvjvjN-TQw8cYgzHS6zdK2_mwtlc88nWqyBLTX7B7J6dAVBpescPqx2YmHO7MplYP30WjBUHZzDBC71vXfW4d5zfpIqWGZuCBc1wkfrwDL0uJVi4238g51ocZjPZo2AQBUmvaZ_UAub2DrYUQJ_niWrMtLMY3mle1MFloZaJ5iu9wish6poF8rS8gYzvL2qtBwPuO0c9f2eU" />
                        <img alt="User 3" class="w-10 h-10 rounded-full border-2 border-bg-dark" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDi_nJQKl8Ie_ROS_i_SE_-8Ax4uKcZe42s2xeRrEZ6EJP25khZG9B9WyeLebZrQO7ZJmfYUtKLr__HQIKlFDgiuzx1UowHu73Q3DBQ5KlYlb_bFEk9yRbGD5XcX8qRVpgs5CS2T4KV6PbsaQxvbITZlbbPPkEN4ty80drXmSlKqoHpWEby9Ykekb_vQna5YQJnfq7D_9eaT1un7E0vw8P4lJqme3_l8kUCcjcLOfJ6AAmIuNL-iKssF4VQXaeXkikQrWmYTXRd8dk" />
                    </div>
                    <span class="text-white/80 font-label-sm text-label-sm">Trusted by 500+ Enterprise Partners</span>
                </div>
            </div>
            <div class="absolute inset-0 pointer-events-none opacity-30" id="particle-container"></div>
        </section>

        <section class="w-full md:w-[45%] bg-surface flex flex-col justify-center px-8 md:px-16 lg:px-24 py-16">
            <div class="max-w-md w-full mx-auto">

                <div class="mb-12">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Welcome back</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Sign in to your {{ config('app.name', 'NexusB2B') }} workspace</p>
                </div>

                <x-auth-session-status class="mb-4 text-primary font-medium" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-stack-lg">
                    @csrf

                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface mb-2" for="email">Corporate Email</label>
                        <input class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all duration-200 outline-none placeholder:text-outline-variant @error('email') border-error @else border-border-subtle @enderror"
                               id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@company.com" />
                        @error('email')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block font-label-sm text-label-sm text-on-surface" for="password">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-primary font-label-sm text-label-sm hover:underline transition-all" href="{{ route('password.request') }}">Forgot Password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary bg-white transition-all duration-200 outline-none placeholder:text-outline-variant @error('password') border-error @else border-border-subtle @enderror"
                                   id="password" name="password" required autocomplete="current-password" placeholder="••••••••" type="password" />
                            <button id="toggle-password-btn" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center py-2">
                        <input class="w-4 h-4 text-primary border-border-subtle rounded focus:ring-primary" id="remember" name="remember" type="checkbox" />
                        <label class="ml-2 font-body-md text-body-md text-on-surface-variant" for="remember">Keep me signed in</label>
                    </div>

                    <button class="w-full py-4 bg-primary text-on-primary rounded-full font-body-md-medium text-body-md-medium hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2 group" type="submit">
                        Sign In
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-10 pt-10 border-t border-border-subtle text-center">
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        New to {{ config('app.name', 'Gnosys Digital') }}?
                        <a class="text-primary font-semibold hover:underline ml-1" href="{{ route('register') }}">Create an account</a>
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-2 px-4 py-2.5 border border-border-subtle rounded-lg hover:bg-surface-container-low transition-colors font-label-sm text-label-sm text-on-surface">
                        <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="currentColor"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="currentColor"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="currentColor"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="currentColor"></path></svg>
                        Google
                    </button>
                    <button class="flex items-center justify-center gap-2 px-4 py-2.5 border border-border-subtle rounded-lg hover:bg-surface-container-low transition-colors font-label-sm text-label-sm text-on-surface">
                        <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.419 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.341-3.369-1.341-.454-1.152-1.11-1.459-1.11-1.459-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z" fill="currentColor"></path></svg>
                        GitHub
                    </button>
                </div>

            </div>
        </section>

    </main>

    <footer class="w-full py-stack-lg bg-surface border-t border-border-subtle dark:border-outline-variant">
        <div class="flex flex-col md:flex-row justify-between items-center px-gutter max-w-container-max mx-auto gap-stack-md">
            <div class="text-on-surface-variant font-label-sm text-label-sm">
                © {{ date('Y') }} {{ config('app.name', 'NexusB2B') }} Digital Marketplace. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <script>
        // Micro-interactions: Floating particles in the hero section
        const container = document.getElementById('particle-container');
        if (container) {
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute rounded-full bg-primary-fixed opacity-20 pointer-events-none';

                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;

                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;

                const duration = Math.random() * 10 + 10;
                const delay = Math.random() * 5;

                particle.style.animation = `float ${duration}s ${delay}s infinite linear`;
                container.appendChild(particle);
            }
        }

        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes float {
                0% { transform: translateY(0) translateX(0); opacity: 0; }
                20% { opacity: 0.3; }
                80% { opacity: 0.3; }
                100% { transform: translateY(-100px) translateX(20px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Interaction: Password toggle logic
        document.getElementById('toggle-password-btn').addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = this.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        });
    </script>
</body>

</html>
