<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codi'        => ['required', 'string', 'unique:codis_descompte,codi'],
            'percentatge' => ['required', 'numeric', 'min:0', 'max:100'],
            'actiu'       => ['nullable', 'boolean'],
            'expires_at'  => ['required', 'date', 'after:now'],
        ];
    }
}