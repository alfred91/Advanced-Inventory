<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => $this->generateDni(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address()
        ];
    }

    /**
     * Generar un DNI aleatorio.
     *
     * @return string
     */
    protected function generateDni(): string
    {
        $dni = '';
        for ($i = 0; $i < 8; $i++) {
            $dni .= rand(0, 9);
        }
        return $dni;
    }
}
