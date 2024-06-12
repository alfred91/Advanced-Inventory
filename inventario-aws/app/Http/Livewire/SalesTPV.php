<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Customer;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;

class SalesTPV extends Component
{
    use WithPagination, WithFileUploads;

    public $isRegistered = null;
    public $search = '';
    public $selectedCustomer = null;
    public $selectedProducts = [];
    public $totalAmount = 0;
    public $paymentMethod = 'cash';
    public $selectedCategory = null;
    public $selectedCategoryName = 'Todas';
    public $selectedCategoryImage = 'storage/categories/todas.png';
    public $showCategories = false;
    public $showSmsModal = false;
    public $genericCustomer;
    public $orderId;
    public $isLoading = false;
    public $showCustomerModal = false;
    public $customerRole = null;


    protected $queryString = ['search'];

    public function mount()
    {
        $this->genericCustomer = Customer::where('name', 'Cliente Genérico')->first();
        $allCategory = Category::where('name', 'Todas')->first();
        if ($allCategory) {
            $this->selectedCategory = $allCategory->id;
            $this->selectedCategoryName = $allCategory->name;
            $this->selectedCategoryImage = $allCategory->image;
        }
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
        $this->customerRole = $this->selectedCustomer->role;
        $this->showCustomerModal = false;
    }

    public function selectGenericCustomer()
    {
        $this->selectedCustomer = $this->genericCustomer;
        $this->isRegistered = false;
        $this->customerRole = null;
    }

    public function selectRole($role)
    {
        $this->customerRole = $role;
        $this->isRegistered = true;
        $this->selectedCustomer = null;
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);

        if (isset($this->selectedProducts[$productId])) {
            if ($this->selectedProducts[$productId]['quantity'] < $product->quantity) {
                $this->selectedProducts[$productId]['quantity']++;
            } else {
                session()->flash('error', 'No hay suficiente stock disponible.');
            }
        } else {
            if ($product->quantity > 0) {
                $price = $product->price;
                $discount = $this->customerRole === 'professional' ? $product->discount : 0;
                $priceWithDiscount = $price * (1 - $discount / 100);

                $this->selectedProducts[$productId] = [
                    'quantity' => 1,
                    'name' => $product->name,
                    'price' => $price,
                    'discount' => $discount,
                    'priceWithDiscount' => $priceWithDiscount,
                ];
            } else {
                session()->flash('error', 'No hay suficiente stock disponible.');
            }
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

    public function updateProductQuantity($productId, $quantity)
    {
        $product = Product::find($productId);

        if (isset($this->selectedProducts[$productId])) {
            $newQuantity = $this->selectedProducts[$productId]['quantity'] + $quantity;

            if ($newQuantity > 0 && $newQuantity <= $product->quantity) {
                $this->selectedProducts[$productId]['quantity'] = $newQuantity;
            } elseif ($newQuantity > $product->quantity) {
                session()->flash('error', 'No hay suficiente stock disponible.');
            } else {
                unset($this->selectedProducts[$productId]);
            }

            $this->updateTotalAmount();
        }
    }

    public function updateTotalAmount()
    {
        $this->totalAmount = collect($this->selectedProducts)->sum(function ($product) {
            if ($this->customerRole === 'professional') {
                return $product['quantity'] * ($product['price'] * (1 - ($product['discount'] / 100)));
            } else {
                return $product['quantity'] * $product['price'];
            }
        });
    }

    public function placeOrder()
    {
        DB::transaction(function () {
            // Crear el pedido
            $order = Order::create([
                'customer_id' => $this->selectedCustomer ? $this->selectedCustomer->id : null,
                'total_amount' => $this->totalAmount,
                'status' => 'pending', // Estado inicial pendiente
                'payment_method' => $this->paymentMethod,
                'order_date' => Carbon::now(),
            ]);

            // Adjuntar productos al pedido
            foreach ($this->selectedProducts as $productId => $product) {
                $order->products()->attach($productId, [
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['price'],
                ]);

                // Actualizar la cantidad del producto
                $productModel = Product::find($productId);
                $productModel->quantity -= $product['quantity'];
                $productModel->save();
            }

            $this->orderId = $order->id;

            // Enviar email de confirmación del pedido
            $order->sendStatusChangeEmail();

            // Calcular el total a enviar a PayPal
            $totalAmountToSend = $this->customerRole === 'professional'
                ? $this->calculateTotalAmountWithDiscount($order)
                : $this->totalAmount;

            // Crear el pedido en PayPal
            if ($this->paymentMethod === 'paypal') {
                $paypalService = app(PayPalService::class);
                $response = $paypalService->createOrder($totalAmountToSend);

                if ($response) {
                    return redirect($response->result->links[1]->href);
                } else {
                    return redirect()->route('orders.index')->with('error', 'Hubo un problema al crear el pago con PayPal.');
                }
            } else {
                $order->update(['status' => 'completed']);
                if ($this->selectedCustomer && $this->selectedCustomer->id !== $this->genericCustomer->id) {
                    $this->showSmsModal = true;
                } else {
                    $this->resetOrder();
                }
            }
        });
    }

    public function calculateTotalAmountWithDiscount($order)
    {
        $total = 0;

        foreach ($order->products as $product) {
            $discount = $product->discount;
            $total += $product->pivot->quantity * ($product->pivot->unit_price * (1 - ($discount / 100)));
        }

        return number_format($total, 2, '.', '');
    }


    public function confirmSmsSend($sendSms)
    {
        if ($sendSms) {
            $order = Order::find($this->orderId);
            $order->sendStatusChangeEmail(true);
        }

        $this->resetOrder();
        $this->showSmsModal = false;
    }


    public function resetOrder()
    {
        $this->selectedCustomer = null;
        $this->selectedProducts = [];
        $this->totalAmount = 0;
        $this->paymentMethod = 'cash';
        $this->isRegistered = null;
        $this->customerRole = null;
        $this->showSmsModal = false;
    }

    public function toggleCategories()
    {
        $this->showCategories = !$this->showCategories;
    }

    public function selectCategory($categoryId, $categoryName)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $this->selectedCategory = $categoryId;
            $this->selectedCategoryName = $categoryName;
            $this->selectedCategoryImage = $category->image;
        } else {
            $this->selectedCategory = null;
            $this->selectedCategoryName = 'Todas';
            $this->selectedCategoryImage = 'storage/categories/todas.png';
        }
        $this->showCategories = false;
        $this->resetPage();
    }

    public function render()
    {
        $customersQuery = Customer::query();

        if ($this->search) {
            $customersQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->customerRole) {
            $customersQuery->where('role', $this->customerRole);
        }

        $customers = $customersQuery->orderBy('name')->paginate(10);

        $categories = Category::all();

        $productsQuery = Product::query()->where('quantity', '>', 0);
        if ($this->selectedCategory) {
            $productsQuery->where('category_id', $this->selectedCategory);
        }

        $products = $productsQuery->orderBy('name')->paginate(12);

        return view('livewire.sales-tpv', [
            'customers' => $customers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
