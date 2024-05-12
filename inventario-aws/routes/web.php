<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\ProductsList;
use App\Http\Livewire\SuppliersList;
use App\Http\Livewire\OrdersList;

Route::get('/', function () {
    return view('welcome');
});

// Rutas con autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rutas CRUD para productos
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductsList::class)->except(['index']);
});

Route::get('/products', ProductsList::class)->name('products.index');

Route::get('/suppliers', SuppliersList::class)->name('suppliers.index');

Route::get('/orders', OrdersList::class)->name('orders.index');


// Rutas de autenticación
require __DIR__ . '/auth.php';
