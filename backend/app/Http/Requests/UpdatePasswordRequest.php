<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

/**
 * Request encarregada de validar el canvi de contrasenya d'un usuari.
 *
 * Verifica la contrasenya actual i valida la nova contrasenya abans
 * de permetre l'actualització.
 */
class UpdatePasswordRequest extends FormRequest
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
     * Defineix les regles de validació per al canvi de contrasenya.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password_actual' => ['required', 'string'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Afegeix validacions personalitzades després de les regles principals.
     *
     * Comprova que la contrasenya actual proporcionada coincideix amb la de l'usuari autenticat.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->password_actual, $this->user()->password)) {
                $validator->errors()->add('password_actual', 'La contrasenya actual no és correcta.');
            }
        });
    }
}