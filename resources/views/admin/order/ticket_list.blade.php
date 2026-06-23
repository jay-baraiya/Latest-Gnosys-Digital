@if($order->tickets->isEmpty())
<div class="alert alert-info text-center mb-0">
    No tickets found for this order.
</div>
@else

<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Product Details</th>
                <th>Price</th>
                {{-- <th>Action</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $order)
            @php
            $productName = $order->product_name ?? '-';
            $productType = $order->product_type ?? '';
            $variantName = $order->variant_name ?? '';

            $productPrice = $order->product_price ?? 0;
            @endphp
            <tr>
                <td>
                    <strong>{{ $productName }}</strong>

                    @if(!empty($productType))
                    <span class="badge bg-light text-secondary border px-1 py-0 ms-1">{{ ucfirst($productType) }}</span>
                    @endif

                    @if(!empty($variantName))
                    <br>
                    <small class="text-muted">Variant: {{ $variantName }}</small>
                    @endif
                </td>
                <td class="text-nowrap">${{ number_format($productPrice, 2) }}</td>

                {{-- <td>
                            <div class="dropdown table-action">
                                <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item wallet-action-btn" href="#" data-action="approve">
                                        <i class="ti ti-user-check text-success"></i> Assign Developer
                                    </a>
                                </div>
                            </div>
                        </td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif