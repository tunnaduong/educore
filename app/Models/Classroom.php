<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'level', 'schedule'];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')->withPivot('role')->withTimestamps();
    }
}
