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

        // New quiz builder fields
        'question_type',
        'options',
        'correct_answers',
        'points',
        'feedback',
        'required',

        // Existing fields - kept for old quizzes
        'choice_a',
        'choice_b',
        'choice_c',
        'choice_d',
        'correct_answer',

        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'required' => 'boolean',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}