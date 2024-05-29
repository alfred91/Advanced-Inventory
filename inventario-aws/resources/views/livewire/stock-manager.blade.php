<div class="container mx-auto p-4">
    <h1 class="text-xl font-semibold mb-4">Gestión de Stock</h1>

    <!-- Botón de alerta de stock bajo -->
    @if ($hasLowStock)
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4">
            <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full md:w-auto" placeholder="Buscar por producto..." wire:model="search" wire:input.debounce.500ms="reloadProducts">
            <button wire:click="openLowStockModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                <i class="fas fa-exclamation-triangle mr-2"></i> Alerta de Stock Bajo
            </button>
        </div>
        <div wire:loading class="spinner">Buscando...</div>
    </div>
    @endif

    <!-- Modal de productos con stock bajo -->
    @if ($showLowStockModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-2xl w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Productos con Stock Bajo</h2>
            <ul class="space-y-2 max-h-80 overflow-y-auto">
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

    <!-- Modal de cantidad personalizada -->
    @if ($showCustomQuantityModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Añadir Cantidad</h2>
            <div>
                <p class="mb-2">Producto: {{ $selectedProduct->name }}</p>
                <label for="customQuantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" wire:model="customQuantity" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                @error('customQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end mt-4">
                <button wire:click="closeCustomQuantityModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">Cancelar</button>
                <button wire:click="addCustomQuantity" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105 ml-2">Añadir</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de incidencia -->
    @if ($showIncidentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Registrar Incidencia</h2>
            <div>
                <p class="mb-2">Producto: {{ $selectedProduct->name }}</p>
                <label for="customQuantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" wire:model="customQuantity" min="1" max="{{ $selectedProduct->quantity }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                @error('customQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <label for="incidentReason" class="block text-sm font-medium text-gray-700">Motivo</label>
                <select wire:model="incidentReason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Seleccione un motivo</option>
                    <option value="robo">Robo</option>
                    <option value="perdida">Pérdida</option>
                    <option value="rotura">Rotura</option>
                </select>
                @error('incidentReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <label for="incidentDescription" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                <textarea wire:model="incidentDescription" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                @error('incidentDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end mt-4">
                <button wire:click="closeIncidentModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">Cancelar</button>
                <button wire:click="reportIncident" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105 ml-2">Registrar</button>
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
                    <th class="px-6 py-3">Imagen</th>
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
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        {{ $product->name }}
                        @if($product->quantity <= $product->minimum_stock)
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
                    <td class="px-6 py-4">
                        @if($product->quantity <= $product->minimum_stock)
                            <span class="text-red-500">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span>{{ $product->minimum_stock }}</span>
                            @else
                            <span class="text-green-500">
                                <i class="fas fa-check"></i>
                            </span>
                            <span>{{ $product->minimum_stock }}</span>
                            @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button wire:click="openIncidentModal({{ $product->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">-</button>
                            <button wire:click="openCustomQuantityModal({{ $product->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">+</button>
                        </div>
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
