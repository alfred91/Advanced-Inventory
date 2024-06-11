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
    public $applyDiscount = false;

    public $showModal = false;
    public $showCreateModal = false;
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
    public $showConfirmModal = false;
    public $sendSms = false;
    protected $listeners = ['showOrderDetailsFromCustomersList' => 'showOrderDetails'];

    protected $rules = [
        'customerId' => 'required|exists:customers,id',
        'status' => 'required|in:pending,completed,cancelled',
        'orderDate' => 'required|date|before_or_equal:today',
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
        $this->showCreateModal = true;

        $customer = Customer::find($this->customerId);
        $this->applyDiscount = $customer && $customer->role === 'professional';
    }

    public function showOrderDetails($orderId, $isEdit = false)
    {
        $this->resetInputFields();
        $this->isEdit = $isEdit;
        $this->orderId = $orderId;
        $order = Order::with(['customer', 'products'])->findOrFail($orderId);

        $this->customerId = $order->customer_id;
        $this->status = $order->status;
        $this->totalAmount = $order->total_amount;
        $this->orderDate = $order->order_date;
        $this->applyDiscount = $order->customer->role === 'professional';
        $this->selectedProducts = $order->products->mapWithKeys(function ($product) {
            return [$product->id => [
                'quantity' => $product->pivot->quantity,
                'unit_price' => $product->pivot->unit_price,
                'available_quantity' => $product->quantity + $product->pivot->quantity,
                'discount' => $product->discount, // Asegúrate de incluir el descuento
                'price_with_discount' => $this->calculatePriceWithDiscount($product->pivot->unit_price, $product->discount)
            ]];
        })->toArray();

        $this->updateTotalAmount();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showCreateModal = false; // Cerrar el modal de creación si está abierto
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

            $order->status = 'cancelled';
            $order->sendStatusChangeEmail();

            session()->flash('message', 'Order deleted successfully.');
            $this->resetPage();
        } else {
            session()->flash('error', 'Order not found.');
        }
    }

    public function getTranslatedStatus($status)
    {
        $translations = [
            'pending' => 'Pendiente',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];

        return $translations[$status] ?? $status;
    }

    public function getTranslatedRole($role)
    {
        switch ($role) {
            case 'normal':
                return 'Particular';
            case 'professional':
                return 'Profesional';
            default:
                return $role;
        }
    }

    public function saveChanges()
    {
        $this->validate();

        $this->showConfirmModal = true;
    }

    public function confirmSave($sendSms)
    {
        $this->sendSms = $sendSms;

        if ($this->isEdit) {
            $order = Order::findOrFail($this->orderId);

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
                        $price = $this->applyDiscount($productModel);

                        $order->products()->attach($productId, [
                            'quantity' => $product['quantity'],
                            'unit_price' => $price
                        ]);

                        $productModel->quantity -= $product['quantity'];
                        $productModel->save();
                    }
                }
            });

            $this->updateTotalAmount($order);
            $order->total_amount = $this->totalAmount;
            $order->save();

            $order->sendStatusChangeEmail($this->sendSms);

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
                        $price = $this->applyDiscount($productModel);

                        $order->products()->attach($productId, [
                            'quantity' => $product['quantity'],
                            'unit_price' => $price
                        ]);

                        $productModel->quantity -= $product['quantity'];
                        $productModel->save();
                    }
                }

                $this->updateTotalAmount($order);
                $order->total_amount = $this->totalAmount;
                $order->save();

                $order->sendStatusChangeEmail($this->sendSms);

                session()->flash('message', 'Order created successfully.');
            });
        }

        $this->showConfirmModal = false;
        $this->closeModal();
    }

    public function addProduct()
    {
        $this->validate([
            'newProductId' => 'required|exists:products,id',
            'newProductQuantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($this->newProductId);
        $price = $this->applyDiscount($product);

        if (isset($this->selectedProducts[$this->newProductId])) {
            $this->selectedProducts[$this->newProductId]['quantity'] += $this->newProductQuantity;
        } else {
            $this->selectedProducts[$this->newProductId] = [
                'quantity' => $this->newProductQuantity,
                'unit_price' => $price,
                'available_quantity' => $product->quantity,
                'discount' => $product->discount, // Asegúrate de incluir el descuento
                'price_with_discount' => $this->calculatePriceWithDiscount($price, $product->discount)
            ];
        }

        $this->newProductId = null;
        $this->newProductQuantity = 1;
        $this->updateTotalAmount();
    }

    public function updateDiscountStatus()
    {
        $customer = Customer::find($this->customerId);
        $this->applyDiscount = $customer && $customer->role === 'professional';

        // Recalcular los precios
        foreach ($this->selectedProducts as $productId => &$product) {
            $productModel = Product::find($productId);
            $product['unit_price'] = $this->applyDiscount($productModel);
            $product['discount'] = $productModel->discount; // Asegúrate de incluir el descuento
            $product['price_with_discount'] = $this->calculatePriceWithDiscount($product['unit_price'], $productModel->discount);
        }

        $this->updateTotalAmount();
    }

    private function applyDiscount($product)
    {
        $customer = Customer::find($this->customerId);

        if ($customer && $customer->role === 'professional') {
            $this->applyDiscount = true;
            return $product->price * (1 - ($product->discount / 100));
        }

        $this->applyDiscount = false;
        return $product->price;
    }

    private function calculatePriceWithDiscount($price, $discount)
    {
        return number_format($price * (1 - ($discount / 100)), 2, '.', '');
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
            return $this->applyDiscount
                ? $product['quantity'] * $product['unit_price'] * (1 - $product['discount'] / 100)
                : $product['quantity'] * $product['unit_price'];
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
        $this->applyDiscount = false;
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
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('role', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->sortField == 'customer_name') {
            $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.name as customer_name', 'customers.role as customer_role')
                ->orderBy('customers.name', $this->sortDirection);
        } elseif ($this->sortField == 'customer_role') {
            $query->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.role as customer_role')
                ->orderBy('customers.role', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $orders = $query->paginate(10);
        $customers = Customer::all()->sortBy('name');
        $allProducts = Product::all();

        return view('livewire.orders-list', [
            'orders' => $orders,
            'customers' => $customers,
            'allProducts' => $allProducts,
        ]);
    }
}
