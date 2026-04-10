<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $totalProducts  = Product::count();
        $totalOrders    = Order::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Supplier::count();

        $lowStockCount = Product::whereColumn('quantity', '<=', 'minimum_stock')->count();

        $stockValue = Product::sum(DB::raw('quantity * price'));

        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingOrders   = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $salesLast30 = Order::where('status', 'completed')
            ->where('order_date', '>=', now()->subDays(30))
            ->sum('total_amount');

        $recentMovements = InventoryTransaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topLowStock = Product::whereColumn('quantity', '<=', 'minimum_stock')
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCustomers', 'totalSuppliers',
            'lowStockCount', 'stockValue', 'recentOrders',
            'pendingOrders', 'completedOrders', 'cancelledOrders',
            'salesLast30', 'recentMovements', 'topLowStock'
        ));
    }
}
