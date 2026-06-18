<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Type</th>
            <th>Balance Before</th>
            <th>Transfer Amount</th>
            <th>Balance After</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        @if (!empty($historys->histories))
            @foreach($historys->histories as $key => $history)
                <tr>
                    <td>{{ $key + 1 }}</td>

                    <td>
                        {{ $history->date }}
                    </td>

                    <td>
                        {{ ucfirst($history->type) }}
                    </td>

                    <td>
                        {{ number_format($history->balance_before, 2) }}
                    </td>

                    <td>
                        {{ number_format($history->transfer_amount, 2) }}
                    </td>

                    <td>
                        {{ number_format($history->balance_after, 2) }}
                    </td>

                    <td>
                        {{ ucfirst($history->status) }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">
                    No Transaction History Found
                </td>
            </tr>
        @endif

    </tbody>
</table>
