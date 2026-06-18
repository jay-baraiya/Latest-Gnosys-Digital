<section class="max-w-4xl bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
        <p class="text-gray-500 text-base">View and track your recent purchases and transactions.</p>
    </div>

    <div class="border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order Number</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm font-bold text-blue-600">${{ number_format($order->total_amount, 2) }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-600 uppercase font-medium">{{ $order->payment_method ?? 'N/A' }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $order->payment_status === 'refunded' ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="text-sm text-gray-500 font-mono">{{ $order->transaction_id ?? '-' }}</span></td>

                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                <button type="button" data-id="{{ encrypt($order->id) }}" class="view-history-btn inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-sm font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                    </svg>
                                    History
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-lg font-medium text-gray-900">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</section>

<div id="orderModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900" id="modal-title">Order History Details</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto" id="modalContent">
                    <div class="flex justify-center items-center py-10" id="loadingState">
                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button type="button" class="close-modal bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-medium rounded-lg px-5 py-2 text-sm transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
        $(document).ready(function() {
            // Open Modal and fetch data
            $('.view-history-btn').on('click', function(e) {
                e.preventDefault();
                let encryptedId = $(this).data('id');

                // Show modal and reset content to loading
                $('#orderModal').removeClass('hidden');
                $('#modalContent').html(`
                    <div class="flex justify-center items-center py-10">
                        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                `);

                // AJAX Request
                $.ajax({
                    url: '{{ route('order.item.list') }}',
                    type: 'POST',
                    data: {
                        order_id: encryptedId,
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#modalContent').html(response.html);
                        } else {
                            $('#modalContent').html('<p class="text-red-500 text-center py-4">Failed to load order items.</p>');
                        }
                    },
                    error: function() {
                        $('#modalContent').html('<p class="text-red-500 text-center py-4">Something went wrong. Please try again.</p>');
                    }
                });
            });

            // Close Modal Logic
            $('.close-modal').on('click', function() {
                $('#orderModal').addClass('hidden');
            });
        });
    </script>
@endsection
