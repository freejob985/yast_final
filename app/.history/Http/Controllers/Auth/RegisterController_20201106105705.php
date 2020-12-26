<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Role;
use App\Setting;
use App\SocialLogin;
use App\Subscription;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Register - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.auth.register', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'mobil' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if (isset($data['Option'])) {

            $new_user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => Role::USER_ROLE_ID,
                'user_suspended' => User::USER_NOT_SUSPENDED,
                'Type' => $data['Type'],
                'mobil' => $data['mobil'],
            ]);

            // // assign the new user a subscription with free plan
            // $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->get()->first();
            // $free_subscription = new Subscription(array(
            //     'user_id' => $new_user->id,
            //     'plan_id' => $free_plan->id,
            //     'subscription_start_date' => Carbon::now()->toDateString(),
            //     'subscription_max_featured_listing' => 0,
            // ));
            // $new_free_subscription = $new_user->subscription()->save($free_subscription);
            Auth::login($new_user);
            $login_user = Auth::user();
            $subscription = $login_user->subscription()->get()->first();
            $this->redirectTo = "user/subscriptions/$subscription->id/edit";

            return $new_user;

        } else {

            return Redirect::to('/register');

        }

    }

    public function showRegistrationForm()
    {
        /**
         * Start social login
         */
        $social_logins = new SocialLogin();
        $social_login_facebook = $social_logins->isFacebookEnabled();
        $social_login_google = $social_logins->isGoogleEnabled();
        $social_login_twitter = $social_logins->isTwitterEnabled();
        $social_login_linkedin = $social_logins->isLinkedInEnabled();
        $social_login_github = $social_logins->isGitHubEnabled();
        /**
         * End social login
         */

        return view('auth.register',
            compact('social_login_facebook', 'social_login_google',
                'social_login_twitter', 'social_login_linkedin', 'social_login_github'));
    }
}
