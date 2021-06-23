<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateProductFormRequest extends FormRequest
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
            'name' => "required|min:3|unique:products,name,{$this->segment(3)}",
            'category_id' => "required|exists:categories,id",
            'image' => 'image|nullable',
            'description' => 'max:1000',
        ];
    }
}
