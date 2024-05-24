<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesTPV extends Component
{
    use WithPagination;

    public $isRegistered = null;
    public $search = '';
    public $selectedCustomer = null;
    public $selectedProducts = [];
    public $totalAmount = 0;
    public $paymentMethod = 'cash';
    public $selectedCategory = null;
    public $showCustomerModal = false;
    public $showConfirmationModal = false;
    public $genericCustomer;
    public $orderId;
    public $isLoading = false;

    protected $queryString = ['search'];

    public function mount()
    {
        $this->genericCustomer = Customer::where('name', 'Cliente Genérico')->first();
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

    public function reloadCustomers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomer = Customer::find($customerId);
        $this->showCustomerModal = false;
    }

    public function selectGenericCustomer()
    {
        $this->selectedCustomer = $this->genericCustomer;
        $this->isRegistered = false;
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);

        if (isset($this->selectedProducts[$productId])) {
            $this->selectedProducts[$productId]['quantity']++;
        } else {
            $this->selectedProducts[$productId] = [
                'quantity' => 1,
                'name' => $product->name,
                'price' => $product->price,
            ];
        }

        $this->updateTotalAmount();
    }

    public function removeProduct($productId)
    {
        if (isset($this->selectedProducts[$productId])) {
            unset($this->selectedProducts[$productId]);
            $this->updateTotalAmount();
        }
    }

    public function updateTotalAmount()
    {
        $this->totalAmount = collect($this->selectedProducts)->sum(function ($product) {
            return $product['quantity'] * $product['price'];
        });
    }

    public function placeOrder()
    {
        DB::transaction(function () {
            $order = Order::create([
                'customer_id' => $this->selectedCustomer ? $this->selectedCustomer->id : null,
                'total_amount' => $this->totalAmount,
                'status' => 'completed',
                'payment_method' => $this->paymentMethod,
                'order_date' => Carbon::now(), // Asignar la fecha actual
            ]);

            foreach ($this->selectedProducts as $productId => $product) {
                $order->products()->attach($productId, [
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['price'],
                ]);

                $productModel = Product::find($productId);
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }

            $this->orderId = $order->id; // Guardar el ID del pedido recién creado

            $this->showConfirmationModal = true; // Mostrar el modal de confirmación
        });
    }

    public function confirmEmailSend($sendEmail)
    {
        if ($sendEmail) {
            $order = Order::find($this->orderId);
            $order->sendStatusChangeEmail(); // Enviar correo
        }

        $this->resetOrder();
    }

    public function resetOrder()
    {
        $this->selectedCustomer = null;
        $this->selectedProducts = [];
        $this->totalAmount = 0;
        $this->paymentMethod = 'cash';
        $this->isRegistered = null;
        $this->showConfirmationModal = false; // Asegurarse de que el modal esté oculto
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone_number', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $categories = Category::all();

        $productsQuery = Product::query();
        if ($this->selectedCategory) {
            $productsQuery->where('category_id', $this->selectedCategory);
        }

        $products = $productsQuery->orderBy('name')->paginate(16); // Ordenar por nombre

        return view('livewire.sales-tpv', [
            'customers' => $customers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
