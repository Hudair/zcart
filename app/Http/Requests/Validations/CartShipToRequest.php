<?php

namespace App\Http\Requests\Validations;

use Auth;
use App\Http\Requests\Request;

class CartShipToRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if (Auth::guard('api')->check()) {
            $rules = [
                'address_id' =>  'required',
                'country_id' => 'required|integer',
            ];
        }
        else {
            $unique_ck = $this->has('create-account') ? '|unique:customers' : '';

            $rules = [
                'address_title' => 'required',
                'address_line_1' => 'required',
                'zip_code' => 'required',
                'country_id' => 'required|integer',
                'email' =>  'required|email|max:255' . $unique_ck,
                'password' => 'required_with:create-account|confirmed|min:6',
            ];
        }

        return $rules;
    }


   /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return [
    //         'attribute_type_id.required' => trans('validation.attribute_type_id_required'),
    //     ];
    // }
}
