<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(): JsonResponse
    {
        $adreces = Auth::user()->adreces()->get();
        
        return response()->json($adreces);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $data = $request->validated();
    
        $etiqueta = $data['etiqueta'] ??
            $data['adreca'] . '-' . $data['codi_postal'] . '-' . $data['ciutat'] . '-' . now()->timestamp;
    
        $adreca = Auth::user()->adreces()->create([
            'etiqueta'       => $etiqueta,
            'adreca'         => $data['adreca'],
            'codi_postal'    => $data['codi_postal'],
            'ciutat'         => $data['ciutat'],
            'predeterminada' => Auth::user()->adreces()->count() === 0,
        ]);
        return response()->json([
            'message' => 'Adreça afegida correctament.',
            'data' => $adreca
        ], 201);
    }

    public function update(
        StoreAddressRequest $request,
        Address $adreca
    ): JsonResponse
    {
        if ($adreca->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat'], 403);
        }
        
        $data = $request->validated();
        $etiqueta = $data['etiqueta'] ??
            $data['adreca'] . '-' . $data['codi_postal'] . '-' . $data['ciutat'] . '-' . now()->timestamp;

        $adreca->update([
            'etiqueta'       => $etiqueta,
            'adreca'         => $data['adreca'],
            'codi_postal'    => $data['codi_postal'],
            'ciutat'         => $data['ciutat'],
        ]);
        return response()->json([
            'message' => 'Adreça actualitzada correctament.'
        ], 200);
    }

    public function destroy(Address $adreca): JsonResponse
    {
        if ($adreca->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat'], 403);
        }

        $adreca->delete();
        return response()->json(['message' => 'Adreça esborrada correctament.']);
    }

    public function setPredeterminada(Address $adreca): JsonResponse
    {
        if ($adreca->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat'], 403);
        }

        Auth::user()->adreces()->update([
            'predeterminada' => false
        ]);

        $adreca->update([
            'predeterminada' => true
        ]);

        return response()->json([
            'message' => 'Adreça predeterminada actualitzada.'
        ], 200);
    }
}