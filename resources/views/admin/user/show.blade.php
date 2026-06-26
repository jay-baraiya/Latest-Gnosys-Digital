<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $user->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                value="{{ old('email', $user->email ?? '') }}">
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone"
                                value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                        @error('phone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="designation_id" id="designation_id">
                            <option value="">Select Designation</option>
                            @if (isset($designations) && count($designations) > 0)
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}"
                                        {{ old('designation_id', $user->designation_id ?? '') == $designation->id ? 'selected' : '' }}>
                                        {{ $designation->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('designation_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="role_id">Role <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="role_id" id="role_id">
                            <option value="">Select Role</option>
                            @if (isset($roles))
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user?->role?->id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('role_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="zip">Zip</label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="zip" id="zip" placeholder="Zip"
                                value="{{ old('zip', $user->zip ?? '') }}">
                        </div>
                        @error('zip')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="country_id">Country <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="country_id" id="country_id">
                            <option value="">Select Country</option>
                        </select>
                        @error('country_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="state_id">State <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="state_id" id="state_id">
                            <option value="">Select State</option>
                        </select>
                        @error('state_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="city_id">City <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="city_id" id="city_id">
                            <option value="">Select City</option>
                        </select>
                        @error('city_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="address">Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="image">User Image </label>
                        </div>

                        <input type="hidden" name="remove_existing_image" id="remove_existing_image" value="0">

                        <div id="fileInputContainer">
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        </div>

                        <div id="urlInputContainer" class="d-none">
                            <input type="url" class="form-control" name="image_url" id="image_url" placeholder="https://example.com/image.jpg" value="{{ old('image_url') }}">
                        </div>

                        @error('image')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                        @error('image_url')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror

                        <div class="mt-3 d-none text-center p-2 border border-dashed rounded" id="imagePreviewContainer">
                            <img id="imagePreview" src="" alt="Image Preview" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            <button type="button" class="btn btn-sm btn-soft-danger mt-2 d-block mx-auto" id="clearPreviewBtn">Clear Image</button>
                        </div>

                        @if(isset($user) && $user->image)
                            <div class="mt-3 p-2 border border-dashed rounded d-inline-block position-relative" id="currentImageContainer">
                                <small class="text-muted d-block mb-2">Current Saved Image:</small>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ filter_var($user->image, FILTER_VALIDATE_URL) ? $user->image : asset($user->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">

                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" id="removeExistingImageBtn" title="Remove Image" style="line-height: 1;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-active"
                            value="1" @if (old('status', isset($user) ? $user->status : 1) == 1) checked @endif>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($user) ? $user->status : 1) == 0) checked @endif>
                        <label class="form-check-label" for="status-inactive">Inactive</label>
                    </div>
                </div>
                @error('status')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
            </div>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                function isImageRequired() {
                    let hasExistingImage = $('#currentImageContainer').length > 0 && !$('#currentImageContainer').hasClass('d-none');
                    let markedForRemoval = $('#remove_existing_image').val() === "1";

                    return !hasExistingImage || markedForRemoval;
                }

                let isEdit = @json(isset($user));

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param);
                }, 'File size must be less than 1 MB.');

                $('#userForm').validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },
                        email: {
                            required: true,
                            email: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.users.check.email') }}",
                                type: "post",
                                data: {
                                    email: function() {
                                        return $("#email").val();
                                    },
                                    user_id: function() {
                                        return '{{ isset($user) ? $user->id : '' }}';
                                    },
                                }
                            }
                        },
                        password: {
                            required: !isEdit,
                            minlength: 8
                        },
                        password_confirmation: {
                            required: !isEdit,
                            equalTo: "#password"
                        },
                        phone: {
                            required: true,
                            maxlength: 15,
                            digits: true,
                            remote: {
                                url: "{{ route('admin.users.check.phone') }}",
                                type: "post",
                                data: {
                                    phone: function() {
                                        return $("#phone").val();
                                    },
                                    user_id: function() {
                                        return '{{ isset($user) ? $user->id : '' }}';
                                    },
                                }
                            }
                        },
                        zip: {
                            maxlength: 6,
                            digits: true
                        },
                        country_id: {
                            required: true
                        },
                        state_id: {
                            required: true
                        },
                        city_id: {
                            required: true
                        },
                        role_id: {
                            required: true
                        },
                        designation_id : {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        image: {
                            required: function(element) {
                                return /*isImageRequired()*/ false;
                            },
                            extension: "jpg|jpeg|png|webp",
                            filesize: 1048576
                        },
                    },
                    messages: {
                        name: {
                            required: "Please enter a name."
                        },
                        email: {
                            required: "Please enter a valid email.",
                            email: "Enter a valid email structure.",
                            remote: "This email is already registered."
                        },
                        password: {
                            required: "Please provide a password.",
                            minlength: "Minimum 8 characters."
                        },
                        password_confirmation: {
                            required: "Please confirm password.",
                            equalTo: "Passwords do not match."
                        },
                        role_id: {
                            required: "Please select a role."
                        },
                        designation_id: {
                            required: "Please select a designation."
                        },
                        image: {
                            extension: "Only JPG, JPEG, PNG and WEBP files are allowed.",
                            filesize: "File size must not exceed 1 MB."
                        },
                        phone: {
                            remote: "This phone number is already in use."
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

                $('#designation_id').select2({
                    placeholder: 'Select a designation',
                    allowClear: true,
                });

                $('#role_id').select2({
                    placeholder: 'Select a role',
                    allowClear: true,
                });

                async function loadEditData() {
                    const countryId = "{{ isset($user->country_id) ? $user->country_id : '' }}";
                    const countryName = "{{ isset($user->country->name) ? $user->country->name : '' }}";

                    const stateId = "{{ isset($user->state_id) ? $user->state_id : '' }}";
                    const stateName = "{{ isset($user->state->name) ? $user->state->name : '' }}";

                    const cityId = "{{ isset($user->city_id) ? $user->city_id : '' }}";
                    const cityName = "{{ isset($user->city->name) ? $user->city->name : '' }}";

                    $('#state_id').prop('disabled', true);
                    $('#city_id').prop('disabled', true);

                    await setSelect2Value('#country_id', countryId, countryName);

                    $('#state_id').prop('disabled', false);

                    await setSelect2Value('#state_id', stateId, stateName);

                    $('#city_id').prop('disabled', false);

                    await setSelect2Value('#city_id', cityId, cityName);
                }

                loadEditData();

            });

            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        showPreview(e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else {
                    clearPreview();
                }
            });

            $('#imagePreview').on('error', function() {
                $(this).attr('src', 'https://placehold.co/600x400?text=Invalid+Image+URL');
            });

            $('#clearPreviewBtn').on('click', function() {
                if (isUrlMode) {
                    $('#image_url').val('');
                } else {
                    $('#image').val('');
                }
                clearPreview();
            });

            $('#removeExistingImageBtn').on('click', function() {
                $('#currentImageContainer').addClass('d-none');
                $('#remove_existing_image').val('1');
            });

            function showPreview(src) {
                $('#imagePreview').attr('src', src);
                $('#imagePreviewContainer').removeClass('d-none');
                $('#currentImageContainer').addClass('d-none');
            }

            function clearPreview() {
                $('#imagePreview').attr('src', '');
                $('#imagePreviewContainer').addClass('d-none');
                if ($('#remove_existing_image').val() !== '1') {
                    $('#currentImageContainer').removeClass('d-none');
                }
            }
        </script>
    @endpush

</x-master-layout>
