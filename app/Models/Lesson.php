<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'number', 'title', 'description', 'content', 'attachment', 'video', 'classroom_id'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
}
