<?php

namespace App\Models;

use App\Traits\TrackActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DigitalService extends Model
{
    use TrackActions, SoftDeletes;

    protected $guarded = [];

    // protected $casts = [
    //     'features' => 'array',
    // ];

    protected static function booted()
    {
        static::forceDeleted(function ($digitalservice) {

            if ($digitalservice->image && !filter_var($digitalservice->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace('/storage/', '', $digitalservice->image);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
        });
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function variants() {
        return $this->hasMany(ServiceVariant::class, 'service_id', 'id');
    }

    public function getPriceDisplayAttribute()
    {
        if ($this->variants()->exists()) {
            $prices = $this->variants()->pluck('price')->map(function ($price) {
                if (empty($price)) return null;
                $cleanPrice = preg_replace('/[^\d.]/', '', $price);
                return is_numeric($cleanPrice) ? (float)$cleanPrice : null;
            })->filter(function ($value) {
                return !is_null($value);
            });

            if ($prices->isNotEmpty()) {
                $minPrice = $prices->min();
                $maxPrice = $prices->max();
                if ($minPrice == $maxPrice) {
                    return '$' . ($minPrice == (int)$minPrice ? (int)$minPrice : number_format($minPrice, 2));
                }
                $minStr = $minPrice == (int)$minPrice ? (int)$minPrice : number_format($minPrice, 2);
                $maxStr = $maxPrice == (int)$maxPrice ? (int)$maxPrice : number_format($maxPrice, 2);
                return '$' . $minStr . ' - $' . $maxStr;
            }
        }

        if ($this->price) {
            $cleanPrice = preg_replace('/[^\d.]/', '', $this->price);
            if (is_numeric($cleanPrice)) {
                $p = (float)$cleanPrice;
                return '$' . ($p == (int)$p ? (int)$p : number_format($p, 2));
            }
            return $this->price;
        }

        return 'Contact for Price';
    }

    public function serviceFeatures() {
        return $this->hasMany(ServiceFeature::class, 'service_id', 'id');
    }
}
