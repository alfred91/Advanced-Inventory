<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Lista de Proveedores</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar Proveedor..." wire:model.debounce.500ms="search" wire:input="reloadSuppliers">
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
                            <img src="{{ asset('storage/' . $supplier->image) }}" alt="Proveedor" class="w-20 h-auto rounded-lg">
                        </td>
                        <td class="px-6 py-4 flex items-center gap-2">
                            @livewire('edit-supplier', ['supplierId' => $supplier->id], key($supplier->id))
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
</div>
