<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $casts = [
        'date_time' => 'datetime',
    ];
    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
