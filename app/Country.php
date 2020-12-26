<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_abbr', 'country_name', 'country_slug',
    ];

    /**
     * Get the states for the country.
     */
    public function states()
    {
        return $this->hasMany('App\State');
    }

    /**
     * Get the items for the country.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
