<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Historial de movimientos de stock</h1>
        <button wire:click="exportCsv" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition-transform transform hover:scale-105">
            <span class="material-icons text-sm">download</span> Exportar CSV
        </button>
    </div>

    {{-- Filtros --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
        <input type="text" wire:model.debounce.400ms="search" placeholder="Buscar producto o motivo..."
            class="form-input rounded-md shadow-sm block w-full text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">

        <select wire:model="filterType" class="form-select rounded-md shadow-sm block w-full text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
            <option value="">Todos los tipos</option>
            @foreach($types as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <select wire:model="filterProduct" class="form-select rounded-md shadow-sm block w-full text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
            <option value="">Todos los productos</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('id')" class="focus:outline-none">
                            ID @if($sortField==='id') {{ $sortDirection==='asc' ? '▲' : '▼' }} @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">Producto</th>
                    <th class="px-4 py-3">Usuario</th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('transaction_type')" class="focus:outline-none">
                            Tipo @if($sortField==='transaction_type') {{ $sortDirection==='asc' ? '▲' : '▼' }} @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('quantity')" class="focus:outline-none">
                            Cantidad @if($sortField==='quantity') {{ $sortDirection==='asc' ? '▲' : '▼' }} @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">Antes → Después</th>
                    <th class="px-4 py-3">Motivo</th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('created_at')" class="focus:outline-none">
                            Fecha @if($sortField==='created_at') {{ $sortDirection==='asc' ? '▲' : '▼' }} @endif
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3">{{ $t->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $t->product->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $t->user->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $typeMap = [
                                'entrada'           => ['label'=>'Entrada',    'class'=>'bg-green-100 text-green-700'],
                                'entrada_manual'    => ['label'=>'Entrada manual','class'=>'bg-green-100 text-green-700'],
                                'venta'             => ['label'=>'Venta',      'class'=>'bg-blue-100 text-blue-700'],
                                'salida'            => ['label'=>'Salida',     'class'=>'bg-red-100 text-red-700'],
                                'devolucion_pedido' => ['label'=>'Devolución', 'class'=>'bg-yellow-100 text-yellow-700'],
                            ];
                            $info = $typeMap[$t->transaction_type] ?? ['label'=>$t->transaction_type,'class'=>'bg-gray-100 text-gray-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $info['class'] }}">{{ $info['label'] }}</span>
                    </td>
                    <td class="px-4 py-3 font-bold
                        @if(in_array($t->transaction_type,['entrada','entrada_manual','devolucion_pedido'])) text-green-600
                        @else text-red-600 @endif">
                        @if(in_array($t->transaction_type,['entrada','entrada_manual','devolucion_pedido'])) +@else -@endif{{ $t->quantity }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $t->before_quantity }} → {{ $t->after_quantity }}</td>
                    <td class="px-4 py-3 text-xs text-left max-w-xs truncate" title="{{ $t->description }}">
                        {{ $t->reason ?? '—' }}
                        @if($t->description)
                            <span class="text-gray-400"> — {{ Str::limit($t->description, 40) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-gray-400">No hay movimientos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $transactions->links() }}</div>
    </div>
</div>
