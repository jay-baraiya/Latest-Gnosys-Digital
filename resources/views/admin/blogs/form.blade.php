<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($blog) ? 'Edit Blog' : 'Create Blog') }}">
        <form id="blogForm"
            action="{{ isset($blog) ? route('admin.blogs.update', encrypt($blog->id)) : route('admin.blogs.store') }}"
            method="post"
            enctype="multipart/form-data">

            @csrf
            @if (isset($blog))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Blog Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter blog name"
                                value="{{ old('name', $blog->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="category_id" id="category_id">
                            <option value="">Select Category</option>
                            @if (isset($categories))
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="image">Blog Image <span class="text-danger">*</span> </label>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" id="toggleImageMode">
                                <i class="ti ti-link"></i> <span id="toggleImageText">Use Image URL instead</span>
                            </button>
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

                        @if(isset($blog) && $blog->image)
                            <div class="mt-3 p-2 border border-dashed rounded d-inline-block position-relative" id="currentImageContainer">
                                <small class="text-muted d-block mb-2">Current Saved Image:</small>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ filter_var($blog->image, FILTER_VALIDATE_URL) ? $blog->image : asset($blog->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">

                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" id="removeExistingImageBtn" title="Remove Image" style="line-height: 1;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <label class="form-check-label" for="tags">Tags</label>
                    <input type="text" name="tags" id="tags" class="form-control", value="{{ !empty($blog->tags) ? $blog->tags : '' }}" placeholder="Enter tags..." data-choices>
                </div>

                <div class="col-lg-4">
                    <label class="form-check-label" for="read_time">Read Time</label>
                    <input type="text" name="read_time" id="read_time" class="form-control", value="{{ !empty($blog->read_time) ? $blog->read_time : '' }}" placeholder="Ex. 12 min read">
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="short_description">Short Description<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="short_description" id="short_description" rows="2" placeholder="Brief summary">{{ old('short_description', $blog->short_description ?? '') }}</textarea>
                        @error('short_description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Full Description <span class="text-danger">*</span></label>

                        <input type="hidden" name="description" id="description" value="{{ old('description', $blog->description ?? '') }}">

                        <div id="quill-editor" style="height: 200px;">{!! old('description', $blog->description ?? '') !!}</div>

                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status-active"
                                value="1" @if (old('status', $blog->status ?? 1) == 1) checked @endif>
                            <label class="form-check-label" for="status-active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status-inactive"
                                value="0" @if (old('status', $blog->status ?? 1) == 0) checked @endif>
                            <label class="form-check-label" for="status-inactive">Inactive</label>
                        </div>
                    </div>
                    @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ isset($moduleUrl) ? route($moduleUrl) : url()->previous() }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#category_id').select2({
                    placeholder: 'Select a category',
                    allowClear: true,
                });

                new Choices('[data-choices]', {
                    removeItemButton: true,
                    duplicateItemsAllowed: false,
                    paste: true
                });

                Quill.register("modules/htmlEditButton", htmlEditButton);

                var toolbarOptions = [
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],

                    [{ 'script': 'sub'}, { 'script': 'super' }],

                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    [{ 'direction': 'rtl' }],

                    ['link', 'image', 'video', 'formula'],
                    ['blockquote', 'code-block'],

                    ['clean']
                ];

                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Detailed service description...',
                    modules: {
                        toolbar: toolbarOptions,

                        htmlEditButton: {
                            debug: false,
                            msg: "Edit the HTML below. Clicking 'Save' will update the editor.",
                            okText: "Save",
                            cancelText: "Cancel",
                            buttonHTML: "&lt;&gt;",
                            buttonTitle: "Show HTML source",
                            syntax: false
                        }
                    }
                });

                let validator = $('#blogForm').validate({
                    rules: {
                        name: { required: true, maxlength: 255 },
                        short_description: { required: true },
                        category_id: { required: true },
                        status: { required: true },
                        image: {
                            required: function(element) {
                                return isImageRequired();
                            },
                            extension: "jpg|jpeg|png|webp",
                            filesize: 1048576
                        },
                    },
                    messages: {
                        name: { required: "Please enter the blog name." },
                        short_description: { required: "Please provide a detailed short description." },
                        category_id: { required: "Please select a category." },
                        image: {
                            extension: "Only JPG, JPEG, PNG and WEBP files are allowed.",
                            filesize: "File size must not exceed 1 MB."
                        },
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
                        } else if (element.attr('id') === 'description') {
                            error.insertAfter('#quill-editor');
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                quill.on('text-change', function() {
                    var html = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0) {
                        html = '';
                    }
                    $('#description').val(html);

                    validator.element('#description');
                });

                let isUrlMode = false;

                $('#toggleImageMode').on('click', function(e) {
                    e.preventDefault();
                    isUrlMode = !isUrlMode;

                    if (isUrlMode) {
                        $('#fileInputContainer').addClass('d-none');
                        $('#urlInputContainer').removeClass('d-none');
                        $(this).html('<i class="ti ti-upload"></i> <span id="toggleImageText">Upload an Image instead</span>');

                        $('#image').val('');
                        $('#image').rules('remove', 'extension');
                        $('#image_url').rules('add', { required: function(element) {
                            return isImageRequired();
                        }, url: true, messages: { url: "Please enter a valid URL." } });
                    } else {
                        $('#urlInputContainer').addClass('d-none');
                        $('#fileInputContainer').removeClass('d-none');
                        $(this).html('<i class="ti ti-link"></i> <span id="toggleImageText">Use Image URL instead</span>');

                        $('#image_url').val('');
                        $('#image_url').rules('remove', 'url');
                        $('#image').rules('add', { required: function(element) {
                            return isImageRequired();
                        }, extension: "jpg|jpeg|png|webp" });
                    }

                    clearPreview();
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

                let urlTimeout;
                $('#image_url').on('input', function() {
                    clearTimeout(urlTimeout);
                    const url = $(this).val();

                    urlTimeout = setTimeout(() => {
                        if (url && isValidUrl(url)) {
                            showPreview(url);
                        } else {
                            clearPreview();
                        }
                    }, 500);
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

                function isValidUrl(string) {
                    try {
                        new URL(string);
                        return true;
                    } catch (_) {
                        return false;
                    }
                }

                function isImageRequired() {
                    let hasExistingImage = $('#currentImageContainer').length > 0 && !$('#currentImageContainer').hasClass('d-none');
                    let markedForRemoval = $('#remove_existing_image').val() === "1";

                    return !hasExistingImage || markedForRemoval;
                }
            });
        </script>
    @endpush
</x-master-layout>
