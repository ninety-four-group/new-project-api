<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
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
            'title' =>  ['sometimes','string','max:255'],
            'caption' =>  ['sometimes','string','max:255'],
            'alt_text' =>  ['sometimes','string','max:255'],
            'description' =>  ['sometimes','string','max:255'],
            'file' =>  ['sometimes','image','mimes:jpg,jpeg,png,svg,gif','max:20000'], //20 MB
        ];
    }
}
