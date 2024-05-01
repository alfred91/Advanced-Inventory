<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use WithFileUploads;
    public $showModal = false;
    public $productId;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $newImage;


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
            'newImage' => 'nullable|image|max:2048',
        ]);

        $product = Product::find($this->productId);
        if ($product) {
            $product->update($validatedData);

            if ($this->newImage) {
                $imageName = $this->newImage->store('products', 'public');
                $product->image = $imageName;
                $product->save();
            }

            $this->showModal = false;
        }
        $this->dispatch('productUpdated'); //EMIT DESAPARECE DE LA V3
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
