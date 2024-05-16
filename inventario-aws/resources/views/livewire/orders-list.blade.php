<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold">Lista de Pedidos</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar por producto, categoría o proveedor..." wire:model="search" wire:input.debounce.500ms="reloadOrders">
        @livewire('create-order')
    </div>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer Name</th>
                    <th class="px-6 py-3">Total Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Details</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->customer->name }}</td>
                    <td class="px-6 py-4">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">{{ $order->status }}</td>
                    <td class="px-6 py-4">
                        <button wire:click="showOrderDetails({{ $order->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Detalles</button>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        @livewire('edit-order', ['orderId' => $order->id], key('edit-order-'.$order->id))
                        <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded" onclick="confirm('Are you sure you want to delete this order?')" wire:click="deleteOrder({{ $order->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>

    <!-- Modal for Order Details -->
    @if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Detalles del Pedido: #{{ $selectedOrder->id }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p><strong>Fecha de Pedido:</strong> {{ $selectedOrder->order_date }}</p>
                                    <p><strong>Cliente:</strong> {{ $selectedOrder->customer->name }}</p>
                                    <p><strong>Email:</strong> {{ $selectedOrder->customer->email }}</p>
                                    <p><strong>Teléfono:</strong> {{ $selectedOrder->customer->phone_number }}</p>
                                </div>
                                <div>
                                    <p><strong>Total:</strong> {{ number_format($selectedOrder->total_amount, 2) }} € </p>
                                    <p><strong>Estado:</strong> {{ $selectedOrder->status }}</p>
                                    <p><strong>Notificación Enviada:</strong> {{ $selectedOrder->notification_sent ? 'Sí' : 'No' }}</p>
                                    <p><strong>Dirección de Envío:</strong> {{ $selectedOrder->customer->address }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($selectedOrder->products as $product)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->pivot->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($product->pivot->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
