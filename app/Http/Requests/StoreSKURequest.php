<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSKURequest extends FormRequest
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
            'variation_id' => ['required','string','max:255'],
            'warehouse_id' => ['required','string','max:255'],
            'product_id' => ['required','string','max:255'],
            'quantity' => ['required','integer'],
            'price' => ['required'],
        ];
    }
}