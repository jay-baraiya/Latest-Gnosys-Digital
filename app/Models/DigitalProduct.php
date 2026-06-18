<?php

namespace App\Models;

use App\Traits\TrackActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DigitalProduct extends Model
{
    use TrackActions, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::forceDeleted(function ($digitalProduct) {

            if ($digitalProduct->image && !filter_var($digitalProduct->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace('/storage/', '', $digitalProduct->image);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }

            if ($digitalProduct->project && Storage::disk('local')->exists($digitalProduct->project)) {
                Storage::disk('local')->delete($digitalProduct->project);
            }
        });
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
