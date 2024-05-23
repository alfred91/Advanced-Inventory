<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Terminal Punto de Venta (TPV)</h1>

    @if (is_null($isRegistered))
    <div class="text-center">
        <h2 class="text-xl mb-4">¿El cliente está registrado?</h2>
        <button wire:click="$set('isRegistered', true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Sí
        </button>
        <button wire:click="selectGenericCustomer" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            No
        </button>
    </div>
    @else
    @if ($selectedCustomer)
    <div class="mb-4">
        <h2 class="text-xl">Cliente: {{ $selectedCustomer->name }}</h2>
        <button wire:click="resetOrder" class="text-red-500">Cambiar Cliente</button>
    </div>
    @else
    <div class="mb-4">
        <input type="text" wire:model="search" wire:input.debounce.500ms="reloadCustomers" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Buscar cliente...">
        <div wire:loading>
            <p>Cargando...</p>
        </div>
        <ul class="mt-4">
            @foreach ($customers as $customer)
            <li class="cursor-pointer hover:bg-gray-200 p-2" wire:click="selectCustomer({{ $customer->id }})">
                {{ $customer->name }} ({{ $customer->email }})
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
    @endif

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            <div class="mb-4">
                <h2 class="text-xl mb-2">Categorías</h2>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="selectCategory(null)" class="px-4 py-2 bg-gray-300 rounded @if(is_null($selectedCategory)) bg-blue-500 text-white @endif">Todas</button>
                    @foreach ($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" class="px-4 py-2 bg-gray-300 rounded @if($selectedCategory === $category->id) bg-blue-500 text-white @endif">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>

            <h2 class="text-xl mb-2">Productos</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($products as $product)
                <div class="border p-4 rounded cursor-pointer" wire:click="addProduct({{ $product->id }})">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover mb-2">
                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                    <p>{{ number_format($product->price, 2) }} €</p>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>

        <div>
            <h2 class="text-xl mb-2">Resumen del Pedido</h2>
            <div class="border p-4 rounded">
                <ul>
                    @foreach ($selectedProducts as $productId => $product)
                    <li class="mb-2 flex justify-between">
                        <div>
                            {{ $product['name'] }} ({{ $product['quantity'] }} x {{ number_format($product['price'], 2) }} €)
                        </div>
                        <button class="text-red-500" wire:click="removeProduct({{ $productId }})">Eliminar</button>
                    </li>
                    @endforeach
                </ul>
                <div class="border-t mt-2 pt-2">
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
                <button wire:click="placeOrder" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4 w-full">
                    Realizar Pedido
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
                <button wire:click="confirmEmailSend(true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Sí
                </button>
                <button wire:click="confirmEmailSend(false)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    No
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
