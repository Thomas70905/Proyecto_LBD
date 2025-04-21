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
    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Detalles de la Cita</h1>
                        @if($appointment['asistencia'] == 1)
                            <a href="{{ route('appointment-history.download-pdf', $appointment['id']) }}" 
                            class="btn btn-primary">
                                <i class="fas fa-file-pdf"></i> Descargar PDF
                            </a>
                        @endif
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Información de la Cita</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Fecha y Hora:</th>
                                    <td>{{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="badge bg-{{ $appointment['asistencia'] == -1 ? 'warning' : ($appointment['asistencia'] == 1 ? 'success' : 'danger') }}">
                                            @switch($appointment['asistencia'])
                                                @case(-1) Pendiente @break
                                                @case(0) No Asistió @break
                                                @case(1) Asistió @break
                                                @default Desconocido
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Servicio:</th>
                                    <td>{{ $appointment['nombre_servicio'] }}</td>
                                </tr>
                                <tr>
                                    <th>Descripción:</th>
                                    <td>{{ $appointment['descripcion'] ?? 'Sin descripción' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Información de la Mascota</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $appointment['nombre_mascota'] }}</td>
                                </tr>
                                <tr>
                                    <th>Edad:</th>
                                    <td>{{ $appointment['edad_mascota'] }} años</td>
                                </tr>
                                <tr>
                                    <th>Peso:</th>
                                    <td>{{ $appointment['peso_mascota'] }} kg</td>
                                </tr>
                                <tr>
                                    <th>Raza:</th>
                                    <td>{{ $appointment['raza_mascota'] }}</td>
                                </tr>
                                <tr>
                                    <th>Especie:</th>
                                    <td>{{ $appointment['especie_mascota'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Facturación</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Servicio</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Servicio:</th>
                                                    <td>{{ $appointment['nombre_servicio'] }}</td>
                                                    <td class="text-end price">{{ number_format($servicePrice, 2) }}</td>
                                                </tr>
                                            </table>

                                            <h5>Productos Utilizados</h5>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-end">Precio Unitario</th>
                                                            <th class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($usedProducts as $product)
                                                            <tr>
                                                                <td class="align-middle">{{ $product['nombreproducto'] }}</td>
                                                                <td class="align-middle">
                                                                    {{ $product['cantidad'] }}
                                                                </td>
                                                                <td class="text-end align-middle price">{{ number_format($product['precio'] ?? 0, 2) }}</td>
                                                                <td class="text-end align-middle price">{{ number_format(($product['precio'] ?? 0) * $product['cantidad'], 2) }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No se utilizaron productos</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5>Resumen de Factura</h5>
                                            <table class="table">
                                                <tr>
                                                    <th>Subtotal Servicio:</th>
                                                    <td class="text-end price">{{ number_format($servicePrice, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Subtotal Productos:</th>
                                                    <td class="text-end price">{{ number_format($productsTotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Subtotal:</th>
                                                    <td class="text-end price">{{ number_format($subtotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>IVA (13%):</th>
                                                    <td class="text-end price">{{ number_format($iva, 2) }}</td>
                                                </tr>
                                                <tr class="fw-bold">
                                                    <th>Total:</th>
                                                    <td class="text-end price">{{ number_format($total, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 