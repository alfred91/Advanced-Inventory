<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\ProductsList;

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
    Route::resource('products', ProductController::class)->except(['index']); // Excluir 'index' si usas Livewire para esta vista
});

// Usar Livewire para la lista de productos
Route::get('/products', ProductsList::class)->name('products.index');

// Búsqueda de productos
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// Test Modal View
Route::get('/test-modal', function () {
    return view('livewire/test-modal');
});

// Rutas de autenticación
require __DIR__ . '/auth.php';
