<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'metode'          => ['required', 'string', 'in:targeta,efectiu'],
            'codi_descompte'  => ['nullable', 'string', 'exists:codis_descompte,codi'],
            'adreca_id'       => ['nullable', 'integer', 'exists:adreces,id'],
            'adreca'          => ['nullable', 'array'],
            'adreca.adreca'   => ['required_with:adreca', 'string'],
            'adreca.codi_postal' => ['required_with:adreca', 'string'],
            'adreca.ciutat'   => ['required_with:adreca', 'string'],
            'adreca.etiqueta' => ['nullable', 'string'],
            'force_status'    => ['nullable', 'string', 'in:exit,fallida'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->adreca_id && !$this->adreca) {
                $validator->errors()->add('adreca', 'Cal proporcionar una adreça o seleccionar-ne una d\'existent.');
            }
        });
    }
}