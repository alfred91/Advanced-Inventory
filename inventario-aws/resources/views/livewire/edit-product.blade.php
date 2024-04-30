<div>
    <button wire:click="openModal">Editar Producto</button>

    @if ($showModal)
    <div style="background-color: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 8px;">
            <h2>Editando Producto: {{ $name }}</h2>
            <form wire:submit.prevent="saveChanges" enctype="multipart/form-data">
                <input type="text" wire:model="name" placeholder="Nombre" class="block w-full mb-2">
                <textarea wire:model="description" placeholder="Descripción" class="block w-full mb-2"></textarea>
                <input type="text" wire:model="price" placeholder="Precio" class="block w-full mb-2">
                <input type="number" wire:model="quantity" placeholder="Cantidad" class="block w-full mb-2">

                <!-- Mostrar la imagen actual -->
                @if ($image)
                <img src="{{ asset('storage/images/products/' . $image) }}" alt="Imagen actual" class="mb-2">
                @endif

                <!-- Campo para actualizar la imagen -->
                <input type="file" wire:model="newImage" class="block w-full mb-2">

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Guardar Cambios</button>
                <button type="button" wire:click="closeModal" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">Cerrar</button>
            </form>
        </div>
    </div>
    @endif
</div>
