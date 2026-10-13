<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_items',
        'percentage',
        'status',
        'attempt_number',
        'attempts_used',
    ];

    protected $casts = [
        'quiz_id' => 'integer',
        'user_id' => 'integer',
        'score' => 'integer',
        'total_items' => 'integer',
        'percentage' => 'decimal:2',
        'attempt_number' => 'integer',
        'attempts_used' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User_tbl::class, 'user_id');
    }
}
