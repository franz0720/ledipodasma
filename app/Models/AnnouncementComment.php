<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementComment extends Model
{
    use HasFactory;

    protected $table = 'announcement_comments';

    protected $fillable = [
        'announcement_id',
        'user_id',
        'comment',
    ];

    public function announcement()
    {
        return $this->belongsTo(TrainerAnnouncement::class, 'announcement_id');
    }

    public function user()
    {
        return $this->belongsTo(User_tbl::class, 'user_id');
    }
}