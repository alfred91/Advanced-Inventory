<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class EditProduct extends Component
{
    public $showModal = false;
    public $productId;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;

    public function mount($productId)
    {
        $this->productId = $productId;
        $product = Product::find($productId);

        if ($product) {
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->quantity = $product->quantity;
            $this->image = $product->image;
        }
    }
    public function saveChanges()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = Product::find($this->productId);
        if ($product) {
            $product->update($validatedData);
            $this->showModal = false;
        }
    }



    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
