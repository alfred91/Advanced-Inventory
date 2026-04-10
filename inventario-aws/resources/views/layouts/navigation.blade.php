<nav x-data="{ open: false }" class="bg-white border-b border-gray-400 shadow dark:bg-blue-900 dark:border-blue-800">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @php $lowStockCount = \App\Models\Product::whereColumn('quantity', '<=', 'minimum_stock')->count(); @endphp
        <div class="flex items-center h-16 gap-4">

            <!-- Logo (izquierda) -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('products.index') }}">
                    <img src="{{ asset('logo.png') }}" class="block w-12 h-12 transition-transform transform hover:scale-110 hover:brightness-110" alt="Logo">
                </a>
            </div>

            <!-- Navigation Links (centro) -->
            <div class="flex-1 flex justify-center">
                <div class="hidden space-x-6 sm:-my-px sm:flex">
                    @if(Auth::user()->role === 'administrativo')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">dashboard</span>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">inventory</span>
                        {{ __('Productos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">category</span>
                        {{ __('Categorias') }}
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">local_shipping</span>
                        {{ __('Proveedores') }}
                    </x-nav-link>
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">receipt</span>
                        {{ __('Pedidos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">people</span>
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales.tpv')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">point_of_sale</span>
                        {{ __('TPV') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stock.manager')" :active="request()->routeIs('stock.manager')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="relative">
                            <span class="material-icons transition-transform transform hover:scale-110">inventory_2</span>
                            @if($lowStockCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $lowStockCount }}</span>
                            @endif
                        </span>
                        {{ __('Stock') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stock.history')" :active="request()->routeIs('stock.history')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">history</span>
                        {{ __('Historial') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role === 'mozo_almacen')
                    <x-nav-link :href="route('stock.manager')" :active="request()->routeIs('stock.manager')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="relative">
                            <span class="material-icons transition-transform transform hover:scale-110">inventory_2</span>
                            @if($lowStockCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $lowStockCount }}</span>
                            @endif
                        </span>
                        {{ __('Stock') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stock.history')" :active="request()->routeIs('stock.history')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">history</span>
                        {{ __('Historial') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role === 'ventas')
                    <x-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales.tpv')" class="text-gray-700 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        <span class="material-icons transition-transform transform hover:scale-110">point_of_sale</span>
                        {{ __('TPV') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Derecha: tema + usuario -->
            <div
                x-data="{
                    theme: localStorage.getItem('theme') || 'system',
                    init() { this.apply(this.theme); },
                    apply(t) {
                        this.theme = t;
                        localStorage.setItem('theme', t);
                        const dark = t === 'dark' || (t === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                        document.documentElement.classList.toggle('dark', dark);
                    }
                }"
                class="hidden sm:flex items-center gap-4 shrink-0 pl-6 border-l border-gray-200 dark:border-gray-700"
            >
                <!-- Toggle de tema: 3 botones compactos -->
                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5 gap-0.5 border border-gray-200 dark:border-gray-700">
                    <button @click="apply('light')"
                        :class="theme==='light' ? 'bg-white dark:bg-gray-600 shadow text-amber-500' : 'text-gray-400 hover:text-amber-500'"
                        class="flex items-center justify-center w-8 h-8 rounded-md transition-all duration-150"
                        title="Tema claro">
                        <span class="material-icons text-[18px]">light_mode</span>
                    </button>
                    <button @click="apply('system')"
                        :class="theme==='system' ? 'bg-white dark:bg-gray-600 shadow text-blue-500' : 'text-gray-400 hover:text-blue-500'"
                        class="flex items-center justify-center w-8 h-8 rounded-md transition-all duration-150"
                        title="Seguir sistema">
                        <span class="material-icons text-[18px]">laptop</span>
                    </button>
                    <button @click="apply('dark')"
                        :class="theme==='dark' ? 'bg-white dark:bg-gray-600 shadow text-indigo-500' : 'text-gray-400 hover:text-indigo-500'"
                        class="flex items-center justify-center w-8 h-8 rounded-md transition-all duration-150"
                        title="Tema oscuro">
                        <span class="material-icons text-[18px]">dark_mode</span>
                    </button>
                </div>

                <!-- Avatar + dropdown usuario -->
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 group focus:outline-none">
                            {{-- Avatar circular con iniciales --}}
                            <span class="w-9 h-9 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shadow group-hover:ring-2 group-hover:ring-indigo-400 transition-all">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1)) }}
                            </span>
                            <div class="hidden lg:flex flex-col items-start leading-tight">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-100">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-400 capitalize">{{ Auth::user()->role ?? 'Usuario' }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <span class="material-icons text-sm text-gray-400">manage_accounts</span>
                            {{ __('Mi perfil') }}
                        </x-dropdown-link>
                        <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 hover:bg-red-50 dark:hover:bg-red-900/30 text-red-500">
                                    <span class="material-icons text-sm">logout</span>
                                    {{ __('Cerrar sesión') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:text-gray-400">
                    <svg class="w-6 h-6 transition-transform transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'administrativo')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">dashboard</span>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">inventory</span>
                {{ __('Productos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">category</span>
                {{ __('Categorias') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">local_shipping</span>
                {{ __('Proveedores') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">receipt</span>
                {{ __('Pedidos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">people</span>
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales.tpv')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">point_of_sale</span>
                {{ __('TPV') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock.manager')" :active="request()->routeIs('stock.manager')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">inventory_2</span>
                {{ __('Stock') }}
                @if($lowStockCount > 0) <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1">{{ $lowStockCount }}</span> @endif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock.history')" :active="request()->routeIs('stock.history')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">history</span>
                {{ __('Historial') }}
            </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'mozo_almacen')
            <x-responsive-nav-link :href="route('stock.manager')" :active="request()->routeIs('stock.manager')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">inventory_2</span>
                {{ __('Stock') }}
                @if($lowStockCount > 0) <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1">{{ $lowStockCount }}</span> @endif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stock.history')" :active="request()->routeIs('stock.history')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">history</span>
                {{ __('Historial') }}
            </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'ventas')
            <x-responsive-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales.tpv')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons transition-transform transform hover:scale-110">point_of_sale</span>
                {{ __('TPV') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="py-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1 mt-3">
                <x-responsive-nav-link :href="route('profile.edit')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
