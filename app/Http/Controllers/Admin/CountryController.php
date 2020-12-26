<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Countries - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.country.countries', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // show all countries
        $all_countries = Country::all();

        return response()->view('backend.admin.country.index', compact('all_countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Create Country - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.country.create-country', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.country.create');
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
            'country_name' => 'required|unique:countries|regex:/^[\pL\s\-]+$/u|max:255',
            'country_abbr' => 'required|unique:countries|max:255',
        ]);

        $country = new Country();
        $country->country_name = ucwords(strtolower($request->country_name));
        $country->country_abbr = strtoupper($request->country_abbr);
        $country->country_slug = str_slug($request->country_name);
        $country->save();

        \Session::flash('flash_message', __('alert.country-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.countries.edit', compact('country'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     */
    public function show(Country $country)
    {
        return redirect()->route('admin.countries.edit', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit Country - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.country.edit-country', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.country.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Country $country
     * @throws ValidationException
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'country_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'country_abbr' => 'required|max:255',
        ]);

        $country_name = ucwords(strtolower($request->country_name));
        $country_abbr = strtoupper($request->country_abbr);
        $country_slug = str_slug($request->country_name);

        // check if country name and country abbr exist
        $validate_error = array();

        $country_exist = Country::where('country_name', $country_name)
        ->where('id', '!=', $country->id)
        ->get()->count();
        if($country_exist > 0)
        {
            $validate_error['country_name'] = 'Country name has been taken.';
        }

        $country_abbr_exist = Country::where('country_abbr', $country_abbr)
            ->where('id', '!=', $country->id)
            ->get()->count();
        if($country_abbr_exist > 0)
        {
            $validate_error['country_abbr'] = 'Country abbr has been taken.';
        }

        if(count($validate_error))
        {
            throw ValidationException::withMessages($validate_error);
        }

        // now update country
        $country->country_name = $country_name;
        $country->country_abbr = $country_abbr;
        $country->country_slug = $country_slug;
        $country->save();

        \Session::flash('flash_message', __('alert.country-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.countries.edit', compact('country'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     */
    public function destroy(Country $country)
    {

        $settings = Setting::find(1);
        if($country->id == $settings->setting_site_location_country_id)
        {
            // website settings
            \Session::flash('flash_message', __('alert.country-delete-error-setting'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.countries.edit', $country->id);
        }
        elseif($country->items()->get()->count() > 0)
        {
            // check model relations before delete
            \Session::flash('flash_message', __('alert.country-delete-error-listing'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.countries.edit', $country->id);
        }
        elseif($country->states()->get()->count() > 0)
        {
            // check model relations before delete
            \Session::flash('flash_message', __('alert.country-delete-error-state'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.countries.edit', $country->id);
        }
        else
        {
            $country->delete();

            \Session::flash('flash_message', __('alert.country-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.countries.index');
        }
    }
}
