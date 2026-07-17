<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = [
        'module_type',
        'custom_field_type_id',
        'recode_id',
        'department_id',
        'name',
        'slug',
        'status',
        'options',
        'params',
        'sort_order'
    ];


    public function fieldType() {
        return $this->belongsTo(CustomFieldType::class, 'custom_field_type_id', 'id');
    }
}
