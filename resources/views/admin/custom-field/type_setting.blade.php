<div class="mt-3 p-3 border rounded bg-light">
    <h5 class="mb-3">{{ $fieldType->name }} Settings</h5>

    @php
        $indexKey = !empty($index) ? $index : 0;
    @endphp

    @if($fieldType->has_options)
        <div class="form-group mb-3">
            <label>Field Options <span class="text-danger">*</span></label>

            <div id="dynamic-options-container">
                @if (!empty($options))
                    @foreach ($options as $key => $option)
                        <div class="input-group mb-2 option-row custom-field-option-row">
                            <input type="text" name="custom_field[options][{{ $indexKey }}][{{ $key }}]" class="form-control" placeholder="Enter option value" value="{{ $option }}">
                            <button class="btn {{ $loop->first ? 'btn-success add-option-btn' : 'btn-danger remove-option-btn' }}" type="button">
                                <strong>{{ $loop->first ? '+' : '-' }}</strong>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 option-row">
                        <input type="text" name="custom_field[options][{{ $indexKey }}][]" class="form-control" placeholder="Enter option value">
                        <button class="btn btn-success add-option-btn" type="button">
                            <strong>+</strong>
                        </button>
                    </div>
                @endif
            </div>

            <small class="text-muted">Add the choices the user will see (e.g., Red, Green, Blue).</small>
        </div>
    @endif

    <div class="row">
        @if(is_array($field_type_params) && array_key_exists('placeholder', $field_type_params))
            <div class="col-md-6 mb-3">
                <label>Placeholder</label>
                <input type="text" name="custom_field[params][{{ $indexKey }}][placeholder]" class="form-control" placeholder="e.g., Enter your name..." value="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}" >
            </div>
        @endif

        @if(is_array($field_type_params) && array_key_exists('default_value', $field_type_params))
            <div class="col-md-6 mb-3">
                <label>Default Value</label>
                <input type="text" name="custom_field[params][{{ $indexKey }}][default_value]" class="form-control" value="{{ !empty($params['default_value']) ? $params['default_value'] : '' }}">
            </div>
        @endif

        @if(is_array($field_type_params) && array_key_exists('min', $field_type_params))
            <div class="col-md-6 mb-3">
                <label>Min Value</label>
                <input type="text" name="custom_field[params][{{ $indexKey }}][min]" class="form-control" placeholder="Min value" value="{{ !empty($params['min']) ? $params['min'] : '' }}">
            </div>
        @endif

        @if(is_array($field_type_params) && array_key_exists('max', $field_type_params))
            <div class="col-md-6 mb-3">
                <label>Max Value</label>
                <input type="text" name="custom_field[params][{{ $indexKey }}][max]" class="form-control" placeholder="Max value" value="{{ !empty($params['max']) ? $params['max'] : '' }}">
            </div>
        @endif

        @if(is_array($field_type_params) && array_key_exists('rows', $field_type_params))
            <div class="col-md-6 mb-3">
                <label>Textarea Rows</label>
                <input type="number" name="custom_field[params][{{ $indexKey }}][rows]" class="form-control" value="{{ !empty($params['rows']) ? $params['rows'] : '3' }}">
            </div>
        @endif
    </div>

    @if(is_array($field_type_params) && array_key_exists('is_required', $field_type_params))
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="custom_field[params][{{ $indexKey }}][is_required]" value="1" {{ !empty($params['is_required']) ? 'checked' : '' }} id="is_required_check_{{ $indexKey }}">
            <label class="form-check-label" for="is_required_check_{{ $indexKey }}">
                Is this field required?
            </label>
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {

        function updateOptionIndex(container) {

            container.find('.option-row').each(function(index) {

                let input = $(this).find('input[type="text"]');
                let oldName = input.attr('name');

                if (oldName) {
                    let newName = oldName.replace(/\[[^\]]*\]$/, '[' + index + ']');
                    input.attr('name', newName);
                }
            });
        }

        $(document).off('click', '.add-option-btn').on('click', '.add-option-btn', function() {

            let container = $(this).closest('#dynamic-options-container');

            let newRow = $(this).closest('.option-row').clone();

            newRow.find('input').val('');

            newRow.find('button')
                .removeClass('btn-success add-option-btn')
                .addClass('btn-danger remove-option-btn')
                .html('<strong>-</strong>');

            container.append(newRow);

            updateOptionIndex(container);
        });

        $(document).off('click', '.remove-option-btn').on('click', '.remove-option-btn', function() {

            let container = $(this).closest('#dynamic-options-container');

            $(this).closest('.option-row').remove();

            updateOptionIndex(container);
        });

    });
</script>
