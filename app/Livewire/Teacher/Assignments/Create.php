<?php

namespace App\Livewire\Teacher\Assignments;

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
    public $types = [];
    public $allTypes = [
        'text' => 'Điền từ',
        'essay' => 'Tự luận',
        'image' => 'Nộp ảnh',
        'audio' => 'Ghi âm',
        'video' => 'Quay video',
    ];
    public $classrooms = [];
    public $attachment;
    public $video;
    public $score;

    public function mount()
    {
        $user = Auth::user();
        $this->classrooms = $user->teachingClassrooms ?? Classroom::all();
    }

    public function createAssignment()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classrooms,id',
            'deadline' => 'required|date',
            'types' => 'required|array|min:1',
            'attachment' => 'nullable|file|mimes:doc,docx,pdf,zip,rar,txt|max:10240',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400',
            'score' => 'nullable|numeric|min:0',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài tập',
            'class_id.required' => 'Vui lòng chọn lớp',
            'deadline.required' => 'Vui lòng chọn hạn nộp',
            'types.required' => 'Vui lòng chọn ít nhất một loại bài tập',
            'types.min' => 'Vui lòng chọn ít nhất một loại bài tập',
            'attachment.max' => 'Tệp đính kèm tối đa 10MB',
            'attachment.mimes' => 'Chỉ chấp nhận file doc, docx, pdf, zip, rar, txt',
            'video.max' => 'Video tối đa 100MB',
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
            'types' => $this->types,
            'attachment_path' => $attachmentPath,
            'video_path' => $videoPath,
            'score' => $this->score,
        ]);

        session()->flash('success', 'Tạo bài tập thành công!');
        $this->reset(['title', 'description', 'class_id', 'deadline', 'types', 'attachment', 'video', 'score']);
    }

    public function render()
    {
        return view('teacher.assignments.create');
    }
}
