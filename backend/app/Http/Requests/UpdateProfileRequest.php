<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Request encarregada de validar l'actualització del perfil d'usuari.
 *
 * Gestiona la validació de les dades bàsiques del perfil com el nom
 * i l'email, assegurant que l'email sigui únic excepte per l'usuari actual.
 */
class UpdateProfileRequest extends FormRequest
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
     * Defineix les regles de validació per a l'actualització del perfil.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom'   => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('usuaris', 'email')->ignore(Auth::id()),
            ],
        ];
    }
}