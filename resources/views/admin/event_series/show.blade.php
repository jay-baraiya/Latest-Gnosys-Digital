<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="name" id="name" placeholder="Name"
                            value="{{ $eventSeries->name ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="slug">Slug</label>
                    <div class="input-group mb-1">
                        <input disabled type="text" class="form-control" name="slug" id="slug" placeholder="Slug"
                            value="{{ $eventSeries->slug ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="date_time">Date & Time</label>
                    <div class="input-group mb-1">
                        <input disabled type="datetime-local" class="form-control" name="date_time" id="date_time"
                            value="{{ isset($eventSeries) && $eventSeries->date_time ? \Carbon\Carbon::parse($eventSeries->date_time)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3 mt-4 pt-2">
                    <div class="form-check form-switch">
                        <input disabled class="form-check-input" type="checkbox" name="is_archived" id="is_archived" value="1"
                            {{ ($eventSeries->is_archived ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_archived">Is Archived?</label>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea disabled class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ $eventSeries->description ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex gap-3 mb-1">
                <div class="form-check">
                    <input disabled class="form-check-input" type="radio" name="status" id="status-active"
                        value="1" @if (isset($eventSeries) && $eventSeries->status == 1) checked @endif>
                    <label class="form-check-label" for="status-active">Active</label>
                </div>
                <div class="form-check">
                    <input disabled class="form-check-input" type="radio" name="status" id="status-inactive"
                        value="0" @if (isset($eventSeries) && $eventSeries->status == 0) checked @endif>
                    <label class="form-check-label" for="status-inactive">Inactive</label>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
        </div>
    </x-form-wrapper>
</x-master-layout>
