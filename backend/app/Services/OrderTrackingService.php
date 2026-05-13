<?php

namespace App\Services;

use App\Models\Order;

class OrderTrackingService
{
    public function initTracking(Order $order): void
    {
        $minuts = rand(1, 5);

        $order->update([
            'estat'          => 'empaquetant',
            'temps_estimat'  => now()->addMinutes($minuts),
        ]);
    }

    public function advanceTracking(Order $order): void
    {
        match($order->estat) {
            'empaquetant' => $this->startEnviament($order),
            'en_enviament' => $this->completeOrder($order),
            default => null,
        };
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

        $estat  = 'entregada';
        $missatge = null;

        if ($rand <= 30) {         // 3% — el repartidor s'ha menjat la comanda
        
            $estat = 'incidencia';
            $missatge = "Lamentem informar-li que el nostre repartidor ha consumit la seva comanda durant el trajecte. Ens disculpem per les molèsties ocasionades.";
        
        } elseif ($rand <= 40) {   // 1% — repartidor arrestat

            $estat = 'incidencia';
            $missatge = "Degut a circumstàncies legals imprevistes que afecten al seu repartidor assignat, la seva comanda es troba temporalment retinguda. El nostre equip jurídic està tractant el cas.";
        
        } elseif ($rand <= 45) {   // 0.5% — havia d'anar al bany

            $estat = 'en_enviament';
            $missatge = "El seu repartidor ha hagut de fer una parada tècnica imprevista. El temps estimat s'ha recalculat.";
            $order->update(['temps_estimat' => now()->addHours(99)]);
        
        } elseif ($rand <= 46) {   // 0.1% — perdut, comanda en una altra ciutat
            $estat = 'incidencia';
            $missatge = "Lamentem informar-li que per un error de navegació, la seva comanda es troba actualment en una ubicació diferent a la prevista. El nostre equip d'operacions està localitzant el paquet.";
        }

        $order->update([
            'estat'         => $estat,
            'temps_estimat' => $estat === 'entregada' ? null : $order->temps_estimat,
            'missatge'      => $missatge,
        ]);
    }
}