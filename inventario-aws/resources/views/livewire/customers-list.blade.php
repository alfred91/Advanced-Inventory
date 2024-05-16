<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Lista de Clientes</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar por nombre, email o teléfono..." wire:model="search">
        <button wire:click="showCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            Nuevo Cliente
        </button>
    </div>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Teléfono</th>
                    <th class="px-6 py-3">Dirección</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $customer->id }}</td>
                    <td class="px-6 py-4">{{ $customer->name }}</td>
                    <td class="px-6 py-4">{{ $customer->email }}</td>
                    <td class="px-6 py-4">{{ $customer->phone_number }}</td>
                    <td class="px-6 py-4">{{ $customer->address }}</td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button wire:click="showEditModal({{ $customer->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                            Editar
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded shadow-lg transition-transform transform hover:scale-110" onclick="confirm('¿Está seguro de que desea eliminar este cliente?') || event.stopImmediatePropagation()" wire:click="deleteCustomer({{ $customer->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>

    <!-- Modal for Creating and Editing Customers -->
    @if ($showModal)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">{{ $isEdit ? 'Editar Cliente' : 'Nuevo Cliente' }}</h3>
                            <form wire:submit.prevent="saveCustomer" class="space-y-4">
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input type="text" wire:model="phone_number" id="phone_number" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                                    <input type="text" wire:model="address" id="address" class="mt-1 block w-full form-input rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
