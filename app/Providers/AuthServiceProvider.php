<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * start authorization checks
         */

        // site admin always can do anything
        Gate::before(function ($user) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // jsonDeleteItemImageGallery for users
        Gate::define('delete-item-image-gallery', function ($user, $itemImageGallery) {

            $item = $itemImageGallery->item()->get()->first();
            $item_user = $item->user()->get()->first();

            return $user->id == $item_user->id;
        });

        // Item model for users
        Gate::define('edit-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });
        Gate::define('update-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });
        Gate::define('delete-item', function ($user, $item) {
            return $user->id == $item->user_id;
        });

    }
}
