<div class="container mx-auto p-4">
    @if (is_null($isRegistered))
    <div class="text-center">
        <h2 class="text-xl mb-4">¿El cliente está registrado?</h2>
        <div class="flex justify-center space-x-4">
            <button wire:click="$set('isRegistered', true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                <i class="material-icons mr-2">check_circle</i> Sí
            </button>
            <button wire:click="selectGenericCustomer" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                <i class="material-icons mr-2">cancel</i> No
            </button>
        </div>
    </div>
    @elseif (is_null($customerRole) && $isRegistered)
    <div class="text-center">
        <h2 class="text-xl mb-4">¿El cliente es Particular o Profesional?</h2>
        <div class="flex justify-center space-x-4">
            <button wire:click="selectRole('normal')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                <i class="material-icons mr-2">person</i> Particular
            </button>
            <button wire:click="selectRole('professional')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                <i class="material-icons mr-2">business</i> Profesional
            </button>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-2">
            @if ($showCategories)
            <div class="border p-4 rounded shadow-lg mb-6">
                <h2 class="text-xl mb-4">Categorías</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                    <div class="border p-2 rounded cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-100 {{ is_null($selectedCategory) ? 'bg-blue-100' : '' }}" wire:click="selectCategory(null, 'Todas')">
                        <div class="w-full h-24 mb-2 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/categories/todas.png') }}" alt="Todas" class="object-contain h-full transition-transform transform hover:scale-110">
                        </div>
                        <h3 class="text-lg font-semibold text-center">Todas</h3>
                    </div>
                    @foreach ($categories as $category)
                    <div class="border p-2 rounded cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-100 {{ $selectedCategory == $category->id ? 'bg-blue-100' : '' }}" wire:click="selectCategory({{ $category->id }}, '{{ $category->name }}')">
                        <div class="w-full h-24 mb-2 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="object-contain h-full transition-transform transform hover:scale-110">
                        </div>
                        <h3 class="text-lg font-semibold text-center">{{ $category->name }}</h3>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="border p-4 rounded shadow-lg mb-6 cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-100 flex items-center justify-center" wire:click="toggleCategories">
                <div class="w-full h-16 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset($selectedCategoryImage) }}" alt="{{ $selectedCategoryName }}" class="object-contain h-full transition-transform transform hover:scale-110">
                </div>
                <h3 class="text-lg font-semibold text-center">{{ $selectedCategoryName }}</h3>
            </div>
            @endif
        </div>

        <div class="md:col-span-2">
            @if ($selectedCustomer)
            <div class="border p-4 rounded shadow-lg mb-6 cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-100 flex items-center" wire:click="resetOrder">
                <div class="w-24 h-16 flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('storage/products/customer.png') }}" alt="Cambiar Cliente" class="object-contain h-full w-full transition-transform transform hover:scale-110">
                </div>
                <div class="ml-4">
                    <h2 class="text-xl mb-2">{{ $selectedCustomer->name }}</h2>
                    <div class="flex items-center text-lg">
                        <i class="material-icons mr-2">change_circle</i> Cambiar Cliente
                    </div>
                </div>
            </div>
            @else
            <div class="border p-4 rounded shadow-lg mb-6">
                <input type="text" wire:model="search" wire:input.debounce.500ms="reloadCustomers" class="form-input rounded-md shadow-sm mt-1 block w-full text-lg" placeholder="Buscar cliente...">
                <div wire:loading>
                    <p class="text-gray-500">Cargando...</p>
                </div>
                <ul class="mt-4 border rounded-md shadow-md">
                    @foreach ($customers as $customer)
                    <li class="cursor-pointer hover:bg-gray-200 p-2 border-b last:border-none transition-transform transform hover:scale-105 text-lg" wire:click="selectCustomer({{ $customer->id }})">
                        {{ $customer->name }} ({{ $customer->email }})
                    </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-3">
            <div class="flex justify-between items-center">
                <button class="text-gray-500 hover:text-gray-700 text-4xl" wire:click="previousPage">
                    <i class="material-icons">chevron_left</i>
                </button>
                <div class="flex-1 mx-2">
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2">
                        @foreach ($products as $product)
                        <div class="border p-4 rounded cursor-pointer transition-transform transform hover:scale-105 hover:bg-gray-100" wire:click="addProduct({{ $product->id }})">
                            <div class="w-full h-24 mb-2 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-contain h-full transition-transform transform hover:scale-110">
                            </div>
                            <h3 class="text-sm font-semibold">{{ $product->name }}</h3>
                            <p class="text-gray-600">{{ number_format($product->price, 2) }} €</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
                <button class="text-gray-500 hover:text-gray-700 text-4xl" wire:click="nextPage">
                    <i class="material-icons">chevron_right</i>
                </button>
            </div>
        </div>

        <div class="md:col-span-1">
            <h2 class="text-xl mb-4">Resumen del Pedido</h2>
            <div class="border p-6 rounded shadow-lg">
                <ul>
                    @foreach ($selectedProducts as $productId => $product)
                    <li class="mb-4 flex justify-between items-center text-lg">
                        <div>
                            {{ $product['name'] }} ({{ $product['quantity'] }} x {{ number_format($product['price'], 2) }} €)
                            @if ($customerRole === 'professional')
                            <p class="text-sm text-green-600">Descuento: {{ $product['discount'] }}%</p>
                            <p class="text-sm text-green-600">Precio con Descuento: {{ number_format($product['price'] * (1 - $product['discount'] / 100), 2) }} €</p>
                            @endif
                        </div>
                        <div class="flex items-center">
                            <button class="text-gray-500 hover:text-gray-700 mr-4 text-4xl" wire:click="updateProductQuantity({{ $productId }}, -1)">
                                <i class="material-icons">remove_circle_outline</i>
                            </button>
                            <button class="text-gray-500 hover:text-gray-700 text-4xl" wire:click="updateProductQuantity({{ $productId }}, 1)">
                                <i class="material-icons">add_circle_outline</i>
                            </button>
                        </div>
                        <button class="text-red-500 underline hover:text-red-700 ml-4 flex items-center text-lg" wire:click="removeProduct({{ $productId }})">
                            <i class="material-icons text-3xl">delete_outline</i>
                        </button>
                    </li>
                    @endforeach
                </ul>
                <div class="border-t mt-4 pt-4">
                    <p class="text-2xl font-semibold">Total: {{ number_format($totalAmount, 2) }} €</p>
                </div>
                <div class="mt-4">
                    <label for="paymentMethod" class="block mb-2 text-lg">Método de Pago</label>
                    <select wire:model="paymentMethod" id="paymentMethod" class="form-select rounded-md shadow-sm w-full text-lg">
                        <option value="cash">Efectivo</option>
                        <option value="credit_card">Tarjeta de Crédito</option>
                        <option value="bank_transfer">Transferencia Bancaria</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <button wire:click="placeOrder" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg mt-4 w-full transition-transform transform hover:scale-105 text-lg">
                    <i class="material-icons mr-2">shopping_cart</i> Realizar Pedido
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($showSmsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">¿Desea recibir una copia del pedido por SMS?</h2>
            <div class="flex justify-around">
                <button wire:click="confirmSmsSend(true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                    <i class="material-icons mr-2">sms</i> Sí
                </button>
                <button wire:click="confirmSmsSend(false)" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded shadow-lg flex items-center justify-center transition-transform transform hover:scale-105 text-lg">
                    <i class="material-icons mr-2">cancel</i> No
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
