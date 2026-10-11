<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnsiteActivity extends Model
{
    use HasFactory;

    protected $table = 'onsite_activities';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'max_score',
        'activity_date',
    ];

    public function grades()
    {
        return $this->hasMany(OnsiteGrade::class, 'onsite_activity_id');
    }
}