<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderTracking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Order $order) {}

    public function handle(OrderTrackingService $trackingService): void
    {
        $trackingService->initTracking($this->order);

        // Fase 1 - Empaquetant
        $minutsEmpaquetant = $this->order->temps_estimat->diffInMinutes(now());
        sleep($minutsEmpaquetant * 60);

        $trackingService->advanceTracking($this->order->fresh());

        // Fase 2 - En enviament
        $this->order->refresh();
        $minutsEnviament = $this->order->temps_estimat->diffInMinutes(now());
        sleep($minutsEnviament * 60);

        $trackingService->advanceTracking($this->order->fresh());
    }
}