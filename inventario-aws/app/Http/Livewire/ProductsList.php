<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categories;
    public $suppliers;
    public $isLoading = false;

    // Estado del modal
    public $showModal = false;
    public $isEdit = false;

    // Propiedades del producto
    public $productId;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $newImage;
    public $category_id;
    public $supplier_id;
    public $minimum_stock;
    public $discount; // Nuevo campo de descuento
    public $showImageModal = false;
    public $currentImage = null;

    // Ordenamiento
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'minimum_stock' => 'required|integer|min:1',
        'discount' => 'nullable|integer|min:0|max:100',
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

    public function openModal($isEdit = false, $productId = null)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isEdit = $isEdit;

        if ($isEdit) {
            $this->loadProduct($productId);
        } else {
            $this->resetInputFields();
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function openImageModal($imageUrl)
    {
        $this->currentImage = $imageUrl;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = null;
    }

    public function loadProduct($productId)
    {
        $this->productId = $productId;
        $product = Product::findOrFail($productId);

        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->quantity = $product->quantity;
        $this->image = $product->image ? Storage::url($product->image) : null;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->minimum_stock = $product->minimum_stock;
        $this->discount = $product->discount;
    }

    public function saveProduct()
    {
        $validatedData = $this->validate();

        if ($this->isEdit) {
            $product = Product::findOrFail($this->productId);
            $product->update($validatedData);
        } else {
            $product = Product::create($validatedData);
        }

        if ($this->newImage) {
            if ($this->isEdit && $product->image && $product->image !== 'products/product.png') {
                Storage::delete('public/' . $product->image);
            }
            $imageName = $this->newImage->store('products', 'public');
            $product->image = $imageName;
            $product->save();
        }

        session()->flash('message', $this->isEdit ? 'Producto actualizado correctamente.' : 'Producto creado correctamente.');
        $this->closeModal();
        $this->resetPage();
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            if ($product->image && $product->image !== 'products/product.png') {
                Storage::delete('public/' . $product->image);
            }
            $product->delete();
            $this->resetPage();
        }
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
        $this->minimum_stock = 100;
        $this->discount = '';
    }

    public function sortBy($field)
    {
        if ($field === 'isStockBelowMinimum') {
            $field = 'stock_alert';
        }

        if ($field === 'quantity_minimum') {
            $field = 'quantity';
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $query = Product::with(['category', 'supplier'])
            ->selectRaw('products.*, (quantity <= minimum_stock) as stock_alert');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%')
                    ->orWhere('quantity', 'like', '%' . $this->search . '%')
                    ->orWhere('price', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('supplier', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->sortField === 'stock_alert') {
            $query->orderByRaw('stock_alert ' . ($this->sortDirection === 'asc' ? 'asc' : 'desc'));
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $products = $query->paginate(10);

        return view('livewire.products-list', [
            'products' => $products,
        ]);
    }
}
