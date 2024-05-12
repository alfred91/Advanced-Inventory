<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class EditOrder extends Component
{
    use WithFileUploads;

    public $order;
    public $orderId, $customerId, $products = [], $selectedProducts = [];
    public $showModal = false;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['customer', 'products'])->find($this->orderId);
        $this->customerId = $this->order->customer_id;
        $this->selectedProducts = $this->order->products->pluck('pivot.quantity', 'id');
    }

    public function render()
    {
        $customers = Customer::all();
        $allProducts = Product::all();
        return view('livewire.edit-order', [
            'order' => $this->order,
            'customers' => $customers,
            'allProducts' => $allProducts
        ]);
    }
    public function updateOrder()
    {
        // Validación de campos
        $this->validate([
            'customerId' => 'required',
            //'order.total_amount' => 'required|numeric',
            'order.status' => 'required|in:pending,completed,cancelled',
        ]);

        // Actualización del pedido
        $this->order->update([
            'customer_id' => $this->customerId,
            'total_amount' => $this->order->total_amount,
            'status' => $this->order->status,

        ]);

        // Actualización de detalles
        foreach ($this->selectedProducts as $productId => $quantity) {
            if ($quantity > 0) {
                $this->order->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
            } else {
                $this->order->products()->detach($productId);
            }
        }

        $this->dispatch('order-updated', ['message' => 'Order has been updated successfully!']);
        $this->closeModal();
        return redirect()->to('/orders');
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
