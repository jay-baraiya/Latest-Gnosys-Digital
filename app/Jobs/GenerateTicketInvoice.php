<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SystemNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateTicketInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticket;
    protected $fileName;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(Ticket $ticket, $fileName, $userId)
    {
        $this->ticket = $ticket;
        $this->fileName = $fileName;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');

        // Load relationships if not loaded
        $this->ticket->loadMissing(['user', 'department', 'tasks']);

        $pdf = Pdf::loadView('admin.ticket.invoice', [
            'ticket' => $this->ticket
        ]);

        Storage::disk('public')->put($this->fileName, $pdf->output());

        // Send Notification
        $user = User::find($this->userId);
        if ($user) {
            $user->notify(new SystemNotification([
                'title' => 'Invoice PDF Ready',
                'message' => 'Ticket invoice #' . $this->ticket->ticket_number . ' is ready for download.',
                'type' => 'export_pdf',
                'file_name' => $this->fileName,
                'download_url' => true,
                'file_path' => $this->fileName, // Pass the path for the generic download route if needed.
                'user_name' => $user->name,
                'export_time' => now()->toDateTimeString(),
            ]));
        }
    }
}
