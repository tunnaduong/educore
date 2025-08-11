<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $lesson;
    public $number, $title, $description, $video, $attachment, $oldAttachment;
    public $classroom_id;
    public $classrooms = [];

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->number = $lesson->number;
        $this->title = $lesson->title;
        $this->description = $lesson->description;
        $this->video = $lesson->video;
        $this->oldAttachment = $lesson->attachment;
        $this->classroom_id = $lesson->classroom_id;
    }

    protected $rules = [
        'number' => 'required',
        'title' => 'required',
        'description' => 'nullable',
        'video' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt',
        'classroom_id' => 'required|exists:classrooms,id',
    ];

    public function update()
    {
        $data = $this->validate();
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('lessons/attachments', 'public');
        } else if ($this->oldAttachment) {
            $data['attachment'] = $this->oldAttachment;
        } else {
            session()->flash('error', 'Bạn phải upload file tài liệu!');
            return;
        }
        $this->lesson->update($data);
        session()->flash('success', 'Cập nhật bài học thành công!');
        return $this->redirect(route('lessons.index'), true);
    }

    public function render()
    {
        $this->classrooms = \App\Models\Classroom::all();
        return view('admin.lessons.edit', [
            'classrooms' => $this->classrooms,
        ]);
    }
}
