<?php

namespace App\Http\Controllers;

use App\Handlers\AppointmentHandler;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador AppointmentHistoryController
 *
 * Este controlador gestiona el historial de citas de los clientes autenticados.
 * Proporciona funcionalidades para:
 *   - Listar citas agrupadas por mascota y ordenadas por fecha.
 *   - Mostrar detalles de una cita específica junto con el cálculo de facturación.
 *   - Generar y descargar un PDF resumen de la cita.
 *
 * Se integra con:
 *   - AppointmentHandler: para obtener datos de citas y productos utilizados.
 *   - DomPDF (Pdf): para generación de documentos PDF.
 *
 * @package App\Http\Controllers
 */
class AppointmentHistoryController extends Controller
{
    /**
     * @var AppointmentHandler Instancia del manejador de citas
     *
     * Utilizada para realizar operaciones de lectura sobre citas y productos asociados.
     */
    protected $appointmentHandler;

    /**
     * Constructor del controlador.
     *
     * Inicializa la dependencia del manejador de citas.
     *
     * @param AppointmentHandler $appointmentHandler Manejador de operaciones de citas
     */
    public function __construct(AppointmentHandler $appointmentHandler)
    {
        $this->appointmentHandler = $appointmentHandler;
    }

    /**
     * Muestra el historial de citas del usuario autenticado.
     *
     * Este método:
     *   1. Obtiene todas las citas del usuario con detalles.
     *   2. Agrupa y ordena las citas por nombre de mascota y fecha.
     *   3. Retorna la vista con el conjunto de datos.
     *
     * @return \Illuminate\View\View Vista con el listado de citas históricas
     */
    public function index()
    {
        // Get all appointments for the authenticated user
        $appointments = $this->appointmentHandler->getUserAppointmentsWithDetails(auth()->id());
        
        // Sort appointments by pet name and then by date
        usort($appointments, function($a, $b) {
            // First sort by pet name
            $nameCompare = strcmp($a['nombre_mascota'], $b['nombre_mascota']);
            if ($nameCompare !== 0) {
                return $nameCompare;
            }
            // If same pet, sort by date (newest first)
            return strtotime($b['fechainicio']) - strtotime($a['fechainicio']);
        });
        
        return view('appointments.history.index', compact('appointments'));
    }

    /**
     * Muestra los detalles de una cita específica.
     *
     * Este método:
     *   1. Obtiene los datos de la cita seleccionada.
     *   2. Recupera los productos utilizados en la cita.
     *   3. Calcula subtotales, impuesto (13%) y total.
     *   4. Retorna la vista con la información completa de facturación.
     *
     * @param int $id ID de la cita a mostrar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Vista de detalles o redirección si la cita no existe
     */
    public function show($id)
    {
        $appointment = $this->appointmentHandler->getAppointmentWithDetailsById($id);
        $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);

        if (!$appointment) {
            return redirect()->route('appointment-history.index')
                           ->with('error', 'Cita no encontrada');
        }

        $servicePrice = $appointment['precio_servicio'] ?? 0;
        $productsTotal = 0;

        foreach ($usedProducts as $product) {
            $productPrice = $product['precio'] ?? 0;
            $productsTotal += $productPrice * $product['cantidad'];
        }

        $subtotal = $servicePrice + $productsTotal;
        $iva = $subtotal * 0.13; 
        $total = $subtotal + $iva;

        return view('appointments.history.show', compact(
            'appointment',
            'usedProducts',
            'servicePrice',
            'productsTotal',
            'subtotal',
            'iva',
            'total'
        ));
    }

    /**
     * Genera y descarga un PDF con el resumen de la cita.
     *
     * Este método:
     *   1. Obtiene los datos de la cita y productos asociados.
     *   2. Calcula los totales de facturación.
     *   3. Renderiza la vista 'appointments.history.pdf'.
     *   4. Devuelve la respuesta con el archivo PDF para descarga.
     *
     * @param int $id ID de la cita a exportar
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadPdf($id)
    {
        $appointment = $this->appointmentHandler->getAppointmentWithDetailsById($id);
        $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);

        if (!$appointment) {
            return redirect()->route('appointment-history.index')
                           ->with('error', 'Cita no encontrada');
        }

        $servicePrice = $appointment['precio_servicio'] ?? 0;
        $productsTotal = 0;

        foreach ($usedProducts as $product) {
            $productPrice = $product['precio'] ?? 0;
            $productsTotal += $productPrice * $product['cantidad'];
        }

        $subtotal = $servicePrice + $productsTotal;
        $iva = $subtotal * 0.13;
        $total = $subtotal + $iva;

        $pdf = Pdf::loadView('appointments.history.pdf', compact(
            'appointment',
            'usedProducts',
            'servicePrice',
            'productsTotal',
            'subtotal',
            'iva',
            'total'
        ));

        return $pdf->download('resumen-cita-' . $appointment['id'] . '.pdf');
    }
} 