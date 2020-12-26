<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Setting;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Users - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.users', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // show all users except self (admin)
        $all_users = User::where('role_id', Role::USER_ROLE_ID)->orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.user.index', compact('all_users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Create User - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.create-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $name = $request->name;
        $email = $request->email;
        $email_verified_at = date("Y-m-d H:i:s");
        $password = bcrypt($request->password);
        $role_id = Role::USER_ROLE_ID;
        $user_about = $request->user_about;

        $user_image = $request->user_image;
        $user_image_name = null;
        if(!empty($user_image)){

            $currentDate = Carbon::now()->toDateString();

            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }

            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream();

            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->email_verified_at = $email_verified_at;
        $user->password = $password;
        $user->role_id = $role_id;
        $user->user_about = $user_about;
        $user->user_image = $user_image_name;
        $user->save();

        \Session::flash('flash_message', __('alert.user-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);

    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit User - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Get current user's social accounts if any
         */
        $social_accounts = $user->socialiteAccounts()->get();

        return response()->view('backend.admin.user.edit', compact('user', 'social_accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        $name = $request->name;
        $email = $request->email;
        $mobil = $request->mobil;
        $user_about = $request->user_about;

        $validate_error = array();
        $user_email_exist = User::where('email', $email)
            ->where('id', '!=', $user->id)
            ->get()->count();
        if($user_email_exist > 0)
        {
            $validate_error['email'] = 'User email has been taken.';
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $user_image = $request->user_image;
        $user_image_name = $user->user_image;
        if(!empty($user_image)){

            $currentDate = Carbon::now()->toDateString();

            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }
            if(Storage::disk('public')->exists('user/' . $user->user_image)){
                Storage::disk('public')->delete('user/' . $user->user_image);
            }

            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream();

            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }


        $user->name = $name;
        $user->email = $email;
        $user->mobil = $mobil;
        $user->user_about = $user_about;
        $user->user_image = $user_image_name;
        $user->save();

        \Session::flash('flash_message', __('alert.user-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        // Cannot delete admin account
        if(!$user->isAdmin())
        {
            $user->deleteUser();

            \Session::flash('flash_message', __('alert.user-deleted'));
            \Session::flash('flash_type', 'success');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * @param User $user
     * @return Response
     */
    public function editUserPassword(User $user)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Change User Password - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.change-user-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.password.edit', compact('user'));
    }

    public function updateUserPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        // change password
        $user->password = bcrypt($request->new_password);
        $user->save();

        \Session::flash('flash_message', __('alert.user-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit Profile - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $user_admin = User::getAdmin();

        return response()->view('backend.admin.user.profile.edit', compact('user_admin'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'user_prefer_language' => 'nullable|max:5',
        ]);

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        $user_prefer_language = $request->user_prefer_language;

        $user_admin = User::getAdmin();

        $validate_error = array();
        $email_exist = User::where('email', $email)
            ->where('id', '!=', $user_admin->id)
            ->get()->count();
        if($email_exist > 0)
        {
            $validate_error['email'] = 'Email has been taken.';
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        else
        {
            $user_image = $request->user_image;
            $user_image_name = $user_admin->user_image;
            if(!empty($user_image)){

                $currentDate = Carbon::now()->toDateString();

                $user_image_name = 'admin-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.png';

                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $user_admin->user_image)){
                    Storage::disk('public')->delete('user/' . $user_admin->user_image);
                }

                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream();

                Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
            }

            $user_admin->name = $name;
            $user_admin->email = $email;
            $user_admin->user_about = $user_about;
            $user_admin->user_image = $user_image_name;
            $user_admin->user_prefer_language = empty($user_prefer_language) ? null : $user_prefer_language;
            $user_admin->save();

            \Session::flash('flash_message', __('alert.user-profile-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.users.profile.edit');
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editProfilePassword(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Change Password - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.user.change-profile-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.profile.password.edit');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        $user_admin = User::getAdmin();

        // check current password
        $current_password = $request->password;
        if(!Hash::check($current_password, $user_admin->password))
        {
            throw ValidationException::withMessages(['password' => 'Current password wrong.']);
        }

        // change password
        $user_admin->password = bcrypt($request->new_password);
        $user_admin->save();

        \Session::flash('flash_message', __('alert.user-profile-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.profile.edit');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function suspendUser(User $user)
    {
        $user->user_suspended = User::USER_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-suspended'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function unsuspendUser(User $user)
    {
        $user->user_suspended = User::USER_NOT_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-unlocked'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }
}
