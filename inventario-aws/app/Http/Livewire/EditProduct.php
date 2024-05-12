<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class EditProduct extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $product, $productId, $name, $description, $price, $quantity, $image, $newImage, $category_id, $supplier_id;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->product = Product::find($productId);

        if ($this->product) {
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->price = $this->product->price;
            $this->quantity = $this->product->quantity;
            $this->image = $this->product->image;
            $this->category_id = $this->product->category_id;
            $this->supplier_id = $this->product->supplier_id;
        }
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

    public function saveChanges()
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

        $this->product->update($validatedData);

        if ($this->newImage) {
            Storage::delete('public/' . $this->product->image);
            $imageName = $this->newImage->store('products', 'public');
            $this->product->image = $imageName;
            $this->product->save();
        }

        session()->flash('message', 'Product updated successfully.');
        return redirect()->to('/products');
    }


    public function render()
    {
        return view('livewire.edit-product');
    }
}
