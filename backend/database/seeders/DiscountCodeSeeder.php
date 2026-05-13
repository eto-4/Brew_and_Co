<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        DiscountCode::create([
            'codi'        => 'BREWCO10',
            'percentatge' => 10.00,
            'actiu'       => true,
            'expires_at'  => now()->addMonths(3),
        ]);

        DiscountCode::create([
            'codi'        => 'WELCOME20',
            'percentatge' => 20.00,
            'actiu'       => true,
            'expires_at'  => now()->addMonth(),
        ]);
    }
}