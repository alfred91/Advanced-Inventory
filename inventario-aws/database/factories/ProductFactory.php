<?php

namespace Database\Factories;

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
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'category_id' => Category::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'minimum_stock' => $this->faker->numberBetween(10, 50),
            'image' => 'products/product.svg',
        ];
    }

    /**
     * Create a product with specific data.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSpecificData(string $name, string $image)
    {
        return $this->state(function (array $attributes) use ($name, $image) {
            return [
                'name' => $name,
                'image' => 'products/' . $image,
            ];
        });
    }
}
