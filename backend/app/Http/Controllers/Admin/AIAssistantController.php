<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Services\AIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador responsable de l'assistent d'intel·ligència artificial.
 *
 * Permet interactuar amb un model d'IA utilitzant dades del sistema
 * per respondre preguntes relacionades amb el negoci.
 */
class AIAssistantController extends Controller
{
    /**
     * Gestiona la conversa amb l'assistent d'IA.
     *
     * Valida el missatge de l'usuari, construeix el context del sistema
     * i retorna la resposta generada per la IA.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Construeix el context de dades del sistema per a la IA.
     *
     * Recull estadístiques de comandes, ingressos, productes més venuts,
     * patrons de compres i pagaments fallits.
     *
     * @return string
     */
    private function buildContext(): string
    {
        $totalOrdres       = Order::count();
        $ordresProcessades = Order::where('estat', 'entregada')->count();
        $totalIngressos    = Order::where('estat', 'entregada')->sum('total');

        $productesPopulars = Product::withCount('orderLines')
            ->orderBy('order_lines_count', 'desc')
            ->take(5)
            ->get()
            ->map(fn($p) => "{$p->nom}: {$p->order_lines_count} unitats venudes")
            ->join(', ');

        $ordresPerDia = Order::where('estat', 'entregada')
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