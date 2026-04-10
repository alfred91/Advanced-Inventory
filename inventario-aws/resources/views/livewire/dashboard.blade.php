@php
    $circ    = 282.74;
    $safe    = max($totalOrders, 1);
    $pDash   = round(($pendingOrders   / $safe) * $circ, 2);
    $cDash   = round(($completedOrders / $safe) * $circ, 2);
    $xDash   = round(($cancelledOrders / $safe) * $circ, 2);
    $cOff    = round(-$pDash, 2);
    $xOff    = round(-($pDash + $cDash), 2);
    $pPct    = $totalOrders > 0 ? round($pendingOrders   / $totalOrders * 100) : 0;
    $cPct    = $totalOrders > 0 ? round($completedOrders / $totalOrders * 100) : 0;
    $xPct    = $totalOrders > 0 ? round($cancelledOrders / $totalOrders * 100) : 0;
@endphp

<div class="space-y-5">

    {{-- Cabecera --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Panel de control</h1>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 capitalize">{{ now()->isoFormat('dddd, D [de] MMMM') }}</p>
        </div>
        <a href="{{ route('sales.tpv') }}"
           class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all hover:shadow-md">
            <span class="material-icons text-[18px]">point_of_sale</span> Abrir TPV
        </a>
    </div>

    {{-- KPI Row 1 — 4 cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <a href="{{ route('products.index') }}"
           class="group relative bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-icons text-[22px]">inventory</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Productos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $totalProducts }}</p>
                </div>
            </div>
            <div class="absolute -right-2 -bottom-2 text-blue-50 dark:text-blue-900/20 group-hover:text-blue-100 dark:group-hover:text-blue-900/30 transition-colors">
                <span class="material-icons text-7xl select-none">inventory</span>
            </div>
        </a>

        <a href="{{ route('orders.index') }}"
           class="group relative bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-icons text-[22px]">receipt_long</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Pedidos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $totalOrders }}</p>
                </div>
            </div>
            <div class="absolute -right-2 -bottom-2 text-emerald-50 dark:text-emerald-900/20 group-hover:text-emerald-100 transition-colors">
                <span class="material-icons text-7xl select-none">receipt_long</span>
            </div>
        </a>

        <a href="{{ route('customers.index') }}"
           class="group relative bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-icons text-[22px]">people</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Clientes</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $totalCustomers }}</p>
                </div>
            </div>
            <div class="absolute -right-2 -bottom-2 text-purple-50 dark:text-purple-900/20 group-hover:text-purple-100 transition-colors">
                <span class="material-icons text-7xl select-none">people</span>
            </div>
        </a>

        <a href="{{ route('suppliers.index') }}"
           class="group relative bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="flex items-center gap-3">
                <div class="bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-icons text-[22px]">local_shipping</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Proveedores</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">{{ $totalSuppliers }}</p>
                </div>
            </div>
            <div class="absolute -right-2 -bottom-2 text-amber-50 dark:text-amber-900/20 group-hover:text-amber-100 transition-colors">
                <span class="material-icons text-7xl select-none">local_shipping</span>
            </div>
        </a>

    </div>

    {{-- KPI Row 2 — 3 métricas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <a href="{{ route('stock.manager') }}"
           class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-4">
            <div class="bg-teal-100 dark:bg-teal-900/50 text-teal-600 dark:text-teal-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform shrink-0">
                <span class="material-icons text-[22px]">savings</span>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Valor de stock</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($stockValue, 0, ',', '.') }} €</p>
            </div>
        </a>

        <a href="{{ route('orders.index') }}"
           class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-4">
            <div class="bg-sky-100 dark:bg-sky-900/50 text-sky-600 dark:text-sky-400 p-2.5 rounded-xl group-hover:scale-110 transition-transform shrink-0">
                <span class="material-icons text-[22px]">trending_up</span>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium uppercase tracking-wide">Ventas 30 días</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ number_format($salesLast30, 0, ',', '.') }} €</p>
            </div>
        </a>

        @if($lowStockCount > 0)
        <a href="{{ route('stock.manager') }}"
           class="group bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all flex items-center gap-4">
            <div class="bg-red-100 dark:bg-red-900/50 text-red-500 p-2.5 rounded-xl animate-pulse shrink-0">
                <span class="material-icons text-[22px]">warning</span>
            </div>
            <div>
                <p class="text-xs text-red-500 font-medium uppercase tracking-wide">Alerta de stock</p>
                <p class="text-xl font-bold text-red-700 dark:text-red-300">{{ $lowStockCount }} productos</p>
            </div>
        </a>
        @else
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 p-2.5 rounded-xl shrink-0">
                <span class="material-icons text-[22px]">check_circle</span>
            </div>
            <div>
                <p class="text-xs text-emerald-600 font-medium uppercase tracking-wide">Stock OK</p>
                <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Todo sobre el mínimo</p>
            </div>
        </div>
        @endif

    </div>

    {{-- Fila inferior: 3 columnas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Col 1: Últimos pedidos --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Últimos pedidos</h2>
                <a href="{{ route('orders.index') }}" class="text-xs text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition-colors">Ver todos →</a>
            </div>
            @if($recentOrders->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">Sin pedidos aún.</p>
            @else
            <ul class="divide-y divide-gray-50 dark:divide-gray-700">
                @foreach($recentOrders as $order)
                <li class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                    @if($order->status === 'completed')
                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center shrink-0">
                        <span class="material-icons text-sm text-emerald-600 dark:text-emerald-400">check_circle</span>
                    </div>
                    @elseif($order->status === 'pending')
                    <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center shrink-0">
                        <span class="material-icons text-sm text-amber-500 dark:text-amber-400">hourglass_empty</span>
                    </div>
                    @else
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center shrink-0">
                        <span class="material-icons text-sm text-red-500 dark:text-red-400">cancel</span>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ $order->customer->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">#{{ $order->id }} · {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200 shrink-0">{{ number_format($order->total_amount,2,',','.') }} €</p>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Col 2: Estado pedidos (donut) --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-5"
             x-data="{ view: 'donut' }">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Estado de pedidos</h2>
                <button @click="view = view === 'donut' ? 'bars' : 'donut'"
                        title="Cambiar vista"
                        class="text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span class="material-icons text-[20px]" x-text="view === 'donut' ? 'bar_chart' : 'donut_large'"></span>
                </button>
            </div>

            {{-- Donut --}}
            <div x-show="view === 'donut'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="flex justify-center">
                    <div class="relative w-32 h-32">
                        <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                            <circle cx="60" cy="60" r="45" fill="none" stroke="#e5e7eb" stroke-width="16" class="dark:stroke-gray-700"/>
                            @if($totalOrders > 0)
                            <circle cx="60" cy="60" r="45" fill="none" stroke="#f59e0b" stroke-width="16"
                                stroke-dasharray="{{ $pDash }} {{ $circ }}" stroke-dashoffset="0"/>
                            <circle cx="60" cy="60" r="45" fill="none" stroke="#10b981" stroke-width="16"
                                stroke-dasharray="{{ $cDash }} {{ $circ }}" stroke-dashoffset="{{ $cOff }}"/>
                            <circle cx="60" cy="60" r="45" fill="none" stroke="#ef4444" stroke-width="16"
                                stroke-dasharray="{{ $xDash }} {{ $circ }}" stroke-dashoffset="{{ $xOff }}"/>
                            @endif
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalOrders }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wide">total</span>
                        </div>
                    </div>
                </div>
                <ul class="mt-4 space-y-2.5">
                    <li class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span> Pendientes
                        </span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $pendingOrders }} <span class="text-xs text-gray-400 font-normal">({{ $pPct }}%)</span></span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span> Completados
                        </span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $completedOrders }} <span class="text-xs text-gray-400 font-normal">({{ $cPct }}%)</span></span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-red-500 shrink-0"></span> Cancelados
                        </span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $cancelledOrders }} <span class="text-xs text-gray-400 font-normal">({{ $xPct }}%)</span></span>
                    </li>
                </ul>
            </div>

            {{-- Barras --}}
            <div x-show="view === 'bars'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Pendientes</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $pendingOrders }} <span class="text-xs text-gray-400">({{ $pPct }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-amber-400 h-3 rounded-full" style="width: {{ $pPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Completados</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $completedOrders }} <span class="text-xs text-gray-400">({{ $cPct }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $cPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Cancelados</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $cancelledOrders }} <span class="text-xs text-gray-400">({{ $xPct }}%)</span></span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-red-500 h-3 rounded-full" style="width: {{ $xPct }}%"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 text-right">Total: {{ $totalOrders }}</p>
            </div>
        </div>

        {{-- Col 3: Stock crítico + movimientos --}}
        <div class="space-y-4">

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-1.5">
                        <span class="material-icons text-red-500 text-[16px]">warning</span> Stock crítico
                    </h2>
                    <a href="{{ route('stock.manager') }}" class="text-xs text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition-colors">Gestionar →</a>
                </div>
                @if($topLowStock->isEmpty())
                <div class="px-5 py-4 flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                    <span class="material-icons text-base">check_circle</span>
                    <span class="text-sm font-medium">Todo en orden</span>
                </div>
                @else
                <ul class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($topLowStock as $p)
                    <li class="flex items-center justify-between px-5 py-2.5 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $p->name }}</span>
                        <span class="shrink-0 ml-2 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full">
                            {{ $p->quantity }}/{{ $p->minimum_stock }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Movimientos recientes</h2>
                    <a href="{{ route('stock.history') }}" class="text-xs text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition-colors">Historial →</a>
                </div>
                @if($recentMovements->isEmpty())
                <p class="text-sm text-gray-400 px-5 py-4">Sin movimientos aún.</p>
                @else
                <ul class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($recentMovements as $mov)
                    <li class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                        @if(in_array($mov->transaction_type, ['entrada','entrada_manual','devolucion_pedido']))
                        <span class="shrink-0 w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold flex items-center justify-center">+{{ $mov->quantity }}</span>
                        @else
                        <span class="shrink-0 w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 text-xs font-bold flex items-center justify-center">-{{ $mov->quantity }}</span>
                        @endif
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-200 truncate">{{ $mov->product->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $mov->before_quantity }}→{{ $mov->after_quantity }} · {{ $mov->created_at->format('d/m H:i') }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

        </div>
    </div>
</div>
