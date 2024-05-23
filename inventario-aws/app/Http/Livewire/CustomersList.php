<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Order;

class CustomersList extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;

    // Propiedades Crear/Editar Clientes
    public $showModal = false;
    public $isEdit = false;
    public $customerId;
    public $name;
    public $email;
    public $phone_number;
    public $address;

    // Ordenación
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Properties for viewing customer orders
    public $showOrdersModal = false;
    public $orders = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    // Lifecycle hooks
    public function mount()
    {
        // Initialize properties if needed
    }

    // Updating methods
    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->isLoading = false;
    }

    // Reload the customer list
    public function reloadCustomers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    // Modal methods
    public function showCreateModal()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetInputFields();
        $this->isEdit = true;
        $customer = Customer::findOrFail($id);
        $this->fillCustomerData($customer);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function closeOrdersModal()
    {
        $this->showOrdersModal = false;
        $this->orders = [];
    }

    // Customer methods
    public function saveCustomer()
    {
        $this->validate();

        if ($this->isEdit) {
            $customer = Customer::findOrFail($this->customerId);
            $customer->update($this->getCustomerData());
            session()->flash('message', 'Cliente actualizado correctamente.');
        } else {
            Customer::create($this->getCustomerData());
            session()->flash('message', 'Cliente creado correctamente.');
        }

        $this->showModal = false;
        $this->resetInputFields();
        $this->reloadCustomers();
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Cliente eliminado correctamente.');
        $this->resetPage();
    }

    // Order methods
    public function showCustomerOrders($customerId)
    {
        $this->customerId = $customerId;
        $this->orders = Order::where('customer_id', $customerId)->with('products')->get();
        $this->showOrdersModal = true;
    }

    // Sorting methods
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // Helper methods
    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->address = '';
    }

    private function fillCustomerData($customer)
    {
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
    }

    private function getCustomerData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ];
    }

    public function render()
    {
        $query = Customer::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $customers = $query->paginate(10);

        return view('livewire.customers-list', [
            'customers' => $customers,
        ]);
    }
}
