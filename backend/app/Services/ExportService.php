<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Response;

class ExportService
{
    private function getData(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with('orderLines.product', 'user', 'payments')
            ->where('estat', 'processada')
            ->get();
    }

    public function exportCsv(): Response
    {
        $orders = $this->getData();

        $csv = "id,usuari,total,estat,created_at\n";

        foreach ($orders as $order) {
            $csv .= implode(',', [
                $order->id,
                $order->user->nom,
                $order->total,
                $order->estat,
                $order->created_at,
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders.csv"',
        ]);
    }

    public function exportJson(): Response
    {
        $orders = $this->getData();

        return response($orders->toJson(), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="orders.json"',
        ]);
    }
}