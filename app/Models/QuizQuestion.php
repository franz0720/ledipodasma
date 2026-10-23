<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'question_type',
        'options',
        'correct_answers',
        'points',
        'feedback',
        'required',

        // Legacy fields for existing quizzes
        'choice_a',
        'choice_b',
        'choice_c',
        'choice_d',
        'correct_answer',

        'order',
    ];

    protected $casts = [
        'quiz_id'        => 'integer',
        'options'        => 'array',
        'correct_answers' => 'array',
        'points'         => 'integer',
        'required'       => 'boolean',
        'order'          => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
