<?php

namespace App\Http\Controllers\Storefront;

use Auth;
use App\Shop;
use App\Cart;
use App\Order;
use App\State;
use App\Coupon;
use App\Country;
use App\Inventory;
use App\Packaging;
use App\ShippingRate;
use App\SystemConfig;
use App\PaymentMethod;
use App\Helpers\ListHelper;
use App\Common\ShoppingCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\DirectCheckoutRequest;

class CartController extends Controller
{
    use ShoppingCart;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $expressId = Null)
    {
        $carts = $this->getShoppingCarts($request);

        // Load related models
        $carts->load([
            'shop' => function($q) {
                $q->with(['config', 'packagings' => function($query) {
                    $query->active();
                }])->active();
            },
            'state:id,name', 'country:id,name', 'inventories.image', 'shippingPackage'
        ]);

        $platformDefaultPackaging = getPlatformDefaultPackaging(); // Get platform's default packaging

        $business_areas = Country::select('id', 'name', 'iso_code')->orderBy('name', 'asc')->get();

        $geoip = geoip(get_visitor_IP());

        $geoip_country = $business_areas->where('iso_code', $geoip->iso_code)->first();

        $geoip_state = State::select('id', 'name', 'iso_code', 'country_id')
        ->where('iso_code', $geoip->state)->where('country_id', $geoip_country->id)->first();

        $shipping_zones = [];
        $shipping_options = [];

        // Prepare shipping info
        foreach ($carts as $cart) {
            $country_id = $cart->ship_to_country_id ?? $geoip_country->id;
            $state_id = $cart->ship_to_state_id ?? optional($geoip_state)->id;

            $shipping_zones[$cart->id] = get_shipping_zone_of($cart->shop_id, $country_id, $state_id);
            $shipping_options[$cart->id] = isset($shipping_zones[$cart->id]->id) ? getShippingRates($shipping_zones[$cart->id]->id) : 'NaN';

            // Update cart if needed
            if (! $cart->ship_to_country_id) {
                $cart->ship_to_country_id = $country_id;
                $cart->ship_to_state_id = $state_id;
                $cart->shipping_zone_id = isset($shipping_zones[$cart->id]->id) ? $shipping_zones[$cart->id]->id : Null;
                if ($shipping_options[$cart->id] != 'NaN') {
                    $cart->shipping_rate_id = $cart->is_free_shipping() ? Null : $shipping_options[$cart->id]->first()->id;
                }
                $cart->save();
            }
        }

        return view('theme::cart', compact('carts', 'business_areas', 'shipping_zones', 'shipping_options','platformDefaultPackaging', 'expressId'));
    }

    /**
     * Add given item to cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->first();

        if (! $item) {
            return response()->json(trans('theme.item_not_available'), 404);
        }

        $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : Null;

        if ($customer_id) {
            $old_cart = Cart::where('shop_id', $item->shop_id)
            ->where(function($query) use ($customer_id) {
                $query->where('customer_id', $customer_id)
                ->orWhere(function($q) {
                    $q->whereNull('customer_id')->where('ip_address', get_visitor_IP());
                });
            })->first();
        }
        else {
            $old_cart = Cart::where('shop_id', $item->shop_id)
            ->whereNull('customer_id')
            ->where('ip_address', get_visitor_IP())->first();
        }

        // Check if the item is alrealy in the cart
        if ($old_cart) {
            $item_in_cart = \DB::table('cart_items')->where('cart_id', $old_cart->id)
            ->where('inventory_id', $item->id)->first();

            if ($item_in_cart) {
                return response()->json(['cart_id' => $item_in_cart->cart_id], 444);  // Item alrealy in cart
            }
        }

        $qtt = $request->quantity ?? $item->min_order_quantity;
        $unit_price = $item->current_sale_price();

        // Instantiate new cart if old cart not found for the shop and customer
        $cart = $old_cart ?? new Cart;
        $cart->shop_id = $item->shop_id;
        $cart->customer_id = $customer_id;
        $cart->ip_address = get_visitor_IP();
        $cart->shipping_zone_id = $request->shippingZoneId;
        $cart->item_count = $old_cart ? ($old_cart->item_count + 1) : 1;
        $cart->quantity = $old_cart ? ($old_cart->quantity + $qtt) : $qtt;

        if ($request->shipTo) {
            $cart->ship_to = $request->shipTo;
        }

        if ($request->shipToCountryId) {
            $cart->ship_to_country_id = $request->shipToCountryId;
        }

        if ($request->shipToStateId) {
            $cart->ship_to_state_id = $request->shipToStateId;
        }

        // Reset if the old cart exist, bcoz shipping rate may change after adding new item
        $cart->shipping_rate_id = $old_cart ? Null : $request->shippingRateId == 'Null' ? Null : $request->shippingRateId;

        $cart->handling = $old_cart ? $old_cart->handling : getShopConfig($item->shop_id, 'order_handling_cost');
        $cart->total = $old_cart ? ($old_cart->total + ($qtt * $unit_price)) : ($qtt * $unit_price);
        // $cart->packaging_id = $old_cart ? $old_cart->packaging_id : 1;

        // All items need to have shipping_weight to calculate shipping
        // If any one the item missing shipping_weight set null to cart shipping_weight
        if ($item->shipping_weight == Null || ($old_cart && $old_cart->shipping_weight == Null)) {
            $cart->shipping_weight = Null;
        }
        else {
            $cart->shipping_weight = $old_cart ? ($old_cart->shipping_weight + $item->shipping_weight) : $item->shipping_weight;
        }

        // Set taxes
        if ($cart->shipping_zone_id) {
            $cart->taxrate = optional($cart->shippingZone->tax)->taxrate;
            $cart->taxes = $cart->get_tax_amount();
        }

        $cart->grand_total = $cart->calculate_grand_total();

        $cart->save();

        // Makes item_description field
        $attributes = implode(' - ', $item->attributeValues->pluck('value')->toArray());
        // Prepare pivot data
        $cart_item_pivot_data = [];
        $cart_item_pivot_data[$item->id] = [
            'inventory_id' => $item->id,
            'item_description'=> $item->sku . ': ' . $item->title . ' - ' . $attributes . ' - ' . $item->condition,
            'quantity' => $qtt,
            'unit_price' => $unit_price,
        ];

        // Save cart items into pivot
        if (! empty($cart_item_pivot_data)) {
            $cart->inventories()->syncWithoutDetaching($cart_item_pivot_data);
        }

        return response()->json($cart->toArray(), 200);
    }

    /**
     * Checkout the specified cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, Cart $cart)
    {
        if (! crosscheckCartOwnership($request, $cart)) {
            return redirect()->route('cart.index')->with('warning', trans('theme.notify.please_login_to_checkout'));
        }

        $cart = crosscheckAndUpdateOldCartInfo($request, $cart);

        $shop = Shop::where('id', $cart->shop_id)->active()->with('config')->first();

        // Abort if the shop is not exist or inactive
        abort_unless($shop, 406, trans('theme.notify.store_not_available'));

        if (vendor_get_paid_directly()) {
            $shop->load(['paymentMethods' => function($q) {
                $q->active();
            }]);

            $paymentMethods = $shop->paymentMethods;
            if (! $paymentMethods) {
                return redirect()->route('cart.index')->with('warning', trans('theme.notify.seller_has_no_payment_method'));
            }
        }
        else {
            $paymentMethods = PaymentMethod::active()->get();
        }

        $customer = Auth::guard('customer')->check() ? Auth::guard('customer')->user() : Null;
        $business_areas = Country::select('id', 'name', 'iso_code')->orderBy('name', 'asc')->get();
        $states = $cart->ship_to_state_id ? ListHelper::states($cart->ship_to_country_id) : []; // Sate list of the country for ship_to dropdown
        $platformDefaultPackaging = getPlatformDefaultPackaging(); // Get platform's default packaging

        $geoip = geoip(get_visitor_IP());

        $geoip_country = $business_areas->where('iso_code', $geoip->iso_code)->first();

        $geoip_state = State::select('id', 'name', 'iso_code', 'country_id')
        ->where('iso_code', $geoip->state)->where('country_id', $geoip_country->id)->first();

        $country_id = $cart->ship_to_country_id ?? $geoip_country->id;
        $state_id = $cart->ship_to_state_id ?? optional($geoip_state)->id;

        $shipping_zones[$cart->id] = get_shipping_zone_of($cart->shop_id, $country_id, $state_id);
        $shipping_options[$cart->id] = isset($shipping_zones[$cart->id]->id) ? getShippingRates($shipping_zones[$cart->id]->id) : 'NaN';

        return view('theme::checkout', compact('cart', 'customer', 'shop', 'business_areas', 'shipping_zones', 'shipping_options', 'states', 'paymentMethods', 'platformDefaultPackaging'));
    }

    /**
     * Direct checkout with the item/cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  str $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function directCheckout(DirectCheckoutRequest $request, $slug)
    {
        $cart = $this->addToCart($request, $slug);

        if (200 == $cart->status()) {
            return redirect()->route('cart.index', $cart->getdata()->id);
        }
        else if (444 == $cart->status()) {
            return redirect()->route('cart.index', $cart->getdata()->cart_id);
        }

        return redirect()->back()->with('warning', trans('theme.notify.failed'));
    }

    /**
     * Update the cart and redirected to checkout page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart    $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        if (! crosscheckCartOwnership($request, $cart)) {
            return response(trans('theme.notify.please_login_to_checkout'), 401);
        }

        $cart = crosscheckAndUpdateOldCartInfo($request, $cart);

        return response(trans('theme.notify.cart_updated'), 200);
    }

    /**
     * Remove item from cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $cart = Cart::findOrFail($request->cart);

        $item = \DB::table('cart_items')->where([
            'cart_id' => $request->cart,
            'inventory_id' => $request->item
        ])->delete();

        // Delete item from cart_items table
        if ($item) {
            // Update or delate cart
            if ($item_count = $cart->inventories->count()) {
                $cart->fill([
                    'quantity' => $cart->inventories->sum('quantity'),
                    'item_count' => $item_count,
                ])->save();
            }
            else {
                $cart->forceDelete();
            }

            return response('Item removed', 200);
        }

        return response('Item remove failed!', 404);
    }

    /**
     * validate coupon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateCoupon(Request $request)
    {
        $coupon = Coupon::active()->where([
            ['code', $request->coupon],
            ['shop_id', $request->shop],
        ])->withCount(['orders','customerOrders'])->first();

        if (! $coupon) {
            return response('Coupon not found', 404);
        }

        if (! $coupon->isLive() || ! $coupon->isValidCustomer()) {
            return response('Coupon not valid', 403);
        }

        if (! $coupon->isValidZone($request->zone)) {
            return response('Coupon not valid for shipping area', 443);
        }

        if (! $coupon->hasQtt()) {
            return response('Coupon qtt limit exit', 444);
        }

        return response()->json($coupon->toArray());
    }
}
