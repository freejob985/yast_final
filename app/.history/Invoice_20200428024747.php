<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id', 'invoice_num', 'invoice_item_title',
        'invoice_item_description', 'invoice_amount', 'invoice_status',
    ];

    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }

    /**
     * @return bool
     */
//    public function getPaidAttribute()
//    {
//        if ($this->payment_status == 'Invalid')
//        {
//            return false;
//        }
//        return true;
//    }
    public function paid()
    {
        if ($this->payment_status == 'Invalid')
        {
            return false;
        }
        return true;
    }
}
