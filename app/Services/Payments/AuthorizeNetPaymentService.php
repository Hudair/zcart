<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;
use App\Exceptions\PaymentFailedException;
use net\authorize\api\contract\v1 as AuthorizeNetAPI;
use net\authorize\api\controller as AuthorizeNetController;
use net\authorize\api\constants\ANetEnvironment;

class AuthorizeNetPaymentService extends PaymentService
{
	public $refId;
	public $apiRequest;

    public function __construct(Request $request)
	{
		parent::__construct($request);

	    $this->refId = 'ref'.time();
	}

	/**
	 * Set payment gateway config
	 */
	public function setConfig()
	{
    	if ($this->receiver == 'merchant') {
		    // Get the vendor configs
		    $vendorConfig = $this->order->shop->config->authorizeNet;

    		$api_login_id = $vendorConfig->api_login_id;
    		$transaction_key = $vendorConfig->transaction_key;
    		$this->sandbox = $vendorConfig->sandbox == 1;
    	}
    	else {
    		$api_login_id = config('services.authorize-net.api_login_id');
    		$transaction_key = config('services.authorize-net.transaction_key');
    		$this->sandbox = config('services.authorize-net.sandbox');
    	}

	    // Common setup for API credentials
	    $merchantAuthentication = new AuthorizeNetAPI\MerchantAuthenticationType();
	    $merchantAuthentication->setName($api_login_id);
	    $merchantAuthentication->setTransactionKey($transaction_key);

	    // Create the payment data for a credit card
	    $creditCard = new AuthorizeNetAPI\CreditCardType();
	    $creditCard->setCardNumber($this->request->cnumber);
	    // $creditCard->setExpirationDate( "2038-12");
	    $expiry = $this->request->card_expiry_year . '-' . $this->request->card_expiry_month;
	    $creditCard->setExpirationDate($expiry);

	    $paymentOne = new AuthorizeNetAPI\PaymentType();
	    $paymentOne->setCreditCard($creditCard);

	    // Create a transaction
	    $transactionRequestType = new AuthorizeNetAPI\TransactionRequestType();
	    $transactionRequestType->setTransactionType("authCaptureTransaction");
	    $transactionRequestType->setAmount($this->amount);
	    $transactionRequestType->setPayment($paymentOne);

	    $ApiRequest = new AuthorizeNetAPI\CreateTransactionRequest();
	    $ApiRequest->setMerchantAuthentication($merchantAuthentication);
	    $ApiRequest->setRefId($this->refId);
	    $ApiRequest->setTransactionRequest($transactionRequestType);

	    $this->apiRequest = $ApiRequest;

	    return $this;
    }

	/**
	 * Execute payment
	 */
    public function charge()
    {
    	// try {
		    $controller = new AuthorizeNetController\CreateTransactionController($this->apiRequest);
		    $response = $controller->executeWithApiResponse($this->sandbox ? ANetEnvironment::SANDBOX : ANetEnvironment::PRODUCTION);

	        if ($response != null) {
	            $tresponse = $response->getTransactionResponse();
	            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) { // Approved
	                \Log::info("AuthorizeNet Charge Success: " .  json_encode([
	                	'AUTH CODE' => $tresponse->getAuthCode(),
	                	'TRANS ID' => $tresponse->getTransId()
	                ]));

	                $this->success = TRUE;

	                return $this;
	            }
	        }

    	// } catch (Exception $e) {
	        \Log::error("Authorize Net Exception:: " . json_encode($response));

	        throw new PaymentFailedException(trans('theme.notify.payment_failed'));
    	// }

        return $response;
    }
}
