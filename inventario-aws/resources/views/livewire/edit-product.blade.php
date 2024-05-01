<div>
    <button wire:click="openModal">Editar Producto</button>

    @if ($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-semibold">Editando Producto: {{ $name }}</h2>
            <form wire:submit.prevent="saveChanges" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea wire:model="description" id="description" class="mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Precio</label>
                    <input type="text" wire:model="price" id="price" class="mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input type="number" wire:model="quantity" id="quantity" class="mt-1 block w-full">
                </div>

                @if ($image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $image) }}" alt="Imagen actual" class="w-20 h-20 object-cover">
                </div>
                @endif

                <div class="mb-4">
                    <label for="newImage" class="block text-sm font-medium text-gray-700">Nueva Imagen</label>
                    <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full">
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
