<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'staff_id',
        'class_id',
        'amount',
        'type',
        'note',
        'spent_at'
    ];

    protected $casts = [
        'spent_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
