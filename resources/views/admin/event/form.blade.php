<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="eventForm"
            action="{{ isset($event) ? route('admin.event.update', encrypt($event->id)) : route('admin.event.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($event))
                @method('PUT')
            @endif
            
            <div class="row">
                <!-- Basic Info -->
                <div class="col-12 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Basic Information</h5>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Event Title"
                                value="{{ old('title', $event->title ?? '') }}">
                        </div>
                        @error('title')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="slug" id="slug" placeholder="Event Slug"
                                value="{{ old('slug', $event->slug ?? '') }}">
                        </div>
                        @error('slug')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="event_type">Event Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="event_type" id="event_type">
                            <option value="single" {{ old('event_type', $event->event_type ?? '') == 'single' ? 'selected' : '' }}>Single Event</option>
                            <option value="series" {{ old('event_type', $event->event_type ?? '') == 'series' ? 'selected' : '' }}>Part of a Series</option>
                        </select>
                        @error('event_type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 series-fields">
                    <div class="mb-3">
                        <label class="form-label" for="series_id">Event Series <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="series_id" id="series_id">
                            <option value="">Select Series</option>
                            @foreach ($series as $s)
                                <option value="{{ $s->id }}" {{ old('series_id', $event->series_id ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('series_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 series-fields">
                    <div class="mb-3">
                        <label class="form-label" for="series_edition">Series Edition</label>
                        <input type="number" class="form-control" name="series_edition" id="series_edition" placeholder="e.g. 1"
                            value="{{ old('series_edition', $event->series_edition ?? '') }}">
                        @error('series_edition')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $event->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Date & Location -->
                <div class="col-12 mt-4 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Schedule & Location</h5>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="start_date">Start Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="start_date" id="start_date"
                            value="{{ old('start_date', isset($event) && $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') : '') }}">
                        @error('start_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="end_date">End Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="end_date" id="end_date"
                            value="{{ old('end_date', isset($event) && $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '') }}">
                        @error('end_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="timezone">Timezone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="timezone" id="timezone" value="{{ old('timezone', $event->timezone ?? 'UTC') }}">
                        @error('timezone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="event_mode">Event Mode <span class="text-danger">*</span></label>
                        <select class="form-select" name="event_mode" id="event_mode">
                            <option value="online" {{ old('event_mode', $event->event_mode ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ old('event_mode', $event->event_mode ?? '') == 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="hybrid" {{ old('event_mode', $event->event_mode ?? '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        @error('event_mode')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 location-fields">
                    <div class="mb-3">
                        <label class="form-label" for="location">Location</label>
                        <input type="text" class="form-control" name="location" id="location" placeholder="Venue Address"
                            value="{{ old('location', $event->location ?? '') }}">
                        @error('location')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 link-fields">
                    <div class="mb-3">
                        <label class="form-label" for="event_link">Event Link (Meeting URL)</label>
                        <input type="url" class="form-control" name="event_link" id="event_link" placeholder="https://..."
                            value="{{ old('event_link', $event->event_link ?? '') }}">
                        @error('event_link')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Registration & Pricing -->
                <div class="col-12 mt-4 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Registration & Pricing</h5>
                </div>

                <div class="col-md-2">
                    <div class="mb-3 mt-4 pt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_free" id="is_free" value="1"
                                {{ old('is_free', $event->is_free ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_free">Is Free?</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 price-fields">
                    <div class="mb-3">
                        <label class="form-label" for="price">Price</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" name="price" id="price" placeholder="0.00"
                                value="{{ old('price', $event->price ?? '') }}">
                        </div>
                        @error('price')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2 price-fields">
                    <div class="mb-3">
                        <label class="form-label" for="currency">Currency <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="currency" id="currency" value="{{ old('currency', $event->currency ?? 'USD') }}">
                        @error('currency')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="capacity">Capacity (Optional)</label>
                        <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Leave empty for unlimited"
                            value="{{ old('capacity', $event->capacity ?? '') }}">
                        @error('capacity')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="mb-3 mt-4 pt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="waitlist_enabled" id="waitlist_enabled" value="1"
                                {{ old('waitlist_enabled', $event->waitlist_enabled ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="waitlist_enabled">Waitlist?</label>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-12 mt-4 mb-3">
                    <h5 class="text-primary border-bottom pb-2">Event Status</h5>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label d-block">Status <span class="text-danger">*</span></label>
                        
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-draft"
                                    value="draft" @if (old('status', $event->status ?? 'draft') == 'draft') checked @endif>
                                <label class="form-check-label" for="status-draft">Draft</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-published"
                                    value="published" @if (old('status', $event->status ?? '') == 'published') checked @endif>
                                <label class="form-check-label" for="status-published">Published</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-ongoing"
                                    value="ongoing" @if (old('status', $event->status ?? '') == 'ongoing') checked @endif>
                                <label class="form-check-label" for="status-ongoing">Ongoing</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-ended"
                                    value="ended" @if (old('status', $event->status ?? '') == 'ended') checked @endif>
                                <label class="form-check-label" for="status-ended">Ended</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-cancelled"
                                    value="cancelled" @if (old('status', $event->status ?? '') == 'cancelled') checked @endif>
                                <label class="form-check-label" for="status-cancelled">Cancelled</label>
                            </div>
                        </div>
                        @error('status')
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

                // Title to Slug
                $('#title').on('keyup', function() {
                    let title = $(this).val();
                    let slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                    $('#slug').val(slug);
                    if ($('#slug').val().length > 0) {
                        $('#slug').valid();
                    }
                });

                // Toggle Series Fields
                function toggleSeriesFields() {
                    if ($('#event_type').val() === 'series') {
                        $('.series-fields').slideDown();
                    } else {
                        $('.series-fields').slideUp();
                        $('#series_id').val('').trigger('change');
                        $('#series_edition').val('');
                    }
                }
                $('#event_type').on('change', toggleSeriesFields);
                toggleSeriesFields();

                // Toggle Location/Link Fields
                function toggleModeFields() {
                    let mode = $('#event_mode').val();
                    if (mode === 'online') {
                        $('.location-fields').slideUp();
                        $('.link-fields').slideDown();
                    } else if (mode === 'offline') {
                        $('.location-fields').slideDown();
                        $('.link-fields').slideUp();
                    } else {
                        $('.location-fields').slideDown();
                        $('.link-fields').slideDown();
                    }
                }
                $('#event_mode').on('change', toggleModeFields);
                toggleModeFields();

                // Toggle Price Fields
                function togglePriceFields() {
                    if ($('#is_free').is(':checked')) {
                        $('.price-fields').slideUp();
                    } else {
                        $('.price-fields').slideDown();
                    }
                }
                $('#is_free').on('change', togglePriceFields);
                togglePriceFields();

                // Validation
                $('#eventForm').validate({
                    rules: {
                        title: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.event.checkTitle') }}",
                                type: "post",
                                data: {
                                    title: function() { return $("#title").val(); },
                                    event_id: function() { return '{{ isset($event) ? $event->id : '' }}'; }
                                }
                            }
                        },
                        slug: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.event.checkSlug') }}",
                                type: "post",
                                data: {
                                    slug: function() { return $("#slug").val(); },
                                    event_id: function() { return '{{ isset($event) ? $event->id : '' }}'; }
                                }
                            }
                        },
                        event_type: "required",
                        series_id: {
                            required: function() { return $('#event_type').val() === 'series'; }
                        },
                        start_date: "required",
                        end_date: "required",
                        timezone: "required",
                        event_mode: "required",
                        currency: {
                            required: function() { return !$('#is_free').is(':checked'); }
                        },
                        status: "required"
                    },
                    messages: {
                        title: {
                            required: "Please enter a title.",
                            remote: "This title is already taken."
                        },
                        slug: {
                            required: "Please enter a slug.",
                            remote: "This slug is already in use."
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
            });
        </script>
    @endpush
</x-master-layout>
