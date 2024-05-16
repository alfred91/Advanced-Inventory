<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
        <i class="fas fa-edit mr-2"></i> Editar
    </button>

    @if ($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-semibold">Editar Proveedor: {{ $name }}</h2>
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full">
                    @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full">
                    @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" wire:model.defer="phone_number" id="phone_number" class="mt-1 block w-full">
                    @error('phone_number') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" wire:model.defer="address" id="address" class="mt-1 block w-full">
                    @error('address') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                @if ($image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $image) }}" alt="Imagen actual" class="w-20 h-20 object-cover">
                </div>
                @endif
                <div class="mb-4">
                    <label for="newImage" class="block text-sm font-medium text-gray-700">Cambiar Imagen</label>
                    <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full">
                    @error('newImage') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Guardar Cambios</button>
                    <button type="button" wire:click="closeModal" class="ml-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
