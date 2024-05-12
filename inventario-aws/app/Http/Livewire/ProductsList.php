<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;

    public $search = '';
    public $editingProductId = null;

    protected $listeners = ['productDeleted' => 'render'];
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function reloadProducts()
    {
        $this->resetPage();
    }
    public function editProduct($productId)
    {
        $this->editingProductId = $productId;
        $this->dispatch('edit-product-modal', ['productId' => $productId]);
    }
    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->delete();
            return redirect()->to('/products');
        }
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')->paginate(10);
        $headers = ['Nombre', 'Descripción', 'Precio', 'Cantidad', 'Imagen'];

        $data = $products->map(function ($product) {
            return [
                'Nombre' => $product->name,
                'Descripción' => $product->description,
                'Precio' => number_format($product->price, 2) . ' €',
                'Cantidad' => $product->quantity,
                'Imagen' => '<img src="' . asset('storage/' . $product->image) . '" style="width:100px; height:auto;">',
                'id' => $product->id
            ];
        });

        return view('livewire.products-list', [
            'products' => $products,
            'headers' => $headers,
            'data' => $data,
        ]);
    }
}
