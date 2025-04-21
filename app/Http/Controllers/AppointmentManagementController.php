<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\AppointmentHandler;
use App\Handlers\InventoryHandler;
use Illuminate\Support\Facades\DB;

/**
 * Controlador AppointmentManagementController
 * 
 * Este controlador maneja la gestión detallada de las citas en el sistema.
 * Proporciona funcionalidades para ver, actualizar y gestionar los productos
 * utilizados en las citas, así como el cálculo de facturación.
 * 
 * El controlador utiliza handlers para interactuar con la base de datos y
 * gestionar la información relacionada con las citas y el inventario.
 * 
 * @package App\Http\Controllers
 */
class AppointmentManagementController extends Controller
{
    /**
     * @var AppointmentHandler Instancia del manejador de citas
     */
    protected $appointmentHandler;

    /**
     * @var InventoryHandler Instancia del manejador de inventario
     */
    protected $inventoryHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param AppointmentHandler $appointmentHandler Manejador de citas
     * @param InventoryHandler $inventoryHandler Manejador de inventario
     */
    public function __construct(
        AppointmentHandler $appointmentHandler,
        InventoryHandler $inventoryHandler
    ) {
        $this->appointmentHandler = $appointmentHandler;
        $this->inventoryHandler = $inventoryHandler;
    }

    /**
     * Muestra la lista de citas con detalles
     * 
     * Este método obtiene todas las citas del sistema con sus detalles asociados
     * y las muestra en la vista correspondiente.
     * 
     * @return \Illuminate\View\View Vista con la lista de citas y sus detalles
     */
    public function index()
    {
        $appointments = $this->appointmentHandler->getAllAppointmentsWithDetails();
        return view('appointments.management.index', compact('appointments'));
    }

    /**
     * Muestra los detalles de una cita específica
     * 
     * Este método obtiene los detalles completos de una cita, incluyendo:
     * - Información de la cita
     * - Productos utilizados
     * - Cálculos de facturación (subtotal, IVA, total)
     * 
     * @param int $id ID de la cita a mostrar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Vista con los detalles de la cita o redirección si no se encuentra
     */
    public function show($id)
    {
        $appointment = $this->appointmentHandler->getAppointmentWithDetailsById($id);
        $inventory = $this->inventoryHandler->getAllInventories();
        $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);

        if (!$appointment) {
            return redirect()->route('appointment-management.index')
                           ->with('error', 'Cita no encontrada');
        }

        $servicePrice = $appointment['precio_servicio'] ?? 0;
        $productsTotal = 0;

        foreach ($usedProducts as $product) {
            $productPrice = $product['precio'] ?? 0;
            $productsTotal += $productPrice * $product['cantidad'];
        }

        $subtotal = $servicePrice + $productsTotal;
        $iva = $subtotal * 0.13; // 13% impuesto
        $total = $subtotal + $iva;

        return view('appointments.management.show', compact(
            'appointment',
            'inventory',
            'usedProducts',
            'servicePrice',
            'productsTotal',
            'subtotal',
            'iva',
            'total'
        ));
    }

    /**
     * Actualiza el estado de asistencia de una cita
     * 
     * Este método actualiza el estado de asistencia de una cita específica.
     * Los valores posibles son:
     * - -1: Pendiente
     * - 0: No asistió
     * - 1: Asistió
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - asistencia: Nuevo estado de asistencia
     * @param int $id ID de la cita a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function updateAttendance(Request $request, $id)
    {
        $request->validate([
            'asistencia' => 'required|in:-1,0,1'
        ]);

        try {            
            $this->appointmentHandler->updateAppointmentAttendance($id, (int)$request->asistencia);
            return redirect()->back()->with('success', 'Estado de asistencia actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el estado de asistencia');
        }
    }

    /**
     * Agrega un producto a una cita
     * 
     * Este método agrega un producto del inventario a una cita específica,
     * verificando la disponibilidad de unidades antes de realizar la operación.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - producto_id: ID del producto
     *                                         - cantidad: Cantidad a agregar
     * @param int $id ID de la cita
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function addProduct(Request $request, $id)
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|min:1',
                'cantidad' => 'required|integer|min:1'
            ]);

            // Verificar que el producto existe y tiene suficiente cantidad unidades
            $product = $this->inventoryHandler->getInventoryById($request->producto_id);
            if (!$product) {
                return redirect()->back()
                    ->with('error', 'El producto no existe.');
            }

            if ($product['cantidadunidades'] < $request->cantidad) {
                return redirect()->back()
                    ->with('error', 'No hay suficiente cantidad de unidades disponibles. Cantidad actual: ' . $product['cantidadunidades']);
            }

            $this->appointmentHandler->addProductToAppointment($id, $request->producto_id, $request->cantidad);
            return redirect()->back()
                ->with('success', 'Producto agregado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo agregar el producto. Por favor, intente nuevamente.');
        }
    }

    /**
     * Actualiza la cantidad de un producto en una cita
     * 
     * Este método actualiza la cantidad de un producto específico en una cita,
     * verificando la disponibilidad de unidades antes de realizar la operación.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - producto_id: ID del producto
     *                                         - cantidad: Nueva cantidad
     * @param int $id ID de la cita
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function updateProductQuantity(Request $request, $id)
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|min:1',
                'cantidad' => 'required|integer|min:0'
            ]);

            // Verificar que el producto existe
            $product = $this->inventoryHandler->getInventoryById($request->producto_id);
            if (!$product) {
                return redirect()->back()
                    ->with('error', 'El producto no existe.');
            }

            // Obtener la cantidad actual en la cita
            $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);
            $currentQuantity = 0;
            foreach ($usedProducts as $usedProduct) {
                if ($usedProduct['inventarioid'] == $request->producto_id) {
                    $currentQuantity = $usedProduct['cantidad'];
                    break;
                }
            }

            // Calcular la diferencia y verificar cantidad unidades disponible
            $difference = $request->cantidad - $currentQuantity;
            if ($difference > 0 && $product['cantidadunidades'] < $difference) {
                return redirect()->back()
                    ->with('error', 'No hay suficiente cantidad de unidades disponibles. Cantidad actual: ' . $product['cantidadunidades']);
            }

            $success = $this->appointmentHandler->updateProductQuantity($id, $request->producto_id, $request->cantidad);
            
            if ($success) {
                return redirect()->back()
                    ->with('success', 'Cantidad actualizada exitosamente');
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo actualizar la cantidad del producto');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo actualizar la cantidad del producto');
        }
    }

    /**
     * Elimina un producto de una cita
     * 
     * Este método elimina un producto específico de una cita.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - producto_id: ID del producto a eliminar
     * @param int $id ID de la cita
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function removeProduct(Request $request, $id)
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|min:1'
            ]);

            $success = $this->appointmentHandler->removeProductFromAppointment($id, $request->producto_id);
            
            if ($success) {
                return redirect()->back()
                    ->with('success', 'Producto eliminado exitosamente');
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo eliminar el producto');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'No se pudo eliminar el producto');
        }
    }
} 