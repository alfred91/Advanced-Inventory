<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class EditOrder extends Component
{
    public $orderId;
    public $customerId;
    public $status;
    public $totalAmount;
    public $selectedProducts = [];
    public $newProductId;
    public $newProductQuantity = 1;
    public $showModal = false;

    protected $rules = [
        'customerId' => 'required',
        'status' => 'required|in:pending,completed,cancelled',
        'selectedProducts.*.quantity' => 'required|integer|min:0',
        'newProductId' => 'nullable|exists:products,id',
        'newProductQuantity' => 'required|integer|min:1'
    ];

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $order = Order::with(['customer', 'products'])->find($this->orderId);
        $this->customerId = $order->customer_id;
        $this->status = $order->status;
        $this->totalAmount = $order->total_amount;
        $this->selectedProducts = $order->products->mapWithKeys(function ($product) {
            return [$product->id => ['quantity' => $product->pivot->quantity, 'unit_price' => $product->pivot->unit_price]];
        })->toArray();
    }

    public function render()
    {
        $customers = Customer::all();
        $allProducts = Product::all();
        return view('livewire.edit-order', [
            'customers' => $customers,
            'allProducts' => $allProducts
        ]);
    }

    public function addProduct()
    {
        $this->validate([
            'newProductId' => 'required|exists:products,id',
            'newProductQuantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($this->newProductId);

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

    public function updateOrder()
    {
        $this->validate();

        $order = Order::find($this->orderId);
        $order->update([
            'customer_id' => $this->customerId,
            'status' => $this->status,
        ]);

        foreach ($this->selectedProducts as $productId => $product) {
            if ($product['quantity'] > 0) {
                $order->products()->syncWithoutDetaching([
                    $productId => ['quantity' => $product['quantity'], 'unit_price' => $product['unit_price']]
                ]);
            } else {
                $order->products()->detach($productId);
            }
        }

        $this->updateTotalAmount();

        $order->total_amount = $this->totalAmount;
        $order->save();

        $this->closeModal();
        return redirect()->to('/orders');
    }

    public function updateTotalAmount()
    {
        $this->totalAmount = collect($this->selectedProducts)->sum(function ($product) {
            return $product['quantity'] * $product['unit_price'];
        });
    }

    public function openModal()
    {
        $this->loadOrder();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}
