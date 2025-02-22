<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Perfil',
            'sobrenome' => 'Administrador',
            'email' => 'admin@gmail.com',
        ]);
        $this->call(CategoriaSeeder::class);
        $this->call(Sub_categoriaSeeder::class);
        $this->call(SaidaSeeder::class);
    }
}
