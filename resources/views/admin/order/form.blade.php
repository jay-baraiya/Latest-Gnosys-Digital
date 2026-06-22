<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
    <form id="orderForm"
        action="{{ isset($order) ? route('admin.tasks.update', encrypt($order->id)) : route('admin.tasks.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @if (isset($order))
            @method('PUT')
        @endif

        <div class="row">
            <h5 class="mb-3 text-primary">Order Details</h5>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="order_number">Order Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('order_number') is-invalid @enderror" name="order_number" id="order_number" placeholder="ORD-12345"
                        value="{{ old('order_number', $order->order_number ?? '') }}">
                    @error('order_number')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="date_time">Order Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('date_time') is-invalid @enderror" name="date_time" id="date_time"
                        value="{{ old('date_time', $order->date_time ?? '') }}">
                    @error('date_time')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="user_id">User / Customer</label>
                    <select class="form-select select2 @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                        <option value="">Select User (Leave blank for guest)</option>
                        @if (isset($users))
                            @foreach ($users as $userOption)
                                <option value="{{ $userOption->id }}"
                                    {{ old('user_id', $order->user_id ?? '') == $userOption->id ? 'selected' : '' }}>
                                    {{ $userOption->name }} ({{ $userOption->email }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('user_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="subtotal">Sub Total <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control @error('subtotal') is-invalid @enderror" name="subtotal" id="subtotal" placeholder="0.00"
                            value="{{ old('subtotal', $order->subtotal ?? '') }}">
                    </div>
                    @error('subtotal')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="total_amount">Order Total <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" name="total_amount" id="total_amount" placeholder="0.00"
                            value="{{ old('total_amount', $order->total_amount ?? '') }}">
                    </div>
                    @error('total_amount')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr class="my-3">

            <h5 class="mb-3 text-primary">Billing Details</h5>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="billing_first_name">Billing First Name</label>
                    <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" name="billing_first_name" id="billing_first_name" placeholder="John Doe"
                        value="{{ old('billing_first_name', $order->billing_first_name ?? '') }}">
                    @error('billing_first_name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="billing_phone">Billing Phone</label>
                    <input type="text" class="form-control @error('billing_phone') is-invalid @enderror" name="billing_phone" id="billing_phone" placeholder="+1 234 567 8900"
                        value="{{ old('billing_phone', $order->billing_phone ?? '') }}">
                    @error('billing_phone')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="billing_country">Billing Country</label>
                    <input type="text" class="form-control @error('billing_country') is-invalid @enderror" name="billing_country" id="billing_country" placeholder="Country"
                        value="{{ old('billing_country', $order->billing_country ?? '') }}">
                    @error('billing_country')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="billing_state">Billing State</label>
                    <input type="text" class="form-control @error('billing_state') is-invalid @enderror" name="billing_state" id="billing_state" placeholder="State/Province"
                        value="{{ old('billing_state', $order->billing_state ?? '') }}">
                    @error('billing_state')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="billing_city">Billing City</label>
                    <input type="text" class="form-control @error('billing_city') is-invalid @enderror" name="billing_city" id="billing_city" placeholder="City"
                        value="{{ old('billing_city', $order->billing_city ?? '') }}">
                    @error('billing_city')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr class="my-3">

            <h5 class="mb-3 text-primary">Status & Notes</h5>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="status">Order Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                        <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ old('status', $order->status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ old('status', $order->status ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status', $order->status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status', $order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="payment_method">Payment Method</label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" id="payment_method">
                        <option value="">Select Method</option>
                        <option value="stripe" {{ old('payment_method', $order->payment_method ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="paypal" {{ old('payment_method', $order->payment_method ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="cod" {{ old('payment_method', $order->payment_method ?? '') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                    </select>
                    @error('payment_method')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="payment_status">Payment Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('payment_status') is-invalid @enderror" name="payment_status" id="payment_status">
                        <option value="pending" {{ old('payment_status', $order->payment_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('payment_status', $order->payment_status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ old('payment_status', $order->payment_status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ old('payment_status', $order->payment_status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="success" {{ old('payment_status', $order->payment_status ?? '') == 'success' ? 'selected' : '' }}>Success</option>
                    </select>
                    @error('payment_status')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="order_notes">Order Note</label>
                    <textarea class="form-control @error('order_notes') is-invalid @enderror" name="order_notes" id="order_notes" rows="3" placeholder="Special instructions or notes...">{{ old('order_notes', $order->order_notes ?? '') }}</textarea>
                    @error('order_notes')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route($moduleUrl ?? 'admin.orders.index') }}" class="btn btn-soft-light">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Order</button>
        </div>
    </form>
</x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                function isImageRequired() {
                    let hasExistingImage = $('#currentImageContainer').length > 0 && !$('#currentImageContainer').hasClass('d-none');
                    let markedForRemoval = $('#remove_existing_image').val() === "1";

                    return !hasExistingImage || markedForRemoval;
                }

                let isEdit = @json(isset($user));

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param);
                }, 'File size must be less than 1 MB.');

                $('#userForm').validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },
                        email: {
                            required: true,
                            email: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.users.check.email') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $("#email").val();
                                    },
                                    user_id: function() {
                                        return '{{ isset($user) ? $user->id : '' }}';
                                    },
                                }
                            }
                        },
                        password: {
                            required: !isEdit,
                            minlength: 8
                        },
                        password_confirmation: {
                            required: !isEdit,
                            equalTo: "#password"
                        },
                        phone: {
                            required: true,
                            maxlength: 15,
                            digits: true,
                            remote: {
                                url: "{{ route('admin.users.check.phone') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $("#phone").val();
                                    },
                                    user_id: function() {
                                        return '{{ isset($user) ? $user->id : '' }}';
                                    },
                                }
                            }
                        },
                        zip: {
                            maxlength: 6,
                            digits: true
                        },
                        country_id: {
                            required: true
                        },
                        state_id: {
                            required: true
                        },
                        city_id: {
                            required: true
                        },
                        role_id: {
                            required: true
                        },
                        designation_id : {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        image: {
                            required: function(element) {
                                return /*isImageRequired()*/ false;
                            },
                            extension: "jpg|jpeg|png|webp",
                            filesize: 1048576
                        },
                    },
                    messages: {
                        name: {
                            required: "Please enter a name."
                        },
                        email: {
                            required: "Please enter a valid email.",
                            email: "Enter a valid email structure.",
                            remote: "This email is already registered."
                        },
                        password: {
                            required: "Please provide a password.",
                            minlength: "Minimum 8 characters."
                        },
                        password_confirmation: {
                            required: "Please confirm password.",
                            equalTo: "Passwords do not match."
                        },
                        role_id: {
                            required: "Please select a role."
                        },
                        designation_id: {
                            required: "Please select a designation."
                        },
                        image: {
                            extension: "Only JPG, JPEG, PNG and WEBP files are allowed.",
                            filesize: "File size must not exceed 1 MB."
                        },
                        phone: {
                            remote: "This phone number is already in use."
                        }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible)",
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                $('#designation_id').select2({
                    placeholder: 'Select a designation',
                    allowClear: true,
                });

                $('#role_id').select2({
                    placeholder: 'Select a role',
                    allowClear: true,
                });

                async function loadEditData() {
                    const countryId = "{{ isset($user->country_id) ? $user->country_id : '' }}";
                    const countryName = "{{ isset($user->country->name) ? $user->country->name : '' }}";

                    const stateId = "{{ isset($user->state_id) ? $user->state_id : '' }}";
                    const stateName = "{{ isset($user->state->name) ? $user->state->name : '' }}";

                    const cityId = "{{ isset($user->city_id) ? $user->city_id : '' }}";
                    const cityName = "{{ isset($user->city->name) ? $user->city->name : '' }}";

                    $('#state_id').prop('disabled', true);
                    $('#city_id').prop('disabled', true);

                    await setSelect2Value('#country_id', countryId, countryName);

                    $('#state_id').prop('disabled', false);

                    await setSelect2Value('#state_id', stateId, stateName);

                    $('#city_id').prop('disabled', false);

                    await setSelect2Value('#city_id', cityId, cityName);
                }

                loadEditData();

            });

            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        showPreview(e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else {
                    clearPreview();
                }
            });

            $('#imagePreview').on('error', function() {
                $(this).attr('src', 'https://placehold.co/600x400?text=Invalid+Image+URL');
            });

            $('#clearPreviewBtn').on('click', function() {
                if (isUrlMode) {
                    $('#image_url').val('');
                } else {
                    $('#image').val('');
                }
                clearPreview();
            });

            $('#removeExistingImageBtn').on('click', function() {
                $('#currentImageContainer').addClass('d-none');
                $('#remove_existing_image').val('1');
            });

            function showPreview(src) {
                $('#imagePreview').attr('src', src);
                $('#imagePreviewContainer').removeClass('d-none');
                $('#currentImageContainer').addClass('d-none');
            }

            function clearPreview() {
                $('#imagePreview').attr('src', '');
                $('#imagePreviewContainer').addClass('d-none');
                if ($('#remove_existing_image').val() !== '1') {
                    $('#currentImageContainer').removeClass('d-none');
                }
            }
        </script>
    @endpush

</x-master-layout>
