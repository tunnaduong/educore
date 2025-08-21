<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now()->toDateString();

        return $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('is_active', true);
    }

    public function isCurrent(): bool
    {
        $now = now()->toDateString();

        return $this->is_active &&
               $this->start_date->toDateString() <= $now &&
               $this->end_date->toDateString() >= $now;
    }

    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        $now = now()->toDateString();
        if ($this->start_date->toDateString() > $now) {
            return 'upcoming';
        }
        if ($this->end_date->toDateString() < $now) {
            return 'ended';
        }

        return 'active';
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Đang diễn ra',
            'upcoming' => 'Sắp diễn ra',
            'ended' => 'Đã kết thúc',
            'inactive' => 'Không hoạt động',
            default => 'Không xác định'
        };
    }
}
