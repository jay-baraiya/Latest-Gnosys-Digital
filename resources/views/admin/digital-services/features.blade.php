<input type="hidden" name="features[service_id]" value="{{ $service_id }}">

<div class="mb-4">

    <div id="features-wrapper" class="d-flex flex-column gap-2">

        @if ($features->isNotEmpty())
            @foreach ($features as $key => $feature)
                <input type="hidden" name="features[{{ $key }}][feature_id]" class="form-control" value="{{ $feature->id }}">
                <div class="feature-item d-flex align-items-center gap-2">
                    <div class="flex-grow-1">
                        <input type="text"
                            name="features[{{ $key }}][name]"
                            class="form-control"
                            placeholder="Feature 1" value="{{ $feature->name }}">
                    </div>

                    <button type="button" class="btn btn-success add-feature-btn" title="Add Feature">
                        <i class="ti ti-plus"></i>
                    </button>

                    <button type="button" class="btn btn-danger remove-feature-btn" title="Remove Feature">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>
            @endforeach
        @else
            <div class="feature-item d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                    <input type="text"
                        name="features[0][name]"
                        class="form-control"
                        placeholder="Feature 1">
                </div>

                <button type="button" class="btn btn-success add-feature-btn" title="Add Feature">
                    <i class="ti ti-plus"></i>
                </button>

                <button type="button" class="btn btn-danger remove-feature-btn" style="display: none;" title="Remove Feature">
                    <i class="ti ti-minus"></i>
                </button>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {

        function updateFeatureIndexes() {
            let totalItems = $('#features-wrapper .feature-item').length;

            $('#features-wrapper .feature-item').each(function(index) {
                $(this).find('input').attr('name', `features[${index}][name]`);

                $(this).find('input').attr('placeholder', `Feature ${index + 1}`);

                if (totalItems > 1) {
                    $(this).find('.remove-feature-btn').show();
                } else {
                    $(this).find('.remove-feature-btn').hide();
                }
            });
        }

        $('#features-wrapper').on('click', '.add-feature-btn', function() {
            let newItem = $('#features-wrapper .feature-item:first').clone();

            newItem.find('input').val('');

            $('#features-wrapper').append(newItem);

            updateFeatureIndexes();
        });

        $('#features-wrapper').on('click', '.remove-feature-btn', function() {
            if ($('#features-wrapper .feature-item').length > 1) {
                $(this).closest('.feature-item').remove();

                updateFeatureIndexes();
            }
        });

        updateFeatureIndexes();
    });
</script>
@endpush
