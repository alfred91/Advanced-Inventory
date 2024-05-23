<?php

use App\Http\Livewire\OrdersList;
use App\Http\Livewire\ProductsList;
use App\Http\Livewire\CustomersList;
use App\Http\Livewire\SuppliersList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Registrar Middleware
Route::aliasMiddleware('role', RoleMiddleware::class);

// Rutas con autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para roles combinados
    Route::middleware('role:administrativo,mozo_almacen,ventas')->group(function () {
        Route::get('/products', ProductsList::class)->name('products.index');
        Route::get('/suppliers', SuppliersList::class)->name('suppliers.index');
    });

    // Rutas específicas para Administrativo
    Route::middleware('role:administrativo')->group(function () {
        Route::get('/orders', OrdersList::class)->name('orders.index');
        Route::get('/customers', CustomersList::class)->name('customers.index');
    });
});

require __DIR__ . '/auth.php';
