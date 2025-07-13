<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $number, $title, $description, $video, $attachment;

    protected $rules = [
        'number' => 'required',
        'title' => 'required',
        'description' => 'nullable',
        'video' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt',
    ];

    public function save()
    {
        $data = $this->validate();
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('lessons/attachments', 'public');
        }
        Lesson::create($data);
        $this->reset(['number', 'title', 'description', 'video', 'attachment']);
        $this->dispatch('lessonCreated');
        session()->flash('success', 'Đã thêm bài học thành công!');
    }

    public function render()
    {
        return view('admin.lessons.create');
    }
}
