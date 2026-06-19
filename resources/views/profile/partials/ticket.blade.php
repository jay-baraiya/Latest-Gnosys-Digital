<section class="max-w-6xl mx-auto bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8 mt-6 relative">

    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Tickets</h1>
            <p class="text-gray-500 text-base">Track your digital service requests and assignments.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($tickets as $order)
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Order Reference</div>
                        <div class="text-lg font-bold text-blue-600">{{ $order->order_number }}</div>
                    </div>

                    <div class="sm:text-right">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tickets in Order</div>
                        <div class="text-sm font-medium text-gray-800">
                            {{ count($order->tickets->where('status', '!=', 'cancelled') ?? []) }} Active Ticket(s)
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ticket Number</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product / Item</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned Developer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($order->tickets as $ticket)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">{{ $ticket->ticket_number }}</span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($ticket?->orderItems)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $ticket->orderItems->product_name ?? 'Item' }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No specific item</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($ticket->datetime)->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($ticket->datetime)->format('h:i A') }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ticket->developer_id)
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">
                                                    {{ substr($ticket->developer->name ?? 'D', 0, 1) }}
                                                </div>
                                                <span class="text-sm text-gray-800 font-medium">{{ $ticket->developer->name ?? 'Developer' }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">Pending Assignment</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'assign_requested' => 'bg-blue-100 text-blue-700',
                                                'assigned' => 'bg-indigo-100 text-indigo-700',
                                                'assign_not_accepted' => 'bg-orange-100 text-orange-700',
                                                'in_progress' => 'bg-purple-100 text-purple-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                                'cancel_requested' => 'bg-red-50 text-red-600 border border-red-200',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'refund' => 'bg-gray-200 text-gray-800',
                                            ];
                                            $badgeClass = $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end items-center gap-2">
                                        <button type="button"
                                            class="view-ticket-btn inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-sm font-semibold"
                                            data-number="{{ $ticket->ticket_number }}"
                                            data-product="{{ $ticket->orderItems->product_name ?? 'N/A' }}"
                                            data-price="${{ number_format($ticket->orderItems->product_price ?? 0, 2) }}"
                                            data-date="{{ \Carbon\Carbon::parse($ticket->datetime)->format('M d, Y h:i A') }}"
                                            data-developer="{{ $ticket->developer->name ?? 'Pending Assignment' }}"
                                            data-status="{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}"
                                            data-reason="{{ $ticket->cancel_reason ?? '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                            </svg>
                                            Details
                                        </button>

                                        @if(in_array($ticket->status, ['pending', 'assigned', 'in_progress', 'assign_requested']))
                                            <button type="button" data-id="{{ encrypt($ticket->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors text-sm font-semibold cancle-ticket-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M18 6l-12 12"></path>
                                                    <path d="M6 6l12 12"></path>
                                                </svg>
                                                Cancel
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">No tickets found for this order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white px-6 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-lg font-medium text-gray-900">No tickets found</p>
                    <p class="text-sm text-gray-500">You don't have any active digital service tickets.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(method_exists($tickets, 'hasPages') && $tickets->hasPages())
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    @endif

</section>

<div id="ticketDetailsModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm close-details-modal" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block w-full px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:p-6 border border-gray-100">

            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" class="text-gray-400 bg-white rounded-md hover:text-gray-600 focus:outline-none close-details-modal">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-blue-50 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15 5l0 2"></path>
                        <path d="M15 11l0 2"></path>
                        <path d="M15 17l0 2"></path>
                        <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2"></path>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-xl font-bold leading-6 text-gray-900 mb-1" id="detail-ticket-number">Ticket Details</h3>
                    <div class="mt-4 border-t border-gray-100 pt-4">

                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold" id="detail-status"></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                                <dd class="mt-1 text-sm text-gray-900" id="detail-date"></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Assigned Developer</dt>
                                <dd class="mt-1 text-sm text-gray-900" id="detail-developer"></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Product / Item</dt>
                                <dd class="mt-1 text-sm text-gray-900" id="detail-product"></dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Product Price</dt>
                                <dd class="mt-1 text-sm text-gray-900" id="detail-price"></dd>
                            </div>

                            <div class="sm:col-span-2 hidden" id="detail-reason-container">
                                <dt class="text-sm font-medium text-red-500">Cancellation Reason</dt>
                                <dd class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded-md border border-red-100" id="detail-reason"></dd>
                            </div>
                        </dl>

                    </div>
                </div>
            </div>

            <div class="mt-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm close-details-modal">
                    Close Details
                </button>
            </div>
        </div>
    </div>
</div>

<div id="cancelTicketModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50 backdrop-blur-sm cancel-modal-backdrop" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">

            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button type="button" class="text-gray-400 bg-white rounded-md hover:text-gray-600 focus:outline-none close-cancel-modal">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Cancel Ticket</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4">Are you sure you want to cancel this ticket? Please provide a reason below.</p>

                        <form id="cancelTicketForm" action="{{ route('tickets.cancel') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="modal-ticket-id">
                            <textarea name="reason" id="modal-ticket-reason" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Type your reason here..." required></textarea>
                        </form>

                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                <button type="submit" form="cancelTicketForm" class="inline-flex justify-center w-full px-4 py-2 text-base font-semibold text-white bg-red-600 border border-transparent rounded-lg shadow-sm hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                    Yes, cancel it
                </button>
                <button type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm close-cancel-modal">
                    Go back
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            // ==========================================
            // DETAILS MODAL LOGIC
            // ==========================================
            $(document).on('click', '.view-ticket-btn', function(e) {
                e.preventDefault();

                // Extract data attributes
                let number = $(this).data('number');
                let product = $(this).data('product');
                let price = $(this).data('price'); // Added price
                let date = $(this).data('date');
                let developer = $(this).data('developer');
                let status = $(this).data('status');
                let reason = $(this).data('reason');

                // Populate Modal Fields
                $('#detail-ticket-number').text('Ticket Details - ' + number);
                $('#detail-product').text(product);
                $('#detail-price').text(price); // Set price
                $('#detail-date').text(date);
                $('#detail-developer').text(developer);
                $('#detail-status').text(status);

                // Handle conditional Cancel Reason block
                if(reason && reason.trim() !== '') {
                    $('#detail-reason').text(reason);
                    $('#detail-reason-container').removeClass('hidden');
                } else {
                    $('#detail-reason-container').addClass('hidden');
                }

                // Show Modal
                $('#ticketDetailsModal').removeClass('hidden').fadeIn(200);
            });

            // Close Details Modal
            $('.close-details-modal').on('click', function() {
                $('#ticketDetailsModal').fadeOut(200, function() {
                    $(this).addClass('hidden');
                });
            });

            // ==========================================
            // CANCEL MODAL LOGIC
            // ==========================================
            $(document).on('click', '.cancle-ticket-btn', function(e) {
                e.preventDefault();

                let ticketId = $(this).attr('data-id');
                $('#modal-ticket-id').val(ticketId);
                $('#modal-ticket-reason').val('');

                $('#cancelTicketModal').removeClass('hidden').fadeIn(200);
            });

            // Close Cancel Modal
            $('.close-cancel-modal, .cancel-modal-backdrop').on('click', function() {
                $('#cancelTicketModal').fadeOut(200, function() {
                    $(this).addClass('hidden');
                });
            });
        });
    </script>
@endpush
