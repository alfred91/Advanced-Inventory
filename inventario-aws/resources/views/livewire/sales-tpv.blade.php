<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-6 text-center">Terminal Punto de Venta (TPV)</h1>

    @if (is_null($isRegistered))
    <div class="text-center">
        <h2 class="text-xl mb-4">¿El cliente está registrado?</h2>
        <div class="flex justify-center space-x-4">
            <button wire:click="$set('isRegistered', true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center justify-center">
                <i class="fas fa-user-check mr-2"></i> Sí
            </button>
            <button wire:click="selectGenericCustomer" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center justify-center">
                <i class="fas fa-user-times mr-2"></i> No
            </button>
        </div>
    </div>
    @else
    @if ($selectedCustomer)
    <div class="mb-6">
        <h2 class="text-xl mb-2">Cliente: {{ $selectedCustomer->name }}</h2>
        <button wire:click="resetOrder" class="text-red-500 underline flex items-center">
            <i class="fas fa-times mr-2"></i> Cambiar Cliente
        </button>
    </div>
    @else
    <div class="mb-6">
        <input type="text" wire:model="search" wire:input.debounce.500ms="reloadCustomers" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Buscar cliente...">
        <div wire:loading>
            <p class="text-gray-500">Cargando...</p>
        </div>
        <ul class="mt-4 border rounded-md shadow-md">
            @foreach ($customers as $customer)
            <li class="cursor-pointer hover:bg-gray-200 p-2 border-b last:border-none" wire:click="selectCustomer({{ $customer->id }})">
                {{ $customer->name }} ({{ $customer->email }})
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-3">
            <div class="mb-6">
                <h2 class="text-xl mb-2">Categorías</h2>
                <select wire:model="selectedCategory" wire:change="selectCategory($event.target.value)" class="form-select rounded-md shadow-sm w-full">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <h2 class="text-xl mb-4">Productos</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
                <div class="border p-4 rounded shadow-lg cursor-pointer hover:shadow-2xl transition-shadow duration-300" wire:click="addProduct({{ $product->id }})">
                    <div class="w-full h-32 mb-2 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-contain h-full">
                    </div>
                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                    <p class="text-gray-600">{{ number_format($product->price, 2) }} €</p>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>

        <div class="md:col-span-1">
            <h2 class="text-xl mb-4">Resumen del Pedido</h2>
            <div class="border p-4 rounded shadow-lg">
                <ul>
                    @foreach ($selectedProducts as $productId => $product)
                    <li class="mb-2 flex justify-between items-center">
                        <div>
                            {{ $product['name'] }} ({{ $product['quantity'] }} x {{ number_format($product['price'], 2) }} €)
                        </div>
                        <div class="flex items-center">
                            <button class="text-gray-500 hover:text-gray-700 mr-2" wire:click="updateProductQuantity({{ $productId }}, -1)">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700" wire:click="updateProductQuantity({{ $productId }}, 1)">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <button class="text-red-500 underline hover:text-red-700 ml-4 flex items-center" wire:click="removeProduct({{ $productId }})">
                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                        </button>
                    </li>
                    @endforeach
                </ul>
                <div class="border-t mt-4 pt-4">
                    <p class="text-lg font-semibold">Total: {{ number_format($totalAmount, 2) }} €</p>
                </div>
                <div class="mt-4">
                    <label for="paymentMethod" class="block mb-2">Método de Pago</label>
                    <select wire:model="paymentMethod" id="paymentMethod" class="form-select rounded-md shadow-sm w-full">
                        <option value="cash">Efectivo</option>
                        <option value="credit_card">Tarjeta de Crédito</option>
                        <option value="bank_transfer">Transferencia Bancaria</option>
                    </select>
                </div>
                <button wire:click="placeOrder" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg mt-4 w-full">
                    <i class="fas fa-shopping-cart mr-2"></i> Realizar Pedido
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($showConfirmationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">¿Desea recibir una copia del pedido por correo?</h2>
            <div class="flex justify-around">
                <button wire:click="confirmEmailSend(true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center justify-center">
                    <i class="fas fa-envelope mr-2"></i> Sí
                </button>
                <button wire:click="confirmEmailSend(false)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i> No
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
