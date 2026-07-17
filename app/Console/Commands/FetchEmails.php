<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch unread emails from the configured IMAP/POP3 account and log them.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fetch emails...');

        try {
            // Connect to the default account defined in config/imap.php (and .env)
            $client = Client::account('default');
            $client->connect();

            // POP3 only supports INBOX. For IMAP, you could iterate over folders, but INBOX is standard.
            $folder = $client->getFolder('INBOX');

            // Fetch unread (unseen) messages
            $messages = $folder->query()->unseen()->get();

            $count = $messages->count();
            $this->info("Found {$count} unread email(s).");
            
            if ($count === 0) {
                return Command::SUCCESS;
            }

            foreach ($messages as $message) {
                // Safely get email properties
                $subject = $message->getSubject();
                
                // getFrom() returns a Collection of address objects
                $fromAddresses = $message->getFrom();
                $from = $fromAddresses->count() > 0 ? $fromAddresses[0]->mail : 'Unknown Sender';
                
                // Get body (prefer text, fallback to html)
                $body = $message->getTextBody();
                if (empty($body)) {
                    $body = $message->getHTMLBody();
                }
                
                $messageId = $message->getMessageId();
                $date = $message->getDate();

                // ---------------------------------------------------------
                // 1) Log the email data to laravel.log
                // ---------------------------------------------------------
                Log::info('--- New Unread Email Fetched ---', [
                    'message_id' => $messageId,
                    'from'       => $from,
                    'subject'    => $subject,
                    'date'       => $date,
                    // If the body is very large, limit it in logs to prevent massive log files
                    'body'       => \Illuminate\Support\Str::limit($body, 1000) 
                ]);

                // ---------------------------------------------------------
                // 2) Future functionality: Store in Database
                // ---------------------------------------------------------
                // To easily store this in the database later, you can uncomment 
                // and modify the code below once you have an Email model and table.
                
                /*
                \App\Models\Email::create([
                    'message_id'  => $messageId,
                    'sender'      => $from,
                    'subject'     => $subject,
                    'body'        => $body,
                    'received_at' => $date,
                ]);
                */

                // ---------------------------------------------------------
                // 3) Mark message as seen / read (Optional)
                // ---------------------------------------------------------
                // Uncomment if you want to mark it as read so it isn't fetched again:
                $message->setFlag(['Seen']);
                
                $this->line("Logged email: {$subject} from {$from}");
            }

            $this->info('Email fetching completed successfully.');

        } catch (Exception $e) {
            $this->error('An error occurred while fetching emails: ' . $e->getMessage());
            Log::error('FetchEmails Command Error: ' . $e->getMessage());
            
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
