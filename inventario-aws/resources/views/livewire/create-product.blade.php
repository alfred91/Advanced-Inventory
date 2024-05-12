<div>
    <x-primary-button wire:click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Añadir Nuevo Producto
    </x-primary-button>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg max-w-lg mx-auto">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Nuevo Producto</h3>
            <div class="mt-2">
                <form wire:submit.prevent="saveProduct">
                    <input type="text" wire:model.defer="name" placeholder="Nombre del producto" class="input input-bordered w-full mb-3" />
                    <input type="text" wire:model.defer="description" placeholder="Descripción del producto" class="input input-bordered w-full mb-3" />
                    <input type="number" wire:model.defer="price" placeholder="Precio" class="input input-bordered w-full mb-3" />
                    <input type="number" wire:model.defer="quantity" placeholder="Cantidad" class="input input-bordered w-full mb-3" />

                    <select wire:model="category_id" class="input input-bordered w-full mb-3">
                        <option value="">Seleccione una categoría</option>
                        @foreach(App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model="supplier_id" class="input input-bordered w-full mb-3">
                        <option value="">Seleccione un proveedor</option>
                        @foreach(App\Models\Supplier::all() as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>

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
