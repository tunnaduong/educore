<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'level',
        'schedule',
        'notes',
        'status',
    ];

    protected $casts = [
        'schedule' => 'array',
        'status' => 'string',
    ];

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

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'classroom_id');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'class_id');
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

    // Thêm các relationship counts để kiểm tra điều kiện xóa
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function getAssignmentsCountAttribute()
    {
        return $this->assignments()->count();
    }

    public function getLessonsCountAttribute()
    {
        return $this->lessons()->count();
    }

    public function getAttendancesCountAttribute()
    {
        return $this->attendances()->count();
    }
}
