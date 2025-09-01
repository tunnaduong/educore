<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendZaloNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zalo:send-notifications {--type=all} {--limit=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi thông báo qua Zalo ZNS theo lịch';

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        $this->info('Bắt đầu gửi thông báo Zalo...');
        $this->info("Loại: {$type}, Giới hạn: {$limit}");

        try {
            if ($type === 'schedule' || $type === 'all') {
                $this->sendScheduleReminders();
            }

            if ($type === 'notification' || $type === 'all') {
                $this->sendScheduledNotifications($limit);
            }

            $this->info('Hoàn thành gửi thông báo Zalo!');
        } catch (Exception $e) {
            $this->error("Lỗi: " . $e->getMessage());
            Log::error('SendZaloNotifications: Command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Gửi nhắc lịch học
     */
    protected function sendScheduleReminders()
    {
        $this->info('Đang gửi nhắc lịch học...');

        $result = $this->notificationService->sendBulkScheduleReminders();

        if ($result['success']) {
            $this->info("✓ Nhắc lịch học: {$result['total_success']}/{$result['total_sent']} thành công");
        } else {
            $this->error("✗ Nhắc lịch học thất bại: " . ($result['error'] ?? 'Lỗi không xác định'));
        }
    }

    /**
     * Gửi thông báo theo lịch
     */
    protected function sendScheduledNotifications($limit)
    {
        $this->info('Đang gửi thông báo theo lịch...');

        // Lấy các thông báo đã đến lịch gửi
        $notifications = Notification::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->where('is_read', false)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->limit($limit)
            ->get();

        $this->info("Tìm thấy {$notifications->count()} thông báo cần gửi");

        $successCount = 0;
        $failedCount = 0;

        foreach ($notifications as $notification) {
            $this->info("Đang gửi thông báo ID: {$notification->id} - {$notification->title}");

            $result = $this->notificationService->sendZaloNotification($notification);

            if ($result['success']) {
                $successCount++;
                $this->info("✓ Thành công: {$result['success_count']}/{$result['total']} người nhận");

                // Đánh dấu thông báo đã được gửi
                $notification->update([
                    'is_read' => true,
                    'zalo_sent' => true,
                    'zalo_sent_at' => now(),
                    'zalo_response' => json_encode($result)
                ]);
            } else {
                $failedCount++;
                $this->error("✗ Thất bại: " . ($result['error'] ?? 'Lỗi không xác định'));

                // Log lỗi
                $notification->update([
                    'zalo_response' => json_encode($result)
                ]);
            }

            // Chờ 1 giây giữa các lần gửi để tránh rate limit
            sleep(1);
        }

        $this->info("Hoàn thành! Thành công: {$successCount}, Thất bại: {$failedCount}");

        Log::info('SendZaloNotifications: Scheduled notifications sent', [
            'total_notifications' => $notifications->count(),
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ]);
    }
}
