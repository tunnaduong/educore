<?php

namespace App\Livewire\Admin\Assignments;

use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $assignment;

    public $title;

    public $description;

    public $class_id;

    public $deadline;

    public $types = [];

    public $allTypes = [
        'text' => 'general.text_type',
        'essay' => 'general.essay',
        'image' => 'general.image_type',
        'audio' => 'general.audio_type',
        'video' => 'general.video_type',
    ];

    public $classrooms = [];

    public $attachment;

    public $video;

    public $old_attachment_path;

    public $old_video_path;

    public $assignmentId;

    public $max_score;

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->assignment = Assignment::findOrFail($assignmentId);
        $this->title = $this->assignment->title;
        $this->description = $this->assignment->description;
        $this->class_id = $this->assignment->class_id;
        $this->deadline = $this->assignment->deadline?->format('Y-m-d\TH:i');
        $this->max_score = $this->assignment->max_score ?? 10.0;
        $this->types = $this->assignment->types ?? [];
        $this->old_attachment_path = $this->assignment->attachment_path;
        $this->old_video_path = $this->assignment->video_path;

        // Giữ key dịch để render theo locale hiện tại trong view
        $this->classrooms = Classroom::all();
    }

    public function updatedDeadline()
    {
        $this->validateOnly('deadline', [
            'deadline' => 'required|date|after:now',
        ], [
            'deadline.required' => 'Vui lòng chọn hạn nộp',
            'deadline.after' => 'Hạn nộp không được trong quá khứ',
        ]);
    }

    public function updateAssignment()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classrooms,id',
            'deadline' => 'required|date|after:now',
            'types' => 'required|array|min:1',
            'attachment' => 'nullable|file|mimes:doc,docx,pdf,zip,rar,txt|max:10240',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400',
            'max_score' => 'nullable|numeric|min:0|max:10',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài tập',
            'class_id.required' => 'Vui lòng chọn lớp',
            'deadline.required' => 'Vui lòng chọn hạn nộp',
            'deadline.after' => 'Hạn nộp không được trong quá khứ',
            'types.required' => 'Vui lòng chọn ít nhất một loại bài tập',
            'types.min' => 'Vui lòng chọn ít nhất một loại bài tập',
            'attachment.max' => 'Tệp đính kèm tối đa 10MB',
            'attachment.mimes' => 'Chỉ chấp nhận file doc, docx, pdf, zip, rar, txt',
            'video.max' => 'Video tối đa 100MB',
            'video.mimetypes' => 'Chỉ chấp nhận video mp4, avi, mpeg, mov',
            'max_score.numeric' => 'Điểm tối đa phải là số',
            'max_score.min' => 'Điểm tối đa phải lớn hơn hoặc bằng 0',
            'max_score.max' => 'Điểm tối đa không được vượt quá 10',
        ]);

        $attachmentPath = $this->old_attachment_path;
        $videoPath = $this->old_video_path;
        if ($this->attachment && is_object($this->attachment) && method_exists($this->attachment, 'store')) {
            if ($attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $this->attachment->store('assignments/attachments', 'public');
        }
        if ($this->video && is_object($this->video) && method_exists($this->video, 'store')) {
            if ($videoPath) {
                Storage::disk('public')->delete($videoPath);
            }
            $videoPath = $this->video->store('assignments/videos', 'public');
        }

        $data = [
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'types' => $this->types,
            'attachment_path' => $attachmentPath,
            'video_path' => $videoPath,
        ];

        // Chỉ thêm max_score nếu có giá trị
        if (! empty($this->max_score)) {
            $data['max_score'] = $this->max_score;
        }

        try {
            $this->assignment->update($data);
            session()->flash('success', 'Cập nhật bài tập thành công!');

            return redirect()->route('assignments.overview');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật bài tập: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('admin.assignments.edit');
    }
}
