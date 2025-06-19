<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'class_id',
        'student_id',
        'date',
        'present',
        'reason'
    ];

    protected $casts = [
        'date' => 'date',
        'present' => 'boolean',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Scope để lọc theo lớp học
    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    // Scope để lọc theo học viên
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Scope để lọc theo ngày
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    // Scope để lọc theo tháng
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
