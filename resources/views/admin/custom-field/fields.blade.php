<input type="hidden" name="custom_field[recode_id]" value="{{ $recode_id }}">
<div class="dynamic-fields-wrapper">
    @if ($customfields->isNotEmpty())
        <input type="hidden" name="custom_field[all_field_ids]" value="{{ $customfields->pluck('id') }}">
        @foreach ($customfields as $key => $field)
            <div class="field-row border p-3 mb-4 rounded" data-row-index="{{ $key }}">
                <input type="hidden" name="custom_field[field_id][{{ $key }}]" value="{{ $field->id }}">
                <div class="row mb-5">
                    <div class="col-md-5">
                        <label class="form-label">Field Name</label>
                        <input type="text" class="form-control" name="custom_field[fields][{{ $key }}][name]" placeholder="Field name" value="{{ $field->name }}">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Field Type</label>
                        <select name="custom_field[fields][{{ $key }}][custom_field_type_id]" class="form-control custom-field-type">
                            <option value="">Select Field Type</option>
                            @if ($customfieldtyeps->isNotEmpty())
                                @foreach ($customfieldtyeps as $type)
                                    <option value="{{ $type->id }}" {{ $field?->fieldType?->id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-success add-field-btn">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger remove-field-btn">
                            <i class="ti ti-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="dynamic-field-settings"></div>
            </div>
        @endforeach
    @else
        <div class="field-row border p-3 mb-4 rounded" data-row-index="0">
            <div class="row mb-5">
                <div class="col-md-5">
                    <label class="form-label">Field Name</label>
                    <input type="text" class="form-control" name="custom_field[fields][0][name]" placeholder="Field name" value="">
                </div>

                <div class="col-md-5">
                    <label class="form-label">Field Type</label>
                    <select name="custom_field[fields][0][custom_field_type_id]" class="form-control custom-field-type">
                        <option value="">Select Field Type</option>
                        @if ($customfieldtyeps->isNotEmpty())
                            @foreach ($customfieldtyeps as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="button" class="btn btn-success add-field-btn">
                        <i class="ti ti-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger remove-field-btn">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>
            </div>

            <div class="dynamic-field-settings"></div>
        </div>
    @endif

</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var customfields = @json($customfields);

            $(document).on('change', '.custom-field-type', function() {
                var typeId = $(this).val();

                var mainDiv = $(this).closest('.field-row');
                var settingsContainer = mainDiv.find('.dynamic-field-settings');
                var index = mainDiv.data('row-index');

                var fieldId = mainDiv.find('input[name^="custom_field[field_id]"]').val();

                if (typeId) {
                    $.ajax({
                        url: "{{ route('admin.custom.fields.getFieldTypeData') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            type_id: typeId,
                            index: index,
                            field_id: fieldId
                        },
                        beforeSend: function() {
                            settingsContainer.html('<div class="mt-3 text-muted">Loading settings...</div>');
                        },
                        success: function(response) {
                            if (response.success) {
                                settingsContainer.fadeOut(200, function () {
                                    $(this).html(response.html).fadeIn(200);
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            settingsContainer.html('<div class="mt-3 text-danger">Error loading field settings.</div>');
                        }
                    });
                } else {
                    settingsContainer.empty();
                }
            });

            let fieldIndex = 0;
            if ($('.field-row').length > 0) {
                fieldIndex = Math.max(...$('.field-row').map(function() {
                    return $(this).data('row-index');
                }).get());
            }

            $(document).on('click', '.add-field-btn', function() {
                fieldIndex++;

                let $clonedRow = $('.dynamic-fields-wrapper .field-row:first').clone();

                $clonedRow.find('input[type="text"]').val('');
                $clonedRow.find('select').prop('selectedIndex', 0);

                $clonedRow.find('.dynamic-field-settings').empty();

                $clonedRow.find('input, select').each(function() {
                    let currentName = $(this).attr('name');
                    if (currentName) {
                        let newName = currentName.replace(/\[\d+\]/, '[' + fieldIndex + ']');
                        $(this).attr('name', newName);
                    }
                });

                $clonedRow.attr('data-row-index', fieldIndex);

                $clonedRow.find('input[name^="custom_field[field_id]"]').val('');

                $('.dynamic-fields-wrapper').append($clonedRow);
            });

            $(document).on('click', '.remove-field-btn', function() {
                if ($('.field-row').length > 1) {
                    $(this).closest('.field-row').remove();
                } else {
                    alert("You must have at least one field.");
                }
            });

            $('.custom-field-type').each(function() {
                if ($(this).val()) {
                    $(this).trigger('change');
                }
            });

            $('.custom-field-type').each(function() {
                if ($(this).val()) {
                    $(this).trigger('change');
                }
            });
        });
    </script>
@endpush
