<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'service_ids' => 'array',
        'event_ids' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
