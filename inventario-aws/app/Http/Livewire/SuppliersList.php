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
    public $isLoading = false;

    protected $listeners = ['supplierUpdated' => '$refresh', 'supplierCreated' => '$refresh'];
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

    public function reloadSuppliers()
    {
        $this->isLoading = true;
        $this->resetPage();
    }

    public function editSupplier($supplierId)
    {
        $this->editingSupplierId = $supplierId;
        $this->dispatchBrowserEvent('edit-supplier-modal', ['supplierId' => $supplierId]);
    }

    public function deleteSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $supplier->delete();
            $this->resetPage();
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'message' => 'Proveedor eliminado correctamente.']);
        } else {
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'message' => 'Proveedor no encontrado.']);
        }
    }

    public function render()
    {
        $query = Supplier::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $suppliers = $query->paginate(10);

        return view('livewire.suppliers-list', [
            'suppliers' => $suppliers,
        ]);
    }
}
