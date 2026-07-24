<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeries extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'current_edition_id', 
        'is_archived',
        'date_time',
        'status',
        'created_at',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];   
}