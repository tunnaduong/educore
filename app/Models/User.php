<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        return $this->hasOne(Student::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
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
     * Relationship với License
     */
    public function license()
    {
        return $this->hasOne(License::class);
    }

    /**
     * Relationship với Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Kiểm tra user có license đang active không
     */
    public function hasActiveLicense(): bool
    {
        $license = $this->getCurrentLicense();

        return $license && $license->isActive();
    }

    /**
     * Lấy license hiện tại của user (active hoặc gần nhất)
     *
     * @return License|null
     */
    public function getCurrentLicense()
    {
        // Lấy license active trước
        $activeLicense = $this->license()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where('is_lifetime', true)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('expires_at')
                            ->where('expires_at', '>', now());
                    });
            })
            ->first();

        if ($activeLicense) {
            return $activeLicense;
        }

        // Nếu không có active, lấy license gần nhất
        return $this->license()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Lấy trạng thái của học viên (nếu có studentProfile), trả về null nếu không có.
     */
    public function getStatusAttribute()
    {
        return $this->student ? $this->student->status : null;
    }
}
