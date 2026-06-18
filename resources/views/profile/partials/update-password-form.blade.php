<section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 mt-8">
    <header class="mb-6 border-b border-slate-100 pb-5">
        <h2 class="text-xl font-semibold text-slate-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-slate-700 mb-1">
                {{ __('Current Password') }}
            </label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-brand focus:ring-brand sm:text-sm @if($errors->updatePassword->has('current_password')) border-red-500 ring-1 ring-red-500 @endif"
                autocomplete="current-password"
            />
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-slate-700 mb-1">
                {{ __('New Password') }}
            </label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-brand focus:ring-brand sm:text-sm @if($errors->updatePassword->has('password')) border-red-500 ring-1 ring-red-500 @endif"
                autocomplete="new-password"
            />
            @if($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                {{ __('Confirm Password') }}
            </label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-brand focus:ring-brand sm:text-sm @if($errors->updatePassword->has('password_confirmation')) border-red-500 ring-1 ring-red-500 @endif"
                autocomplete="new-password"
            />
            @if($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button
                type="submit"
                class="inline-flex justify-center rounded-lg bg-blue-600 py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors"
            >
                {{ __('Save Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Password updated.') }}
                </p>
            @endif
        </div>
    </form>
</section>
