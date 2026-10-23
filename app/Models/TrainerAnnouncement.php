<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course_tbl;
use App\Models\AnnouncementComment;
use App\Models\User_tbl;

class TrainerAnnouncement extends Model
{
    use HasFactory;

    // Explicitly target the table from your database
    protected $table = 'trainer_announcements';

    protected $fillable = [
        'course_id',
        'trainer_id',
        'content',
        'target_audience',
    ];

    /**
     * Relationship to the Course
     */
    public function course()
    {
        return $this->belongsTo(Course_tbl::class, 'course_id');
    }

    /**
     * Relationship to Announcement Comments
     */
    public function comments()
    {
        return $this->hasMany(
            AnnouncementComment::class,
            'announcement_id'
        )->oldest();
    }

    /**
     * Relationship to the Trainer / Author
     */
    public function trainer()
    {
        return $this->belongsTo(User_tbl::class, 'trainer_id');
    }
}
