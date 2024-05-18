<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductsList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categories;
    public $suppliers;
    public $isLoading = false;

    // Editar producto
    public $showModal = false;
    public $productId;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $newImage;
    public $category_id;
    public $supplier_id;

    protected $queryString = ['search'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'newImage' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->categories = Category::all();
        $this->suppliers = Supplier::all();
    }

    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->resetPage();
        }
        $this->isLoading = false;
    }

    public function reloadProducts()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function editProduct($productId)
    {
        $this->productId = $productId;
        $product = Product::findOrFail($productId);

        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;

        $this->showModal = true;
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            if ($product->image && $product->image !== 'products/Default.png') {
                Storage::delete('public/' . $product->image);
            }
            $product->delete();
            $this->resetPage();
        }
    }

    public function saveChanges()
    {
        $validatedData = $this->validate();

        $product = Product::findOrFail($this->productId);
        $product->update($validatedData);

        if ($this->newImage) {
            if ($product->image && $product->image !== 'products/default.png') {
                Storage::delete('public/' . $product->image);
            }
            $imageName = $this->newImage->store('products', 'public');
            $product->image = $imageName;
            $product->save();
        }

        session()->flash('message', 'Producto actualizado correctamente.');
        $this->showModal = false;
        $this->resetInputFields();
        $this->render();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->quantity = '';
        $this->image = '';
        $this->newImage = null;
        $this->category_id = '';
        $this->supplier_id = '';
    }

    public function render()
    {
        $query = Product::with(['category', 'supplier']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('supplier', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $products = $query->paginate(10);

        return view('livewire.products-list', [
            'products' => $products,
        ]);
    }
}
