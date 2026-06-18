<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\DescScope;
use App\Traits\TrackActions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'phone', 'designation_id', 'address', 'country_id', 'state_id', 'city_id', 'zip', 'status', 'role_id', 'user_id','image'])]
#[Hidden(['password', 'remember_token'])]
#[ScopedBy([DescScope::class])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use TrackActions, HasFactory, Notifiable, SoftDeletes;

    const IS_USER = 0;

    const IS_ADMIN = 1;

    const IS_BUYER = 7;

    const SUPERADMIN_ROLE_ID = 1;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::forceDeleted(function ($user) {

            if ($user->image && !filter_var($user->image, FILTER_VALIDATE_URL)) {
                $pathToDelete = str_replace('/storage/', '', $user->image);
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function isSuperAdmin()
    {
        return $this->roles()->where('roles.id', self::SUPERADMIN_ROLE_ID)->exists();
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function balance(){
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }

    public function getCartItems() {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function address() {
        return $this->hasOne(Address::class, 'user_id', 'id');
    }

    public function orders() {
        return $this->hasOne(Order::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }
}
