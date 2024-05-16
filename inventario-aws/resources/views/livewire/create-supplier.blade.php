<div>
    <x-primary-button wire:click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
        Añadir Nuevo Proveedor
    </x-primary-button>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Añadir Nuevo Proveedor</h2>
            <div class="mt-2">
                <form wire:submit.prevent="saveSupplier" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del proveedor</label>
                        <input type="text" wire:model.defer="name" id="name" placeholder="Nombre del proveedor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model.defer="email" id="email" placeholder="Email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" wire:model.defer="phone_number" id="phone_number" placeholder="Teléfono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" wire:model.defer="address" id="address" placeholder="Dirección" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="newImage" class="block text-sm font-medium text-gray-700">Imagen</label>
                        <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" />
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <x-primary-button type="submit">
                            Guardar
                        </x-primary-button>
                        <x-secondary-button type="button" wire:click="closeModal">
                            Cancelar
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
