<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    const STATUS_COMPLETED = 1;

    const TXN_SUBSCRIPTION = 1;
    const TXN_COMMISSION = 2;
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ewallet()
    {
        return $this->hasOne(Ewallet::class);
    }
}
