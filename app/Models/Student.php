<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = ['user_id', 'date_of_birth', 'joined_at', 'status', 'level', 'notes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getClassroomAttribute()
    {
        // Lấy lớp học đầu tiên của học viên
        return $this->user->enrolledClassrooms->first();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class, 'student_id');
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            'class_user',
            'user_id',    // class_user.user_id
            'class_id',   // class_user.class_id
            'user_id',    // students.user_id
            'id'          // classrooms.id
        )->wherePivot('role', 'student');
    }

    public function getClassroomsAttribute()
    {
        return $this->user->enrolledClassrooms;
    }

    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'student_id');
    }
}
