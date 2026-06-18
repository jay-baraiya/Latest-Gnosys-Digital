<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="userForm"
            action="{{ isset($category) ? route('admin.categories.update', encrypt($category->id)) : route('admin.categories.store') }}" method="post">
            @csrf
            @if (isset($category))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $category->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="type" id="type">
                            <option value="">Select Type</option>
                            <option value="product" {{ (!empty($category->type) && $category->type == 'product') ? 'selected' : '' }} >Digital Product</option>
                            <option value="service" {{ (!empty($category->type) && $category->type == 'service') ? 'selected' : '' }} >Digital Service</option>
                        </select>
                        @error('type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-active"
                            value="1" @if (old('status', isset($category) ? $category->status : 1) == 1) checked @endif>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($category) ? $category->status : 1) == 0) checked @endif>
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
                $('#userForm').validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.categories.check.category.name') }}",
                                type: "post",
                                data: {
                                    name: function() {
                                        return $("#name").val();
                                    },
                                    category_id: function() {
                                        return '{{ isset($category) ? $category->id : '' }}';
                                    },
                                }
                            }
                        },
                        type: {
                            required: true
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter a name.",
                            remote: "This category name is already in use."
                        },
                        role_id: {
                            required: "Please select a role."
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

                $('#type').select2({
                    placeholder: 'Select a type',
                    allowClear: true,
                });
            });
        </script>
    @endpush

</x-master-layout>
