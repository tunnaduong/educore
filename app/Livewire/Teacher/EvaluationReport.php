<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Classroom;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class EvaluationReport extends Component
{
    use WithPagination;

    public $classroomId = '';
    public $selectedEvaluation = null;

    protected $queryString = ['classroomId'];

    public function mount()
    {
        // Load classrooms for filter
    }

    public function loadEvaluations()
    {
        $this->resetPage();
    }

    public function showEvaluationDetail(int $evaluationId)
    {
        $this->selectedEvaluation = Evaluation::with('student.user')->find($evaluationId);
    }

    public function closeEvaluationDetail()
    {
        $this->selectedEvaluation = null;
    }

    public function render()
    {
        $query = Evaluation::with(['student.user']);

        if ($this->classroomId) {
            $query->whereHas('student', function ($q) {
                $q->where('classroom_id', $this->classroomId);
            });
        }

        $evaluations = $query->orderBy('created_at', 'desc')->paginate(10);
        $classrooms = Classroom::all();

        // Calculate averages
        $avgTeacher = $evaluations->avg(function ($eva) {
            return $eva->getTeacherAverageRating();
        });

        $avgCourse = $evaluations->avg(function ($eva) {
            return $eva->getCourseAverageRating();
        });

        $avgPersonal = $evaluations->avg('personal_satisfaction');

        $questions = EvaluationQuestion::ordered()->get();

        return view('teacher.evaluation-report', [
            'evaluations' => $evaluations,
            'classrooms' => $classrooms,
            'questions' => $questions,
            'avgTeacher' => $avgTeacher ?: 0,
            'avgCourse' => $avgCourse ?: 0,
            'avgPersonal' => $avgPersonal ?: 0,
            'total' => $evaluations->total(),
        ]);
    }
}
