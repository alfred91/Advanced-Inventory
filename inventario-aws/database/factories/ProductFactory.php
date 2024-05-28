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
        // Lista de imágenes disponibles en el directorio "products/"
        $images = [
            'altravoz.png',
            'aspiradora.png',
            'aspiradora1.png',
            'atornilladora.png',
            'camara.png',
            'cargador.png',
            'cascos.png',
            'cortacesped.png',
            'grabadora.png',
            'iphone.png',
            'jbl1.png',
            'satinadorpng',
            'lavadora.png',
            'macbook.png',
            'martillo.png',
            'microsd.png',
            'monitorAsus.png',
            'msiLaptop.png',
            'patinete.png',
            'pc.png',
            'pilas.png',
            'radial.png',
            'remachadora.png',
            'taladro.png',
            'torre.png',
            'usb.png',
            'vitroceramica.png'
        ];

        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(0, 100),
            'price' => fake()->randomFloat(2, 0, 1000),
            'category_id' => Category::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'minimum_stock' => fake()->numberBetween(10, 50),
            'image' => 'products/' . fake()->randomElement($images),
        ];
    }
}
