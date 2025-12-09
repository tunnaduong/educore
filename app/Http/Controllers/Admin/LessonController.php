<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'number' => 'required',
      'title' => 'required',
      'description' => 'nullable',
      'video' => 'nullable|string',
      'attachment' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt',
      'classroom_id' => 'required|exists:classrooms,id',
    ]);

    $data = $request->only(['number', 'title', 'description', 'video', 'classroom_id']);

    if ($request->hasFile('attachment')) {
      $data['attachment'] = $request->file('attachment')->store('lessons/attachments', 'public');
    }

    Lesson::create($data);

    return redirect()->route('lessons.create')->with('success', 'Đã thêm bài học thành công!');
  }
}
