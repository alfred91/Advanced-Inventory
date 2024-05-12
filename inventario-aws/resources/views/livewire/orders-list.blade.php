<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold">Lista de Pedidos</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto" placeholder="Buscar..." wire:model.debounce.500ms="search">
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
                        @livewire('edit-order', ['orderId' => $order->id], key($order->id))
                        <button wire:click="showOrderDetails({{ $order->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver Detalles</button>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded" onclick="confirm('Are you sure you want to delete this order?')" wire:click="deleteOrder({{ $order->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
    <!-- Modal for Order Details -->
    @if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Detalles del Pedido: #{{ $selectedOrder->id }}</h3>
                    <div class="mt-2">
                        <p><strong>Cliente:</strong> {{ $selectedOrder->customer->name }}</p>
                        <p><strong>Total:</strong> ${{ number_format($selectedOrder->total_amount, 2) }}</p>
                        <p><strong>Productos:</strong></p>
                        <ul>
                            @foreach ($selectedOrder->products as $product)
                            <li>{{ $product->name }}: Cantidad - {{ $product->pivot->quantity }}, Precio Unitario - ${{ number_format($product->pivot->unit_price, 2) }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
