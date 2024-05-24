<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class StockManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->isLoading = false;
    }

    public function reloadProducts()
    {
        $this->isLoading = true;
        $this->resetPage();
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

    public function incrementStock($productId)
    {
        $product = Product::findOrFail($productId);
        $product->increment('quantity');
    }

    public function decrementStock($productId)
    {
        $product = Product::findOrFail($productId);
        if ($product->quantity > 0) {
            $product->decrement('quantity');
        }
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $products = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.stock-manager', [
            'products' => $products,
        ]);
    }
}
