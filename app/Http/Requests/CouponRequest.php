<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            "code"=>"required|max:8",
            "nominal_value"=>"required",
            "currency"=>"required",
            "is_only_once"=>"boolean",
            "is_absolute"=>"boolean",
            "expired_at"=>"required|date",
            "description"=>"string",
        ];
    }
}
