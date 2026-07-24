<x-master-layout>
    <x-form-wrapper action="{{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }}">
        <form id="couponForm"
            action="{{ isset($coupon) ? route('admin.coupons.update', encrypt($coupon->id)) : route('admin.coupons.store') }}"
            method="post"
            enctype="multipart/form-data">

            @csrf
            @if (isset($coupon))
                @method('PUT')
            @endif

            <h5 class="mb-3">BASIC INFORMATION</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="code">Coupon Code <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control text-uppercase" name="code" id="code" placeholder="e.g. WELCOME20"
                                value="{{ old('code', $coupon->code ?? '') }}" style="text-transform: uppercase;">
                        </div>
                        {{-- <small class="text-muted">Uppercase letters, numbers only.</small> --}}
                        @error('code')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="type">Discount Type <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="type" id="type">
                            <option value="percentage" {{ old('type', $coupon->type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                        @error('type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="value">Discount Value <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="number" step="0.01" min="0" class="form-control" name="value" id="value" placeholder="20"
                                value="{{ old('value', $coupon->value ?? '') }}">
                        </div>
                        @error('value')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="max_discount_amount">Max Discount (Cap)</label>
                        <div class="input-group mb-1">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" name="max_discount_amount" id="max_discount_amount" placeholder="100.00"
                                value="{{ old('max_discount_amount', $coupon->max_discount_amount ?? '') }}">
                        </div>
                        <small class="text-muted">For percentage coupons.</small>
                        @error('max_discount_amount')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">APPLIES TO</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Coupon Applicability <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="applies_to" id="applies_both" value="both" {{ old('applies_to', $coupon->applies_to ?? 'both') == 'both' ? 'checked' : '' }}>
                                <label class="form-check-label" for="applies_both">Both (Credits & Events)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="applies_to" id="applies_credits" value="credits" {{ old('applies_to', $coupon->applies_to ?? '') == 'credits' ? 'checked' : '' }}>
                                <label class="form-check-label" for="applies_credits">Credits Only (Service Purchases)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="applies_to" id="applies_events" value="events" {{ old('applies_to', $coupon->applies_to ?? '') == 'events' ? 'checked' : '' }}>
                                <label class="form-check-label" for="applies_events">Events Only</label>
                            </div>
                        </div>
                        @error('applies_to')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="service_ids">Specific Services</label>
                            <a href="{{ route('admin.digital.services.create') }}" target="_blank" class="btn btn-sm btn-link text-decoration-none p-0">
                                <i class="ti ti-plus"></i> Add New
                            </a>
                        </div>
                        <select class="form-select select2-ajax-services" name="service_ids[]" id="service_ids" multiple="multiple" data-placeholder="Leave empty for all">
                            @if(isset($coupon) && !empty($coupon->service_ids))
                                @foreach ($services as $service)
                                    @if(in_array($service->id, $coupon->service_ids))
                                        <option value="{{ $service->id }}" selected>{{ $service->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('service_ids')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="event_ids">Specific Events</label>
                        <select class="form-select select2" name="event_ids[]" id="event_ids" multiple data-placeholder="Leave empty for all">
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" 
                                    @if(is_array(old('event_ids', $coupon->event_ids ?? [])) && in_array($event->id, old('event_ids', $coupon->event_ids ?? []))) selected @endif>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_ids')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">PURCHASE REQUIREMENTS</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="min_purchase_amount">Minimum Purchase Amount</label>
                        <div class="input-group mb-1">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" name="min_purchase_amount" id="min_purchase_amount" placeholder="0.00"
                                value="{{ old('min_purchase_amount', $coupon->min_purchase_amount ?? '') }}">
                        </div>
                        <small class="text-muted">Set 0 for no minimum requirement.</small>
                        @error('min_purchase_amount')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">USAGE LIMITS</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="usage_limit">Total Usage Limit</label>
                        <div class="input-group mb-1">
                            <input type="number" min="0" class="form-control" name="usage_limit" id="usage_limit" placeholder="500"
                                value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
                        </div>
                        <small class="text-muted">Set empty or 0 for unlimited uses.</small>
                        @error('usage_limit')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="usage_per_user">Per User Limit</label>
                        <div class="input-group mb-1">
                            <input type="number" min="0" class="form-control" name="usage_per_user" id="usage_per_user" placeholder="1"
                                value="{{ old('usage_per_user', $coupon->usage_per_user ?? '1') }}">
                        </div>
                        <small class="text-muted">Set empty or 0 for unlimited uses per user.</small>
                        @error('usage_per_user')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>
            <h5 class="mb-3">VALIDITY PERIOD</h5>
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label" for="starts_at">Starts At</label>
                        <div class="input-group mb-1">
                            <input type="datetime-local" class="form-control date-time-picker" name="starts_at" id="starts_at"
                                value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        @error('starts_at')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label" for="expires_at">Expires At</label>
                        <div class="input-group mb-1">
                            <input type="datetime-local" class="form-control date-time-picker" name="expires_at" id="expires_at"
                                value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        @error('expires_at')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="never_expire" id="never_expire" value="1" {{ (empty($coupon->starts_at) && empty($coupon->expires_at) && isset($coupon)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="never_expire">
                            Never expire
                        </label>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label d-block">Status <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', $coupon->status ?? 1) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status', $coupon->status ?? 1) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_inactive">Inactive</label>
                        </div>
                        @error('status')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">{{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}</button>
            </div>

        </form>
    </x-form-wrapper>

    @push('scripts')
    <script>
        $(document).ready(function() {
            if ($('.select2').length > 0) {
                $('.select2').select2({
                    width: '100%',
                    placeholder: function() {
                        $(this).data('placeholder');
                    }
                });
            }

            if ($('.select2-ajax-services').length > 0) {
                $('.select2-ajax-services').select2({
                    width: '100%',
                    placeholder: "Leave empty for all",
                    allowClear: true,
                    multiple: true,
                    ajax: {
                        url: '{{ route('admin.common.getServices') }}',
                        dataType: 'json',
                        delay: 250,
                        method: 'POST',
                        data: function (params) {
                            return {
                                q: params.term,
                                _token: "{{ csrf_token() }}"
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });
            }

            // Handle never expire checkbox
            $('#never_expire').on('change', function() {
                if($(this).is(':checked')) {
                    $('#starts_at').val('').prop('disabled', true);
                    $('#expires_at').val('').prop('disabled', true);
                } else {
                    $('#starts_at').prop('disabled', false);
                    $('#expires_at').prop('disabled', false);
                }
            });

            // Trigger on load
            if($('#never_expire').is(':checked')) {
                $('#starts_at').prop('disabled', true);
                $('#expires_at').prop('disabled', true);
            }

            // Handle code uppercase
            $('#code').on('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });

            // Validation
            $.validator.addMethod("pattern", function(value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                if (typeof param === "string") {
                    param = new RegExp("^(?:" + param + ")$");
                }
                return param.test(value);
            }, "Invalid format.");

            $('#couponForm').validate({
                ignore: ":hidden:not(.select2-hidden-accessible)",
                rules: {
                    code: {
                        required: true,
                        pattern: /^[A-Z0-9]+$/,
                        remote: {
                            url: "{{ route('admin.coupons.check.code') }}",
                            type: "post",
                            data: {
                                _token: "{{ csrf_token() }}",
                                coupon_id: function() {
                                    return "{{ isset($coupon) ? $coupon->id : '' }}";
                                }
                            }
                        }
                    },
                    type: { required: true },
                    value: { required: true, number: true, min: 0 },
                    applies_to: { required: true },
                    min_purchase_amount: { number: true, min: 0 },
                    max_discount_amount: { number: true, min: 0 },
                    usage_limit: { digits: true, min: 0 },
                    usage_per_user: { digits: true, min: 0 },
                    status: { required: true }
                },
                messages: {
                    code: {
                        required: "Please enter a coupon code.",
                        pattern: "Coupon code can only contain uppercase letters and numbers.",
                        remote: "This coupon code is already in use."
                    },
                    type: { required: "Please select a discount type." },
                    value: { required: "Please enter a discount value." },
                    applies_to: { required: "Please select applicability." },
                    status: { required: "Please select status." }
                },
                errorClass: 'text-danger small mt-1',
                errorElement: 'span',
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
                        error.insertAfter(element.parent().next('.text-muted').length ? element.parent().next('.text-muted') : element.parent());
                    } else if (element.prop('type') === 'radio') {
                        let wrapper = element.closest('.d-flex');
                        if (wrapper.length) {
                            error.insertAfter(wrapper);
                        } else {
                            error.insertAfter(element.closest('.form-check-inline').parent());
                        }
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
    @endpush
</x-master-layout>
