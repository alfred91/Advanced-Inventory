<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar por producto, categoría, proveedor..." wire:model="search" wire:input.debounce.500ms="reloadOrders">
        <button wire:click="openCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            <i class="material-icons mr-2">add</i> Añadir Pedido
        </button>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('id')" class="focus:outline-none">
                            ID Pedido
                            @if($sortField === 'id')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('customer_name')" class="focus:outline-none">
                            Cliente
                            @if($sortField === 'customer_name')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('customer_role')" class="focus:outline-none">
                            Rol
                            @if($sortField === 'customer_role')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('total_amount')" class="focus:outline-none">
                            Monto Total
                            @if($sortField === 'total_amount')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('status')" class="focus:outline-none">
                            Estado
                            @if($sortField === 'status')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('order_date')" class="focus:outline-none">
                            Fecha de Pedido
                            @if($sortField === 'order_date')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" wire:click="showOrderDetails({{ $order->id }}, false)">
                    <td class="px-6 py-4">{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->customer->name }}</td>
                    <td class="px-6 py-4">{{ $order->customer->role }}</td>
                    <td class="px-6 py-4">{{ number_format($order->total_amount, 2) }} €</td>
                    <td class="px-6 py-4">{{ $this->getTranslatedStatus($order->status) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 flex items-center gap-2" wire:click.stop>
                        <button wire:click="showOrderDetails({{ $order->id }}, true)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105 flex items-center justify-center">
                            <span class="material-icons">edit</span>
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-110 flex items-center justify-center" onclick="confirm('¿Está seguro de que desea eliminar este pedido?') || event.stopImmediatePropagation()" wire:click="deleteOrder({{ $order->id }})">
                            <span class="material-icons">delete</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Modal Crear Pedido-->
    @if ($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-xl font-semibold mb-4">Crear Nuevo Pedido</h2>
            <form wire:submit.prevent="confirmSave(false)" class="space-y-4">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select wire:model="customerId" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" wire:change="updateDiscountStatus">
                        <option value="">Seleccione un cliente</option>
                        <optgroup label="Particulares">
                            @foreach($customers->where('role', 'normal') as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Profesionales">
                            @foreach($customers->where('role', 'professional') as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                @if($applyDiscount)
                <div class="flex items-center">
                    <input type="checkbox" wire:model="applyDiscount" class="form-checkbox" disabled>
                    <label for="apply_discount" class="ml-2 block text-sm font-medium text-gray-700">Aplicar Descuento Profesional</label>
                </div>
                @endif
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select wire:model.defer="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Fecha del Pedido</label>
                        <input type="date" wire:model="orderDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" max="{{ now()->toDateString() }}">
                    </div>
                    <div class="w-1/2">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700">Monto Total</label>
                        <input type="text" wire:model="totalAmount" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Productos</label>
                    <div class="max-h-60 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    @if($applyDiscount)
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dto. (%)</th>
                                    @endif
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($selectedProducts as $productId => $product)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $allProducts->find($productId)->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($product['unit_price'], 2) }} €</td>
                                    @if($applyDiscount)
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ intval($product['discount']) }}%</td>
                                    @endif
                                    <td class="px-4 py-2 flex items-center">
                                        <button type="button" wire:click="decreaseProductQuantity({{ $productId }})" class="ml-2 text-gray-500 hover:text-gray-700"><i class="fas fa-minus"></i></button>
                                        <input type="number" min="0" wire:model.lazy="selectedProducts.{{ $productId }}.quantity" id="product_{{ $productId }}" class="block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad" max="{{ $product['available_quantity'] }}">
                                        <button type="button" wire:click="increaseProductQuantity({{ $productId }})" class="ml-2 text-gray-500 hover:text-gray-700"><i class="fas fa-plus"></i></button>
                                        @if(isset($this->selectedProducts[$productId]) && $this->selectedProducts[$productId]['quantity'] <= 0) <button type="button" wire:click="removeProduct({{ $productId }})" class="ml-2 text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                                            @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        @if($applyDiscount)
                                        {{ number_format($product['quantity'] * $product['unit_price'] * (1 - $product['discount'] / 100), 2) }} €
                                        @else
                                        {{ number_format($product['quantity'] * $product['unit_price'], 2) }} €
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Añadir Producto</label>
                    <div class="flex space-x-2">
                        <select wire:model="newProductId" class="mt-1 block w-3/4 form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione un producto</option>
                            @foreach($allProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Disponible: {{ $product->quantity }})</option>
                            @endforeach
                        </select>
                        <input type="number" min="1" max="{{ $newProductId ? $allProducts->find($newProductId)->quantity : '' }}" wire:model="newProductQuantity" class="mt-1 block w-1/4 form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad">
                        <button type="button" wire:click="addProduct" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                            Añadir
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Cerrar
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        Guardar Pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    <!-- Modal Detalles/Editar Pedido-->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Editar Pedido: #' . $orderId : 'Detalles del Pedido: #' . $orderId }}</h2>
            <form wire:submit.prevent="saveChanges" class="space-y-4">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select wire:model.defer="customerId" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" {{ $isEdit ? '' : 'disabled' }}>
                        <option value="">Seleccione un cliente</option>
                        <optgroup label="Particulares">
                            @foreach($customers->where('role', 'normal') as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Profesionales">
                            @foreach($customers->where('role', 'professional') as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                @if($applyDiscount)
                <div class="flex items-center">
                    <input type="checkbox" wire:model="applyDiscount" class="form-checkbox" disabled>
                    <label for="apply_discount" class="ml-2 block text-sm font-medium text-gray-700">Aplicar Descuento Profesional</label>
                </div>
                @endif
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select wire:model.defer="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" {{ $isEdit ? '' : 'disabled' }}>
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Fecha del Pedido</label>
                        <input type="date" wire:model="orderDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" max="{{ now()->toDateString() }}" {{ $isEdit ? '' : 'disabled' }}>
                    </div>
                    <div class="w-1/2">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700">Monto Total</label>
                        <input type="text" wire:model="totalAmount" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Productos</label>
                    <div class="max-h-60 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    @if($applyDiscount)
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dto. (%)</th>
                                    @endif
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($selectedProducts as $productId => $product)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $allProducts->find($productId)->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($product['unit_price'], 2) }} €</td>
                                    @if($applyDiscount)
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ intval($product['discount']) }}%</td>
                                    @endif
                                    <td class="px-4 py-2 flex items-center">
                                        @if($isEdit)
                                        <button type="button" wire:click="decreaseProductQuantity({{ $productId }})" class="ml-2 text-gray-500 hover:text-gray-700"><i class="fas fa-minus"></i></button>
                                        <input type="number" min="0" wire:model.lazy="selectedProducts.{{ $productId }}.quantity" id="product_{{ $productId }}" class="block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad" max="{{ $product['available_quantity'] }}">
                                        <button type="button" wire:click="increaseProductQuantity({{ $productId }})" class="ml-2 text-gray-500 hover:text-gray-700"><i class="fas fa-plus"></i></button>
                                        @if(isset($this->selectedProducts[$productId]) && $this->selectedProducts[$productId]['quantity'] <= 0) <button type="button" wire:click="removeProduct({{ $productId }})" class="ml-2 text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></button>
                                            @endif
                                            @else
                                            <input type="number" min="0" wire:model.lazy="selectedProducts.{{ $productId }}.quantity" id="product_{{ $productId }}" class="block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad" max="{{ $product['available_quantity'] }}" disabled>
                                            @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        @if($applyDiscount)
                                        {{ number_format($product['quantity'] * $product['unit_price'] * (1 - $product['discount'] / 100), 2) }} €
                                        @else
                                        {{ number_format($product['quantity'] * $product['unit_price'], 2) }} €
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($isEdit)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Añadir Producto</label>
                    <div class="flex space-x-2">
                        <select wire:model="newProductId" class="mt-1 block w-3/4 form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione un producto</option>
                            @foreach($allProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Disponible: {{ $product->quantity }})</option>
                            @endforeach
                        </select>
                        <input type="number" min="1" max="{{ $newProductId ? $allProducts->find($newProductId)->quantity : '' }}" wire:model="newProductQuantity" class="mt-1 block w-1/4 form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad">
                        <button type="button" wire:click="addProduct" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                            Añadir
                        </button>
                    </div>
                </div>
                @endif
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Cerrar
                    </button>
                    @if($isEdit)
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        Guardar Cambios
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @endif


    <!-- Modal Confirmación de Guardar Cambios-->
    @if ($showConfirmModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-xl font-semibold mb-4">Confirmar Guardar Cambios</h2>
            <p class="mb-4">¿Desea enviar un SMS al cliente con la actualización del estado del pedido?</p>
            <div class="flex justify-end space-x-2">
                <button type="button" wire:click="confirmSave(false)" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    No Enviar
                </button>
                <button type="button" wire:click="confirmSave(true)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Enviar SMS
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
