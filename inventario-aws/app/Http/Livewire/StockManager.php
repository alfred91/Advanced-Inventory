<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class StockManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $showLowStockModal = false;
    public $lowStockProducts = [];
    public $selectedProduct = null;
    public $customQuantity = 1;
    public $incidentReason = '';
    public $incidentDescription = '';

    public $showCustomQuantityModal = false;
    public $showIncidentModal = false;
    public $hasLowStock = false;
    public $showImageModal = false;
    public $currentImage = '';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = ['productUpdated' => 'checkLowStock'];

    public function mount()
    {
        $this->checkLowStock();
    }

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
        if ($field === 'isStockBelowMinimum') {
            $field = 'stock_alert';
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function incrementStock($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        $product->increment('quantity', $quantity);
        $this->dispatch('productUpdated');
    }

    public function decrementStock($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        if ($product->quantity >= $quantity) {
            $product->decrement('quantity', $quantity);
            $this->dispatch('productUpdated');
        }
    }

    public function checkLowStock()
    {
        $this->lowStockProducts = Product::whereColumn('quantity', '<=', 'minimum_stock')->get();
        $this->hasLowStock = $this->lowStockProducts->isNotEmpty();
    }

    public function openLowStockModal()
    {
        $this->checkLowStock();
        $this->showLowStockModal = true;
    }

    public function closeLowStockModal()
    {
        $this->showLowStockModal = false;
    }

    public function openCustomQuantityModal($productId)
    {
        $this->selectedProduct = Product::findOrFail($productId);
        $this->customQuantity = 1;
        $this->showCustomQuantityModal = true;
    }

    public function closeCustomQuantityModal()
    {
        $this->showCustomQuantityModal = false;
    }

    public function addCustomQuantity()
    {
        $this->validate(['customQuantity' => 'required|integer|min:1']);
        $this->incrementStock($this->selectedProduct->id, $this->customQuantity);
        $this->closeCustomQuantityModal();
    }

    public function openIncidentModal($productId)
    {
        $this->selectedProduct = Product::findOrFail($productId);
        $this->incidentReason = '';
        $this->customQuantity = 1;
        $this->showIncidentModal = true;
    }

    public function closeIncidentModal()
    {
        $this->showIncidentModal = false;
    }

    public function reportIncident()
    {
        $this->validate([
            'customQuantity' => 'required|integer|min:1|max:' . $this->selectedProduct->quantity,
            'incidentReason' => 'required|string|max:255',
            'incidentDescription' => 'nullable|string|max:1000',
        ]);

        $this->decrementStock($this->selectedProduct->id, $this->customQuantity);

        $product = $this->selectedProduct;
        $quantity = $this->customQuantity;
        $reason = $this->incidentReason;
        $description = $this->incidentDescription;

        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'administrativo');
        })->get();

        foreach ($admins as $admin) {
            $details = [
                'title' => 'Incidencia de Stock',
                'body' => "Se ha registrado una incidencia en el inventario.\n\nProducto: {$product->name}\nCantidad: {$quantity}\nMotivo: {$reason}\nDescripción: {$description}\n\nPor favor, revisa la situación lo antes posible."
            ];

            Mail::raw($details['body'], function ($message) use ($details, $admin) {
                $message->to($admin->email)
                    ->subject($details['title']);
            });
        }
        $this->closeIncidentModal();
    }

    public function openImageModal($imageUrl)
    {
        $this->currentImage = $imageUrl;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->currentImage = '';
    }

    public function render()
    {
        $query = Product::query()->selectRaw('products.*, (quantity <= minimum_stock) as stock_alert');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->sortField === 'stock_alert') {
            $query->orderByRaw('stock_alert ' . ($this->sortDirection === 'asc' ? 'asc' : 'desc'));
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $products = $query->paginate(10);

        return view('livewire.stock-manager', [
            'products' => $products,
        ]);
    }
}
