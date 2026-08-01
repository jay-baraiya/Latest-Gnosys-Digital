<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'View' }}">
        <div class="row">
            <!-- Basic Info -->
            <div class="col-12 mb-3">
                <h5 class="text-primary border-bottom pb-2">Registration Information</h5>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="event">Event</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="event" id="event"
                            value="{{ $eventRegistration->event ? $eventRegistration->event->title : 'N/A' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="user">User</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="user" id="user"
                            value="{{ $eventRegistration->user ? $eventRegistration->user->name : 'N/A' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group mb-1">
                        <input disabled type="email" class="form-control" name="email" id="email"
                            value="{{ $eventRegistration->email ?? 'N/A' }}">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-12 mt-4 mb-3">
                <h5 class="text-primary border-bottom pb-2">Registration Status</h5>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label d-block">Payment Status</label>
                    <input disabled type="text" class="form-control w-50" value="{{ ucfirst($eventRegistration->payment_status ?? 'Pending') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label d-block">Attendee Status</label>
                    <input disabled type="text" class="form-control w-50" value="{{ ucfirst(str_replace('_', ' ', $eventRegistration->attendee_status ?? 'Registered')) }}">
                </div>
            </div>

            @if(isset($eventRegistration->order))
            <div class="col-md-6">
                <div class="mb-3 mt-3">
                    <label class="form-label d-block">Order ID</label>
                    <input disabled type="text" class="form-control w-50" value="{{ $eventRegistration->order->order_number ?? $eventRegistration->order_id }}">
                </div>
            </div>
            @endif
        </div>

        <div class="text-end mt-4">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Back</a>
        </div>
    </x-form-wrapper>
</x-master-layout>
