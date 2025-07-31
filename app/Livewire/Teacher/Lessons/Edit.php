<?php

namespace App\Livewire\Teacher\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $lesson;
    public $title = '';
    public $description = '';
    public $number = '';
    public $classroom_id = '';
    public $video = '';
    public $attachment;
    public $classrooms = [];
    public $currentAttachment = '';

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

    public function mount(Lesson $lesson)
    {
        $user = Auth::user();
        $this->classrooms = $user->teachingClassrooms;
        
        // Fallback: nếu teacher chưa được gán vào lớp nào, hiển thị tất cả lớp học
        if ($this->classrooms->isEmpty()) {
            $this->classrooms = Classroom::all();
        }
        
        // Kiểm tra xem teacher có quyền chỉnh sửa bài học này không
        $this->lesson = Lesson::whereIn('classroom_id', $this->classrooms->pluck('id'))
            ->findOrFail($lesson->id);
        
        $this->title = $this->lesson->title;
        $this->description = $this->lesson->description;
        $this->number = $this->lesson->number;
        $this->classroom_id = $this->lesson->classroom_id;
        $this->video = $this->lesson->video;
        $this->currentAttachment = $this->lesson->attachment;
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
            $this->lesson->title = $this->title;
            $this->lesson->description = $this->description;
            $this->lesson->number = $this->number;
            $this->lesson->classroom_id = $this->classroom_id;
            $this->lesson->video = $this->video;

            if ($this->attachment) {
                \Log::info('Uploading new file: ' . $this->attachment->getClientOriginalName());
                \Log::info('File size: ' . $this->attachment->getSize());
                \Log::info('File mime: ' . $this->attachment->getMimeType());
                
                // Xóa file cũ nếu có
                if ($this->currentAttachment) {
                    Storage::disk('public')->delete($this->currentAttachment);
                    \Log::info('Deleted old file: ' . $this->currentAttachment);
                }
                
                $path = $this->attachment->store('lessons/attachments', 'public');
                $this->lesson->attachment = $path;
                
                \Log::info('New file stored at: ' . $path);
            }

            $this->lesson->save();

            session()->flash('success', 'Đã cập nhật bài học thành công!');
            $this->dispatch('lessonUpdated');
            return redirect()->route('teacher.lessons.index');
            
        } catch (\Exception $e) {
            \Log::error('Error updating lesson: ' . $e->getMessage());
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật bài học: ' . $e->getMessage());
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
        return view('teacher.lessons.edit');
    }
} 