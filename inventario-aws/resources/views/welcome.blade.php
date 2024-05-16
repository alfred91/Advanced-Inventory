<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplicación de Inventario Avanzado</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased text-gray-800 dark:text-white">
    <div class="flex flex-col min-h-screen justify-between">
        <header class="bg-white dark:bg-gray-800 py-6 shadow-md opacity-75">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center items-center">
                <h1 class="text-xl font-semibold text-center">Inventario Avanzado</h1>
            </div>
        </header>

        <main class="flex-grow">
            <div class="py-10 flex flex-col items-center justify-center">
                <h2 class="text-3xl font-bold mb-4">Bienvenido a la Aplicación de Inventario Avanzado</h2>
                <p class="text-lg mb-6">Gestiona tu inventario de forma eficiente y moderna con nuestra solución integral.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 flex items-center justify-center space-x-2">
                        <span>Dashboard</span>
                    </a>
                    @else
                    <div class="flex flex-col items-center space-y-4">
                        <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 8v6M23 11h-6" />
                            </svg>
                            <span>Log in</span>
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Register</span>
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-800 text-center py-4 shadow-md opacity-75">
            <p class="text-sm text-gray-600 dark:text-gray-400">© 2024 Aplicación de Inventario Avanzado. Todos los derechos reservados.</p>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
