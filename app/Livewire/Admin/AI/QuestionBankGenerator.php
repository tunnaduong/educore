<?php

namespace App\Livewire\Admin\AI;

use App\Helpers\AIHelper;
use App\Models\QuestionBank;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuestionBankGenerator extends Component
{
    public $name = '';

    public $description = '';

    public $topic = '';

    public $maxQuestions = 15;

    public $generatedBank = null;

    public $isProcessing = false;

    public $showPreview = false;

    public function mount()
    {
        // Không cần danh sách môn học vì chỉ dạy tiếng Trung
    }

    public function generateQuestionBank()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'topic' => 'required|string|max:255',
            'maxQuestions' => 'required|integer|min:10|max:100',
        ]);

        $this->isProcessing = true;
        $this->generatedBank = null;

        try {
            $aiHelper = new AIHelper;

            if (! $aiHelper->isAIAvailable()) {
                session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API key trong file .env (GEMINI_API_KEY=your_api_key_here)');
                $this->isProcessing = false;

                return;
            }

            $result = $aiHelper->generateQuestionBank(
                $this->topic,
                'Tiếng Trung', // Cố định là tiếng Trung
                $this->maxQuestions
            );

            if ($result && ! empty($result['questions'])) {
                $this->generatedBank = $result;
                $this->showPreview = true;
                session()->flash('success', 'Đã tạo ngân hàng câu hỏi tiếng Trung bằng AI thành công!');
            } else {
                session()->flash('error', 'Không thể tạo ngân hàng câu hỏi. Vui lòng kiểm tra API key và thử lại. Xem log trong storage/logs/laravel.log để biết chi tiết.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: '.$e->getMessage().'. Vui lòng kiểm tra log trong storage/logs/laravel.log');
        }

        $this->isProcessing = false;
    }

    public function saveQuestionBank()
    {
        if (! $this->generatedBank) {
            session()->flash('error', 'Không có ngân hàng câu hỏi để lưu.');

            return;
        }

        try {
            $questionBank = QuestionBank::create([
                'name' => $this->name,
                'description' => $this->description,
                'subject' => 'Tiếng Trung', // Cố định là tiếng Trung
                'topic' => $this->topic,
                'questions' => $this->generatedBank['questions'],
                'statistics' => $this->generatedBank['statistics'],
                'ai_generated' => true,
                'ai_generation_params' => [
                    'max_questions' => $this->maxQuestions,
                    'subject' => 'Tiếng Trung',
                    'topic' => $this->topic,
                ],
                'ai_generated_at' => now(),
                'created_by' => Auth::id(),
            ]);

            session()->flash('success', 'Đã lưu ngân hàng câu hỏi thành công!');
            $this->showPreview = false;
            $this->generatedBank = null;

            // Reset form
            $this->reset(['name', 'description', 'topic', 'maxQuestions']);
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi lưu ngân hàng câu hỏi: '.$e->getMessage());
        }
    }

    public function createQuizFromBank()
    {
        if (! $this->generatedBank) {
            session()->flash('error', 'Không có ngân hàng câu hỏi để tạo quiz.');

            return;
        }

        // Tạo quiz tạm thời từ ngân hàng câu hỏi
        $tempBank = (object) [
            'id' => 'temp',
            'name' => $this->name,
            'questions' => $this->generatedBank['questions'],
        ];

        // Redirect đến trang tạo quiz với dữ liệu ngân hàng câu hỏi
        session()->flash('temp_question_bank', [
            'name' => $this->name,
            'questions' => $this->generatedBank['questions'],
            'statistics' => $this->generatedBank['statistics'],
        ]);

        return redirect()->route('quizzes.create');
    }

    public function render()
    {
        return view('admin.ai.question-bank-generator', [
            'name' => $this->name,
            'description' => $this->description,
            'topic' => $this->topic,
            'maxQuestions' => $this->maxQuestions,
            'generatedBank' => $this->generatedBank,
            'isProcessing' => $this->isProcessing,
            'showPreview' => $this->showPreview,
        ]);
    }
}
