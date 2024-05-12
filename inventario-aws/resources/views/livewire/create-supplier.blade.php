<div>
    <x-primary-button wire:click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Añadir Nuevo Proveedor
    </x-primary-button>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-md mx-auto">
            <h2 class="text-xl font-semibold">Añadir Nuevo Proveedor</h2>
            <div class="mt-2">
                <form wire:submit.prevent="saveSupplier">
                    <input type="text" wire:model.defer="name" placeholder="Nombre del proveedor" class="input input-bordered w-full mb-3" />
                    <input type="email" wire:model.defer="email" placeholder="Email" class="input input-bordered w-full mb-3" />
                    <input type="text" wire:model.defer="phone_number" placeholder="Teléfono" class="input input-bordered w-full mb-3" />
                    <input type="text" wire:model.defer="address" placeholder="Dirección" class="input input-bordered w-full mb-3" />
                    <input type="file" wire:model="newImage" class="input input-bordered w-full mb-3" />
                    <div class="flex justify-end space-x-2">
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
