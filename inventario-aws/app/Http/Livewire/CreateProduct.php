<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $name, $description, $price, $quantity, $newImage, $category_id, $supplier_id;
    public $categories, $suppliers, $customers;

    public function mount()
    {
        $this->categories = Category::all();
        $this->suppliers = Supplier::all();
        $this->customers = Customer::all();
    }

    public function render()
    {
        return view('livewire.create-product');
    }

    public function openModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveProduct()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'newImage' => 'nullable|image|max:2048',
        ]);

        $product = new Product([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
        ]);

        if ($this->newImage) {
            $imageName = $this->newImage->store('products', 'public');
            $product->image = $imageName;
        }

        $product->save();

        $this->closeModal();
        $this->reset('name', 'description', 'price', 'quantity', 'newImage', 'category_id', 'supplier_id');
        $this->dispatch('productCreated'); // Emit an event to notify that the product was created
    }
}
