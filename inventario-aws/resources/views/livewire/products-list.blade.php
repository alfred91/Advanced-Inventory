<!-- resources/views/livewire/products-list.blade.php -->
<div class="container p-4 mx-auto">
    <h1 class="text-xl font-semibold">Lista de Productos</h1>
    <table class="w-full table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Descripción</th>
                <th class="px-4 py-2">Precio</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Imagen</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td class="border px-4 py-2">{{ $product->name }}</td>
                <td class="border px-4 py-2">{{ $product->description }}</td>
                <td class="border px-4 py-2">{{ number_format($product->price, 2) }} €</td>
                <td class="border px-4 py-2">{{ $product->quantity }}</td>
                <td class="border px-4 py-2">
                    <img src="{{ asset('storage/' . $product->image) }}" style="width:100px; height:auto;"> </td>
                <td class="border px-4 py-2">
                    @livewire('edit-product', ['productId' => $product->id], key($product->id))
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
