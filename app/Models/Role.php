<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Models\Scopes\DescScope;

#[ScopedBy([DescScope::class])]
#[Fillable([
    'name',
    'slug',
    'description',
    'status',
    'user_id',
])]

class Role extends Model
{
    use SoftDeletes;

    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
