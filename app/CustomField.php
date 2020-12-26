<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    const TYPE_TEXT = 1;
    const TYPE_SELECT = 2;
    const TYPE_MULTI_SELECT = 3;
    const TYPE_LINK = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'custom_field_name', 'custom_field_type', 'custom_field_seed_value',
        'custom_field_icon_class', 'custom_field_order'
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the item features for the custom field.
     */
    public function itemFeatures()
    {
        return $this->hasMany('App\ItemFeature');
    }
}
