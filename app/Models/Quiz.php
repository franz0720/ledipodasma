<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'passing_score' => 'integer',
        'time_limit' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course_tbl::class, 'course_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
}