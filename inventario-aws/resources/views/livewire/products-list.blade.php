<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar por producto, categoría o proveedor..." wire:model="search" wire:input.debounce.500ms="reloadProducts">
        <div wire:loading class="spinner">Buscando...</div>
        <button wire:click="openModal(false)" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            <i class="material-icons mr-2">add</i> Añadir Producto
        </button>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('name')" class="focus:outline-none">
                            Nombre
                            @if($sortField === 'name')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('description')" class="focus:outline-none">
                            Descripción
                            @if($sortField === 'description')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('price')" class="focus:outline-none">
                            Precio
                            @if($sortField === 'price')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('quantity')" class="focus:outline-none">
                            Cantidad
                            @if($sortField === 'quantity')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('category_id')" class="focus:outline-none">
                            Categoría
                            @if($sortField === 'category_id')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('supplier_id')" class="focus:outline-none">
                            Proveedor
                            @if($sortField === 'supplier_id')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('minimum_stock')" class="focus:outline-none">
                            Stock Mínimo
                            @if($sortField === 'minimum_stock')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('isStockBelowMinimum')" class="focus:outline-none">
                            Alerta de Stock
                            @if($sortField === 'stock_alert')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('discount')" class="focus:outline-none">
                            Descuento
                            @if($sortField === 'discount')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th> <!-- Nuevo encabezado de descuento -->
                    <th class="px-6 py-3">Imagen</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $product)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ Str::limit($product->description, 50) }}</td>
                    <td class="px-6 py-4">{{ number_format($product->price, 2) }} €</td>
                    <td class="px-6 py-4">{{ $product->quantity }}</td>
                    <td class="px-6 py-4">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $product->supplier->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $product->minimum_stock }}</td>
                    <td class="px-6 py-4">
                        @if($product->quantity <= $product->minimum_stock)
                            <span class="text-red-500">
                                <i class="material-icons">error</i> {{ $product->quantity }} / {{ $product->minimum_stock }}
                            </span>
                            @else
                            <span class="text-green-500">
                                <i class="material-icons">check_circle</i> {{ $product->quantity }} / {{ $product->minimum_stock }}
                            </span>
                            @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->discount > 0)
                        {{ $product->discount }}%
                        @endif
                    </td> <!-- Mostrar el descuento solo si es mayor que 0 -->
                    <td class="px-6 py-4">
                        <div class="w-20 h-20 overflow-hidden rounded-lg flex items-center justify-center">
                            <img src="{{ Storage::url($product->image) }}" alt="Imagen de Producto" class="h-full w-auto object-contain transition-transform transform hover:scale-110 hover:brightness-110 cursor-pointer" wire:click="openImageModal('{{ Storage::url($product->image) }}')">
                        </div>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button wire:click="openModal(true, {{ $product->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-110 flex items-center justify-center">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-110 flex items-center justify-center" onclick="confirm('¿Está seguro de que desea eliminar este producto?') || event.stopImmediatePropagation()" wire:click="deleteProduct({{ $product->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Modal Ver imagen -->
    @if ($showImageModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeImageModal">
        <div class="relative p-4 max-w-lg w-full mx-2">
            <div class="flex justify-center">
                <img src="{{ $currentImage }}" alt="Imagen del Producto" class="rounded-md max-h-screen" style="background-color: transparent;">
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Crear/Editar Producto -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Editando Producto: ' . $name : 'Crear Nuevo Producto' }}</h2>
            <form wire:submit.prevent="saveProduct" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select wire:model="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea wire:model="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                        <select wire:model="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Seleccione un proveedor</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Precio (€)</label>
                        <input type="number" wire:model.lazy="price" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" step="0.01" min="0">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" min="0" wire:model="quantity" id="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                        <input type="number" wire:model="minimum_stock" id="minimum_stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" min="0">
                        @error('minimum_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700">Descuento (%)</label>
                        <input type="number" wire:model="discount" id="discount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" min="0" max="100">
                        @error('discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @if ($isEdit && $image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Imagen Actual</label>
                        <img src="{{ $image }}" alt="Imagen actual" class="w-20 h-20 object-cover rounded-md shadow-sm">
                    </div>
                    @endif
                    <div>
                        <label for="newImage" class="block text-sm font-medium text-gray-700">Nueva Imagen</label>
                        <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @error('newImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
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
