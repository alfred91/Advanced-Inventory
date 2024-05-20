<?php

use App\Http\Livewire\OrdersList;
use App\Http\Livewire\ProductsList;
use App\Http\Livewire\CustomersList;
use App\Http\Livewire\SuppliersList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Mail;

Route::get('/send-test-email', function () {
    $details = [
        'title' => 'Correo de prueba de Laravel',
        'body' => 'Este es un correo de prueba enviado desde Laravel utilizando Mailtrap.'
    ];

    Mail::raw($details['body'], function ($message) use ($details) {
        $message->to('destinatario@example.com')
            ->subject($details['title']);
    });

    return 'Correo de prueba enviado';
});


Route::get('/', function () {
    return view('welcome');
});

// Rutas con autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas componentes Livewire
    Route::get('/products', ProductsList::class)->name('products.index');
    Route::get('/suppliers', SuppliersList::class)->name('suppliers.index');
    Route::get('/orders', OrdersList::class)->name('orders.index');
    Route::get('/customers', CustomersList::class)->name('customers.index');
});

// Rutas de autenticación
require __DIR__ . '/auth.php';
