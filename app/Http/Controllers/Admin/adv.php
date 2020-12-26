<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Country;
use App\Http\Controllers\Controller;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use App\ThreadItem;
use App\User;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class adv extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */

    public function adv($adv, Request $request)
    {
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Create Listing - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.item.create-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
        $all_categories = Category::orderBy('category_name')->get();
        //$country = Country::where('country_abbr', 'US')->first();
        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $all_states = $country->states()->get();

        $category_id = $request->category > 0 ? $request->category : '';
        $all_customFields = collect();
        if ($category_id) {
            $category = Category::findOrFail($category_id);
            $all_customFields = $category->customFields()
                ->orderBy('custom_field_order')
                ->orderBy('created_at')
                ->get();
        }
        return response()->view('backend.admin.item.adv',
            compact('all_categories', 'all_states',
                'category_id', 'all_customFields', 'adv'));
        //   dd($adv);
    }
    public function adv_req(Request $request)
    {
        Auth::user()->name;
        $this->validate($request, [
            'Title' => 'required',
            'Text' => 'required',
            'users' => 'required',
            'Link' => 'required',

        ], [
            'Title.required' => ' The data field is required',
            'Text.required' => ' The data field is required',
            'users.required' => ' The data field is required',
            'Link.required' => ' The data field is required',

        ]);

        DB::table('Invitations')->insert([
            'Title' => $request->input('Title'),
            'Topic' => $request->input('Text'),
            'User' => $request->input('users'),
            'Link' => $request->input('Link'),
            'Calling' => Auth::user()->name,
        ]);
        return redirect()->back()->with('alert-success', 'تم ارسال الدعوة الي المستخدم');

    }

    public function msg(Request $request){
             /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Messages - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.message.messages', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $user_id = empty($request->user_id) ? 0 : $request->user_id;
        $all_users = User::orderBy('name')->get();

        if($user_id > 0)
        {
            // All threads that user is participating in
            $threads = Thread::forUser($user_id)->latest('updated_at')->get();
        }
        else
        {
            // All threads, ignore deleted/archived participants
            $threads = Thread::getAllLatest()->get();
        }
       // $control = control::orderBy('id', 'DESC')->where('name', 'John')->get();
        $Invitations = DB::table('Invitations')->where('User',  Auth::user()->id)->orderBy('id', 'desc')->limit(150)->get();
        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();
        return view('backend.admin.item.msg', compact('all_users', 'user_id', 'threads','Invitations'));
       // return response()->view('backend.admin.item.msg');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */

}
