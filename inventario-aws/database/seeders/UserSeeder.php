<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea un usuario administrador
        User::factory()->admin()->create();

        // Crea tres usuarios empleados
        User::factory()->employee()->count(3)->create();
    }
}
