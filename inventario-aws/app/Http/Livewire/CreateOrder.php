<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CreateOrder extends Component
{
    public $customers, $products;
    public $selectedCustomerId;
    public $orderProducts = [];
    public $showModal = false;

    protected $rules = [
        'selectedCustomerId' => 'required|exists:customers,id',
        'orderProducts.*.quantity' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->customers = Customer::all();
        $this->products = Product::pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.create-order');
    }

    public function openModal()
    {
        $this->reset(['selectedCustomerId', 'orderProducts']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function addProduct($productId)
    {
        if (!array_key_exists($productId, $this->orderProducts)) {
            $this->orderProducts[$productId] = ['quantity' => 1];
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        if (isset($this->orderProducts[$productId])) {
            $this->orderProducts[$productId]['quantity'] = $quantity;
        }
    }

    public function removeProduct($productId)
    {
        unset($this->orderProducts[$productId]);
    }

    public function saveOrder()
    {
        $this->validate();

        DB::transaction(function () {
            $order = Order::create([
                'customer_id' => $this->selectedCustomerId,
                'order_date' => now(),
                'total_amount' => array_sum(array_map(function ($quantity, $productId) {
                    return Product::find($productId)->price * $quantity;
                }, array_column($this->orderProducts, 'quantity'), array_keys($this->orderProducts))),
                'status' => 'pending' // assuming a default status
            ]);

            foreach ($this->orderProducts as $productId => $product) {
                $order->products()->attach($productId, [
                    'quantity' => $product['quantity'],
                    'unit_price' => Product::find($productId)->price
                ]);
            }
        });

        $this->dispatch('order-created', ['message' => 'Order created successfully!']);
        $this->closeModal();
        $this->reset('selectedCustomerId', 'orderProducts');
    }
}
