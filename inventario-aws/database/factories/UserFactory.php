<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'role' => 'mozo_almacen',
        ];
    }

    /**
     * Indicate that the user should be administrativo.
     */
    public function administrativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'administrativo',
            'email' => 'admin@gmail.com',
        ]);
    }

    /**
     * Indicate that the user should be mozo de almacén.
     */
    public function mozoDeAlmacen(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'mozo_almacen',
            'email' => 'mozo_almacen@gmail.com',
        ]);
    }

    /**
     * Indicate that the user should be ventas.
     */
    public function ventas(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'ventas',
            'email' => 'ventas@gmail.com',
        ]);
    }
}
