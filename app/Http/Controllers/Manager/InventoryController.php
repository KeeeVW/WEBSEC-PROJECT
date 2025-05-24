<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductInventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = ProductInventory::with('product')->paginate(10);
        return view('manager.inventory.index', compact('inventory'));
    }

    public function create()
    {
        $products = Product::all();
        return view('manager.inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        ProductInventory::create($validated);

        return redirect()->route('manager.inventory.index')
            ->with('success', 'Inventory item added successfully');
    }

    public function edit(ProductInventory $item)
    {
        $products = Product::all();
        return view('manager.inventory.edit', compact('item', 'products'));
    }

    public function update(Request $request, ProductInventory $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        $item->update($validated);

        return redirect()->route('manager.inventory.index')
            ->with('success', 'Inventory item updated successfully');
    }

    public function destroy(ProductInventory $item)
    {
        if ($item->quantity > 0) {
            return back()->with('error', 'Cannot delete inventory item with quantity greater than 0');
        }

        $item->delete();

        return redirect()->route('manager.inventory.index')
            ->with('success', 'Inventory item deleted successfully');
    }
} 