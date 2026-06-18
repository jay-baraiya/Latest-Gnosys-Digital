<x-master-layout>
    <x-form-wrapper>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <div class="input-group mb-1">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                            value="{{ old('name', $role->name ?? '') }}" disabled>
                    </div>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" disabled>{{ old('description', $role->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex gap-3 mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-active" value="1"
                        disabled @if (old('status', isset($role) ? $role->status : 1) == 1) checked @endif>
                    <label class="form-check-label" for="status-active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-inactive" value="0"
                        disabled @if (old('status', isset($role) ? $role->status : 1) == 0) checked @endif>
                    <label class="form-check-label" for="status-inactive">Inactive</label>
                </div>
            </div>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="text-end mt-3">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
        </div>
    </x-form-wrapper>
</x-master-layout>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#roleForm').validate({
                errorClass: 'text-danger small mt-1',
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a role name.",
                        minlength: "Role name must consist of at least 2 characters.",
                        maxlength: "Role name cannot exceed 255 characters."
                    },
                    status: {
                        required: "Please select a status."
                    }
                }
            });
        });
    </script>
@endpush
