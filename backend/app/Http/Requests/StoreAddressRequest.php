<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'etiqueta'       => ['nullable','string'],
            'adreca'         => ['required', 'string'],
            'codi_postal'    => ['required', 'string'],
            'ciutat'         => ['required', 'string'],
        ];
    }
}