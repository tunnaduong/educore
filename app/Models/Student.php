<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'date_of_birth', 'joined_at', 'status', 'level', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
