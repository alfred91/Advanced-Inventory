<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplicación de Inventario Avanzado</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-cover bg-center text-white" style="background-image: url('{{ asset('storage/inventario.jpg') }}');">
    <div class="flex flex-col min-h-screen justify-between bg-black bg-opacity-60">
        
        <!-- Header -->
        <header class="py-6 bg-black bg-opacity-75 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center items-center">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 mr-3 transition-transform transform hover:scale-110 drop-shadow-md">
                <h1 class="text-3xl font-semibold text-center text-white">Inventario Avanzado</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col items-center justify-center text-center px-4">
            <div class="mb-8 max-w-lg w-full">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-24 mx-auto mb-4 transition-transform transform hover:scale-110 drop-shadow-lg">
                <h2 class="text-4xl font-bold mb-4">Bienvenido a la Aplicación de Inventario Avanzado</h2>
                <p class="text-lg mb-6">Gestiona tus productos de forma eficiente con nuestra solución integral.</p>
                
                <!-- Button Container -->
                <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
                    @auth
                    <a href="{{ url('/products') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-4 px-6 rounded-lg shadow-lg transform transition hover:scale-105 flex items-center justify-center space-x-2">
                        <span>Ir a Productos</span>
                    </a>
                    @else
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <a href="{{ route('login') }}" class="bg-white text-blue-700 font-bold py-4 px-6 rounded-lg shadow-lg transform transition hover:scale-105 hover:bg-gray-200 flex items-center justify-center space-x-2">
                            <span class="material-icons">login</span>
                            <span>Log in</span>
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-white text-blue-700 font-bold py-4 px-6 rounded-lg shadow-lg transform transition hover:scale-105 hover:bg-gray-200 flex items-center justify-center space-x-2">
                            <span class="material-icons">person_add</span>
                            <span>Registro</span>
                        </a>
                        @endif
                        
                        <!-- Dropdown Button -->
                        <div class="relative">
                            <button id="dropdownButton" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg transform transition hover:scale-105 flex items-center justify-center space-x-2">
                                <span class="material-icons">account_circle</span>
                                <span>DEMO</span>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="dropdownMenu" class="hidden absolute mt-2 w-full bg-white rounded-lg shadow-lg text-black z-10">
                                <a href="{{ route('login.demo', ['email' => 'ventas@gmail.com', 'password' => '12345678']) }}" class="block px-4 py-2 hover:bg-gray-200">Ventas</a>
                                <a href="{{ route('login.demo', ['email' => 'mozo_almacen@gmail.com', 'password' => '12345678']) }}" class="block px-4 py-2 hover:bg-gray-200">Mozo de Almacén</a>
                                <a href="{{ route('login.demo', ['email' => 'admin@gmail.com', 'password' => '12345678']) }}" class="block px-4 py-2 hover:bg-gray-200">Administrador</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Script -->
                    <script>
                        const dropdownButton = document.getElementById('dropdownButton');
                        const dropdownMenu = document.getElementById('dropdownMenu');
                        dropdownButton.addEventListener('click', () => {
                            dropdownMenu.classList.toggle('hidden');
                        });
                        document.addEventListener('click', (event) => {
                            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                                dropdownMenu.classList.add('hidden');
                            }
                        });
                    </script>
                    @endauth
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center py-4 bg-black bg-opacity-75 shadow-md">
            <p class="text-sm">© 2024 Aplicación de Inventario Avanzado. Todos los derechos reservados.</p>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
