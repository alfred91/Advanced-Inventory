<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-auto md:w-auto text-center" placeholder="Buscar por ID, nombre, email, teléfono, DNI..." wire:model="search" wire:input.debounce.500ms="reloadCustomers">
        <button wire:click="showCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
            <i class="material-icons mr-2">add</i> Añadir Cliente
        </button>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
            <thead class="text-m text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                        <button wire:click="sortBy('dni')" class="focus:outline-none">
                            DNI/CIF
                            @if($sortField === 'dni')
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
                            NOMBRE
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
                            EMAIL
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
                            TELÉFONO
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
                            DIRECCIÓN
                            @if($sortField === 'address')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('role')" class="focus:outline-none">
                            ROL
                            @if($sortField === 'role')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">
                        <button wire:click="sortBy('orders_count')" class="focus:outline-none">
                            PEDIDOS
                            @if($sortField === 'orders_count')
                            @if($sortDirection === 'asc')
                            &#9650;
                            @else
                            &#9660;
                            @endif
                            @endif
                        </button>
                    </th>
                    <th class="px-6 py-3">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-4">{{ $customer->id }}</td>
                    <td class="px-4 py-4">{{ $customer->dni }}</td>
                    <td class="px-4 py-4">{{ $customer->name }}</td>
                    <td class="px-4 py-4">{{ $customer->email }}</td>
                    <td class="px-4 py-4">{{ $customer->phone_number }}</td>
                    <td class="px-4 py-4">{{ $customer->address }}</td>
                    <td class="px-4 py-4">{{ $this->getTranslatedRole($customer->role) }}</td>
                    <td class="px-4 py-4">
                        @if($customer->orders()->count() > 0)
                        <div class="flex items-center gap-2 justify-center">
                            {{ $customer->orders()->count() }}
                            <button wire:click="showCustomerOrders({{ $customer->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                                <i class="material-icons">visibility</i>
                            </button>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2 justify-center">
                        <button wire:click="showEditModal({{ $customer->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-105">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded shadow-lg transition-transform transform hover:scale-110" onclick="confirm('¿Está seguro de que desea eliminar este cliente?') || event.stopImmediatePropagation()" wire:click="deleteCustomer({{ $customer->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Modal Crear/Editar Clientes -->
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">{{ $isEdit ? 'Editar Cliente: ' . $name : 'Nuevo Cliente' }}</h2>
            <form wire:submit.prevent="saveCustomer" class="space-y-4">
                <div>
                    <label for="dni" class="block text-sm font-medium text-gray-700">DNI/CIF</label>
                    <input type="text" wire:model.defer="dni" id="dni" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('dni') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
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
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select wire:model.defer="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="normal">Particular</option>
                        <option value="professional">Profesional</option>
                    </select>
                    @error('role') <span class="error text-red-500">{{ $message }}</span> @enderror
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


    <!-- Modal Pedidos del cliente-->
    @if ($showOrdersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeOrdersModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Pedidos del Cliente</h2>
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">ID Pedido</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Monto Total</th>
                            <th class="px-6 py-3">Productos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer" wire:click="showOrderDetails({{ $order->id }})">
                            <td class="px-6 py-4">{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">{{ $order->translated_status }}</td>
                            <td class="px-6 py-4">{{ $this->calculateTotalAmountWithDiscount($order) }} €</td>
                            <td class="px-6 py-4">
                                <ul>
                                    @foreach ($order->products as $product)
                                    <li>{{ $product->name }} ({{ $product->pivot->quantity }} x {{ number_format($product->pivot->unit_price, 2) }} €)</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" wire:click="closeOrdersModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detalles del Pedido -->
    @if ($showOrderDetailsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:click.self="closeOrderDetailsModal">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-2">
            <h2 class="text-xl font-semibold mb-4">Detalles del Pedido: #{{ $orderDetails->id }}</h2>
            <div class="space-y-4">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <input type="text" id="customer_name" value="{{ $orderDetails->customer->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" disabled>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <input type="text" id="status" value="{{ $orderDetails->translated_status }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" disabled>
                </div>
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700">Fecha del Pedido</label>
                    <input type="text" id="order_date" value="{{ \Carbon\Carbon::parse($orderDetails->order_date)->format('d-m-Y') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" disabled>
                </div>
                <div>
                    <label for="total_amount" class="block text-sm font-medium text-gray-700">Monto Total</label>
                    <input type="text" id="total_amount" value="{{ $this->calculateTotalAmountWithDiscount($orderDetails) }} €" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Productos</label>
                    <div class="max-h-60 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    @if($orderDetails->customer->role === 'professional')
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio con Descuento</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orderDetails->products as $product)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $product->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($product->pivot->unit_price, 2) }} €</td>
                                    <td class="px-4 py-2 text-sm text-gray-700">{{ $product->pivot->quantity }}</td>
                                    @if($orderDetails->customer->role === 'professional')
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        {{ number_format($product->pivot->unit_price * (1 - ($product->discount / 100)), 2) }} €
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" wire:click="closeOrderDetailsModal" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>
