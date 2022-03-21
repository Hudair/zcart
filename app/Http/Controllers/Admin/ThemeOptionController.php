<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Manufacturer;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\UpdateFeaturedCategories;
use App\Http\Requests\Validations\UpdateFeaturedBrandsRequest;
use App\Http\Requests\Validations\UpdateTrendingNowCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ThemeOptionController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $storeFrontThemes = collect($this->storeFrontThemes());
        // $sellingThemes = collect($this->sellingThemes());
        // return view('admin.theme.index', compact('storeFrontThemes', 'sellingThemes'));
        $featured_brands = Manufacturer::whereIn('id', get_from_option_table('featured_brands', []))
        ->get()->pluck('name', 'id')->toArray();

        $trending_categories = Category::whereIn('id', get_from_option_table('trending_categories', []))
        ->get()->pluck('name', 'id')->toArray();

        return view('admin.theme.options', compact('featured_brands', 'trending_categories'));
    }

    /**
     * Show the form for featuredCategories.
     * @return \Illuminate\Http\Response
     */
    public function featuredCategories()
    {
        $categories = Category::all();
        $category = [];

        foreach ($categories as $key => $cat) {
            array_push($category, [$cat->id => $cat->name.' | '. $cat->subGroup->name]);
        }

        $category = call_user_func_array('array_merge', $category);

        return view('admin.theme._edit_featured_categories', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedCategories(UpdateFeaturedCategories $request)
    {
        try {
            Category::where('featured', true)->update(['featured' => Null]); // Reset all featured categories

            Category::whereIn('id', $request->input('featured_categories'))->update(['featured' => true]);

            // Clear featured_categories from cache
            Cache::forget('featured_categories');

            return redirect()->route('admin.appearance.theme.option', '#settings-tab')
            ->with('success', trans('messages.updated_featured_categories'));
        }
        catch (\Exception $e) {
         // Failed
        }

        return redirect()->route('admin.appearance.theme.option')
        ->with('warning', trans('messages.failed'));
    }

    /**
     * Show the form for featuredCategories.
     * @return \Illuminate\Http\Response
     */
    public function featuredBrands()
    {
        $brands = Manufacturer::all()->pluck('name', 'id')->toArray();

        $featured_brands = Manufacturer::whereIn('id', get_from_option_table('featured_brands', []))
        ->get()->pluck('name', 'id')->toArray();

        return view('admin.theme._edit_featured_brands', compact('featured_brands', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedBrands(UpdateFeaturedBrandsRequest $request)
    {
        $update = DB::table(get_option_table_name())->updateOrInsert(
            ['option_name' => 'featured_brands'],
            [
                'option_name' => 'featured_brands',
                'option_value' => serialize($request->featured_brands),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        if ($update) {
            // Clear featured_brands from cache
            Cache::forget('featured_brands');

            return redirect()->route('admin.appearance.theme.option')
            ->with('success', trans('messages.featured_brands_updated'));
        }

        return redirect()->route('admin.appearance.theme.option')
        ->with('warning', trans('messages.failed'));
    }

    /**
     * Show form for Trending Categories.
     * @return \Illuminate\Http\Response
     */
    public function editTrendingNow()
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();

        $trending_categories = Category::whereIn('id', get_from_option_table('trending_categories', []))
        ->get()->pluck('name', 'id')->toArray();

        return view('admin.theme._edit_trending_categories', compact('categories', 'trending_categories'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTrendingNow(UpdateTrendingNowCategoryRequest $request)
    {
         /*$limit = config('system.popular.trending_category');

         if ($limit < count($request->trending_categories)) {

            return redirect()->route('admin.appearance.theme.option')
                ->with('warning', trans('messages.trending_categories_update_failed', ['limit' => $limit]));
         }*/

        $update = DB::table(get_option_table_name())->updateOrInsert(
            ['option_name' => 'trending_categories'],
            [
                'option_name' => 'trending_categories',
                'option_value' => serialize($request->trending_categories),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        if ($update) {
            // Clear trending_categories from cache
            Cache::forget('trending_categories');

            return redirect()->route('admin.appearance.theme.option')
            ->with('success', trans('messages.trending_now_category_updated'));
        }

        return redirect()->route('admin.appearance.theme.option')
        ->with('warning', trans('messages.failed'));
    }

}
