<?php

namespace App\Models;

use App\Traits\TrackActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use TrackActions, SoftDeletes;

    protected $guarded = [];

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function products() {
        return $this->hasMany(DigitalProduct::class, 'category_id', 'id');
    }

    public function services() {
        return $this->hasMany(DigitalService::class, 'category_id', 'id');
    }

    public function blogs() {
        return $this->hasMany(Blog::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'sub_cat_id', 'id');
    }
}
