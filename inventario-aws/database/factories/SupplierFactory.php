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
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'image' => 'suppliers/default.png',
        ];
    }

    /**
     * Create a supplier with specific data.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSpecificData(string $name, string $image)
    {
        return $this->state(function (array $attributes) use ($name, $image) {
            return [
                'name' => $name,
                'image' => 'suppliers/' . $image,
            ];
        });
    }
}
