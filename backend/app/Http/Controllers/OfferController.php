<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function index(): JsonResponse
    {
        $offers = Offer::with('products')->get();

        return response()->json($offers);
    }

    public function store(StoreOfferRequest $request): JsonResponse
    {
        $offer = Offer::create([
            'preu_rebaixat' => $request->preu_rebaixat,
            'data_inici'    => $request->data_inici,
            'data_fi'       => $request->data_fi,
        ]);

        $offer->products()->attach($request->producte_ids);

        return response()->json($offer->load('products'), 201);
    }

    public function update(StoreOfferRequest $request, Offer $offer): JsonResponse
    {
        $offer->update([
            'preu_rebaixat' => $request->preu_rebaixat,
            'data_inici'    => $request->data_inici,
            'data_fi'       => $request->data_fi,
        ]);

        if ($request->producte_ids) {
            $offer->products()->sync($request->producte_ids);
        }

        return response()->json($offer->load('products'));
    }

    public function destroy(Offer $offer): JsonResponse
    {
        $offer->products()->detach();
        $offer->delete();

        return response()->json(['message' => 'Oferta eliminada correctament.']);
    }
}