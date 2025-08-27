<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AITest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test AI chấm điểm bài tập
     */
    public function test_ai_grading(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/ai/grading')
                ->assertSee('AI Chấm điểm')
                ->click('@select-assignment')
                ->select('assignment_id', '1')
                ->press('Bắt đầu chấm điểm')
                ->waitFor('@grading-result')
                ->assertSee('Kết quả chấm điểm');
        });
    }

    /**
     * Test AI tạo quiz
     */
    public function test_ai_quiz_generator(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/ai/quiz-generator')
                ->assertSee('AI Tạo Quiz')
                ->type('topic', 'Toán học lớp 10')
                ->type('description', 'Tạo quiz về đại số và hình học')
                ->select('difficulty', 'medium')
                ->type('question_count', '10')
                ->press('Tạo Quiz')
                ->waitFor('@quiz-result')
                ->assertSee('Quiz đã được tạo');
        });
    }

    /**
     * Test AI trả lời câu hỏi
     */
    public function test_ai_chat(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $this->browse(function (Browser $browser) use ($student) {
            $browser->loginAs($student)
                ->visit('/student/ai/chat')
                ->assertSee('AI Trợ giúp')
                ->type('question', 'Giải thích về định lý Pythagoras')
                ->press('Gửi câu hỏi')
                ->waitFor('@ai-response')
                ->assertSee('Định lý Pythagoras');
        });
    }

    /**
     * Test AI phân tích bài viết
     */
    public function test_ai_essay_analysis(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/ai/essay-analysis')
                ->assertSee('AI Phân tích bài viết')
                ->type('essay_content', 'Đây là một bài viết mẫu để phân tích')
                ->select('analysis_type', 'grammar')
                ->press('Phân tích')
                ->waitFor('@analysis-result')
                ->assertSee('Kết quả phân tích');
        });
    }

    /**
     * Test AI tạo bài học
     */
    public function test_ai_lesson_generator(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->browse(function (Browser $browser) use ($teacher) {
            $browser->loginAs($teacher)
                ->visit('/teacher/ai/lesson-generator')
                ->assertSee('AI Tạo bài học')
                ->type('subject', 'Vật lý')
                ->type('topic', 'Động học chất điểm')
                ->type('grade_level', '10')
                ->type('duration', '45')
                ->press('Tạo bài học')
                ->waitFor('@lesson-result')
                ->assertSee('Bài học đã được tạo');
        });
    }
}
