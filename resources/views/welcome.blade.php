@extends('layouts.app')

@section('title', 'Inicio - Clínica Veterinaria Moncada')

@section('content')
<div class="container mt-5 px-3">
    <!-- Hero Section -->
    <div class="jumbotron text-center bg-light p-5 rounded">
        <h1 class="display-4">Bienvenido a Clínica Veterinaria Moncada</h1>
        <p class="lead">Cuidamos de tus mascotas con amor y profesionalismo.</p>
        <hr class="my-4">
        <p>Ofrecemos servicios de calidad para garantizar la salud y bienestar de tus amigos peludos.</p>
    </div>

    <!-- Values, Mission, and Vision Section -->
    <div class="row mt-5">
        <div class="col-md-4 text-center">
            <img src="https://purina.co.cr/sites/default/files/2023-11/consulta-veterinaria-cachorro-cr.jpg" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;" alt="Valores">
            <h5 class="fw-bold">Nuestros Valores</h5>
            <p>Compromiso, empatía y excelencia en el cuidado de tus mascotas.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="https://images.ctfassets.net/denf86kkcx7r/1u9lvKcYIgnJgZSLthxy0U/38764c99429b7e457c1eb4c898293533/huskyvacunacion-65" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;" alt="Misión">
            <h5 class="fw-bold">Nuestra Misión</h5>
            <p>Brindar atención veterinaria de calidad para mejorar la vida de las mascotas y sus familias.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="https://animalcity.es/clinica/wp-content/uploads/2021/08/veterinary-4940425_1280-min-1024x682.jpg" class="img-fluid rounded mb-3" style="width: 100%; height: 200px; object-fit: cover;" alt="Visión">
            <h5 class="fw-bold">Nuestra Visión</h5>
            <p>Ser la clínica veterinaria líder en innovación y cuidado animal en nuestra comunidad.</p>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="mt-5 text-center">
        <h2>¿Por qué elegirnos?</h2>
        <p>Contamos con un equipo de veterinarios altamente capacitados y apasionados por el cuidado animal.</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">✔ Instalaciones modernas</li>
                <li class="list-group-item">✔ Equipos de última tecnología</li>
                <li class="list-group-item">✔ Atención personalizada</li>
            </ul>
        </div>
        <div class="col-md-6">
            <img src="https://media.istockphoto.com/id/1303362255/pt/foto/young-happy-veterinary-nurse-smiling-while-playing-with-a-dog-high-quality-photo.jpg?s=612x612&w=0&k=20&c=9Y5BtBQEK8WnbNmZaTG9cJq8t37lUyecnJ_ybBFkT0Y=" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;" alt="Veterinaria">
        </div>
    </div>

    <!-- Customer Opinions Section -->
    <div class="mt-5 text-center">
        <h2>Opiniones de Nuestros Clientes</h2>
        <p>Lo que nuestros clientes dicen sobre nosotros:</p>
    </div>

    <div class="row my-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">"Excelente servicio, mi perro siempre recibe el mejor cuidado. ¡Gracias!"</p>
                    <h6 class="card-subtitle text-muted">- María López</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">"El personal es muy amable y profesional. Recomiendo esta clínica al 100%."</p>
                    <h6 class="card-subtitle text-muted">- Juan Pérez</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">"Gracias por salvar a mi gatito en una emergencia. ¡Son los mejores!"</p>
                    <h6 class="card-subtitle text-muted">- Ana García</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection