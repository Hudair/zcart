<?php

namespace App\Http\Controllers\Storefront;

use DB;
use Auth;
use Session;
use App\Cart;
use App\Order;
use App\Address;
use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Events\Order\OrderCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Validations\OrderDetailRequest;
use App\Http\Requests\Validations\CheckoutCartRequest;
use App\Http\Requests\Validations\ConfirmGoodsReceivedRequest;

use App\Contracts\PaymentServiceContract as PaymentService;
use App\Services\Payments\PaypalExpressPaymentService;

use App\Common\ShoppingCart;

class OrderController extends Controller
{
    use ShoppingCart;

    /**
     * Checkout the specified cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CheckoutCartRequest $request, Cart $cart, PaymentService $paymentService)
    {
        $cart = crosscheckAndUpdateOldCartInfo($request, $cart);

        // Start transaction!
        DB::beginTransaction();

        try {
            // Create the order
            $order = $this->saveOrderFromCart($request, $cart);

            $receiver = vendor_get_paid_directly() ? 'merchant' : 'platform';

            $response = $paymentService->setReceiver($receiver)
                ->setOrderInfo($order)
                ->setAmount($order->grand_total)
                ->setDescription(trans('app.purchase_from', ['marketplace' => get_platform_title()]))
                ->setConfig()
                ->charge();

            // Check if the result is a RedirectResponse of Paypal and some other gateways
            if ($response instanceof RedirectResponse) {
                // Everything is fine. Now commit the transaction
                DB::commit();

                // Delete the cart
                // $cart->forceDelete();

                return $response;
            }
            // Payment succeed
            else if ($response->success) {
                // Order confirmed
                if ($order->paymentMethod->type !== PaymentMethod::TYPE_MANUAL) {
                    // Order has been paid
                    $order->markAsPaid();
                }

                // Everything is fine. Now commit the transaction
                DB::commit();

                // Delete the cart
                // $cart->forceDelete();

                // Trigger the Event
                event(new OrderCreated($order));

                return view('theme::order_complete', compact('order'))->with('success', trans('theme.notify.order_placed'));
            }

            throw new \Exception("Error Manual Payment Processing Request");
        }
        catch (\Exception $e) {
            DB::rollback(); // rollback the transaction and log the error

            \Log::error($request->payment_method . ' Payment failed:: '.$e->getMessage());
            \Log::error($e);

            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Return from payment gateways with payment success
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Order $order
     * @param  str $gateway Payment gateway code
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentGatewaySuccessResponse(Request $request, $gateway, $order)
    {
        // Verify Payment Gateway Calls
        if (! $this->verifyPaymentGatewayCalls($request, $gateway)) {
            return redirect()->route("payment.failed", $order);
        }

        if ($gateway == 'paypal-express') {
            try {
                $paymentService = new PaypalExpressPaymentService($request);

                $result = $paymentService->paymentExecution($request->get('paymentId'), $request->get('PayerID'));

                if (! $result->success) {
                    return redirect()->route("payment.failed", $order);
                }
            }
            catch (\Exception $e) {
                \Log::error('Paypal payment failed on execution step:: ');
                \Log::info($e->getMessage());
            }
        }

        // Order has been paid

        // OneCheckout plugin
        $orders = explode('-', $order);
        $order = count($orders) > 1 ? $orders : $order;
        if (is_array($order)) {
            foreach($order as $id) {
                $temp = Order::findOrFail($id);

                $temp->markAsPaid();
            }

            $order = $temp;

            return view('theme::order_complete', compact('order'))->with('success', trans('theme.notify.order_placed'));
        }

        // Single order
        if (! $order instanceOf Order) {
            $order = Order::findOrFail($order);
        }

        $order->markAsPaid();

        // Trigger the Event
        event(new OrderCreated($order));

        return view('theme::order_complete', compact('order'))->with('success', trans('theme.notify.order_placed'));
    }

    /**
     * Payment failed or cancelled
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentFailed(Request $request, $order)
    {
        if (! is_array($order)) {
            $orders = explode('-', $order);
            $order = count($orders) > 1 ? $orders : $order;
        }

        if (is_array($order)) {
            $cart = [];
            foreach ($order as $temp) {
                $cart[] = $this->moveAllItemsToCartAgain($temp, true);
            }
        }
        else {
            $cart = $this->moveAllItemsToCartAgain($order, true);
        }

        if (is_array($cart)) {
            return redirect()->route('cart.index')->with('error', trans('theme.notify.payment_failed'));
            // return redirect()->route('oneCheckout')->with('error', trans('theme.notify.payment_failed'));
        }

        return redirect()->route('cart.checkout', $cart)->with('error', trans('theme.notify.payment_failed'));
    }

    /**
     * Display order detail page.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(OrderDetailRequest $request, Order $order)
    {
        $order->load(['inventories.image','conversation.replies.attachments']);

        return view('theme::order_detail', compact('order'));
    }

    /**
     * Buyer confirmed goods received
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function goods_received(ConfirmGoodsReceivedRequest $request, Order $order)
    {
        $order->mark_as_goods_received();

        return redirect()->route('order.feedback', $order)->with('success', trans('theme.notify.order_updated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Order   $order
     * @return \Illuminate\Http\Response
     */
    public function invoice(Order $order)
    {
        // $this->authorize('view', $order); // Check permission

        $order->invoice('D'); // Download the invoice
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
        return view('theme::order_tracking', compact('order'));
    }

    /**
     * Order again by moving all items into th cart
     */
    public function again(Request $request, Order $order)
    {
        $cart = $this->moveAllItemsToCartAgain($order);

        // If any waring returns from cart, normally out of stock items
        if (Session::has('warning')) {
            return redirect()->route('cart.index');
        }

        return redirect()->route('cart.checkout', $cart)->with('success', trans('theme.notify.cart_updated'));
    }

    /**
     * Verify Payment Gateway Calls
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Str  $gateway
     *
     * @return bool
     */
    private function verifyPaymentGatewayCalls(Request $request, $gateway)
    {
        switch ($gateway) {
            case 'paypal-express':
                return $request->has('token') && $request->has('paymentId') && $request->has('PayerID');

            case 'instamojo':
                return $request->payment_status == 'Credit' && $request->has('payment_request_id') && $request->has('payment_id');

            case 'paystack':
                return $request->has('trxref') && $request->has('reference');
        }

        return false;
    }

    private function logErrors($error, $feedback)
    {
        \Log::error($error);

        // Set error messages:
        // $error = new \Illuminate\Support\MessageBag();
        // $error->add('errors', $feedback);

        return $error;
    }
}