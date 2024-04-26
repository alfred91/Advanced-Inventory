<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->locale('es_ES');
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'image' => 'images/suppliers/Default.png',
        ];
    }
}
