<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">

            <div class="row">
                <h5 class="mb-3 text-primary">Order Details</h5>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="order_number">Order Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('order_number') is-invalid @enderror" name="order_number" id="order_number" placeholder="ORD-12345"
                            value="{{ old('order_number', $order->order_number ?? $order_number ?? '') }}">
                        @error('order_number')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="date_time">Order Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('date_time') is-invalid @enderror" name="date_time" id="date_time"
                            value="{{ old('date_time', $order->date_time ?? '') }}">
                        @error('date_time')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">User / Customer</label>
                        <select class="form-select select2 @error('user_id') is-invalid @enderror" name="user_id" id="user_id">
                            <option value="">Select User (Leave blank for guest)</option>
                            @if (isset($users))
                            @foreach ($users as $userOption)
                            <option value="{{ $userOption->id }}"
                                {{ old('user_id', $order->user_id ?? '') == $userOption->id ? 'selected' : '' }}>
                                {{ $userOption->name }} ({{ $userOption->email }})
                            </option>
                            @endforeach
                            @endif
                        </select>
                        @error('user_id')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="subtotal">Sub Total <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('subtotal') is-invalid @enderror" name="subtotal" id="subtotal" placeholder="0.00"
                                value="{{ old('subtotal', $order->subtotal ?? '') }}">
                        </div>
                        @error('subtotal')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="total_amount">Order Total <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" name="total_amount" id="total_amount" placeholder="0.00"
                                value="{{ old('total_amount', $order->total_amount ?? '') }}">
                        </div>
                        @error('total_amount')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr class="my-3">

                <h5 class="mb-3 text-primary">Billing Details</h5>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="billing_first_name">Billing First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" name="billing_first_name" id="billing_first_name" placeholder="John Doe"
                            value="{{ old('billing_first_name', $order->billing_first_name ?? '') }}">
                        @error('billing_first_name')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="billing_phone">Billing Phone</label>
                        <input type="text" class="form-control @error('billing_phone') is-invalid @enderror" name="billing_phone" id="billing_phone" placeholder="+1 234 567 8900"
                            value="{{ old('billing_phone', $order->billing_phone ?? '') }}">
                        @error('billing_phone')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="billing_country">Billing Country <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="billing_country" id="billing_country">
                            <option value="">Select Country</option>
                            @if ($countries->isNotEmpty())
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ !empty($order->billing_country) && $order->billing_country == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('billing_country')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="billing_state">Billing State <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="billing_state" id="billing_state">
                            <option value="">Select State</option>
                            @if ($states->isNotEmpty())
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ !empty($order->billing_state) && $order->billing_state == $state->id ? 'selected' : '' }} >{{ $state->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('billing_state')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="billing_city">Billing City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('billing_city') is-invalid @enderror" name="billing_city" id="billing_city" placeholder="City"
                            value="{{ old('billing_city', $order->billing_city ?? '') }}">
                        @error('billing_city')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="billing_address">Billing Address <span class="text-danger">*</span></label>
                        <textarea name="billing_address" id="billing_address" class="form-control @error('billing_address') is-invalid @enderror" cols="3" rows="3">{{ old('billing_address', $order->billing_address ?? '') }}</textarea>
                            @error('billing_address')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr class="my-3">

                <h5 class="mb-3 text-primary">Status & Notes</h5>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="status">Order Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status', $order->status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ old('status', $order->status ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ old('status', $order->status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status', $order->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="payment_method">Payment Method</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" id="payment_method">
                            <option value="">Select Method</option>
                            <option value="stripe" {{ old('payment_method', $order->payment_method ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="paypal" {{ old('payment_method', $order->payment_method ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="cod" {{ old('payment_method', $order->payment_method ?? '') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                        </select>
                        @error('payment_method')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="payment_status">Payment Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" name="payment_status" id="payment_status">
                            <option value="pending" {{ old('payment_status', $order->payment_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status', $order->payment_status ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ old('payment_status', $order->payment_status ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ old('payment_status', $order->payment_status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="success" {{ old('payment_status', $order->payment_status ?? '') == 'success' ? 'selected' : '' }}>Success</option>
                        </select>
                        @error('payment_status')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="order_notes">Order Note</label>
                        <textarea class="form-control @error('order_notes') is-invalid @enderror" name="order_notes" id="order_notes" rows="3" placeholder="Special instructions or notes...">{{ old('order_notes', $order->order_notes ?? '') }}</textarea>
                        @error('order_notes')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ✅ FEATURE 2: Dynamic Multiple Order Items --}}
            <hr class="my-3">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-primary mb-0">Order Items</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                    <i class="ti ti-plus me-1"></i> Add More
                </button>
            </div>

            {{-- Table header --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="orderItemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:280px;">Item Type & Product/Service <span class="text-danger">*</span></th>
                            <th style="min-width:200px;">Variant</th>
                            <th style="min-width:110px;">Qty <span class="text-danger">*</span></th>
                            <th style="min-width:130px;">Price <span class="text-danger">*</span></th>
                            <th style="min-width:130px;">Total Amount</th>
                            <th style="min-width:60px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">

                        {{-- Edit mode: pre-fill existing items from DB --}}
                        @if (isset($order) && $order->orderItems->count() > 0)
                        @foreach ($order->orderItems as $item)
                        @php
                        $isProduct = $item->product_type === 'product' || empty($item->product_type);
                        $rowTotal = ($item->product_qty ?? 1) * ($item->product_price ?? 0);
                        @endphp
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="order_item_id[]" value="{{ $item->id }}">
                                <input type="hidden" name="product_type[]" class="item-type-hidden" value="{{ $isProduct ? 'product' : 'service' }}">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge {{ $isProduct ? 'bg-primary' : 'bg-success' }} me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">
                                        {{ $isProduct ? 'Product' : 'Service' }}
                                    </span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[]" required>
                                    <option value="">-- Select {{ $isProduct ? 'Product' : 'Service' }} --</option>
                                    @if ($isProduct)
                                    @foreach ($products ?? [] as $product)
                                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }} data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                    @endforeach
                                    @else
                                    @foreach ($services ?? [] as $service)
                                    <option value="{{ $service->id }}" {{ $item->product_id == $service->id ? 'selected' : '' }} data-price="{{ $service->price }}">
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                @php
                                $variants = [];
                                $hasVariants = false;
                                if (!$isProduct) {
                                $selectedService = $services->firstWhere('id', $item->product_id);
                                if ($selectedService) {
                                $variants = $selectedService->variants;
                                $hasVariants = $variants->count() > 0;
                                }
                                }
                                @endphp
                                <select class="form-select variant-select {{ $hasVariants ? 'variant-select-required' : '' }}" name="variant_id[]" {{ $hasVariants ? '' : 'disabled' }}>
                                    <option value="">{{ $hasVariants ? '-- Select Variant --' : '-- No Variant --' }}</option>
                                    @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $item->variant_id == $variant->id ? 'selected' : '' }} data-price="{{ $variant->price }}">
                                        {{ $variant->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control item-qty" name="quantity[]"
                                    min="1" value="{{ $item->product_qty ?? 1 }}" placeholder="1" required>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-price" name="price[]"
                                        min="0" value="{{ $item->product_price ?? '' }}" placeholder="0.00" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-total" readonly value="{{ number_format($rowTotal, 2, '.', '') }}" placeholder="0.00">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove row">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        {{-- Default empty row for create mode --}}
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="order_item_id[]" value="">
                                <input type="hidden" name="product_type[]" class="item-type-hidden" value="product">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">Product</span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[]" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products ?? [] as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-select variant-select" name="variant_id[]" disabled>
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
                        </tr>
                        @endif

                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td colspan="2" class="fw-bold text-primary" style="font-size: 16px;">
                                <span id="grandTotalDisplay">$0.00</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {{-- END Order Items --}}

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl ?? 'admin.orders.index') }}" class="btn btn-soft-light">Cancel</a>
            </div>
    </x-form-wrapper>

    @push('scripts')
    <script>
        $(document).ready(function() {

            $('#user_id').select2({
                placeholder: 'Select a designation',
                allowClear: true,
            });

            $('#billing_country').select2({
                placeholder: 'Select a country',
                allowClear: true,
            });

            $('#billing_state').select2({
                placeholder: 'Select a state',
                allowClear: true,
            });

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

            // Toggle Item Type (Product <-> Service)
            $(document).on('click', '.item-type-badge', function() {
                var $row = $(this).closest('.order-item-row');
                var $hidden = $row.find('.item-type-hidden');
                var currentType = $hidden.val();
                var $select = $row.find('.product-select');
                var $variantSelect = $row.find('.variant-select');
                var $priceInput = $row.find('.item-price');

                if (currentType === 'product') {
                    // Switch to Service
                    $hidden.val('service');
                    $(this).text('Service').removeClass('bg-primary').addClass('bg-success');
                    populateServices($select);
                } else {
                    // Switch to Product
                    $hidden.val('product');
                    $(this).text('Product').removeClass('bg-success').addClass('bg-primary');
                    populateProducts($select);
                }

                // Reset variants and price
                $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('disabled', true).removeClass('variant-select-required');
                $priceInput.val('');
                calculateTotals();
            });

            // On Product/Service Selection Change
            $(document).on('change', '.product-select', function() {
                var $row = $(this).closest('.order-item-row');
                var $variantSelect = $row.find('.variant-select');
                var $priceInput = $row.find('.item-price');
                var type = $row.find('.item-type-hidden').val();
                var itemId = $(this).val();

                if (!itemId) {
                    $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('disabled', true).removeClass('variant-select-required');
                    $priceInput.val('');
                    calculateTotals();
                    return;
                }

                if (type === 'product') {
                    // Product has no variants
                    var price = $(this).find('option:selected').data('price') || '';
                    $priceInput.val(price);
                    $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('disabled', true).removeClass('variant-select-required');
                } else {
                    // Service - check if it has variants
                    var service = servicesData.find(s => String(s.id) === String(itemId));
                    if (service && service.variants && service.variants.length > 0) {
                        $variantSelect.empty().append('<option value="">-- Select Variant --</option>').prop('disabled', false).addClass('variant-select-required');
                        $.each(service.variants, function(i, variant) {
                            $variantSelect.append(`<option value="${variant.id}" data-price="${variant.price || ''}">${variant.name}</option>`);
                        });
                        $priceInput.val(''); // clear price since user must select variant
                    } else {
                        // Service without variants
                        var price = $(this).find('option:selected').data('price') || '';
                        $priceInput.val(price);
                        $variantSelect.empty().append('<option value="">-- No Variant --</option>').prop('disabled', true).removeClass('variant-select-required');
                    }
                }
                calculateTotals();
            });

            // On Variant Selection Change
            $(document).on('change', '.variant-select', function() {
                var $row = $(this).closest('.order-item-row');
                var $priceInput = $row.find('.item-price');
                var price = $(this).find('option:selected').data('price') || '';
                $priceInput.val(price);
                calculateTotals();
            });

            // Re-calculate totals on quantity or price change
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

            // Run on page load to set correct totals
            calculateTotals();

            // Add More Row
            $('#addItemBtn').on('click', function() {
                var newRow = `
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="order_item_id[]" value="">
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
                                <select class="form-select variant-select" name="variant_id[]" disabled>
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

            // Remove Row
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

            // ── JQUERY VALIDATION ─────────────────────────────────────────────
            $.validator.addClassRules('product-select', {
                required: true
            });
            $.validator.addClassRules('variant-select-required', {
                required: true
            });
            $.validator.addClassRules('item-qty', {
                required: true,
                min: 1,
                digits: true
            });
            $.validator.addClassRules('item-price', {
                required: true,
                min: 0,
                number: true
            });

            $('#orderForm').validate({
                rules: {
                    order_number: {
                        required: true,
                        maxlength: 255
                    },
                    date_time: {
                        required: true
                    },
                    subtotal: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    total_amount: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    status: {
                        required: true
                    },
                    payment_status: {
                        required: true
                    },
                    billing_first_name: {
                        required: true
                    },
                    billing_country: {
                        required: true
                    },
                    billing_state: {
                        required: true
                    },
                    billing_city: {
                        required: true
                    },
                    billing_address: {
                        required: true
                    },
                },
                messages: {
                    order_number: {
                        required: "Please enter order number."
                    },
                    date_time: {
                        required: "Please enter order date & time."
                    },
                    subtotal: {
                        required: "Please enter subtotal."
                    },
                    total_amount: {
                        required: "Please enter order total."
                    },
                    status: {
                        required: "Please select order status."
                    },
                    payment_status: {
                        required: "Please select payment status."
                    }
                },
                submitHandler: function(form) {
                    $(form).find('.variant-select:disabled').prop('disabled', false);
                    form.submit();
                },
                errorClass: 'text-danger small mt-1 d-block',
                errorElement: 'span',
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
                    } else if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $(document).on('change', '#user_id', function() {
                var userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: "{{ route('admin.orders.user.billing.details') }}",
                        type: "POST",
                        data: {
                            user_id: userId
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                var userData = response.data;

                                // $('#billing_first_name').val(userData.billing_first_name);
                                $('#billing_phone').val(userData.billing_phone);
                                $('#billing_city').val(userData.billing_city);
                                $('#billing_address').val(userData.billing_address);

                                if (userData.billing_country) {
                                    var countryId = userData.billing_country;
                                    var countryText = userData.billing_country_name || userData.billing_country;

                                    var countryOption = new Option(countryText, countryId, true, true);
                                    $('#billing_country').empty().append(countryOption).trigger('change');
                                } else {
                                    $('#billing_country').val(null).trigger('change');
                                }

                                if (userData.billing_state) {
                                    var stateId = userData.billing_state;
                                    var stateText = userData.billing_state_name || userData.billing_state;

                                    var stateOption = new Option(stateText, stateId, true, true);
                                    $('#billing_state').empty().append(stateOption).trigger('change');
                                } else {
                                    $('#billing_state').val(null).trigger('change');
                                }

                                $('#billing_first_name, #billing_phone, #billing_country, #billing_state, #billing_city, #billing_address').removeClass('is-invalid');

                            } else {
                                // console.log(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log("Error in fetching user details.");
                        }
                    });
                } else {
                    // $('#billing_first_name').val('');
                    $('#billing_phone').val('');
                    $('#billing_city').val('');
                    $('#billing_address').val('');

                    $('#billing_country').empty().val(null).trigger('change');
                    $('#billing_state').empty().val(null).trigger('change');
                }
            });

        });
    </script>
    @endpush

</x-master-layout>
