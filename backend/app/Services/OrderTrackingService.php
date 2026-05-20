<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Servei responsable del seguiment i evolució de l'estat d'una comanda.
 *
 * Gestiona la transició d'estats de la comanda (empaquetant, en enviament,
 * entregada o incidència) i actualitza el temps estimat en cada fase.
 */
class OrderTrackingService
{
    /**
     * Inicialitza el seguiment d'una comanda.
     *
     * Assigna l'estat inicial "empaquetant" i defineix un temps estimat
     * aleatori per a la següent fase del procés.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function initTracking(Order $order): void
    {
        $minuts = rand(1, 5);

        $order->update([
            'estat'         => 'empaquetant',
            'temps_estimat' => now()->addMinutes($minuts),
        ]);

        Log::info("INIT TRACKING", [
            'order_id' => $order->id,
            'state'    => $order->estat
        ]);
    }

    /**
     * Intenta avançar l'estat de la comanda si s'ha superat el temps estimat.
     *
     * Si encara no s'ha arribat al temps previst, no realitza cap acció.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function tryAdvance(Order $order): void
    {
        if (!$order->temps_estimat) return;

        if (now()->lt($order->temps_estimat)) {
            return;
        }

        $this->advanceTracking($order->fresh());
    }

    /**
     * Avança l'estat de la comanda segons el seu estat actual.
     *
     * Gestiona la transició entre fases del procés de lliurament.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function advanceTracking(Order $order): void
    {
        Log::info("ADVANCE BEFORE", [
            'order_id' => $order->id,
            'state'    => $order->estat
        ]);

        match ($order->estat) {
            'empaquetant'   => $this->startEnviament($order),
            'en_enviament'  => $this->completeOrder($order),
            default         => null,
        };

        Log::info("ADVANCE AFTER", [
            'order_id' => $order->id,
            'state'    => $order->fresh()->estat
        ]);
    }

    /**
     * Força l'avanç immediat de l'estat de la comanda.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function skipCurrentStep(Order $order): void
    {
        $this->advanceTracking($order);
    }

    /**
     * Inicia la fase d'enviament de la comanda.
     *
     * Assigna estat "en_enviament" i defineix un temps estimat aleatori.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    private function startEnviament(Order $order): void
    {
        $minuts = rand(5, 30);

        $order->update([
            'estat'         => 'en_enviament',
            'temps_estimat' => now()->addMinutes($minuts),
        ]);
    }

    /**
     * Finalitza la comanda o genera una incidència simulada.
     *
     * Pot marcar la comanda com entregada, incidència o mantenir-la en enviament
     * amb una extensió del temps estimat.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    private function completeOrder(Order $order): void
    {
        $rand = rand(1, 1000);

        $estat = 'entregada';
        $missatge = null;

        if ($rand <= 30) {
            $estat = 'incidencia';
            $missatge = "El repartidor ha consumit la comanda.";
        } elseif ($rand <= 40) {
            $estat = 'incidencia';
            $missatge = "Problema legal del repartidor.";
        } elseif ($rand <= 45) {
            $estat = 'en_enviament';
            $missatge = "Parada tècnica del repartidor.";
            $order->update(['temps_estimat' => now()->addHours(99)]);
        } elseif ($rand <= 46) {
            $estat = 'incidencia';
            $missatge = "Comanda perduda en trànsit.";
        }

        $order->update([
            'estat'         => $estat,
            'temps_estimat' => $estat === 'entregada' ? null : $order->temps_estimat,
            'missatge'      => $missatge,
        ]);
    }
}