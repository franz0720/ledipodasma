<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacilityResource extends Model
{
    protected $table = 'facility_resources';

    protected $fillable = [
        'facility_id',
        'title',
        'url',
        'description',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
