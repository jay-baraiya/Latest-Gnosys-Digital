<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = [
        'module_type',
        'custom_field_type_id',
        'recode_id',
        'name',
        'slug',
        'status',
        'options',
        'params'
    ];


    public function fieldType() {
        return $this->belongsTo(CustomFieldType::class, 'custom_field_type_id', 'id');
    }
}
