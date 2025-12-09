<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    /**
     * Các trường có thể gán hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_type',
        'status',
        'started_at',
        'expires_at',
        'is_lifetime',
        'payment_id',
        'auto_renew',
    ];

    /**
     * Các trường được cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_lifetime' => 'boolean',
        'auto_renew' => 'boolean',
    ];

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Payment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Kiểm tra license có đang active không
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Nếu là lifetime thì luôn active
        if ($this->is_lifetime) {
            return true;
        }

        // Kiểm tra ngày hết hạn
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra license đã hết hạn chưa
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        // Nếu là lifetime thì không bao giờ hết hạn
        if ($this->is_lifetime) {
            return false;
        }

        // Kiểm tra ngày hết hạn
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Tính số ngày còn lại của license
     */
    public function daysRemaining(): ?int
    {
        if ($this->is_lifetime) {
            return null; // Lifetime không có ngày hết hạn
        }

        if (! $this->expires_at) {
            return null;
        }

        $now = Carbon::now();
        $expiresAt = Carbon::parse($this->expires_at);

        if ($expiresAt->isPast()) {
            return 0;
        }

        return $now->diffInDays($expiresAt, false);
    }

    /**
     * Kiểm tra có phải gói dùng thử không
     */
    public function isFreeTrial(): bool
    {
        return $this->plan_type === 'free_trial';
    }

    /**
     * Gia hạn license
     */
    public function renew(string $planType, ?int $paymentId = null): self
    {
        $this->status = 'active';
        $this->plan_type = $planType;
        $this->started_at = Carbon::now();

        // Tính ngày hết hạn dựa trên plan type
        if ($planType === 'vip_monthly') {
            $this->expires_at = Carbon::now()->addMonth();
        } elseif ($planType === 'vip_yearly') {
            $this->expires_at = Carbon::now()->addYear();
        }

        if ($paymentId) {
            $this->payment_id = $paymentId;
        }

        $this->save();

        return $this;
    }

    /**
     * Scope: Lấy các license đang active
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->where('is_lifetime', true)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('expires_at')
                            ->where('expires_at', '>', now());
                    });
            });
    }

    /**
     * Scope: Lấy các license đã hết hạn
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('status', 'active')
                    ->where('is_lifetime', false)
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
            });
    }
}
