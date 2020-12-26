<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'state_name', 'state_abbr', 'state_slug', 'state_country_abbr'
    ];

    /**
     * Get the cities for the state.
     */
    public function cities()
    {
        return $this->hasMany('App\City');
    }

    /**
     * Get the country that owns the state.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
     * Get the items for the state.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
