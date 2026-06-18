<input type="hidden" name="variant[service_id]" value="{{ $service_id }}">
@if (isset($variants) && $variants->isNotEmpty())
    @foreach ($variants as $key => $variant)
        <input type="hidden" class="existing-variant-id" name="variant[{{ $key }}][variant_id]" value="{{ $variant->id }}">

        <div class="builder-row-wrapper border p-3 rounded mb-4">
            <div class="row mb-5 builder-rows">

                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Field Name</label>
                        <input type="text" class="form-control builder-name-input" name="variant[{{ $key }}][name]" placeholder="Field name" value="{{ $variant->name }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control builder-price-input" name="variant[{{ $key }}][price]" placeholder="e.g. $1.00" value="{{ $variant->price }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-success builder-add-btn">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger builder-remove-btn">
                            <i class="ti ti-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-12 features-container">
                        <label class="form-label">Features</label>
                        @php
                            $features = (is_array($variant->features) || is_object($variant->features)) ? $variant->features : json_decode($variant->features, true);
                            $features = !empty($features) ? $features : [''];
                        @endphp

                        @if (!empty($features))
                            @foreach ($features as $subKey => $feature)
                                <div class="variant-feature-item d-flex align-items-center gap-2 mb-2">
                                    <div class="flex-grow-1">
                                        <input type="text"
                                            name="variant[{{ $key }}][features][]"
                                            class="form-control"
                                            placeholder="Feature" value="{{ is_array($feature) ? ($feature['name'] ?? '') : $feature }}">
                                    </div>

                                    <button type="button" class="btn btn-success add-variant-feature-btn" title="Add Feature">
                                        <i class="ti ti-plus"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger remove-variant-feature-btn" title="Remove Feature">
                                        <i class="ti ti-minus"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="variant-feature-item d-flex align-items-center gap-2 mb-2">
                                <div class="flex-grow-1">
                                    <input type="text"
                                        name="variant[0][features][]"
                                        class="form-control"
                                        placeholder="Feature" value="">
                                </div>

                                <button type="button" class="btn btn-success add-variant-feature-btn" title="Add Feature">
                                    <i class="ti ti-plus"></i>
                                </button>

                                <button type="button" class="btn btn-danger remove-variant-feature-btn" title="Remove Feature">
                                    <i class="ti ti-minus"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="variant-description-{{ $key }}">Full Description </label>

                            <input type="hidden" name="variant[{{ $key }}][description]" id="variant-description-{{ $key }}" value="{{ $variant->description }}">

                            <div id="variant-quill-editor-{{ $key }}" style="height: 200px;"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="builder-dynamic-settings"></div>
        </div>
    @endforeach
@else
    <div class="builder-row-wrapper border p-3 rounded mb-4">
        <div class="row mb-5 builder-rows">

            <div class="row mb-3">
                <div class="col-md-5">
                    <label class="form-label">Field Name</label>
                    <input type="text" class="form-control builder-name-input" name="variant[0][name]" placeholder="Field name" value="">
                </div>

                <div class="col-md-5">
                    <label class="form-label">Price</label>
                    <input type="text" class="form-control builder-price-input" name="variant[0][price]" placeholder="e.g. $1.00" value="">
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="button" class="btn btn-success builder-add-btn">
                        <i class="ti ti-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger builder-remove-btn">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-12 features-container">
                    <label class="form-label">Features</label>
                    <div class="variant-feature-item d-flex align-items-center gap-2 mb-2">
                        <div class="flex-grow-1">
                            <input type="text"
                                name="variant[0][features][]"
                                class="form-control"
                                placeholder="Feature" value="">
                        </div>

                        <button type="button" class="btn btn-success add-variant-feature-btn" title="Add Feature">
                            <i class="ti ti-plus"></i>
                        </button>

                        <button type="button" class="btn btn-danger remove-variant-feature-btn" title="Remove Feature">
                            <i class="ti ti-minus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="variant-description-0">Full Description </label>

                        <input type="hidden" name="variant[0][description]" id="variant-description-0" value="">

                        <div id="variant-quill-editor-0" style="height: 200px;"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="builder-dynamic-settings"></div>
    </div>
@endif

@push('scripts')
<script>
    let variantIndex = $('.builder-row-wrapper').length;

    function initQuillEditor(index) {
        var quill = new Quill('#variant-quill-editor-' + index, {
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

        var existingContent = $('#variant-description-' + index).val();
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

        quill.on('text-change', function() {
            var html = quill.root.innerHTML;
            if (quill.getText().trim().length === 0) {
                html = '';
            }
            $('#variant-description-' + index).val(html);

            if(typeof validator !== 'undefined') {
                validator.element('#variant-description-' + index);
            }
        });
    }

    $(document).ready(function() {

        $('.builder-row-wrapper').each(function() {
            let editorDiv = $(this).find('[id^="variant-quill-editor-"]');
            if(editorDiv.length > 0) {
                let idString = editorDiv.attr('id');
                let idx = idString.split('-').pop();
                initQuillEditor(idx);
            }
        });

        $(document).on('click', '.builder-add-btn', function() {
            let currentRow = $(this).closest('.builder-row-wrapper');
            let clonedRow = currentRow.clone();

            let newIndex = variantIndex++;

            clonedRow.find('.existing-variant-id').remove();

            clonedRow.find('.ql-toolbar').remove();
            let editorDiv = clonedRow.find('[id^="variant-quill-editor-"]');
            editorDiv.removeClass('ql-container ql-snow').empty();

            clonedRow.find('.builder-name-input')
                     .attr('name', `variant[${newIndex}][name]`)
                     .val('');

            clonedRow.find('.builder-price-input')
                     .attr('name', `variant[${newIndex}][price]`)
                     .val('');

            let featuresContainer = clonedRow.find('.features-container');
            featuresContainer.find('.variant-feature-item:not(:first)').remove();
            let featureInput = featuresContainer.find('.variant-feature-item:first input');
            featureInput.attr('name', `variant[${newIndex}][features][]`).val('');

            clonedRow.find('input[type="hidden"][id^="variant-description-"]')
                     .attr('name', `variant[${newIndex}][description]`)
                     .attr('id', `variant-description-${newIndex}`)
                     .val('');

            editorDiv.attr('id', `variant-quill-editor-${newIndex}`);

            currentRow.after(clonedRow);

            initQuillEditor(newIndex);
        });

        $(document).on('click', '.builder-remove-btn', function() {
            if ($('.builder-row-wrapper').length > 1) {
                $(this).closest('.builder-row-wrapper').remove();
            } else {
                alert("You must have at least one variant row.");
            }
        });

        $(document).on('click', '.add-variant-feature-btn', function() {
            let currentFeature = $(this).closest('.variant-feature-item');
            let newFeature = currentFeature.clone();

            newFeature.find('input').val('');

            currentFeature.after(newFeature);
        });

        $(document).on('click', '.remove-variant-feature-btn', function() {
            let featureContainer = $(this).closest('.features-container');

            if (featureContainer.find('.variant-feature-item').length > 1) {
                $(this).closest('.variant-feature-item').remove();
            } else {
                $(this).closest('.variant-feature-item').find('input').val('');
            }
        });

    });
</script>
@endpush
