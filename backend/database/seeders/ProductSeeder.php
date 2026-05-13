<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cafes     = Category::where('nom', 'Cafès')->first();
        $entrepans = Category::where('nom', 'Entrepans')->first();
        $pastisseria = Category::where('nom', 'Pastisseria')->first();
        $snacks    = Category::where('nom', 'Snacks')->first();
        $begudes   = Category::where('nom', 'Begudes')->first();

        $products = [
            // Cafès
            ['nom' => 'Cafè Americà',       'preu' => 1.50, 'imatge' => 'CafeAmerica.webp',                'categoria' => $cafes,       'destacat' => true],
            ['nom' => 'Cafè Cappuccino',    'preu' => 2.20, 'imatge' => 'CafeCappuccino.webp',             'categoria' => $cafes,       'destacat' => true],
            ['nom' => 'Cafè Capufreddo',    'preu' => 2.50, 'imatge' => 'CafeCapuFreddo.webp',             'categoria' => $cafes,       'destacat' => false],
            ['nom' => 'Cafè amb Llet',      'preu' => 1.80, 'imatge' => 'CafeLlet.webp',                   'categoria' => $cafes,       'destacat' => false],
            ['nom' => 'Cafè Sol',           'preu' => 1.20, 'imatge' => 'CafeSol.webp',                    'categoria' => $cafes,       'destacat' => false],
            ['nom' => 'Cafè Tallat',        'preu' => 1.30, 'imatge' => 'CafeTallat.webp',                 'categoria' => $cafes,       'destacat' => false],
            // Entrepans
            ['nom' => 'Entrepà Pernil Dolç','preu' => 3.50, 'imatge' => 'entrepaPernyildols.webp',         'categoria' => $entrepans,   'destacat' => false],
            ['nom' => 'Entrepà Pernil Salat','preu' => 3.50,'imatge' => 'entrepaPernyilSalat.webp',        'categoria' => $entrepans,   'destacat' => false],
            ['nom' => 'Entrepà Pernil i Formatge','preu' => 4.00,'imatge' => 'entrepaPernyilSalatiFormatge.webp','categoria' => $entrepans,'destacat' => true],
            ['nom' => 'Entrepà Truita',     'preu' => 3.80, 'imatge' => 'entrepaTruita.webp',              'categoria' => $entrepans,   'destacat' => false],
            // Pastisseria
            ['nom' => 'Croissant',          'preu' => 1.80, 'imatge' => 'Cruassant.webp',                  'categoria' => $pastisseria, 'destacat' => true],
            ['nom' => 'Donuts',             'preu' => 1.50, 'imatge' => 'Donuts.webp',                     'categoria' => $pastisseria, 'destacat' => false],
            ['nom' => 'Muffin',             'preu' => 2.00, 'imatge' => 'Muffin.webp',                     'categoria' => $pastisseria, 'destacat' => false],
            ['nom' => 'Napolitana Xocolata','preu' => 1.80, 'imatge' => 'NapolitanaChoco.webp',            'categoria' => $pastisseria, 'destacat' => false],
            ['nom' => 'Pasta de Full',      'preu' => 1.60, 'imatge' => 'pastaFull.webp',                  'categoria' => $pastisseria, 'destacat' => false],
            // Snacks
            ['nom' => 'Croquetes',          'preu' => 3.20, 'imatge' => 'croquetes.webp',                  'categoria' => $snacks,      'destacat' => false],
            ['nom' => 'Fruits Secs',        'preu' => 1.50, 'imatge' => 'fruitsSecs.webp',                 'categoria' => $snacks,      'destacat' => false],
            ['nom' => 'Iogurt Grec',        'preu' => 2.00, 'imatge' => 'iogurtGrec.webp',                 'categoria' => $snacks,      'destacat' => false],
            // Begudes
            ['nom' => 'Aigua',              'preu' => 1.00, 'imatge' => 'aigua.webp',                      'categoria' => $begudes,     'destacat' => false],
            ['nom' => 'Suc de Taronja',     'preu' => 2.00, 'imatge' => 'sucTaronja.webp',                 'categoria' => $begudes,     'destacat' => true],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'nom'        => $productData['nom'],
                'preu'       => $productData['preu'],
                'imatge'     => '/images/products/' . $productData['imatge'],
                'disponible' => true,
                'destacat'   => $productData['destacat'],
            ]);

            $product->categories()->attach($productData['categoria']->id);
        }
    }
}