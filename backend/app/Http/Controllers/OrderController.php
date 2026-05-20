<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsable de la gestió de comandes.
 *
 * Permet crear, consultar, modificar i cancel·lar comandes de l'usuari,
 * així com consultar totes les comandes en mode administrador.
 */
class OrderController extends Controller 
{
    /**
     * Retorna totes les comandes de l'usuari autenticat.
     *
     * Inclou les línies de comanda i els productes associats.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = Auth::user()->orders()->with('orderLines.product')->get();

        return response()->json($orders);
    }

    /**
     * Retorna una comanda concreta de l'usuari autenticat.
     *
     * També intenta avançar l'estat del seguiment de la comanda.
     *
     * @param \App\Models\Order $order
     * @param \App\Services\OrderTrackingService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order, OrderTrackingService $service): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }
        
        // Intentar avançar l'estat en cada polling.
        $service->tryAdvance($order);

        return response()->json(
            $order->fresh()->load('orderLines.product')
        );
    }

    /**
     * Crea una nova comanda per a l'usuari autenticat.
     *
     * Genera les línies de comanda, calcula el total i inicialitza
     * la comanda en estat pendent.
     *
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = Auth::user()->orders()->create([
            'adreca_id' => null,
            'estat' => 'pendent',
            'total' => 0,
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

    /**
     * Actualitza una comanda pendent substituint les seves línies.
     *
     * Recalcula el total i elimina les línies anteriors abans d'afegir-ne de noves.
     *
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Cancel·la una comanda si encara no ha estat processada.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Retorna totes les comandes del sistema (només administradors).
     *
     * Inclou informació de les línies de comanda, productes i usuaris.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexAll(): JsonResponse
    {
        $orders = Order::with('orderLines.product', 'user')->get();

        return response()->json($orders);
    }
}