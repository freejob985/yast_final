<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name', 'category_slug', 'category_parent_id', 'category_icon','fileToUpload'
    ];

    /**
     * Get the items for the category.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    /**
     * Get the custom fields for the category.
     */
    public function customFields()
    {
        return $this->hasMany('App\CustomField');
    }
}
