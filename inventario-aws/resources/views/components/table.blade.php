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
                @foreach ($row as $key => $cell)
                @if($key !== 'actions')
                <td>{{ $cell }}</td>
                @else
                <td>
                    @foreach ($cell as $action)
                    {!! $action !!}
                    @endforeach
                </td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
