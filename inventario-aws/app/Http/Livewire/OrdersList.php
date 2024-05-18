<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrdersList extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;

    // Variables para editar pedidos
    public $showModal = false;
    public $orderId;
    public $customerId;
    public $status;
    public $totalAmount;
    public $selectedProducts = [];
    public $newProductId;
    public $newProductQuantity = 1;

    protected $queryString = ['search'];

    protected $rules = [
        'customerId' => 'required|exists:customers,id',
        'status' => 'required|in:pending,completed,cancelled',
        'selectedProducts.*.quantity' => 'required|integer|min:0',
        'newProductId' => 'nullable|exists:products,id',
        'newProductQuantity' => 'required|integer|min:1'
    ];

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

    public function reloadOrders()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function showOrderDetails($orderId)
    {
        $this->orderId = $orderId;
        $order = Order::with(['customer', 'products'])->findOrFail($orderId);

        $this->customerId = $order->customer_id;
        $this->status = $order->status;
        $this->totalAmount = $order->total_amount;
        $this->selectedProducts = $order->products->mapWithKeys(function ($product) {
            return [$product->id => ['quantity' => $product->pivot->quantity, 'unit_price' => $product->pivot->unit_price]];
        })->toArray();

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->delete();
            session()->flash('message', 'Order deleted successfully.');
            $this->resetPage();
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function saveChanges()
    {
        $this->validate();

        $order = Order::findOrFail($this->orderId);
        $order->update([
            'customer_id' => $this->customerId,
            'status' => $this->status,
        ]);

        // Update products in the order
        DB::transaction(function () use ($order) {
            $order->products()->detach();
            foreach ($this->selectedProducts as $productId => $product) {
                if ($product['quantity'] > 0) {
                    $order->products()->attach($productId, [
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price']
                    ]);
                } else {
                    unset($this->selectedProducts[$productId]);
                }
            }
        });

        $this->updateTotalAmount($order);
        $order->total_amount = $this->totalAmount;
        $order->save();

        session()->flash('message', 'Order updated successfully.');
        $this->closeModal();
    }

    public function addProduct()
    {
        $this->validate([
            'newProductId' => 'required|exists:products,id',
            'newProductQuantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($this->newProductId);
        if (isset($this->selectedProducts[$this->newProductId])) {
            $this->selectedProducts[$this->newProductId]['quantity'] += $this->newProductQuantity;
        } else {
            $this->selectedProducts[$this->newProductId] = [
                'quantity' => $this->newProductQuantity,
                'unit_price' => $product->price
            ];
        }

        $this->newProductId = null;
        $this->newProductQuantity = 1;
        $this->updateTotalAmount();
    }

    public function removeProduct($productId)
    {
        unset($this->selectedProducts[$productId]);
        $this->updateTotalAmount();
    }

    private function updateTotalAmount($order = null)
    {
        $this->totalAmount = collect($this->selectedProducts)->sum(function ($product) {
            return $product['quantity'] * $product['unit_price'];
        });

        if ($order) {
            $order->total_amount = $this->totalAmount;
            $order->save();
        }
    }

    private function resetInputFields()
    {
        $this->customerId = '';
        $this->status = '';
        $this->totalAmount = 0;
        $this->selectedProducts = [];
        $this->newProductId = null;
        $this->newProductQuantity = 1;
    }

    public function render()
    {
        $query = Order::with('customer');

        if ($this->search) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
        }

        $orders = $query->paginate(10);
        $customers = Customer::all();
        $allProducts = Product::all();

        return view('livewire.orders-list', [
            'orders' => $orders,
            'customers' => $customers,
            'allProducts' => $allProducts,
        ]);
    }
}
