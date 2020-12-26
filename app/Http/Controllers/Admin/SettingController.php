<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Hamcrest\Core\Set;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function editGeneralSetting(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - General Settings - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.setting.general-settings', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_general_settings = Setting::select(
            'setting_site_logo',
            'setting_site_favicon',
            'setting_site_name',
            'setting_site_address',
            'setting_site_state',
            'setting_site_city',
            'setting_site_country',
            'setting_site_postal_code',
            'setting_site_email',
            'setting_site_phone',
            'setting_site_about',

            'setting_site_location_lat',
            'setting_site_location_lng',
            'setting_site_location_country_id',

            'setting_site_seo_home_title',
            'setting_site_seo_home_keywords',
            'setting_site_seo_home_description',
            'setting_site_google_analytic_enabled',
            'setting_site_google_analytic_tracking_id',
            'setting_site_google_analytic_not_track_admin',
            'setting_site_paypal_mode',
            'setting_site_paypal_currency',
            'setting_site_paypal_sandbox_username',
            'setting_site_paypal_sandbox_password',
            'setting_site_paypal_sandbox_secret',
            'setting_site_paypal_sandbox_certificate',
            'setting_site_paypal_live_username',
            'setting_site_paypal_live_password',
            'setting_site_paypal_live_secret',
            'setting_site_paypal_live_certificate',

            'setting_site_language',

            'setting_site_header_enabled',
            'setting_site_header',
            'setting_site_footer_enabled',
            'setting_site_footer',

            'settings_site_smtp_enabled',
            'settings_site_smtp_sender_name',
            'settings_site_smtp_sender_email',
            'settings_site_smtp_host',
            'settings_site_smtp_port',
            'settings_site_smtp_encryption',
            'settings_site_smtp_username',
            'settings_site_smtp_password')->get()->first();

        $all_countries = Country::orderBy('country_name')->get();

        return response()->view('backend.admin.setting.general.edit',
            compact('all_general_settings', 'all_countries'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateGeneralSetting(Request $request)
    {
        $request->validate([
            'setting_site_name' => 'max:255',
            'setting_site_address' => 'max:255',
            'setting_site_state' => 'max:255',
            'setting_site_city' => 'max:255',
            'setting_site_country' => 'max:255',
            'setting_site_postal_code' => 'max:255',
            'setting_site_email' => 'max:255',
            'setting_site_phone' => 'max:255',
//            'setting_site_google_analytic' => 'max:255',
            'setting_site_paypal_mode' => 'required|in:sandbox,live',
            'setting_site_paypal_currency' => 'required|alpha|max:3',
            'setting_site_location_lat' => 'required|numeric',
            'setting_site_location_lng' => 'required|numeric',
            'setting_site_location_country_id' => 'required|numeric',
            'setting_site_google_analytic_enabled' => 'nullable|numeric',
            'setting_site_google_analytic_tracking_id' => 'nullable|max:255',
            'setting_site_google_analytic_not_track_admin' => 'nullable|numeric',

            'setting_site_language' => 'nullable|max:5',

            'setting_site_header_enabled' => 'nullable|numeric',
            'setting_site_header' => 'nullable',
            'setting_site_footer_enabled' => 'nullable|numeric',
            'setting_site_footer' => 'nullable',

            'settings_site_smtp_enabled' => 'nullable|numeric',
            'settings_site_smtp_sender_name' => 'nullable|max:255',
            'settings_site_smtp_sender_email' => 'nullable|email|max:255',
            'settings_site_smtp_host' => 'nullable|max:255',
            'settings_site_smtp_port' => 'nullable|numeric',
            'settings_site_smtp_encryption' => 'nullable|numeric',
            'settings_site_smtp_username' => 'nullable|max:255',
            'settings_site_smtp_password' => 'nullable|max:255',

        ]);

        $setting = Setting::findOrFail(1);

        $selected_item_country = $request->setting_site_location_country_id;

        $validate_error = array();
        $country_exist = Country::find($selected_item_country);
        if(!$country_exist)
        {
            $validate_error['setting_site_location_country_id'] = 'Country not found.';
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        // save website logo
        $website_logo_image = $request->setting_site_logo;
        $website_logo_image_name = $setting->setting_site_logo;
        if(!empty($website_logo_image)){

            $currentDate = Carbon::now()->toDateString();

            $website_logo_image_name = 'logo-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('setting')){
                Storage::disk('public')->makeDirectory('setting');
            }
            if(Storage::disk('public')->exists('setting/' . $setting->setting_site_logo)){
                Storage::disk('public')->delete('setting/' . $setting->setting_site_logo);
            }

            $new_website_logo_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$website_logo_image)))->stream();

            Storage::disk('public')->put('setting/'.$website_logo_image_name, $new_website_logo_image);
        }
        $setting->setting_site_logo = $website_logo_image_name;

        // save favicon
        $website_favicon_image = $request->setting_site_favicon;
        $website_favicon_image_name = $setting->setting_site_favicon;
        if(!empty($website_favicon_image)){

            $currentDate = Carbon::now()->toDateString();

            $website_favicon_image_name = 'favicon-'.$currentDate.'-'.uniqid().'.png';

            if(!Storage::disk('public')->exists('setting')){
                Storage::disk('public')->makeDirectory('setting');
            }
            if(Storage::disk('public')->exists('setting/' . $setting->setting_site_favicon)){
                Storage::disk('public')->delete('setting/' . $setting->setting_site_favicon);
            }

            $new_website_favicon_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$website_favicon_image)))->stream();

            Storage::disk('public')->put('setting/'.$website_favicon_image_name, $new_website_favicon_image);
        }
        $setting->setting_site_favicon = $website_favicon_image_name;

        $setting->setting_site_name = ucwords(strtolower($request->setting_site_name));
        $setting->setting_site_address = ucwords(strtolower($request->setting_site_address));
        $setting->setting_site_state = ucwords(strtolower($request->setting_site_state));
        $setting->setting_site_city = ucwords(strtolower($request->setting_site_city));
        $setting->setting_site_country = ucwords(strtolower($request->setting_site_country));
        $setting->setting_site_postal_code = $request->setting_site_postal_code;
        $setting->setting_site_email = strtolower($request->setting_site_email);
        $setting->setting_site_phone = $request->setting_site_phone;
        $setting->setting_site_about = $request->setting_site_about;

        $setting->setting_site_location_lat = $request->setting_site_location_lat;
        $setting->setting_site_location_lng = $request->setting_site_location_lng;
        $setting->setting_site_location_country_id = $selected_item_country;

//        $setting->setting_site_google_analytic = $request->setting_site_google_analytic;
//        $setting->setting_site_google_map_api_key = $request->setting_site_google_map_api_key;

        // paypal setting
        $setting->setting_site_paypal_mode = strtolower($request->setting_site_paypal_mode);
        $setting->setting_site_paypal_currency = strtoupper($request->setting_site_paypal_currency);
        $setting->setting_site_paypal_sandbox_username = $request->setting_site_paypal_sandbox_username;
        $setting->setting_site_paypal_sandbox_password = $request->setting_site_paypal_sandbox_password;
        $setting->setting_site_paypal_sandbox_secret = $request->setting_site_paypal_sandbox_secret;
        $setting->setting_site_paypal_sandbox_certificate = $request->setting_site_paypal_sandbox_certificate;
        $setting->setting_site_paypal_live_username = $request->setting_site_paypal_live_username;
        $setting->setting_site_paypal_live_password = $request->setting_site_paypal_live_password;
        $setting->setting_site_paypal_live_secret = $request->setting_site_paypal_live_secret;
        $setting->setting_site_paypal_live_certificate = $request->setting_site_paypal_live_certificate;

        // seo
        $setting->setting_site_seo_home_title = $request->setting_site_seo_home_title;
        $setting->setting_site_seo_home_keywords = $request->setting_site_seo_home_keywords;
        $setting->setting_site_seo_home_description = $request->setting_site_seo_home_description;

        // google analytics
        $setting->setting_site_google_analytic_enabled = $request->setting_site_google_analytic_enabled == Setting::TRACKING_ON ? Setting::TRACKING_ON : Setting::TRACKING_OFF;
        $setting->setting_site_google_analytic_tracking_id = $request->setting_site_google_analytic_tracking_id;
        $setting->setting_site_google_analytic_not_track_admin = $request->setting_site_google_analytic_not_track_admin == Setting::NOT_TRACKING_ADMIN ? Setting::NOT_TRACKING_ADMIN: Setting::TRACKING_ADMIN;

        // site language
        $setting->setting_site_language = empty($request->setting_site_language) ? null : $request->setting_site_language;

        // site header code
        $setting->setting_site_header_enabled = empty($request->setting_site_header_enabled) ? Setting::SITE_HEADER_DISABLED : Setting::SITE_HEADER_ENABLED;
        $setting->setting_site_header = $request->setting_site_header;

        // site footer code
        $setting->setting_site_footer_enabled = empty($request->setting_site_footer_enabled) ? Setting::SITE_FOOTER_DISABLED : Setting::SITE_FOOTER_ENABLED;
        $setting->setting_site_footer = $request->setting_site_footer;

        // SMTP
        $setting->settings_site_smtp_enabled = empty($request->settings_site_smtp_enabled) ? Setting::SITE_SMTP_DISABLED : Setting::SITE_SMTP_ENABLED;
        $setting->settings_site_smtp_sender_name = $request->settings_site_smtp_sender_name;
        $setting->settings_site_smtp_sender_email = $request->settings_site_smtp_sender_email;
        $setting->settings_site_smtp_host = $request->settings_site_smtp_host;
        $setting->settings_site_smtp_port = $request->settings_site_smtp_port;
        $setting->settings_site_smtp_encryption = $request->settings_site_smtp_encryption;
        $setting->settings_site_smtp_username = $request->settings_site_smtp_username;
        $setting->settings_site_smtp_password = $request->settings_site_smtp_password;

        $setting->save();

        \Session::flash('flash_message', __('alert.setting-general-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.general.edit');
    }

    public function editAboutPageSetting(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - About Page - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.setting.about-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_about_settings = Setting::select(
            'setting_page_about_enable',
            'setting_page_about')->get()->first();
        return response()->view('backend.admin.setting.about.edit',
            compact('all_page_about_settings'));
    }

    public function updateAboutPageSetting(Request $request)
    {
        $request->validate([
            'setting_page_about_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_about_enable = empty($request->setting_page_about_enable) ? Setting::ABOUT_PAGE_DISABLED : Setting::ABOUT_PAGE_ENABLED;
        $setting->setting_page_about = clean($request->setting_page_about);
        $setting->save();

        $all_page_about_settings = Setting::select(
            'setting_page_about_enable',
            'setting_page_about')->get()->first();

        \Session::flash('flash_message', __('alert.setting-about-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.about.edit',
            compact('all_page_about_settings'));
    }

    public function editTermsOfServicePageSetting(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Terms of Service Page - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.setting.terms-service-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_terms_of_service_settings = Setting::select(
            'setting_page_terms_of_service_enable',
            'setting_page_terms_of_service')->get()->first();
        return response()->view('backend.admin.setting.terms-of-service.edit',
            compact('all_page_terms_of_service_settings'));
    }

    public function updateTermsOfServicePageSetting(Request $request)
    {
        $request->validate([
            'setting_page_terms_of_service_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_terms_of_service_enable = empty($request->setting_page_terms_of_service_enable) ? Setting::TERM_PAGE_DISABLED : Setting::TERM_PAGE_ENABLED;
        $setting->setting_page_terms_of_service = clean($request->setting_page_terms_of_service);
        $setting->save();

        $all_page_terms_of_service_settings = Setting::select(
            'setting_page_terms_of_service_enable',
            'setting_page_terms_of_service')->get()->first();

        \Session::flash('flash_message', __('alert.setting-terms-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.terms-service.edit',
            compact('all_page_terms_of_service_settings'));
    }

    public function editPrivacyPolicyPageSetting(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Privacy Policy Page - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.setting.privacy-policy-page', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_page_privacy_policy_settings = Setting::select(
            'setting_page_privacy_policy_enable',
            'setting_page_privacy_policy')->get()->first();
        return response()->view('backend.admin.setting.privacy-policy.edit',
            compact('all_page_privacy_policy_settings'));
    }

    public function updatePrivacyPolicyPageSetting(Request $request)
    {
        $request->validate([
            'setting_page_privacy_policy_enable' => 'numeric'
        ]);

        $setting = Setting::findOrFail(1);

        $setting->setting_page_privacy_policy_enable = empty($request->setting_page_privacy_policy_enable) ? Setting::PRIVACY_PAGE_DISABLED : Setting::PRIVACY_PAGE_ENABLED;
        $setting->setting_page_privacy_policy = clean($request->setting_page_privacy_policy);
        $setting->save();

        $all_page_privacy_policy_settings = Setting::select(
            'setting_page_privacy_policy_enable',
            'setting_page_privacy_policy')->get()->first();

        \Session::flash('flash_message', __('alert.setting-privacy-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.settings.page.privacy-policy.edit',
            compact('all_page_privacy_policy_settings'));
    }

}
