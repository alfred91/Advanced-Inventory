<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public $showConfirmationModal = false;
    public $genericCustomer;
    public $orderId;
    public $isLoading = false;
    public $showCustomerModal = false;

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

    public function updateProductQuantity($productId, $quantity)
    {
        if (isset($this->selectedProducts[$productId])) {
            $this->selectedProducts[$productId]['quantity'] += $quantity;
            if ($this->selectedProducts[$productId]['quantity'] <= 0) {
                unset($this->selectedProducts[$productId]);
            }
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
                'order_date' => Carbon::now(),
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

            $this->orderId = $order->id;

            $this->showConfirmationModal = true;
        });
    }

    public function confirmEmailSend($sendEmail)
    {
        if ($sendEmail) {
            $order = Order::find($this->orderId);
            $order->sendStatusChangeEmail();
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
        $this->showConfirmationModal = false;
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

        $products = $productsQuery->orderBy('name')->paginate(16);

        return view('livewire.sales-tpv', [
            'customers' => $customers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
