<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'phone' => 'required|string',
            'dishes' => 'required|array',
            'dishes.*.id' => 'integer|exists:dishes,id',
            'dishes.*.count' => 'integer',
        ];
    }
}
