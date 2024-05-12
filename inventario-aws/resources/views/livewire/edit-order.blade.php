<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        <i class="fas fa-edit mr-2"></i> Editar Pedido
    </button>

    @if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Editando Pedido: #{{ $order->id }}</h3>
                            <div class="mt-2">
                                <form wire:submit.prevent="updateOrder">
                                    <div class="mb-4">
                                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                        <select wire:model="customerId" id="customer_id" class="mt-1 block w-full form-input rounded-md shadow-sm">
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                        <select wire:model="order.status" id="status" class="mt-1 block w-full form-input rounded-md shadow-sm">
                                            <option value="pending">Pendiente</option>
                                            <option value="completed">Completado</option>
                                            <option value="cancelled">Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                                        <input type="text" wire:model="order.total_amount" disabled class="mt-1 block w-full form-input rounded-md shadow-sm bg-gray-100" placeholder="Calculado automáticamente">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($allProducts as $product)
                                        <div class="mb-4">
                                            <label for="product_{{ $product->id }}" class="block text-sm font-medium text-gray-700">{{ $product->name }}</label>
                                            <input type="number" wire:model.lazy="selectedProducts.{{ $product->id }}.quantity" id="product_{{ $product->id }}" class="mt-1 block w-full form-input rounded-md shadow-sm" placeholder="Cantidad">
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
    </div>
    @endif
</div>
