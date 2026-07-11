<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assign()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->belongsTo(Note::class);
    }
}
