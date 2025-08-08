<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
