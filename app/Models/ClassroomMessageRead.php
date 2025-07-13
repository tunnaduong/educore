<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomMessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'last_read_message_id',
        'last_read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }
}
