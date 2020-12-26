<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravelista\Comments\Commentable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;

class Item extends Model implements ReviewRateable
{
    use Commentable, SearchableTrait, ReviewRateableTrait;

    const ITEM_SUBMITTED = 1;
    const ITEM_PUBLISHED = 2;
    const ITEM_SUSPENDED = 3;

    const ITEM_FEATURED = 1;
    const ITEM_NOT_FEATURED = 0;

    const ITEM_FEATURED_BY_ADMIN = 1;
    const ITEM_NOT_FEATURED_BY_ADMIN = 0;

    const ITEM_ADDR_HIDE = 1;

    const ITEM_REVIEW_RECOMMEND_YES = 'Yes';
    const ITEM_REVIEW_RECOMMEND_NO = 'No';

    const ITEM_REVIEW_RATING_ONE = 1;
    const ITEM_REVIEW_RATING_TWO = 2;
    const ITEM_REVIEW_RATING_THREE = 3;
    const ITEM_REVIEW_RATING_FOUR = 4;
    const ITEM_REVIEW_RATING_FIVE = 5;

    const ITEM_REVIEW_APPROVED = 1;
    const ITEM_REVIEW_PENDING = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'item_status',
        'item_featured',
        'item_title',
        'item_slug',
        'item_description',
        'item_image',
        'item_address',
        'item_address_hide',
        'city_id',
        'state_id',
        'country_id',
        'item_postal_code',
        'item_lat',
        'item_lng',
        'item_phone',
        'item_website',
        'item_social_facebook',
        'item_social_twitter',
        'item_social_linkedin',
        'item_features_string',
        'item_image_medium',
        'item_image_small',
        'item_image_tiny',
        'file',
        'item_phone_hide',
         'city_id_m',
          'state_id_m',

    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'items.item_title' => 10,
            'categories.category_name' => 10,
            'items.item_description' => 9,
            'items.item_features_string' => 8,
                        'items.city_id_m' => 10,
                        'items.state_id_m' => 10,


        ],
        'joins' => [
            'categories' => ['items.category_id', 'categories.id'],
//            'item_features' => ['items.id','item_features.item_id'],
        ],
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the gallery images for the item.
     */
    public function galleries()
    {
        return $this->hasMany('App\ItemImageGallery');
    }

    /**
     * Get the item features for the item.
     */
    public function features()
    {
        return $this->hasMany('App\ItemFeature')->orderBy('id');
    }

    /**
     * Get the item state that owns the item.
     */
    public function state()
    {
        return $this->belongsTo('App\State');
    }

    /**
     * Get the item city that owns the item.
     */
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    /**
     * Get the item country that owns the item.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
     * Get the thread_item_rels table records for the item.
     */
    public function threadItems()
    {
        return $this->hasMany('App\ThreadItem');
    }

    /**
     * Get all of the post's comments.
     */
    public function totalComments()
    {
        return DB::table('comments')
            ->where('commentable_type', 'App\Item')
            ->where('approved', 1)
            ->where('commentable_id', $this->id)
            ->count();
    }

    /**
     * Get all of users who saved this item
     */
    public function savedByUsers()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function hasReviewByIdAndUser($review_id, $user_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function hasReviewById($review_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function getReviewById($review_id)
    {
        return DB::table('reviews')
            ->where('id', $review_id)
            ->where('reviewrateable_id', $this->id)
            ->get()->first();
    }

    public function reviewedByUser($user_id)
    {
        return DB::table('reviews')
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function getReviewByUser($user_id)
    {
        return DB::table('reviews')
            ->where('author_id', $user_id)
            ->where('reviewrateable_id', $this->id)
            ->get()->first();
    }

    public function getAverageRating()
    {
        $average_rating_query = DB::table('reviews')
            ->selectRaw('ROUND(AVG(rating), 1) as average_rating')
            ->where('reviewrateable_id', $this->id)
            ->where('approved', self::ITEM_REVIEW_APPROVED)
            ->get()->first();

        return floatval($average_rating_query->average_rating);
    }

    public function getCountRating()
    {
        return DB::table('reviews')
            ->where('approved', self::ITEM_REVIEW_APPROVED)
            ->where('reviewrateable_id', $this->id)
            ->count();
    }

    public function deleteItem()
    {
        // #1 - delete galleries, and image files
        $item_image_gallery = $this->galleries()->get();

        foreach($item_image_gallery as $key => $gallery)
        {
            if(!empty($gallery->item_image_gallery_name))
            {
                if(Storage::disk('public')->exists('item/gallery/' . $gallery->item_image_gallery_name)){
                    Storage::disk('public')->delete('item/gallery/' . $gallery->item_image_gallery_name);
                }
            }

            if(!empty($gallery->item_image_gallery_thumb_name))
            {
                if(Storage::disk('public')->exists('item/gallery/' . $gallery->item_image_gallery_thumb_name)){
                    Storage::disk('public')->delete('item/gallery/' . $gallery->item_image_gallery_thumb_name);
                }
            }

            $gallery->delete();
        }

        // #2 - delete item features
        $item_features = $this->features()->get();
        foreach($item_features as $key => $item_feature)
        {
            $item_feature->delete();
        }

        // #3 - delete item feature image
        if(!empty($this->item_image))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image)){
                Storage::disk('public')->delete('item/' . $this->item_image);
            }
        }
        if(!empty($this->item_image_medium))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_medium)){
                Storage::disk('public')->delete('item/' . $this->item_image_medium);
            }
        }
        if(!empty($this->item_image_small))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_small)){
                Storage::disk('public')->delete('item/' . $this->item_image_small);
            }
        }
        if(!empty($this->item_image_tiny))
        {
            if(Storage::disk('public')->exists('item/' . $this->item_image_tiny)){
                Storage::disk('public')->delete('item/' . $this->item_image_tiny);
            }
        }

        // #4 - delete item reviews
        DB::table('reviews')
            ->where('reviewrateable_id', $this->id)
            ->delete();

        // #5 - delete item comments
        DB::table('comments')
            ->where('commentable_id', $this->id)
            ->delete();

        // #6 - delete all messages of this item
        $threads_item = ThreadItem::where('item_id', $this->id)->get();
        foreach($threads_item as $key => $a_thread)
        {
            DB::table('participants')
                ->where('thread_id', $a_thread->thread_id)
                ->delete();
            DB::table('messages')
                ->where('thread_id', $a_thread->thread_id)
                ->delete();
            DB::table('threads')
                ->where('id', $a_thread->thread_id)
                ->delete();
        }
        ThreadItem::where('item_id', $this->id)->delete();

        // #7 - delete all saved items
        DB::table('item_user')
            ->where('item_id', $this->id)
            ->delete();

        // #8 - delete the item record
        $this->delete();
    }
}
