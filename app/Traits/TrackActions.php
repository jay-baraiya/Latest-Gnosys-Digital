<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

trait TrackActions
{
    /**
     * Boot the trait to hook into Eloquent events.
     */
    protected static function bootTrackActions()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $userId = Auth::id();

                if (empty($model->created_by)) {
                    $model->created_by = $userId;
                }
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check() && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model->updated_by = Auth::id();

                $model->saveQuietly();
            }
        });
    }
}
