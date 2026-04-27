<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderREquest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_lines' => ['required', 'array', 'min:1'],
            'order_lines.*.producte_id' => ['required', 'exists:productes,id'],
            'order_lines.*.quantitat' => ['required', 'integer', 'min:1'],
        ];
    }
}