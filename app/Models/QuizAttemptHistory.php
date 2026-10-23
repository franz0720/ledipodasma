<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptHistory extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempt_histories';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'score',
        'total_items',
        'percentage',
        'status',
        'timed_out',
    ];

    protected function casts(): array
    {
        return [
            'quiz_id'        => 'integer',
            'user_id'        => 'integer',
            'attempt_number' => 'integer',
            'score'          => 'integer',
            'total_items'    => 'integer',
            'percentage'     => 'decimal:2',
            'timed_out'      => 'boolean',
        ];
    }

    /**
     * Get the quiz associated with this attempt.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get the user who submitted this attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User_tbl::class, 'user_id');
    }
}
