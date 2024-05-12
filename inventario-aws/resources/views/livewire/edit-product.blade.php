<div>
    <button wire:click="openModal" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        <i class="fas fa-edit mr-2"></i>
    </button>

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

                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select wire:model="category_id" id="category_id" class="mt-1 block w-full form-select">
                        <option value="">Seleccione una categoría</option>
                        @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                    <select wire:model="supplier_id" id="supplier_id" class="mt-1 block w-full form-select">
                        <option value="">Seleccione un proveedor</option>
                        @foreach(App\Models\Supplier::all() as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
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
                <div class="flex justify-end space-x-2">
                    <x-primary-button type="submit">
                        Guardar Cambios
                    </x-primary-button>
                    <x-secondary-button type="button" wire:click="closeModal">
                        Cerrar
                    </x-secondary-button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
