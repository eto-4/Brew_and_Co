<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use App\Models\Product;

/**
 * Controlador responsable de la gestió dels productes.
 *
 * Permet consultar productes, veure'n el detall, canviar-ne la disponibilitat
 * i obtenir les categories actives.
 */
class ProductController extends Controller
{
    /**
     * Retorna la llista de tots els productes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * Retorna el detall d'un producte específic.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Alterna l'estat de disponibilitat d'un producte.
     *
     * Canvia el valor de "disponible" entre actiu i inactiu.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleDisponible(Product $product): JsonResponse
    {
        $product->update([
            'disponible' => !$product->disponible,
        ]);
        return response()->json($product);
    }

    /**
     * Retorna les categories actives del sistema.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(): JsonResponse
    {
        $categories = Category::where('activa', true)->get();
        return response()->json($categories);
    }
}