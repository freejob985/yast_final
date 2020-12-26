<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
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
        //SEOMeta::setTitle('Dashboard - Categories - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.category.categories', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_categories = Category::all();

        return response()->view('backend.admin.category.index', compact('all_categories'));
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
        //SEOMeta::setTitle('Dashboard - Create Category - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.category.create-category', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.category.create');
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
            'category_name' => 'required|unique:categories|max:255',
        ]);
        
               
              if ($request->hasFile('fileToUpload')) {
            $file = $request->fileToUpload;
            $extension = $file->getClientOriginalExtension();
            $filename = rand(111, 99999) . "_mrbean" . '.' . $extension;
            $file->move(public_path() . '/files/', $filename);

        } else {

            $filename="";
        }

        $category = new Category();

        //$category->category_name = ucwords(strtolower($request->category_name));
        //$category->category_name = ucwords($request->category_name);
        $category->category_name = $request->category_name;
        $category->category_slug = str_slug($request->category_name);
        $category->category_icon = $request->category_icon;
        $category->fileToUpload = $filename;

        $category->save();

        // append category id to category_slug
        $category->category_slug = str_slug($category->category_name . '-' . $category->id);
        $category->save();

        \Session::flash('flash_message', __('alert.category-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        /**
         * Start SEO
         */
        $settings = Setting::find(1);
        //SEOMeta::setTitle('Dashboard - Edit Category - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.admin.category.edit-category', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|max:255',
        ]);


               
              if ($request->hasFile('fileToUpload')) {
            $file = $request->fileToUpload;
            $extension = $file->getClientOriginalExtension();
            $filename = rand(111, 99999) . "_mrbean" . '.' . $extension;
            $file->move(public_path() . '/files/', $filename);

        } else {

            $filename="";
        }
        
        
        //$category_name = ucwords(strtolower($request->category_name));
        //$category_name = ucwords($request->category_name);
        $category_name = $request->category_name;
        $category_slug = str_slug($request->category_name . '-' . $category->id);
        $category_icon = $request->category_icon;

        $validate_error = array();
        $category_name_exist = Category::where('category_name', $category_name)
            ->where('id', '!=', $category->id)->get()->count();
        if($category_name_exist > 0)
        {
            $validate_error['category_name'] = 'Category name has been taken.';
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        else
        {
            $category->category_name = $category_name;
            $category->category_slug = $category_slug;
            $category->category_icon = $category_icon;
            $category->fileToUpload = $filename;
            $category->save();

            \Session::flash('flash_message', __('alert.category-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.categories.edit', $category);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        // check model relations before delete
        if($category->customFields()->get()->count() > 0)
        {
            \Session::flash('flash_message', __('alert.category-delete-error-custom-field'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.categories.edit', $category);
        }
        elseif($category->items()->get()->count() > 0)
        {
            \Session::flash('flash_message', __('alert.category-delete-error-listing'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.categories.edit', $category);
        }
        else
        {
            $category->delete();

            \Session::flash('flash_message', __('alert.category-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.categories.index');
        }
    }
}
