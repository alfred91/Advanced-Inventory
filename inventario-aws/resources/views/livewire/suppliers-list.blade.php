<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Lista de Proveedores</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar Proveedor..." wire:model="search" wire:input.debounce.500ms="reloadSuppliers">
        @livewire('create-supplier')
    </div>

    <div wire:loading.class="opacity-50">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Teléfono</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Imagen</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $supplier->name }}</td>
                        <td class="px-6 py-4">{{ $supplier->email }}</td>
                        <td class="px-6 py-4">{{ $supplier->phone_number }}</td>
                        <td class="px-6 py-4">{{ $supplier->address }}</td>
                        <td class="px-6 py-4">
                            @if ($supplier->image)
                            <img src="{{ Storage::url($supplier->image) }}" alt="Proveedor" class="w-20 h-auto rounded-lg">
                            @else
                            <img src="{{ asset('storage/suppliers/default.gif') }}" alt="Proveedor" class="w-20 h-auto rounded-lg">
                            @endif
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">
                            <button wire:click="editSupplier({{ $supplier->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                                Editar
                            </button>
                            <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded" onclick="confirm('¿Estás seguro de que deseas eliminar este proveedor?') || event.stopImmediatePropagation()" wire:click="deleteSupplier({{ $supplier->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Editar Proveedor -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Editando Proveedor: {{ $name }}</h2>
            <form wire:submit.prevent="saveChanges" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" wire:model.defer="phone_number" id="phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('phone_number') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" wire:model.defer="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('address') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                @if ($image)
                <div class="mb-4">
                    <img src="{{ Storage::url($image) }}" alt="Imagen actual" class="w-20 h-20 object-cover rounded-md shadow-sm">
                </div>
                @endif
                <div>
                    <label for="newImage" class="block text-sm font-medium text-gray-700">Nueva Imagen</label>
                    <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('newImage') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-4">
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
    @endif
</div>
