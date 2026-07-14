<x-master-layout>
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Profile</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ route('admin.profile.edit') }}?tab=profile-information" data-tab="profile-information" class="nav-link p-2 {{ $tab == 'profile-information' ? 'active' : '' }}">
                            <i class="ti ti-user me-2"></i>Profile Information
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ route('admin.profile.edit') }}?tab=update-password" data-tab="update-password" class="nav-link p-2 {{ $tab == 'update-password' ? 'active' : '' }}">
                            <i class="ti ti-lock me-2"></i>Update Password
                        </a>
                    </li>
                </ul>
            </div> <!-- end card body -->
        </div> <!-- end card -->

        <!-- start row -->
        <div class="row">

            <div class="col-xl-12 col-lg-12 tabHide" id="profile-information" style="display: {{ $tab != 'profile-information' ? 'none' : '' }};">

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h5 class="mb-0 fs-17">Profile Information</h5>
                        </div>
                        <form id="profileForm" enctype="multipart/form-data" action="{{ route('admin.profile.update') }}?tab=profile-information" method="post">
                            @csrf
                            @method('patch')
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
                                        <select disabled class="form-select select2" name="role_id" id="role_id">
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
                                        <label class="form-label" for="city-id">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city_id" id="city-id" class="form-control" value={{ old('city_id', $user->city_id ?? '') }}>
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

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label mb-0" for="image">User Image </label>
                                        </div>
                                        <input type="hidden" name="remove_image" id="remove_existing_image" value="0">
                                        <div id="fileInputContainer">
                                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                        </div>
                                        @error('image')
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
                            </div>
                            <div class="text-end mt-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-soft-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->

            </div> <!-- end col -->

            <div class="col-xl-12 col-lg-12 tabHide" id="update-password" style="display: {{ $tab != 'update-password' ? 'none' : '' }};">

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h5 class="mb-0 fs-17">Update Password</h5>
                        </div>
                        <form id="passwordForm" action="{{ route('admin.password.update') }}?tab=update-password" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="current_password">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-flat pass-group mb-1">
                                            <input type="password" class="form-control pass-input @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" id="current_password" placeholder="Current Password" autocomplete="current-password">
                                            <span class="input-group-text toggle-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                        @error('current_password', 'updatePassword')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="update_password">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-flat pass-group mb-1">
                                            <input type="password" class="form-control pass-input @error('password', 'updatePassword') is-invalid @enderror" name="password" id="update_password" placeholder="New Password" autocomplete="new-password">
                                            <span class="input-group-text toggle-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                        @error('password', 'updatePassword')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="update_password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-flat pass-group mb-1">
                                            <input type="password" class="form-control pass-input @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation" id="update_password_confirmation" placeholder="Confirm Password" autocomplete="new-password">
                                            <span class="input-group-text toggle-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-soft-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->

            </div> <!-- end col -->

        </div> <!-- end row -->
    </div> <!-- end content -->

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Tab Switching Logic
                $(document).on('click', '.nav-link', function(e) {
                    e.preventDefault();
                    var tab = $(this).data('tab');
                    var url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);

                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');

                    $('.tabHide').hide();
                    $(`#${tab}`).show();
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

                    if (typeof setSelect2Value === "function") {
                        await setSelect2Value('#country_id', countryId, countryName);
                        $('#state_id').prop('disabled', false);
                        await setSelect2Value('#state_id', stateId, stateName);
                        $('#city_id').prop('disabled', false);
                        await setSelect2Value('#city_id', cityId, cityName);
                    }
                }

                loadEditData();

                // Validation
                $('#profileForm').validate({
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
                                    email: function() { return $("#email").val(); },
                                    user_id: function() { return '{{ isset($user) ? $user->id : '' }}'; }
                                }
                            }
                        },
                        phone: {
                            required: true,
                            maxlength: 15,
                            digits: true,
                            remote: {
                                url: "{{ route('admin.users.check.phone') }}",
                                type: "post",
                                data: {
                                    phone: function() { return $("#phone").val(); },
                                    user_id: function() { return '{{ isset($user) ? $user->id : '' }}'; }
                                }
                            }
                        },
                        zip: { maxlength: 6, digits: true },
                        country_id: { required: true },
                        state_id: { required: true },
                        city_id: { required: true },
                        designation_id : { required: true },
                        image: { extension: "jpg|jpeg|png|webp" }
                    },
                    messages: {
                        name: { required: "Please enter a name." },
                        email: {
                            required: "Please enter a valid email.",
                            email: "Enter a valid email structure.",
                            remote: "This email is already registered."
                        },
                        phone: {
                            remote: "This phone number is already in use."
                        }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible)",
                    highlight: function(element) { $(element).addClass('is-invalid'); },
                    unhighlight: function(element) { $(element).removeClass('is-invalid'); },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                $('#passwordForm').validate({
                    rules: {
                        current_password: { required: true },
                        password: { required: true, minlength: 8 },
                        password_confirmation: { required: true, equalTo: "#update_password" }
                    },
                    messages: {
                        current_password: { required: "Please provide current password." },
                        password: { required: "Please provide a new password.", minlength: "Minimum 8 characters." },
                        password_confirmation: { required: "Please confirm password.", equalTo: "Passwords do not match." }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    highlight: function(element) { $(element).addClass('is-invalid'); },
                    unhighlight: function(element) { $(element).removeClass('is-invalid'); },
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                // Image handling from user form
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
                    $('#image').val('');
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
            });
        </script>
    @endpush
</x-master-layout>