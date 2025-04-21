<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\AppointmentHandler;
use App\Handlers\InventoryHandler;
use Illuminate\Support\Facades\DB;

class AppointmentManagementController extends Controller
{
    protected $appointmentHandler;
    protected $inventoryHandler;

    public function __construct(
        AppointmentHandler $appointmentHandler,
        InventoryHandler $inventoryHandler
    ) {
        $this->appointmentHandler = $appointmentHandler;
        $this->inventoryHandler = $inventoryHandler;
    }

    // Muestra la lista de citas con detalles
    public function index()
    {
        $appointments = $this->appointmentHandler->getAllAppointmentsWithDetails();
        return view('appointments.management.index', compact('appointments'));
    }

    // Muestra los detalles de una cita específica
    public function show($id)
    {
        $appointment = $this->appointmentHandler->getAppointmentWithDetailsById($id);
        $inventory = $this->inventoryHandler->getAllInventories();
        $usedProducts = $this->appointmentHandler->getAppointmentProducts($id);

        if (!$appointment) {
            return redirect()->route('appointment-management.index')
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

    // Actualiza la asistencia de una cita
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

    // Agrega un producto a la cita
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