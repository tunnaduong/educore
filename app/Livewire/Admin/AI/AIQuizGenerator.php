<?php

namespace App\Livewire\Admin\AI;

use App\Helpers\AIHelper;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AIQuizGenerator extends Component
{
  public $selectedLesson = null;

  public $selectedClass = null;

  public $questionCount = 10;

  public $difficulty = 'medium';

  public $quizTitle = '';

  public $generatedQuiz = null;

  public $isProcessing = false;

  public $showPreview = false;

  public $classes = [];

  public function mount()
  {
    // Admin: hiển thị tất cả lớp học
    $this->classes = Classroom::all();
  }

  public function generateQuiz()
  {
    $this->validate([
      'selectedLesson' => 'required|exists:lessons,id',
      'selectedClass' => 'required|exists:classrooms,id',
      'questionCount' => 'required|integer|min:5|max:50',
      'difficulty' => 'required|in:easy,medium,hard',
      'quizTitle' => 'required|string|min:3|max:255',
    ]);

    $this->isProcessing = true;
    $this->generatedQuiz = null;

    try {
      $lesson = Lesson::findOrFail($this->selectedLesson);
      $aiHelper = new AIHelper;

      // Debug nội dung bài học
      Log::info('Lesson content debug', [
        'lesson_id' => $lesson->id,
        'lesson_title' => $lesson->title,
        'content' => $lesson->content,
        'content_length' => strlen($lesson->content ?? ''),
        'content_empty' => empty($lesson->content),
        'description' => $lesson->description,
        'description_length' => strlen($lesson->description ?? ''),
        'video' => $lesson->video,
        'attachment' => $lesson->attachment,
        'all_fields' => $lesson->toArray(),
      ]);

      // Sử dụng description nếu content trống
      $lessonContent = $lesson->content;
      if (empty($lessonContent) && !empty($lesson->description)) {
        $lessonContent = $lesson->description;
        Log::info('Using description as content', ['description_length' => strlen($lessonContent)]);
      }

      if (empty($lessonContent)) {
        session()->flash('error', 'Bài học không có nội dung. Vui lòng thêm nội dung hoặc mô tả vào bài học trước khi tạo quiz. (Debug: content_length = ' . strlen($lesson->content ?? '') . ', description_length = ' . strlen($lesson->description ?? '') . ')');
        $this->isProcessing = false;

        return;
      }

      if (!$aiHelper->isAIAvailable()) {
        session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API.');
        $this->isProcessing = false;

        return;
      }

      // Tạo lesson object với content đã được xử lý
      $lessonWithContent = clone $lesson;
      $lessonWithContent->content = $lessonContent;

      $result = $aiHelper->generateQuizFromLesson(
        $lessonWithContent,
        $this->questionCount,
        $this->difficulty
      );

      if ($result && !empty($result['questions'])) {
        $this->generatedQuiz = $result;
        $this->showPreview = true;
        session()->flash('success', 'Đã tạo quiz tiếng Trung bằng AI thành công!');
      } else {
        // Debug chi tiết nếu thất bại
        Log::error('AI Quiz Generation failed', [
          'lesson_id' => $lesson->id,
          'lesson_title' => $lesson->title,
          'lesson_content_length' => strlen($lesson->content ?? ''),
          'question_count' => $this->questionCount,
          'difficulty' => $this->difficulty,
          'result' => $result,
          'has_questions' => !empty($result['questions'] ?? []),
        ]);

        session()->flash('error', 'Không thể tạo quiz. Vui lòng kiểm tra log để biết chi tiết lỗi.');
      }
    } catch (\Exception $e) {
      session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }

    $this->isProcessing = false;
  }

  public function saveQuiz()
  {
    if (!$this->generatedQuiz) {
      session()->flash('error', 'Không có quiz để lưu.');

      return;
    }

    try {
      $quiz = Quiz::create([
        'class_id' => $this->selectedClass,
        'title' => $this->quizTitle,
        'description' => 'Quiz tiếng Trung được tạo tự động từ bài học bằng AI',
        'questions' => $this->generatedQuiz['questions'],
        'deadline' => now()->addDays(7),
        'assigned_date' => now(),
        'time_limit' => $this->generatedQuiz['estimated_time'] ?? 30,
        'ai_generated' => true,
        'ai_generation_source' => "lesson_{$this->selectedLesson}",
        'ai_generation_params' => [
          'question_count' => $this->questionCount,
          'difficulty' => $this->difficulty,
          'lesson_title' => Lesson::find($this->selectedLesson)->title ?? '',
        ],
        'ai_generated_at' => now(),
      ]);

      session()->flash('success', 'Đã lưu quiz tiếng Trung thành công!');
      $this->showPreview = false;
      $this->generatedQuiz = null;

      // Redirect đến trang quản lý quiz (admin)
      return redirect()->route('quizzes.index');
    } catch (\Exception $e) {
      session()->flash('error', 'Có lỗi xảy ra khi lưu quiz: ' . $e->getMessage());
    }
  }

  public function validateQuiz()
  {
    if (!$this->generatedQuiz) {
      session()->flash('error', 'Không có quiz để kiểm tra.');

      return;
    }

    $this->isProcessing = true;

    try {
      $aiHelper = new AIHelper;

      if (!$aiHelper->isAIAvailable()) {
        session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API.');
        $this->isProcessing = false;

        return;
      }

      $result = $aiHelper->validateQuizWithAI((object) [
        'questions' => $this->generatedQuiz['questions'],
      ]);

      if ($result) {
        $this->generatedQuiz['questions'] = $result['fixed_questions'] ?? $this->generatedQuiz['questions'];
        session()->flash('success', 'Đã kiểm tra và sửa lỗi quiz tiếng Trung!');
      } else {
        session()->flash('error', 'Không thể kiểm tra quiz. Vui lòng thử lại.');
      }
    } catch (\Exception $e) {
      session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }

    $this->isProcessing = false;
  }

  public function updatedSelectedClass()
  {
    $this->selectedLesson = null;
    $this->generatedQuiz = null;
    $this->showPreview = false;
  }

  public function updatedSelectedLesson()
  {
    $this->generatedQuiz = null;
    $this->showPreview = false;

    if ($this->selectedLesson) {
      $lesson = Lesson::find($this->selectedLesson);
      if ($lesson && empty($this->quizTitle)) {
        $this->quizTitle = 'Quiz tiếng Trung - ' . $lesson->title;
      }
    }
  }

  public function render()
  {
    $lessons = collect();
    if ($this->selectedClass) {
      $lessons = Lesson::where('classroom_id', $this->selectedClass)->get();
    }

    return view('admin.ai.ai-quiz-generator', [
      'classes' => $this->classes,
      'lessons' => $lessons,
      'selectedLesson' => $this->selectedLesson,
      'selectedClass' => $this->selectedClass,
      'questionCount' => $this->questionCount,
      'difficulty' => $this->difficulty,
      'quizTitle' => $this->quizTitle,
      'generatedQuiz' => $this->generatedQuiz,
      'isProcessing' => $this->isProcessing,
      'showPreview' => $this->showPreview,
    ])->layout('components.layouts.admin', ['active' => 'ai']);
  }
}
