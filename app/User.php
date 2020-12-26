<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravelista\Comments\Commenter;
use Cmgmyr\Messenger\Traits\Messagable;
use DateTime;
use Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Commenter, Messagable;

    const USER_NOT_SUSPENDED = 0;
    const USER_SUSPENDED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'user_image',
        'user_about',
        'user_suspended',
        'user_prefer_language',
         'Type',
           'mobil',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function hasRole()
    {
        return $this->role->name;
    }

    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }

    public function isUser()
    {
        return $this->role_id == Role::USER_ROLE_ID;
    }

    public function hasSuspended()
    {
        return $this->user_suspended == User::USER_SUSPENDED;
    }

    public function hasActive()
    {
        return $this->user_suspended == User::USER_NOT_SUSPENDED;
    }

    /**
     * Get the items for the user.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function socialiteAccounts()
    {
        return $this->hasMany('App\SocialiteAccount');
    }

    /**
     * Get the items saved by this user
     */
    public function savedItems()
    {
        return $this->belongsToMany('App\Item')->withTimestamps();
    }

    public function hasSavedItem(int $item_id)
    {
        return DB::table('item_user')
            ->where('item_id', $item_id)
            ->where('user_id', $this->id)
            ->get()
            ->count() > 0 ? true : false;
    }
    
        public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
       public function red( $id,$idr)
    {
 
                       $Subscription = Subscription::where('user_id', $id)->get();
                       if(DB::table('subscriptions')->where('user_id', $id)->exists()){
                           $a= 1;
                          
                       }else  if($idr==="1"){
                        
                          $a=1;
                       }else  if($idr==="2"){
                        
                          $a=1;
                       }else{
                            $a=2; 
                       }

       return $a;
   
    }
    
    
    
    
    

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function hasPaidSubscription()
    {
        $today = new DateTime('now');
        $today = $today->format("Y-m-d");

        $subscription_exist = Subscription::where('user_id', $this->id)
            ->where('subscription_end_date', '>=', $today)->count();

        return $subscription_exist > 0 ? true : false;
    }

    public function canFeatureItem()
    {
        if($this->hasPaidSubscription())
        {
            $subscription = $this->subscription()->get()->first();
            $allowed_num_featured_items = intval($subscription->subscription_max_featured_listing);

            if(empty($allowed_num_featured_items))
            {
                return true;
            }
            else
            {
                $total_featured_items = $this->items()
                    ->where('item_featured', Item::ITEM_FEATURED)
                    ->get()->count();

                if($allowed_num_featured_items - $total_featured_items >= 1)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

        }
        elseif($this->isAdmin())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getAdmin()
    {
        return User::where('role_id', Role::ADMIN_ROLE_ID)->first();
    }

    public function getLocale()
    {
        return $this->user_prefer_language;
    }

    public function deleteUser()
    {
        // #1 - delete all items of this user.
        $items = $this->items()->get();
        foreach($items as $key => $item)
        {
            $item->deleteItem();
        }

        // #2 - delete all user's messages
        $participants = DB::table('participants')
            ->where('user_id', $this->id)
            ->get();
        foreach($participants as $key => $participant)
        {
            DB::table('participants')
                ->where('thread_id', $participant->thread_id)
                ->delete();
            DB::table('messages')
                ->where('thread_id', $participant->thread_id)
                ->delete();
            DB::table('threads')
                ->where('id', $participant->thread_id)
                ->delete();
            ThreadItem::where('thread_id', $participant->thread_id)->delete();
        }

        // #3 - delete user's reviews
        DB::table('reviews')
            ->where('author_id', $this->id)
            ->delete();

        // #4 - delete user's comments
        DB::table('comments')
            ->where('commenter_id', $this->id)
            ->delete();

        // #5 - delete saved items records
        DB::table('item_user')
            ->where('user_id', $this->id)
            ->delete();

        // #6 - delete socialite accounts records
        $socialite_accounts = $this->socialiteAccounts()->get();
        foreach($socialite_accounts as $key => $socialite_account)
        {
            $socialite_account->delete();
        }

        // #7 - delete all user invoices
        $invoices = $this->subscription()
            ->get()->first()
            ->invoices()
            ->get();
        foreach($invoices as $key => $invoice)
        {
            $invoice->delete();
        }

        // #8 - delete subscription record
        $subscriptions = $this->subscription()->get();
        foreach($subscriptions as $key => $subscription)
        {
            $subscription->delete();
        }

        // #9 - delete user profile image
        if(!empty($this->user_image))
        {
            if(Storage::disk('public')->exists('user/' . $this->user_image)){
                Storage::disk('public')->delete('user/' . $this->user_image);
            }
        }

        // #10 - delete the user
        $this->delete();
    }


}
