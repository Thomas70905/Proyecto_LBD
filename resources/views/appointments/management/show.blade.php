@extends('layouts.app')

@section('title', 'Detalles de Cita')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles de Cita</h3>
                </div>

                <div class="card-body">
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
                            <h4>Información del Cliente</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nombre:</th>
                                    <td>{{ $appointment['nombre_cliente'] }}</td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td>{{ $appointment['telefono_cliente'] }}</td>
                                </tr>
                                <tr>
                                    <th>Dirección:</th>
                                    <td>{{ $appointment['direccion_cliente'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Actualizar Asistencia</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('appointment-management.updateAttendance', $appointment['id']) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="asistencia" class="form-label">Estado de Asistencia</label>
                                            <select class="form-select" id="asistencia" name="asistencia" required>
                                                <option value="-1" {{ $appointment['asistencia'] == -1 ? 'selected' : '' }}>Pendiente</option>
                                                <option value="1" {{ $appointment['asistencia'] == 1 ? 'selected' : '' }}>Asistió</option>
                                                <option value="0" {{ $appointment['asistencia'] == 0 ? 'selected' : '' }}>No Asistió</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Productos Utilizados</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('appointment-management.addProduct', $appointment['id']) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="producto_id" class="form-label">Producto</label>
                                            <select class="form-select" id="producto_id" name="producto_id" required>
                                                <option value="">Seleccione un producto</option>
                                                @foreach($inventory as $product)
                                                    <option value="{{ $product['id'] }}" 
                                                            data-stock="{{ $product['cantidadunidades'] }}"
                                                            data-price="{{ $product['precio'] }}"
                                                            {{ $product['cantidadunidades'] <= 0 ? 'disabled' : '' }}>
                                                        {{ $product['nombreproducto'] }} (Cantidad Disponible: {{ $product['cantidadunidades'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cantidad" class="form-label">Cantidad</label>
                                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                                    </form>
                                </div>
                            </div>
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
                                                            <th class="text-center">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($usedProducts as $product)
                                                            <tr>
                                                                <td class="align-middle">{{ $product['nombreproducto'] }}</td>
                                                                <td class="align-middle">
                                                                    <form action="{{ route('appointment-management.updateProductQuantity', $appointment['id']) }}" method="POST" class="d-flex align-items-center justify-content-center gap-2">
                                                                        @csrf
                                                                        <input type="hidden" name="producto_id" value="{{ $product['inventarioid'] }}">
                                                                        <input type="number" 
                                                                               name="cantidad" 
                                                                               value="{{ $product['cantidad'] }}" 
                                                                               min="0" 
                                                                               class="form-control form-control-sm text-center" 
                                                                               style="width: 80px;">
                                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                                            <i class="fas fa-save"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                                <td class="text-end align-middle price">{{ number_format($product['precio'] ?? 0, 2) }}</td>
                                                                <td class="text-end align-middle price">{{ number_format(($product['precio'] ?? 0) * $product['cantidad'], 2) }}</td>
                                                                <td class="text-center align-middle">
                                                                    <form action="{{ route('appointment-management.removeProduct', $appointment['id']) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="producto_id" value="{{ $product['inventarioid'] }}">
                                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No se han utilizado productos</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('producto_id');
        const cantidadInput = document.getElementById('cantidad');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock);
            cantidadInput.max = stock;
        });
    });
</script>
@endpush
@endsection 