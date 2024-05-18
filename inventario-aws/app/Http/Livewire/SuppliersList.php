<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SuppliersList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isLoading = false;

    // Editar proveedor
    public $showModal = false;
    public $supplierId;
    public $name;
    public $email;
    public $phone_number;
    public $address;
    public $image;
    public $newImage;

    protected $listeners = ['supplierUpdated' => '$refresh', 'supplierCreated' => '$refresh'];
    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $this->supplierId,
            'phone_number' => 'required|string',
            'address' => 'required|string|max:500',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

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

    public function reloadSuppliers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function editSupplier($supplierId)
    {
        $this->supplierId = $supplierId;
        $supplier = Supplier::findOrFail($supplierId);

        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone_number = $supplier->phone_number;
        $this->address = $supplier->address;
        $this->image = $supplier->image;

        $this->showModal = true;
    }

    public function deleteSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            if ($supplier->image && $supplier->image !== 'suppliers/company.svg') {
                Storage::delete('public/' . $supplier->image);
            }
            $supplier->delete();
            $this->resetPage();
        }
    }

    public function saveChanges()
    {
        $this->validate();

        $supplier = Supplier::findOrFail($this->supplierId);
        $supplier->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
        ]);

        if ($this->newImage) {
            if ($supplier->image && $supplier->image !== 'suppliers/company.svg') {
                Storage::delete('public/' . $supplier->image);
            }
            $imageName = $this->newImage->store('suppliers', 'public');
            $supplier->image = $imageName;
            $supplier->save();
        }

        session()->flash('message', 'Proveedor actualizado correctamente.');
        $this->showModal = false;
        $this->resetInputFields();
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
        $this->image = '';
        $this->newImage = null;
    }

    public function render()
    {
        $query = Supplier::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $suppliers = $query->paginate(10);

        return view('livewire.suppliers-list', [
            'suppliers' => $suppliers,
        ]);
    }
}
