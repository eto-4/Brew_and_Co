<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller 
{
    // Ordres de l'usuari autenticat.
    public function index(): JsonResponse
    {
        $orders = Auth::user()->orders()->with('orderLines.product')->get();

        return response()->json($orders);
    }

    // Una ordre concreta
    public function show(Order $order): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }

        return response()->json($order->load('orderLines.product'));
    }

    //  Crear Ordre
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = Order::create([
            'usuari_id' => Auth::id(),
            'estat'     => 'pendent',
            'total'     => 0,
        ]);

        $total = 0;

        foreach ($request->order_lines as $line) {
            $product = Product::find($line['producte_id']);

            OrderLine::create([
                'comanda_id'   => $order->id,
                'producte_id'  => $line['producte_id'],
                'quantitat'    => $line['quantitat'],
                'preu_unitari' => $product->preu,
            ]);

            $total += $product->preu * $line['quantitat'];
        }

        $order->update(['total' => $total]);

        return response()->json($order->load('orderLines.product'), 201);
    }

    // Modificar Ordre (només linies de comanda)
    public function update(
        StoreOrderRequest $request, 
        Order $order
    ): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }

        if ($order->estat !== 'pendent') {
            return response()->json(['message' => 'No es pot modificar una ordre processada.'], 422);
        }

        $order->orderLines()->delete();

        $total = 0;

        foreach ($request->order_lines as $line) {
            $product = Product::find($line['producte_id']);

            OrderLine::create([
                'comanda_id'   => $order->id,
                'producte_id'  => $line['producte_id'],
                'quantitat'    => $line['quantitat'],
                'preu_unitari' => $product->preu,
            ]);

            $total += $product->preu * $line['quantitat'];
        }

        $order->update(['total' => $total]);

        return response()->json($order->load('orderLines.product'));
    }

    // Cancel·lar Ordre
    public function cancel(Order $order): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }

        if ($order->estat !== 'pendent') {
            return response()->json(['message' => 'No es pot cancel·lar una ordre processada.'], 422);
        }

        $order->update(['estat' => 'cancel·lada']);

        return response()->json(['message' => 'Ordre cancel·lada correctament.']);
    }

    // Totes les ordres (Només admin)
    public function indexAll(): JsonResponse
    {
        $orders = Order::with('orderLines.product', 'user')->get();

        return response()->json($orders);
    }
}