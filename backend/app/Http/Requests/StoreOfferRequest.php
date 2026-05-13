<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preu_rebaixat'  => ['required', 'numeric', 'min:0'],
            'data_inici'     => ['required', 'date'],
            'data_fi'        => ['required', 'date', 'after:data_inici'],
            'producte_ids'   => ['required', 'array', 'min:1'],
            'producte_ids.*' => ['required', 'integer', 'exists:productes,id'],
        ];
    }
}