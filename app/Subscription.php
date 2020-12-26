<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
      public $table = 'subscriptions';
      
    protected $fillable = [
        'user_id', 'plan_id', 'subscription_start_date', 'subscription_end_date',
        'subscription_paypal_profile_id',
    ];

    /**
     * Get the plan that owns the subscription.
     */
    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
    
        public function planSubscriptionValidation($plan_id, $subscription_id, $user_id)
    {
        $validation = true;
        $plan_id_exist = Plan::where('id', $plan_id)
            ->where('plan_type', Plan::PLAN_TYPE_PAID)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->get()->count();
        if($plan_id_exist == 0)
        {
            $validation =  false;
        }
        $subscription_id_exist = Subscription::where('id', $subscription_id)
            ->where('user_id', $user_id)
            ->get()->count();
        if($subscription_id_exist == 0)
        {
            $validation =  false;
        }

        return $validation;
    }
    
    
}
