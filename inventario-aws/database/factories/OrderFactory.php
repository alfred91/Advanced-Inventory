<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'customer_id' => Customer::factory(), // Esto generará un cliente si no existe uno.
            'order_date' => fake()->date(),
            'total_amount' => fake()->randomFloat(2, 0, 1000),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'notification_sent' => fake()->boolean()
        ];
    }
}
