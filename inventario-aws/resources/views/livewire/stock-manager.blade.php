<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Gestión de Stock</h1>

    <!-- Botón de alerta de stock bajo -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full md:w-auto" placeholder="Buscar por producto..." wire:model="search" wire:input.debounce.500ms="reloadProducts">
            <button wire:click="showLowStockModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                <i class="fas fa-exclamation-triangle mr-2"></i> Alerta de Stock Bajo
            </button>
        </div>
        <div wire:loading class="spinner">Buscando...</div>
    </div>

    <!-- Modal de productos con stock bajo -->
    @if ($showLowStockModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Productos con Stock Bajo</h2>
            <ul class="space-y-2">
                @foreach ($lowStockProducts as $product)
                <li class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold">{{ $product->name }}</p>
                            <p>Stock actual: {{ $product->quantity }} (Mínimo: {{ $product->minimum_stock }})</p>
                        </div>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen de Producto" class="w-16 h-auto rounded-lg">
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="flex justify-end mt-4">
                <button wire:click="closeLowStockModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">Cerrar</button>
            </div>
        </div>
    </div>
    @endif

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
                        Imagen
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
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 flex items-center gap-2">
                        {{ $product->name }}
                        @if($product->quantity < $product->minimum_stock)
                            <span class="text-red-500">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            @endif
                    </td>
                    <td class="px-6 py-4">{{ $product->description }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen de Producto" class="w-20 h-auto rounded-lg">
                    </td>
                    <td class="px-6 py-4">{{ $product->quantity }}</td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button wire:click="decrementStock({{ $product->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">-</button>
                        <button wire:click="incrementStock({{ $product->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">+</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
