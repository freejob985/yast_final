<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Setting;
use App\Subscription;
use Artesaos\SEOTools\Facades\SEOMeta;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function Transformation(Request $request)
    {

        $code = $request->Code;
        DB::table('Orders')->insert($request->all());
        return redirect()->back()->with('message', "تم بنجاح ارسال طلبك. رقم العملية هو  #$code");

    }

    public function index()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Subscriptions - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.subscription.subscriptions', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // show subscription information for current user
        $login_user = Auth::user();
        $subscription = $login_user->subscription()->get()->first();

        //$invoices = $subscription->invoices()->orderBy('created_at', 'DESC')->get();
        if (!empty($invoices)) {
            $invoices = $subscription->invoices()->latest('created_at')->get();

        } else {
            $invoices = array();
        }

        return response()->view('backend.user.subscription.index',
            compact('subscription', 'invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     *
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */

    public function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }

    public function edit(Subscription $subscription)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit Subscription - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.subscription.edit-subscription', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

//        $plan = $subscription->plan()->get()->first();
        //
        //        if($plan->plan_type == Plan::PLAN_TYPE_PAID)
        //        {
        //            \Session::flash('flash_message', 'You must cancel current plan before switch to other plans.');
        //            \Session::flash('flash_type', 'danger');
        //
        //            return redirect()->route('user.subscriptions.index');
        //        }

//dd(Auth::user()->Type);
        if (Auth::user()->Type == 1) {
            $type = "صنايعي";

        } elseif (Auth::user()->Type == 2) {
            $type = "شركة";
        } else {
            $type = "العميل";
        }

        $date = date("d-m-Y");
        dd($this->dateDiff($date, $subscription->subscription_end_date));

        $all_plans = Plan::where('id', '!=', $subscription->plan_id)
            ->where('Type', Auth::user()->Type)
            ->get();

        return response()->view('backend.user.subscription.edit',
            compact('subscription', 'all_plans', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Subscription $subscription)
    {
        $plan_id = $request->plan_id;

        // validate plan_id
        if (empty($plan_id)) {
            \Session::flash('flash_message', __('alert.subscription-choose-plan'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // validate plan_id exist
        $plan_id_exist = Plan::where('id', $plan_id)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
            ->get()->count();

        if ($plan_id_exist == 0) {
            \Session::flash('flash_message', __('alert.plan-not-exist'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // TODO
        // start PayPal payment gateway process

        // update plan_id to the subscription record
        $subscription->plan_id = $plan_id;
        // update subscription_end_date
        $select_plan = Plan::find($plan_id);

        $today = new DateTime('now');
        if (!empty($subscription->subscription_end_date)) {
            $today = new DateTime($subscription->subscription_end_date);
        }

        if ($select_plan->plan_period == Plan::PLAN_MONTHLY) {
            $today->modify("+1 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if ($select_plan->plan_period == Plan::PLAN_QUARTERLY) {
            $today->modify("+3 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if ($select_plan->plan_period == Plan::PLAN_YEARLY) {
            $today->modify("+12 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        $subscription->save();

        \Session::flash('flash_message', __('alert.plan-switched'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }
}
