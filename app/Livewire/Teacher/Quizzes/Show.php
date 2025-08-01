<?php

namespace App\Livewire\Teacher\Quizzes;

use Livewire\Component;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public Quiz $quiz;

    public function mount($quizId)
    {
        $this->quiz = Quiz::with(['classroom', 'results'])->findOrFail($quizId);
        
        // Kiểm tra quyền xem
        $teacherClassIds = Auth::user()->teachingClassrooms->pluck('id');
        if (!$teacherClassIds->contains($this->quiz->class_id)) {
            session()->flash('error', 'Bạn không có quyền xem bài kiểm tra này.');
            return redirect()->route('teacher.quizzes.index');
        }
    }

    public function render()
    {
        $questions = $this->quiz->questions ?? [];
        $results = $this->quiz->results()->with('student.user')->get();
        
        return view('teacher.quizzes.show', [
            'questions' => $questions,
            'results' => $results,
        ]);
    }
}
