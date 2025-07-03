<?php

namespace App\Livewire\Admin\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $class_id;
    public $deadline;
    public $type = null; // Đổi từ $types = [] sang $type = null
    public $allTypes = [
        'multiple_choice' => 'Trắc nghiệm',
        'text' => 'Tự luận',
        'upload_image' => 'Nộp ảnh',
        'record' => 'Ghi âm',
        'video' => 'Quay video',
    ];

    public $classrooms = [];
    public $attachment;
    public $video;
    public $score;

    public function mount()
    {
        $user = Auth::user();
        // Nếu là admin thì lấy tất cả lớp, nếu là giáo viên thì chỉ lấy lớp mình dạy
        if ($user->role === 'admin') {
            $this->classrooms = Classroom::all();
        } else {
            $this->classrooms = $user->teachingClassrooms;
        }
    }

    public function createAssignment()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classrooms,id',
            'deadline' => 'required|date',
            'type' => 'required', // Đổi từ 'types' => ... sang 'type' => 'required'
            'attachment' => 'nullable|file|mimes:doc,docx,pdf,zip,rar,txt|max:10240',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:51200',
            'score' => 'nullable|numeric|min:0',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài tập',
            'class_id.required' => 'Vui lòng chọn lớp',
            'deadline.required' => 'Vui lòng chọn hạn nộp',
            'type.required' => 'Vui lòng chọn một loại bài tập', // Đổi từ types sang type
            'attachment.max' => 'Tệp đính kèm tối đa 10MB',
            'attachment.mimes' => 'Chỉ chấp nhận file doc, docx, pdf, zip, rar, txt',
            'video.max' => 'Video tối đa 50MB',
            'video.mimetypes' => 'Chỉ chấp nhận video mp4, avi, mpeg, mov',
            'score.numeric' => 'Điểm tối đa phải là số',
            'score.min' => 'Điểm tối đa phải lớn hơn hoặc bằng 0',
        ]);

        $attachmentPath = null;
        $videoPath = null;
        if ($this->attachment && is_object($this->attachment) && method_exists($this->attachment, 'store')) {
            $attachmentPath = $this->attachment->store('assignments/attachments', 'public');
        }
        if ($this->video && is_object($this->video) && method_exists($this->video, 'store')) {
            $videoPath = $this->video->store('assignments/videos', 'public');
        }

        Assignment::create([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'types' => $this->type, // Lưu $type thay vì $types
            'attachment_path' => $attachmentPath,
            'video_path' => $videoPath,
            'score' => $this->score,
        ]);

        session()->flash('success', 'Tạo bài tập thành công!');
        $this->reset(['title', 'description', 'class_id', 'deadline', 'type', 'attachment', 'video', 'score']); // Đổi 'types' thành 'type'
    }

    public function render()
    {
        return view('admin.assignments.create');
    }
}
