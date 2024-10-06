<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-cover bg-center text-white dark:bg-gray-900" style="background-image: url('{{ asset('storage/inventario.jpg') }}');">
    <div class="min-h-screen flex flex-col bg-black bg-opacity-60 dark:bg-opacity-80"> 
        <!-- Header -->
        <header class="py-6 bg-black bg-opacity-75 shadow-md w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center items-center">
                <a href="/"> <!-- Enlace al logo -->
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 mr-3 transition-transform transform hover:scale-110 drop-shadow-md">
                </a>
                <h1 class="text-3xl font-semibold text-center">Inventario Avanzado</h1>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white bg-opacity-80 text-black shadow-md overflow-hidden sm:rounded-lg dark:bg-gray-800 dark:bg-opacity-60 dark:text-white">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-black bg-opacity-75 text-center py-4">
            <p class="text-sm">© 2024 Aplicación de Inventario Avanzado. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
