<?php

namespace App\Http\Requests\Validations;

use Auth;
use App\Http\Requests\Request;

class ContactSellerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('customer')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|max:200',
            'message' => 'required|max:500',
            'g-recaptcha-response' => 'required|recaptcha'
        ];
    }

}
