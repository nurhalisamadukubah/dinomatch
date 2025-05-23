<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'JurassicJig',
                'email'    => 'jurassicjig@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 1,
            ],
            [
                'username' => 'TrickyTriceratops',
                'email'    => 'trickytriceratops@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 2,
            ],
            [
                'username' => 'StegoSolver',
                'email'    => 'stegosolver@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 3,
            ],
            [
                'username' => 'VelociSolve',
                'email'    => 'velocisolve@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 0,
            ],
            [
                'username' => 'PteroPieces',
                'email'    => 'pteropieces@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 1,
            ],
            [
                'username' => 'BrontoBlocks',
                'email'    => 'brontoblocks@example.com',
                'password' => Hash::make('rahasia123'),
                'level'    => 2,
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }
    }
}
