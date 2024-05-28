<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-6 text-center">Seleccionar Categoría</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <div class="border p-4 rounded shadow-lg text-center cursor-pointer hover:shadow-2xl transition-shadow duration-300" wire:click="$emit('categorySelected', {{ $category->id }})">
            <div class="w-full h-36 overflow-hidden rounded-lg flex items-center justify-center">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-contain mb-4">
            </div>
            <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
        </div>
        @endforeach
    </div>
</div>
