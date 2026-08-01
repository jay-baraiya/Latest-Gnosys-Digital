<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="eventRegistrationForm"
            action="{{ isset($eventRegistration) ? route('admin.event_registrations.update', encrypt($eventRegistration->id)) : route('admin.event_registrations.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($eventRegistration))
                @method('PUT')
            @endif
            
            <div class="row">
                <!-- Basic Info -->
                <div class="col-12 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Registration Information</h5>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="event_id">Event <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="event_id" id="event_id">
                            <option value="">Select Event</option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}" {{ old('event_id', $eventRegistration->event_id ?? '') == $e->id ? 'selected' : '' }}>{{ $e->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">User</label>
                        <select class="form-select select2" name="user_id" id="user_id">
                            <option value="">Select User</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" data-email="{{ $u->email }}" {{ old('user_id', $eventRegistration->user_id ?? '') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Registrant Email"
                                value="{{ old('email', $eventRegistration->email ?? '') }}">
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 mt-4 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Registration Status</h5>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label d-block">Payment Status <span class="text-danger">*</span></label>
                        
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="ps-pending"
                                    value="pending" @if (old('payment_status', $eventRegistration->payment_status ?? 'pending') == 'pending') checked @endif>
                                <label class="form-check-label" for="ps-pending">Pending</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="ps-paid"
                                    value="paid" @if (old('payment_status', $eventRegistration->payment_status ?? '') == 'paid') checked @endif>
                                <label class="form-check-label" for="ps-paid">Paid</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="ps-failed"
                                    value="failed" @if (old('payment_status', $eventRegistration->payment_status ?? '') == 'failed') checked @endif>
                                <label class="form-check-label" for="ps-failed">Failed</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="ps-refunded"
                                    value="refunded" @if (old('payment_status', $eventRegistration->payment_status ?? '') == 'refunded') checked @endif>
                                <label class="form-check-label" for="ps-refunded">Refunded</label>
                            </div>
                        </div>
                        @error('payment_status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label d-block">Attendee Status <span class="text-danger">*</span></label>
                        
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attendee_status" id="as-registered"
                                    value="registered" @if (old('attendee_status', $eventRegistration->attendee_status ?? 'registered') == 'registered') checked @endif>
                                <label class="form-check-label" for="as-registered">Registered</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attendee_status" id="as-checked_in"
                                    value="checked_in" @if (old('attendee_status', $eventRegistration->attendee_status ?? '') == 'checked_in') checked @endif>
                                <label class="form-check-label" for="as-checked_in">Checked In</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attendee_status" id="as-no_show"
                                    value="no_show" @if (old('attendee_status', $eventRegistration->attendee_status ?? '') == 'no_show') checked @endif>
                                <label class="form-check-label" for="as-no_show">No Show</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attendee_status" id="as-cancelled"
                                    value="cancelled" @if (old('attendee_status', $eventRegistration->attendee_status ?? '') == 'cancelled') checked @endif>
                                <label class="form-check-label" for="as-cancelled">Cancelled</label>
                            </div>
                        </div>
                        @error('attendee_status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {
                
                // Initialize Select2
                $('.select2').select2({
                    width: '100%',
                    placeholder: "Select an option"
                });

                // Auto-fill email when user is selected
                $('#user_id').on('change', function() {
                    let selectedOption = $(this).find('option:selected');
                    let userEmail = selectedOption.data('email');
                    
                    if (userEmail) {
                        $('#email').val(userEmail);
                        // Trigger validation update if it's already active
                        $('#email').valid();
                    }
                });

                // Validation
                $('#eventRegistrationForm').validate({
                    rules: {
                        event_id: "required",
                        email: {
                            required: true,
                            email: true
                        },
                        payment_status: "required",
                        attendee_status: "required"
                    },
                    messages: {
                        event_id: "Please select an event",
                        email: "Please enter a valid email address"
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
            });
        </script>
    @endpush
</x-master-layout>
