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

    public $showModal = false;
    public $isEdit = false;
    public $orderId;
    public $customerId;
    public $status = 'pending';
    public $totalAmount = 0;
    public $orderDate;
    public $selectedProducts = [];
    public $newProductId;
    public $newProductQuantity = 1;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $rules = [
        'customerId' => 'required|exists:customers,id',
        'status' => 'required|in:pending,completed,cancelled',
        'orderDate' => 'required|date',
        'selectedProducts.*.quantity' => 'required|integer|min:1',
        'newProductId' => 'nullable|exists:products,id',
        'newProductQuantity' => 'nullable|integer|min:1'
    ];

    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->isLoading = false;
    }

    public function reloadOrders()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showOrderDetails($orderId)
    {
        $this->resetInputFields();
        $this->isEdit = true;
        $this->orderId = $orderId;
        $order = Order::with(['customer', 'products'])->findOrFail($orderId);

        $this->customerId = $order->customer_id;
        $this->status = $order->status;
        $this->totalAmount = $order->total_amount;
        $this->orderDate = $order->order_date;
        $this->selectedProducts = $order->products->mapWithKeys(function ($product) {
            return [$product->id => [
                'quantity' => $product->pivot->quantity,
                'unit_price' => $product->pivot->unit_price,
                'available_quantity' => $product->quantity + $product->pivot->quantity
            ]];
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
            foreach ($order->products as $product) {
                $product->quantity += $product->pivot->quantity;
                $product->save();
            }

            $order->delete();

            // Opcional: enviar correo de cancelación
            $order->status = 'cancelled';
            $order->sendStatusChangeEmail();

            session()->flash('message', 'Order deleted successfully.');
            $this->resetPage();
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function saveChanges()
    {
        $this->validate();

        if ($this->isEdit) {
            $order = Order::findOrFail($this->orderId);

            // Restore the quantities to the products before making changes
            foreach ($order->products as $product) {
                $product->quantity += $product->pivot->quantity;
                $product->save();
            }

            $order->update([
                'customer_id' => $this->customerId,
                'status' => $this->status,
                'order_date' => $this->orderDate,
            ]);

            DB::transaction(function () use ($order) {
                $order->products()->detach();

                foreach ($this->selectedProducts as $productId => $product) {
                    if ($product['quantity'] > 0) {
                        $productModel = Product::findOrFail($productId);
                        if ($product['quantity'] > $productModel->quantity) {
                            session()->flash('error', 'Cantidad no disponible para el producto ' . $productModel->name);
                            return;
                        }
                        $order->products()->attach($productId, [
                            'quantity' => $product['quantity'],
                            'unit_price' => $product['unit_price']
                        ]);

                        $productModel->quantity -= $product['quantity'];
                        $productModel->save();
                    }
                }
            });

            $this->updateTotalAmount($order);
            $order->total_amount = $this->totalAmount;
            $order->save();

            $order->sendStatusChangeEmail(); // Enviar correo

            session()->flash('message', 'Order updated successfully.');
        } else {
            DB::transaction(function () {
                $order = Order::create([
                    'customer_id' => $this->customerId,
                    'order_date' => $this->orderDate,
                    'total_amount' => $this->totalAmount,
                    'status' => $this->status
                ]);

                foreach ($this->selectedProducts as $productId => $product) {
                    if ($product['quantity'] > 0) {
                        $productModel = Product::findOrFail($productId);
                        if ($product['quantity'] > $productModel->quantity) {
                            session()->flash('error', 'Cantidad no disponible para el producto ' . $productModel->name);
                            return;
                        }
                        $order->products()->attach($productId, [
                            'quantity' => $product['quantity'],
                            'unit_price' => $product['unit_price']
                        ]);

                        $productModel->quantity -= $product['quantity'];
                        $productModel->save();
                    }
                }

                $this->updateTotalAmount($order);
                $order->total_amount = $this->totalAmount;
                $order->save();

                $order->sendStatusChangeEmail(); // Enviar correo

                session()->flash('message', 'Order created successfully.');
            });
        }

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
                'unit_price' => $product->price,
                'available_quantity' => $product->quantity
            ];
        }

        $this->newProductId = null;
        $this->newProductQuantity = 1;
        $this->updateTotalAmount();
    }

    public function removeProduct($productId)
    {
        if (isset($this->selectedProducts[$productId])) {
            unset($this->selectedProducts[$productId]);
            $this->updateTotalAmount();
        }
    }

    public function increaseProductQuantity($productId)
    {
        if (isset($this->selectedProducts[$productId])) {
            $productModel = Product::findOrFail($productId);
            if ($this->selectedProducts[$productId]['quantity'] < $productModel->quantity) {
                $this->selectedProducts[$productId]['quantity']++;
                $productModel->quantity--;
                $productModel->save();
                $this->updateTotalAmount();
            } else {
                session()->flash('error', 'Cantidad no disponible para el producto ' . $productModel->name);
            }
        }
    }

    public function decreaseProductQuantity($productId)
    {
        if (isset($this->selectedProducts[$productId])) {
            $this->selectedProducts[$productId]['quantity']--;

            if ($this->selectedProducts[$productId]['quantity'] <= 0) {
                unset($this->selectedProducts[$productId]);
            } else {
                $productModel = Product::findOrFail($productId);
                $productModel->quantity++;
                $productModel->save();
            }

            $this->updateTotalAmount();
        }
    }

    private function updateTotalAmount($order = null)
    {
        $this->totalAmount = number_format(collect($this->selectedProducts)->sum(function ($product) {
            return $product['quantity'] * $product['unit_price'];
        }), 2, '.', '');

        if ($order) {
            $order->total_amount = $this->totalAmount;
            $order->save();
        }
    }

    private function resetInputFields()
    {
        $this->customerId = '';
        $this->status = 'pending';
        $this->totalAmount = 0;
        $this->selectedProducts = [];
        $this->newProductId = null;
        $this->newProductQuantity = 1;
        $this->orderDate = now()->toDateString();
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
    public function render()
    {
        $query = Order::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('total_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('order_date', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

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
