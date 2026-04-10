<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterProduct = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'filterType', 'filterProduct', 'sortField', 'sortDirection'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }
    public function updatingFilterProduct() { $this->resetPage(); }

    public function sortBy($field)
    {
        $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : $this->sortDirection = 'asc';
        $this->sortField = $field;
    }

    public function exportCsv()
    {
        $transactions = $this->buildQuery()->get();

        $filename = 'movimientos_stock_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, ['ID', 'Producto', 'Usuario', 'Tipo', 'Cantidad', 'Antes', 'Después', 'Motivo', 'Descripción', 'Fecha'], ';');
            foreach ($transactions as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->product->name ?? '—',
                    $t->user->name ?? '—',
                    $t->transaction_type,
                    $t->quantity,
                    $t->before_quantity,
                    $t->after_quantity,
                    $t->reason ?? '',
                    $t->description ?? '',
                    $t->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildQuery()
    {
        $query = InventoryTransaction::with(['product', 'user']);

        if ($this->search) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhere('reason', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('transaction_type', $this->filterType);
        }

        if ($this->filterProduct) {
            $query->where('product_id', $this->filterProduct);
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $transactions = $this->buildQuery()->paginate(15);
        $products = Product::orderBy('name')->get(['id', 'name']);

        $types = [
            'entrada'          => 'Entrada',
            'entrada_manual'   => 'Entrada manual',
            'venta'            => 'Venta',
            'salida'           => 'Salida',
            'devolucion_pedido'=> 'Devolución pedido',
        ];

        return view('livewire.stock-history', compact('transactions', 'products', 'types'));
    }
}
