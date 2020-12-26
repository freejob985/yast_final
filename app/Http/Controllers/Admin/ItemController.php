<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\City;
use App\Country;
use App\CustomField;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemFeature;
use App\ItemImageGallery;
use App\Setting;
use App\State;
use App\User;
use App\ThreadItem;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Mail;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
             /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Listings - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.item.items', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
     if($request->fullUrl()=="https://yasta.net/admin/items?category=7&state=0"){
      
             $all_categories = Category::whereNotIn('id', [7])->orderBy('category_name')->get();
        //$country = Country::where('country_abbr', 'US')->first();
        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $all_states = $country->states()->orderBy('state_name')->get();

        $category_id = $request->category;
        $state_id = $request->state;

        // check if all ids are valid
        if($category_id)
        {
            $category = Category::findOrFail($category_id);

            if($state_id)
            {
                $state = State::findOrFail($state_id);

                $items = $category->items()->get();
            
                return response()->view('backend.admin.item.index', compact(
                    'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
            }

            $items = $category->items()
                ->orderBy('created_at', 'DESC')
                ->get();
       

            return response()->view('backend.admin.item.index', compact(
                'all_categories', 'all_states', 'category_id', 'state_id', 'items'));

        }
        else
        {
            if($state_id)
            {
                $state = State::findOrFail($state_id);

                $items = $state->items()
                    ->where('country_id', $settings->setting_site_location_country_id)
                    ->get();

                return response()->view('backend.admin.item.index', compact(
                    'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
            }

            $items = Item::where('country_id', $settings->setting_site_location_country_id)
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->view('backend.admin.item.index', compact(
                'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
        } 
        
     }else{
      
         
        $all_categories = Category::whereNotIn('id', [7])->orderBy('id')->get();
      
        //$country = Country::where('country_abbr', 'US')->first();
        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $all_states = $country->states()->orderBy('state_name')->get();

        $category_id = $request->category;
        $state_id = $request->state;

        // check if all ids are valid
        if($category_id)
        {
            $category = Category::findOrFail($category_id);

            if($state_id)
            {
                $state = State::findOrFail($state_id);

                $items = $category->items()
                    ->where('state_id', $state->id)
                    -> whereNotIn('category_id', array( 7))     
                    ->where('country_id', $settings->setting_site_location_country_id)
                    ->get();

                return response()->view('backend.admin.item.index', compact(
                    'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
            }

            $items = $category->items()
                ->where('country_id', $settings->setting_site_location_country_id)
                     -> whereNotIn('category_id', array( 7))
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->view('backend.admin.item.index', compact(
                'all_categories', 'all_states', 'category_id', 'state_id', 'items'));

        }
        else
        {
            if($state_id)
            {
                $state = State::findOrFail($state_id);

                $items = $state->items()
                    ->where('country_id', $settings->setting_site_location_country_id)
                         -> whereNotIn('category_id', array( 7))
                    ->get();

                return response()->view('backend.admin.item.index', compact(
                    'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
            }

            $items = Item::where('country_id', $settings->setting_site_location_country_id)
                     -> whereNotIn('category_id', array( 7))
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->view('backend.admin.item.index', compact(
                'all_categories', 'all_states', 'category_id', 'state_id', 'items'));
        }   
     }
       
   

    }
    public function adv($adv,Request $request){
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
        if($category_id)
        {
            $category = Category::findOrFail($category_id);
            $all_customFields = $category->customFields()
                ->orderBy('custom_field_order')
                ->orderBy('created_at')
                ->get();
        }
        return response()->view('backend.admin.item.adv',
            compact('all_categories', 'all_states',
                'category_id', 'all_customFields','adv'));
        //   dd($adv);
    }
    public function adv_req(Request $request){
        dd(
            $request->all()
            );
    }



    public function msg(){
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Create Listing - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.item.create-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
         if(Auth::user()->Type=="1"){
             dd(1);
        $all_categories = Category::whereNotIn('id', [1])->orderBy('id')->get();
         }else{
         $all_categories = Category::orderBy('id')->get();

         }
        
        //$country = Country::where('country_abbr', 'US')->first();
        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $all_states = $country->states()->get();

        $category_id = $request->category > 0 ? $request->category : '';
        $all_customFields = collect();
        if($category_id)
        {
            $category = Category::findOrFail($category_id);
            $all_customFields = $category->customFields()
                ->orderBy('custom_field_order')
                ->orderBy('created_at')
                ->get();
        }

        return response()->view('backend.admin.item.create',
            compact('all_categories', 'all_states',
                'category_id', 'all_customFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {

     //    dd($request->a1);
                $all_subscriber = User::all();
  
        
        // prepare rule for general information
        $validate_rule = [
            'category' => 'required|numeric',
            'item_status' => 'required|numeric',
            'item_featured' => 'required|numeric',
            'item_title' => 'required|max:255',
   
            'a1' => 'required',
            'a2' => 'required',
     
            'item_phone' => 'nullable|max:255',
            'item_website' => 'nullable|url|max:255',
            'item_social_facebook' => 'nullable|url|max:255',
            'item_social_twitter' => 'nullable|url|max:255',
            'item_social_linkedin' => 'nullable|url|max:255',
//            'feature_image' => 'image|max:5120',
//            'image_gallery.*' => 'image|max:5120',
        ];



        //================================================

if ($request->hasFile('file')) {
    $file =$request->file; 
    $extension = $file->getClientOriginalExtension();
    $filename = rand(111, 99999) . "_mrbean" . '.' . $extension;
    $file->move(public_path() . '/files/', $filename);
  

 

}else{

    $filename="";
}

//====================================================================================


        // validate category_id
        $select_category = Category::find($request->category);
        if(!$select_category)
        {
            throw ValidationException::withMessages(
                [
                    'category' => 'Category not found',
                ]);
        }

        // prepare validate rule for custom fields
        $custom_field_validation = array();
        $custom_field_link = $select_category->customFields()
            ->where('custom_field_type', CustomField::TYPE_LINK)
            ->get();

        if($custom_field_link->count() > 0)
        {
            foreach($custom_field_link as $key => $a_link)
            {
                $custom_field_validation[str_slug($a_link->custom_field_name . $a_link->id)] = 'nullable|url';
            }
        }

        $validate_rule = array_merge($validate_rule, $custom_field_validation);

        // validate request
        $request->validate($validate_rule);

        // validate state_id
        $select_state = State::find($request->state_id[0]);
        if(!$select_state)
        {
            throw ValidationException::withMessages(
                [
                    'a1' => 'State not found',
                ]);
        }
        // validate city_id
        $select_city = City::find($request->city_id[0]);
        if(!$select_city)
        {
            throw ValidationException::withMessages(
                [
                    'a2' => 'City not found',
                ]);
        }

        $user_id = Auth::user()->id;
        //$user_id = 1;
        $category_id = $select_category->id;
        $item_status = $request->item_status;
        $item_featured = $request->item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED : Item::ITEM_NOT_FEATURED;
        $item_featured_by_admin = $item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED_BY_ADMIN : Item::ITEM_NOT_FEATURED_BY_ADMIN;
        $item_title = ucfirst(strtolower($request->item_title));
        //$item_slug = str_slug($request->item_title) . '-' . $random_identifier;
        $item_slug = get_item_slug();
        $item_description = $request->item_description;
        $item_address = $request->item_address;
        $item_address_hide = $request->item_address_hide == 1 ? 1 : 0;
        $item_phone_hide = $request->item_phone_hide == 1 ? 1 : 0;
              $city_id_m =implode(",",$request->a2)  ;
         $state_id_m = implode(",",$request->a1) ;
       // dd($item_phone_hide);
        $city_id = $select_city->id;
        $state_id = $select_state->id;

        //$default_country = Country::where('country_abbr', 'US')->first();
        $default_country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $country_id = $default_country->id;

        $item_postal_code = $request->item_postal_code;
        $item_lat = $request->item_lat;
        $item_lng = $request->item_lng;

        // guess lat and lng if empty
        if(empty($item_lat) || empty($item_lng))
        {
            $item_lat = $select_city->city_lat;
            $item_lng = $select_city->city_lng;
        }

        $item_phone = empty($request->item_phone) ? null : $request->item_phone;
        $item_website = $request->item_website;
        $item_social_facebook = $request->item_social_facebook;
        $item_social_twitter = $request->item_social_twitter;
        $item_social_linkedin = $request->item_social_linkedin;

        // start upload feature image
        $feature_image = $request->feature_image;
        $item_feature_image_name = null;
        $item_feature_image_name_medium = null;
        $item_feature_image_name_small = null;
        $item_feature_image_name_tiny = null;
        if(!empty($feature_image)){

            $currentDate = Carbon::now()->toDateString();

            $item_feature_image_name = $item_slug.'-'.$currentDate.'-'.uniqid().'.png';
            $item_feature_image_name_medium = $item_slug . '-' . $currentDate . '-' . uniqid() . '-medium.png';
            $item_feature_image_name_small = $item_slug . '-' . $currentDate . '-' . uniqid() . '-small.png';
            $item_feature_image_name_tiny = $item_slug . '-' . $currentDate . '-' . uniqid() . '-tiny.png';

            if(!Storage::disk('public')->exists('item')){
                Storage::disk('public')->makeDirectory('item');
            }

            // original size
            $item_feature_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name, $item_feature_image);

            // medium size
            $item_feature_image_medium = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(350, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_medium = $item_feature_image_medium->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_medium, $item_feature_image_medium);

            // small size
            $item_feature_image_small = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(230, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_small = $item_feature_image_small->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_small, $item_feature_image_small);

            // tiny size
            $item_feature_image_tiny = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(160, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_tiny = $item_feature_image_tiny->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_tiny, $item_feature_image_tiny);

        }

        // fill new item data
        $new_item = new Item(array(
            'user_id' => $user_id,
            'item_status' => $item_status,
            'item_featured' => $item_featured,
            'item_featured_by_admin' => $item_featured_by_admin,
            'item_title' => $item_title,
            'item_slug' => $item_slug,
            'item_description' => $item_description,
            'item_image' => $item_feature_image_name,
            'item_image_medium' => $item_feature_image_name_medium,
            'item_image_small' => $item_feature_image_name_small,
            'item_image_tiny' => $item_feature_image_name_tiny,
            'item_address' => $item_address,
            'item_address_hide' => $item_address_hide,
            'item_phone_hide' => $item_phone_hide,
            'city_id' => $city_id_m,
            'state_id' => $state_id_m,
            'country_id' => $default_country->id,
            'item_postal_code' => ".",
            'item_lat' => $item_lat,
            'item_lng' => $item_lng,
            'item_phone' => $item_phone,
            'item_website' => $item_website,
            'item_social_facebook' => $item_social_facebook,
            'item_social_twitter' => $item_social_twitter,
            'item_social_linkedin' => $item_social_linkedin,
            'file' => $filename,
                  'city_id_m' => $city_id_m,
              'state_id_m' => $state_id_m,
            

        ));
        $created_item = $select_category->items()->save($new_item);

        // start to save custom fields data
        $category_custom_fields = $select_category->customFields()->orderBy('custom_field_order')->get();

        if($category_custom_fields->count() > 0)
        {
            foreach($category_custom_fields as $key => $custom_field)
            {
                if($custom_field->custom_field_type == CustomField::TYPE_MULTI_SELECT)
                {
                    $multi_select_values = $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), '');
                    $multi_select_str = '';
                    if(is_array($multi_select_values))
                    {
                        foreach($multi_select_values as $key => $value)
                        {
                            $multi_select_str .= $value . ', ';
                        }
                    }
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => empty($multi_select_str) ? '' : substr(trim($multi_select_str), 0, -1),
                    ));
                }
                else
                {
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), ''),
                    ));
                }

                $created_item_feature = $created_item->features()->save($new_item_feature);

                $created_item->item_features_string = $created_item->item_features_string . $created_item_feature->item_feature_value . " ";
                $created_item->save();
            }
        }

        // start to upload image galleries
        $image_gallary = $request->image_gallery;
        if(is_array($image_gallary) && count($image_gallary) > 0)
        {
            foreach($image_gallary as $key => $image)
            {
                // only total 12 images are allowed
                if($key < 12)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $item_image_gallery_uniqid = uniqid();

                    $item_image_gallery['item_image_gallery_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'.png';
                    $item_image_gallery['item_image_gallery_thumb_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'-thumb.png';

                    //$item_image_gallery['item_image_gallery_size'] = $image->getClientSize();
                    //$item_image_gallery['property_id'] = $created_item->id;

                    if(!Storage::disk('public')->exists('item/gallery')){
                        Storage::disk('public')->makeDirectory('item/gallery');
                    }

                    // original
                    $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream();
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_name'], $one_gallery_image);

                    // thumb size
                    $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 180, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb = $one_gallery_image_thumb->stream();
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_thumb_name'], $one_gallery_image_thumb);

                    $created_item_image_gallery = $created_item->galleries()->create($item_image_gallery);
                }
            }
        }
      if( $request->category=="7"){
     
            
       //    dd( get_item_slug());
            
            	$array=array();
               
               //==========================
           
           foreach ($all_subscriber as $subscriber){
                 $array['item_title']= $request->input('item_title');
                 $array['item_description']= $request->input('item_description');
                 $array['email']= $subscriber->email;
                 $array['item_slug']=  $item_slug;
                 $array['item_image']= "https://yasta.net//laravel_project/public/storage/item/$item_feature_image_name" ;
     

    
 
    //  		Mail::send('/mail/mail', ['array' => $array], function($m) use ($array){
    //  //	dd(print_r($array));
	// 				$m->to($array['email'])->subject('New announcement')->getSwiftMessage()
	// 				->getHeaders()
	// 				->addTextHeader('x-mailgun-native-send', 'true');
	// 				   $m->from('notyfi@digi-gate.tech','digi-gate');
					   
					
					
					
    //             });
                

                DB::table('notificationss')->insert([
                    'Title' => $request->input('item_title'),
                    'owner' =>Auth::user()->name,
                    'Explanation' => $request->input('item_description'),
                    'user' =>  $subscriber->id,
                    'Notifications' => "0",
                    'url' => "https://yasta.net/item/$item_slug",
                            ]);



                            
			
           }	
				
				
        }
        // success, flash message
        \Session::flash('flash_message', __('alert.item-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.items.edit', $created_item);
    }

    /**
     * Display the specified resource.
     *
     * @param Item $item
     * @return RedirectResponse
     */
    public function show(Item $item)
    {
        return redirect()->route('page.item', $item->item_slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Item $item
     * @return Response
     */
    public function edit(Item $item)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit Listing - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.item.edit-item', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        //$country = Country::where('country_abbr', 'US')->first();
        $country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $all_states = $country->states()->orderBy('state_name')->get();
        $all_cities = State::findOrFail($item->state_id)->cities()->orderBy('city_name')->get();

        $category = Category::findOrFail($item->category_id);
        $all_customFields = $category->customFields()
            ->orderBy('custom_field_order')
            ->orderBy('created_at')
            ->get();

        return response()->view('backend.admin.item.edit', compact('all_states', 'all_cities', 'all_customFields', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Item $item
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Item $item)
    {
        // prepare rule for general information
        $validate_rule = [
            'item_status' => 'required|numeric',
            'item_featured' => 'required|numeric',
            'item_title' => 'required|max:255',
            'item_description' => 'required',
            'city_id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'item_postal_code' => 'required|max:255',
            'item_phone' => 'nullable|max:255',
            'item_website' => 'nullable|url|max:255',
            'item_social_facebook' => 'nullable|url|max:255',
            'item_social_twitter' => 'nullable|url|max:255',
            'item_social_linkedin' => 'nullable|url|max:255',
//            'feature_image' => 'image|max:5120',
//            'image_gallery.*' => 'image|max:5120',
        ];

        // prepare validate rule for custom fields
        $select_category = $item->category()->get()->first();
        $custom_field_validation = array();
        $custom_field_link = $select_category->customFields()
            ->where('custom_field_type', CustomField::TYPE_LINK)
            ->get();

        if($custom_field_link->count() > 0)
        {
            foreach($custom_field_link as $key => $a_link)
            {
                $custom_field_validation[str_slug($a_link->custom_field_name . $a_link->id)] = 'nullable|url';
            }
        }

        $validate_rule = array_merge($validate_rule, $custom_field_validation);

        // validate request
        $request->validate($validate_rule);

        // validate state_id
        $select_state = State::find($request->state_id);
        if(!$select_state)
        {
            throw ValidationException::withMessages(
                [
                    'state_id' => 'State not found',
                ]);
        }
        // validate city_id
        $select_city = City::find($request->city_id);
        if(!$select_city)
        {
            throw ValidationException::withMessages(
                [
                    'city_id' => 'City not found',
                ]);
        }

        $user_id = Auth::user()->id;
        //$user_id = 1;
        $category_id = $select_category->id;
        $item_status = $request->item_status;
        $item_featured = $request->item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED : Item::ITEM_NOT_FEATURED;
        $item_featured_by_admin = $item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED_BY_ADMIN : Item::ITEM_NOT_FEATURED_BY_ADMIN;
        $item_title = ucfirst(strtolower($request->item_title));

        //$item_slug = str_slug($request->item_title) . '-' . $random_identifier;

        $item_description = $request->item_description;
        $item_address = $request->item_address;
        $item_address_hide = $request->item_address_hide == 1 ? 1 : 0;
        $city_id = $select_city->id;
        $state_id = $select_state->id;

        //$default_country = Country::where('country_abbr', 'US')->first();
        $default_country = Country::find(Setting::find(1)->setting_site_location_country_id);
        $country_id = $default_country->id;

        $item_postal_code = $request->item_postal_code;
        $item_lat = $request->item_lat;
        $item_lng = $request->item_lng;

        // guess lat and lng if empty
        if(empty($item_lat) || empty($item_lng))
        {
            $item_lat = $select_city->city_lat;
            $item_lng = $select_city->city_lng;
        }

        $item_phone = empty($request->item_phone) ? null : $request->item_phone;
        $item_website = $request->item_website;
        $item_social_facebook = $request->item_social_facebook;
        $item_social_twitter = $request->item_social_twitter;
        $item_social_linkedin = $request->item_social_linkedin;

        // start upload feature image
        $feature_image = $request->feature_image;
        $item_feature_image_name = $item->item_image;
        $item_feature_image_name_medium = $item->item_image_medium;
        $item_feature_image_name_small = $item->item_image_small;
        $item_feature_image_name_tiny = $item->item_image_tiny;
        if(!empty($feature_image)){

            $currentDate = Carbon::now()->toDateString();

            $item_feature_image_name = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '.png';
            $item_feature_image_name_medium = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-medium.png';
            $item_feature_image_name_small = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-small.png';
            $item_feature_image_name_tiny = $item->item_slug . '-' . $currentDate . '-' . uniqid() . '-tiny.png';

            if(!Storage::disk('public')->exists('item')){
                Storage::disk('public')->makeDirectory('item');
            }
            if(Storage::disk('public')->exists('item/' . $item->item_image)){

                Storage::disk('public')->delete('item/' . $item->item_image);
                Storage::disk('public')->delete('item/' . $item->item_image_medium);
                Storage::disk('public')->delete('item/' . $item->item_image_small);
                Storage::disk('public')->delete('item/' . $item->item_image_tiny);

            }

            // original size
            $item_feature_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name, $item_feature_image);

            // medium size
            $item_feature_image_medium = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(350, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_medium = $item_feature_image_medium->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_medium, $item_feature_image_medium);

            // small size
            $item_feature_image_small = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(230, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_small = $item_feature_image_small->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_small, $item_feature_image_small);

            // tiny size
            $item_feature_image_tiny = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$feature_image)))
                ->resize(160, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $item_feature_image_tiny = $item_feature_image_tiny->stream();
            Storage::disk('public')->put('item/'.$item_feature_image_name_tiny, $item_feature_image_tiny);

        }

        $item->item_status = $item_status;
        $item->item_featured = $item_featured;
        $item->item_featured_by_admin = $item_featured_by_admin;
        $item->item_title = $item_title;
        //$item->item_slug = $item_slug;
        $item->item_description = $item_description;

        $item->item_image = $item_feature_image_name;
        $item->item_image_medium = $item_feature_image_name_medium;
        $item->item_image_small = $item_feature_image_name_small;
        $item->item_image_tiny = $item_feature_image_name_tiny;

        $item->item_address = $item_address;
        $item->item_address_hide = $item_address_hide;
        $item->city_id = $city_id;
        $item->state_id = $state_id;
        $item->country_id = $country_id;
        $item->item_postal_code = $item_postal_code;
        $item->item_lat = $item_lat;
        $item->item_lng = $item_lng;

        $item->item_phone = $item_phone;
        $item->item_website = $item_website;
        $item->item_social_facebook = $item_social_facebook;
        $item->item_social_twitter = $item_social_twitter;
        $item->item_social_linkedin = $item_social_linkedin;

        $item->item_features_string = null;
        $item->save();

        // start to save custom fields data
        $item->features()->delete();
        $category_custom_fields = $select_category->customFields()->orderBy('custom_field_order')->get();

        if($category_custom_fields->count() > 0)
        {
            foreach($category_custom_fields as $key => $custom_field)
            {
                if($custom_field->custom_field_type == CustomField::TYPE_MULTI_SELECT)
                {
                    $multi_select_values = $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), '');
                    $multi_select_str = '';
                    if(is_array($multi_select_values))
                    {
                        foreach($multi_select_values as $key => $value)
                        {
                            $multi_select_str .= $value . ', ';
                        }
                    }
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => empty($multi_select_str) ? '' : substr(trim($multi_select_str), 0, -1),
                    ));
                }
                else
                {
                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => $request->get(str_slug($custom_field->custom_field_name . $custom_field->id), ''),
                    ));
                }

                $created_item_feature = $item->features()->save($new_item_feature);

                $item->item_features_string = $item->item_features_string . $created_item_feature->item_feature_value . " ";
                $item->save();
            }
        }

        // start to upload image galleries
        $image_gallary = $request->image_gallery;
        if(is_array($image_gallary) && count($image_gallary) > 0)
        {
            $total_item_image_gallery = $item->galleries()->get()->count();
            foreach($image_gallary as $key => $image)
            {
                // only total 12 images are allowed
                if($total_item_image_gallery + $key < 12)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $item_image_gallery_uniqid = uniqid();

                    $item_image_gallery['item_image_gallery_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'.png';
                    $item_image_gallery['item_image_gallery_thumb_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'-thumb.png';
                    //$item_image_gallery['item_image_gallery_size'] = $image->getClientSize();
                    //$item_image_gallery['property_id'] = $created_item->id;

                    if(!Storage::disk('public')->exists('item/gallery')){
                        Storage::disk('public')->makeDirectory('item/gallery');
                    }

                    // original
                    $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream();
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_name'], $one_gallery_image);

                    // thumb size
                    $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 180, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb = $one_gallery_image_thumb->stream();
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_thumb_name'], $one_gallery_image_thumb);

                    $created_item_image_gallery = $item->galleries()->create($item_image_gallery);
                }
            }
        }

        // success, flash message
        \Session::flash('flash_message', __('alert.item-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.items.edit', $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Item $item
     * @return RedirectResponse
     */
    public function destroy(Item $item)
    {
        $item->deleteItem();

        \Session::flash('flash_message', __('alert.item-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.items.index');
    }

    public function approveItem(Item $item)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $item->user_id)
        {
            $item->item_status = Item::ITEM_PUBLISHED;
            $item->save();

            \Session::flash('flash_message', __('alert.item-approved'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.edit', $item);
        }
        else
        {
            return redirect()->route('admin.items.index');
        }
    }

    public function disApproveItem(Item $item)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $item->user_id)
        {
            $item->item_status = Item::ITEM_SUBMITTED;
            $item->save();

            \Session::flash('flash_message', __('alert.item-disapproved'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.edit', $item);
        }
        else
        {
            return redirect()->route('admin.items.index');
        }
    }

    public function suspendItem(Item $item)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $item->user_id)
        {
            $item->item_status = Item::ITEM_SUSPENDED;
            $item->save();

            \Session::flash('flash_message', __('alert.item-suspended'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.edit', $item);
        }
        else
        {
            return redirect()->route('admin.items.index');
        }
    }

    public function savedItems()
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Saved Listings - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.item.saved-items', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $saved_items = $login_user->savedItems()->get();

        return response()->view('backend.admin.item.saved',
            compact('saved_items'));
    }

    public function unSaveItem(Request $request, string $item_slug)
    {
        $settings = Setting::find(1);

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->get()->first();

        if($item)
        {
            $login_user = Auth::user();

            if($login_user->hasSavedItem($item->id))
            {
                $login_user->savedItems()->detach($item->id);

                \Session::flash('flash_message', __('backend.item.unsave-item-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.items.saved');
            }
            else
            {
                \Session::flash('flash_message', __('backend.item.unsave-item-error-exist'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('admin.items.saved');
            }
        }
        else
        {
            abort(404);
        }

    }

    /**
     * @param string $item_slug
     * @return Response
     */
    public function itemReviewsCreate(string $item_slug)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        SEOMeta::setTitle(__('review.seo.write-a-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', Auth::user()->id)
            ->get()->first();

        if($item)
        {
            if($item->reviewedByUser(Auth::user()->id))
            {
                \Session::flash('flash_message', __('review.alert.cannot-post-more-one-review'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
            else
            {
                return response()->view('backend.admin.item.review.create',
                    compact('item'));
            }

        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsStore(Request $request, string $item_slug)
    {
        $settings = Setting::find(1);

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', Auth::user()->id)
            ->get()->first();

        if($item)
        {
            if($item->reviewedByUser(Auth::user()->id))
            {
                \Session::flash('flash_message', __('review.alert.cannot-post-more-one-review'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('page.item', $item->item_slug);
            }
            else
            {
                $request->validate([
                    'rating' => 'required|numeric|max:5',
                    'customer_service_rating' => 'required|numeric|max:5',
                    'quality_rating' => 'required|numeric|max:5',
                    'friendly_rating' => 'required|numeric|max:5',
                    'pricing_rating' => 'required|numeric|max:5',
                    'title' => 'nullable|max:255',
                    'body' => 'required|max:65535',
                    'recommend' => 'nullable|numeric|max:1',
                ]);

                $login_user = Auth::user();
                $rating_title = empty($request->title) ? '' : $request->title;
                $rating_body = $request->body;
                $overall_rating = $request->rating;
                $customer_service_rating = $request->customer_service_rating;
                $quality_rating = $request->quality_rating;
                $friendly_rating = $request->friendly_rating;
                $pricing_rating = $request->pricing_rating;
                $recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
                $approved = $login_user->isAdmin() ? true : false;

                $new_rating = $item->rating([
                    'title' => $rating_title,
                    'body' => $rating_body,
                    'customer_service_rating' => $customer_service_rating,
                    'quality_rating' => $quality_rating,
                    'friendly_rating' => $friendly_rating,
                    'pricing_rating' => $pricing_rating,
                    'rating' => $overall_rating,
                    'recommend' => $recommend,
                    'approved' => $approved, // This is optional and defaults to false
                ], $login_user);

                \Session::flash('flash_message', __('review.alert.review-posted-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $new_rating->id]);
            }

        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsEdit(string $item_slug, int $review)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        SEOMeta::setTitle(__('review.seo.edit-a-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', Auth::user()->id)
            ->get()->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $review = $item->getReviewById($review);

                return response()->view('backend.admin.item.review.edit',
                    compact('item', 'review'));
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsUpdate(Request $request, string $item_slug, int $review)
    {
        $settings = Setting::find(1);

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', Auth::user()->id)
            ->get()->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $request->validate([
                    'rating' => 'required|numeric|max:5',
                    'customer_service_rating' => 'required|numeric|max:5',
                    'quality_rating' => 'required|numeric|max:5',
                    'friendly_rating' => 'required|numeric|max:5',
                    'pricing_rating' => 'required|numeric|max:5',
                    'title' => 'nullable|max:255',
                    'body' => 'required|max:65535',
                    'recommend' => 'nullable|numeric|max:1',
                ]);

                $login_user = Auth::user();
                $rating_title = empty($request->title) ? '' : $request->title;
                $rating_body = $request->body;
                $overall_rating = $request->rating;
                $customer_service_rating = $request->customer_service_rating;
                $quality_rating = $request->quality_rating;
                $friendly_rating = $request->friendly_rating;
                $pricing_rating = $request->pricing_rating;
                $recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
                $approved = $login_user->isAdmin() ? true : false;

                $updated_rating = $item->updateRating($review, [
                    'title' => $rating_title,
                    'body' => $rating_body,
                    'rating' => $overall_rating,
                    'customer_service_rating' => $customer_service_rating,
                    'quality_rating' => $quality_rating,
                    'friendly_rating' => $friendly_rating,
                    'pricing_rating' => $pricing_rating,
                    'recommend' => $recommend,
                    'approved' => $approved, // This is optional and defaults to false
                ]);

                \Session::flash('flash_message', __('review.alert.review-updated-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $updated_rating->id]);
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsDestroy(string $item_slug, int $review)
    {
        $settings = Setting::find(1);

        $item = Item::where('item_slug', $item_slug)
            ->where('country_id', $settings->setting_site_location_country_id)
            ->where('item_status', Item::ITEM_PUBLISHED)
            ->where('user_id', '!=', Auth::user()->id)
            ->get()->first();

        if($item)
        {
            if($item->hasReviewByIdAndUser($review, Auth::user()->id))
            {
                $item->deleteRating($review);

                \Session::flash('flash_message', __('review.alert.review-deleted-success'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.items.reviews.index');
            }
            else
            {
                abort(404);
            }
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsIndex(Request $request)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        SEOMeta::setTitle(__('review.seo.manage-reviews', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $reviews_type = $request->reviews_type;

        if(empty($reviews_type) || $reviews_type == 'all')
        {
            $reviews = DB::table('reviews')
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        else
        {
            if($reviews_type == 'pending')
            {
                $reviews = DB::table('reviews')
                    ->where('approved', Item::ITEM_REVIEW_PENDING)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            if($reviews_type == 'approved')
            {
                $reviews = DB::table('reviews')
                    ->where('approved', Item::ITEM_REVIEW_APPROVED)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            if($reviews_type == 'me')
            {
                $reviews = DB::table('reviews')
                    ->where('author_id', Auth::user()->id)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

        }

        return response()->view('backend.admin.item.review.index',
            compact('reviews_type', 'reviews'));
    }

    public function itemReviewsShow(int $review_id)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        SEOMeta::setTitle(__('review.seo.show-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->get()->first();

        if($review)
        {
            $item_id = $review->reviewrateable_id;
            $item = Item::findOrFail($item_id);

            return response()->view('backend.admin.item.review.show',
                compact('item', 'review'));
        }
        else
        {
            abort(404);
        }
    }

    public function itemReviewsApprove(int $review_id)
    {
        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->get()->first();

        if($review)
        {
            DB::table('reviews')
                ->where('id', $review_id)
                ->update(['approved' => Item::ITEM_REVIEW_APPROVED]);

            \Session::flash('flash_message', __('review.alert.review-approved'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.reviews.show', ['review_id' => $review->id]);
        }
        else
        {
            abort(404);
        }

    }

    public function itemReviewsDisapprove(int $review_id)
    {
        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->get()->first();

        if($review)
        {
            DB::table('reviews')
                ->where('id', $review_id)
                ->update(['approved' => Item::ITEM_REVIEW_PENDING]);

            \Session::flash('flash_message', __('review.alert.review-disapproved'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.reviews.show', ['review_id' => $review->id]);
        }
        else
        {
            abort(404);
        }

    }

    public function itemReviewsDelete(int $review_id)
    {

        $review = DB::table('reviews')
            ->where('id', $review_id)
            ->get()->first();

        if($review)
        {
            DB::table('reviews')
                ->where('id', $review_id)
                ->delete();

            \Session::flash('flash_message', __('review.alert.review-deleted-success'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.items.reviews.index');
        }
        else
        {
            abort(404);
        }

    }
}
