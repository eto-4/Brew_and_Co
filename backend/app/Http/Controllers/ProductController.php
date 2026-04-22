<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Product;

class ProductController implements Controller 
{
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function toggleDisponible(Product $product): JsonResponse
    {
        $product->update([
            'disponible' => !$product->disponible,
        ]);
        return response()->json($product);
    }
}