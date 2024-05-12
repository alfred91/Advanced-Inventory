<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class CreateSupplier extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $name, $email, $phone_number, $address, $newImage;

    public function render()
    {
        return view('livewire.create-supplier');
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
    public function saveSupplier()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048',
        ]);

        $data = array_merge($validatedData, [
            'image' => $this->newImage ? $this->newImage->store('suppliers', 'public') : null,
        ]);

        Supplier::create($data);

        $this->closeModal();
        $this->reset('name', 'email', 'phone_number', 'address', 'newImage');
        $this->dispatch('supplierCreated');
    }
}
