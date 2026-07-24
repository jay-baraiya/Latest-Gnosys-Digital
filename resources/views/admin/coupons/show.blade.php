<x-master-layout>
    <x-form-wrapper action="View Coupon">
        <form id="couponForm">



            <h5 class="mb-3">BASIC INFORMATION</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="code">Coupon Code <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control text-uppercase" name="code" id="code" placeholder="e.g. WELCOME20"
                                value="{{ old('code', $coupon->code ?? '') }}" style="text-transform: uppercase;">
                        </div>
                        <small class="text-muted">Uppercase letters, numbers only.</small>
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
                        <select disabled class="form-select select2" name="type" id="type">
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
                            <input disabled type="number" step="0.01" min="0" class="form-control" name="value" id="value" placeholder="20"
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
                            <input disabled type="number" step="0.01" min="0" class="form-control" name="max_discount_amount" id="max_discount_amount" placeholder="100.00"
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
                                <input disabled class="form-check-input" type="radio" name="applies_to" id="applies_both" value="both" {{ old('applies_to', $coupon->applies_to ?? 'both') == 'both' ? 'checked' : '' }}>
                                <label class="form-check-label" for="applies_both">Both (Credits & Events)</label>
                            </div>
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="applies_to" id="applies_credits" value="credits" {{ old('applies_to', $coupon->applies_to ?? '') == 'credits' ? 'checked' : '' }}>
                                <label class="form-check-label" for="applies_credits">Credits Only (Service Purchases)</label>
                            </div>
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="applies_to" id="applies_events" value="events" {{ old('applies_to', $coupon->applies_to ?? '') == 'events' ? 'checked' : '' }}>
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
                        <label class="form-label" for="service_ids">Specific Services</label>
                        <select disabled class="form-select select2" name="service_ids[]" id="service_ids" multiple data-placeholder="Leave empty for all">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" 
                                    @if(is_array(old('service_ids', $coupon->service_ids ?? [])) && in_array($service->id, old('service_ids', $coupon->service_ids ?? []))) selected @endif>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_ids')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="event_ids">Specific Events</label>
                        <select disabled class="form-select select2" name="event_ids[]" id="event_ids" multiple data-placeholder="Leave empty for all">
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
                            <input disabled type="number" step="0.01" min="0" class="form-control" name="min_purchase_amount" id="min_purchase_amount" placeholder="0.00"
                                value="{{ old('min_purchase_amount', $coupon->min_purchase_amount ?? '0.00') }}">
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
                            <input disabled type="number" min="0" class="form-control" name="usage_limit" id="usage_limit" placeholder="500"
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
                            <input disabled type="number" min="0" class="form-control" name="usage_per_user" id="usage_per_user" placeholder="1"
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
                            <input disabled type="datetime-local" class="form-control date-time-picker" name="starts_at" id="starts_at"
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
                            <input disabled type="datetime-local" class="form-control date-time-picker" name="expires_at" id="expires_at"
                                value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        @error('expires_at')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input disabled class="form-check-input" type="checkbox" name="never_expire" id="never_expire" value="1" {{ (empty($coupon->starts_at) && empty($coupon->expires_at) && isset($coupon)) ? 'checked' : '' }}>
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
                            <input disabled class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ old('status', $coupon->status ?? 1) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_active">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input disabled class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ old('status', $coupon->status ?? 1) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_inactive">Inactive</label>
                        </div>
                        @error('status')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">Back</a>
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
        });
    </script>
    @endpush
</x-master-layout>
