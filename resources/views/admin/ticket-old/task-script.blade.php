<script>
    $(document).ready(function () {
        var productsData = @json($products ?? []);
        var servicesData = @json($services ?? []);
        var departmentsData = @json($departments ?? []);
        var developersData = @json($developers ?? []);

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

            // Remove required class and validation error styling when clearing variant
            $variantSelect.empty().append('<option value="">-- No Variant --</option>')
                .prop('readonly', true)
                .removeClass('variant-select-required is-invalid');
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
                $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required is-invalid');
                $priceInput.val('');
                calculateTotals();
                return;
            }

            var orderId = @json($ticket->order_id ?? null);

            if (orderId) {
                setQty(this, orderId, itemId, type);
            }

            if (type === 'product') {
                var price = $(this).find('option:selected').data('price') || '';
                $priceInput.val(price);
                $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required is-invalid');
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
                    $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('readonly', true).removeClass('variant-select-required is-invalid');
                }
            }
            calculateTotals();
        });

        function setQty(element, orderId, val, type) {
            if (!orderId || !val || !type) {
                return;
            }

            $.ajax({
                url: '{{ route("admin.tickets.get-qty") }}',
                type: 'POST',
                data: {
                    order_id: orderId,
                    product_id: val,
                    product_type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $(element).closest('.order-item-row').find('.item-qty').val(response.qty);
                        calculateTotals();
                    }
                }
            });
        }

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

        var rowIdxCounter = $('.order-item-row').length;

        $('#addItemBtn').on('click', function() {

            var rowIdx = rowIdxCounter++;

            var newRow = `
                <tr class="order-item-row">
                    <td>
                        <input type="hidden" name="task_id[${rowIdx}]" value="">
                        <input type="hidden" name="product_type[${rowIdx}]" class="item-type-hidden" value="product">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">Product</span>
                            <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                        </div>
                        <select class="form-select product-select" name="product_id[${rowIdx}]" required>
                            <option value="">-- Select Product --</option>
                            ${productsData.map(p => `<option value="${p.id}" data-price="${p.price || ''}">${p.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <select class="form-select variant-select" name="variant_id[${rowIdx}]" readonly>
                            <option value="">-- No Variant --</option>
                        </select>
                    </td>
                    <td>
                        <input type="date" class="form-control due-date" name="due_date[${rowIdx}]" required>
                    </td>
                    <td>
                        <select class="form-select department-id" name="department_id[${rowIdx}]" required>
                            <option value="">-- Select Department --</option>
                            ${departmentsData.map(dep => `<option value="${dep.id}">${dep.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <select class="form-select assign-id" name="assign_id[${rowIdx}]" required>
                            <option value="">-- Select Developer --</option>
                            ${developersData.map(dev => `<option value="${dev.id}">${dev.name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control item-qty" name="quantity[${rowIdx}]"
                            min="1" value="1" placeholder="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control item-price" name="price[${rowIdx}]"
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

        var validator = $('#taskForm').validate({
            ignore: ":hidden:not(.select2-hidden-accessible, #description)",
            rules: {},
            messages: {},
            errorClass: 'text-danger small mt-1',
            errorElement: 'span',
            highlight: function(element) {
                $(element).addClass('is-invalid');
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next('.select2-container').find('.select2-selection').addClass('border-danger');
                }
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next('.select2-container').find('.select2-selection').removeClass('border-danger');
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr('id') === 'description') {
                    error.insertAfter('#quill-editor');
                } else if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // These work perfectly now because every input has a unique name[] array key
        $.validator.addClassRules('product-select', { required: true });
        $.validator.addClassRules('variant-select-required', { required: true });
        $.validator.addClassRules('due-date', { required: true });
        $.validator.addClassRules('item-qty', { required: true, min: 1 });
        $.validator.addClassRules('item-price', { required: true, min: 0, number: true });
        $.validator.addClassRules('assign-id', { required: true });
        $.validator.addClassRules('department-id', { required: true });
    });
</script>
