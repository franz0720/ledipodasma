<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'instructions',
        'quiz_type',
        'passing_score',
        'time_limit',
        'attempts_allowed',
    ];

    protected $casts = [
        'course_id'        => 'integer',
        'module_id'        => 'integer',
        'passing_score'    => 'integer',
        'time_limit'       => 'integer',
        'attempts_allowed' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course_tbl::class, 'course_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')
                    ->orderBy('order');
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    public function attemptHistories(): HasMany
    {
        return $this->hasMany(QuizAttemptHistory::class, 'quiz_id');
    }
}
