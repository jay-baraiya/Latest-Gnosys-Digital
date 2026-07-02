<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($digitalproduct) ? 'Edit Digital Product' : 'Create Digital Product') }}">
        
            

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="name">Product Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="name" id="name" placeholder="Enter product name"
                                value="{{ old('name', $digitalproduct->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="sku">SKU <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU"
                                value="{{ old('sku', $digitalproduct->sku ?? '') }}">
                        </div>
                        @error('sku')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
                        <select disabled class="form-select select2" name="category_id" id="category_id">
                            <option value="">Select Category</option>
                            @if (isset($categories))
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $digitalproduct->category_id ?? '') == $category->id ? 'selected' : '' }}>
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

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="number" step="0.01" min="0" class="form-control" name="price" id="price" placeholder="0.00"
                                value="{{ old('price', $digitalproduct->price ?? '') }}">
                        </div>
                        @error('price')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="price_for_sale">Sale Price</label>
                        <div class="input-group mb-1">
                            <input disabled type="number" step="0.01" min="0" class="form-control" name="price_for_sale" id="price_for_sale" placeholder="0.00"
                                value="{{ old('price_for_sale', $digitalproduct->price_for_sale ?? '') }}">
                        </div>
                        @error('price_for_sale')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <div class="input-group mb-1">
                            <input disabled type="number" min="0" class="form-control" name="sort_order" id="sort_order" placeholder="0"
                                value="{{ old('sort_order', $digitalproduct->sort_order ?? 0) }}">
                        </div>
                        @error('sort_order')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="badge">Badge (e.g. New, Hot)</label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="badge" id="badge" placeholder="Badge text"
                                value="{{ old('badge', $digitalproduct->badge ?? '') }}">
                        </div>
                        @error('badge')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="image">Product Image <span class="text-danger">*</span> </label>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" id="toggleImageMode">
                                <i class="ti ti-link"></i> <span id="toggleImageText">Use Image URL instead</span>
                            </button>
                        </div>

                        <input disabled type="hidden" name="remove_existing_image" id="remove_existing_image" value="0">

                        <div id="fileInputContainer">
                            <input disabled type="file" class="form-control" name="image" id="image" accept="image/*">
                        </div>

                        <div id="urlInputContainer" class="d-none">
                            <input disabled type="url" class="form-control" name="image_url" id="image_url" placeholder="https://example.com/image.jpg" value="{{ old('image_url') }}">
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

                        @if(isset($digitalproduct) && $digitalproduct->image)
                            <div class="mt-3 p-2 border border-dashed rounded d-inline-block position-relative" id="currentImageContainer">
                                <small class="text-muted d-block mb-2">Current Saved Image:</small>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ filter_var($digitalproduct->image, FILTER_VALIDATE_URL) ? $digitalproduct->image : asset($digitalproduct->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">

                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1" id="removeExistingImageBtn" title="Remove Image" style="line-height: 1;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label" for="project">Upload Project File (.zip only, max 1GB) <span class="text-danger">*</span></label>

                        <input disabled type="hidden" name="remove_existing_project" id="remove_existing_project" value="0">

                        <input disabled type="file" name="project" id="project" class="form-control" accept=".zip,application/zip,application/x-zip-compressed">

                        @error('project')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror

                        @if(isset($digitalproduct) && $digitalproduct->project && \Illuminate\Support\Facades\Storage::disk('local')->exists($digitalproduct->project))
                            <div class="mt-3 overflow-auto p-2 border border-dashed rounded d-inline-flex align-items-center justify-content-between w-100" id="currentProjectContainer">
                                <div>
                                    <small class="text-muted d-block mb-1">
                                        Current Saved File:
                                        <button type="button" class="btn btn-sm btn-danger rounded p-2" id="removeExistingProjectBtn" title="Remove Project File">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </small>
                                    <strong><i class="ti ti-file-zip text-primary"></i> {{ basename($digitalproduct->project) }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <label class="form-check-label" for="tags">Tags</label>
                    <input disabled type="text" name="tags" id="tags" class="form-control", value="{{ !empty($digitalproduct->tags) ? $digitalproduct->tags : '' }}" placeholder="Enter tags..." data-choices>
                </div>


                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="short_description">Short Description <span class="text-danger">*</span></label>
                        <textarea disabled class="form-control" name="short_description" id="short_description" rows="2" placeholder="Brief summary">{{ old('short_description', $digitalproduct->short_description ?? '') }}</textarea>
                        @error('short_description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Full Description <span class="text-danger">*</span></label>

                        <input disabled type="hidden" name="description" id="description" value="{{ old('description', $digitalproduct->description ?? '') }}">

                        <div id="quill-editor" style="height: 200px;">{!! old('description', $digitalproduct->description ?? '') !!}</div>

                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <input disabled type="hidden" name="custom_field[module_type]" value="product">

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mb-1">
                        <div class="form-check">
                            <input disabled class="form-check-input" type="radio" name="status" id="status-active"
                                value="1" @if (old('status', $digitalproduct->status ?? 1) == 1) checked @endif>
                            <label class="form-check-label" for="status-active">Active</label>
                        </div>
                        <div class="form-check">
                            <input disabled class="form-check-input" type="radio" name="status" id="status-inactive"
                                value="0" @if (old('status', $digitalproduct->status ?? 1) == 0) checked @endif>
                            <label class="form-check-label" for="status-inactive">Inactive</label>
                        </div>
                    </div>
                    @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input disabled type="hidden" name="on_sale" value="0">
                        <input disabled class="form-check-input" type="checkbox" name="on_sale" id="on_sale" value="1"
                            {{ old('on_sale', $digitalproduct->on_sale ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="on_sale">
                            Product is on sale
                        </label>
                    </div>
                    @error('on_sale')
                        <span class="text-danger small ms-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="fw-bold mb-3">Custom Fields</div>

            @include('admin.custom-field.fields', [
                'recode_id' => !empty($digitalproduct->id) ? $digitalproduct->id : '' ,
                'customfieldtyeps' => $customfieldtyeps,
                'customfields' => isset($customfields) ? $customfields : collect([]),
            ])

            <div class="text-end mt-3">
                <a href="{{ isset($moduleUrl) ? route($moduleUrl) : url()->previous() }}" class="btn btn-soft-light">Cancel</a>
                
            </div>
        
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

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param);
                }, 'File size must be less than 1 MB.');

                let validator = $('#digitalProductForm').validate({
                    rules: {
                        name: { required: true, maxlength: 255 },
                        short_description: { required: true },
                        sku: { required: true, maxlength: 255 },
                        category_id: { required: true },
                        price: { required: true, number: true, min: 0 },
                        price_for_sale: { number: true, min: 0 },
                        sort_order: { digits: true, min: 0 },
                        status: { required: true },
                        image: {
                            required: function(element) {
                                return isImageRequired();
                            },
                            extension: "jpg|jpeg|png|webp",
                            filesize: 1048576
                        },
                        project: {
                            required: function(element) {
                                return isProjectRequired();
                            },
                            extension: "zip",
                            accept: "application/zip,application/x-zip-compressed,application/x-compressed,multipart/x-zip,.zip",
                            filesize: 1073741824 // 1 GB in bytes
                        }
                    },
                    messages: {
                        name: { required: "Please enter the product name." },
                        short_description: { required: "Please provide a detailed short description." },
                        sku: { required: "Please provide a unique SKU." },
                        category_id: { required: "Please select a category." },
                        image: {
                            extension: "Only JPG, JPEG, PNG and WEBP files are allowed.",
                            filesize: "File size must not exceed 1 MB."
                        },
                        project: {
                            required: "Please upload the project file.",
                            extension: "Only .zip files are allowed.",
                            accept: "Please upload a valid .zip file format.",
                            filesize: "File size must not exceed 1 GB."
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

                $('#removeExistingProjectBtn').on('click', function() {
                    $('#currentProjectContainer').addClass('d-none');

                    $('#remove_existing_project').val('1');

                    validator.element('#project');
                });

                function isProjectRequired() {
                    let hasExistingProject = $('#currentProjectContainer').length > 0 && !$('#currentProjectContainer').hasClass('d-none');
                    let markedForRemoval = $('#remove_existing_project').val() === "1";

                    return !hasExistingProject || markedForRemoval;
                }
            });
        </script>
    @endpush
</x-master-layout>
