<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Product;
use App\Inventory;
use App\Helpers\ListHelper;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\UpdateBestFindsRequest;
use App\Http\Requests\Validations\UpdateDealOfTheDayRequest;
use App\Http\Requests\Validations\UpdateFeaturedItemsRequest;
use App\Http\Requests\Validations\UpdatePromotionalTaglineRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PromotionsController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.promotions.options');
    }

    /**
     * Show the form for deal of the day.
     * @return \Illuminate\Http\Response
     */
    public function editDealOfTheDay()
    {
        $item = get_deal_of_the_day();

        return view('admin.promotions._edit_deal_of_the_day', compact('item'));
    }

    /**
     *  update Deal Of The Day.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateDealOfTheDay(UpdateDealOfTheDayRequest $request)
    {
        if (update_option_table_record('deal_of_the_day', $request->item_id)) {
            // Clear deal_of_the_day from cache
            Cache::forget('deal_of_the_day');

            return redirect()->route('admin.promotions')
            ->with('success', trans('messages.updated_deal_of_the_day'));
        }

        return redirect()->route('admin.promotions')->with('error', trans('messages.failed'));
    }

    //Featured Products:
    public function editFeaturedItems()
    {
        $featured_items = ListHelper::featured_items();

        return view('admin.promotions._edit_featured_items', compact('featured_items'));
    }

    /**
     * Update Featured Products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedItems(UpdateFeaturedItemsRequest $request)
    {
        if (update_option_table_record('featured_items', $request->featured_items)) {
            // Clear featured_items from cache
            Cache::forget('featured_items');

            return redirect()->route('admin.promotions')
            ->with('success', trans('messages.featured_items_updated'));
        }

        return redirect()->route('admin.promotions')->with('error', trans('messages.failed'));
    }

    /**
     * Promotional Tagline
     * @return \Illuminate\Http\Response
     */
    public function editTagline()
    {
        $tagline = get_from_option_table('promotional_tagline', []);

        return view('admin.promotions._edit_tagline', compact('tagline'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTagline(UpdatePromotionalTaglineRequest $request)
    {
        $data = [
            'text' => $request->text,
            'action_url' => $request->action_url
        ];

        if (update_option_table_record('promotional_tagline', $data)) {
            // Clear promotional_tagline from cache
            Cache::forget('promotional_tagline');

            return redirect()->route('admin.promotions')
            ->with('success', trans('messages.updated_promotional_tagline'));
        }

        return redirect()->route('admin.promotions')->with('error', trans('messages.failed'));
    }

    /**
     * Edit Best Finds
     * @return \Illuminate\Http\Response
     */
    public function editBestFinds()
    {
        $bestFinds = get_from_option_table('best_finds_under');

        return view('admin.promotions._edit_best_finds', compact('bestFinds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateBestFinds(UpdateBestFindsRequest $request)
    {
        if (update_option_table_record('best_finds_under', $request->price)) {
            // Update the cached value
            Cache::forget('deals_under');
            Cache::remember('deals_under', config('cache.remember.deals', 0), function() use ($request) {
                return ListHelper::best_find_under($request->price);
            });

            return redirect()->route('admin.promotions')
            ->with('success', trans('messages.best_finds_under_updated'));
        }

        return redirect()->route('admin.promotions')->with('error', trans('messages.failed'));
    }
}
