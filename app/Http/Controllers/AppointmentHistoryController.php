<?php

namespace App\Http\Controllers;

use App\Handlers\AppointmentHandler;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador para la gestión del historial de citas de los clientes.
 * 
 * Este controlador maneja todas las operaciones relacionadas con el historial
 * de citas de los clientes, incluyendo:
 * 
 * 1. Visualización de citas:
 *    - Listado de citas agrupadas por mascota
 *    - Detalles específicos de cada cita
 *    - Información de facturación
 * 
 * 2. Funcionalidades:
 *    - Generación de PDFs de resumen
 *    - Cálculo de totales y subtotales
 *    - Gestión de productos utilizados
 * 
 * 3. Integración:
 *    - Interacción con AppointmentHandler
 *    - Generación de PDFs con DomPDF
 *    - Sistema de autenticación
 * 
 * Dependencias:
 * - AppointmentHandler: Para operaciones con citas
 * - Pdf: Para generación de documentos
 * 
 * @package App\Http\Controllers
 */
class AppointmentHistoryController extends Controller
{
    /**
     * Instancia del manejador de citas.
     * 
     * Se utiliza para realizar todas las operaciones relacionadas
     * con las citas en la base de datos.
     * 
     * @var AppointmentHandler
     */
    protected $appointmentHandler;

    /**
     * Constructor del controlador.
     * 
     * Inicializa el controlador con una instancia del manejador de citas.
     * 
     * @param AppointmentHandler $appointmentHandler Instancia del manejador
     */
    public function __construct(AppointmentHandler $appointmentHandler)
    {
        $this->appointmentHandler = $appointmentHandler;
    }

    /**
     * Muestra el listado de citas del usuario autenticado.
     * 
     * Este método:
     * 1. Obtiene todas las citas del usuario
     * 2. Las agrupa por mascota
     * 3. Ordena las citas por fecha
     * 4. Retorna la vista con los datos
     * 
     * La vista muestra:
     * - Listado de mascotas
     * - Citas asociadas a cada mascota
     * - Estado de cada cita
     * - Acciones disponibles
     * 
     * @return \Illuminate\View\View Vista del listado de citas
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
     * 1. Obtiene los detalles de la cita
     * 2. Recupera los productos utilizados
     * 3. Calcula los totales de facturación
     * 4. Retorna la vista con los datos
     * 
     * La vista muestra:
     * - Información general de la cita
     * - Detalles del servicio
     * - Productos utilizados
     * - Resumen de facturación
     * 
     * @param int $id ID de la cita a mostrar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $appointment = $this->appointmentHandler->getAppointmentWithDetailsById($id);
        $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);

        if (!$appointment) {
            return redirect()->route('appointment-history.index')
                           ->with('error', 'Cita no encontrada');
        }

        // Calculate billing totals
        $servicePrice = $appointment['precio_servicio'] ?? 0;
        $productsTotal = 0;

        foreach ($usedProducts as $product) {
            $productPrice = $product['precio'] ?? 0;
            $productsTotal += $productPrice * $product['cantidad'];
        }

        $subtotal = $servicePrice + $productsTotal;
        $iva = $subtotal * 0.13; // 13% tax
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
     * 1. Obtiene los detalles de la cita
     * 2. Recupera los productos utilizados
     * 3. Calcula los totales
     * 4. Genera el PDF
     * 5. Retorna el archivo para descarga
     * 
     * El PDF incluye:
     * - Información de la cita
     * - Detalles del servicio
     * - Lista de productos
     * - Resumen de facturación
     * 
     * @param int $id ID de la cita
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

        // Calculate billing totals
        $servicePrice = $appointment['precio_servicio'] ?? 0;
        $productsTotal = 0;

        foreach ($usedProducts as $product) {
            $productPrice = $product['precio'] ?? 0;
            $productsTotal += $productPrice * $product['cantidad'];
        }

        $subtotal = $servicePrice + $productsTotal;
        $iva = $subtotal * 0.13; // 13% tax
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