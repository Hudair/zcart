<?php

namespace App\Http\Controllers\Api;

use App\Shop;
use App\Product;
use App\Category;
use App\Inventory;
use App\Manufacturer;
use Carbon\Carbon;
use App\CategoryGroup;
use App\CategorySubGroup;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ItemLightResource;
use App\Http\Resources\ListingResource;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\ShopListingResource;
use App\Http\Resources\ManufacturerResource;
use App\Http\Resources\ShippingOptionResource;
use Illuminate\Database\Eloquent\Builder;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($list = 'latest')
    {
        switch ($list) {
            case 'trending':
                $listings = ListHelper::popular_items(config('mobile_app.popular.period.trending', 2), config('mobile_app.popular.take.trending', 8));
                break;

            case 'popular':
                $listings = ListHelper::popular_items(config('mobile_app.popular.period.weekly', 7), config('mobile_app.popular.take.weekly', 8));
                break;

            case 'random':
                $listings = ListHelper::random_items(Null);
                break;

            case 'latest':
            default:
                $listings = ListHelper::latest_available_items(8);
                break;
        }

        return ListingResource::collection($listings);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $term)
    {
        $products = Inventory::search($term)->where('active', 1)->get();
        $products->load(['shop:id,current_billing_plan,active']);

        // Keep results only from active shops
        $products = $products->filter(function ($product) {
            return ($product->shop->current_billing_plan !== Null) && ($product->shop->active == 1);
        });

        $products = $products->where('stock_quantity', '>', 0)->where('available_from', '<=', Carbon::now());

        if ($request->has('free_shipping')) {
            $products = $products->where('free_shipping', 1);
        }

        if ($request->has('new_arrivals')) {
            $products = $products->where('created_at', '>', Carbon::now()->subDays(config('mobile_app.filter.new_arrival', 7)));
        }

        if ($request->has('has_offers')) {
            $products = $products->where('offer_price', '>', 0)
            ->where('offer_start', '<', Carbon::now())
            ->where('offer_end', '>', Carbon::now());
        }

        if ($request->has('condition')) {
            $products = $products->whereIn('condition', array_keys($request->input('condition')));
        }

        if ($request->has('price')) {
            $price = explode('-', request()->input('price'));
            $products = $products->where('sale_price', '>=', $price[0])->where('sale_price', '<=', $price[1]);
        }

        $products = $products->paginate(config('mobile_app.view_listing_per_page', 8));

        return ListingResource::collection($products);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  str $slug item_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function item(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->available()->withCount('feedbacks')->firstOrFail();

        $item->load(['product' => function($q) {
                $q->select('id', 'name', 'slug', 'model_number', 'brand', 'mpn', 'gtin', 'gtin_type', 'description', 'origin_country', 'manufacturer_id', 'created_at')
                ->withCount(['inventories' => function($query) {
                    $query->available();
                }]);
            },
            'attributeValues' => function($q) {
                $q->select('id', 'attribute_values.attribute_id', 'value', 'color', 'order')
                ->with('attribute:id,name,attribute_type_id,order')->orderBy('order');
            },
            'feedbacks.customer:id,nice_name,name',
            'feedbacks.customer.image:path,imageable_id,imageable_type',
            'image:id,path,imageable_id,imageable_type',
        ]);

        $variants = Inventory::select(['id'])
        ->where(['product_id' => $item->product_id, 'shop_id' => $item->shop_id])
        ->with(['images', 'attributes.attributeType', 'attributeValues'])->available()->get();

        $attrs = $variants->pluck('attributes')->flatten(1)->toArray();
        $attrVs = $variants->pluck('attributeValues')->flatten(1)->toArray();

        $tempArr = [];
        foreach ($attrs as $key => $attr) {
            $tempArr[] = [
                'id' => $attr['id'],
                'type' => $attr['attribute_type']['type'],
                'name' => $attr['name'],
                'value' => [
                    'id' => $attrVs[$key]['id'],
                    'name' => $attrVs[$key]['value']
                ],
                'color' => $attrVs[$key]['color'],
            ];
        }

        $uniqueAttrs = array_unique($tempArr, SORT_REGULAR);

        $attributes = [];
        foreach ($uniqueAttrs as $attr) {
           $attributes[$attr['id']]['name'] = $attr['name'];
           $attributes[$attr['id']]['value'][$attr['value']['id']] = $attr['value']['name'];
        }

        // Shipping Zone
        $geoip = geoip(get_visitor_IP()); // Set the location of the user
        $shipping_country_id = get_id_of_model('countries', 'iso_code', $geoip->iso_code);

        return (new ItemResource($item))->additional(['variants' => [
                    'images' => ImageResource::collection($variants->pluck('images')->flatten(1)),
                    'attributes' => $attributes,
                ],
                'shipping_country_id' => $shipping_country_id,
                'shipping_options' => $this->get_shipping_options($item, $shipping_country_id, $geoip->state),
                'countries' => ListHelper::countries(), // Country list for ship_to dropdown
            ]);
    }

    /**
     * Return shipping options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shipTo(Request $request, Inventory $item)
    {
        $shipping_options = $this->get_shipping_options($item, $request->country_id, $request->state_id);

        if (! $shipping_options) {
            return response()->json(['message' => trans('theme.notify.seller_doesnt_ship')], 404);
        }

        return response()->json([
            'shipping_options' => $shipping_options,
        ], 200);
    }

    /**
     * Display variant of an item
     *
     * @param  str $slug item_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function variant(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->available()->firstOrFail();

        $attributes = $request->input('attributes');

        $variants = Inventory::where(['product_id' => $item->product_id, 'shop_id' => $item->shop_id])
        ->with(['attributeValues' => function($q) {
            $q->select('id', 'attribute_values.attribute_id', 'value', 'color');
        }])->available()->get();

        foreach ($variants as $key => $variant) {
            $temp = $variant->attributeValues->pluck('value')->toArray();

            if (! (bool) array_diff($temp, $attributes)) {
                return new ItemLightResource($variant);
            }
        }

        return response()->json(['message' => trans('api.item_not_in_stock')], 404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  str $slug category_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryGroup($slug)
    {
        $categoryGroup = CategoryGroup::where('slug', $slug)->active()->firstOrFail();

        $all_products = prepareFilteredListings(request(), $categoryGroup);

        // Paginate the results
        $listings = $all_products->paginate(config('mobile_app.view_listing_per_page', 8))
        ->appends(request()->except('page'));

        return ListingResource::collection($listings);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  str $slug category_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function categorySubGroup($slug)
    {
        $categorySubGroup = CategorySubGroup::where('slug', $slug)->active()->firstOrFail();

        $all_products = prepareFilteredListings(request(), $categorySubGroup);

        // Paginate the results
        $listings = $all_products->paginate(config('mobile_app.view_listing_per_page', 8))
        ->appends(request()->except('page'));

        return ListingResource::collection($listings);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  str $slug category_slug
     *
     * @return \Illuminate\Http\Response
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();

        // Take only available items
        $all_products = $category->listings()->available();

        // Filter results
        $listings = $all_products->filter(request()->all())
        ->withCount(['feedbacks', 'orders' => function($q) {
            $q->withArchived();
        }])
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type,updated_at', 'image:path,imageable_id,imageable_type'])
        ->paginate(config('mobile_app.view_listing_per_page', 8))
        ->appends(request()->except('page'));

        return ListingResource::collection($listings);
    }

    /**
     * Display a listing of the shop.
     *
     * @param  str $slug shop_slug
     *
     * @return [type]       [description]
     */
    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->active()
        ->withCount(['inventories' => function($q) {
            $q->available();
        }])->firstOrFail();

        // Check shop maintenance_mode
        if ($shop->isDown()) {
            return response()->json(['message' => trans('app.marketplace_down')], 404);
        }

        $listings = Inventory::where('shop_id', $shop->id)->filter(request()->all())
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type,updated_at', 'image:path,imageable_id,imageable_type'])
        ->withCount(['orders' => function($q) {
            $q->withArchived();
        }])
        ->available()->paginate(config('mobile_app.view_listing_per_page', 10));

        // return (new ShopListingResource($shop))->listings(ListingResource::collection($listings));
        return ListingResource::collection($listings);
    }

    /**
     * Open brand page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function brand($slug)
    {
        $brand = Manufacturer::where('slug', $slug)->firstOrFail();

        $ids = Product::where('manufacturer_id', $brand->id)->pluck('id');

        $listings = Inventory::whereIn('product_id', $ids)->filter(request()->all())
        ->whereHas('shop', function($q) {
            $q->select(['id', 'current_billing_plan', 'active'])->active();
        })
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type,updated_at', 'image:path,imageable_id,imageable_type'])
        ->withCount(['orders' => function($q) {
            $q->withArchived();
        }])
        ->active()->paginate(config('mobile_app.view_listing_per_page', 10));

        // return (new ManufacturerResource($brand))->listings(ListingResource::collection($listings));
        return ListingResource::collection($listings);
    }

    /**
     * Return available shipping options for the item
     *
     * @param  item  $item
     * @param  country_id  $country_id
     * @param  state_id  $state
     *
     * @return array|Null
     */
    private function get_shipping_options($item, $country_id, $state)
    {
        $zone = get_shipping_zone_of($item->shop_id, $country_id, $state);

        if (! $zone || ! isset($zone->id)) {
            return Null;
        }

        $free_shipping = [];
        if ($item->free_shipping) {
            $free_shipping[] = getFreeShippingObject($zone);
        }

        $shipping_options = ShippingOptionResource::collection(
            filterShippingOptions($zone->id, $item->current_sale_price(), $item->shipping_weight)
        );

        return empty($free_shipping) ?
                $shipping_options : collect($free_shipping)->merge($shipping_options);
    }
}