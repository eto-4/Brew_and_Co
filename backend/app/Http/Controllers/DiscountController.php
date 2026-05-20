<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
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