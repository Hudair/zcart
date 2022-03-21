<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;

class StripePaymentService extends PaymentService
{
	public $stripe_account_id;
	public $token;
	public $fee;
	public $meta;

    public function __construct(Request $request)
	{
		parent::__construct($request);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
	}

	public function setConfig()
	{
		$this->setStripAccountId();

		$this->setStripeToken();

		return $this;
	}

    public function charge()
    {
    	$data = [
            'amount' => get_cent_from_doller($this->amount),
            'currency' => get_currency_code(),
            'description' => $this->description,
            'metadata' => $this->meta,
        ];

		if ($this->chargeSavedCustomer()) {
            $data['customer'] = $this->payee->stripe_id;
		}
		else {
            $data['source'] = $this->token;
		}

        // Set application fee if merchant get paid
        if ($this->receiver == 'merchant' && $this->order && $this->payee instanceof \App\Customer) {

        	// Set platform fee for order if not already set
        	if (! $this->fee) {
				 $this->setPlatformFee(getPlatformFeeForOrder($this->order));
        	}

        	if ($this->fee) {
	            $data['application_fee'] = $this->fee;
        	}
        }

        $result = \Stripe\Charge::create($data, [
            "stripe_account" => $this->stripe_account_id
        ]);

        if ($result->status == 'succeeded') {
            $this->success = TRUE;
        }

		return $this;
    }

	public function setPlatformFee($fee = 0)
	{
		$this->fee = $fee > 0 ? get_cent_from_doller($fee) : 0;

		return $this;
	}

    private function setStripeToken()
    {
		if ($this->receiver == 'merchant' && $this->chargeSavedCustomer()) {
            $newToken = \Stripe\Token::create([
              "customer" => $this->payee->stripe_id,
            ], ["stripe_account" => $this->stripe_account_id]);
		}

    	$this->token = isset($newToken) ? $newToken->id : $this->request->cc_token;
    }

	public function setOrderInfo($order)
	{
		$this->order = $order;

		// If multiple orders take the info from last one
		if (is_array($this->order)) {
			$order = reset($order);
		}

		$this->meta = [
            'order_number' => $order->order_number,
            'shipping_address' => strip_tags($order->shipping_address),
            'buyer_note' => $order->buyer_note
		];

		return $this;
	}

    private function setStripAccountId()
    {
        $this->stripe_account_id = $this->order && $this->receiver == 'merchant' ?
        					$this->order->shop->config->stripe->stripe_user_id :
				            config('services.stripe.account_id');
    }

    private function chargeSavedCustomer()
    {
		return $this->payee && $this->payee->hasBillingToken() &&
				$this->request->has('remember_the_card') ||
				$this->request->payment_method == 'saved_card';
    }

}
