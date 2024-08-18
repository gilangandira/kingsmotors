<?php

namespace App\Http\Controllers;

use App\Models\IngoingItem;
use App\Models\Inventory;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistributionController extends Controller
{
    public function showOutgoingForm(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = OutgoingItem::with('inventory');

        // Filter based on date range
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('inventory', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                })
                    ->orWhere('quantity', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }

        $outgoingItems = $query->orderBy($sort, $direction)->paginate(10);

        return view('outgoing', [
            'outgoingItems' => $outgoingItems,
            'sort' => $sort,
            'direction' => $direction,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $search,
        ]);
    }

    public function storeOutgoing(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
        ]);

        // Kurangi jumlah inventori
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Tambahkan barang keluar
        OutgoingItem::create([
            'inventory_id' => $inventory->id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Outgoing item recorded successfully');
    }
    public function scan(Request $request)
    {
        $barcode = $request->input('barcode');

        // Cari item berdasarkan barcode
        $item = Inventory::where('barcode', $barcode)->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'data' => $item
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ]);
        }
    }

    public function submitOutgoingByScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:inventories,barcode', // Validasi barcode
            'quantity' => 'required|integer|min:1',
        ]);

        // Cari item berdasarkan barcode
        $inventory = Inventory::where('barcode', $request->barcode)->firstOrFail();

        // Validasi jika kuantitas mencukupi
        if ($request->quantity > $inventory->quantity) {
            return response()->json(['error' => 'Not enough inventory available.'], 422);
        }

        // Kurangi kuantitas inventaris
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Catat barang keluar
        OutgoingItem::create([
            'inventory_id' => $inventory->id,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['success' => 'Outgoing item recorded successfully.']);
    }

    public function destroyOutgoing(OutgoingItem $outgoingItem)
    {
        $inventory = $outgoingItem->inventory;
        $inventory->quantity += $outgoingItem->quantity;
        $inventory->save();

        $outgoingItem->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Outgoing item deleted successfully');
    }

    public function showIngoingForm(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = IngoingItem::with('inventory');

        // Filter based on date range
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search functionality
        if ($search) {
            $query->whereHas('inventory', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
                ->orWhere('quantity', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%");
        }

        $outgoingItems = $query->orderBy($sort, $direction)->paginate(10);

        return view('ingoing', [
            'outgoingItems' => $outgoingItems,
            'sort' => $sort,
            'direction' => $direction,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $search,
        ]);
    }
    public function listIngoingItem(Request $request)
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

        return view('listIngoing', compact('inventories', 'sort', 'direction', 'search'));
    }

    public function storeIngoing(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Tambah jumlah inventori
        $inventory->quantity += $request->quantity;
        $inventory->save();

        // Tambah barang ingoing
        IngoingItem::create([
            'inventory_id' => $inventory->id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('inventory.listIngoingItem')
            ->with('success', 'Ingoing item recorded successfully');
    }


}
