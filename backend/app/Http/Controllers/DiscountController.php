<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsable de la validació de codis de descompte.
 *
 * Permet verificar si un codi és vàlid, està actiu i si l'usuari
 * ja l'ha utilitzat prèviament.
 */
class DiscountController extends Controller
{
    /**
     * Valida un codi de descompte per a l'usuari autenticat.
     *
     * Comprova si el codi existeix, està actiu, no ha caducat i si
     * l'usuari ja l'ha utilitzat en pagaments anteriors.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request): JsonResponse
    {
        $codi = DiscountCode::where('codi', $request->codi)
            ->where('actiu', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$codi) {
            return response()->json([
                'message' => 'Codi de descompte invàlid o caducat.'
            ], 422);
        }

        $jaUsat = Payment::whereHas('order', function ($q) {
            $q->where('usuari_id', Auth::id());
        })->where('codi_descompte_id', $codi->id)->exists();

        if ($jaUsat) {
            return response()->json([
                'message' => 'Ja has utilitzat aquest codi de descompte.'
            ], 422);
        }

        return response()->json([
            'codi'        => $codi->codi,
            'percentatge' => $codi->percentatge,
        ]);
    }

}