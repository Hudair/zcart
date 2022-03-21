<?php

namespace App\Services\Payments;

use Auth;
use App\Order;
use Illuminate\Http\Request;
use App\Contracts\PaymentServiceContract;

class PaymentService implements PaymentServiceContract
{
	public $request;
	public $payee;
	public $receiver;
	public $order;
	public $fee;
	public $amount;
	public $meta;
	public $description;
	public $sandbox;
	public $success;

   public function __construct(Request $request)
	{
		$this->request = $request;

        // Get payee model
        if ($this->request->has('payee')) {
	        $this->setPayee($this->request->payee);
        }
        else if (Auth::guard('customer')->check()) {
        	$this->setPayee(Auth::guard('customer')->user());
        }
        else if (Auth::guard('web')->check() && Auth::user()->isMerchant()) {
            $this->setPayee(Auth::user()->owns);
        }
	}

	/**
	 * Set the payee
	 * return $this
	 */
	public function setPayee($payee)
	{
		$this->payee = $payee;

		return $this;
	}

	/**
	 * Set the payable amount
	 * return $this
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	/**
	 * Set the description
	 * return $this
	 */
	public function setDescription($description = '')
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Set the receiver
	 * return $this
	 */
	public function setReceiver($receiver = 'platform')
	{
		$this->receiver = $receiver;

		return $this;
	}

	/**
	 * Set order info if any
	 * return $this
	 */
	public function setOrderInfo($order)
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * Return order id or the last order id
	 * return $this
	 */
	public function getOrderId()
	{
		if ($this->order) {
			if (is_array($this->order)) {
				return implode('-', array_column($this->order, 'id'));
			}

	        if (! $this->order instanceOf Order) {
	            $this->order = Order::findOrFail($this->order);
	        }

			return $this->order->id;
		}

		return Null;
	}

	/**
	 * Set payment gate configs
	 * Overwite on child class
	 */
	public function setConfig()
	{
		return $this;
	}

	/**
	 * The payment will execute here, overwite on child class
	 */
    public function charge()
    {
        $this->success = TRUE;

    	return $this;
    }
}