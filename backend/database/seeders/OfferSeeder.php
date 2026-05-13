<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $cafeAmerica = Product::where('nom', 'Cafè Americà')->first();
        $croissant   = Product::where('nom', 'Croissant')->first();
        $sucTaronja  = Product::where('nom', 'Suc de Taronja')->first();
        $napolitana  = Product::where('nom', 'Napolitana Xocolata')->first();

        // Menú Matí: Cafè Americà + Croissant (normal 3.30€ → pack 2.80€)
        $menuMati = Offer::create([
            'preu_rebaixat' => 2.80,
            'data_inici'    => now(),
            'data_fi'       => now()->addMonth(),
        ]);
        $menuMati->products()->attach([$cafeAmerica->id, $croissant->id]);

        // Menú Refresc: Suc de Taronja + Napolitana (normal 3.80€ → pack 2.90€)
        $menuRefresc = Offer::create([
            'preu_rebaixat' => 2.90,
            'data_inici'    => now(),
            'data_fi'       => now()->addMonth(),
        ]);
        $menuRefresc->products()->attach([$sucTaronja->id, $napolitana->id]);
    }
}