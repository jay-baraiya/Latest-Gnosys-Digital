<x-app-layout>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
            <p class="text-gray-500 text-base">Review your order and complete the transaction securely.</p>
        </div>

        <!-- Checkout Form -->
        <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- Contact Information -->
                    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                                    <path d="M3 7l9 6l9 -6"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Contact Information</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-600">Full Name <span class="text-red-500">*</span> </label>
                                <input name="first_name" id="full_name" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none" placeholder="Full Name" type="text" value="{{ Auth::user()->name }}" />
                                @error('first_name')
                                    <span class="text-red-500" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-600">Email Address <span class="text-red-500">*</span> </label>
                                <input name="email" id="email" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none" placeholder="example@gmail.com" type="email" value="{{ Auth::user()->email }}" />
                                @error('email')
                                    <span class="text-red-500" >{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <!-- Billing Address -->
                    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                    <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Billing Address</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Street Address -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium text-gray-600">Street Address <span class="text-red-500">*</span></label>
                                <input name="billing_address" id="address" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none" placeholder="123 Innovation Drive" type="text" value="{{ $address?->billing_address ?? '' }}" />
                                @error('billing_address')
                                    <span class="text-red-500" >{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Country, State, and City Row -->
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Country Dropdown -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-600">Country <span class="text-red-500">*</span></label>
                                    <select name="billing_country" id="country" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.2em_1.2em] bg-[right_0.75rem_center] bg-no-repeat">
                                        <option value="">Select Country</option>
                                        @foreach($countrys as $country)
                                            <option value="{{ $country->id }}" {{ $address?->billing_country == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('billing_country')
                                        <span class="text-red-500" >{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- State Input -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-600">State / Province <span class="text-red-500">*</span></label>
                                    <select name="billing_state" id="state" data-selected="{{ $address?->billing_state }}" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.2em_1.2em] bg-[right_0.75rem_center] bg-no-repeat">
                                        <option value="">Select State</option>
                                        </select>
                                    @error('billing_state')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- City Input -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-600">City <span class="text-red-500">*</span></label>
                                    <input name="billing_city" id="city" class="w-full h-12 px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all outline-none" placeholder="City" type="text" value="{{ $address?->billing_city }}" />
                                    @error('billing_city')
                                        <span class="text-red-500" >{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Payment Method -->
                    <section class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                                    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Payment Method</h2>
                        </div>
                        <div class="space-y-4">
                            <div class="border-2 border-blue-600 rounded-xl p-6 bg-blue-50/30">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-600" name="payment_method" value="paypal" type="radio" checked />
                                    <span class="text-base font-medium text-gray-900">PayPal</span>
                                    <div class="ml-auto text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 13l2.5 0c2.5 0 5 -1.7 5 -5c0 -3.6 -2.5 -5 -5 -5h-5.5c-.5 0 -1 .5 -1 1l-2 14c0 .5 .5 1 1 1h2.8l1.2 -5c.1 -.6 .4 -1 1 -1zm7.5 -5.8c1.7 1 2.5 2.8 2.5 4.8c0 2.5 -2.5 4.5 -5 4.5h-2.6l-.6 3.6a1 1 0 0 1 -1 .8l-2.7 0a.5 .5 0 0 1 -.5 -.6l.2 -1.4"></path>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Column: Order Summary -->
                <aside class="lg:col-span-4 sticky top-10 space-y-6">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h3>

                        <div class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2">
                            <!-- Cart Foreach Loop -->
                            @forelse($carts as $item)

                            <input type="hidden" name="order_product_id[]" value="{{ encrypt($item->product_id) }}">

                            <input type="hidden" name="order_product_variant_id[]" value="{{ !empty($item->variant_id) ? encrypt ($item->variant_id) : '' }}">
                            <input type="hidden" name="order_product_variant_name[]" value="{{ $item->variant_name }}">

                            <input type="hidden" name="order_product_type[]" value="{{ $item->product_type }}">
                            <input type="hidden" name="order_product_title[]" value="{{ $item->product_title }}">
                            <input type="hidden" name="order_product_price[]" value="{{ $item->product_price }}">
                            <input type="hidden" name="order_product_qty[]" value="{{ $item->product_qty }}">
                            <input type="hidden" name="order_product_total_amount[]" value="{{ $item->total_amount }}">

                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img alt="Product image" class="w-full h-full object-cover" src="{{ asset($item->product_img) }}" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $item->product_title }}</h4>
                                    <p class="text-gray-500 text-xs mt-0.5">
                                        Qty: {{ $item->product_qty }}
                                        @if(isset($item->product_type)) | {{ $item->product_type }} @endif
                                        @if(isset($item->variant_name)) | {{ $item->variant_name }} @endif
                                    </p>
                                    <p class="text-blue-600 font-bold mt-1">${{ number_format($item->total_amount, 2) }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">Your cart is empty.</p>
                            @endforelse
                        </div>

                        @php
                            $taxAmount = $grandTotal * 0;
                            $finalTotal = $grandTotal + $taxAmount;
                        @endphp

                        <input type="hidden" name="order_product_grand_total" value="{{ $finalTotal }}">

                        <div class="border-t border-gray-200 pt-6 space-y-3 mb-8">
                            <div class="flex justify-between text-gray-600">
                                <span class="text-base">Subtotal</span>
                                <span class="text-base font-medium">${{ number_format($grandTotal, 2) }}</span>
                            </div>
                            {{-- <div class="flex justify-between text-gray-600">
                                <span class="text-base">Estimated Tax (8%)</span>
                                <span class="text-base font-medium">${{ number_format($taxAmount, 2) }}</span>
                            </div> --}}
                            <div class="flex justify-between text-gray-900 font-bold text-xl pt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span>${{ number_format($finalTotal, 2) }}</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 flex flex-col gap-3 mb-8 border border-gray-100">
                            <div class="flex items-center gap-3 text-xs font-medium text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 flex-shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -1.116 9.376"></path>
                                    <path d="M15 19l2 2l4 -4"></path>
                                </svg>
                                SSL Encrypted & 256-bit Secure
                            </div>
                            <div class="flex items-center gap-3 text-xs font-medium text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 flex-shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11"></path>
                                </svg>
                                Instant Digital Asset Access
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-full font-semibold text-lg hover:bg-blue-700 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-blue-600/30">
                            Complete Purchase
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4">By clicking complete purchase, you agree to our Terms of Service.</p>
                    </div>
                </aside>
            </div>
        </form>
    </section>

    @section('script')
        <script>
            $(document).ready(function() {

                // Function to fetch and load states
                function loadStates(countryId, selectedStateId = null) {
                    let stateSelect = $('#state');
                    stateSelect.html('<option value="">Loading...</option>'); // Show loading text

                    if (countryId) {
                        // Use a placeholder in the route and replace it in JS
                        let url = '{{ route("profile.get.states", ":country_id") }}';
                        url = url.replace(':country_id', countryId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function(response) {
                                stateSelect.html('<option value="">Select State</option>');

                                // Loop through the returned states and append to select box
                                $.each(response, function(index, state) {
                                    // Check if this state should be pre-selected (for Edit page)
                                    let isSelected = (state.id == selectedStateId) ? 'selected' : '';
                                    stateSelect.append('<option value="'+ state.id +'" '+ isSelected +'>'+ state.name +'</option>');
                                });
                            },
                            error: function(xhr) {
                                stateSelect.html('<option value="">Error loading states</option>');
                                console.error('Failed to fetch states');
                            }
                        });
                    } else {
                        stateSelect.html('<option value="">Select State</option>');
                    }
                }

                // 1. Listen for country changes
                $('#country').on('change', function() {
                    let countryId = $(this).val();
                    loadStates(countryId);
                });

                // 2. Trigger on page load (For Edit Mode / Validation Fails)
                let initialCountryId = $('#country').val();
                let initialStateId = $('#state').data('selected'); // Gets the $address->billing_state value

                if (initialCountryId) {
                    loadStates(initialCountryId, initialStateId);
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                // Form Validation Initialize
                $("#checkoutForm").validate({
                    rules: {
                        full_name: {
                            required: true,
                            minlength: 2
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        address: {
                            required: true
                        },
                        country: {
                            required: true
                        },
                        state: {
                            required: true
                        },
                        city: {
                            required: true
                        }
                    },
                    messages: {
                        full_name: {
                            required: "Please enter your full name",
                            minlength: "Name must be at least 2 characters long"
                        },
                        email: {
                            required: "Please enter your email address",
                            email: "Please enter a valid email address"
                        },
                        address: "Please enter your street address",
                        country: "Please select your country",
                        state: "Please enter your state/province",
                        city: "Please enter your city"
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('text-red-500 text-xs font-medium mt-1 block');
                        element.closest('.space-y-2').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('border-red-500 focus:border-red-500 focus:ring-red-500').removeClass('border-gray-300 focus:border-blue-500 focus:ring-blue-500');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('border-red-500 focus:border-red-500 focus:ring-red-500').addClass('border-gray-300 focus:border-blue-500 focus:ring-blue-500');
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
