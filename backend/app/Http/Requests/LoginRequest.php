<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar les dades d'inici de sessió.
 *
 * Defineix les regles de validació necessàries per autenticar
 * un usuari dins del sistema.
 */
class LoginRequest extends FormRequest
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
     * Defineix les regles de validació de la petició.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}