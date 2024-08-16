<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\IngoingItem;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'name'); // Default sort by 'name'
        $direction = $request->input('direction', 'asc'); // Default sort direction is 'asc'
        $search = $request->input('search'); // Input untuk pencarian

        $inventories = Inventory::select('inventories.*')
            ->when($search, function ($query) use ($search) {
                return $query->where('inventories.name', 'like', '%' . $search . '%')
                    ->orWhere('inventories.barcode', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('location', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->when($sort === 'category.name', function ($query) use ($direction) {
                return $query->join('categories', 'inventories.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $direction);
            })
            ->when($sort === 'brand.name', function ($query) use ($direction) {
                return $query->join('brands', 'inventories.brand_id', '=', 'brands.id')
                    ->orderBy('brands.name', $direction);
            })
            ->when($sort === 'location.name', function ($query) use ($direction) {
                return $query->join('locations', 'inventories.location_id', '=', 'locations.id')
                    ->orderBy('locations.name', $direction);
            })
            ->when(!in_array($sort, ['category.name', 'brand.name', 'location.name']), function ($query) use ($sort, $direction) {
                return $query->orderBy($sort, $direction);
            })
            ->paginate(10);

        return view('index', compact('inventories', 'sort', 'direction', 'search'));
    }

    public function dashboard()
    {
        // Total number of inventories
        $totalInventories = Inventory::count();

        // Total quantity of all items
        $totalQuantity = Inventory::sum('quantity');

        // Total value of all inventories in Rupiah
        $totalValue = Inventory::sum(\DB::raw('quantity * price'));

        // Format total value in Rupiah
        $totalValue = number_format($totalValue, 0, ',', '.');

        // Total value of all inventories
        $totalValue = Inventory::sum(\DB::raw('quantity * price'));

        // Inventories low in stock (e.g., quantity less than 10)
        $lowStockInventories = Inventory::where('quantity', '<', 10)->get();

        // Recent ingoing items
        $recentIngoing = IngoingItem::with('inventory')->latest()->take(5)->get();

        // Recent outgoing items
        $recentOutgoing = OutgoingItem::with('inventory')->latest()->take(5)->get();

        // Inventory distribution by category
        $inventoryByCategory = Category::withCount('inventories')->get();

        // Inventory distribution by Brand
        $inventoryByBrand = Brand::withCount('inventories')->get();

        return view(
            'dashboard',
            compact(
                'totalInventories',
                'totalQuantity',
                'totalValue',
                'lowStockInventories',
                'recentIngoing',
                'recentOutgoing',
                'inventoryByCategory',
                'inventoryByBrand'
            )
        );
    }
    public function showInventories(Request $request)
    {
        $sort = $request->input('sort', 'name'); // Default sort by 'name'
        $direction = $request->input('direction', 'asc'); // Default sort direction is 'asc'
        $search = $request->input('search'); // Input untuk pencarian

        $inventories = Inventory::select('inventories.*')
            ->when($search, function ($query) use ($search) {
                return $query->where('inventories.name', 'like', '%' . $search . '%')
                    ->orWhere('inventories.barcode', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('location', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->when($sort === 'category.name', function ($query) use ($direction) {
                return $query->join('categories', 'inventories.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $direction);
            })
            ->when($sort === 'brand.name', function ($query) use ($direction) {
                return $query->join('brands', 'inventories.brand_id', '=', 'brands.id')
                    ->orderBy('brands.name', $direction);
            })
            ->when($sort === 'location.name', function ($query) use ($direction) {
                return $query->join('locations', 'inventories.location_id', '=', 'locations.id')
                    ->orderBy('locations.name', $direction);
            })
            ->when(!in_array($sort, ['category.name', 'brand.name', 'location.name']), function ($query) use ($sort, $direction) {
                return $query->orderBy($sort, $direction);
            })
            ->paginate(10);

        return view('inventories', compact('inventories', 'sort', 'direction', 'search'));
    }


    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $locations = Location::all();
        return view('create', compact('categories', 'brands', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'barcode' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'location_id' => 'required|exists:locations,id',

        ]);

        $inventory = Inventory::create($request->all());

        IngoingItem::create([
            'inventory_id' => $inventory->id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('inventory.showInventories')
            ->with('success', 'Inventory created successfully.');

    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $locations = Location::all();
        return view('edit', compact('inventory', 'categories', 'brands', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->update($request->all());
        return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
    }


    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory deleted successfully');
    }


}


