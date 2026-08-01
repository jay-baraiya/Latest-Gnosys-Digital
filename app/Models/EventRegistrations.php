<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistrations extends Model
{
    protected $table = 'event_registrations';
    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array',
        'checked_in_at' => 'datetime',
        'feedback_submitted_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
