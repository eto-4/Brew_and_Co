<?php

namespace App\Services;

class PaymentService
{
    private array $errorMessages = [
        'Fons insuficients.',
        'Targeta caducada.',
        'Operació denegada pel banc.',
        'Error de connexió amb l\'entitat bancària.',
        'Límit de transaccions superat.',
    ];

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