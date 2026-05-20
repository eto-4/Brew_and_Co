<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Response;

/**
 * Servei encarregat de l'exportació de comandes en diferents formats.
 *
 * Permet exportar les comandes processades en format CSV o JSON,
 * incloent informació relacionada com usuari, línies de comanda i pagaments.
 */
class ExportService
{

    /**
     * Obté les comandes processades amb les seves relacions carregades.
     *
     * Inclou línies de comanda, productes, usuari i pagaments associats.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getData(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with('orderLines.product', 'user', 'payments')
            ->where('estat', 'processada')
            ->get();
    }

    /**
     * Exporta les comandes processades en format CSV.
     *
     * Genera un fitxer descarregable amb informació bàsica de cada comanda.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Exporta les comandes processades en format JSON.
     *
     * Retorna un fitxer descarregable amb totes les dades de les comandes
     * i les seves relacions.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportJson(): Response
    {
        $orders = $this->getData();

        return response($orders->toJson(), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="orders.json"',
        ]);
    }
}