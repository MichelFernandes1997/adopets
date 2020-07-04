<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(\Auth::user() !== null)
            return true;
        else
            false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required | string',
            'description' => 'required | max:500 | string',
            'category' => 'required | string',
            'price' => 'required | numeric',
            'stock' => 'required | integer',
        ];
    }
}
