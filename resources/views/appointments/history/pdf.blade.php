{{--
    Plantilla: PDF de Detalles de Cita
    Descripción: Genera un documento PDF con la información detallada de una cita,
    incluyendo facturación y productos utilizados.
    
    Estructura:
    1. Encabezado con logo y datos de la veterinaria
    2. Información general de la cita
    3. Detalles de la mascota
    4. Productos utilizados
    5. Resumen de facturación
    6. Pie de página
    
    Componentes:
    - Encabezado con logo
    - Tablas de información
    - Resumen de facturación
    - Pie de página con agradecimiento
    
    Datos requeridos:
    - appointment: Array con datos de la cita
    - usedProducts: Array de productos utilizados
    - servicePrice: Precio del servicio
    - productsTotal: Total de productos
    - subtotal: Subtotal de la factura
    - iva: Valor del IVA
    - total: Total de la factura
    
    Estilos:
    - Estilos CSS para PDF
    - Diseño profesional
    - Formato de factura
    
    Notas:
    - Los estilos están optimizados para generación de PDF
    - Se utiliza formato de moneda local (₡)
    - Se incluyen márgenes y espaciado apropiados
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Resumen de Cita - Veterinaria Moncada</title>
    <style>
        @font-face {
            font-family: 'Arial';
            src: local('Arial');
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Veterinaria Moncada</h1>
        <p>Resumen de Cita</p>
    </div>

    <div class="section">
        <h2>Información de la Cita</h2>
        <table>
            <tr>
                <th>Fecha y Hora</th>
                <td>{{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Servicio</th>
                <td>{{ $appointment['nombre_servicio'] }}</td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td>{{ $appointment['descripcion'] ?? 'Sin descripción' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Información de la Mascota</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <td>{{ $appointment['nombre_mascota'] }}</td>
            </tr>
            <tr>
                <th>Edad</th>
                <td>{{ $appointment['edad_mascota'] }} años</td>
            </tr>
            <tr>
                <th>Peso</th>
                <td>{{ $appointment['peso_mascota'] }} kg</td>
            </tr>
            <tr>
                <th>Raza</th>
                <td>{{ $appointment['raza_mascota'] }}</td>
            </tr>
            <tr>
                <th>Especie</th>
                <td>{{ $appointment['especie_mascota'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Facturación</h2>
        <table>
            <tr>
                <th>Servicio</th>
                <td>{{ $appointment['nombre_servicio'] }}</td>
                <td class="text-right">{{ number_format($servicePrice, 2) }}</td>
            </tr>
        </table>

        <h3>Productos Utilizados</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th class="text-right">Precio Unitario</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usedProducts as $product)
                    <tr>
                        <td>{{ $product['nombreproducto'] }}</td>
                        <td>{{ $product['cantidad'] }}</td>
                        <td class="text-right">{{ number_format($product['precio'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format(($product['precio'] ?? 0) * $product['cantidad'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No se utilizaron productos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table>
            <tr>
                <th>Subtotal Servicio</th>
                <td class="text-right">{{ number_format($servicePrice, 2) }}</td>
            </tr>
            <tr>
                <th>Subtotal Productos</th>
                <td class="text-right">{{ number_format($productsTotal, 2) }}</td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td class="text-right">{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>IVA (13%)</th>
                <td class="text-right">{{ number_format($iva, 2) }}</td>
            </tr>
            <tr class="total">
                <th>Total</th>
                <td class="text-right">{{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Gracias por confiar en nuestros servicios</p>
        <p>Veterinaria Moncada - {{ date('d/m/Y') }}</p>
    </div>
</body>
</html> 