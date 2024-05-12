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
        'selectedCustomerId' => 'required',
        'orderProducts.*.quantity' => 'required|integer|min:1',
        'orderProducts.*.product_id' => 'required|exists:products,id'
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
            $this->orderProducts[$productId] = ['quantity' => 1, 'product_id' => $productId];
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
                'total_amount' => array_sum(array_map(function ($product) {
                    return Product::find($product['product_id'])->price * $product['quantity'];
                }, $this->orderProducts)),
                'status' => 'pending' // assuming a default status
            ]);

            foreach ($this->orderProducts as $product) {
                $order->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'unit_price' => Product::find($product['product_id'])->price
                ]);
            }
        });

        $this->dispatchBrowserEvent('order-created', ['message' => 'Order created successfully!']);
        $this->closeModal();
        $this->reset('selectedCustomerId', 'orderProducts');
    }
}
