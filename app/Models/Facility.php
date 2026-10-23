<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'name',
        'address',
        'capacity',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course_tbl::class, 'facility_id');
    }

    /**
     * Legacy relationship for existing dashboard code.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(
            FacilityResource::class,
            'facility_id'
        );
    }

    /**
     * The single resource used by the facility form.
     */
    public function resource(): HasOne
    {
        return $this->hasOne(
            FacilityResource::class,
            'facility_id'
        );
    }
}
