<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Jobs\ProcessOrderTracking;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\DiscountCode;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(
        ProcessPaymentRequest $request, 
        Order $order
    ): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }

        if ($order->estat !== 'pendent') {
            return response()->json(['message' => 'Aquesta ordre ja ha estat processada.'], 422);
        }

        // Gestió de l'adreça
        if ($request->adreca_id) {

            $adrecaId = $request->adreca_id;
        
        } else {
          
            $etiqueta = $request->adreca['etiqueta'] ??
                $request->adreca['adreca'] . '-' . $request->adreca['codi_postal'] . '-' . $request->adreca['ciutat'] . '-' . now()->timestamp;

            $novaAdreca = Address::create([
                'usuari_id'      => Auth::id(),
                'etiqueta'       => $etiqueta,
                'adreca'         => $request->adreca['adreca'],
                'codi_postal'    => $request->adreca['codi_postal'],
                'ciutat'         => $request->adreca['ciutat'],
                'predeterminada' => Auth::user()->adreces()->count() === 0,
            ]);

            $adrecaId = $novaAdreca->id;
        }

        // Gestió del codi de descompte
        $codiDescompteId = null;
        $descompte = 0;

        if ($request->codi_descompte) {
            $codi = DiscountCode::where('codi', $request->codi_descompte)
                ->where('actiu', true)
                ->where('expires_at', '>', now())
                ->first();

            if ($codi) {
                $codiDescompteId = $codi->id;
                $descompte = $order->total * ($codi->percentatge / 100);
            }
        }

        // Simulació del pagament
        $paymentService = new PaymentService();
        $result = $paymentService->process();

        // Crear registre de pagament
        $payment = Payment::create([
            'comanda_id'        => $order->id,
            'metode'            => $request->metode,
            'estat'             => $result['estat'],
            'descripcio'        => $result['descripcio'],
            'codi_descompte_id' => $codiDescompteId,
        ]);

        // Actualitzar ordre
        $order->update([
            'adreca_id' => $adrecaId,
            'estat'     => $result['estat'] === 'exit' ? 'processada' : 'fallida',
            'total'     => $order->total - $descompte,
        ]);

        if ($result['estat'] === 'exit') {
            ProcessOrderTracking::dispatch($order);
        }

        return response()->json([
            'payment' => $payment,
            'order'   => $order,
            'result'  => $result,
        ], $result['estat'] === 'exit' ? 200 : 422);
    }

    public function forceStatus(
        ProcessPaymentRequest $request, 
        Payment $payment
    ): JsonResponse
    {
        $payment->update(['estat' => $request->estat]);

        $payment->order->update([
            'estat' => $request->estat === 'exit' ? 'processada' : 'fallida',
        ]);

        return response()->json([
            'payment' => $payment,
            'order'   => $payment->order,
        ]);
    }
}