<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $context = $this->buildContext();

        $aiService = new AIService();
        $result    = $aiService->chat($request->message, $context);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 503);
        }

        return response()->json(['message' => $result['message']]);
    }

    private function buildContext(): string
    {
        $totalOrdres       = Order::count();
        $ordresProcessades = Order::where('estat', 'processada')->count();
        $totalIngressos    = Order::where('estat', 'processada')->sum('total');

        $productesPopulars = Product::withCount('orderLines')
            ->orderBy('order_lines_count', 'desc')
            ->take(5)
            ->get()
            ->map(fn($p) => "{$p->nom}: {$p->order_lines_count} unitats venudes")
            ->join(', ');

        $ordresPerDia = Order::where('estat', 'processada')
            ->selectRaw('DAYOFWEEK(created_at) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->get()
            ->map(fn($o) => "Dia {$o->dia}: {$o->total} ordres")
            ->join(', ');

        $pagamentsFallits = Payment::where('estat', 'fallida')->count();

        return "
            - Total d'ordres: {$totalOrdres}
            - Ordres processades: {$ordresProcessades}
            - Total d'ingressos: {$totalIngressos}€
            - Productes més venuts: {$productesPopulars}
            - Ordres per dia de la setmana: {$ordresPerDia}
            - Pagaments fallits: {$pagamentsFallits}
        ";
    }
}