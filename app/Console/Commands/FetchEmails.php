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
        try {
            $departments = \App\Models\Department::whereNotNull('email_id')->with('emailAccount')->get();
            $totalFetched = 0;

            foreach ($departments as $department) {
                $emailAccount = $department->emailAccount;
                if ($emailAccount && $emailAccount->status == 1) {
                    $this->info("Fetching emails for department: {$department->name} using {$emailAccount->email}");
                    $count = $this->fetchFromCustomAccount($emailAccount, $department->id);
                    $totalFetched += $count;
                }
            }

            // "jo tema koi email get na thai to je email setting mathi config m aave che te mathi email fetch kari ne ticket generat ekarvi"
            if ($totalFetched === 0) {
                $this->info("No emails fetched from department accounts. Fetching from default config...");
                $this->fetchFromDefaultConfig();
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

    protected function fetchFromCustomAccount($emailAccount, $departmentId)
    {
        try {
            \App\Helpers\Helper::setDynamicImapConfig($emailAccount, 'default');
            
            $client = \Webklex\IMAP\Facades\Client::account('default');
            $client->connect();
            
            return $this->processClientMessages($client, $emailAccount->protocol, $departmentId);
        } catch (Exception $e) {
            $this->warn("Failed to fetch for account {$emailAccount->email}: " . $e->getMessage());
            Log::error("FetchEmails Custom Account Error ({$emailAccount->email}): " . $e->getMessage());
            return 0;
        }
    }

    protected function fetchFromDefaultConfig()
    {
        $host = config('imap.accounts.default.host');
        if (empty($host)) {
            $this->warn('IMAP/POP3 host is not configured. Skipping default email fetching.');
            return 0;
        }

        try {
            $client = Client::account('default');
            $client->connect();
            $protocol = config('imap.accounts.default.protocol');
            
            return $this->processClientMessages($client, $protocol, null);
        } catch (Exception $e) {
            $this->warn("Failed to fetch for default account: " . $e->getMessage());
            Log::error('FetchEmails Default Account Error: ' . $e->getMessage());
            return 0;
        }
    }

    protected function processClientMessages($client, $protocol, $departmentId = null)
    {
        // POP3 only supports INBOX. For IMAP, you could iterate over folders, but INBOX is standard.
        $folder = $client->getFolder('INBOX');

        // Fetch messages depending on protocol (POP3 doesn't support 'unseen' flags)
        if ($protocol === 'pop3') {
            $messages = $folder->query()->all()->get();
        } else {
            $messages = $folder->query()->unseen()->get();
        }

        $count = $messages->count();
        $this->info("Found {$count} email(s) to process.");
        
        if ($count === 0) {
            return 0;
        }

        foreach ($messages as $message) {
            // Safely get email properties
            $subject = $message->getSubject();
            
            // getFrom() returns a Collection of address objects
            $fromAddresses = $message->getFrom();
            $from = $fromAddresses->count() > 0 ? $fromAddresses[0]->mail : 'Unknown Sender';
            
            // Get body (prefer html, fallback to text)
            $body = $message->getHTMLBody();
            if (empty($body)) {
                $body = $message->getTextBody();
                if (!empty($body)) {
                    $body = nl2br(htmlspecialchars($body));
                }
            }
            
            $messageId = $message->getMessageId();
            $date = $message->getDate();

            // ---------------------------------------------------------
            // 1) Log the email data to laravel.log
            // ---------------------------------------------------------
            // Log::info('--- New Unread Email Fetched ---', [
            //     'message_id' => $messageId,
            //     'from'       => $from,
            //     'subject'    => $subject,
            //     'date'       => $date,
            //     // If the body is very large, limit it in logs to prevent massive log files
            //     'body'       => \Illuminate\Support\Str::limit($body, 1000) 
            // ]);

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

            $ticketData = [
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
                'priority'         => 'Low'
            ];

            if ($departmentId) {
                $ticketData['department_id'] = $departmentId;
            }

            \App\Models\Ticket::create($ticketData);

            // ---------------------------------------------------------
            // 3) Delete message (Optional for POP3)
            // ---------------------------------------------------------
            // Uncomment if you want to delete it from the server so it isn't fetched again:
            $message->delete();
            
            $this->line("Logged email: {$subject} from {$from}");
        }

        return $count;
    }
}
