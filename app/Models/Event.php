<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',

        'is_free' => 'boolean',
        'waitlist_enabled' => 'boolean',
        'certificate_enabled' => 'boolean',

        'price' => 'decimal:2',

        'registration_form_schema' => 'array',
        'feedback_form_schema' => 'array',
        'certificate_template' => 'array',
    ];

    /**
     * Event belongs to an Event Series.
     */
    public function series()
    {
        return $this->belongsTo(EventSeries::class, 'series_id');
    }
}