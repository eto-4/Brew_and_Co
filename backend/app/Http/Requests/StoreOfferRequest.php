<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar la creació d'una oferta.
 *
 * Defineix les regles necessàries per crear una oferta amb preu rebaixat,
 * dates de vigència i productes associats.
 */
class StoreOfferRequest extends FormRequest
{
    /**
     * Determina si la petició està autoritzada.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Defineix les regles de validació per a la creació d'una oferta.
     *
     * @return array<string, mixed>
     */
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