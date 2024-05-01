<form action="{{ $route }}" method="POST" onsubmit="return confirm('¿Estás seguro de querer eliminar este producto?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="flex items-center justify-center p-2 text-white bg-red-500 rounded hover:bg-red-700 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Eliminar
    </button>
</form>
