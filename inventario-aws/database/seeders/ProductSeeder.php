<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'altavoz', 'image' => 'altavoz.png'],
            ['name' => 'amdGrafica', 'image' => 'amdGrafica.png'],
            ['name' => 'amdRadeon', 'image' => 'amdRadeon.png'],
            ['name' => 'appleTV', 'image' => 'appleTV.png'],
            ['name' => 'aspiradora', 'image' => 'aspiradora.png'],
            ['name' => 'aspiradora1', 'image' => 'aspiradora1.png'],
            ['name' => 'atornilladora', 'image' => 'atornilladora.png'],
            ['name' => 'brother1', 'image' => 'brother1.png'],
            ['name' => 'camara', 'image' => 'camara.png'],
            ['name' => 'canonImpresora', 'image' => 'canonImpresora.png'],
            ['name' => 'canonTinta', 'image' => 'canonTinta.png'],
            ['name' => 'capturadora', 'image' => 'capturadora.png'],
            ['name' => 'capturadora1', 'image' => 'capturadora1.png'],
            ['name' => 'cargador', 'image' => 'cargador.png'],
            ['name' => 'cascos', 'image' => 'cascos.png'],
            ['name' => 'cortacesped', 'image' => 'cortacesped.png'],
            ['name' => 'sopladora', 'image' => 'sopladora.png'],
            ['name' => 'bateria', 'image' => 'bateria.png'],
            ['name' => 'firestick', 'image' => 'firestick.png'],
            ['name' => 'gigabyteCard', 'image' => 'gigabyteCard.png'],
            ['name' => 'grabadoraLG', 'image' => 'grabadoraLG.png'],
            ['name' => 'i5', 'image' => 'i5.png'],
            ['name' => 'i9', 'image' => 'i9.png'],
            ['name' => 'imacRetina', 'image' => 'imacRetina.png'],
            ['name' => 'impresoraBrother', 'image' => 'impresoraBrother.png'],
            ['name' => 'iphone', 'image' => 'iphone.png'],
            ['name' => 'jbl1', 'image' => 'jbl1.png'],
            ['name' => 'jblCharge', 'image' => 'jblCharge.png'],
            ['name' => 'jblGo', 'image' => 'jblGo.png'],
            ['name' => 'lavadora', 'image' => 'lavadora.png'],
            ['name' => 'lenovoLaptop', 'image' => 'lenovoLaptop.png'],
            ['name' => 'lenovoLaptop1', 'image' => 'lenovoLaptop1.png'],
            ['name' => 'logitechCam', 'image' => 'logitechCam.png'],
            ['name' => 'logitechMouse', 'image' => 'logitechMouse.png'],
            ['name' => 'macbook', 'image' => 'macbook.png'],
            ['name' => 'macbookPro', 'image' => 'macbookPro.png'],
            ['name' => 'martillo', 'image' => 'martillo.png'],
            ['name' => 'microsd', 'image' => 'microsd.png'],
            ['name' => 'monitorAsus', 'image' => 'monitorAsus.png'],
            ['name' => 'motosierra1', 'image' => 'motosierra1.png'],
            ['name' => 'msiGrafica', 'image' => 'msiGrafica.png'],
            ['name' => 'msiLaptop', 'image' => 'msiLaptop.png'],
            ['name' => 'msiMonitor', 'image' => 'msiMonitor.png'],
            ['name' => 'msiPlaca', 'image' => 'msiPlaca.png'],
            ['name' => 'msiPlaca1', 'image' => 'msiPlaca1.png'],
            ['name' => 'patinete', 'image' => 'patinete.png'],
            ['name' => 'pc', 'image' => 'pc.png'],
            ['name' => 'pilas', 'image' => 'pilas.png'],
            ['name' => 'portatilSamsung', 'image' => 'portatilSamsung.png'],
            ['name' => 'product', 'image' => 'product.svg'],
            ['name' => 'ps4', 'image' => 'ps4.png'],
            ['name' => 'radial', 'image' => 'radial.png'],
            ['name' => 'recortadora', 'image' => 'recortadora.png'],
            ['name' => 'remachadora', 'image' => 'remachadora.png'],
            ['name' => 'rogPhone3', 'image' => 'rogPhone3.png'],
            ['name' => 'sandiskUSB', 'image' => 'sandiskUSB.png'],
            ['name' => 'satinador', 'image' => 'satinador.png'],
            ['name' => 'sd', 'image' => 'sd.png'],
            ['name' => 'sonySpeaker', 'image' => 'sonySpeaker.png'],
            ['name' => 'sonySrs', 'image' => 'sonySrs.png'],
            ['name' => 'steelseriesKeyboard', 'image' => 'steelseriesKeyboard.png'],
            ['name' => 'steelseriesMouse', 'image' => 'steelseriesMouse.png'],
            ['name' => 'steelseriesMousepad', 'image' => 'steelseriesMousepad.png'],
            ['name' => 'taladro', 'image' => 'taladro.png'],
            ['name' => 'torre', 'image' => 'torre.png'],
            ['name' => 'usb', 'image' => 'usb.png'],
            ['name' => 'vitroceramica', 'image' => 'vitroceramica.png']
        ];

        // Delete existing products to prevent duplicates
        Product::query()->delete();

        // Create specified products
        foreach ($products as $product) {
            Product::factory()->withSpecificData($product['name'], $product['image'])->create();
        }
    }
}
