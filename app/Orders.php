<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    const PLAN_TYPE_FREE = 1;
    const PLAN_TYPE_PAID = 2;
    const PLAN_TYPE_ADMIN = 3;

    const PLAN_LIFETIME = 1;
    const PLAN_MONTHLY = 2;
    const PLAN_QUARTERLY = 3;
    const PLAN_YEARLY = 4;

    const PLAN_ENABLED = 1;
    const PLAN_DISABLED = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
   


public $table = 'Orders';
public $timestamps = false;





    protected $fillable = [
        'Code', 'Package', 'price', 'User', 'st',
        'Type','Notes',
    ];

    /**
     * Get the subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
}
