<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Order;
use App\Reply;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderLightResource;
use App\Http\Resources\ConversationResource;
use App\Http\Requests\Validations\OrderDetailRequest;
use App\Http\Requests\Validations\DirectCheckoutRequest;
// use App\Http\Requests\Validations\DirectCheckoutRequest;
use App\Http\Requests\Validations\ConfirmGoodsReceivedRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Auth::guard('api')->user()->orders()
        ->with([
            'shop:id,name,slug',
            'inventories:id,title,slug,product_id',
            'inventories.image:path,imageable_id,imageable_type',
            'dispute:id,order_id'
        ])
        ->paginate(config('mobile_app.view_listing_per_page', 8));

        return OrderLightResource::collection($orders);
    }

    /**
     * Display order detail page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function show(OrderDetailRequest $request, Order $order)
    {
        $order->load([
            'conversation:id,order_id,user_id,customer_id,subject,message,product_id,status,updated_at',
            'conversation.attachments',
            'feedback'
        ]);

        return new OrderResource($order);
    }


    /**
     * Display order conversation page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function conversation(OrderDetailRequest $request, Order $order)
    {
        $order->load(['shop:id,name,slug','conversation.replies','conversation.replies.attachments']);

        if (! $order->conversation) {
            return response()->json(['message' => trans('responses.resource_not_found')], 404);
        }

        return new ConversationResource($order->conversation);
    }

    /**
     * Start/Replay a order conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function save_conversation(OrderDetailRequest $request, Order $order)
    {
        $user_id = Auth::user()->id;

        if ($order->conversation) {
            $msg = new Reply;
            $msg->reply = $request->input('message');

            if (Auth::guard('api')->check()) {
                $msg->customer_id = $user_id;
            }
            else {
                $msg->user_id = $user_id;
            }

            $order->conversation->replies()->save($msg);
        }
        else {
            $msg = new Message;
            $msg->message = $request->input('message');
            $msg->shop_id = $order->shop_id;

            if (Auth::guard('api')->check()) {
                $msg->subject = trans('theme.defaults.new_message_from', ['sender' => Auth::user()->getName()]);
                $msg->customer_id = $user_id;
            }
            else {
                $msg->user_id = $user_id;
            }

            $order->conversation()->save($msg);
        }

        // Update the order if goods_received
        if ($request->has('goods_received')) {
            $order->goods_received();
        }

        if ($request->has('photo')) {
            //$msg->saveAttachments($request->file('photo'));
            $photo = create_file_from_base64($request->get('photo'));
            $msg->saveAttachments($photo);
        }

        return new ConversationResource($order->conversation);
    }

    /**
     * Buyer confirmed goods received
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function goods_received(ConfirmGoodsReceivedRequest $request, Order $order)
    {
        $order->mark_as_goods_received();

        return new OrderResource($order);
    }

    /**
     * Track order shippping.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order   $order
     *
     * @return \Illuminate\Http\Response
     */
    public function track(Request $request, Order $order)
    {
        $url = $order->getTrackingUrl();

        // if ( ! $url )
        //     $url = ;

        return response()->json(['tracking_url' => $url], 200);
    }
}