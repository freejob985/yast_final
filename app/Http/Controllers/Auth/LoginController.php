<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\Setting;
use App\SocialiteAccount;
use App\SocialLogin;
use App\Subscription;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
 
 
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    
    protected $username = 'mobil';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Login - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.auth.login', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
    }

    protected function authenticated($request, $user)
    {
        // dd($user);
        if ($user->isAdmin())
        {
            //$this->redirectTo = route('admin.index');
            $this->redirectTo = route('page.home');
        }

        if ($user->isUser())
        {
            //$this->redirectTo = route('user.index');
            $this->redirectTo = route('page.home');
        }
    }
    
    
           protected function credentials(Request $request){
            
  
            
          if(is_numeric($request->get('email'))){
            return ['mobil'=>$request->get('email'),'password'=>$request->get('password')];
          }
          
          elseif (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->get('email'), 'password'=>$request->get('password')];
          }
          return ['name' => $request->get('email'), 'password'=>$request->get('password')];
        }
        

    public function showLoginForm()
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
 
        return view('auth.login',
            compact('social_login_facebook', 'social_login_google',
                'social_login_twitter', 'social_login_linkedin', 'social_login_github'));
                
               
    }

    public function redirectToFacebook()
    {
        $social_logins = new SocialLogin();
        $social_login_facebook = $social_logins->getFacebookLogin();
        if($social_login_facebook->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.facebook' => array(
                    'client_id' => $social_login_facebook->social_login_provider_client_id,
                    'client_secret' => $social_login_facebook->social_login_provider_client_secret,
                    'redirect' => route('auth.login.facebook.callback'),
                )]
            );

            return Socialite::driver('facebook')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-facebook-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }

    }

    public function handleFacebookCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_facebook = $social_logins->getFacebookLogin();

            if($social_login_facebook->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-facebook-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.facebook' => array(
                    'client_id' => $social_login_facebook->social_login_provider_client_id,
                    'client_secret' => $social_login_facebook->social_login_provider_client_secret,
                    'redirect' => route('auth.login.facebook.callback'),
                )]
            );

            $user = Socialite::driver('facebook')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_FACEBOOK);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-facebook-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToGoogle()
    {
        $social_logins = new SocialLogin();
        $social_login_google = $social_logins->getGoogleLogin();
        if($social_login_google->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.google' => array(
                    'client_id' => $social_login_google->social_login_provider_client_id,
                    'client_secret' => $social_login_google->social_login_provider_client_secret,
                    'redirect' => route('auth.login.google.callback'),
                )]
            );

            return Socialite::driver('google')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-google-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }



public function Mobil()
{
    return 'Mobil'; //or return the field which you want to use.
}
    public function handleGoogleCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_google = $social_logins->getGoogleLogin();

            if($social_login_google->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-google-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.google' => array(
                    'client_id' => $social_login_google->social_login_provider_client_id,
                    'client_secret' => $social_login_google->social_login_provider_client_secret,
                    'redirect' => route('auth.login.google.callback'),
                )]
            );

            $user = Socialite::driver('google')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_GOOGLE);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-google-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToTwitter()
    {
        $social_logins = new SocialLogin();
        $social_login_twitter = $social_logins->getTwitterLogin();
        if($social_login_twitter->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.google' => array(
                    'client_id' => $social_login_twitter->social_login_provider_client_id,
                    'client_secret' => $social_login_twitter->social_login_provider_client_secret,
                    'redirect' => route('auth.login.twitter.callback'),
                )]
            );

            return Socialite::driver('twitter')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-twitter-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleTwitterCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_twitter = $social_logins->getTwitterLogin();

            if($social_login_twitter->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-twitter-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.twitter' => array(
                    'client_id' => $social_login_twitter->social_login_provider_client_id,
                    'client_secret' => $social_login_twitter->social_login_provider_client_secret,
                    'redirect' => route('auth.login.twitter.callback'),
                )]
            );

            $user = Socialite::driver('twitter')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_TWITTER);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-twitter-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToLinkedIn()
    {
        $social_logins = new SocialLogin();
        $social_login_linkedin = $social_logins->getLinkedInLogin();
        if($social_login_linkedin->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.linkedin' => array(
                    'client_id' => $social_login_linkedin->social_login_provider_client_id,
                    'client_secret' => $social_login_linkedin->social_login_provider_client_secret,
                    'redirect' => route('auth.login.linkedin.callback'),
                )]
            );

            return Socialite::driver('linkedin')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-linkedin-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleLinkedInCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_linkedin = $social_logins->getLinkedInLogin();

            if($social_login_linkedin->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-linkedin-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.linkedin' => array(
                    'client_id' => $social_login_linkedin->social_login_provider_client_id,
                    'client_secret' => $social_login_linkedin->social_login_provider_client_secret,
                    'redirect' => route('auth.login.linkedin.callback'),
                )]
            );

            $user = Socialite::driver('linkedin')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_LINKEDIN);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-linkedin-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToGitHub()
    {
        $social_logins = new SocialLogin();
        $social_login_github = $social_logins->getGitHubLogin();
        if($social_login_github->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.github' => array(
                    'client_id' => $social_login_github->social_login_provider_client_id,
                    'client_secret' => $social_login_github->social_login_provider_client_secret,
                    'redirect' => route('auth.login.github.callback'),
                )]
            );

            return Socialite::driver('github')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-github-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleGitHubCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_github = $social_logins->getGitHubLogin();

            if($social_login_github->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-github-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.github' => array(
                    'client_id' => $social_login_github->social_login_provider_client_id,
                    'client_secret' => $social_login_github->social_login_provider_client_secret,
                    'redirect' => route('auth.login.github.callback'),
                )]
            );

            $user = Socialite::driver('github')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_GITHUB);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-github-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    private function createOrGetSocialLoginUser($social_login_user, $social_login_provider)
    {
        $social_account = SocialiteAccount::where('socialite_account_provider_name', $social_login_provider)
            ->where('socialite_account_provider_id', $social_login_user->id)
            ->get()
            ->first();

        if($social_account)
        {
            return $social_account->user;
        }
        else
        {
            $new_social_account = new SocialiteAccount([
                'socialite_account_provider_id' => $social_login_user->id,
                'socialite_account_provider_name' => $social_login_provider,
            ]);

            $new_social_account_email = empty($social_login_user->email) ? strtolower($social_login_provider) . "-" . $social_login_user->id . "@mail.com" : $social_login_user->email;

            $find_user = User::where('email', $new_social_account_email)->get()->first();

            if(!$find_user)
            {
                $find_user =  User::create([
                    'name' => $social_login_user->name,
                    'email' => $new_social_account_email,
                    'password' => Hash::make(uniqid()),
                    'role_id'   => Role::USER_ROLE_ID,
                    'user_suspended' => User::USER_NOT_SUSPENDED,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                ]);

                // assign the new user a subscription with free plan
                $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->get()->first();
                $free_subscription = new Subscription(array(
                    'user_id' => $find_user->id,
                    'plan_id' => $free_plan->id,
                    'subscription_start_date' => Carbon::now()->toDateString(),
                    'subscription_max_featured_listing' => 0,
                ));
                $new_free_subscription = $find_user->subscription()->save($free_subscription);
            }

            $new_social_account->user()->associate($find_user);
            $new_social_account->save();

            return $find_user;
        }
    }
}
