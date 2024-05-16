<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;

    public $search = '';
    public $categories;
    public $suppliers;
    public $editingProductId = null;
    public $isLoading = false;

    protected $listeners = ['productDeleted' => 'render', 'productCreated' => 'render'];
    protected $queryString = ['search'];

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
        $this->editingProductId = $productId;
        $this->dispatchBrowserEvent('edit-product-modal', ['productId' => $productId]); // Dispatch browser event
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->delete();
            $this->resetPage();
            $this->dispatchBrowserEvent('productDeleted');
        }
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
