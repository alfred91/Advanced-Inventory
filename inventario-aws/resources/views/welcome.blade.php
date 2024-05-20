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

    <style>
        body {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: #fff;
        }

        header,
        footer {
            background: rgba(0, 0, 0, 0.75);
        }

        main h2 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .button-primary {
            background: #1E40AF;
            transition: background 0.3s ease;
        }

        .button-primary:hover {
            background: #2563EB;
        }

        .button-secondary {
            background: #64748B;
            transition: background 0.3s ease;
        }

        .button-secondary:hover {
            background: #94A3B8;
        }

        .shadow-custom {
            box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.25);
        }

    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex flex-col min-h-screen justify-between">
        <header class="py-6 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center items-center">
                <h1 class="text-xl font-semibold text-center">Inventario Avanzado</h1>
            </div>
        </header>

        <main class="flex-grow flex flex-col items-center justify-center">
            <div class="text-center">
                <h2 class="text-4xl font-bold mb-4">Bienvenido a la Aplicación de Inventario Avanzado</h2>
                <p class="text-lg mb-6">Gestiona tu inventario de forma eficiente y moderna con nuestra solución integral.</p>
                <div class="flex flex-col md:flex-row gap-4">
                    @auth
                    <a href="{{ url('/products') }}" class="button-primary text-white font-bold py-2 px-4 rounded-lg shadow-custom flex items-center justify-center space-x-2">
                        <span>Ir a Productos</span>
                    </a>
                    @else
                    <div class="flex flex-col items-center space-y-4">
                        <a href="{{ route('login') }}" class="button-primary text-white font-bold py-2 px-4 rounded-lg shadow-custom flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 8v6M23 11h-6" />
                            </svg>
                            <span>Log in</span>
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="button-secondary text-white font-bold py-2 px-4 rounded-lg shadow-custom flex items-center justify-center space-x-2">
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

        <footer class="text-center py-4 shadow-md">
            <p class="text-sm">© 2024 Aplicación de Inventario Avanzado. Todos los derechos reservados.</p>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
n
