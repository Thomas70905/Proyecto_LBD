<!-- filepath: resources/views/layouts/app.blade.php -->
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Document')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Logo</a>
                <div class="d-flex align-items-center d-lg-none">
                    <a class="nav-link me-3" href="/login">Iniciar sesión</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/employees">Administrar Empleados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/inventory">Administrar Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/services">Administrar Servicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/appointments">Administrar Citas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Historial Clinico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Generacion Reportes</a>
                        </li>
                    </ul>
                    <div class="d-none d-lg-flex ms-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link me-5" href="/login">Iniciar sesión</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>