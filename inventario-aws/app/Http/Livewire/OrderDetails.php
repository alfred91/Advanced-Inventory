<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderDetails extends Component
{
    public $order;
    public $showModal = false;

    public function loadOrder($orderId)
    {
        $this->order = Order::with(['customer', 'products'])->find($orderId);
        if ($this->order) {
            $this->showModal = true;
        } else {
            $this->addError('orderNotFound', 'No se pudo encontrar el pedido.');
            $this->showModal = false;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.order-details');
    }
}
