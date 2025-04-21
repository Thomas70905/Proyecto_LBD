<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Proveedor de servicios de la aplicación
 * 
 * Este proveedor registra los servicios principales de la aplicación
 * y configura las dependencias necesarias para el funcionamiento
 * del sistema.
 * 
 * Funcionalidades:
 * 1. Registro de servicios
 * 2. Configuración de bindings
 * 3. Registro de facades
 * 4. Configuración de rutas
 * 
 * Servicios registrados:
 * - PDF: Generación de documentos PDF
 * - AppointmentHandler: Gestión de citas
 * - Otros servicios personalizados
 * 
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios de la aplicación.
     * 
     * Este método registra todos los servicios necesarios
     * para el funcionamiento de la aplicación.
     * 
     * @return void
     */
    public function register(): void
    {
        // Registro del facade PDF
        $this->app->bind('pdf', function() {
            return new Pdf();
        });
    }

    /**
     * Inicializa los servicios de la aplicación.
     * 
     * Este método se ejecuta después de que todos los servicios
     * han sido registrados y se utiliza para realizar cualquier
     * inicialización necesaria.
     * 
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
