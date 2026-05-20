<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar la creació d'una adreça.
 *
 * Defineix les regles necessàries per emmagatzemar una nova adreça
 * associada a un usuari dins del sistema.
 */
class StoreAddressRequest extends FormRequest
{
    /**
     * Defineix les regles de validació per a la creació d'una adreça.
     *
     * @return array<string, mixed>
     */
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