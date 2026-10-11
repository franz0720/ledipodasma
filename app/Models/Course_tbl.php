<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainerAnnouncement; // Added

class Course_tbl extends Model
{
    use HasFactory;

    protected $table = 'course_tbls';

    protected $fillable = [
        'course_code',
        'title',
        'description',
        'objectives',
        'sector',
        'duration',
        'schedule',
        'location',
        'thumbnail',
        'banner_image', // Added for the banner feature
        'slots',
        'status',
        'trainer_id',
        'facility_id',
        'syllabus_title',
    'syllabus_content',
    'syllabus_path',
    ];

    /**
     * Type casting for attributes.
     */
    protected $casts = [
        'slots' => 'integer',
        'duration' => 'integer',
    ];

    /**
     * Virtual attributes automatically included in array/JSON serialization.
     */
    protected $appends = [
        'enrolled_count',
        'available_slots',
    ];

    /* ==========================================================
     * RELATIONSHIPS
     * ========================================================== */

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

    /**
     * Relationship to Enrolled Students (used by TrainerController)
     */
    public function students()
    {
        return $this->belongsToMany(User_tbl::class, 'enrollment_tbls', 'course_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Relationship to Course Modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }

    /**
     * Relationship to Course Quizzes
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    /**
     * Relationship to Course Deadlines
     */
    public function deadlines()
    {
        return $this->hasMany(Deadline_tbl::class, 'course_id');
    }

    /**
     * Relationship to Trainer Announcements (Ordered newest first)
     */
    public function trainerAnnouncements()
    {
        return $this->hasMany(TrainerAnnouncement::class, 'course_id')->latest();
    }

    /**
     * Alias for announcements
     */
    public function announcements()
    {
        return $this->trainerAnnouncements();
    }

    /* ==========================================================
     * ACCESSORS & VIRTUAL ATTRIBUTES
     * ========================================================== */

    /**
     * Get total enrolled count.
     */
    public function getEnrolledCountAttribute(): int
    {
        // Avoid executing a query if count/relation is already eager loaded
        if (isset($this->attributes['enrollments_count'])) {
            return (int) $this->attributes['enrollments_count'];
        }

        if ($this->relationLoaded('enrollments')) {
            return $this->enrollments->count();
        }

        return $this->enrollments()->count();
    }

    /**
     * Calculate remaining available slots.
     */
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, (int) $this->slots - $this->enrolled_count);
    }
}