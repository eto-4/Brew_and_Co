<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request encarregada de validar la creació d'una comanda.
 *
 * Defineix les regles necessàries per assegurar que una comanda
 * contingui com a mínim una línia de producte amb quantitat vàlida.
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Defineix les regles de validació per a la creació d'una comanda.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'order_lines' => ['required', 'array', 'min:1'],
            'order_lines.*.producte_id' => ['required', 'exists:productes,id'],
            'order_lines.*.quantitat' => ['required', 'integer', 'min:1'],
        ];
    }
}