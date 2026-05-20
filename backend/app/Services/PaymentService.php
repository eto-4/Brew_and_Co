<?php

namespace App\Services;

/**
 * Servei que simula el processament de pagaments.
 *
 * Gestiona l'execució d'un pagament i retorna un resultat simulat,
 * incloent èxit o fallada amb un missatge d'error aleatori.
 */
class PaymentService
{
    /**
     * Llista de missatges d'error possibles en un pagament fallit.
     *
     * @var array<int, string>
     */
    private array $errorMessages = [
        'Fons insuficients.',
        'Targeta caducada.',
        'Operació denegada pel banc.',
        'Error de connexió amb l\'entitat bancària.',
        'Límit de transaccions superat.',
    ];

    /**
     * Processa un pagament de manera simulada.
     *
     * Genera un resultat aleatori amb un 80% de probabilitat d'èxit.
     * En cas d'error, retorna un missatge aleatori de la llista d'errors.
     *
     * @return array{estat: string, descripcio: string}
     */
    public function process(): array
    {
        $success = rand(1, 100) <= 80;

        if ($success) {
            return [
                'estat'      => 'exit',
                'descripcio' => 'Pagament processat correctament.',
            ];
        }   

        return [
            'estat'      => 'fallida',
            'descripcio' => $this->errorMessages[array_rand($this->errorMessages)],
        ];
    }
}