<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles_vista_reportes.css') }}">
    <title>Reportes</title>
</head>
<body>
    <h1>Reportes de Ingresos</h1>
    {{-- <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Centro</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Egreso</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingresos as $ingreso)
            <tr>
                <td>{{ $ingreso->id }}</td>
                <td>{{ $ingreso->nombre_centro }}</td>
                <td>{{ $ingreso->fecha_ingreso }}</td>
                <td>{{ $ingreso->fecha_egreso }}</td>
                <td>{{ $ingreso->estado }}</td>
            </tr>
            @endforeach
        </tbody>
    </table> --}}
</body>
</html>
