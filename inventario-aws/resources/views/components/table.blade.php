<!-- resources/views/components/table.blade.php -->

<div class="table-responsive mt-3">
    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach ($headers as $header)
                <th>{{ $header }}</th>
                @endforeach
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                @foreach ($row as $cell)
                <td>{{ $cell }}</td>
                @endforeach
                <td>
                    <a href="{{ route('products.show', $row['id']) }}" class="btn btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('products.edit', $row['id']) }}" class="btn btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('products.destroy', $row['id']) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de querer eliminar este producto?')"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
