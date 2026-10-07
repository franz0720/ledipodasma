<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HandsOnEvaluation extends Model
{
    protected $table = 'hands_on_evaluations';

    protected $fillable = [
        'user_id',
        'course_id',
        'trainer_id',
        'result',
        'evaluated_at',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User_tbl::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course_tbl::class, 'course_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User_tbl::class, 'trainer_id');
    }
}