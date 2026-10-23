<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainerAnnouncement;

class Course_tbl extends Model
{
    use HasFactory;

    protected $table = 'course_tbls';

    protected $fillable = [
        'course_code',
        'training_id',
        'title',
        'description',
        'objectives',
        'sector',
        'duration',
        'schedule',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'training_days',
        'location',
        'thumbnail',
        'banner_image',
        'slots',
        'status',
        'trainer_id',
        'facility_id',
        'syllabus_title',
        'syllabus_content',
        'syllabus_path',
    ];

    protected $casts = [
        'slots' => 'integer',
        'duration' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'training_days' => 'array',
    ];

    protected $appends = [
        'enrolled_count',
        'available_slots',
    ];

    // RELATIONSHIPS

    public function trainer()
    {
        return $this->belongsTo(User_tbl::class, 'trainer_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment_tbl::class, 'course_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            User_tbl::class,
            'enrollment_tbls',
            'course_id',
            'user_id'
        )
        ->withPivot('status')
        ->withTimestamps();
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function deadlines()
    {
        return $this->hasMany(Deadline_tbl::class, 'course_id');
    }

    public function trainerAnnouncements()
    {
        return $this->hasMany(
            TrainerAnnouncement::class,
            'course_id'
        )->latest();
    }

    public function announcements()
    {
        return $this->trainerAnnouncements();
    }

    // ACCESSORS

    public function getEnrolledCountAttribute(): int
    {
        if (isset($this->attributes['enrollments_count'])) {
            return (int) $this->attributes['enrollments_count'];
        }

        if ($this->relationLoaded('enrollments')) {
            return $this->enrollments->count();
        }

        return $this->enrollments()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(
            0,
            (int) $this->slots - $this->enrolled_count
        );
    }
}