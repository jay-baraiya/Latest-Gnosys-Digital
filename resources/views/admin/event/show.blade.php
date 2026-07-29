<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'View' }}">
        <div class="row">
            <!-- Basic Info -->
            <div class="col-12 mb-3">
                <h5 class="text-primary border-bottom pb-2">Basic Information</h5>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="title" id="title" placeholder="Event Title"
                            value="{{ $event->title ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="slug">Slug</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="slug" id="slug" placeholder="Event Slug"
                            value="{{ $event->slug ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="event_type">Event Type</label>
                    <input disabled type="text" class="form-control" value="{{ $event->event_type == 'series' ? 'Part of a Series' : 'Single Event' }}">
                </div>
            </div>

            @if(isset($event) && $event->event_type == 'series')
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="series_id">Event Series</label>
                    <input disabled type="text" class="form-control" value="{{ $event->series->name ?? 'N/A' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="series_edition">Series Edition</label>
                    <input disabled type="text" class="form-control" value="{{ $event->series_edition ?? 'N/A' }}">
                </div>
            </div>
            @endif

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea disabled class="form-control" name="description" id="description" rows="3">{{ $event->description ?? '' }}</textarea>
                </div>
            </div>

            <!-- Date & Location -->
            <div class="col-12 mt-4 mb-3">
                <h5 class="text-primary border-bottom pb-2">Schedule & Location</h5>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="start_date">Start Date & Time</label>
                    <input disabled type="datetime-local" class="form-control"
                        value="{{ isset($event) && $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="end_date">End Date & Time</label>
                    <input disabled type="datetime-local" class="form-control"
                        value="{{ isset($event) && $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="timezone">Timezone</label>
                    <input disabled type="text" class="form-control" value="{{ $event->timezone ?? 'UTC' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="event_mode">Event Mode</label>
                    <input disabled type="text" class="form-control" value="{{ ucfirst($event->event_mode ?? 'Unknown') }}">
                </div>
            </div>

            @if(isset($event) && in_array($event->event_mode, ['offline', 'hybrid']))
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="location">Location</label>
                    <input disabled type="text" class="form-control" value="{{ $event->location ?? 'N/A' }}">
                </div>
            </div>
            @endif

            @if(isset($event) && in_array($event->event_mode, ['online', 'hybrid']))
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="event_link">Event Link (Meeting URL)</label>
                    <input disabled type="url" class="form-control" value="{{ $event->event_link ?? 'N/A' }}">
                </div>
            </div>
            @endif

            <!-- Registration & Pricing -->
            <div class="col-12 mt-4 mb-3">
                <h5 class="text-primary border-bottom pb-2">Registration & Pricing</h5>
            </div>

            <div class="col-md-2">
                <div class="mb-3 mt-4 pt-2">
                    <div class="form-check form-switch">
                        <input disabled class="form-check-input" type="checkbox"
                            {{ ($event->is_free ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Is Free?</label>
                    </div>
                </div>
            </div>

            @if(isset($event) && !$event->is_free)
            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label" for="price">Price</label>
                    <input disabled type="text" class="form-control" value="{{ $event->price ?? '0.00' }}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="mb-3">
                    <label class="form-label" for="currency">Currency</label>
                    <input disabled type="text" class="form-control" value="{{ $event->currency ?? 'USD' }}">
                </div>
            </div>
            @endif

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label" for="capacity">Capacity</label>
                    <input disabled type="text" class="form-control" value="{{ $event->capacity ?? 'Unlimited' }}">
                </div>
            </div>

            <div class="col-md-2">
                <div class="mb-3 mt-4 pt-2">
                    <div class="form-check form-switch">
                        <input disabled class="form-check-input" type="checkbox"
                            {{ ($event->waitlist_enabled ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Waitlist?</label>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-12 mt-4 mb-3">
                <h5 class="text-primary border-bottom pb-2">Event Status</h5>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label d-block">Status</label>
                    <input disabled type="text" class="form-control w-25" value="{{ ucfirst($event->status ?? 'Draft') }}">
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Back</a>
        </div>
    </x-form-wrapper>
</x-master-layout>
