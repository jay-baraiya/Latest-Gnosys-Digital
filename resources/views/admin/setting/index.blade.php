<x-master-layout>
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-1">Settings</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ route('admin.settings.index') }}?tab=general-settings" data-tab="general-settings" class="nav-link p-2 {{ $tab == 'general-settings' ? 'active' : '' }}">
                            <i class="ti ti-settings-cog me-2"></i>General Settings
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ route('admin.settings.index') }}?tab=website-settings" data-tab="website-settings" class="nav-link p-2 {{ $tab == 'website-settings' ? 'active' : '' }}">
                            <i class="ti ti-world-cog me-2"></i>Website Settings
                        </a>
                    </li>
                </ul>
            </div> <!-- end card body -->
        </div> <!-- end card -->

        <!-- start row -->
        <div class="row">

            <div class="col-xl-12 col-lg-12 tabHide" id="general-settings" style="display: {{ $tab != 'general-settings' ? 'none' : '' }};">

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h5 class="mb-0 fs-17">Profile</h5>
                        </div>
                        <form method="POST" action="{{ route('admin.settings.update', ['setting' => encrypt($user->id)]) }}?tab=general-settings" enctype="multipart/form-data" id="userForm">

                            @csrf
                            @if (isset($user))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <h6 class="mb-1">Employee Information</h6>
                                <p class="mb-0">Provide the information below</p>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="remove_existing_image" value="0">
                                <div class="profile-upload d-flex align-items-center">
                                    <div
                                        class="profile-upload-img avatar avatar-xxl border border-dashed rounded position-relative flex-shrink-0">
                                        <span><i class="ti ti-photo"></i></span>
                                        <img id="ImgPreview" src="{{ !empty($user->image) ? asset($user->image) : asset("assets/img/profiles/default.jpg") }}" alt="img"
                                            class="preview1">
                                        <a href="javascript:void(0);" id="removeImage1" class="profile-remove">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    </div>
                                    <div class="profile-upload-content ms-3">
                                        <label
                                            class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                            <i class="ti ti-file-broken me-1"></i>Upload File
                                            <input type="file" id="imag" name="image"
                                                class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0">
                                        </label>
                                        {{-- <p class="mb-0">JPG, GIF or PNG. Max size of 800K</p> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom mb-3">

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

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password @if (!isset($user))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <div class="input-group input-group-flat pass-group mb-1">
                                                <input type="password"
                                                    class="form-control pass-input @error('password') is-invalid @enderror" name="password"
                                                    id="password" placeholder="Password" autocomplete="new-password">
                                                <span class="input-group-text toggle-password">
                                                    <i class="ti ti-eye-off"></i>
                                                </span>
                                            </div>
                                            @if (isset($user))
                                                <small class="text-muted">Leave blank to keep current password.</small>
                                            @endif
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="password_confirmation">Confirm Password @if (!isset($user))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <div class="input-group input-group-flat pass-group mb-1">
                                                <input type="password"
                                                    class="form-control pass-input @error('password_confirmation') is-invalid @enderror"
                                                    name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
                                                    autocomplete="new-password">
                                                <span class="input-group-text toggle-password">
                                                    <i class="ti ti-eye-off"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
                                            <select disabled class="form-select select2" name="designation_id" id="designation_id">
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

                                    <div class="col-md-6">
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


                                </div>

                            </div>
                            <div class="border-bottom mb-3">
                                <div class="mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <p class="mb-0">Please enter the address details</p>
                                </div>

                                <!-- start row -->
                                <div class="row">

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
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
                                <!-- end row -->

                            </div>
                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <a href="profile-settings.html#" class="btn btn-sm btn-light">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->

            </div>

            <div class="col-xl-12 col-lg-12 tabHide" id="website-settings" style="display: {{ $tab != 'website-settings' ? 'none' : '' }};">

                <div class="card mb-0">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h6 class="mb-1">Website Settings</h6>
                            <p class="mb-0">Manage your website's general configurations, logos, and SEO settings below.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.settings.storeWebsiteSetting') }}?tab=website-settings" enctype="multipart/form-data">

                            @csrf

                            <!-- ================= Logos & Icons Section ================= -->
                            <div class="border-bottom mb-4 pb-3">
                                <h6 class="mb-3">Logos & Icons</h6>
                                <div class="row">

                                    <!-- Favicon -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Favicon <span class="text-muted small">(e.g., 32x32)</span></label>
                                        <!-- Field-specific hidden input for removal -->
                                        <input type="hidden" name="remove_favicon" class="remove-flag" value="0">
                                        <div class="profile-upload d-flex align-items-center">
                                            <div class="profile-upload-img avatar avatar-xl border border-dashed rounded position-relative flex-shrink-0">
                                                <span><i class="ti ti-photo"></i></span>
                                                <img id="previewFavicon" src="{{ !empty($settings->favicon) ? asset($settings->favicon) : asset('assets/img/empty-image.webp') }}" alt="Favicon" class="setting-preview-img">
                                                <!-- Added data-default to restore default image on remove -->
                                                <a href="javascript:void(0);" id="removeFavicon" class="profile-remove setting-remove-btn" data-default="{{ asset('assets/img/empty-image.webp') }}">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                            <div class="profile-upload-content ms-3">
                                                <label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                                    <i class="ti ti-upload me-1"></i>Upload
                                                    <input type="file" id="imgFavicon" name="favicon" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0 setting-img-input">
                                                </label>
                                                @error('favicon') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Header Icon / Logo -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Header Logo</label>
                                        <input type="hidden" name="remove_header_logo" class="remove-flag" value="0">
                                        <div class="profile-upload d-flex align-items-center">
                                            <div class="profile-upload-img avatar avatar-xl border border-dashed rounded position-relative flex-shrink-0">
                                                <span><i class="ti ti-photo"></i></span>
                                                <img id="previewHeaderLogo" src="{{ !empty($settings->header_logo) ? asset($settings->header_logo) : asset('assets/img/empty-image.webp') }}" alt="Header Logo" class="setting-preview-img">
                                                <a href="javascript:void(0);" id="removeHeaderLogo" class="profile-remove setting-remove-btn" data-default="{{ asset('assets/img/empty-image.webp') }}">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                            <div class="profile-upload-content ms-3">
                                                <label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                                    <i class="ti ti-upload me-1"></i>Upload
                                                    <input type="file" id="imgHeaderLogo" name="header_logo" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0 setting-img-input">
                                                </label>
                                                @error('header_logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Icon / Logo -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Footer Logo</label>
                                        <input type="hidden" name="remove_footer_logo" class="remove-flag" value="0">
                                        <div class="profile-upload d-flex align-items-center">
                                            <div class="profile-upload-img avatar avatar-xl border border-dashed rounded position-relative flex-shrink-0">
                                                <span><i class="ti ti-photo"></i></span>
                                                <img id="previewFooterLogo" src="{{ !empty($settings->footer_logo) ? asset($settings->footer_logo) : asset('assets/img/empty-image.webp') }}" alt="Footer Logo" class="setting-preview-img">
                                                <a href="javascript:void(0);" id="removeFooterLogo" class="profile-remove setting-remove-btn" data-default="{{ asset('assets/img/empty-image.webp') }}">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                            <div class="profile-upload-content ms-3">
                                                <label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                                    <i class="ti ti-upload me-1"></i>Upload
                                                    <input type="file" id="imgFooterLogo" name="footer_logo" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0 setting-img-input">
                                                </label>
                                                @error('footer_logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Header Icon -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mobile Header Logo</label>
                                        <input type="hidden" name="remove_mobile_header_logo" class="remove-flag" value="0">
                                        <div class="profile-upload d-flex align-items-center">
                                            <div class="profile-upload-img avatar avatar-xl border border-dashed rounded position-relative flex-shrink-0">
                                                <span><i class="ti ti-photo"></i></span>
                                                <img id="previewMobileHeader" src="{{ !empty($settings->mobile_header_logo) ? asset($settings->mobile_header_logo) : asset('assets/img/empty-image.webp') }}" alt="Mobile Header Logo" class="setting-preview-img">
                                                <a href="javascript:void(0);" id="removeMobileHeader" class="profile-remove setting-remove-btn" data-default="{{ asset('assets/img/empty-image.webp') }}">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                            <div class="profile-upload-content ms-3">
                                                <label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                                    <i class="ti ti-upload me-1"></i>Upload
                                                    <input type="file" id="imgMobileHeader" name="mobile_header_logo" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0 setting-img-input">
                                                </label>
                                                @error('mobile_header_logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Footer Icon -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mobile Footer Logo</label>
                                        <input type="hidden" name="remove_mobile_footer_logo" class="remove-flag" value="0">
                                        <div class="profile-upload d-flex align-items-center">
                                            <div class="profile-upload-img avatar avatar-xl border border-dashed rounded position-relative flex-shrink-0">
                                                <span><i class="ti ti-photo"></i></span>
                                                <img id="previewMobileFooter" src="{{ !empty($settings->mobile_footer_logo) ? asset($settings->mobile_footer_logo) : asset('assets/img/empty-image.webp') }}" alt="Mobile Footer Logo" class="setting-preview-img">
                                                <a href="javascript:void(0);" id="removeMobileFooter" class="profile-remove setting-remove-btn" data-default="{{ asset('assets/img/empty-image.webp') }}">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
                                            <div class="profile-upload-content ms-3">
                                                <label class="d-inline-flex align-items-center position-relative btn btn-primary btn-sm mb-2">
                                                    <i class="ti ti-upload me-1"></i>Upload
                                                    <input type="file" id="imgMobileFooter" name="mobile_footer_logo" class="input-img position-absolute w-100 h-100 opacity-0 top-0 end-0 setting-img-input">
                                                </label>
                                                @error('mobile_footer_logo') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- ================= General Settings Section ================= -->
                            <div class="border-bottom mb-4 pb-3">
                                <h6 class="mb-3">General Settings</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="site_name">Site Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="site_name" id="site_name" placeholder="Website Name" value="{{ old('site_name', $settings->site_name ?? '') }}">
                                            @error('site_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="contact_email">Contact Email</label>
                                            <input type="email" class="form-control" name="contact_email" id="contact_email" placeholder="support@example.com" value="{{ old('contact_email', $settings->contact_email ?? '') }}">
                                            @error('contact_email') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="contact_phone">Contact Phone</label>
                                            <input type="text" class="form-control" name="contact_phone" id="contact_phone" placeholder="+1 234 567 890" value="{{ old('contact_phone', $settings->contact_phone ?? '') }}">
                                            @error('contact_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="address-1">Address</label>
                                            <textarea class="form-control" name="address_one" id="address-1" rows="2" placeholder="Physical or Office Address">{{ old('address_one', $settings->address_one ?? '') }}</textarea>
                                            @error('address_one') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="address-2">Address 2</label>
                                            <textarea class="form-control" name="address_two" id="address-2" rows="2" placeholder="Physical or Office Address 2">{{ old('address_two', $settings->address_two ?? '') }}</textarea>
                                            @error('address_two') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ================= SEO Settings Section ================= -->
                            <div class="border-bottom mb-4 pb-3">
                                <h6 class="mb-3">SEO Settings</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="meta_title">Home Page Meta Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="SEO Title" value="{{ old('meta_title', $settings->meta_title ?? '') }}">
                                            @error('meta_title') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="meta_keywords">Meta Keywords</label>
                                            <input type="text" class="form-control" name="meta_keywords" id="meta_keywords" placeholder="keyword1, keyword2, keyword3" value="{{ old('meta_keywords', $settings->meta_keywords ?? '') }}">
                                            @error('meta_keywords') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="meta_description">Meta Description</label>
                                            <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Enter SEO meta description here...">{{ old('meta_description', $settings->meta_description ?? '') }}</textarea>
                                            @error('meta_description') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ================= Social Links Section ================= -->
                            <div class="mb-3">
                                <h6 class="mb-3">Social Links</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="facebook_url">Facebook URL</label>
                                            <input type="url" class="form-control" name="facebook_url" id="facebook_url" placeholder="https://facebook.com/..." value="{{ old('facebook_url', $settings->facebook_url ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="instagram_url">Instagram URL</label>
                                            <input type="url" class="form-control" name="instagram_url" id="instagram_url" placeholder="https://instagram.com/..." value="{{ old('instagram_url', $settings->instagram_url ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="twitter_url">Twitter/X URL</label>
                                            <input type="url" class="form-control" name="twitter_url" id="twitter_url" placeholder="https://twitter.com/..." value="{{ old('twitter_url', $settings->twitter_url ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="linkedin_url">LinkedIn URL</label>
                                            <input type="url" class="form-control" name="linkedin_url" id="linkedin_url" placeholder="https://linkedin.com/..." value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="pinterest_url">Pinterest URL</label>
                                            <input type="url" class="form-control" name="pinterest_url" id="pinterest_url" placeholder="https://pinterest.com/..." value="{{ old('pinterest_url', $settings->pinterest_url ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div> <!-- end card body -->
                </div> <!-- end card -->

            </div>

        </div>
        <!-- end row -->

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

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

                var Img = '{{ !empty($user->image) ? asset($user->image) : "assets/img/profiles/avatar-02.jpg" }}';

                function readURL(input, imgControlName) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgControlName).attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".input-img").on('change', function() {
                    var imgControlName = ".preview1";
                    readURL(this, imgControlName);
                    $(this).closest('.profile-upload').find('.preview1').addClass('it');
                    $(this).closest('.profile-upload').find('.profile-remove').addClass('profile-remove-btn');
                });

                $(".profile-remove").on('click', function(e) {
                    e.preventDefault();
                    $(this).closest('.profile-upload').find(".input-img").val("");
                    $(this).closest('.profile-upload').find(".preview1").attr("src", "");
                    $(this).closest('.profile-upload').find('.preview1').removeClass('it');
                    $(this).closest('.profile-upload').find('.profile-remove').removeClass('profile-remove-btn');
                    $('input[name="remove_existing_image"]').val(1);
                });

                function readSettingsURL(input, previewElement) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            previewElement.attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".setting-img-input").on('change', function() {
                    var container = $(this).closest('.col-md-4');
                    var previewImg = container.find('.setting-preview-img');
                    var removeBtn = container.find('.setting-remove-btn');
                    var removeFlag = container.find('.remove-flag');

                    readSettingsURL(this, previewImg);

                    previewImg.addClass('it');
                    removeBtn.addClass('profile-remove-btn');

                    removeFlag.val("0");
                });

                // Triggered when the remove cross is clicked
                $(".setting-remove-btn").on('click', function(e) {
                    e.preventDefault();

                    // Find the specific container for the clicked remove button
                    var container = $(this).closest('.col-md-4');
                    var fileInput = container.find(".setting-img-input");
                    var previewImg = container.find(".setting-preview-img");
                    var removeFlag = container.find('.remove-flag');

                    // Clear the file input
                    fileInput.val("");

                    // Reset image src to the default path stored in data-default attribute
                    var defaultSrc = $(this).data('default') || "";
                    previewImg.attr("src", defaultSrc);

                    // Remove display classes
                    previewImg.removeClass('it');
                    $(this).removeClass('profile-remove-btn');

                    // Set this specific field's remove flag to 1 for backend logic
                    removeFlag.val("1");
                });

                $(".input-img").trigger('change');

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
        </script>
    @endpush

</x-master-layout>
