<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessPaymentRequest;
use App\Jobs\ProcessOrderTracking;
use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(ProcessPaymentRequest $request, Order $order): JsonResponse
    {
        if ($order->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat.'], 403);
        }

        if (!in_array($order->estat, ['pendent', 'fallida'])) {
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
        
        $yaAplicoDescompte = $order->payments()
            ->whereNotNull('codi_descompte_id')
            ->exists();
        
        if (!$yaAplicoDescompte && $request->codi_descompte) {
            $codi = DiscountCode::where('codi', $request->codi_descompte)
                ->where('actiu', true)
                ->where('expires_at', '>', now())
                ->first();
        
            if ($codi) {
                $codiDescompteId = $codi->id;
                $descompte = $order->total * ($codi->percentatge / 100);
            }
        }

        // Simulació del pagament — force_status si és admin
        if ($request->force_status && Auth::user()->isAdmin()) {
            $result = [
                'estat'      => $request->force_status,
                'descripcio' => $request->force_status === 'exit'
                    ? 'Pagament forçat per administrador.'
                    : 'Fallida forçada per administrador.',
            ];
        } else {
            $paymentService = new PaymentService();
            $result = $paymentService->process();
        }

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

        // Despachar tracking si èxit
        if ($result['estat'] === 'exit') {
            ProcessOrderTracking::dispatch($order->fresh());
        }

        return response()->json([
            'payment' => $payment->fresh(),
            'order'   => $order->fresh(),
            'result'  => $result,
        ], $result['estat'] === 'exit' ? 200 : 422);
    }

    public function adminPrefill(): JsonResponse
    {
        $adreca = Auth::user()->adreces()
            ->where('predeterminada', true)
            ->first();

        $adrecaData = $adreca ? [
            'adreca_id' => $adreca->id,
            'adreca'    => null,
        ] : [
            'adreca_id' => null,
            'adreca'    => [
                'etiqueta'    => 'Test',
                'adreca'      => 'Carrer de Proves 123',
                'codi_postal' => '08000',
                'ciutat'      => 'Barcelona',
            ],
        ];

        return response()->json([
            'targeta' => [
                'numero'    => '4111 1111 1111 1111',
                'nom'       => 'ADMIN TEST',
                'caducitat' => '12/99',
                'cvv'       => '123',
            ],
            ...$adrecaData,
        ]);
    }
}