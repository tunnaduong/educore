<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Classroom;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationReport extends Component
{
    use WithPagination;

    public $classroomId = '';
    public $selectedEvaluation = null;
    public $roundId = '';

    protected $queryString = ['classroomId', 'roundId'];

    public function mount()
    {
        // nothing
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
        $teacherId = Auth::id();

        // Lấy danh sách lớp mà giáo viên này đang dạy (từ bảng class_user, role = 'teacher')
        $teacherClassroomIds = DB::table('class_user')
            ->where('user_id', $teacherId)
            ->where('role', 'teacher')
            ->pluck('class_id')
            ->toArray();

        // Danh sách lớp cho dropdown chỉ gồm lớp giáo viên dạy
        $classrooms = Classroom::whereIn('id', $teacherClassroomIds)->get();

        // Nếu classroomId không thuộc các lớp giáo viên dạy, reset về rỗng
        if ($this->classroomId && !in_array((int)$this->classroomId, $teacherClassroomIds, true)) {
            $this->classroomId = '';
        }

        $query = Evaluation::with(['student.user']);

        // Chỉ lấy đánh giá của học viên thuộc các lớp giáo viên dạy
        $query->whereHas('student.user.enrolledClassrooms', function ($q) use ($teacherClassroomIds) {
            $q->whereIn('classrooms.id', $teacherClassroomIds);
        });

        // Lọc theo lớp cụ thể nếu được chọn
        if ($this->classroomId) {
            $classroomId = (int) $this->classroomId;
            $query->whereHas('student.user.enrolledClassrooms', function ($q) use ($classroomId) {
                $q->where('classrooms.id', $classroomId);
            });
        }

        // Lọc theo đợt đánh giá
        if ($this->roundId) {
            $query->where('evaluation_round_id', (int)$this->roundId);
        }

        $evaluations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Lấy danh sách đợt có dữ liệu trong phạm vi lớp giáo viên dạy
        $roundOptions = Evaluation::query()
            ->whereHas('student.user.enrolledClassrooms', function ($q) use ($teacherClassroomIds) {
                $q->whereIn('classrooms.id', $teacherClassroomIds);
            })
            ->select('evaluation_round_id')
            ->distinct()
            ->pluck('evaluation_round_id');

        $rounds = \App\Models\EvaluationRound::whereIn('id', $roundOptions)
            ->orderBy('start_date', 'desc')
            ->get();

        // Tính điểm trung bình trên trang hiện tại
        $avgTeacher = $evaluations->getCollection()->avg(function ($eva) {
            return $eva->getTeacherAverageRating();
        });
        $avgCourse = $evaluations->getCollection()->avg(function ($eva) {
            return $eva->getCourseAverageRating();
        });
        $avgPersonal = $evaluations->getCollection()->avg('personal_satisfaction');

        $questions = EvaluationQuestion::ordered()->get();

        return view('teacher.evaluation-report', [
            'evaluations' => $evaluations,
            'classrooms' => $classrooms,
            'questions' => $questions,
            'avgTeacher' => $avgTeacher ?: 0,
            'avgCourse' => $avgCourse ?: 0,
            'avgPersonal' => $avgPersonal ?: 0,
            'total' => $evaluations->total(),
            'rounds' => $rounds,
        ]);
    }
}
