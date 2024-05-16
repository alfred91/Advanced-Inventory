<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
        <i class="fas fa-edit mr-2"></i> Editar
    </button>

    @if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Editando Pedido: #{{ $orderId }}</h3>
                            <form wire:submit.prevent="updateOrder" class="space-y-4">
                                <div class="mb-4">
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                    <select wire:model="customerId" id="customer_id" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select wire:model="status" id="status" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending">Pendiente</option>
                                        <option value="completed">Completado</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                                    <input type="text" wire:model="totalAmount" disabled class="mt-1 block w-full form-input rounded-md shadow-sm bg-gray-100 border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Calculado automáticamente">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Productos</label>
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($selectedProducts as $productId => $product)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $allProducts->find($productId)->name }}</td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" wire:model.lazy="selectedProducts.{{ $productId }}.quantity" id="product_{{ $productId }}" class="block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Añadir Producto</label>
                                    <div class="flex space-x-2">
                                        <select wire:model="newProductId" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Seleccione un producto</option>
                                            @foreach($allProducts as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" wire:model="newProductQuantity" class="mt-1 block w-24 form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Cantidad" min="1">
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
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
