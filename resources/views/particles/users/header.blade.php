<header class="fixed inset-x-0 top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8" aria-label="Global">

        <div class="flex lg:flex-1">
            <a href="{{ route('home') }}" class="items-center gap-2">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" alt="Gnosys Digital logo" class="h-[40px] md:h-[50px] w-auto">
                </div>
            </a>
        </div>

        <div class="hidden lg:flex lg:gap-x-12 items-center">

            {{-- Digital Services Dropdown --}}
            <div class="relative group">
                {{-- Changed from <button> to <a> and added route --}}
                <a href="{{ route('services.listing') }}" class="flex items-center gap-x-1 text-sm font-medium leading-6  hover:text-brand transition-colors {{ request()->routeIs('services.*') ? 'text-brand' : 'text-slate-900' }}">
                    Digital Services
                    {{-- <svg class="h-4 w-4 flex-none text-slate-400 group-hover:text-brand transition-colors" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg> --}}
                </a>
                {{-- <div class="absolute -left-4 top-full z-10 pt-4 hidden group-hover:block w-56">
                    <div class="rounded-xl bg-white p-2 shadow-lg ring-1 ring-slate-900/5">
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">Web Development</a>
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">GSAP Animations</a>
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">3D Web Solutions</a>
                    </div>
                </div> --}}
            </div>

            {{-- Digital Products Dropdown --}}
            <div class="relative group">
                {{-- Updated href from "button" to the correct route --}}
                <a href="{{ route('products.listing') }}" class="flex items-center gap-x-1 text-sm font-medium leading-6  hover:text-brand transition-colors {{ request()->routeIs('products.*') ? 'text-brand' : 'text-slate-900' }}">
                    Digital Products
                    {{-- <svg class="h-4 w-4 flex-none text-slate-400 group-hover:text-brand transition-colors" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg> --}}
                </a>
                {{-- <div class="absolute -left-4 top-full z-10 pt-4 hidden group-hover:block w-56">
                    <div class="rounded-xl bg-white p-2 shadow-lg ring-1 ring-slate-900/5">
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">Laravel Boilerplates</a>
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">AdminLTE Themes</a>
                        <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium leading-6 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">Caption Generators</a>
                    </div>
                </div> --}}
            </div>

            <a href="#" class="text-sm font-medium leading-6 text-slate-900 hover:text-brand transition-colors">ERP Solutions</a>
            <a href="{{ route('blogs.listing') }}" class="text-sm font-medium leading-6 hover:text-brand transition-colors {{ request()->routeIs('blogs.*') ? 'text-brand' : 'text-slate-900' }}">Blogs</a>
            <a href="#" class="text-sm font-medium leading-6 text-slate-900 hover:text-brand transition-colors">About Us</a>
        </div>

        <div class="flex flex-1 justify-end items-center gap-3 sm:gap-5">

            @auth

                <a href="{{ route('profile.edit') }}#wallet" id="wallet-trigger-btn-" class="hidden sm:flex items-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-full transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                    </svg>
                    <span class="text-sm font-semibold text-slate-800">${{ number_format(auth()->user()?->balance?->balance, 2) }}</span>
                </a>

                <div class="relative hidden lg:block">
                    <button type="button" id="user-menu-button" class="flex items-center text-slate-600 hover:text-brand transition-colors p-1 focus:outline-none">
                        <span class="sr-only">User Profile</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </button>

                    <div id="user-dropdown" class="hidden absolute right-0 top-full z-50 mt-2 w-56 rounded-xl bg-white py-2 shadow-lg ring-1 ring-slate-900/5 origin-top-right">
                        <div class="px-4 py-3 border-b border-slate-100 mb-1">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand transition-colors">Account Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth

            <a href="{{ route('cart') }}" class="text-slate-600 hover:text-brand transition-colors relative p-1">
                <span class="sr-only">Cart</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-brand text-[9px] font-bold text-white cart-count-badge">
                    {{ auth()->check() ? auth()->user()?->getCartItems?->count() : count(session('cart', [])) }}
                </span>
            </a>

            @guest
                <a href="{{ route('login') }}" class="hidden lg:inline-flex group items-center justify-center rounded-full py-2 px-4 text-sm font-semibold bg-brand text-white hover:bg-brand/90 transition-colors">
                    Login
                </a>
            @endguest

            <button type="button" id="mobile-menu-btn" class="lg:hidden inline-flex items-center justify-center rounded-md p-1 text-slate-700 hover:text-brand transition-colors">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </nav>
</header>

<div class="hidden lg:hidden" id="mobile-menu" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="fixed inset-y-0 right-0 z-[101] w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-slate-900/10" data-lenis-prevent>

        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <a href="{{ route('home') }}" class="-m-1.5 p-1.5">
                <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" alt="Gnosys Digital logo" class="h-8 w-auto">
            </a>
            <button type="button" id="close-mobile-menu" class="-m-2.5 rounded-md p-2.5 text-slate-700 hover:text-brand transition-colors">
                <span class="sr-only">Close menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <div>
                <a href="{{ route('services.listing') }}" class="mobile-dropdown-btn flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-medium leading-7 hover:bg-slate-50 transition-colors {{ request()->routeIs('services.*') ? 'text-brand' : 'text-slate-900' }}" aria-controls="mobile-dropdown-1">
                    Digital Services
                    {{-- <svg class="h-5 w-5 flex-none transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg> --}}
                </a>
                {{-- <div class="mt-2 space-y-2 hidden pl-4 border-l-2 border-slate-100 ml-4" id="mobile-dropdown-1">
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">Web Development</a>
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">GSAP Animations</a>
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">3D Web Solutions</a>
                </div> --}}
            </div>

            <div>
                <a href="{{ route('products.listing') }}" class="mobile-dropdown-btn flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base font-medium leading-7 hover:bg-slate-50 transition-colors {{ request()->routeIs('products.*') ? 'text-brand' : 'text-slate-900' }}" aria-controls="mobile-dropdown-2">
                    Digital Products
                    {{-- <svg class="h-5 w-5 flex-none transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg> --}}
                </a>
                {{-- <div class="mt-2 space-y-2 hidden pl-4 border-l-2 border-slate-100 ml-4" id="mobile-dropdown-2">
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">Laravel Boilerplates</a>
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">AdminLTE Themes</a>
                    <a href="#" class="block rounded-lg py-2 pl-2 pr-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">Caption Generators</a>
                </div> --}}
            </div>

            <a href="#" class="block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">ERP Solutions</a>
            <a href="{{ route('blogs.listing') }}" class="text-sm font-medium leading-6 hover:text-brand transition-colors {{ request()->routeIs('blogs.*') ? 'text-brand' : 'text-slate-900' }} ">Blogs</a>
            <a href="#" class="block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-900 hover:bg-slate-50 hover:text-brand transition-colors">About Us</a>

            <div class="mt-6 pt-6 border-t border-slate-100">
                @guest
                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-full py-2.5 px-4 text-sm font-semibold bg-brand text-white hover:bg-brand/90 transition-colors">
                        Login
                    </a>
                @endguest

                @auth
                    <div class="flex items-center px-2 mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-semibold leading-none text-slate-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium leading-none text-slate-500 mt-1">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="px-2 mb-4">
                        <a href="#" class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200 hover:border-brand/30 hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm border border-slate-100">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Wallet Balance</span>
                            </div>
                            <span class="font-bold text-slate-900">${{ number_format(auth()->user()?->balance?->balance, 2) }}</span>
                        </a>
                    </div>

                    <div class="space-y-1 px-2">
                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand transition-colors">Account Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="block w-full text-left rounded-lg px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand transition-colors">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div id="wallet-modal-wrapper" class="fixed inset-0 z-[110] flex items-center justify-center p-4" style="display: none;">

    <div id="wallet-modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm cursor-pointer"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md z-10 overflow-hidden flex flex-col">

        <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                </svg>
                My Wallet
            </h3>
            <button id="close-wallet-modal" type="button" class="text-slate-400 hover:text-slate-700 transition-colors rounded-lg p-1.5 hover:bg-slate-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">

            <div class="text-center mb-6">
                <p class="text-sm font-medium text-slate-500 mb-1">Available Balance</p>
                <p class="text-4xl font-extrabold text-slate-900 tracking-tight">${{ number_format(auth()->user()?->balance?->balance, 2) }}</p>
            </div>

            <hr class="border-slate-100 mb-6">

            <form action="#" method="POST" id="add-funds-form">
                @csrf
                <div class="mb-5">
                    <label for="amount" class="block text-sm font-semibold text-slate-700 mb-1.5">Add Funds to Wallet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-slate-500 font-medium sm:text-sm">$</span>
                        </div>
                        <input type="number" name="amount" id="amount" min="1" step="0.01" required
                            class="block w-full pl-8 pr-4 py-3 rounded-xl border-slate-300 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg transition-all"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mb-6">
                    <button type="button" id="cancel-wallet-btn" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md hover:shadow-lg transition-all focus:outline-none">
                        Add Balance
                    </button>
                </div>
            </form>

            <button type="button" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-50 text-slate-700 hover:text-blue-600 hover:bg-blue-50 rounded-xl font-medium transition-colors border border-slate-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                View Transaction History
            </button>

        </div>
    </div>
</div>
