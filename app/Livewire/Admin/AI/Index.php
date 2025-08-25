<?php

namespace App\Livewire\Admin\AI;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $recentSubmissions = [];

    public $availableAssignments = [];

    public $classrooms = [];

    public function mount()
    {
        $user = Auth::user();
        if (! $user) {
            // Không có user đăng nhập, để trống dữ liệu
            $this->recentSubmissions = collect();
            $this->availableAssignments = collect();
            $this->classrooms = collect();
            return;
        }
        /** @var UserModel $user */
        $user = $user;

        // Nếu là admin: xem toàn hệ thống. Nếu là giáo viên: giới hạn theo lớp dạy.
        $isAdmin = $user && $user->role === 'admin';

        if ($isAdmin) {
            // Admin thấy tất cả bài nộp có thể chấm bằng AI
            $this->recentSubmissions = AssignmentSubmission::whereHas('assignment')
                ->whereNotNull('submitted_at')
                ->whereNotIn('submission_type', ['image', 'audio', 'video'])
                ->where(function ($query) {
                    $query->whereNull('score')
                        ->whereNull('ai_score');
                })
                ->with(['assignment', 'student'])
                ->latest()
                ->take(5)
                ->get();

            // Admin thấy các bài tập gần đây để tạo quiz
            $this->availableAssignments = Assignment::with('classroom')
                ->latest()
                ->take(10)
                ->get();

            // Admin thấy tất cả lớp
            $this->classrooms = Classroom::with('students')->get();
        } else {
            // Giáo viên: theo lớp đang dạy
            $teacherClassroomIds = $user->teachingClassrooms()->pluck('classrooms.id');

            $this->recentSubmissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($teacherClassroomIds) {
                $query->whereIn('class_id', $teacherClassroomIds);
            })
                ->whereNotNull('submitted_at')
                ->whereNotIn('submission_type', ['image', 'audio', 'video'])
                ->where(function ($query) {
                    $query->whereNull('score')
                        ->whereNull('ai_score');
                })
                ->with(['assignment', 'student'])
                ->latest()
                ->take(5)
                ->get();

            $this->availableAssignments = Assignment::whereIn('class_id', $teacherClassroomIds)
                ->with('classroom')
                ->latest()
                ->take(10)
                ->get();

            $this->classrooms = $user->teachingClassrooms()->with('students')->get();
        }
    }

    public function render()
    {
        return view('admin.ai.index', [
            'recentSubmissions' => $this->recentSubmissions,
            'availableAssignments' => $this->availableAssignments,
            'classrooms' => $this->classrooms,
        ]);
    }
}
