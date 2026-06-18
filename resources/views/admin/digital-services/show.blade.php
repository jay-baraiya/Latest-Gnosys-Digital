<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($digitalservice) ? 'Edit Digital Product' : 'Create Digital Product') }}">

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="name">Product Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter product name"
                                value="{{ old('name', $digitalservice->name ?? '') }}">
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
                            <input type="text" class="form-control" name="sku" id="sku" placeholder="Enter SKU"
                                value="{{ old('sku', $digitalservice->sku ?? '') }}">
                        </div>
                        @error('sku')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="category_id" id="category_id">
                            <option value="">Select Category</option>
                            @if (isset($categories))
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $digitalservice->category_id ?? '') == $category->id ? 'selected' : '' }}>
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
                            <input type="number" step="0.01" min="0" class="form-control" name="price" id="price" placeholder="0.00"
                                value="{{ old('price', $digitalservice->price ?? '') }}">
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
                            <input type="number" step="0.01" min="0" class="form-control" name="price_for_sale" id="price_for_sale" placeholder="0.00"
                                value="{{ old('price_for_sale', $digitalservice->price_for_sale ?? '') }}">
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
                            <input type="number" min="0" class="form-control" name="sort_order" id="sort_order" placeholder="0"
                                value="{{ old('sort_order', $digitalservice->sort_order ?? 0) }}">
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
                            <input type="text" class="form-control" name="badge" id="badge" placeholder="Badge text"
                                value="{{ old('badge', $digitalservice->badge ?? '') }}">
                        </div>
                        @error('badge')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="image">Product Image</label>
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

                        @if(isset($digitalservice) && $digitalservice->image)
                            <div class="mt-3 p-2 border border-dashed rounded d-inline-block position-relative" id="currentImageContainer">
                                <small class="text-muted d-block mb-2">Current Saved Image:</small>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ filter_var($digitalservice->image, FILTER_VALIDATE_URL) ? $digitalservice->image : asset($digitalservice->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">

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
                    <input type="text" name="tags" id="tags" class="form-control", value="{{ !empty($digitalservice->tags) ? $digitalservice->tags : '' }}" placeholder="Enter tags..." data-choices>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input type="hidden" name="on_sale" value="0">
                        <input class="form-check-input" type="checkbox" name="on_sale" id="on_sale" value="1"
                            {{ old('on_sale', $digitalservice->on_sale ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="on_sale">
                            Product is on sale
                        </label>
                    </div>
                    @error('on_sale')
                        <span class="text-danger small ms-2">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="short_description">Short Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="short_description" id="short_description" rows="2" placeholder="Brief summary">{{ old('short_description', $digitalservice->short_description ?? '') }}</textarea>
                        @error('short_description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Full Description <span class="text-danger">*</span></label>

                        <input type="hidden" name="description" id="description" value="{{ old('description', $digitalservice->description ?? '') }}">

                        <div id="quill-editor" style="height: 200px;">{!! old('description', $digitalservice->description ?? '') !!}</div>

                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <input type="hidden" name="custom_field[module_type]" value="service">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mb-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status-active"
                                value="1" @if (old('status', $digitalservice->status ?? 1) == 1) checked @endif>
                            <label class="form-check-label" for="status-active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status-inactive"
                                value="0" @if (old('status', $digitalservice->status ?? 1) == 0) checked @endif>
                            <label class="form-check-label" for="status-inactive">Inactive</label>
                        </div>
                    </div>
                    @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="fw-bold mb-3">Custom Fields</div>

            @include('custom-field.fields', [
                'recode_id' => !empty($digitalservice->id) ? $digitalservice->id : '' ,
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

                new Choices('[data-choices]', {
                    removeItemButton: true,
                    duplicateItemsAllowed: false,
                    paste: true
                });

                $('#category_id').select2({
                    placeholder: 'Select a category',
                    allowClear: true,
                });

                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Detailed service description...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'clean']
                        ]
                    }
                });

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param);
                }, 'File size must be less than 1 MB.');

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
                        $('#image_url').rules('add', { url: true, messages: { url: "Please enter a valid URL." } });
                    } else {
                        $('#urlInputContainer').addClass('d-none');
                        $('#fileInputContainer').removeClass('d-none');
                        $(this).html('<i class="ti ti-link"></i> <span id="toggleImageText">Use Image URL instead</span>');

                        $('#image_url').val('');
                        $('#image_url').rules('remove', 'url');
                        $('#image').rules('add', { extension: "jpg|jpeg|png|webp|gif" });
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
            });
        </script>
    @endpush
</x-master-layout>
