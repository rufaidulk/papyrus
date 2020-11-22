<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
