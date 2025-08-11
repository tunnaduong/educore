<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class TestQuizSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:test {--create : Create test quiz} {--check : Check quiz system}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test quiz system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('check')) {
            $this->checkQuizSystem();
        } elseif ($this->option('create')) {
            $this->createTestQuiz();
        } else {
            $this->info('Quiz System Test');
            $this->info('Available options:');
            $this->info('  --check  : Check quiz system');
            $this->info('  --create : Create test quiz');
        }

        return 0;
    }

    private function checkQuizSystem()
    {
        $this->info('🔍 Checking Quiz System...');

        $issues = [];

        // Check database tables
        $this->checkDatabaseTables($issues);

        // Check models
        $this->checkModels($issues);

        // Check existing quizzes
        $this->checkExistingQuizzes($issues);

        // Display results
        $this->displayResults($issues);
    }

    private function checkDatabaseTables(&$issues)
    {
        $this->info('📊 Checking database tables...');

        // Check quizzes table
        if (!DB::getSchemaBuilder()->hasTable('quizzes')) {
            $issues[] = '❌ Quizzes table does not exist';
        } else {
            $this->info('✅ Quizzes table exists');

            // Check columns
            $columns = DB::getSchemaBuilder()->getColumnListing('quizzes');
            $requiredColumns = ['id', 'class_id', 'title', 'description', 'questions', 'time_limit', 'deadline', 'created_at', 'updated_at'];

            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    $issues[] = "❌ Quizzes table missing column: {$column}";
                }
            }
        }

        // Check quiz_results table
        if (!DB::getSchemaBuilder()->hasTable('quiz_results')) {
            $issues[] = '❌ Quiz results table does not exist';
        } else {
            $this->info('✅ Quiz results table exists');
        }
    }

    private function checkModels(&$issues)
    {
        $this->info('🏗️ Checking models...');

        // Check Quiz model
        try {
            $quiz = new Quiz();
            $this->info('✅ Quiz model works');
        } catch (\Exception $e) {
            $issues[] = '❌ Quiz model error: ' . $e->getMessage();
        }

        // Check QuizResult model
        try {
            $result = new QuizResult();
            $this->info('✅ QuizResult model works');
        } catch (\Exception $e) {
            $issues[] = '❌ QuizResult model error: ' . $e->getMessage();
        }
    }

    private function checkExistingQuizzes(&$issues)
    {
        $this->info('📝 Checking existing quizzes...');

        $quizCount = Quiz::count();
        $this->info("✅ Found {$quizCount} quizzes");

        if ($quizCount > 0) {
            $quizzes = Quiz::with('classroom')->get();
            foreach ($quizzes as $quiz) {
                $this->info("  - Quiz: {$quiz->title} (ID: {$quiz->id})");
                $this->info("    Time limit: {$quiz->time_limit} minutes");
                $this->info("    Questions: " . count($quiz->questions ?? []));
                $this->info("    Classroom: " . ($quiz->classroom ? $quiz->classroom->name : 'N/A'));
            }
        } else {
            $issues[] = '⚠️ No quizzes found. Run --create to create test quiz.';
        }
    }

    private function displayResults($issues)
    {
        $this->newLine();

        if (empty($issues)) {
            $this->info('🎉 All checks passed! Quiz system is working correctly.');
        } else {
            $this->error('⚠️ Found ' . count($issues) . ' issue(s):');
            foreach ($issues as $issue) {
                $this->line($issue);
            }
        }
    }

    private function createTestQuiz()
    {
        $this->info('📝 Creating test quiz...');

        // Find a classroom
        $classroom = Classroom::first();
        if (!$classroom) {
            $this->error('❌ No classroom found. Please create a classroom first.');
            return;
        }

        // Create test quiz
        $quiz = Quiz::create([
            'class_id' => $classroom->id,
            'title' => 'Bài kiểm tra mẫu - ' . now()->format('Y-m-d H:i'),
            'description' => 'Bài kiểm tra mẫu để test chức năng quiz system',
            'time_limit' => 30, // 30 minutes
            'deadline' => now()->addDays(7),
            'questions' => [
                [
                    'type' => 'multiple_choice',
                    'question' => 'Thủ đô của Việt Nam là gì?',
                    'options' => ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Huế'],
                    'correct_answer' => 'Hà Nội',
                    'score' => 1
                ],
                [
                    'type' => 'fill_blank',
                    'question' => 'Việt Nam có bao nhiêu tỉnh thành?',
                    'correct_answer' => '63',
                    'score' => 1
                ],
                [
                    'type' => 'multiple_choice',
                    'question' => 'Ngôn ngữ chính thức của Việt Nam là gì?',
                    'options' => ['Tiếng Việt', 'Tiếng Anh', 'Tiếng Pháp', 'Tiếng Trung'],
                    'correct_answer' => 'Tiếng Việt',
                    'score' => 1
                ],
                [
                    'type' => 'essay',
                    'question' => 'Hãy viết một đoạn văn ngắn về quê hương của bạn.',
                    'score' => 2
                ]
            ]
        ]);

        $this->info("✅ Created test quiz: {$quiz->title}");
        $this->info("   Quiz ID: {$quiz->id}");
        $this->info("   Time limit: {$quiz->time_limit} minutes");
        $this->info("   Questions: " . count($quiz->questions));
        $this->info("   Classroom: {$classroom->name}");

        $this->newLine();
        $this->info('🎯 Test the quiz at:');
        $this->info("   http://localhost/student/quizzes/{$quiz->id}/do");
    }
}
