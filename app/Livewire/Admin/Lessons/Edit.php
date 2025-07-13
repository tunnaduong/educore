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

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->number = $lesson->number;
        $this->title = $lesson->title;
        $this->description = $lesson->description;
        $this->video = $lesson->video;
        $this->oldAttachment = $lesson->attachment;
    }

    protected $rules = [
        'number' => 'required',
        'title' => 'required',
        'description' => 'nullable',
        'video' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt',
    ];

    public function update()
    {
        $data = $this->validate();
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('lessons/attachments', 'public');
        } else {
            $data['attachment'] = $this->oldAttachment;
        }
        $this->lesson->update($data);
        session()->flash('success', 'Cập nhật bài học thành công!');
        return redirect()->route('lessons.index');
    }

    public function render()
    {
        return view('admin.lessons.edit');
    }
}
