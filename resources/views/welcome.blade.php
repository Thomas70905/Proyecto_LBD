<!-- filepath: resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clientes List</title>
</head>
<body>
    <h1>List of Clientes</h1>
    <ul>
        @forelse($clientes as $cliente)
            <li>
                {{ $cliente->nombre }} {{ $cliente->apellido }} |
                Tel: {{ $cliente->telefono }} |
                Email: {{ $cliente->email }}
            </li>
        @empty
            <li>No clientes found.</li>
        @endforelse
    </ul>
</body>
</html>