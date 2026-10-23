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
        'attempt_number',
        'attempts_used',
        'score',
        'total_items',
        'percentage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quiz_id'       => 'integer',
            'user_id'       => 'integer',
            'attempt_number' => 'integer',
            'attempts_used' => 'integer',
            'score'         => 'integer',
            'total_items'   => 'integer',
            'percentage'    => 'float',
        ];
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(User_tbl::class, 'user_id');
    }
}
