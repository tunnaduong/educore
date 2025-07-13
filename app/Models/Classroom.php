<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'schedule',
        'notes',
        'teacher_id',
        'status',
    ];

    protected $casts = [
        'schedule' => 'array',
        'status' => 'string',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->wherePivot('role', 'teacher')
            ->withTimestamps();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->wherePivot('role', 'student')
            ->withTimestamps();
    }

    public function getFirstTeacher()
    {
        return $this->teachers()->first();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function messageReads()
    {
        return $this->hasMany(ClassroomMessageRead::class, 'class_id');
    }

    public function unreadMessagesCountForUser($userId)
    {
        $read = $this->messageReads()->where('user_id', $userId)->first();
        $lastReadId = $read ? $read->last_read_message_id : 0;
        return Message::where('class_id', $this->id)
            ->when($lastReadId, function ($q) use ($lastReadId) {
                $q->where('id', '>', $lastReadId);
            })
            ->count();
    }
}
