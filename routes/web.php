<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/mm', function () {
    trim_characters();
  //  return view('welcome');
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

 

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
Route::get('/link', function () {
    generate_symlink();
});
Route::get('/foo', function () {
    Artisan::call('storage:link');
});




Route::middleware(['installed','demo','locale'])->group(function () {
    /**
     * Auth routes
     */
    Auth::routes(['verify' => true]);
    /**
     * Social login routes
     */
    // facebook
    Route::get('/auth/facebook', 'Auth\LoginController@redirectToFacebook')->name('auth.login.facebook');
    Route::get('/auth/facebook/callback', 'Auth\LoginController@handleFacebookCallback')->name('auth.login.facebook.callback');

    // google
    Route::get('/auth/google', 'Auth\LoginController@redirectToGoogle')->name('auth.login.google');
    Route::get('/auth/google/callback', 'Auth\LoginController@handleGoogleCallback')->name('auth.login.google.callback');

    // twitter
    Route::get('/auth/twitter', 'Auth\LoginController@redirectToTwitter')->name('auth.login.twitter');
    Route::get('/auth/twitter/callback', 'Auth\LoginController@handleTwitterCallback')->name('auth.login.twitter.callback');

    // linkedin
    Route::get('/auth/linkedin', 'Auth\LoginController@redirectToLinkedIn')->name('auth.login.linkedin');
    Route::get('/auth/linkedin/callback', 'Auth\LoginController@handleLinkedInCallback')->name('auth.login.linkedin.callback');

    // github
    Route::get('/auth/github', 'Auth\LoginController@redirectToGitHub')->name('auth.login.github');
    Route::get('/auth/github/callback', 'Auth\LoginController@handleGitHubCallback')->name('auth.login.github.callback');

    /**
     * Public routes
     */
    Route::get('/', 'PagesController@index')->name('page.home');

    Route::get('/search', 'PagesController@search')->name('page.search');
    Route::post('/search', 'PagesController@doSearch')->name('page.search.do');

    Route::get('/about', 'PagesController@about')->name('page.about');
    Route::get('/contact', 'PagesController@contact')->name('page.contact');
    Route::get('/ads', 'PagesController@ads')->name('page.ads');
    Route::post('/contact', 'PagesController@doContact')->name('page.contact.do');

    Route::get('/categories', 'PagesController@categories')->name('page.categories');
    Route::get('/category/{category_slug}', 'PagesController@category')->name('page.category');
    Route::get('/category/{category_slug}/state/{state_slug}', 'PagesController@categoryByState')->name('page.category.state');
    Route::get('/category/{category_slug}/state/{state_slug}/city/{city_slug}', 'PagesController@categoryByStateCity')->name('page.category.state.city');
    Route::match(['get', 'post'], '/login/user/yasta', 'PagesController@login_user')->name('login.user.yasta');
   // Route::get('/login', 'index@login')->name('admin.login');
    Route::get('/state/{state_slug}', 'PagesController@state')->name('page.state');
    Route::get('/state/{state_slug}/city/{city_slug}', 'PagesController@city')->name('page.city');

    Route::get('/item/{item_slug}', 'PagesController@item')->name('page.item');
    Route::post('/sub_ajax', 'PagesController@sub_ajax')->name('sub_ajax.home');

    Route::middleware(['auth'])->group(function () {

        Route::post('/items/{item_slug}/email', 'PagesController@emailItem')->name('page.item.email');
        Route::post('/items/{item_slug}/save', 'PagesController@saveItem')->name('page.item.save');
        Route::post('/items/{item_slug}/unsave', 'PagesController@unSaveItem')->name('page.item.unsave');
    });

    Route::get('/terms-of-service', 'PagesController@termsOfService')->name('page.terms-of-service');
    Route::get('/privacy-policy', 'PagesController@privacyPolicy')->name('page.privacy-policy');
    /**
     * Blog routes
     */
    Route::group(['prefix'=>'blog'], function(){

        // Get all published posts
        Route::get('/', 'PagesController@blog')->name('page.blog');

        // Get posts for a given tag
        Route::get('/tag/{tag_slug}', 'PagesController@blogByTag')->name('page.blog.tag');

        // Get posts for a given topic
        Route::get('/topic/{topic_slug}', 'PagesController@blogByTopic')->name('page.blog.topic');

        // Find a single post
        Route::get('/{blog_slug}', 'PagesController@blogPost')
            ->middleware('Canvas\Http\Middleware\Session')
            ->name('page.blog.show');
    });
    Route::put('/locale/update', 'PagesController@updateLocale')->name('page.locale.update');
    /**
     * PayPal IPN Route
     *
     * Receive post request from paypal to verify future recurring payment.
     */
    Route::post('/paypal/notify', 'User\PaypalController@notify')->name('user.paypal.notify');
    /**
     * ajax routes serve frontend elements
     */
    Route::get('/ajax/cities/{state_id}', 'PagesController@jsonGetCitiesByState')->name('json.city');
    Route::post('/ajax/item/gallery/delete/{item_image_gallery_id}', 'PagesController@jsonDeleteItemImageGallery')->name('json.item.image.gallery');
    Route::post('/ajax/location/save/{lat}/{lng}', 'PagesController@ajaxLocationSave')->name('ajax.location.save');
    /**
     * Back-end admin routes
     */
     
     //Clear Cache facade value:
   Route::get('/clear-cache','PagesController@mrzaabola')->name('clear-cache');

    Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['verified','auth','admin'],'as'=>'admin.'], function(){
        Route::get('/','PagesController@index')->name('index');
        Route::resource('/countries', 'CountryController');
        Route::resource('/states', 'StateController');
        Route::resource('/cities', 'CityController');
        Route::resource('/categories', 'CategoryController');
        Route::resource('/custom-fields', 'CustomFieldController');
        Route::resource('/items', 'ItemController');
        Route::get('/adv/{adv}', 'ItemController@adv')->name('adv');
        Route::get('/msg', 'adv@msg')->name('msg');
        Route::post('/Invitations', 'adv@adv_req')->name('invitations');
        Route::get('/items/saved/index', 'ItemController@savedItems')->name('items.saved');
        Route::post('/items/{item_slug}/unsave', 'ItemController@unSaveItem')->name('items.unsave');
        Route::put('/items/{item}/approve', 'ItemController@approveItem')->name('items.approve');
        Route::put('/items/{item}/disapprove', 'ItemController@disApproveItem')->name('items.disapprove');
        Route::put('/items/{item}/suspend', 'ItemController@suspendItem')->name('items.suspend');
        // item reviews routes
        Route::get('/items/{item_slug}/reviews/create', 'ItemController@itemReviewsCreate')->name('items.reviews.create');
        Route::post('/items/{item_slug}/reviews/store', 'ItemController@itemReviewsStore')->name('items.reviews.store');
        Route::get('/items/{item_slug}/reviews/{review}/edit', 'ItemController@itemReviewsEdit')->name('items.reviews.edit');
        Route::put('/items/{item_slug}/reviews/update/{review}', 'ItemController@itemReviewsUpdate')->name('items.reviews.update');
        Route::delete('/items/{item_slug}/reviews/destroy/{review}', 'ItemController@itemReviewsDestroy')->name('items.reviews.destroy');
        // item reviews management admin routes
        Route::get('/items/reviews/index', 'ItemController@itemReviewsIndex')->name('items.reviews.index');
        Route::get('/items/reviews/{review_id}', 'ItemController@itemReviewsShow')->name('items.reviews.show');
        Route::put('/items/reviews/update/{review_id}/approve', 'ItemController@itemReviewsApprove')->name('items.reviews.approve');
        Route::put('/items/reviews/update/{review_id}/disapprove', 'ItemController@itemReviewsDisapprove')->name('items.reviews.disapprove');
        Route::delete('/items/reviews/destroy/{review_id}', 'ItemController@itemReviewsDelete')->name('items.reviews.delete');
        // message routes
        Route::resource('/messages', 'MessageController');
        // plan routes
        Route::resource('/plans', 'PlanController');
                        Route::resource('/Applications', 'ApplicationsController');
    Route::post('/quickupdate/CourseProgresss/{id}','ApplicationsController@CourseProgresss')->name('CourseProgresss.quick');


        // subscription routes
        Route::resource('/subscriptions', 'SubscriptionController');

        Route::resource('/users', 'UserController');
        Route::get('/users/password/{user}/edit', 'UserController@editUserPassword')->name('users.password.edit');
        Route::post('/users/password/{user}', 'UserController@updateUserPassword')->name('users.password.update');

        Route::put('/users/{user}}/suspend', 'UserController@suspendUser')->name('users.suspend');
        Route::put('/users/{user}}/unsuspend', 'UserController@unsuspendUser')->name('users.unsuspend');


        Route::get('/profile', 'UserController@editProfile')->name('users.profile.edit');
        Route::post('/profile', 'UserController@updateProfile')->name('users.profile.update');
        Route::get('/profile/password', 'UserController@editProfilePassword')->name('users.profile.password.edit');
        Route::post('/profile/password', 'UserController@updateProfilePassword')->name('users.profile.password.update');
        Route::resource('/testimonials', 'TestimonialController');
        Route::resource('/faqs', 'FaqController');
        Route::resource('/social-medias', 'SocialMediaController');
        // setting general
        Route::get('/settings/general', 'SettingController@editGeneralSetting')->name('settings.general.edit');
        Route::post('/settings/general', 'SettingController@updateGeneralSetting')->name('settings.general.update');

        // setting about page
        Route::get('/settings/about', 'SettingController@editAboutPageSetting')->name('settings.page.about.edit');
        Route::post('/settings/about', 'SettingController@updateAboutPageSetting')->name('settings.page.about.update');

        // setting terms-of-service page
        Route::get('/settings/terms-of-service', 'SettingController@editTermsOfServicePageSetting')->name('settings.page.terms-service.edit');
        Route::post('/settings/terms-of-service', 'SettingController@updateTermsOfServicePageSetting')->name('settings.page.terms-service.update');

        // setting privacy-policy page
        Route::get('/settings/privacy-policy', 'SettingController@editPrivacyPolicyPageSetting')->name('settings.page.privacy-policy.edit');
        Route::post('/settings/privacy-policy', 'SettingController@updatePrivacyPolicyPageSetting')->name('settings.page.privacy-policy.update');

        Route::get('/comments', 'CommentController@index')->name('comments.index');
        Route::put('/comments/{comment}/approve', 'CommentController@approve')->name('comments.approve');
        Route::put('/comments/{comment}/disapprove', 'CommentController@disapprove')->name('comments.disapprove');
        Route::delete('/comments/{comment}/delete', 'CommentController@destroy')->name('comments.destroy');

        // advertisement management
        Route::resource('/advertisements', 'AdvertisementController');

        // social login management
        Route::resource('/social-logins', 'SocialLoginController');

    });

    /**
     * Back-end user routes
     */
    Route::group(['prefix'=>'user','namespace'=>'User','middleware'=>['verified','auth','user'],'as'=>'user.'], function(){

        Route::get('/','PagesController@index')->name('index');
        Route::resource('/items', 'ItemController');

        Route::get('/items/saved/index', 'ItemController@savedItems')->name('items.saved');
        Route::post('/items/{item_slug}/unsave', 'ItemController@unSaveItem')->name('items.unsave');
        Route::get('/adv/{adv}', 'adv@adv')->name('adv');
        Route::get('/msg', 'adv@msg')->name('msg');
        Route::post('/Invitations', 'adv@adv_req')->name('invitations');
        // item reviews routes
        Route::get('/items/{item_slug}/reviews/create', 'ItemController@itemReviewsCreate')->name('items.reviews.create');
        Route::post('/items/{item_slug}/reviews/store', 'ItemController@itemReviewsStore')->name('items.reviews.store');
        Route::get('/items/{item_slug}/reviews/{review}/edit', 'ItemController@itemReviewsEdit')->name('items.reviews.edit');
        Route::put('/items/{item_slug}/reviews/update/{review}', 'ItemController@itemReviewsUpdate')->name('items.reviews.update');
        Route::delete('/items/{item_slug}/reviews/destroy/{review}', 'ItemController@itemReviewsDestroy')->name('items.reviews.destroy');

        // user manage reviews route
        Route::get('/items/reviews/index', 'ItemController@itemReviewsIndex')->name('items.reviews.index');

        // message routes
        Route::resource('/messages', 'MessageController');

        // subscription routes
        Route::resource('/subscriptions', 'SubscriptionController');

        Route::post('/Transformation', 'SubscriptionController@Transformation')->name('Transforma');

        Route::get('/comments', 'CommentController@index')->name('comments.index');

        // PayPal gateway
        Route::get('/paypal/checkout/plan/{plan_id}/subscription/{subscription_id}', 'PaypalController@doCheckout')->name('paypal.checkout.do');
        Route::get('/paypal/checkout/success/plan/{plan_id}/subscription/{subscription_id}', 'PaypalController@showCheckoutSuccess')->name('paypal.checkout.success');
        Route::get('/paypal/checkout/cancel', 'PaypalController@showCheckoutCancel')->name('paypal.checkout.cancel');
        Route::post('/paypal/recurring/cancel', 'PaypalController@cancelRecurring')->name('paypal.recurring.cancel');

        Route::get('/profile', 'UserController@editProfile')->name('profile.edit');
        Route::post('/profile', 'UserController@updateProfile')->name('profile.update');
        Route::get('/profile/password', 'UserController@editProfilePassword')->name('profile.password.edit');
        Route::post('/profile/password', 'UserController@updateProfilePassword')->name('profile.password.update');
    });

});
