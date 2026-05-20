<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

/**
 * Controlador responsable de la gestió de les ofertes.
 *
 * Permet consultar, crear, actualitzar i eliminar ofertes,
 * així com gestionar la seva relació amb els productes.
 */
class OfferController extends Controller
{
    /**
     * Retorna totes les ofertes amb els seus productes associats.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $offers = Offer::with('products')->get();

        return response()->json($offers);
    }

    /**
     * Crea una nova oferta i la relaciona amb productes.
     *
     * @param \App\Http\Requests\StoreOfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Actualitza una oferta existent i sincronitza els seus productes.
     *
     * @param \App\Http\Requests\StoreOfferRequest $request
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Elimina una oferta i desassocia tots els seus productes.
     *
     * @param \App\Models\Offer $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Offer $offer): JsonResponse
    {
        $offer->products()->detach();
        $offer->delete();

        return response()->json(['message' => 'Oferta eliminada correctament.']);
    }
}