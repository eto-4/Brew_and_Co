<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountCodeRequest;
use App\Http\Requests\UpdateDiscountCodeRequest;
use App\Models\DiscountCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return response()->json([
            'codi'        => $codi->codi,
            'percentatge' => $codi->percentatge,
        ]);
    }

    public function store(StoreDiscountCodeRequest $request): JsonResponse
    {
        $codi = DiscountCode::create([
            'codi'        => $request->codi,
            'percentatge' => $request->percentatge,
            'actiu'       => $request->actiu ?? true,
            'expires_at'  => $request->expires_at,
        ]);

        return response()->json($codi, 201);
    }

    public function update(
        UpdateDiscountCodeRequest $request, 
        DiscountCode $discountCode
    ): JsonResponse
    {
        $discountCode->update($request->only([
            'codi',
            'percentatge',
            'actiu',
            'expires_at',
        ]));

        return response()->json($discountCode);
    }
}