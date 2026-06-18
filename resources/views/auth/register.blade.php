<x-app-layout>
    <div class="flex min-h-[calc(100vh-80px)] bg-white w-full pt-20">

        <section class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden flex-col justify-between p-12">
            <div class="absolute inset-0 z-0">
                <img class="w-full h-full object-cover opacity-30" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAf_ehEiknehynuXlAqY4o8MDRq_4GHFN3KpPMjWJQm0GrS7YXchBGspstLMeHxOUpq_qlKbi6X0j97qEdwShFCDwqs2zM_bNcx3YEQGWXnVMow2U9BXnUs0HoINEStiPegygTL8-fesXSh_xxjhcriKMfusENPOEuTAVTfodVMbUmhGZrgNtF9sE2qmKcRf8WUyJh17lFEODdKHYV73S7qElkuSD26t4ItFKiaGZFqVNkPEwhDNcH9hjdP6IJWKMV1Dz5tL7PTLN0" alt="Background" />
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-transparent to-blue-900/50"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="ti ti-git-branch text-white text-xl"></i>
                    </div>
                    <span class="text-2xl text-white font-bold tracking-tight">{{ config('app.name', 'NexusB2B') }}</span>
                </div>
            </div>

            <div class="relative z-10 max-w-md">
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Scale your enterprise operations.</h1>
                <p class="text-lg text-slate-300 mb-8">
                    Join the digital marketplace designed for technical proficiency. Access elite solutions, managed services, and industry-leading enterprise tools in one unified ecosystem.
                </p>

                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20">
                        <i class="ti ti-shield-check text-blue-400 text-3xl mb-2"></i>
                        <p class="text-sm font-medium text-white">ISO 27001 Certified</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20">
                        <i class="ti ti-activity text-blue-400 text-3xl mb-2"></i>
                        <p class="text-sm font-medium text-white">99.9% Uptime SLA</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <p class="text-sm text-slate-400">
                    © {{ date('Y') }} {{ config('app.name', 'NexusB2B') }} Digital Marketplace. Global technical infrastructure.
                </p>
            </div>
        </section>

        <section class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md py-12">

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Create your account</h2>
                    <p class="text-slate-500 text-base">Start your journey with {{ config('app.name', 'Gnosys Digital') }} today.</p>
                </div>

                <form action="{{ route('register') }}" class="space-y-5" method="POST">
                    @csrf

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700" for="name">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-user text-slate-400 text-xl"></i>
                            </span>
                            <input class="w-full pl-10 pr-4 py-3 bg-slate-50 border rounded-lg text-slate-900 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none @error('name') border-red-500 @else border-slate-300 @enderror"
                                    id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" />
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-slate-700" for="email">Business Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ti ti-mail text-slate-400 text-xl"></i>
                            </span>
                            <input class="w-full pl-10 pr-4 py-3 bg-slate-50 border rounded-lg text-slate-900 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none @error('email') border-red-500 @else border-slate-300 @enderror"
                                    id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@company.com" />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700" for="password">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-lock text-slate-400 text-xl"></i>
                                </span>
                                <input class="w-full pl-10 pr-4 py-3 bg-slate-50 border rounded-lg text-slate-900 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none @error('password') border-red-500 @else border-slate-300 @enderror"
                                        id="password" name="password" type="password" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700" for="password_confirmation">Confirm</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ti ti-lock-check text-slate-400 text-xl"></i>
                                </span>
                                <input class="w-full pl-10 pr-4 py-3 bg-slate-50 border rounded-lg text-slate-900 focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none @error('password_confirmation') border-red-500 @else border-slate-300 @enderror"
                                        id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" />
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-start gap-3 py-2">
                        <div class="flex items-center h-5">
                            <input class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-600" id="terms" name="terms" type="checkbox" required />
                        </div>
                        <label class="text-sm text-slate-600" for="terms">
                            I agree to the <a class="text-blue-600 hover:underline transition-all" href="#">Terms of Service</a> and <a class="text-blue-600 hover:underline transition-all" href="#">Privacy Policy</a>.
                        </label>
                    </div>

                    <button class="w-full py-3.5 bg-blue-600 text-white font-semibold rounded-full shadow-md hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center justify-center gap-2" type="submit">
                        Create Account
                        <i class="ti ti-arrow-right text-xl"></i>
                    </button>
                </form>

                <div class="relative py-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white px-4 text-slate-500">Or register with</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-300 rounded-full text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                        <i class="ti ti-brand-google text-xl"></i>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-300 rounded-full text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">
                        <i class="ti ti-brand-github text-xl"></i>
                        GitHub
                    </button>
                </div>

                <div class="pt-8 text-center">
                    <p class="text-sm text-slate-600">
                        Already have an account?
                        <a class="text-blue-600 font-semibold hover:underline transition-all" href="{{ route('login') }}">Log in</a>
                    </p>
                </div>

            </div>
        </section>
    </div>

    @section('script')
        <script>
        // Subtle input focus animation
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('scale-[1.01]');
                input.parentElement.style.transition = 'transform 0.2s ease-in-out';
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('scale-[1.01]');
            });
        });
        </script>
    @endsection
</x-app-layout>
