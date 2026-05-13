<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codi'        => ['sometimes', 'string', 'unique:codis_descompte,codi,' . $this->discountCode->id],
            'percentatge' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'actiu'       => ['sometimes', 'boolean'],
            'expires_at'  => ['sometimes', 'date', 'after:now'],
        ];
    }
}