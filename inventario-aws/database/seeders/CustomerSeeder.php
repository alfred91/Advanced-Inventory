<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un cliente genérico
        Customer::create([
            'name' => 'Cliente Genérico',
            'email' => 'clienteGenerico@miempresa.com',
            'phone_number' => '0000000000',
            'address' => 'No especificada'
        ]);

        // Crear otros clientes aleatorios
        Customer::factory(20)->create();
    }
}
