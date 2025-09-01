<?php

namespace Tests\Browser;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StudentRouteDuskTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo classroom trước
        $classroom = Classroom::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Test Classroom',
                'description' => 'Test Description',
                'is_active' => true,
            ]
        );

        // Tạo dữ liệu test cơ bản
        $assignment = Assignment::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Assignment',
                'description' => 'Test Description',
                'deadline' => now()->addDays(7),
                'class_id' => $classroom->id,
            ]
        );

        $lesson = Lesson::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Lesson',
                'content' => 'Test Content',
                'is_active' => true,
            ]
        );

        // Tạo quiz
        $quiz = Quiz::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'Test Quiz',
                'description' => 'Test Quiz Description',
                'questions' => [
                    [
                        'question' => 'Câu hỏi test 1',
                        'type' => 'multiple_choice',
                        'options' => ['A', 'B', 'C', 'D'],
                        'correct_answer' => 'A',
                        'score' => 1
                    ]
                ],
                'class_id' => $classroom->id,
            ]
        );

        // Tạo student profile
        $student = Student::firstOrCreate(
            ['user_id' => 3],
            [
                'student_id' => 'STU001',
                'status' => 'active',
                'is_active' => true,
            ]
        );

        // Gán assignment vào classroom
        $assignment->update(['class_id' => $classroom->id]);

        // Gán student vào classroom
        $classroom->users()->syncWithoutDetaching([
            3 => ['role' => 'student'],
        ]);
    }

    /**
     * Test đăng nhập và truy cập dashboard
     */
    public function test_student_can_login_and_access_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('phone', 'student')
                ->type('password', 'Student@12')
                ->press('Đăng nhập')
                ->waitForLocation('/dashboard')
                ->assertPathIs('/dashboard')
                ->assertSee('EduCore');
        });
    }

    /**
     * Test truy cập trang lessons
     */
    public function test_student_can_access_lessons()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('phone', 'student')->first())
                ->visit('/student/lessons')
                ->assertPathIs('/student/lessons')
                ->assertSee('Bài học');
        });
    }

    /**
     * Test xem chi tiết lesson
     */
    public function test_student_can_view_lesson_detail()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/lessons/1')
                ->assertPathIs('/student/lessons/1')
                ->assertSee('Test Lesson');
        });
    }

    /**
     * Test truy cập trang assignments
     */
    public function test_student_can_access_assignments()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/assignments')
                ->assertPathIs('/student/assignments')
                ->assertSee('Bài tập');
        });
    }

    /**
     * Test xem chi tiết assignment
     */
    public function test_student_can_view_assignment_detail()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/assignments/1')
                ->assertPathIs('/student/assignments/1')
                ->assertSee('Test Assignment');
        });
    }

    /**
     * Test truy cập trang submit assignment
     */
    public function test_student_can_access_submit_assignment()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/assignments/1/submit')
                ->assertPathIs('/student/assignments/1/submit')
                ->assertSee('Nộp bài');
        });
    }

    /**
     * Test truy cập trang quizzes
     */
    public function test_student_can_access_quizzes()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/quizzes')
                ->assertPathIs('/student/quizzes')
                ->assertSee('bài kiểm tra');
        });
    }

    /**
     * Test truy cập trang do quiz
     */
    public function test_student_can_access_do_quiz()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/quizzes/1/do')
                ->assertPathIs('/student/quizzes/1/do')
                ->assertSee('EduCore');
        });
    }

    /**
     * Test truy cập trang notifications
     */
    public function test_student_can_access_notifications()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/notifications')
                ->assertPathIs('/student/notifications')
                ->assertSee('Thông báo');
        });
    }

    /**
     * Test truy cập trang reports
     */
    public function test_student_can_access_reports()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/reports')
                ->assertPathIs('/student/reports')
                ->assertSee('Báo cáo');
        });
    }

    /**
     * Test truy cập trang schedules
     */
    public function test_student_can_access_schedules()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/schedules')
                ->assertPathIs('/student/schedules')
                ->assertSee('Lịch học');
        });
    }

    /**
     * Test truy cập trang chat
     */
    public function test_student_can_access_chat()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/chat')
                ->assertPathIs('/student/chat')
                ->assertSee('Chat');
        });
    }

    /**
     * Test truy cập trang evaluation
     */
    public function test_student_can_access_evaluation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                ->visit('/student/evaluation')
                ->assertPathIs('/student/evaluation')
                ->assertSee('Đánh giá');
        });
    }
}
