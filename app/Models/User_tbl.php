<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User_tbl extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'user_tbls';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'username',
        'password',
        'role',
        'must_reset_password',
        'email_verified_at',
        'status',
        'remarks',
        'contact',
        'id_number',
        'certificate_path',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'must_reset_password' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the enrollments associated with this user.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(
            Enrollment_tbl::class,
            'user_id',
            'id'
        );
    }
}
