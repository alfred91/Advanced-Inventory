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
<body class="font-sans antialiased bg-gradient-to-br from-blue-600 to-blue-900 text-white">
    <div class="flex flex-col min-h-screen justify-between">
        <header class="py-6 bg-opacity-75 bg-black shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center items-center">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 mr-3">
                <h1 class="text-2xl font-semibold text-center">Inventario Avanzado</h1>
            </div>
        </header>

        <main class="flex-grow flex flex-col items-center justify-center text-center">
            <div class="mb-8">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-24 mx-auto mb-4">
                <h2 class="text-4xl font-bold mb-4">Bienvenido a la Aplicación de Inventario Avanzado</h2>
                <p class="text-lg mb-6">Gestiona tu inventario de forma eficiente y moderna con nuestra solución integral.</p>
                <div class="flex flex-col md:flex-row gap-4">
                    @auth
                    <a href="{{ url('/products') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center justify-center space-x-2">
                        <span>Ir a Productos</span>
                    </a>
                    @else
                    <div class="flex flex-col items-center space-y-4">
                        <a href="{{ route('login') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 8v6M23 11h-6" />
                            </svg>
                            <span>Log in</span>
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center justify-center space-x-2">
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

        <footer class="text-center py-4 bg-opacity-75 bg-black shadow-md">
            <p class="text-sm">© 2024 Aplicación de Inventario Avanzado. Todos los derechos reservados.</p>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
