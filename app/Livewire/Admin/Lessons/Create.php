<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $number, $title, $description, $video, $attachment;
    public $classroom_id;
    public $classrooms = [];

    protected $rules = [
        'number' => 'required',
        'title' => 'required',
        'description' => 'nullable',
        'video' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt',
        'classroom_id' => 'required|exists:classrooms,id',
    ];

    public function save()
    {
        $data = $this->validate();
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('lessons/attachments', 'public');
        }
        Lesson::create($data);
        $this->reset(['number', 'title', 'description', 'video', 'attachment', 'classroom_id']);
        $this->dispatch('lessonCreated');
        session()->flash('success', 'Đã thêm bài học thành công!');
    }

    public function render()
    {
        $this->classrooms = \App\Models\Classroom::all();
        return view('admin.lessons.create', [
            'classrooms' => $this->classrooms,
        ]);
    }
}
