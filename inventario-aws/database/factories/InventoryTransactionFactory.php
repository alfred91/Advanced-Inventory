<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(), // Esto generará un producto si no existe uno.
            'transaction_type' => fake()->randomElement(['purchase', 'sale', 'adjustment']),
            'quantity' => fake()->numberBetween(1, 20),
        ];
    }
}
