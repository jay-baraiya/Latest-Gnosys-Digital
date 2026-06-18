<?php

namespace App\Models;

use App\Traits\TrackActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use TrackActions, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::forceDeleted(function ($blog) {

            if ($blog->image && !filter_var($blog->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace('/storage/', '', $blog->image);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
        });
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
