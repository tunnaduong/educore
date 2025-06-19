<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function getStatusAttribute()
    {
        return $this->studentProfile->status;
    }
}
