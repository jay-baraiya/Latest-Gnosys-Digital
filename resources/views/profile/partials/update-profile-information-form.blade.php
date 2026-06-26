<section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
    <header class="mb-6 border-b border-slate-100 pb-5">
        <h2 class="text-xl font-semibold text-slate-900">{{ __('Profile Information') }}</h2>
        <p class="mt-1 text-sm text-slate-500">{{ __("Update your account's profile information and address details.") }}</p>
    </header>

    <form method="post" action="{{ route('profile.update.info') }}" class="space-y-6">
        @csrf
        @method('post')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Name') }} <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 ring-1 ring-red-500 @enderror" />
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Email') }} <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('email') border-red-500 ring-1 ring-red-500 @enderror" />
                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Phone') }} <span class="text-red-500">*</span></label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', auth()->user()->phone) }}" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('phone') border-red-500 ring-1 ring-red-500 @enderror" />
                @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="zip" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Zip Code') }}</label>
                <input id="zip" name="zip" type="text" value="{{ old('zip', auth()->user()->zip) }}" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('zip') border-red-500 ring-1 ring-red-500 @enderror" />
                @error('zip')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="country_id" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Country') }} <span class="text-red-500">*</span></label>
                <select id="country_id" name="country_id" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white @error('country_id') border-red-500 ring-1 ring-red-500 @enderror">
                    <option value="">{{ __('Select Country') }}</option>
                    @foreach($countries as $country)
                    <option value="{{ $country->id }}" @selected(old('country_id', auth()->user()->country_id) == $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="state_id" class="block text-sm font-medium text-slate-700 mb-1">{{ __('State') }} <span class="text-red-500">*</span></label>
                <select id="state_id" name="state_id" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white @error('state_id') border-red-500 ring-1 ring-red-500 @enderror">
                    <option value="">{{ __('Select State') }}</option>
                </select>
                @error('state_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="city_id" class="block text-sm font-medium text-slate-700 mb-1">{{ __('City') }} <span class="text-red-500">*</span></label>
                <input id="city_id" name="city_id" type="text" value="{{ old('city_id', auth()->user()->city_id ?? '') }}" placeholder="{{ __('Enter your city') }}" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white @error('city') border-red-500 ring-1 ring-red-500 @enderror" />
                @error('city_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Address') }}</label>
                <textarea id="address" name="address" rows="3" class="block w-full rounded-lg border-slate-300 py-2.5 px-3 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('address') border-red-500 ring-1 ring-red-500 @enderror">{{ old('address', auth()->user()->address) }}</textarea>
                @error('address')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 py-2.5 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">{{ __('Save Information') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" x-init="setTimeout(() => show = false, 3000)" class="text-sm font-medium text-emerald-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

@push('script')
    <script>
    $(document).ready(function() {
        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            var stateDropdown = $('#state_id');

            stateDropdown.empty().append('<option value="">Loading...</option>');

            if (countryId) {
                $.ajax({
                    url: '/account/get-states/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        stateDropdown.empty();
                        stateDropdown.append('<option value="">{{ __("Select State") }}</option>');

                        $.each(data, function(key, state) {
                            stateDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    },
                    error: function() {
                        stateDropdown.empty();
                        stateDropdown.append('<option value="">Error loading states</option>');
                    }
                });
            } else {
                stateDropdown.empty().append('<option value="">{{ __("Select State") }}</option>');
            }
        });

        var oldCountry = '{{ old('country_id', auth()->user()->country_id ?? '') }}';
        var oldState = '{{ old('state_id', auth()->user()->state_id ?? '') }}';

        if (oldCountry) {
            $('#country_id').val(oldCountry).trigger('change');

            $(document).ajaxStop(function() {
                if (oldState) {
                    $('#state_id').val(oldState);
                }
            });
        }
    });
</script>
@endpush
