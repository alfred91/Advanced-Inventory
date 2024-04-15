@extends('layouts.app')

@section('content')
<div class="container p-4 mx-auto">
    <h1 class="text-xl font-semibold">Lista de Productos</h1>
    <ul>
        @foreach ($products as $product)
            <li>{{ $product->name }} - {{ $product->description }}</li>
        @endforeach
    </ul>
</div>
@endsection
