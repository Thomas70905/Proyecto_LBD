<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Document')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img 
                        src="/assets/images/logo.png" 
                        alt="Logo" 
                        class="d-inline-block align-text-top" 
                        height="40"
                    >
                </a>
                <div class="d-flex align-items-center d-lg-none">
                    @auth
                        <span class="nav-link me-3">Bienvenido, {{ Auth::user()->nombre_completo }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;">
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a class="nav-link me-3" href="{{ route('login') }}">Iniciar sesión</a>
                    @endauth
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->rol === 'cliente')
                                <li class="nav-item">
                                    <a class="nav-link" href="/pets">Administrar Mascotas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/appointments">Administrar Citas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-none d-lg-block" href="{{ route('appointment-history.index') }}">Historial Clínico</a>
                                </li>
                            @endif
                            @if(Auth::user()->rol === 'administrador')
                                <li class="nav-item">
                                    <a class="nav-link" href="/employees">Administrar Empleados</a>
                                </li>
                            @endif
                            @if(Auth::user()->rol === 'veterinario' || Auth::user()->rol === 'administrador')
                                <li class="nav-item">
                                    <a class="nav-link" href="/inventory">Administrar Inventario</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/services">Administrar Servicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/appointment-management">Gestionar Citas</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <div class="ms-auto">
                        <ul class="navbar-nav d-flex align-items-center">
                            @auth
                                <li class="nav-item d-flex align-items-center">
                                    <span class="nav-link me-3">Bienvenido, {{ Auth::user()->nombre_completo }}</span>
                                </li>
                                <li class="nav-item d-flex align-items-center">
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;">
                                            Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link me-5 d-none d-lg-block" href="{{ route('login') }}">Iniciar sesión</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">© 2025 Clínica Veterinaria Moncada. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>