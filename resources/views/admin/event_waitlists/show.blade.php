<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'View' }}">
        <div class="row">
            <!-- Basic Info -->
            <div class="col-12 mb-3">
                <h5 class="text-primary border-bottom pb-2">Waitlist Information</h5>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="event">Event</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="event" id="event"
                            value="{{ $eventWaitlist->event ? $eventWaitlist->event->title : 'N/A' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group mb-1">
                        <input disabled type="email" class="form-control" name="email" id="email"
                            value="{{ $eventWaitlist->email ?? 'N/A' }}">
                    </div>
                </div>
            </div>

            <!-- Date Info -->
            <div class="col-12 mt-4 mb-3">
                <h5 class="text-primary border-bottom pb-2">Timeline</h5>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label d-block">Added At</label>
                    <input disabled type="text" class="form-control w-75" value="{{ $eventWaitlist->created_at ? \Carbon\Carbon::parse($eventWaitlist->created_at)->format('d M Y, h:i A') : 'N/A' }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label d-block">Notified At</label>
                    <input disabled type="text" class="form-control w-75" value="{{ $eventWaitlist->notified_at ? \Carbon\Carbon::parse($eventWaitlist->notified_at)->format('d M Y, h:i A') : 'Not Notified' }}">
                </div>
            </div>

        </div>

        <div class="text-end mt-4">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Back</a>
        </div>
    </x-form-wrapper>
</x-master-layout>
