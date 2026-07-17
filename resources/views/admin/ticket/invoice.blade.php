<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #555;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
        }
        .details-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .details-table td strong {
            display: inline-block;
            width: 100px;
        }
        .tasks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .tasks-table th, .tasks-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .tasks-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        .total-container {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice</h2>
        <p>Ticket: {{ $ticket->ticket_number }}</p>
    </div>

    <div class="details-container">
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong>Client Name:</strong> {{ $ticket->name ?? ($ticket->user->name ?? 'N/A') }}
                </td>
                <td style="width: 50%;">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($ticket->datetime)->format('d-m-Y H:i') }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Email:</strong> {{ $ticket->email ?? ($ticket->user->email ?? 'N/A') }}
                </td>
                <td>
                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Subject:</strong> {{ $ticket->subject ?? 'N/A' }}
                </td>
                <td>
                    <strong>Department:</strong> {{ $ticket->department->name ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Priority:</strong> {{ ucfirst($ticket->priority) }}
                </td>
                <td>
                </td>
            </tr>
        </table>
    </div>

    <h3>Task Details</h3>
    <table class="tasks-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Type</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($ticket->tasks as $index => $task)
                @php 
                    $lineTotal = $task->quantity * $task->price;
                    $grandTotal += $lineTotal;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst($task->product_type) }}</td>
                    <td>{{ $task->product_id }}</td>
                    <td>{{ $task->quantity }}</td>
                    <td>{{ number_format($task->price, 2) }}</td>
                    <td>{{ number_format($lineTotal, 2) }}</td>
                </tr>
            @endforeach
            @if($ticket->tasks->isEmpty())
                <tr>
                    <td colspan="6" style="text-align:center; color: #777;">No tasks found for this ticket.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="total-container">
        Grand Total: {{ number_format($grandTotal, 2) }}
    </div>
</body>
</html>
