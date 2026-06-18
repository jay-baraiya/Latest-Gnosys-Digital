<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $guarded = [];

    protected $casts = ['date' => 'datetime'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function histories() {
        return $this->hasMany(WalletHistory::class, 'wallet_id', 'id');
    }

}
