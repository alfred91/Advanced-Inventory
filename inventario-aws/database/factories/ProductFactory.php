<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(0, 100),
            'price' => fake()->randomFloat(2, 0, 1000),
            'category_id' => Category::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'image' => 'images/products/Default.png',
        ];
    }
}
