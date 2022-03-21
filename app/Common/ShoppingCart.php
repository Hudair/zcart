<?php

namespace App\Common;

use Auth;
use Session;
use App\Cart;
use App\Order;
use App\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Addresses
 *
 * @author Munna Khan
 */
trait ShoppingCart
{
   /**
     * Get all carts of a user.
     *
     * @return App\Cart
     */
     private function getShoppingCarts(Request $request)
    {
        $carts = Cart::whereNull('customer_id')->where('ip_address', get_visitor_IP());

        if (Auth::guard('customer')->check()) {
            $carts = $carts->orWhere('customer_id', Auth::guard('customer')->user()->id);
        }

        return $carts->with('shop:id,slug,name','shop.logo:path,imageable_id,imageable_type')->get();
    }

    /**
     * Create a new order from the cart
     *
     * @param  Request $request
     * @param  App\Cart $cart
     *
     * @return App\Order
     */
    private function saveOrderFromCart(Request $request, Cart $cart)
    {
        // Set shipping_rate_id and handling cost to NULL if its free shipping
        // if ($cart->is_free_shipping()) {
        //     $cart->shipping_rate_id = Null;
        //     $cart->handling = Null;
        // }

        // Save the order
        $order = new Order;
        $order->fill(
            array_merge($cart->toArray(), [
                'customer_id' => $cart->customer_id,
                'payment_method_id' => $request->payment_method_id ?? $cart->payment_method_id,
                'grand_total' => $cart->calculate_grand_total(),
                'order_number' => get_formated_order_number($cart->shop_id),
                'carrier_id' => $cart->carrier() ? $cart->carrier->id : NULL,
                'shipping_address' => $request->shipping_address ?? $cart->shipping_address,
                'billing_address' => $request->shipping_address ?? $cart->shipping_address,
                'email' => $request->email ?? $cart->email,
                'buyer_note' => $request->buyer_note,
                'device_id' => $request->device_id ?? $cart->device_id,
            ])
        )->save();

        if ($request->hasFile('prescription')) {
            $order->saveAttachments($request->file('prescription'));
        }

        // This has to be after save the order
        if ($payment_instruction = $order->menualPaymentInstructions()) {
            $order->forceFill(['payment_instruction' => $payment_instruction])->save();
        }

        // Add order item into pivot table
        $cart_items = $cart->inventories->pluck('pivot');
        $order_items = [];
        foreach ($cart_items as $item) {
            $order_items[] = [
                'order_id'          => $order->id,
                'inventory_id'      => $item->inventory_id,
                'item_description'  => $item->item_description,
                'quantity'          => $item->quantity,
                'unit_price'        => $item->unit_price,
                'created_at'        => $item->created_at,
                'updated_at'        => $item->updated_at,
            ];
        }

        \DB::table('order_items')->insert($order_items);

         // Sync up the inventory. Decrease the stock of the order items from the listing
        foreach ($order->inventories as $item) {
            $item->decrement('stock_quantity', $item->pivot->quantity);
        }

        // Reduce the coupone in use
        if ($order->coupon_id) {
            Coupon::find($order->coupon_id)->decrement('quantity');
        }

        // Delete the cart
        $cart->forceDelete();

        return $order;
    }

    /**
     * Revert order to cart
     *
     * @param  App\Order $Order
     *
     * @return App\Cart
     */
    private function moveAllItemsToCartAgain($order, $revert = false)
    {
        if (! $order instanceOf Order ) {
            $order = Order::find($order);
        }

        if (! $order) return;

        // Set time
        $now = Carbon::now();

        // Save the cart
        $cart = Cart::create([
                    'shop_id' => $order->shop_id,
                    'customer_id' => $order->customer_id,
                    'ship_to' => $order->ship_to,
                    'shipping_zone_id' => $order->shipping_zone_id,
                    'shipping_rate_id' => $order->shipping_rate_id,
                    // 'ship_to_country_id' => $order->ship_to_country_id,
                    // 'ship_to_state_id' => $order->ship_to_state_id,
                    'packaging_id' => $order->packaging_id,
                    'item_count' => $order->item_count,
                    'quantity' => $order->quantity,
                    'taxrate' => $order->taxrate,
                    'shipping_weight' => $order->shipping_weight,
                    'total' => $order->total,
                    'shipping' => $order->shipping,
                    'packaging' => $order->packaging,
                    'handling' => $order->handling,
                    'taxes' => $order->taxes,
                    'grand_total' => $order->grand_total,
                    'email' => $order->email,
                    'ip_address' => get_visitor_IP(),
                    'created_at' => $revert ? $order->created_at : $now,
                    'updated_at' => $revert ? $order->updated_at : $now,
                ]);

        // Add order item into cart pivot table
        $cart_items = [];
        $quantity = 0;
        $shipping_weight = 0;
        $total = 0;
        $grand_total = 0;

        foreach ($order->inventories as $item) {
            // Skip if the item is out of stock
            if (! $item->stock_quantity > 0) {
                Session::flash('warning', trans('messages.some_item_out_of_stock'));
                continue;
            }

            // Get current updated price
            $unit_price = $item->current_sale_price();

            // Set qtt after checking availablity
            $item_qtt = $item->stock_quantity >= $item->pivot->quantity ?
                        $item->pivot->quantity : $item->stock_quantity;

            $shipping_weight += $item->shipping_weight;
            $quantity += $item_qtt;
            $total += $item_qtt * $unit_price;

            $cart_items[] = [
                'cart_id'           => $cart->id,
                'inventory_id'      => $item->pivot->inventory_id,
                'item_description'  => $item->pivot->item_description,
                'quantity'          => $item_qtt,
                'unit_price'        => $unit_price,
                'created_at'        => $revert ? $item->pivot->created_at : $now,
                'updated_at'        => $revert ? $item->pivot->created_at : $now,
            ];

            // Sync up the inventory. Increase the stock of the order items from the listing
            if ($revert) {
                $item->increment('stock_quantity', $item->pivot->quantity);
            }
        }

        \DB::table('cart_items')->insert($cart_items);

        if ($revert) {
            // Increment the coupone in use
            if ($order->coupon_id) {
                Coupon::find($order->coupon_id)->increment('quantity');
            }

            $order->forceDelete();   // Delete the order
        }

        // Update cart
        $cart->quantity = $quantity;
        $cart->shipping_weight = $shipping_weight;
        $cart->total = $total;
        $cart->grand_total = $cart->calculate_grand_total();
        $cart->updated_at = $cart->updated_at;
        $cart->taxes = $cart->get_tax_amount();
        $cart->save();

        return $cart;
    }

}