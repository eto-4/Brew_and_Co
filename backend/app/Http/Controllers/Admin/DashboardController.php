<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\ExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Controlador del panell d'administració.
 *
 * Proporciona estadístiques generals del sistema i funcionalitat
 * d'exportació de dades en diferents formats.
 */
class DashboardController extends Controller
{
    /**
     * Retorna les estadístiques principals del sistema.
     *
     * Inclou ingressos totals, comandes del mes, productes més populars
     * i ingressos agrupats per dia.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $totalIngressosMes = Order::where('estat', 'entregada')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $totalIngressosAllTime = Order::where('estat', 'entregada')
            ->sum('total');

        $ordresMesActual = Order::whereMonth('created_at', now()->month)->count();

        $productesPopulars = Product::withCount('orderLines')
            ->orderBy('order_lines_count', 'desc')
            ->take(3)
            ->get();

        $ingressosPerId = Order::where('estat', 'entregada')
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DAYOFWEEK(created_at) as dia, SUM(total) as total')
            ->groupBy('dia')
            ->get();

        return response()->json([
            'total_ingressos_mes'      => $totalIngressosMes,
            'total_ingressos_alltime'  => $totalIngressosAllTime,
            'ordres_mes_actual'        => $ordresMesActual,
            'productes_populars'       => $productesPopulars,
            'ingressos_per_dia'        => $ingressosPerId,
        ]);
    }

    /**
     * Exporta les dades del sistema en el format especificat.
     *
     * Permet exportar informació en CSV o JSON mitjançant el servei d'exportació.
     *
     * @param string $format Format d'exportació (csv o json)
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function export(string $format): Response|JsonResponse
    {
        $exportService = new ExportService();

        return match($format) {
            'csv'  => $exportService->exportCsv(),
            'json' => $exportService->exportJson(),
            default => response()->json(['message' => 'Format no vàlid.'], 422)
        };
    }
}