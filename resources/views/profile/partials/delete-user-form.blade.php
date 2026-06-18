<section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8" x-data="{ showModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-slate-900">{{ __('Delete Account') }}</h2>
        <div class="mt-3 p-4 bg-red-50 border border-red-100 rounded-xl">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <p class="text-sm text-red-800 leading-relaxed">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
            </div>
        </div>
    </header>

    <button type="button" x-on:click.prevent="showModal = true" class="inline-flex justify-center items-center gap-2 rounded-lg bg-red-600 py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
        {{ __('Delete Account') }}
    </button>

    <div x-cloak>
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"></div>
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="showModal = false" @keydown.escape.window="showModal = false" class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden w-full max-w-lg transform transition-all">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
                    @csrf @method('delete')
                    <h2 class="text-xl font-semibold text-slate-900 mb-2">{{ __('Are you sure you want to delete your account?') }}</h2>
                    <p class="text-sm text-slate-500 mb-6">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
                    <div>
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @if($errors->userDeletion->has('password')) border-red-500 ring-1 ring-red-500 @endif" placeholder="{{ __('Password') }}" @keyup.enter="$event.target.form.submit()" />
                        @if($errors->userDeletion->has('password'))<p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>@endif
                    </div>
                    <div class="mt-8 flex items-center justify-end gap-3">
                        <button type="button" x-on:click="showModal = false" class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">{{ __('Cancel') }}</button>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-colors">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
