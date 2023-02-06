<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['sometimes','string','max:255'],
            'variation_category_id' => ['sometimes','string','max:255'],
            'image' => ['sometimes','image','mimes:png,jpg,jpeg','max:10240'] //max : 10MB
        ];
    }
}