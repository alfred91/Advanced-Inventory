<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold">Lista de Productos</h1>
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar..." wire:model="search" wire:input.debounce.500ms="reloadProducts">
        @livewire('create-product')
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Descripción</th>
                    <th class="px-6 py-3">Precio</th>
                    <th class="px-6 py-3">Cantidad</th>
                    <th class="px-6 py-3">Imagen</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ $product->description }}</td>
                    <td class="px-6 py-4">{{ number_format($product->price, 2) }} €</td>
                    <td class="px-6 py-4">{{ $product->quantity }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen de Producto" class="w-20 h-auto">
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        @livewire('edit-product', ['productId' => $product->id], key($product->id))
                        <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded" onclick="confirm('Are you sure you want to delete this product?')" wire:click="deleteProduct({{ $product->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
</div>
