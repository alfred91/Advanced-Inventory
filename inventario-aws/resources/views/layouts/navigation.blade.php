<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow dark:bg-blue-900 dark:border-blue-800">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex justify-center flex-1">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <x-application-logo class="block w-auto h-9 fill-current text-gray-800 dark:text-gray-200" />
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(Auth::user()->role === 'administrativo')
                    <!-- Enlaces para Administrativo -->
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Productos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Proveedores') }}
                    </x-nav-link>
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Pedidos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Clientes') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role === 'mozo_almacen')
                    <!-- Enlaces para Mozo de Almacén -->
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Productos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Proveedores') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role === 'ventas')
                    <!-- Enlaces para Ventas (TPV) -->
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Productos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('Proveedores') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales-tpv.*')" class="text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ __('TPV') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-700 focus:outline-none dark:bg-blue-900 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white dark:hover:bg-blue-800">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -mr-2 sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
            <!-- Enlaces para Administrativo -->
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Productos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Proveedores') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Pedidos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'mozo_almacen')
            <!-- Enlaces para Mozo de Almacén -->
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Productos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Proveedores') }}
            </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'ventas')
            <!-- Enlaces para Ventas (TPV) -->
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Productos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Proveedores') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales.tpv')" :active="request()->routeIs('sales-tpv.*')" class="hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('Proveedores') }}
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
