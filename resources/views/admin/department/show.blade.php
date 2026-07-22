<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">

            <input type="hidden" name="id" id="id" value="{{ isset($department->id) ? encrypt($department->id) : '' }}">

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $department->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="email_id">Email Account</label>
                        <select disabled class="form-select" name="email_id" id="email_id">
                            <option value="">Select Email Account</option>
                            @foreach ($emailAccounts as $account)
                                <option value="{{ $account->id }}" {{ old('email_id', $department->email_id ?? '') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} ({{ $account->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea disabled class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $department->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input disabled class="form-check-input" type="radio" name="status" id="status-active" value="1"
                            @if (old('status', isset($department) ? $department->status : 1) == 1) checked @endif>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input disabled class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($department) ? $department->status : 1) == 0) checked @endif>
                        <label class="form-check-label" for="status-inactive">Inactive</label>
                    </div>
                </div>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <hr>

            <div class="fw-bold mb-3">Custom Fields</div>

            <input type="hidden" name="custom_field[module_type]" value="department">

            @include('admin.custom-field.fields', [
                'recode_id' => !empty($department->id) ? $department->id : '' ,
                'customfieldtyeps' => !empty($customfieldtyeps) ? $customfieldtyeps : collect([]),
                'customfields' => isset($customfields) ? $customfields : collect([]),
                'is_disabled' => 'disabled'
            ])

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
            </div>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#departmentForm').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 2,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.validate.departments') }}",
                                type: "post",
                                data: {
                                    id: function() {
                                        return $("#id").val();
                                    },
                                    name: function() {
                                        return $("#name").val();
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
                            required: "Please enter a department name.",
                            minlength: "Department name must consist of at least 2 characters.",
                            maxlength: "Department name cannot exceed 255 characters.",
                            remote: "Department name already exists."
                        },
                        status: {
                            required: "Please select a status."
                        }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
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
