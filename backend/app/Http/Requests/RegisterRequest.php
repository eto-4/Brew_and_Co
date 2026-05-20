<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar les dades de registre d'un usuari.
 *
 * Defineix les regles necessàries per crear un nou usuari al sistema,
 * incloent validació d'email únic i contrasenya segura.
 */
class RegisterRequest extends FormRequest
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
     * Defineix les regles de validació del registre d'usuari.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom'      => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:usuaris,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'adreca'   => ['nullable', 'string', 'max:255'],
        ];
    }
}