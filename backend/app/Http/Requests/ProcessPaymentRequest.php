<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar les dades del procés de pagament.
 *
 * Gestiona la validació del mètode de pagament, codis de descompte
 * i dades d'adreça (existent o nova) abans de processar la comanda.
 */
class ProcessPaymentRequest extends FormRequest
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
     * Defineix les regles de validació del procés de pagament.
     *
     * @return array<string, mixed>
     */
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

    /**
     * Afegeix validacions personalitzades després de les regles principals.
     *
     * Verifica que l'usuari proporcioni una adreça existent o una de nova.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->adreca_id && !$this->adreca) {
                $validator->errors()->add('adreca', 'Cal proporcionar una adreça o seleccionar-ne una d\'existent.');
            }
        });
    }
}