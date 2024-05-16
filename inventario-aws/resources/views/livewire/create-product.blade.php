<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
        <i class="fas fa-plus mr-2"></i> Crear Producto
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
                                Crear Nuevo Producto
                            </h3>
                            <div class="mt-2">
                                <form wire:submit.prevent="saveProduct" class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                        <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                        <textarea wire:model="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700">Precio</label>
                                        <input type="number" wire:model="price" id="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                                        <input type="number" wire:model="quantity" id="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                                        <select wire:model="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            <option value="">Selecciona una categoría</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                                        <select wire:model="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            <option value="">Selecciona un proveedor</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="newImage" class="block text-sm font-medium text-gray-700">Imagen</label>
                                        <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('newImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        @if ($newImage)
                                        <div class="mt-2">
                                            <img src="{{ $newImage->temporaryUrl() }}" alt="Preview" class="w-20 h-auto">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Guardar Producto
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
