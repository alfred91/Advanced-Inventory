<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SuppliersList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isLoading = false;

    // Editar proveedor
    public $showModal = false;
    public $supplierId;
    public $name;
    public $email;
    public $phone_number;
    public $address;
    public $image;
    public $newImage;

    // Productos del proveedor
    public $products = [];
    public $newProductName;
    public $newProductDescription;
    public $newProductPrice;
    public $newProductQuantity;
    public $newProductCategoryId;
    public $categories;

    // Ordenamiento
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $listeners = ['supplierUpdated' => '$refresh', 'supplierCreated' => '$refresh'];
    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $this->supplierId,
            'phone_number' => 'required|string',
            'address' => 'required|string|max:500',
            'newImage' => 'nullable|image|max:2048',
            'products.*.price' => 'required|numeric|min:0',
            'newProductName' => 'nullable|string|max:255',
            'newProductDescription' => 'nullable|string',
            'newProductPrice' => 'nullable|numeric|min:0',
            'newProductQuantity' => 'nullable|integer|min:0',
            'newProductCategoryId' => 'nullable|exists:categories,id',
        ];
    }

    public function mount()
    {
        $this->categories = Category::all();
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

    public function reloadSuppliers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function editSupplier($supplierId)
    {
        $this->supplierId = $supplierId;
        $supplier = Supplier::with('products')->findOrFail($supplierId);

        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone_number = $supplier->phone_number;
        $this->address = $supplier->address;
        $this->image = $supplier->image;
        $this->products = $supplier->products->toArray();

        $this->showModal = true;
    }

    public function deleteSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            if ($supplier->image && $supplier->image !== 'suppliers/company.svg') {
                Storage::delete('public/' . $supplier->image);
            }
            $supplier->delete();
            $this->resetPage();
        }
    }

    public function saveChanges()
    {
        $this->validate();

        $supplier = Supplier::findOrFail($this->supplierId);
        $supplier->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ]);

        if ($this->newImage) {
            if ($supplier->image && $supplier->image !== 'suppliers/company.svg') {
                Storage::delete('public/' . $supplier->image);
            }
            $imageName = $this->newImage->store('suppliers', 'public');
            $supplier->image = $imageName;
            $supplier->save();
        }

        foreach ($this->products as $product) {
            $productModel = Product::findOrFail($product['id']);
            $productModel->update([
                'price' => $product['price'],
            ]);
        }

        session()->flash('message', 'Proveedor actualizado correctamente.');
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->address = '';
        $this->image = '';
        $this->newImage = null;
        $this->products = [];
        $this->newProductName = null;
        $this->newProductDescription = null;
        $this->newProductPrice = null;
        $this->newProductQuantity = null;
        $this->newProductCategoryId = null;
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

    public function addProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductDescription' => 'required|string',
            'newProductPrice' => 'required|numeric|min:0',
            'newProductQuantity' => 'required|integer|min:0',
            'newProductCategoryId' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $this->newProductName,
            'description' => $this->newProductDescription,
            'price' => $this->newProductPrice,
            'quantity' => $this->newProductQuantity,
            'category_id' => $this->newProductCategoryId,
            'supplier_id' => $this->supplierId,
        ]);

        $this->products[] = $product->toArray();
        $this->resetNewProductFields();
    }

    private function resetNewProductFields()
    {
        $this->newProductName = '';
        $this->newProductDescription = '';
        $this->newProductPrice = '';
        $this->newProductQuantity = '';
        $this->newProductCategoryId = '';
    }

    public function removeProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['supplier_id' => null]);

        $this->products = array_filter($this->products, function ($product) use ($productId) {
            return $product['id'] != $productId;
        });
    }

    public function render()
    {
        $query = Supplier::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $suppliers = $query->paginate(10);

        return view('livewire.suppliers-list', [
            'suppliers' => $suppliers,
        ]);
    }
}
