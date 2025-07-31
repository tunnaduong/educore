<?php

namespace App\Livewire\Teacher\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $user = Auth::user();
        $this->classrooms = $user->teachingClassrooms;
        
        // Debug: Log thông tin
        \Log::info('Teacher ID: ' . $user->id);
        \Log::info('Teacher Role: ' . $user->role);
        \Log::info('Teaching Classrooms Count: ' . $this->classrooms->count());
        
        // Fallback: nếu teacher chưa được gán vào lớp nào, hiển thị tất cả lớp học
        if ($this->classrooms->isEmpty()) {
            $this->classrooms = Classroom::all();
            \Log::info('Fallback: Using all classrooms. Count: ' . $this->classrooms->count());
        }
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
            $lesson = new Lesson();
            $lesson->title = $this->title;
            $lesson->description = $this->description;
            $lesson->number = $this->number;
            $lesson->classroom_id = $this->classroom_id;
            $lesson->video = $this->video;

            if ($this->attachment) {
                \Log::info('Uploading file: ' . $this->attachment->getClientOriginalName());
                \Log::info('File size: ' . $this->attachment->getSize());
                \Log::info('File mime: ' . $this->attachment->getMimeType());
                
                $path = $this->attachment->store('lessons/attachments', 'public');
                $lesson->attachment = $path;
                
                \Log::info('File stored at: ' . $path);
            }

            $lesson->save();

            session()->flash('success', 'Đã tạo bài học thành công!');
            $this->dispatch('lessonCreated');
            return redirect()->route('teacher.lessons.index');
            
        } catch (\Exception $e) {
            \Log::error('Error creating lesson: ' . $e->getMessage());
            session()->flash('error', 'Có lỗi xảy ra khi tạo bài học: ' . $e->getMessage());
        }
    }

    public function updatedAttachment()
    {
        if ($this->attachment) {
            \Log::info('File selected: ' . $this->attachment->getClientOriginalName());
            \Log::info('File size: ' . $this->attachment->getSize());
            \Log::info('File mime: ' . $this->attachment->getMimeType());
            \Log::info('File extension: ' . $this->attachment->getClientOriginalExtension());
        }
    }

    public function render()
    {
        return view('teacher.lessons.create');
    }
} 