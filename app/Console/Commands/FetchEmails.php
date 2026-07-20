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
        $host = config('imap.accounts.default.host');
        if (empty($host)) {
            $this->warn('IMAP/POP3 host is not configured. Skipping email fetching.');
            return Command::SUCCESS;
        }

        try {
            // Connect to the default account defined in config/imap.php (and .env)
            $client = Client::account('default');
            $client->connect();

            // POP3 only supports INBOX. For IMAP, you could iterate over folders, but INBOX is standard.
            $folder = $client->getFolder('INBOX');

            // Fetch messages depending on protocol (POP3 doesn't support 'unseen' flags)
            $protocol = config('imap.accounts.default.protocol');
            if ($protocol === 'pop3') {
                $messages = $folder->query()->all()->get();
            } else {
                $messages = $folder->query()->unseen()->get();
            }

            $count = $messages->count();
            $this->info("Found {$count} email(s) to process.");
            
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
                // 2) Create Ticket in Database
                // ---------------------------------------------------------
                $user = \App\Models\User::where('email', $from)->first();
                
                if (!$user) {
                    $newName = ($fromAddresses->count() > 0 && !empty($fromAddresses[0]->personal)) ? $fromAddresses[0]->personal : 'Unknown Sender';
                    
                    $user = \App\Models\User::create([
                        'name'     => $newName,
                        'email'    => $from,
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(10)),
                        'status'   => '1',
                    ]);

                    if ($user) {
                        \App\Models\UserRole::create([
                            'user_id' => $user->id,
                            'role_id' => \App\Models\User::IS_BUYER,
                        ]);
                    }
                }

                $userId = $user ? $user->id : null;
                $name = $user ? $user->name : 'Unknown Sender';

                \App\Models\Ticket::create([
                    'ticket_number'    => 'TCK-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'datetime'         => now(),
                    'user_id'          => $userId,
                    'name'             => $name,
                    'email'            => $from,
                    'subject'          => $subject,
                    'body'             => $body,
                    'ticket_source'    => 'email',
                    'status'           => 'pending',
                    'ticket_status'    => 'open',
                ]);

                // ---------------------------------------------------------
                // 3) Delete message (Optional for POP3)
                // ---------------------------------------------------------
                // Uncomment if you want to delete it from the server so it isn't fetched again:
                $message->delete();
                
                $this->line("Logged email: {$subject} from {$from}");
            }

            $this->info('Email fetching completed successfully.');

        } catch (Exception $e) {
            $this->error('An error occurred while fetching emails: ' . $e->getMessage());
            Log::error('FetchEmails Command Error: ' . $e->getMessage());
            
            return Command::FAILURE;
        } finally {
            if (function_exists('imap_errors')) {
                imap_errors();
            }
        }

        return Command::SUCCESS;
    }
}
