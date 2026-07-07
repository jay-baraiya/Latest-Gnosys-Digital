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

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function assign()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'ref_id');
    }

    public function descriptionNote()
    {
        return $this->hasOne(Note::class, 'ref_id')->where('ref_type', 'description');
    }

    public function internalNoteRelation()
    {
        return $this->hasOne(Note::class, 'ref_id')->where('ref_type', 'internal_note');
    }

    public function getDescriptionAttribute()
    {
        return $this->descriptionNote?->text;
    }

    public function getInternalNoteAttribute()
    {
        return $this->internalNoteRelation?->text;
    }

}
