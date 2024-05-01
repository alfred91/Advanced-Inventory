<?php

// App\Http\Livewire\ProductsList.php
namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductsList extends Component
{

    public function reloadProducts()
    {
        $this->products = Product::all();
    }
    public $products;
    protected $layout = 'layouts.app';
    protected $listeners = ['productUpdated' => 'reloadProducts'];
    public function mount()
    {
        $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.products-list', ['products' => $this->products]);
    }
}
