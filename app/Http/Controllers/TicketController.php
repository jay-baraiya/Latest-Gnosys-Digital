<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function cancelUserTicket(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        $ticketId = decrypt($request->id);

        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = 'cancelled';
        $ticket->cancel_reason = $request->reason;
        $ticket->save();

        return redirect()->back()->withFragment('ticket')->with('success', 'Ticket has been cancelled successfully.');
    }
}
