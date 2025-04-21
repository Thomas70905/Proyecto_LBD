{{--
    Vista: Detalles de Cita
    Descripción: Muestra la información detallada de una cita específica, incluyendo
    información de facturación y productos utilizados.
    
    Estructura:
    1. Layout principal
    2. Contenedor de contenido
    3. Sección de información general
    4. Sección de facturación
    5. Botones de acción
    
    Componentes:
    - Tarjeta de información general
    - Tabla de productos utilizados
    - Resumen de facturación
    - Botón de descarga PDF
    
    Datos requeridos:
    - appointment: Array con datos de la cita
    - usedProducts: Array de productos utilizados
    - servicePrice: Precio del servicio
    - productsTotal: Total de productos
    - subtotal: Subtotal de la factura
    - iva: Valor del IVA
    - total: Total de la factura
    
    Estilos:
    - Bootstrap 5
    - Personalización de colores
    - Diseño responsive
    
    @extends('layouts.app')
    @section('title', 'Detalles de Cita')
--}}

@extends('layouts.app')

@section('title', 'Detalles de Cita')

@section('content')
<div class="container py-4">
    {{-- Título y botón de descarga PDF --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalles de la Cita</h1>
        @if($appointment['asistencia'] == 1)
            <a href="{{ route('appointment-history.download-pdf', $appointment['id']) }}" 
               class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
        @endif
    </div>

    {{-- Información general de la cita --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Información de la Cita</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Fecha y hora --}}
                <div class="col-md-6">
                    <p><strong>Fecha y Hora:</strong> 
                        {{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('d/m/Y H:i') }}
                    </p>
                </div>
                
                {{-- Estado de asistencia --}}
                <div class="col-md-6">
                    <p><strong>Estado:</strong>
                        @switch($appointment['asistencia'])
                            @case(0)
                                <span class="badge bg-warning">Pendiente</span>
                                @break
                            @case(1)
                                <span class="badge bg-success">Asistió</span>
                                @break
                            @case(2)
                                <span class="badge bg-danger">No Asistió</span>
                                @break
                        @endswitch
                    </p>
                </div>
                
                {{-- Servicio --}}
                <div class="col-md-6">
                    <p><strong>Servicio:</strong> {{ $appointment['nombre_servicio'] }}</p>
                </div>
                
                {{-- Descripción --}}
                <div class="col-12">
                    <p><strong>Descripción:</strong> {{ $appointment['descripcion'] ?? 'Sin descripción' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Información de la mascota --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Información de la Mascota</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Nombre de la mascota --}}
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $appointment['nombre_mascota'] }}</p>
                </div>
                
                {{-- Edad --}}
                <div class="col-md-6">
                    <p><strong>Edad:</strong> {{ $appointment['edad_mascota'] }} años</p>
                </div>
                
                {{-- Peso --}}
                <div class="col-md-6">
                    <p><strong>Peso:</strong> {{ $appointment['peso_mascota'] }} kg</p>
                </div>
                
                {{-- Raza --}}
                <div class="col-md-6">
                    <p><strong>Raza:</strong> {{ $appointment['raza_mascota'] }}</p>
                </div>
                
                {{-- Especie --}}
                <div class="col-md-6">
                    <p><strong>Especie:</strong> {{ $appointment['especie_mascota'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($appointment['asistencia'] == 1)
        {{-- Productos utilizados --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Productos Utilizados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-end">Precio Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usedProducts as $product)
                                <tr>
                                    <td>{{ $product['nombreproducto'] }}</td>
                                    <td class="text-end">{{ $product['cantidad'] }}</td>
                                    <td class="text-end">₡{{ number_format($product['precio'] ?? 0, 2) }}</td>
                                    <td class="text-end">₡{{ number_format(($product['precio'] ?? 0) * $product['cantidad'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No se utilizaron productos en esta cita</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Resumen de facturación --}}
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Resumen de Facturación</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <table class="table">
                            <tbody>
                                {{-- Subtotal del servicio --}}
                                <tr>
                                    <td><strong>Subtotal Servicio:</strong></td>
                                    <td class="text-end">₡{{ number_format($servicePrice, 2) }}</td>
                                </tr>
                                
                                {{-- Subtotal de productos --}}
                                <tr>
                                    <td><strong>Subtotal Productos:</strong></td>
                                    <td class="text-end">₡{{ number_format($productsTotal, 2) }}</td>
                                </tr>
                                
                                {{-- Subtotal general --}}
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">₡{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                
                                {{-- IVA --}}
                                <tr>
                                    <td><strong>IVA (13%):</strong></td>
                                    <td class="text-end">₡{{ number_format($iva, 2) }}</td>
                                </tr>
                                
                                {{-- Total --}}
                                <tr class="table-primary">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>₡{{ number_format($total, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 