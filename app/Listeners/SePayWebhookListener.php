<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Log;
use SePay\SePay\Events\SePayWebhookEvent;

class SePayWebhookListener
{
    protected $licenseService;

    /**
     * Create the event listener.
     */
    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle the event.
     * Xử lý webhook từ SEPay và kích hoạt license
     */
    public function handle(SePayWebhookEvent $event): void
    {
        // Chỉ xử lý giao dịch tiền vào
        if ($event->sePayWebhookData->transferType !== 'in') {
            return;
        }

        try {
            // $event->info chứa thông tin được parse từ pattern (ví dụ: user_id)
            // Pattern mặc định là "SE" nên nội dung "SE123456" sẽ parse được "123456"
            $userId = $event->info;

            if (! $userId) {
                Log::warning('SePayWebhookListener: Không tìm thấy user ID từ webhook', [
                    'content' => $event->sePayWebhookData->content ?? null,
                    'code' => $event->sePayWebhookData->code ?? null,
                ]);

                return;
            }

            // Tìm user
            $user = User::find($userId);
            if (! $user) {
                Log::warning("SePayWebhookListener: Không tìm thấy user với ID: {$userId}");

                return;
            }

            // Parse plan type từ nội dung chuyển khoản
            $content = $event->sePayWebhookData->content ?? '';
            $planType = $this->parsePlanTypeFromContent($content);

            if (! $planType) {
                Log::warning("SePayWebhookListener: Không thể xác định plan type từ content: {$content}");

                return;
            }

            // Tạo payment record
            $payment = \App\Models\Payment::create([
                'user_id' => $user->id,
                'class_id' => 1, // Default class
                'amount' => $event->sePayWebhookData->transferAmount ?? 0,
                'type' => $planType,
                'status' => 'paid',
                'paid_at' => isset($event->sePayWebhookData->transactionDate)
                  ? \Carbon\Carbon::parse($event->sePayWebhookData->transactionDate)
                  : \Carbon\Carbon::now(),
            ]);

            // Kích hoạt license
            $license = $this->licenseService->activateLicense($user, $planType, $payment->id);

            Log::info('SePayWebhookListener: License activated successfully', [
                'user_id' => $user->id,
                'license_id' => $license->id,
                'plan_type' => $planType,
                'transaction_id' => $event->sePayWebhookData->id ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('SePayWebhookListener: Error processing webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Parse plan type từ nội dung chuyển khoản
     */
    private function parsePlanTypeFromContent(string $content): ?string
    {
        $contentLower = strtolower($content);

        // Kiểm tra monthly
        if (str_contains($contentLower, 'monthly') || str_contains($contentLower, 'thang')) {
            return 'vip_monthly';
        }

        // Kiểm tra yearly
        if (str_contains($contentLower, 'yearly') || str_contains($contentLower, 'nam')) {
            return 'vip_yearly';
        }

        return null;
    }
}
