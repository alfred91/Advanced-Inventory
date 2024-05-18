<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
        <i class="fas fa-plus mr-2"></i> Create Order
    </button>

    @if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Create New Order
                            </h3>
                            <div class="mt-2">
                                <form wire:submit.prevent="saveOrder" class="space-y-4">
                                    <div>
                                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                                        <select wire:model="selectedCustomerId" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            <option value="">Select a customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedCustomerId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($products as $productId => $productName)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $productName }}</label>
                                            <input type="number" wire:model.lazy="orderProducts.{{ $productId }}.quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Quantity">
                                            @error('orderProducts.' . $productId . '.quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                            <button type="button" wire:click="removeProduct({{ $productId }})" class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded">Remove</button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Cancel
                                        </button>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Save Order
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