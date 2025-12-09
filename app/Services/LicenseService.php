<?php

namespace App\Services;

use App\Models\License;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LicenseService
{
    /**
     * Kích hoạt license cho user
     */
    public function activateLicense(User $user, string $planType, ?int $paymentId = null): License
    {
        // Đóng license cũ nếu có
        $this->deactivateOldLicense($user);

        // Tính ngày hết hạn dựa trên plan type
        $expiresAt = $this->calculateExpiresAt($planType);

        // Tạo license mới
        $license = License::create([
            'user_id' => $user->id,
            'plan_type' => $planType,
            'status' => 'active',
            'started_at' => Carbon::now(),
            'expires_at' => $expiresAt,
            'is_lifetime' => false,
            'payment_id' => $paymentId,
            'auto_renew' => false,
        ]);

        Log::info("License activated for user {$user->id}: {$planType}");

        return $license;
    }

    /**
     * Kích hoạt gói dùng thử (Free Trial)
     */
    public function activateFreeTrial(User $user): License
    {
        // Kiểm tra user đã từng dùng Free Trial chưa
        $existingTrial = License::where('user_id', $user->id)
            ->where('plan_type', 'free_trial')
            ->exists();

        if ($existingTrial) {
            throw new \Exception('Bạn đã sử dụng gói dùng thử trước đó.');
        }

        // Lấy số ngày dùng thử từ config
        $trialDays = config('license.free_trial_duration_days', 7);

        return $this->activateLicense($user, 'free_trial', null);
    }

    /**
     * Kiểm tra trạng thái license của user
     */
    public function checkLicenseStatus(User $user): array
    {
        $license = $user->getCurrentLicense();

        if (! $license) {
            return [
                'has_license' => false,
                'is_active' => false,
                'status' => 'no_license',
                'message' => 'Bạn chưa có license.',
            ];
        }

        $isActive = $license->isActive();
        $daysRemaining = $license->daysRemaining();

        return [
            'has_license' => true,
            'is_active' => $isActive,
            'status' => $license->status,
            'plan_type' => $license->plan_type,
            'expires_at' => $license->expires_at?->format('Y-m-d H:i:s'),
            'days_remaining' => $daysRemaining,
            'is_lifetime' => $license->is_lifetime,
            'message' => $isActive
                ? "License đang active. Còn {$daysRemaining} ngày."
                : 'License đã hết hạn hoặc không hợp lệ.',
        ];
    }

    /**
     * Gia hạn license
     */
    public function renewLicense(User $user, string $planType, ?int $paymentId = null): License
    {
        $currentLicense = $user->getCurrentLicense();

        if ($currentLicense && $currentLicense->isActive()) {
            // Gia hạn license hiện tại
            $expiresAt = $this->calculateExpiresAt($planType, $currentLicense->expires_at);
            $currentLicense->update([
                'plan_type' => $planType,
                'expires_at' => $expiresAt,
                'payment_id' => $paymentId ?? $currentLicense->payment_id,
            ]);

            Log::info("License renewed for user {$user->id}: {$planType}");

            return $currentLicense->fresh();
        }

        // Nếu không có license active, tạo mới
        return $this->activateLicense($user, $planType, $paymentId);
    }

    /**
     * Tính số ngày còn lại của license
     */
    public function getDaysRemaining(License $license): ?int
    {
        return $license->daysRemaining();
    }

    /**
     * Đánh dấu license hết hạn
     */
    public function expireLicense(License $license): License
    {
        $license->update([
            'status' => 'expired',
        ]);

        Log::info("License expired: {$license->id}");

        return $license;
    }

    /**
     * Tính ngày hết hạn dựa trên plan type
     */
    private function calculateExpiresAt(string $planType, ?Carbon $currentExpiresAt = null): ?Carbon
    {
        $startDate = $currentExpiresAt && $currentExpiresAt->isFuture()
            ? $currentExpiresAt
            : Carbon::now();

        return match ($planType) {
            'free_trial' => $startDate->copy()->addDays((int) config('license.free_trial_duration_days', 7)),
            'vip_monthly' => $startDate->copy()->addMonth(),
            'vip_yearly' => $startDate->copy()->addYear(),
            default => null,
        };
    }

    /**
     * Đóng license cũ của user
     */
    private function deactivateOldLicense(User $user): void
    {
        License::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);
    }
}
