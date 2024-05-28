<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaming', 'image' => 'storage/categories/gaming.png'],
            ['name' => 'Telefonia', 'image' => 'storage/categories/telefonia.png'],
            ['name' => 'Herramientas', 'image' => 'storage/categories/herramientas.png'],
            ['name' => 'Informática', 'image' => 'storage/categories/informatica.png'],
            ['name' => 'Deportes', 'image' => 'storage/categories/deportes.png'],
            ['name' => 'Electrodomésticos', 'image' => 'storage/categories/electrodomesticos.png'],
            ['name' => 'Juguetes', 'image' => 'storage/categories/juguetes.png'],
            ['name' => 'Libros', 'image' => 'storage/categories/libros.png'],
            ['name' => 'Jardineria', 'image' => 'storage/categories/jardineria.png'],
            ['name' => 'Musica', 'image' => 'storage/categories/musica.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
