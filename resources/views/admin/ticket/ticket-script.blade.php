@push('scripts')
    <script>
        $(document).ready(function () {
            var productsData = @json($products ?? []);
            var servicesData = @json($services ?? []);

            function populateProducts($select, selectedValue = '') {
                $select.empty().append('<option value="">-- Select Product --</option>');
                $.each(productsData, function(i, product) {
                    var selected = selectedValue == product.id ? 'selected' : '';
                    $select.append(`<option value="${product.id}" data-price="${product.price || ''}" ${selected}>${product.name}</option>`);
                });
            }

            function populateServices($select, selectedValue = '') {
                $select.empty().append('<option value="">-- Select Service --</option>');
                $.each(servicesData, function(i, service) {
                    var selected = selectedValue == service.id ? 'selected' : '';
                    $select.append(`<option value="${service.id}" data-price="${service.price || ''}" ${selected}>${service.name}</option>`);
                });
            }

            $(document).on('click', '.item-type-badge', function() {
                var $row = $(this).closest('.order-item-row');
                var $hidden = $row.find('.item-type-hidden');
                var currentType = $hidden.val();
                var $select = $row.find('.product-select');
                var $variantSelect = $row.find('.variant-select');
                var $priceInput = $row.find('.item-price');

                if (currentType === 'product') {
                    $hidden.val('service');
                    $(this).text('Service').removeClass('bg-primary').addClass('bg-success');
                    populateServices($select);
                } else {
                    $hidden.val('product');
                    $(this).text('Product').removeClass('bg-success').addClass('bg-primary');
                    populateProducts($select);
                }

                $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required');
                $priceInput.val('');
                calculateTotals();
            });

            $(document).on('change', '.product-select', function() {
                var $row = $(this).closest('.order-item-row');
                var $variantSelect = $row.find('.variant-select');
                var $priceInput = $row.find('.item-price');
                var type = $row.find('.item-type-hidden').val();
                var itemId = $(this).val();

                if (!itemId) {
                    $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required');
                    $priceInput.val('');
                    calculateTotals();
                    return;
                }

                if (type === 'product') {
                    var price = $(this).find('option:selected').data('price') || '';
                    $priceInput.val(price);
                    $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required');
                } else {
                    var service = servicesData.find(s => String(s.id) === String(itemId));
                    if (service && service.variants && service.variants.length > 0) {
                        $variantSelect.empty().append('<option value="">-- Select Variant --</option>').prop('readonly', false).addClass('variant-select-required');
                        $.each(service.variants, function(i, variant) {
                            $variantSelect.append(`<option value="${variant.id}" data-price="${variant.price || ''}">${variant.name}</option>`);
                        });
                        $priceInput.val('');
                    } else {
                        var price = $(this).find('option:selected').data('price') || '';
                        $priceInput.val(price);
                        $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required');
                    }
                }
                calculateTotals();
            });

            $(document).on('change', '.variant-select', function() {
                var $row = $(this).closest('.order-item-row');
                var $priceInput = $row.find('.item-price');
                var price = $(this).find('option:selected').data('price') || '';
                $priceInput.val(price);
                calculateTotals();
            });

            $(document).on('input change', '.item-qty, .item-price', function() {
                calculateTotals();
            });

            function calculateRowTotal($row) {
                var qty = parseFloat($row.find('.item-qty').val()) || 0;
                var price = parseFloat($row.find('.item-price').val()) || 0;
                var total = qty * price;
                $row.find('.item-total').val(total.toFixed(2));
            }

            function calculateTotals() {
                var grandTotal = 0;
                $('.order-item-row').each(function() {
                    calculateRowTotal($(this));
                    var rowTotal = parseFloat($(this).find('.item-total').val()) || 0;
                    grandTotal += rowTotal;
                });
                $('#subtotal').val(grandTotal.toFixed(2));
                $('#total_amount').val(grandTotal.toFixed(2));
                $('#grandTotalDisplay').text('$' + grandTotal.toFixed(2));
            }

            calculateTotals();

            $('#addItemBtn').on('click', function() {
                var newRow = `
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="task_id[]" value="">
                                <input type="hidden" name="product_type[]" class="item-type-hidden" value="product">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">Product</span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[]" required>
                                    <option value="">-- Select Product --</option>
                                    ${productsData.map(p => `<option value="${p.id}" data-price="${p.price || ''}">${p.name}</option>`).join('')}
                                </select>
                            </td>
                            <td>
                                <select class="form-select variant-select" name="variant_id[]" readonly>
                                    <option value="">-- No Variant --</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control item-qty" name="quantity[]"
                                    min="1" value="1" placeholder="1" required>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-price" name="price[]"
                                        min="0" value="" placeholder="0.00" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-total" readonly value="" placeholder="0.00">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove row">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                $('#orderItemsBody').append(newRow);
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-item-btn', function() {
                var rows = $('#orderItemsBody .order-item-row');
                if (rows.length > 1) {
                    $(this).closest('tr').remove();
                    updateRemoveButtons();
                    calculateTotals();
                } else {
                    alert('At least one order item is required.');
                }
            });

            function updateRemoveButtons() {
                var rows = $('#orderItemsBody .order-item-row');
                if (rows.length === 1) {
                    rows.find('.remove-item-btn').prop('disabled', true);
                } else {
                    rows.find('.remove-item-btn').prop('disabled', false);
                }
            }

            updateRemoveButtons();

            $.validator.addClassRules('product-select', {
                required: true
            });
            $.validator.addClassRules('variant-select-required', {
                required: true
            });
            $.validator.addClassRules('item-qty', {
                required: true,
                min: 1,
                // digits: true
            });
            $.validator.addClassRules('item-price', {
                required: true,
                min: 0,
                number: true
            });
        });
    </script>
@endpush
