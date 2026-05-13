<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Cafès',      'descripcio' => 'Tota la nostra selecció de cafès artesanals.'],
            ['nom' => 'Entrepans',  'descripcio' => 'Entrepans frescos preparats al moment.'],
            ['nom' => 'Pastisseria','descripcio' => 'Dolços i pastissos de forn propis.'],
            ['nom' => 'Snacks',     'descripcio' => 'Picades i snacks variats.'],
            ['nom' => 'Begudes',    'descripcio' => 'Begudes fredes i sucs naturals.'],
        ];

        foreach ($categories as $categoria) {
            Category::create([...$categoria, 'activa' => true]);
        }
    }
}