<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Livewire\SalesTPV;
use App\Http\Livewire\OrdersList;
use App\Http\Livewire\CategoryList;
use App\Http\Livewire\ProductsList;
use App\Http\Livewire\StockManager;
use App\Http\Livewire\CustomersList;
use App\Http\Livewire\SuppliersList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayPalController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function (Request $request) {
    $user = User::find(1);
    if ($user) {
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['token' => $token]);
    }
    return response()->json(['message' => 'User not found'], 404);
});


Route::get('/login-demo', function (Request $request) {
    // Credenciales para iniciar sesión
    $credentials = [
        'email' => $request->query('email'),
        'password' => $request->query('password'),
    ];

    // Intento de autenticación
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Redirige al usuario a la página de inicio (o a otra ruta específica según el rol)
        return redirect()->intended('/products');
    }

    return redirect('/')->withErrors(['error' => 'Credenciales incorrectas']);
})->name('login.demo');


// Rutas con autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

    Route::middleware('role:administrativo')->group(function () {
        Route::get('/products', ProductsList::class)->name('products.index');
        Route::get('/suppliers', SuppliersList::class)->name('suppliers.index');
        Route::get('/orders', OrdersList::class)->name('orders.index');
        Route::get('/customers', CustomersList::class)->name('customers.index');
        Route::get('/categories', CategoryList::class)->name('categories.index');
    });

    // Rutas específicas para Mozo de Almacén
    Route::middleware('role:mozo_almacen,administrativo')->group(function () {
        Route::get('/stock-manager', StockManager::class)->name('stock.manager');
    });

    // Ruta específica para Ventas
    Route::middleware('role:ventas,administrativo')->group(function () {
        Route::get('/sales-tpv', SalesTPV::class)->name('sales.tpv');
    });
});



require __DIR__ . '/auth.php';
