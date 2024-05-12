<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersList extends Component
{
    use WithPagination;

    public $search = '';
    public $editingSupplierId = null;

    protected $listeners = ['supplierUpdated' => '$refresh', 'supplierCreated' => '$refresh'];
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function reloadSuppliers()
    {
        $this->resetPage();
    }


    public function editSupplier($supplierId)
    {
        $this->editingSupplierId = $supplierId;
        $this->dispatch('edit-supplier-modal', ['supplierId' => $supplierId]);
    }

    public function deleteSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $supplier->delete();
            $this->dispatch('notification', ['type' => 'success', 'message' => 'Proveedor eliminado correctamente.']);
            //$this->resetPage(); de momento no va bien
            return redirect()->to('/suppliers');
        } else {
            $this->dispatch('notification', ['type' => 'error', 'message' => 'Proveedor no encontrado.']);
        }
    }

    public function render()
    {
        $suppliers = Supplier::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })->paginate(10);

        $headers = ['Nombre', 'Email', 'Teléfono', 'Dirección', 'Imagen'];

        $data = $suppliers->map(function ($supplier) {
            return [
                'Nombre' => $supplier->name,
                'Email' => $supplier->email,
                'Teléfono' => $supplier->phone_number,
                'Dirección' => $supplier->address,
                'Imagen' => '<img src="' . asset('storage/' . $supplier->image) . '" style="width:100px; height:auto;">',
                'id' => $supplier->id
            ];
        });

        return view('livewire.suppliers-list', [
            'suppliers' => $suppliers,
            'headers' => $headers,
            'data' => $data,
        ]);
    }
}
