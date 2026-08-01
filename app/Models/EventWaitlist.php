<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventWaitlist extends Model
{
    protected $table = 'event_waitlists';
    protected $guarded = [];

    const UPDATED_AT = null;

    protected $casts = [
        'form_data' => 'array',
        'notified_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
