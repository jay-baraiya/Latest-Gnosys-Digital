<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    protected $guarded = [];

    public function permission()
    {
        return $this->hasMany(Permission::class, 'id', 'permission_id');
    }
}
