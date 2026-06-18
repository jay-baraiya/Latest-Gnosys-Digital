<div class="space-y-4">
    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4 flex justify-between items-center">
        <div>
            <p class="text-sm font-semibold text-blue-800">Order ID: #{{ $order->order_number }}</p>
            <p class="text-xs text-blue-600 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-blue-600 uppercase font-bold tracking-wide">Grand Total</p>
            <p class="text-lg font-bold text-blue-800">${{ number_format($order->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product Name</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">

                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $item->product_name }}</div>

                                <div class="flex items-center gap-2 mt-1.5">
                                    @if($item->variant_name)
                                        <span class="text-[11px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200">
                                            {{ $item->variant_name }}
                                        </span>
                                    @endif

                                    @if($item->product_type)
                                        <span class="text-[11px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                            {{ ucfirst($item->product_type) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-gray-700 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-200">
                                    {{ $item->product_qty }}
                                </span>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-gray-600">
                                ${{ number_format($item->product_price, 2) }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                                ${{ number_format($item->total_amount, 2) }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
