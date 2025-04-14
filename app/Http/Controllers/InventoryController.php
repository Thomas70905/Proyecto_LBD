<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Display a listing of the products.
    public function index()
    {
        // For demonstration, using a hardcoded array of products.
        $products = [
            ['id' => 1, 'name' => 'Producto A', 'description' => 'Descripción del Producto A', 'price' => 10.00, 'stock' => 100],
            ['id' => 2, 'name' => 'Producto B', 'description' => 'Descripción del Producto B', 'price' => 20.00, 'stock' => 50],
        ];

        // Later, replace this with a query to your Product model.
        return view('inventory.index', compact('products'));
    }

    // Show the form for creating a new product.
    public function create()
    {
        return view('inventory.create');
    }

    // Store a newly created product in storage.
    public function store(Request $request)
    {
        // Validate and store the product.
        // For now, assume success and redirect to index.
        // Example validation (adjust as needed):
        // $request->validate([
        //     'name'        => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'price'       => 'required|numeric',
        //     'stock'       => 'required|integer',
        // ]);

        return redirect()->route('inventory.index')->with('status', 'Producto agregado exitosamente.');
    }

    // Show the form for editing the specified product.
    public function edit($id)
    {
        // For demonstration, retrieve a hardcoded product.
        // Replace with a query using your Product model.
        $product = [
            'id'          => $id,
            'name'        => 'Producto A',
            'description' => 'Descripción del Producto A',
            'price'       => 10.00,
            'stock'       => 100,
        ];

        return view('inventory.edit', compact('product'));
    }

    // Update the specified product in storage.
    public function update(Request $request, $id)
    {
        // Validate and update the product.
        // For now, assume success.
        return redirect()->route('inventory.index')->with('status', 'Producto actualizado exitosamente.');
    }

    // Remove the specified product from storage.
    public function destroy($id)
    {
        // For demonstration, assume deletion is successful.
        return redirect()->route('inventory.index')->with('status', 'Producto eliminado exitosamente.');
    }
}