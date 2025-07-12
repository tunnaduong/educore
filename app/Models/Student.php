<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\QuizResult;
use App\Models\AssignmentSubmission;

class Student extends Model
{
    protected $fillable = ['user_id', 'date_of_birth', 'joined_at', 'status', 'level', 'notes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsToMany(Classroom::class, 'class_user', 'user_id', 'class_id')
            ->wherePivot('role', 'student')
            ->using(function ($query) {
                $query->where('user_id', $this->user_id);
            });
    }

    public function getClassroomsAttribute()
    {
        return $this->user->enrolledClassrooms;
    }

    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }
}
