<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\AIHelper;

class TestAIService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test {--topic=Giao tiếp cơ bản} {--questions=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test AI service for question bank generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing AI Service...');

        $aiHelper = new AIHelper();

        // Kiểm tra AI có sẵn sàng không
        if (!$aiHelper->isAIAvailable()) {
            $this->error('❌ AI service không khả dụng!');
            $this->line('Vui lòng kiểm tra:');
            $this->line('1. API key đã được cấu hình trong file .env');
            $this->line('2. GEMINI_API_KEY=your_api_key_here');
            return 1;
        }

        $this->info('✅ AI service khả dụng!');

        // Test tạo ngân hàng câu hỏi
        $topic = $this->option('topic');
        $questions = (int) $this->option('questions');

        $this->info("Đang tạo ngân hàng câu hỏi về chủ đề: {$topic}");
        $this->info("Số câu hỏi: {$questions}");

        $result = $aiHelper->generateQuestionBank($topic, 'Tiếng Trung', $questions);

        if ($result && !empty($result['questions'])) {
            $this->info('✅ Tạo ngân hàng câu hỏi thành công!');
            $this->line('Thống kê:');
            $this->line('- Tổng câu hỏi: ' . count($result['questions']));
            $this->line('- Câu dễ: ' . ($result['statistics']['easy_count'] ?? 0));
            $this->line('- Câu trung bình: ' . ($result['statistics']['medium_count'] ?? 0));
            $this->line('- Câu khó: ' . ($result['statistics']['hard_count'] ?? 0));

            // Hiển thị một số câu hỏi mẫu
            $this->line('');
            $this->info('Một số câu hỏi mẫu:');
            foreach (array_slice($result['questions'], 0, 3) as $index => $question) {
                $this->line(($index + 1) . '. ' . $question['question']);
                $this->line('   Loại: ' . $question['type'] . ', Độ khó: ' . $question['difficulty']);
                $this->line('');
            }
        } else {
            $this->error('❌ Không thể tạo ngân hàng câu hỏi!');
            $this->line('Vui lòng kiểm tra log trong storage/logs/laravel.log');
            return 1;
        }

        return 0;
    }
}