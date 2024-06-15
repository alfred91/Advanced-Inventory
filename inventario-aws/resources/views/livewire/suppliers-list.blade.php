<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto" placeholder="Buscar Proveedor..." wire:model="search" wire:input.debounce.500ms="reloadSuppliers">
        <button wire:click="openModal(false)" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            <span class="material-icons mr-2">add</span> Añadir Proveedor
        </button>
    </div>

    <div wire:loading.class="opacity-50">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">
                            <button wire:click="sortBy('id')" class="focus:outline-none">
                                ID
                                @if($sortField === 'id')
                                @if($sortDirection === 'asc')
                                &#9650;
                                @else
                                &#9660;
                                @endif
                                @endif
                            </button>
                        </th>
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
                            <button wire:click="sortBy('email')" class="focus:outline-none">
                                Email
                                @if($sortField === 'email')
                                @if($sortDirection === 'asc')
                                &#9650;
                                @else
                                &#9660;
                                @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3">
                            <button wire:click="sortBy('phone_number')" class="focus:outline-none">
                                Teléfono
                                @if($sortField === 'phone_number')
                                @if($sortDirection === 'asc')
                                &#9650;
                                @else
                                &#9660;
                                @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3">
                            <button wire:click="sortBy('address')" class="focus:outline-none">
                                Dirección
                                @if($sortField === 'address')
                                @if($sortDirection === 'asc')
                                &#9650;
                                @else
                                &#9660;
                                @endif
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3">Imagen</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $supplier->id }}</td>
                        <td class="px-6 py-4">{{ $supplier->name }}</td>
                        <td class="px-6 py-4">{{ $supplier->email }}</td>
                        <td class="px-6 py-4">{{ $supplier->phone_number }}</td>
                        <td class="px-6 py-4">{{ $supplier->address }}</td>
                        <td class="px-6 py-4">
                            @if ($supplier->image)
                            <div class="w-20 h-20 overflow-hidden rounded-lg flex items-center justify-center cursor-pointer">
                                <img src="{{ Storage::url($supplier->image) }}" alt="Proveedor" class="max-h-full max-w-full object-contain transition-transform transform hover:scale-110 hover:brightness-110" wire:click="openImageModal('{{ Storage::url($supplier->image) }}')">
                            </div>
                            @else
                            <div class="w-20 h-20 overflow-hidden rounded-lg flex items-center justify-center cursor-pointer">
                                <img src="{{ asset('storage/suppliers/default.gif') }}" alt="Proveedor" class="max-h-full max-w-full object-contain transition-transform transform hover:scale-110 hover:brightness-110" wire:click="openImageModal('{{ asset('storage/suppliers/default.gif') }}')">
                            </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 flex items-center gap-2">
                            <button wire:click="openModal(true, {{ $supplier->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105 flex items-center justify-center">
                                <span class="material-icons">edit</span>
                            </button>
                            <button class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-110 flex items-center justify-center" onclick="confirm('¿Está seguro de que desea eliminar este proveedor?') || event.stopImmediatePropagation()" wire:click="deleteSupplier({{ $supplier->id }})">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Proveedor -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-2xl font-semibold mb-4 text-center">{{ $isEdit ? 'Editando Proveedor: ' . $name : 'Crear Nuevo Proveedor' }}</h2>
            <form wire:submit.prevent="saveSupplier" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" wire:model.defer="phone_number" id="phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('phone_number') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" wire:model.defer="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('address') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @if ($isEdit && $image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Imagen Actual</label>
                        <img src="{{ Storage::url($image) }}" alt="Imagen actual" class="w-32 h-32 object-contain rounded-md shadow-sm transition-transform transform hover:scale-110">
                    </div>
                    @endif
                    <div class="flex items-end">
                        <div>
                            <label for="newImage" class="block text-sm font-medium text-gray-700">Nueva Imagen</label>
                            <input type="file" wire:model="newImage" id="newImage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('newImage') <span class="error text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Productos</label>
                    <div class="max-h-60 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    <th scope="col" class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $product['name'] }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <input type="number" step="0.01" min="0.01" wire:model.defer="products.{{ $loop->index }}.price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-4 py-2">
                                        <button type="button" wire:click="removeProduct({{ $product['id'] }})" class="text-red-500 hover:text-red-700"><i class="material-icons">delete</i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="openAddProductModal" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                        Añadir Producto
                    </button>
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

    <!-- Modal Añadir Producto -->
    @if ($showAddProductModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeAddProductModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2" wire:click.stop>
            <h2 class="text-2xl font-semibold mb-4 text-center">Añadir Producto</h2>
            <form wire:submit.prevent="addProduct" class="space-y-4">
                <div>
                    <label for="newProductName" class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
                    <input type="text" wire:model.defer="newProductName" id="newProductName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('newProductName') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="newProductDescription" class="block text-sm font-medium text-gray-700">Descripción del Producto</label>
                    <textarea wire:model.defer="newProductDescription" id="newProductDescription" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    @error('newProductDescription') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="newProductPrice" class="block text-sm font-medium text-gray-700">Precio</label>
                    <input type="number" step="0.01" min="0" wire:model.defer="newProductPrice" id="newProductPrice" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('newProductPrice') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="newProductQuantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input type="number" min="0" wire:model.defer="newProductQuantity" id="newProductQuantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('newProductQuantity') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="newProductMinimumStock" class="block text-sm font-medium text-gray-700">Stock Mínimo</label>
                    <input type="number" min="0" wire:model.defer="newProductMinimumStock" id="newProductMinimumStock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('newProductMinimumStock') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="newProductCategoryId" class="block text-sm font-medium text-gray-700">Categoría</label>
                    <select wire:model.defer="newProductCategoryId" id="newProductCategoryId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('newProductCategoryId') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" wire:click="closeAddProductModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Cerrar
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal Ver Imagen -->
    @if ($showImageModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeImageModal">
        <div class="relative p-4 max-w-lg w-full mx-2">
            <div class="flex justify-center">
                <img src="{{ $currentImage }}" alt="Imagen del Proveedor" class="rounded-md max-h-screen" style="background-color: transparent;">
            </div>
        </div>
    </div>
    @endif

</div>
