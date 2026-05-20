<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador responsable de la gestió d'adreces d'usuari.
 *
 * Permet consultar, crear, actualitzar, eliminar i definir
 * l'adreça predeterminada de l'usuari autenticat.
 */
class AddressController extends Controller
{
    /**
     * Retorna totes les adreces de l'usuari autenticat.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $adreces = Auth::user()->adreces()->get();
        
        return response()->json($adreces);
    }

    /**
     * Crea una nova adreça per a l'usuari autenticat.
     *
     * Genera automàticament una etiqueta si no es proporciona.
     *
     * @param \App\Http\Requests\StoreAddressRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Actualitza una adreça existent de l'usuari autenticat.
     *
     * Verifica que l'usuari sigui el propietari abans de modificar-la.
     *
     * @param \App\Http\Requests\StoreAddressRequest $request
     * @param \App\Models\Address $adreca
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Elimina una adreça de l'usuari autenticat.
     *
     * Verifica que l'usuari sigui el propietari abans d'esborrar-la.
     *
     * @param \App\Models\Address $adreca
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Address $adreca): JsonResponse
    {
        if ($adreca->usuari_id !== Auth::id()) {
            return response()->json(['message' => 'No autoritzat'], 403);
        }

        $adreca->delete();
        return response()->json(['message' => 'Adreça esborrada correctament.']);
    }

    /**
     * Estableix una adreça com a predeterminada de l'usuari.
     *
     * Desactiva la resta d'adreces predeterminades abans d'assignar la nova.
     *
     * @param \App\Models\Address $adreca
     * @return \Illuminate\Http\JsonResponse
     */
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