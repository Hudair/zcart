<?php

namespace App\Http\Controllers\Storefront;

use Carbon\Carbon;
use App\Category;
use App\Inventory;
use App\CategoryGroup;
use App\CategorySubGroup;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $term = $request->input('q');

        $items = Inventory::search($term)->where('active', 1)->get();
        $items = $items->unique(function ($item) {
            return $item['product_id'].$item['shop_id'];
        });

        if (is_incevio_package_loaded('pharmacy')) {
            $items = $items->where('expiry_date', '>', Carbon::now());
        }

        $items->load([
            'shop:id,slug,name,current_billing_plan,trial_ends_at,active',
            'shop.config:shop_id,maintenance_mode',
            'shop.currentSubscription',
            'product:id,name,gtin,model_number'
        ]);

        // return view('debug');

        // Keep results only from active shops
        $items = $items->filter(function ($product) {
            return $product->shop->canGoLive();
        });

        // Filter variants from same vendor
        $items = $items->unique(function ($item) {
            return $item['product_id'].$item['shop_id'];
        });

        $category = Null;

        if ($request->has('in')) {
            $category = Category::where('slug', $request->input('in'))->active()->firstOrFail();
            $listings = $category->listings()->available()->get();
            $items = $items->intersect($listings);
        }
        else if ($request->has('insubgrp') && ($request->input('insubgrp') != 'all')) {
            $category = CategorySubGroup::where('slug', $request->input('insubgrp'))->active()->firstOrFail();
            $listings = prepareFilteredListings($request, $category);
            $items = $items->intersect($listings);
        }
        else if ($request->has('ingrp')) {
            $category = CategoryGroup::where('slug', $request->input('ingrp'))->active()->firstOrFail();
            $listings = prepareFilteredListings($request, $category);
            $items = $items->intersect($listings);
        }

        $items = $items->where('stock_quantity', '>', 0)->where('available_from', '<=', Carbon::now());

        // Attributes for filters
        $brands = ListHelper::get_unique_brand_names_from_linstings($items);
        $priceRange = ListHelper::get_price_ranges_from_linstings($items);

        if ($request->has('free_shipping')) {
            $items = $items->where('free_shipping', 1);
        }
        if ($request->has('new_arrivals')) {
            $items = $items->where('created_at', '>', Carbon::now()->subDays(config('system.filter.new_arrival', 7)));
        }
        if ($request->has('has_offers')) {
            $items = $items->where('offer_price', '>', 0)
            ->where('offer_start', '<', Carbon::now())
            ->where('offer_end', '>', Carbon::now());
        }

        if ($request->has('sort_by')) {
            switch ($request->get('sort_by')) {
                case 'newest':
                    $items = $items->sortByDesc('created_at');
                    break;

                case 'oldest':
                    $items = $items->sortBy('created_at');
                    break;

                case 'price_acs':
                    $items = $items->sortBy('sale_price');
                    break;

                case 'price_desc':
                    $items = $items->sortByDesc('sale_price');
                    break;

                case 'best_match':
                default:
                    break;
            }
        }

        if ($request->has('condition')) {
            $items = $items->whereIn('condition', array_keys($request->input('condition')));
        }

        if ($request->has('price')) {
            $price = explode('-', $request->input('price'));
            $items = $items->where('sale_price', '>=', $price[0])->where('sale_price', '<=', $price[1]);
        }

        if ($request->has('brand')) {
            $items = $items->whereIn('brand', array_keys($request->input('brand')));
        }

        $products = $items->paginate(config('system.view_listing_per_page', 16));

        $products->load([
            'product' => function($q) {
                $q->select('id')->with([
                    'categories:id,name,slug,category_sub_group_id',
                    'categories.subGroup:id,name,slug,category_group_id',
                    'categories.subGroup.group:id,name,slug'
                ]);
            },
            // 'feedbacks:rating,feedbackable_id,feedbackable_type',
            'images:path,imageable_id,imageable_type'
        ])
        ->loadCount([
            // 'feedbacks',
            'feedbacks as ratings' => function($q2) {
                $q2->select(\DB::raw('avg(rating)'));
            }
        ]);

        return view('theme::search_results', compact('products', 'category', 'brands', 'priceRange'));
    }
}
