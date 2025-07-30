<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'class_id',
        'message',
        'attachment',
        'read_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->orWhereHas('classroom.users', function ($subQ) use ($userId) {
                    $subQ->where('users.id', $userId);
                });
        });
    }

    public function scopeUnread($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            // Tin nhắn 1-1 chưa đọc
            $q->where('receiver_id', $userId)
                ->whereNull('read_at');
        })->orWhere(function ($q) use ($userId) {
            // Tin nhắn nhóm chưa đọc (sử dụng classroom_message_reads)
            $q->whereNotNull('class_id')
                ->whereHas('classroom.users', function ($subQ) use ($userId) {
                    $subQ->where('users.id', $userId);
                })
                ->whereDoesntHave('classroom.messageReads', function ($subQ) use ($userId) {
                    $subQ->where('user_id', $userId);
                });
        });
    }
}
