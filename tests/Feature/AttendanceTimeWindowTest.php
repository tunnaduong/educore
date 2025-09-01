<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTimeWindowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo user admin
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Tạo lớp học với lịch học
        $this->classroom = Classroom::create([
            'name' => 'Test Class A1',
            'description' => 'Test class for attendance time window',
            'schedule' => [
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'time' => '08:25 - 11:00'
            ]
        ]);
    }

    /** @test */
    public function can_take_attendance_15_minutes_before_class_time()
    {
        // Thiết lập thời gian hiện tại là 15 phút trước giờ học
        $now = Carbon::parse('2025-01-27 08:10:00'); // Thứ 2, 8:10 (15 phút trước 8:25)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    /** @test */
    public function can_take_attendance_15_minutes_after_class_time()
    {
        // Thiết lập thời gian hiện tại là 15 phút sau giờ học
        $now = Carbon::parse('2025-01-27 11:15:00'); // Thứ 2, 11:15 (15 phút sau 11:00)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    /** @test */
    public function cannot_take_attendance_too_early()
    {
        // Thiết lập thời gian hiện tại là 20 phút trước giờ học
        $now = Carbon::parse('2025-01-27 08:05:00'); // Thứ 2, 8:05 (20 phút trước 8:25)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertFalse($result['can']);
        $this->assertStringContainsString('Chưa đến thời gian điểm danh', $result['message']);
        $this->assertStringContainsString('08:10', $result['message']); // 8:25 - 15 phút
        $this->assertStringContainsString('11:15', $result['message']); // 11:00 + 15 phút
    }

    /** @test */
    public function cannot_take_attendance_too_late()
    {
        // Thiết lập thời gian hiện tại là 20 phút sau giờ học
        $now = Carbon::parse('2025-01-27 11:20:00'); // Thứ 2, 11:20 (20 phút sau 11:00)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertFalse($result['can']);
        $this->assertStringContainsString('Đã qua thời gian điểm danh', $result['message']);
    }

    /** @test */
    public function can_take_attendance_during_class_time()
    {
        // Thiết lập thời gian hiện tại là trong giờ học
        $now = Carbon::parse('2025-01-27 09:30:00'); // Thứ 2, 9:30 (trong giờ học 8:25-11:00)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    /** @test */
    public function can_take_attendance_exactly_at_attendance_start_time()
    {
        // Thiết lập thời gian hiện tại là chính xác thời gian bắt đầu điểm danh
        $now = Carbon::parse('2025-01-27 08:10:00'); // Thứ 2, 8:10 (chính xác 8:25 - 15 phút)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    /** @test */
    public function can_take_attendance_exactly_at_attendance_end_time()
    {
        // Thiết lập thời gian hiện tại là chính xác thời gian kết thúc điểm danh
        $now = Carbon::parse('2025-01-27 11:15:00'); // Thứ 2, 11:15 (chính xác 11:00 + 15 phút)
        Carbon::setTestNow($now);

        $result = Attendance::canTakeAttendance($this->classroom, '2025-01-27');

        $this->assertTrue($result['can']);
        $this->assertEquals('', $result['message']);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Reset thời gian test
        parent::tearDown();
    }
}
