<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_take_attendance_for_future_date()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Thử điểm danh cho ngày tương lai
        $futureDate = Carbon::now()->addDays(2)->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $futureDate);
        
        $this->assertFalse($result['can']);
        $this->assertEquals('Không thể điểm danh cho ngày trong tương lai.', $result['message']);
    }

    public function test_cannot_take_attendance_for_past_date()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Thử điểm danh cho ngày quá khứ
        $pastDate = Carbon::now()->subDays(2)->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $pastDate);
        
        $this->assertFalse($result['can']);
        $this->assertEquals('Không thể điểm danh cho ngày trong quá khứ.', $result['message']);
    }

    public function test_cannot_take_attendance_before_class_time()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Giả lập thời gian hiện tại là trước giờ học
        Carbon::setTestNow(Carbon::now()->setTime(8, 30)); // 8:30

        // Thử điểm danh cho ngày hôm nay
        $today = Carbon::now()->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $today);
        
        $this->assertFalse($result['can']);
        $this->assertStringContainsString('Chưa đến thời gian học', $result['message']);
    }

    public function test_cannot_take_attendance_after_class_time()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Giả lập thời gian hiện tại là sau giờ học
        Carbon::setTestNow(Carbon::now()->setTime(18, 0)); // 18:00

        // Thử điểm danh cho ngày hôm nay
        $today = Carbon::now()->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $today);
        
        $this->assertFalse($result['can']);
        $this->assertEquals('Đã qua thời gian học. Không thể điểm danh lại.', $result['message']);
    }

    public function test_can_take_attendance_during_class_time()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Giả lập thời gian hiện tại là trong giờ học
        Carbon::setTestNow(Carbon::now()->setTime(15, 30)); // 15:30

        // Thử điểm danh cho ngày hôm nay
        $today = Carbon::now()->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $today);
        
        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    public function test_cannot_take_attendance_on_non_class_day()
    {
        // Tạo classroom với lịch học chỉ vào thứ 2, 4, 6
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Tìm ngày thứ 3 (Tuesday)
        $tuesday = Carbon::now()->startOfWeek()->addDays(1)->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $tuesday);
        
        $this->assertFalse($result['can']);
        $this->assertEquals('Ngày này không phải là ngày học của lớp.', $result['message']);
    }

    public function test_cannot_take_attendance_outside_class_time_on_same_day()
    {
        // Tạo classroom với lịch học
        $classroom = Classroom::factory()->create([
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Friday'],
                'time' => '14:30 - 17:00'
            ]
        ]);

        // Tạo student
        $student = Student::factory()->create();

        // Thêm student vào classroom
        $classroom->students()->attach($student->user_id, ['role' => 'student']);

        // Giả lập thời gian hiện tại là 8:30 (trước giờ học)
        Carbon::setTestNow(Carbon::now()->setTime(8, 30));

        // Thử điểm danh cho ngày hôm nay
        $today = Carbon::now()->format('Y-m-d');
        
        $result = Attendance::canTakeAttendance($classroom, $today);
        
        $this->assertFalse($result['can']);
        $this->assertStringContainsString('Chưa đến thời gian học', $result['message']);
        $this->assertStringContainsString('14:30', $result['message']);
        $this->assertStringContainsString('17:00', $result['message']);
    }
} 