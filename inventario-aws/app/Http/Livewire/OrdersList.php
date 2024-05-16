<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrdersList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOrder = null;
    public $showModal = false;
    public $isLoading = false;


    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'products'])->find($orderId);
        if ($this->selectedOrder) {
            $this->showModal = true;
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    public function reloadOrders()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->delete();
            session()->flash('message', 'Order deleted successfully.');
            $this->showModal = false;
            $this->reset('selectedOrder');
            $this->resetPage();
            return redirect('/orders'); //ARREGLO TEMPORAL PARA CARGAR BIEN LOS PEDIDOS AL BORRAR
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function render()
    {
        $query = Order::query();

        if ($this->search) {
            $query->whereHas('customer', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })->orWhere('id', 'like', '%' . $this->search . '%')
                ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%');
        }

        $orders = $query->paginate(10);
        return view('livewire.orders-list', [
            'orders' => $orders
        ]);
    }
}
