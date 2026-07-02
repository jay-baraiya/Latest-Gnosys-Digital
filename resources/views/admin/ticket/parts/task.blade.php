<form action="{{ route('admin.tickets.store.task') }}" method="POST" id="taskForm">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="text-primary mb-0">Task List</h5>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
            <i class="ti ti-plus me-1"></i> Add More
        </button>
    </div>

    <input type="hidden" name="tab" value="task-form">
    <input type="hidden" name="ticket_id" value="{{ isset($ticket) ? $ticket->id : '' }}">

    {{-- Table header --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="orderItemsTable">
            <thead class="table-light">
                <tr>
                    <th style="min-width:280px;">Product/Service <span class="text-danger">*</span></th>
                    <th style="min-width:200px;">Variant</th>
                    <th style="min-width:200px;">Due Date <span class="text-danger">*</span></th>
                    <th style="min-width:200px;">Department <span class="text-danger">*</span></th>
                    <th style="min-width:200px;">Assign <span class="text-danger">*</span></th>
                    <th style="min-width:110px;">Qty <span class="text-danger">*</span></th>
                    <th style="min-width:130px;">Price <span class="text-danger">*</span></th>
                    <th style="min-width:130px;">Total Amount</th>
                    <th style="min-width:60px;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="orderItemsBody">

                {{-- Edit mode: pre-fill existing items from DB --}}
                @if (isset($tasks) && $tasks->count() > 0)
                    @foreach ($tasks as $index => $item)
                        @php
                            $isProduct = $item->product_type === 'product' || empty($item->product_type);
                            $rowTotal = ($item->product_qty ?? 1) * ($item->product_price ?? 0);
                        @endphp
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="task_id[{{ $index }}]" value="{{ $item->id }}">
                                <input type="hidden" name="product_type[{{ $index }}]" class="item-type-hidden"
                                    value="{{ $isProduct ? 'product' : 'service' }}">
                                <div class="d-flex align-items-center mb-2">
                                    <span
                                        class="badge {{ $isProduct ? 'bg-primary' : 'bg-success' }} me-2 item-type-badge pointer"
                                        style="cursor:pointer;" title="Click to toggle type">
                                        {{ $isProduct ? 'Product' : 'Service' }}
                                    </span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click
                                        to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[{{ $index }}]"
                                    required>
                                    <option value="">-- Select
                                        {{ $isProduct ? 'Product' : 'Service' }} --</option>
                                    @if ($isProduct)
                                        @foreach ($products ?? [] as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $item->product_id == $product->id ? 'selected' : '' }}
                                                data-price="{{ $product->price }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach ($services ?? [] as $service)
                                            <option value="{{ $service->id }}"
                                                {{ $item->product_id == $service->id ? 'selected' : '' }}
                                                data-price="{{ $service->price }}">
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
                                <select
                                    class="form-select variant-select {{ $hasVariants ? 'variant-select-required' : '' }}"
                                    name="variant_id[{{ $index }}]" {{ $hasVariants ? '' : 'readonly' }}>
                                    <option value="">
                                        {{ $hasVariants ? '-- Select Variant --' : '-- No Variant --' }}
                                    </option>
                                    @foreach ($variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            {{ $item->variant_id == $variant->id ? 'selected' : '' }}
                                            data-price="{{ $variant->price }}">
                                            {{ $variant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control due-date"
                                    name="due_date[{{ $index }}]"
                                    value="{{ !empty($item->due_date) ? \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') : now()->format('Y-m-d') }}"
                                    required>
                            </td>
                            <td>
                                <select name="department_id[{{ $index }}]" class="department-id form-control">
                                    <option value="">Select Department</option>
                                    @if ($departments->isNotEmpty())
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $item->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select name="assign_id[{{ $index }}]" class="assign-id form-control">
                                    <option value="">Select Developer</option>
                                    @if ($developers->isNotEmpty())
                                        @foreach ($developers as $developer)
                                            <option value="{{ $developer->id }}"
                                                {{ $item->assign_id == $developer->id ? 'selected' : '' }}>
                                                {{ $developer->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control item-qty"
                                    name="quantity[{{ $index }}]" min="1"
                                    value="{{ $item->quantity ?? 1 }}" placeholder="1" required>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-price"
                                        name="price[{{ $index }}]" min="0"
                                        value="{{ $item->price ?? '' }}" placeholder="0.00" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-total" readonly
                                        value="{{ number_format($rowTotal, 2, '.', '') }}" placeholder="0.00">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"
                                    title="Remove row">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- Default empty row for create mode --}}
                    <tr class="order-item-row">
                        <td>
                            <input type="hidden" name="task_id[0]" value="">
                            <input type="hidden" name="product_type[0]" class="item-type-hidden" value="product">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2 item-type-badge pointer" style="cursor:pointer;"
                                    title="Click to toggle type">Product</span>
                                <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click
                                    to switch</small>
                            </div>
                            <select class="form-select product-select" name="product_id[0]" required>
                                <option value="">-- Select Product --</option>
                                @foreach ($products ?? [] as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-select variant-select" name="variant_id[0]" readonly>
                                <option value="">-- No Variant --</option>
                            </select>
                        </td>
                        <td>
                            <input type="date" class="form-control due-date" name="due_date[0]" required>
                        </td>
                        <td>
                            <select name="department_id[0]" class="department-id form-select">
                                <option value="">Select Department</option>
                                @if ($departments->isNotEmpty())
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td>
                            <select name="assign_id[0]" class="assign-id form-select">
                                <option value="">Select Developer</option>
                                @if ($developers->isNotEmpty())
                                    @foreach ($developers as $developer)
                                        <option value="{{ $developer->id }}">{{ $developer->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control item-qty" name="quantity[0]" min="1"
                                value="1" placeholder="1" required>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control item-price" name="price[0]"
                                    min="0" value="" placeholder="0.00" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control item-total" readonly
                                    value="" placeholder="0.00">
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn"
                                title ="Remove row">
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
    <div class="text-end mt-3">
        <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
        <button type="submit" class="btn btn-primary">Generate Ticket</button>
    </div>
</form>
