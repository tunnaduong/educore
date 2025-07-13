<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled notifications...');

        $scheduledNotifications = Notification::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->where('is_read', false)
            ->get();

        if ($scheduledNotifications->isEmpty()) {
            $this->info('No scheduled notifications to send.');
            return 0;
        }

        $this->info("Found {$scheduledNotifications->count()} scheduled notifications to send.");

        foreach ($scheduledNotifications as $notification) {
            try {
                // Log the notification being sent
                Log::info("Sending scheduled notification: {$notification->title}", [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'class_id' => $notification->class_id,
                    'scheduled_at' => $notification->scheduled_at,
                ]);

                $this->line("✓ Sent: {$notification->title}");
                
                // You can add additional logic here like:
                // - Sending email notifications
                // - Sending push notifications
                // - Broadcasting to WebSocket
                // - etc.
                
            } catch (\Exception $e) {
                $this->error("Failed to send notification {$notification->id}: {$e->getMessage()}");
                Log::error("Failed to send scheduled notification", [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info('Scheduled notifications processing completed.');
        return 0;
    }
}
