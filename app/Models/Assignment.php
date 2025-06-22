<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AssignmentSubmission;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'deadline',
        'types',
    ];

    protected $casts = [
        'types' => 'array',
        'deadline' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
