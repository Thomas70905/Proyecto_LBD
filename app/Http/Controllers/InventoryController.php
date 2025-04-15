<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\InventoryHandler;

class InventoryController extends Controller
{
    protected $inventoryHandler;

    public function __construct(InventoryHandler $inventoryHandler)
    {
        $this->inventoryHandler = $inventoryHandler;
    }

    // Muestra la lista de inventarios
    public function index()
    {
        // Fetch inventory items using the handler
        $inventories = $this->inventoryHandler->getAllInventories();

        return view('inventory.index', compact('inventories'));
    }

    // Muestra el formulario para crear un nuevo inventario
    public function create()
    {
        return view('inventory.create');
    }

    // Almacena un nuevo inventario
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'nombreProducto'   => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'precio'           => 'required|numeric',
            'cantidadUnidades' => 'required|integer',
            'fechaCaducidad'   => 'required|date',
        ]);

        // Llama al procedimiento almacenado para insertar el inventario
        $this->inventoryHandler->insertInventory(
            $request->nombreProducto,
            $request->descripcion,
            $request->precio,
            $request->cantidadUnidades,
            $request->fechaCaducidad
        );

        return redirect()->route('inventory.index')->with('status', 'Inventario agregado exitosamente.');
    }

    // Muestra el formulario para editar un inventario existente
    public function edit($id)
    {
        // Fetch the inventory item by ID using the handler
        $inventory = $this->inventoryHandler->getInventoryById($id);

        if (!$inventory) {
            return redirect()->route('inventory.index')->with('error', 'Inventario no encontrado.');
        }

        return view('inventory.edit', compact('inventory'));
    }

    // Actualiza un inventario existente
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'nombreProducto'   => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'precio'           => 'required|numeric',
            'cantidadUnidades' => 'required|integer',
            'fechaCaducidad'   => 'required|date',
        ]);

        // Llama al procedimiento almacenado para actualizar el inventario
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

    // Elimina un inventario
    public function destroy($id)
    {
        // Llama al procedimiento almacenado para eliminar el inventario
        $this->inventoryHandler->deleteInventory($id);

        return redirect()->route('inventory.index')->with('status', 'Inventario eliminado exitosamente.');
    }
}