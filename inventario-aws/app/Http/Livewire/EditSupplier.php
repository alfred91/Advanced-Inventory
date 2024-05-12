<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSupplier extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $supplier, $supplierId, $name, $email, $phone_number, $address, $image, $newImage;

    public function mount($supplierId)
    {
        $this->supplierId = $supplierId;
        $supplier = Supplier::find($supplierId);

        if ($supplier) {
            $this->name = $supplier->name;
            $this->email = $supplier->email;
            $this->phone_number = $supplier->phone_number;
            $this->address = $supplier->address;
            $this->image = $supplier->image;
        }
    }
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $this->supplierId,
            'phone_number' => 'required|string',
            'address' => 'required|string|max:500',
            'newImage' => 'nullable|image|max:2048',
        ]);

        $supplier = Supplier::find($this->supplierId);
        if ($supplier) {
            $supplierData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
            ];

            if ($this->newImage) {
                $imageName = $this->newImage->store('suppliers', 'public');
                $supplier->image = $imageName;
            }

            $supplier->update($supplierData);
            $this->showModal = false;
            $this->dispatch('supplierUpdated');
            $this->dispatch('refreshSuppliers');
        }
    }

    public function openModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.edit-supplier');
    }
}
