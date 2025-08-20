<?php

namespace App\Helpers;

use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;

class ScheduleConflictHelper
{
    /**
     * Kiểm tra xem học sinh có bị trùng lịch khi thêm vào lớp mới không
     */
    public static function checkStudentScheduleConflict(User $student, Classroom $newClassroom): array
    {
        $conflicts = [];
        
        // Lấy tất cả lớp học hiện tại của học sinh
        $currentClassrooms = $student->enrolledClassrooms()
            ->where('classrooms.id', '!=', $newClassroom->id)
            ->get();
        
        if ($currentClassrooms->isEmpty()) {
            return ['hasConflict' => false, 'conflicts' => []];
        }
        
        $newSchedule = $newClassroom->schedule;
        if (!$newSchedule || !isset($newSchedule['days']) || !isset($newSchedule['time'])) {
            return ['hasConflict' => false, 'conflicts' => []];
        }
        
        $newDays = $newSchedule['days'];
        $newTimeRange = $newSchedule['time'];
        
        foreach ($currentClassrooms as $currentClassroom) {
            // Loại trừ chính lớp đang xét nếu vì lý do nào đó vẫn lọt qua filter truy vấn
            if ($newClassroom->id && $currentClassroom->id == $newClassroom->id) {
                continue;
            }
            $currentSchedule = $currentClassroom->schedule;
            if (!$currentSchedule || !isset($currentSchedule['days']) || !isset($currentSchedule['time'])) {
                continue;
            }
            
            $currentDays = $currentSchedule['days'];
            $currentTimeRange = $currentSchedule['time'];
            
            // Kiểm tra trùng ngày
            $conflictingDays = array_intersect($newDays, $currentDays);
            
            if (!empty($conflictingDays)) {
                // Kiểm tra trùng thời gian
                $timeConflict = self::checkTimeConflict($newTimeRange, $currentTimeRange);
                
                if ($timeConflict['hasConflict']) {
                    $conflicts[] = [
                        'classroom' => $currentClassroom,
                        'conflictingDays' => $conflictingDays,
                        'newTime' => $newTimeRange,
                        'currentTime' => $currentTimeRange,
                        'overlapTime' => $timeConflict['overlapTime'] ?? null,
                        'message' => self::generateConflictMessage(
                            $currentClassroom->name,
                            $conflictingDays,
                            $newTimeRange,
                            $currentTimeRange,
                            $timeConflict['overlapTime'] ?? null
                        )
                    ];
                }
            }
        }
        
        return [
            'hasConflict' => !empty($conflicts),
            'conflicts' => $conflicts
        ];
    }

    /**
     * Kiểm tra xem giáo viên có bị trùng lịch khi thêm vào lớp mới không
     */
    public static function checkTeacherScheduleConflict(User $teacher, Classroom $newClassroom): array
    {
        $conflicts = [];
        
        // Lấy tất cả lớp học hiện tại của giáo viên
        $currentClassrooms = $teacher->teachingClassrooms()
            ->where('classrooms.id', '!=', $newClassroom->id)
            ->get();
        
        if ($currentClassrooms->isEmpty()) {
            return ['hasConflict' => false, 'conflicts' => []];
        }
        
        $newSchedule = $newClassroom->schedule;
        if (!$newSchedule || !isset($newSchedule['days']) || !isset($newSchedule['time'])) {
            return ['hasConflict' => false, 'conflicts' => []];
        }
        
        $newDays = $newSchedule['days'];
        $newTimeRange = $newSchedule['time'];
        
        foreach ($currentClassrooms as $currentClassroom) {
            // Loại trừ chính lớp đang xét nếu vì lý do nào đó vẫn lọt qua filter truy vấn
            if ($newClassroom->id && $currentClassroom->id == $newClassroom->id) {
                continue;
            }
            $currentSchedule = $currentClassroom->schedule;
            if (!$currentSchedule || !isset($currentSchedule['days']) || !isset($currentSchedule['time'])) {
                continue;
            }
            
            $currentDays = $currentSchedule['days'];
            $currentTimeRange = $currentSchedule['time'];
            
            // Kiểm tra trùng ngày
            $conflictingDays = array_intersect($newDays, $currentDays);
            
            if (!empty($conflictingDays)) {
                // Kiểm tra trùng thời gian
                $timeConflict = self::checkTimeConflict($newTimeRange, $currentTimeRange);
                
                if ($timeConflict['hasConflict']) {
                    $conflicts[] = [
                        'classroom' => $currentClassroom,
                        'conflictingDays' => $conflictingDays,
                        'newTime' => $newTimeRange,
                        'currentTime' => $currentTimeRange,
                        'overlapTime' => $timeConflict['overlapTime'] ?? null,
                        'message' => self::generateTeacherConflictMessage(
                            $currentClassroom->name,
                            $conflictingDays,
                            $newTimeRange,
                            $currentTimeRange,
                            $timeConflict['overlapTime'] ?? null
                        )
                    ];
                }
            }
        }
        
        return [
            'hasConflict' => !empty($conflicts),
            'conflicts' => $conflicts
        ];
    }

    /**
     * Kiểm tra trùng lịch cho nhiều giáo viên cùng lúc
     */
    public static function checkMultipleTeachersScheduleConflict(array $teacherIds, Classroom $classroom): array
    {
        $allConflicts = [];
        
        foreach ($teacherIds as $teacherId) {
            $teacher = User::find($teacherId);
            if (!$teacher) continue;
            
            $conflict = self::checkTeacherScheduleConflict($teacher, $classroom);

            // Loại bỏ mọi xung đột trùng với chính lớp hiện tại theo id
            if (!empty($conflict['conflicts'])) {
                $filtered = array_values(array_filter($conflict['conflicts'], function ($item) use ($classroom) {
                    return isset($item['classroom']) && $classroom->id
                        ? $item['classroom']->id !== $classroom->id
                        : true;
                }));

                if (!empty($filtered)) {
                    $allConflicts[$teacherId] = [
                        'teacher' => $teacher,
                        'conflicts' => $filtered,
                    ];
                }
            }
        }
        
        return [
            'hasConflict' => !empty($allConflicts),
            'conflicts' => $allConflicts
        ];
    }
    
    /**
     * Kiểm tra trùng thời gian giữa hai khoảng thời gian
     */
    private static function checkTimeConflict(string $time1, string $time2): array
    {
        $time1Parts = explode(' - ', $time1);
        $time2Parts = explode(' - ', $time2);
        
        if (count($time1Parts) !== 2 || count($time2Parts) !== 2) {
            return ['hasConflict' => false];
        }
        
        $start1 = Carbon::createFromFormat('H:i', trim($time1Parts[0]));
        $end1 = Carbon::createFromFormat('H:i', trim($time1Parts[1]));
        $start2 = Carbon::createFromFormat('H:i', trim($time2Parts[0]));
        $end2 = Carbon::createFromFormat('H:i', trim($time2Parts[1]));
        
        // Kiểm tra xem có trùng thời gian không
        if ($start1 < $end2 && $start2 < $end1) {
            // Tính thời gian trùng
            $overlapStart = $start1->copy()->max($start2);
            $overlapEnd = $end1->copy()->min($end2);
            $overlapTime = $overlapStart->format('H:i') . ' - ' . $overlapEnd->format('H:i');
            
            return [
                'hasConflict' => true,
                'overlapTime' => $overlapTime
            ];
        }
        
        return ['hasConflict' => false];
    }
    
    /**
     * Tạo thông báo trùng lịch cho học sinh
     */
    private static function generateConflictMessage(string $classroomName, array $conflictingDays, string $newTime, string $currentTime, ?string $overlapTime): string
    {
        $dayNames = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3', 
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật'
        ];
        
        $conflictingDayNames = [];
        foreach ($conflictingDays as $day) {
            $conflictingDayNames[] = $dayNames[$day] ?? $day;
        }
        
        $conflictingDaysText = implode(', ', $conflictingDayNames);
        
        if ($overlapTime) {
            return "Trùng lịch với lớp {$classroomName} vào {$conflictingDaysText} từ {$overlapTime}";
        } else {
            return "Trùng lịch với lớp {$classroomName} vào {$conflictingDaysText}";
        }
    }

    /**
     * Tạo thông báo trùng lịch cho giáo viên
     */
    private static function generateTeacherConflictMessage(string $classroomName, array $conflictingDays, string $newTime, string $currentTime, ?string $overlapTime): string
    {
        $dayNames = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3', 
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật'
        ];
        
        $conflictingDayNames = [];
        foreach ($conflictingDays as $day) {
            $conflictingDayNames[] = $dayNames[$day] ?? $day;
        }
        
        $conflictingDaysText = implode(', ', $conflictingDayNames);
        
        if ($overlapTime) {
            return "Giáo viên bị trùng lịch với lớp {$classroomName} vào {$conflictingDaysText} từ {$overlapTime}";
        } else {
            return "Giáo viên bị trùng lịch với lớp {$classroomName} vào {$conflictingDaysText}";
        }
    }
    
    /**
     * Kiểm tra trùng lịch cho nhiều học sinh cùng lúc
     */
    public static function checkMultipleStudentsScheduleConflict(array $studentIds, Classroom $classroom): array
    {
        $allConflicts = [];
        
        foreach ($studentIds as $studentId) {
            $student = User::find($studentId);
            if (!$student) continue;
            
            $conflict = self::checkStudentScheduleConflict($student, $classroom);
            if ($conflict['hasConflict']) {
                $allConflicts[$studentId] = [
                    'student' => $student,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }
        
        return [
            'hasConflict' => !empty($allConflicts),
            'conflicts' => $allConflicts
        ];
    }
}
