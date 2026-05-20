<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job encarregat d'inicialitzar el seguiment d'una comanda.
 *
 * Executa el servei de tracking per assignar l'estat inicial
 * i el temps estimat de processament de la comanda.
 */
class ProcessOrderTracking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Inicialitza el job amb la comanda que s'ha de processar.
     *
     * @param \App\Models\Order $order
     */
    public function __construct(private Order $order) {}

    /**
     * Executa el procés d'inicialització del seguiment de la comanda.
     *
     * @param \App\Services\OrderTrackingService $trackingService
     * @return void
     */
    public function handle(OrderTrackingService $trackingService): void
    {
        $trackingService->initTracking($this->order->fresh());
    }
}