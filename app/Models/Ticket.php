<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the customer (user) who created the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the developer assigned to the ticket.
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function orderItems() {
        return $this->hasOne(OrderItem::class, 'id');
    }

}
