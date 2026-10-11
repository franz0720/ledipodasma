<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnsiteGrade extends Model
{
    use HasFactory;

    protected $table = 'onsite_grades';

    protected $fillable = [
        'onsite_activity_id',
        'enrollment_id',
        'user_id',
        'score',
        'remarks',
        'graded_by',
    ];

    public function activity()
    {
        return $this->belongsTo(OnsiteActivity::class, 'onsite_activity_id');
    }
}