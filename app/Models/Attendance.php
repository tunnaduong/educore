<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'class_id',
        'student_id',
        'date',
        'present',
        'reason',
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
        return $this->belongsTo(Student::class, 'student_id');
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

    // Kiểm tra xem có thể điểm danh cho ngày này không
    public static function canTakeAttendance($classroom, $date)
    {
        $selectedDate = Carbon::parse($date);
        $now = Carbon::now();

        // Kiểm tra xem ngày đã chọn có phải là tương lai không
        if ($selectedDate->isFuture()) {
            return [
                'can' => false,
                'message' => 'Không thể điểm danh cho ngày trong tương lai.',
            ];
        }

        // Kiểm tra xem ngày đã chọn có phải là quá khứ không
        if ($selectedDate->isPast() && ! $selectedDate->isToday()) {
            return [
                'can' => false,
                'message' => 'Không thể điểm danh cho ngày trong quá khứ.',
            ];
        }

        // Kiểm tra thời gian học nếu có lịch học
        if ($classroom->schedule && is_array($classroom->schedule)) {
            $schedule = $classroom->schedule;
            $days = $schedule['days'] ?? [];
            $time = $schedule['time'] ?? '';

            if (! empty($days) && ! empty($time)) {
                // Kiểm tra xem ngày đã chọn có phải là ngày học không
                $dayOfWeek = $selectedDate->format('l'); // Monday, Tuesday, etc.

                if (! in_array($dayOfWeek, $days)) {
                    return [
                        'can' => false,
                        'message' => 'Ngày này không phải là ngày học của lớp.',
                    ];
                }

                // Kiểm tra thời gian học chỉ cho ngày hôm nay
                if ($selectedDate->isToday()) {
                    $timeParts = explode(' - ', $time);
                    if (count($timeParts) === 2) {
                        $startTime = Carbon::parse($timeParts[0]);
                        $endTime = Carbon::parse($timeParts[1]);

                        // Tạo thời gian học cho ngày đã chọn
                        $classStartTime = $selectedDate->copy()->setTime($startTime->hour, $startTime->minute);
                        $classEndTime = $selectedDate->copy()->setTime($endTime->hour, $endTime->minute);

                        // Kiểm tra xem đã đến thời gian học chưa
                        if ($now->isBefore($classStartTime)) {
                            return [
                                'can' => false,
                                'message' => 'Chưa đến thời gian học. Chỉ có thể điểm danh từ '.$startTime->format('H:i').' đến '.$endTime->format('H:i').'.',
                            ];
                        }

                        // Kiểm tra xem đã qua thời gian học chưa
                        if ($now->isAfter($classEndTime)) {
                            return [
                                'can' => false,
                                'message' => 'Đã qua thời gian học. Không thể điểm danh lại.',
                            ];
                        }
                    }
                }
            }
        }

        return [
            'can' => true,
            'message' => '',
        ];
    }

    // Validation rules
    public static function rules()
    {
        return [
            'class_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'present' => 'required|boolean',
            'reason' => 'nullable|string|max:255',
        ];
    }
}
