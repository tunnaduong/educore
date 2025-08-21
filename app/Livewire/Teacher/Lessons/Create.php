<?php

namespace App\Livewire\Teacher\Lessons;

use App\Models\Classroom;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';

    public $description = '';

    public $number = '';

    public $classroom_id = '';

    public $video = '';

    public $attachment;

    public $classrooms = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'number' => 'nullable|string|max:50',
        'classroom_id' => 'required|exists:classrooms,id',
        'video' => 'nullable|url',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài học.',
        'classroom_id.required' => 'Vui lòng chọn lớp học.',
        'classroom_id.exists' => 'Lớp học không tồn tại.',
        'video.url' => 'Link video không hợp lệ.',
    ];

    public function mount()
    {
        // Chỉ lấy các lớp học mà giáo viên hiện tại đã tham gia
        $this->classrooms = Classroom::whereHas('teachers', function ($query) {
            $query->where('users.id', Auth::id());
        })->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        // Validate file upload
        if ($this->attachment) {
            $this->validate([
                'attachment' => 'file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt|max:10240',
            ], [
                'attachment.file' => 'File đính kèm không hợp lệ.',
                'attachment.mimes' => 'File đính kèm phải là: pdf, doc, docx, ppt, pptx, xls, xlsx, txt.',
                'attachment.max' => 'File đính kèm không được vượt quá 10MB.',
            ]);
        }

        try {
            $lesson = new Lesson;
            $lesson->title = $this->title;
            $lesson->description = $this->description;
            $lesson->number = $this->number;
            $lesson->classroom_id = $this->classroom_id;
            $lesson->video = $this->video;

            if ($this->attachment) {
                Log::info('Uploading file: '.$this->attachment->getClientOriginalName());
                Log::info('File size: '.$this->attachment->getSize());
                Log::info('File mime: '.$this->attachment->getMimeType());

                $path = $this->attachment->store('lessons/attachments', 'public');
                $lesson->attachment = $path;

                Log::info('File stored at: '.$path);
            }

            $lesson->save();

            session()->flash('success', 'Đã tạo bài học thành công!');
            $this->dispatch('lessonCreated');

            return redirect()->route('teacher.lessons.index');
        } catch (\Exception $e) {
            Log::error('Error creating lesson: '.$e->getMessage());
            session()->flash('error', 'Có lỗi xảy ra khi tạo bài học: '.$e->getMessage());
        }
    }

    public function updatedAttachment()
    {
        if ($this->attachment) {
            Log::info('File selected: '.$this->attachment->getClientOriginalName());
            Log::info('File size: '.$this->attachment->getSize());
            Log::info('File mime: '.$this->attachment->getMimeType());
            Log::info('File extension: '.$this->attachment->getClientOriginalExtension());
        }
    }

    public function render()
    {
        return view('teacher.lessons.create', [
            'classrooms' => $this->classrooms,
        ]);
    }
}
