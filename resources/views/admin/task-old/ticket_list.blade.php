@if($order->tickets->isEmpty())
    <div class="alert alert-info text-center mb-0">
        No tickets found for this order.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ticket #</th>
                    <th>Product Details</th>
                    <th>Price</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($order->tickets as $ticket)
                    @php
                        $productName = $ticket->orderItems->product_name ?? '-';
                        $productType = $ticket->orderItems->product_type ?? '';
                        $variantName = $ticket->orderItems->variant_name ?? '';

                        $productPrice = $ticket->orderItems->product_price ?? 0;

                        $developerName = $ticket->developer->name ?? 'Unassigned';
                        $developerRoleName = $ticket->developer?->role->name ?? '';

                        $statusClass = match($ticket->status) {
                            'pending', 'assign_requested',
                            'cancel_requested' => 'badge bg-warning text-dark',
                            'assigned',
                            'in_progress' => 'badge bg-info text-dark',
                            'completed' => 'badge bg-success',
                            'assign_not_accepted',
                            'cancelled' => 'badge bg-danger',
                            'refund' => 'badge bg-secondary',
                            default => 'badge bg-light text-dark',
                        };
                        $statusText = ucwords(str_replace('_', ' ', $ticket->status));
                    @endphp
                    <tr>
                        <td class="fw-semibold text-primary">{{ $ticket->ticket_number }}</td>
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

                        <td>
                            <div class="dev-section">
                                @if($developerName === 'Unassigned')
                                    <span class="text-muted fst-italic assign-dev cursor-pointer" data-ticket-id="{{ $ticket->id }}">Unassigned</span>
                                @else
                                    <span class="fw-medium text-dark cursor-pointer assign-dev" data-ticket-id="{{ $ticket->id }}">{{ $developerName .' ( '. $developerRoleName .' ) ' }}</span>
                                @endif
                            </div>
                        </td>

                        <td><span class="{{ $statusClass }}">{{ $statusText }}</span></td>

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
