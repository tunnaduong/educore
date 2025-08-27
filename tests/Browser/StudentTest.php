<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StudentTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test xem bài học
     */
    public function test_view_lessons(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/lessons')
                    ->assertSee('Bài học')
                    ->click('@lesson-1')
                    ->assertSee('Nội dung bài học');
        });
    }

    /**
     * Test xem bài tập
     */
    public function test_view_assignments(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/assignments')
                    ->assertSee('Bài tập')
                    ->click('@assignment-1')
                    ->assertSee('Chi tiết bài tập');
        });
    }

    /**
     * Test nộp bài tập
     */
    public function test_submit_assignment(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/assignments/1/submit')
                    ->type('content', 'Đây là bài làm của tôi')
                    ->attach('file', __DIR__ . '/../../storage/app/public/assignments/test.pdf')
                    ->press('Nộp bài')
                    ->assertSee('Bài tập đã được nộp thành công');
        });
    }

    /**
     * Test làm quiz
     */
    public function test_take_quiz(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/quiz/1')
                    ->assertSee('Bắt đầu làm bài')
                    ->press('Bắt đầu')
                    ->click('@answer-1') // Chọn câu trả lời 1
                    ->click('@answer-2') // Chọn câu trả lời 2
                    ->press('Nộp bài')
                    ->assertSee('Kết quả bài thi');
        });
    }

    /**
     * Test xem lịch học
     */
    public function test_view_schedule(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/schedules')
                    ->assertSee('Lịch học')
                    ->assertSee('Thứ 2')
                    ->assertSee('Thứ 3');
        });
    }

    /**
     * Test xem điểm số
     */
    public function test_view_grades(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/reports')
                    ->assertSee('Báo cáo học tập')
                    ->click('@grades-tab')
                    ->assertSee('Điểm số');
        });
    }

    /**
     * Test chat với giáo viên
     */
    public function test_chat_with_teacher(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/chat')
                    ->assertSee('Chat')
                    ->type('message', 'Thưa thầy, em có câu hỏi về bài tập')
                    ->press('Gửi tin nhắn')
                    ->assertSee('Thưa thầy, em có câu hỏi về bài tập');
        });
    }

    /**
     * Test xem thông báo
     */
    public function test_view_notifications(): void
    {
        $student = User::factory()->create([
            'role' => 'student'
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                    ->visit('/student/notifications')
                    ->assertSee('Thông báo')
                    ->click('@notification-1')
                    ->assertSee('Chi tiết thông báo');
        });
    }
} 