<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
        return [
            'name'=>'string',
            'price'=>'integer|min:1|max:1000000',
            'brand'=>'integer|min:1',
            'category'=>'integer|min:1',
            'tags'=>'string',
            'main_image'=>'string',
            'images'=>'array',
        ];
    }
}
