<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status' => 'required|string',
            'dishes' => 'required|array',
            'dishes.*' => 'required|array',
            'dishes.*.id' => [
                'integer',
                Rule::exists('dishes')->where(function ($query) {
                   return $query->whereNull('deleted_at');
                }),
            ],
            'dishes.*.count' => 'integer',
        ];
    }
}
