<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'deadline',
        'types',
        'attachment_path',
        'video_path',
        'max_score',
        // Optional AI/Grading config fields (if present in DB)
        'grading_criteria',
        'ai_analysis_enabled',
        'ai_analysis_config',
    ];

    protected $casts = [
        'types' => 'array',
        'deadline' => 'datetime',
        'max_score' => 'decimal:1',
        'grading_criteria' => 'array',
        'ai_analysis_enabled' => 'boolean',
        'ai_analysis_config' => 'array',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && now()->isAfter($this->deadline);
    }

    /**
     * Set the max_score attribute.
     */
    public function setMaxScoreAttribute($value)
    {
        $this->attributes['max_score'] = $value === '' || $value === null ? null : $value;
    }
}
