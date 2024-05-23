<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Importar el modelo de Role

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $roles = ['administrativo', 'mozo_almacen', 'ventas'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crea un usuario administrativo y asigna el rol
        $admin = User::factory()->administrativo()->create();
        $admin->assignRole('administrativo');

        // Crea un usuario mozo de almacén y asigna el rol
        $mozo = User::factory()->mozoDeAlmacen()->create();
        $mozo->assignRole('mozo_almacen');

        // Crea un usuario de ventas y asigna el rol
        $ventas = User::factory()->ventas()->create();
        $ventas->assignRole('ventas');
    }
}
