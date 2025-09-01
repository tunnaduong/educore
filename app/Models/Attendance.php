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
                'message' => __('general.cannot_attendance_future_date'),
            ];
        }

        // Kiểm tra xem ngày đã chọn có phải là quá khứ không
        if ($selectedDate->isPast() && ! $selectedDate->isToday()) {
            return [
                'can' => false,
                'message' => __('general.cannot_attendance_past_date'),
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
                        'message' => __('general.not_class_day'),
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

                        // Cho phép điểm danh trước 15 phút trước giờ học và sau 15 phút sau giờ học
                        $attendanceStartTime = $classStartTime->copy()->subMinutes(15);
                        $attendanceEndTime = $classEndTime->copy()->addMinutes(15);

                        // Kiểm tra xem đã đến thời gian điểm danh chưa
                        if ($now->isBefore($attendanceStartTime)) {
                            return [
                                'can' => false,
                                'message' => __('general.not_attendance_time_yet', [
                                    'start_time' => $attendanceStartTime->format('H:i'),
                                    'end_time' => $attendanceEndTime->format('H:i'),
                                ]),
                            ];
                        }

                        // Kiểm tra xem đã qua thời gian điểm danh chưa
                        if ($now->isAfter($attendanceEndTime)) {
                            return [
                                'can' => false,
                                'message' => __('general.attendance_time_passed'),
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
