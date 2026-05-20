<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderTrackingService
{
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

    public function tryAdvance(Order $order): void
    {
        if (!$order->temps_estimat) return;

        if (now()->lt($order->temps_estimat)) {
            return;
        }

        $this->advanceTracking($order->fresh());
    }

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

    public function skipCurrentStep(Order $order): void
    {
        $this->advanceTracking($order);
    }

    private function startEnviament(Order $order): void
    {
        $minuts = rand(5, 30);

        $order->update([
            'estat'         => 'en_enviament',
            'temps_estimat' => now()->addMinutes($minuts),
        ]);
    }

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