<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
