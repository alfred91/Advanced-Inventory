<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            ['name' => 'AMAZON', 'image' => 'AMAZON.png'],
            ['name' => 'AMD', 'image' => 'AMD.png'],
            ['name' => 'APPLE', 'image' => 'APPLE.png'],
            ['name' => 'ASUS', 'image' => 'ASUS.png'],
            ['name' => 'BOSCH', 'image' => 'BOSCH.png'],
            ['name' => 'BROTHER', 'image' => 'BROTHER.png'],
            ['name' => 'CANON', 'image' => 'CANON.png'],
            ['name' => 'DYSON', 'image' => 'DYSON.png'],
            ['name' => 'ELGATO', 'image' => 'ELGATO.png'],
            ['name' => 'GIGABYTE', 'image' => 'GIGABYTE.png'],
            ['name' => 'HP', 'image' => 'HP.png'],
            ['name' => 'INTEL', 'image' => 'INTEL.png'],
            ['name' => 'JBL', 'image' => 'JBL.png'],
            ['name' => 'LENOVO', 'image' => 'LENOVO.png'],
            ['name' => 'LG', 'image' => 'LG.png'],
            ['name' => 'LOGITECH', 'image' => 'LOGITECH.png'],
            ['name' => 'MSI', 'image' => 'MSI.png'],
            ['name' => 'SAMSUNG', 'image' => 'SAMSUNG.png'],
            ['name' => 'SANDISK', 'image' => 'SANDISK.png'],
            ['name' => 'SONY', 'image' => 'SONY.png'],
            ['name' => 'STEELSERIES', 'image' => 'STEELSERIES.png'],
            ['name' => 'STIHL', 'image' => 'STIHL.png'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::factory()->withSpecificData($supplier['name'], $supplier['image'])->create();
        }
    }
}
