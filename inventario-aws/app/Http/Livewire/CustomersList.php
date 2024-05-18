<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomersList extends Component
{
    use WithPagination;

    public $search = '';
    public $isLoading = false;

    // Editar cliente
    public $showModal = false;
    public $isEdit = false;
    public $customerId;
    public $name;
    public $email;
    public $phone_number;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ];

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->resetPage();
        }
        $this->isLoading = false;
    }

    public function reloadCustomers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

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
        $this->customerId = $id;
        $customer = Customer::findOrFail($id);
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->showModal = true;
    }

    public function saveCustomer()
    {
        $this->validate();

        if ($this->isEdit) {
            $customer = Customer::findOrFail($this->customerId);
            $customer->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
            ]);
        } else {
            Customer::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
            ]);
        }

        session()->flash('message', $this->isEdit ? 'Cliente actualizado correctamente.' : 'Cliente creado correctamente.');
        $this->showModal = false;
        $this->resetInputFields();
        $this->render();
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Cliente eliminado correctamente.');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->address = '';
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

        $customers = $query->paginate(10);

        return view('livewire.customers-list', [
            'customers' => $customers,
        ]);
    }
}
