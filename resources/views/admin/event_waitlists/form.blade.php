<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="eventWaitlistForm"
            action="{{ isset($eventWaitlist) ? route('admin.event_waitlists.update', encrypt($eventWaitlist->id)) : route('admin.event_waitlists.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($eventWaitlist))
                @method('PUT')
            @endif
            
            <div class="row">
                <!-- Basic Info -->
                <div class="col-12 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Waitlist Information</h5>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="event_id">Event <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="event_id" id="event_id">
                            <option value="">Select Event</option>
                            @foreach ($events as $e)
                                <option value="{{ $e->id }}" {{ old('event_id', $eventWaitlist->event_id ?? '') == $e->id ? 'selected' : '' }}>{{ $e->title }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Waitlist Email"
                                value="{{ old('email', $eventWaitlist->email ?? '') }}">
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Notification -->
                <div class="col-12 mt-4 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Notification Status</h5>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="notified_at">Notified At (Optional)</label>
                        <input type="datetime-local" class="form-control" name="notified_at" id="notified_at"
                            value="{{ old('notified_at', isset($eventWaitlist) && $eventWaitlist->notified_at ? \Carbon\Carbon::parse($eventWaitlist->notified_at)->format('Y-m-d\TH:i') : '') }}">
                        <small class="text-muted">Leave empty if user is not yet notified.</small>
                        @error('notified_at')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
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

                // Validation
                $('#eventWaitlistForm').validate({
                    rules: {
                        event_id: "required",
                        email: {
                            required: true,
                            email: true
                        }
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
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    @endpush
</x-master-layout>
