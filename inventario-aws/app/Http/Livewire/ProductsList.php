<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;
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
        'minimum_stock' => 'required|integer|min:0',
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

    public function loadProduct($productId)
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
        $this->minimum_stock = $product->minimum_stock;
    }
    //FUNCION PARA GUARDAR PRODUCTO, SIN BORRAR LA IMAGEN POR DEFECTO,
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
            if ($this->isEdit && $product->image && $product->image !== 'products/product.svg') {
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

    //FUNCION PARA BORRAR UN PRODUCTO, SIN BORRAR LA IMAGEN POR DEFECTO
    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            if ($product->image && $product->image !== 'products/product.svg') {
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
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
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

        $query->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate(10);

        return view('livewire.products-list', [
            'products' => $products,
        ]);
    }
}
