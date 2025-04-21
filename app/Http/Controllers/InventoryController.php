<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\InventoryHandler;

/**
 * Controlador InventoryController
 * 
 * Este controlador maneja todas las operaciones relacionadas con el inventario del sistema.
 * Proporciona funcionalidades para gestionar productos, incluyendo su creación, visualización,
 * actualización y eliminación. También maneja la validación de datos y la interacción
 * con la base de datos a través del InventoryHandler.
 * 
 * El controlador utiliza procedimientos almacenados para realizar todas las operaciones
 * de base de datos, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Http\Controllers
 */
class InventoryController extends Controller
{
    /**
     * @var InventoryHandler Instancia del manejador de inventario
     */
    protected $inventoryHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param InventoryHandler $inventoryHandler Manejador de inventario
     */
    public function __construct(InventoryHandler $inventoryHandler)
    {
        $this->inventoryHandler = $inventoryHandler;
    }

    /**
     * Muestra la lista de todos los productos en inventario
     * 
     * Este método obtiene y muestra la lista completa de productos en el inventario
     * del sistema.
     * 
     * @return \Illuminate\View\View Vista con la lista de productos en inventario
     */
    public function index()
    {
        $inventories = $this->inventoryHandler->getAllInventories();
        return view('inventory.index', compact('inventories'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto
     * 
     * Este método muestra el formulario que permite registrar un nuevo producto
     * en el inventario del sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de registro
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Almacena un nuevo producto en el inventario
     * 
     * Este método valida y almacena un nuevo producto en el inventario del sistema.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - nombreProducto: Nombre del producto
     *                                         - descripcion: Descripción detallada
     *                                         - precio: Precio unitario
     *                                         - cantidadUnidades: Cantidad disponible
     *                                         - fechaCaducidad: Fecha de caducidad
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreProducto'   => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'precio'           => 'required|numeric',
            'cantidadUnidades' => 'required|integer',
            'fechaCaducidad'   => 'required|date',
        ]);

        $this->inventoryHandler->insertInventory(
            $request->nombreProducto,
            $request->descripcion,
            $request->precio,
            $request->cantidadUnidades,
            $request->fechaCaducidad
        );

        return redirect()->route('inventory.index')->with('status', 'Inventario agregado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un producto existente
     * 
     * Este método obtiene los datos de un producto específico y muestra el formulario
     * para su edición.
     * 
     * @param int $id ID del producto a editar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Vista con el formulario de edición o redirección si no se encuentra
     */
    public function edit($id)
    {
        $inventory = $this->inventoryHandler->getInventoryById($id);

        if (!$inventory) {
            return redirect()->route('inventory.index')->with('error', 'Inventario no encontrado.');
        }

        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Actualiza un producto existente en el inventario
     * 
     * Este método valida y actualiza la información de un producto específico
     * en el inventario del sistema.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - nombreProducto: Nuevo nombre
     *                                         - descripcion: Nueva descripción
     *                                         - precio: Nuevo precio
     *                                         - cantidadUnidades: Nueva cantidad
     *                                         - fechaCaducidad: Nueva fecha de caducidad
     * @param int $id ID del producto a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreProducto'   => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'precio'           => 'required|numeric',
            'cantidadUnidades' => 'required|integer',
            'fechaCaducidad'   => 'required|date',
        ]);

        $this->inventoryHandler->updateInventory(
            $id,
            $request->nombreProducto,
            $request->descripcion,
            $request->precio,
            $request->cantidadUnidades,
            $request->fechaCaducidad
        );

        return redirect()->route('inventory.index')->with('status', 'Inventario actualizado exitosamente.');
    }

    /**
     * Elimina un producto del inventario
     * 
     * Este método elimina un producto específico del inventario del sistema.
     * 
     * @param int $id ID del producto a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     */
    public function destroy($id)
    {
        $this->inventoryHandler->deleteInventory($id);
        return redirect()->route('inventory.index')->with('status', 'Inventario eliminado exitosamente.');
    }
}