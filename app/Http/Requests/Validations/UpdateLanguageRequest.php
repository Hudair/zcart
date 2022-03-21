<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class UpdateLanguageRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = Request::segment(count(Request::segments())); //Current model ID

        return [
           'language' => 'required',
           'code' => 'required|max:3|unique:languages,code,' . $id,
           'php_locale_code' => 'required|unique:languages,php_locale_code,' . $id,
           'active' => 'required',
           'order' => 'nullable|numeric'
        ];
    }
}
