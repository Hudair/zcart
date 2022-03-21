<?php

namespace App\Http\Requests\Validations;

use Auth;
use App\Http\Requests\Request;

class ApiCheckoutCartRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Skip the checking here and do it on the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'shipping_address'  =>  'required',
            'shipping_option_id'=>  'present',
            'payment_method_id' =>  'required',
            'shipping_address'  =>  'required',
            'buyer_note'        =>  'nullable|max:500',
        ];

        if (Auth::guard('api')->check()) {
            $rules['address_id'] = 'required';
        }

        if ('saved_card' != $this->payment_method_id) {
            $rules['payment_method_id'] = ['required', 'exists:payment_methods,id,enabled,1'];
        }

        return $rules;
    }
}
