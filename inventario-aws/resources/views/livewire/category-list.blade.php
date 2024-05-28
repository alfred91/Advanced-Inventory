<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-6 text-center">Categorías</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar categoría..." wire:model="search" wire:input.debounce.500ms="reloadCategories">
        <button wire:click="showCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Añadir Categoría
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <div class="border p-4 rounded shadow-lg text-center transition-transform transform hover:scale-105">
            <div class="w-full h-36 overflow-hidden rounded-lg flex items-center justify-center">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-contain mb-4 transition-transform transform hover:scale-110 hover:blur-sm">
            </div>
            <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
            <button wire:click="showEditModal({{ $category->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                Editar
            </button>
            <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded shadow-lg transition-transform transform hover:scale-110" onclick="confirm('¿Está seguro de que desea eliminar esta categoría?') || event.stopImmediatePropagation()" wire:click="deleteCategory({{ $category->id }})">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    <!-- Modal Crear/Editar Categorías -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Editar Categoría' : 'Nueva Categoría' }}</h2>
            <form wire:submit.prevent="saveCategory" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                @if ($isEdit && $currentImage)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Imagen Actual</label>
                    <img src="{{ asset( $currentImage) }}" alt="{{ $name }}" class="w-32 h-32 object-contain rounded-md shadow-sm transition-transform transform hover:scale-110 hover:blur-sm">
                </div>
                @endif
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Nueva Imagen</label>
                    <input type="file" wire:model.defer="image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('image') <span class="error text-red-500">{{ $message }}</span> @enderror
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
