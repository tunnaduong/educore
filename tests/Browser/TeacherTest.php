<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TeacherTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test tạo bài học mới
     */
    public function test_create_lesson(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/lessons/create')
                ->type('title', 'Bài 1: Giới thiệu về Toán học')
                ->type('content', 'Nội dung bài học về toán học cơ bản')
                ->select('classroom_id', '1')
                ->press('Tạo bài học')
                ->assertSee('Bài học đã được tạo thành công');
        });
    }

    /**
     * Test tạo bài tập mới
     */
    public function test_create_assignment(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/assignments/create')
                ->type('title', 'Bài tập về nhà số 1')
                ->type('description', 'Làm bài tập 1-10 trong sách giáo khoa')
                ->type('deadline', '2024-12-31')
                ->select('class_id', '1')
                ->press('Tạo bài tập')
                ->assertSee('Bài tập đã được tạo thành công');
        });
    }

    /**
     * Test điểm danh học sinh
     */
    public function test_take_attendance(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/attendance')
                ->assertSee('Điểm danh')
                ->click('@present-1') // Đánh dấu học sinh 1 có mặt
                ->click('@absent-2')  // Đánh dấu học sinh 2 vắng
                ->press('Lưu điểm danh')
                ->assertSee('Điểm danh đã được lưu');
        });
    }

    /**
     * Test chấm điểm bài tập
     */
    public function test_grade_assignment(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/grading')
                ->assertSee('Chấm điểm')
                ->click('@grade-assignment-1')
                ->type('score', '85')
                ->type('feedback', 'Bài làm tốt, cần cải thiện phần trình bày')
                ->press('Lưu điểm')
                ->assertSee('Điểm đã được lưu');
        });
    }

    /**
     * Test tạo quiz
     */
    public function test_create_quiz(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/quizzes/create')
                ->type('title', 'Quiz kiểm tra kiến thức')
                ->type('description', 'Quiz về chương 1')
                ->type('duration', '30')
                ->press('Tạo Quiz')
                ->assertSee('Quiz đã được tạo thành công');
        });
    }

    /**
     * Test xem báo cáo lớp học
     */
    public function test_view_class_report(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/reports')
                ->assertSee('Báo cáo lớp học')
                ->click('@attendance-stats')
                ->assertSee('Thống kê điểm danh');
        });
    }
}
