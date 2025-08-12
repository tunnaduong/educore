<?php

namespace App\Livewire\Teacher\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\Classroom;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

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
        'text' => 'Điền từ',
        'essay' => 'Tự luận',
        'image' => 'Nộp ảnh',
        'audio' => 'Ghi âm',
        'video' => 'Quay video',
    ];
    public $classrooms = [];
    public $attachment;
    public $video;
    public $old_attachment_path;
    public $old_video_path;
    public $assignmentId;
    public $score;

    public function mount($assignment)
    {
        $this->assignmentId = $assignment;
        $this->assignment = Assignment::findOrFail($assignment);
        $this->title = $this->assignment->title;
        $this->description = $this->assignment->description;
        $this->class_id = $this->assignment->class_id;
        $this->deadline = $this->assignment->deadline?->format('Y-m-d\TH:i');
        $this->score = $this->assignment->score ?? null;
        $this->types = $this->assignment->types ?? [];
        $this->old_attachment_path = $this->assignment->attachment_path;
        $this->old_video_path = $this->assignment->video_path;
        $this->classrooms = Classroom::all();
    }

    public function updateAssignment()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classrooms,id',
            'deadline' => 'required|date',
            'types' => 'required|array|min:1',
            'attachment' => 'nullable|file|mimes:doc,docx,pdf,zip,rar,txt|max:10240',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:102400',
        ]);

        $attachmentPath = $this->old_attachment_path;
        $videoPath = $this->old_video_path;
        if ($this->attachment && is_object($this->attachment) && method_exists($this->attachment, 'store')) {
            if ($attachmentPath)
                Storage::disk('public')->delete($attachmentPath);
            $attachmentPath = $this->attachment->store('assignments/attachments', 'public');
        }
        if ($this->video && is_object($this->video) && method_exists($this->video, 'store')) {
            if ($videoPath)
                Storage::disk('public')->delete($videoPath);
            $videoPath = $this->video->store('assignments/videos', 'public');
        }

        $this->assignment->update([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'score' => $this->score,
            'types' => $this->types,
            'attachment_path' => $attachmentPath,
            'video_path' => $videoPath,
        ]);

        session()->flash('success', 'Cập nhật bài tập thành công!');
        return redirect()->route('teacher.assignments.index');
    }

    public function render()
    {
        return view('teacher.assignments.edit');
    }
}
