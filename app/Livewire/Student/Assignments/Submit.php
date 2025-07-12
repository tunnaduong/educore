<?php

namespace App\Livewire\Student\Assignments;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Submit extends Component
{
    use WithFileUploads;

    public Assignment $assignment;
    public $assignmentId;
    public $content = '';
    public $essay = '';
    public $imageFile;
    public $audioFile;
    public $videoFile;
    public $submissionType = 'text';

    protected function rules()
    {
        switch ($this->submissionType) {
            case 'text':
                return [
                    'content' => 'required|string|max:10000',
                ];
            case 'essay':
                return [
                    'essay' => 'required|string|max:50000',
                ];
            case 'image':
                return [
                    'imageFile' => 'required|image|max:10240', // 10MB
                ];
            case 'audio':
                return [
                    'audioFile' => 'required|file|mimes:mp3,wav,m4a|max:51200', // 50MB
                ];
            case 'video':
                return [
                    'videoFile' => 'required|file|mimes:mp4,avi,mov|max:102400', // 100MB
                ];
            default:
                return [];
        }
    }

    protected $messages = [
        'content.required_if' => 'Vui lòng nhập nội dung bài tập',
        'essay.required_if' => 'Vui lòng nhập nội dung bài luận',
        'essay.max' => 'Nội dung bài luận không được vượt quá 50,000 ký tự',
        'imageFile.required_if' => 'Vui lòng tải lên ảnh bài viết',
        'imageFile.image' => 'File phải là hình ảnh',
        'imageFile.max' => 'Kích thước ảnh không được vượt quá 10MB',
        'audioFile.required_if' => 'Vui lòng tải lên file âm thanh',
        'audioFile.mimes' => 'File âm thanh phải có định dạng mp3, wav, hoặc m4a',
        'audioFile.max' => 'Kích thước file âm thanh không được vượt quá 50MB',
        'videoFile.required_if' => 'Vui lòng tải lên file video',
        'videoFile.mimes' => 'File video phải có định dạng mp4, avi, hoặc mov',
        'videoFile.max' => 'Kích thước file video không được vượt quá 100MB',
    ];

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->loadAssignment();
    }

    public function loadAssignment()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        $this->assignment = Assignment::whereHas('classroom.students', function ($q) use ($student) {
            $q->where('users.id', $student->user_id);
        })
        ->with(['submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
        ->findOrFail($this->assignmentId);

        // Chỉ kiểm tra quá hạn, cho phép nộp lại nhiều lần trước hạn
        if ($this->assignment->deadline < now()) {
            session()->flash('error', 'Bài tập đã quá hạn, không thể nộp');
            return redirect()->route('student.assignments.show', $this->assignmentId);
        }

        // Set default submission type based on assignment types
        if ($this->assignment->types && in_array('image', $this->assignment->types)) {
            $this->submissionType = 'image';
        } elseif ($this->assignment->types && in_array('audio', $this->assignment->types)) {
            $this->submissionType = 'audio';
        } elseif ($this->assignment->types && in_array('video', $this->assignment->types)) {
            $this->submissionType = 'video';
        }
    }

    public function updatedSubmissionType()
    {
        $this->resetValidation();
        $this->content = '';
        $this->essay = '';
        $this->imageFile = null;
        $this->audioFile = null;
        $this->videoFile = null;
    }

    public function submitAssignment()
    {
        Log::info('Bắt đầu submitAssignment', [
            'submissionType' => $this->submissionType,
            'content' => $this->content,
            'essay' => $this->essay,
            'imageFile' => $this->imageFile ? 'Có file' : 'Không',
            'audioFile' => $this->audioFile ? 'Có file' : 'Không',
            'videoFile' => $this->videoFile ? 'Có file' : 'Không',
        ]);

        try {
            $this->validate();
            Log::info('Validate thành công');
        } catch (\Exception $e) {
            Log::error('Validate thất bại', ['error' => $e->getMessage()]);
            throw $e;
        }

        $student = Auth::user()->student;
        if (!$student) {
            Log::error('Không tìm thấy student');
            session()->flash('error', 'Bạn không có quyền truy cập');
            return;
        }

        // Kiểm tra đã nộp dạng này chưa
        $existing = AssignmentSubmission::where('assignment_id', $this->assignment->id)
            ->where('student_id', $student->id)
            ->where('submission_type', $this->submissionType)
            ->first();
        if ($existing) {
            session()->flash('error', 'Bạn đã nộp dạng này rồi!');
            return;
        }

        try {
            $submissionData = [
                'assignment_id' => $this->assignment->id,
                'student_id' => $student->id,
                'submission_type' => $this->submissionType,
                'submitted_at' => now(),
            ];

            Log::info('Chuẩn bị lưu submission', ['submissionData' => $submissionData]);

            // Handle different submission types
            switch ($this->submissionType) {
                case 'text':
                    $submissionData['content'] = $this->content;
                    break;
                case 'essay':
                    $submissionData['content'] = $this->essay;
                    break;
                case 'image':
                    $path = $this->imageFile->store('assignments/images', 'public');
                    $submissionData['content'] = $path;
                    break;
                case 'audio':
                    $path = $this->audioFile->store('assignments/audio', 'public');
                    $submissionData['content'] = $path;
                    break;
                case 'video':
                    $path = $this->videoFile->store('assignments/video', 'public');
                    $submissionData['content'] = $path;
                    break;
            }

            Log::info('Dữ liệu cuối cùng trước khi lưu', ['submissionData' => $submissionData]);

            AssignmentSubmission::create($submissionData);

            Log::info('Nộp bài thành công!');
            session()->flash('success', 'Nộp bài tập thành công!');
            return $this->redirect(route('student.assignments.show', $this->assignmentId), navigate: true);

        } catch (\Exception $e) {
            Log::error('Có lỗi xảy ra khi nộp bài tập', ['error' => $e->getMessage()]);
            session()->flash('error', 'Có lỗi xảy ra khi nộp bài tập: ' . $e->getMessage());
        }
    }

    public function isOverdue()
    {
        return $this->assignment->deadline < now();
    }

    public function getTimeRemaining()
    {
        if ($this->isOverdue()) {
            return 'Đã quá hạn ' . $this->assignment->deadline->diffForHumans();
        }

        return 'Còn lại ' . $this->assignment->deadline->diffForHumans(now(), ['parts' => 2]);
    }

    public function render()
    {
        return view('student.assignments.submit');
    }
}
