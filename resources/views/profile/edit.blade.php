<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight mt-20">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    @php
        $backendTab = '';

        if ($errors->hasAny(['amount', 'transaction_id', 'payment_proof']) || session('status') === 'funds-added') {
            $backendTab = 'wallet';
        } elseif ($errors->updatePassword->isNotEmpty() || session('status') === 'password-updated') {
            $backendTab = 'password';
        } elseif ($errors->userDeletion->isNotEmpty()) {
            $backendTab = 'danger';
        }
    @endphp

    <div class="pt-32 pb-12 bg-slate-50 min-h-screen"
         x-data="{
            tab: '{{ $backendTab }}' !== '' ? '{{ $backendTab }}' : (window.location.hash ? window.location.hash.replace('#', '') : 'profile'),

            // Helper function to switch tabs and update the browser URL
            switchTab(newTab) {
                this.tab = newTab;
                window.history.replaceState(null, null, '#' + newTab);
            }
         }"
         x-cloak>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row gap-8">

                <div class="w-full md:w-64 flex-shrink-0">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto pb-4 md:pb-0 hide-scrollbar">

                        <button
                            @click="switchTab('profile')"
                            :class="{ 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100': tab === 'profile', 'text-slate-600 hover:bg-slate-100 font-medium': tab !== 'profile' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            Profile Information
                        </button>

                        <button
                            @click="switchTab('orders')"
                            :class="{ 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100': tab === 'orders', 'text-slate-600 hover:bg-slate-100 font-medium': tab !== 'orders' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            Orders
                        </button>

                        <button
                            @click="switchTab('wallet')"
                            :class="{ 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100': tab === 'wallet', 'text-slate-600 hover:bg-slate-100 font-medium': tab !== 'wallet' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                            Wallet & Billing
                        </button>

                        <button
                            @click="switchTab('ticket')"
                            :class="{ 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100': tab === 'ticket', 'text-slate-600 hover:bg-slate-100 font-medium': tab !== 'ticket' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ticket"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M15 5l0 2" /><path d="M15 11l0 2" /><path d="M15 17l0 2" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>
                            Tickets
                        </button>

                        <button
                            @click="switchTab('password')"
                            :class="{ 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100': tab === 'password', 'text-slate-600 hover:bg-slate-100 font-medium': tab !== 'password' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                            Security & Password
                        </button>

                        <button
                            @click="switchTab('danger')"
                            :class="{ 'bg-red-50 text-red-700 font-semibold ring-1 ring-red-100': tab === 'danger', 'text-red-600 hover:bg-red-50 font-medium': tab !== 'danger' }"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all whitespace-nowrap text-left mt-0 md:mt-4"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            Danger Zone
                        </button>
                    </nav>
                </div>

                <div class="flex-1">

                    <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div x-show="tab === 'orders'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.order-history')
                    </div>

                    <div x-show="tab === 'wallet'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.user-wallet-form')
                    </div>

                    <div x-show="tab === 'ticket'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.ticket')
                    </div>

                    <div x-show="tab === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div x-show="tab === 'danger'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
