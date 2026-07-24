<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="eventSeriesForm"
            action="{{ isset($eventSeries) ? route('admin.event_series.update', encrypt($eventSeries->id)) : route('admin.event_series.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($eventSeries))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $eventSeries->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="slug">Slug <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug"
                                value="{{ old('slug', $eventSeries->slug ?? '') }}">
                        </div>
                        @error('slug')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="date_time">Date & Time</label>
                        <div class="input-group mb-1">
                            <input type="datetime-local" class="form-control" name="date_time" id="date_time"
                                value="{{ old('date_time', isset($eventSeries) && $eventSeries->date_time ? \Carbon\Carbon::parse($eventSeries->date_time)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        @error('date_time')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3 mt-4 pt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_archived" id="is_archived" value="1"
                                {{ old('is_archived', $eventSeries->is_archived ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_archived">Is Archived?</label>
                        </div>
                        @error('is_archived')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $eventSeries->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-active"
                            value="1" @if (old('status', isset($eventSeries) ? $eventSeries->status : 1) == 1) checked @endif>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($eventSeries) ? $eventSeries->status : 1) == 0) checked @endif>
                        <label class="form-check-label" for="status-inactive">Inactive</label>
                    </div>
                </div>
                @error('status')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#name').on('keyup', function() {
                    let name = $(this).val();
                    let slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                    $('#slug').val(slug);
                    if ($('#slug').val().length > 0) {
                        $('#slug').valid();
                    }
                });

                $('#eventSeriesForm').validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.event_series.checkName') }}",
                                type: "post",
                                data: {
                                    name: function() {
                                        return $("#name").val();
                                    },
                                    event_series_id: function() {
                                        return '{{ isset($eventSeries) ? $eventSeries->id : '' }}';
                                    }
                                }
                            }
                        },
                        slug: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.event_series.checkSlug') }}",
                                type: "post",
                                data: {
                                    slug: function() {
                                        return $("#slug").val();
                                    },
                                    event_series_id: function() {
                                        return '{{ isset($eventSeries) ? $eventSeries->id : '' }}';
                                    }
                                }
                            }
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter a name.",
                            remote: "This name is already taken."
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
