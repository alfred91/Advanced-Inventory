<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomersList extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;

    // Propiedades Crear/Editar Clientes
    public $showModal = false;
    public $isEdit = false;
    public $customerId;
    public $dni;
    public $name;
    public $email;
    public $phone_number;
    public $address;
    public $role; // Añadir el rol

    // Ordenación
    public $sortField = 'id';
    public $sortDirection = 'asc';

    // Properties for viewing customer orders
    public $showOrdersModal = false;
    public $orders = [];

    protected function rules()
    {
        return [
            'dni' => 'required|string|max:20|unique:customers,dni,' . $this->customerId,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $this->customerId,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'role' => 'required|in:normal,professional', // Validación para el rol
        ];
    }

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
        $this->dni = '';
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->address = '';
        $this->role = 'normal'; // Inicializar el rol
        $this->customerId = null;
    }

    private function fillCustomerData($customer)
    {
        $this->customerId = $customer->id;
        $this->dni = $customer->dni;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->role = $customer->role; // Asignar el rol
    }

    private function getCustomerData()
    {
        return [
            'dni' => $this->dni,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'role' => $this->role, // Incluir el rol
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
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%');
            });
        }

        $query->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->select('customers.id', 'customers.dni', 'customers.name', 'customers.email', 'customers.phone_number', 'customers.address', 'customers.role', DB::raw('COUNT(orders.id) as orders_count'))
            ->groupBy('customers.id', 'customers.dni', 'customers.name', 'customers.email', 'customers.phone_number', 'customers.address', 'customers.role')
            ->orderBy($this->sortField === 'orders_count' ? DB::raw('orders_count') : $this->sortField, $this->sortDirection);

        $customers = $query->paginate(10);

        return view('livewire.customers-list', [
            'customers' => $customers,
        ]);
    }
}
