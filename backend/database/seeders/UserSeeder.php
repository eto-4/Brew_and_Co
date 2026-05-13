<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nom'      => 'Admin',
            'email'    => 'admin@brewco.com',
            'password' => Hash::make('admin1234'),
            'rol'      => 'admin',
        ]);

        $clients = [
            ['nom' => 'Marc Puig',    'email' => 'marc@example.com'],
            ['nom' => 'Laura Ferrer', 'email' => 'laura@example.com'],
            ['nom' => 'Joan Vila',    'email' => 'joan@example.com'],
        ];

        foreach ($clients as $client) {
            User::create([
                'nom'      => $client['nom'],
                'email'    => $client['email'],
                'password' => Hash::make('client1234'),
                'rol'      => 'client',
            ]);
        }
    }
}