<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Student;
use App\Models\QuizResult;
use App\Models\Lesson;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teachingClassrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'class_user', 'user_id', 'class_id')
            ->wherePivot('role', 'teacher')
            ->withTimestamps();
    }

    public function enrolledClassrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'class_user', 'user_id', 'class_id')
            ->wherePivot('role', 'student')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Student::class, 'user_id', 'student_id');
    }

    public function quizResults()
    {
        return $this->hasManyThrough(QuizResult::class, Student::class, 'user_id', 'student_id');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    /**
     * Lấy trạng thái của học viên (nếu có studentProfile), trả về null nếu không có.
     */
    public function getStatusAttribute()
    {
        return $this->studentProfile ? $this->studentProfile->status : null;
    }
}
