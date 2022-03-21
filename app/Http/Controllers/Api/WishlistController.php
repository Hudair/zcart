<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Wishlist;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Http\Requests\Validations\DirectCheckoutRequest;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wishlists = Wishlist::mine()->whereHas('inventory', function($q) {
            $q->available();
        })->with([
            'inventory',
            'inventory.feedbacks:rating,feedbackable_id,feedbackable_type',
            'inventory.image:path,imageable_id,imageable_type'
        ])->paginate(config('mobile_app.view_listing_per_page', 8));

        return WishlistResource::collection($wishlists);
    }

    /**
     * Add item to the wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $slug)
    {
        $item = Inventory::where('slug', $slug)->firstOrFail();

        $customer_id = Auth::guard('api')->user()->id;

        $item_in_wishlist = Wishlist::where('inventory_id', $item->id)
        ->where('customer_id', $customer_id)->first();

        if ($item_in_wishlist) {
            return response()->json(['message' => trans('api.item_alrealy_in_wishlist')], 409); // Item alrealy in cart
        }

        $wishlist = new Wishlist;
        $wishlist->updateOrCreate([
            'inventory_id'   =>  $item->id,
            'product_id'   =>  $item->product_id,
            'customer_id' => $customer_id
        ]);

        return response()->json(['message' => trans('api.item_added_to_wishlist')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request, Wishlist $wishlist)
    {
        $this->authorize('remove', $wishlist);

        $wishlist->forceDelete();

        return response()->json(['message' => trans('api.item_removed_from_wishlist')], 200);
    }
}