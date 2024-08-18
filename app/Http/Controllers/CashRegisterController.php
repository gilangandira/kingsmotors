<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Inventory;
use App\Models\InvoiceItem;
use App\Models\OutgoingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    // Metode untuk menampilkan daftar invoice dengan pagination
    public function listInvoices(Request $request)
    {
        // Ambil query pencarian, rentang tanggal, dan kolom sort dari permintaan
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sortBy = $request->input('sort_by', 'created_at'); // Default sorting by created_at
        $sortDirection = $request->input('sort_direction', 'desc'); // Default sort direction

        // Query untuk invoice dengan pencarian, tanggal, sorting, dan pagination
        $invoices = Invoice::with('items.inventory')
            ->where(function ($query) use ($search, $startDate, $endDate) {
                if ($search) {
                    $query->where('id', 'like', "%$search%")
                        ->orWhere('total_amount', 'like', "%$search%");
                }

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            })
            ->orderBy($sortBy, $sortDirection) // Sorting berdasarkan kolom
            ->paginate(10);

        return view('cash_register.list_invoices', compact('invoices', 'search', 'startDate', 'endDate', 'sortBy', 'sortDirection'));
    }
    public function showCart()
    {
        return view('cash_register.create');
    }

    public function addToCart(Request $request)
    {
        $inventory = Inventory::where('barcode', $request->barcode)
            ->orWhere('name', $request->name)
            ->first();

        if ($inventory) {
            $cart = session()->get('cart', []);

            if (isset($cart[$inventory->id])) {
                // Ensure quantity does not exceed available inventory
                $newQuantity = $cart[$inventory->id]['quantity'] + $request->quantity;
                if ($newQuantity <= $inventory->quantity) {
                    $cart[$inventory->id]['quantity'] = $newQuantity;
                } else {
                    return redirect()->route('cash_register.create')->with('error', 'Quantity exceeds available stock.');
                }
            } else {
                if ($request->quantity <= $inventory->quantity) {
                    $cart[$inventory->id] = [
                        'name' => $inventory->name,
                        'quantity' => $request->quantity,
                        'price' => $inventory->price,
                    ];
                } else {
                    return redirect()->route('cash_register.create')->with('error', 'Quantity exceeds available stock.');
                }
            }

            session()->put('cart', $cart);

            return redirect()->route('cash_register.create')->with('success', 'Item added to cart');
        }

        return redirect()->route('cash_register.create')->with('error', 'Item not found');
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->input('id');
        $quantity = $request->input('quantity');

        if (isset($cart[$id])) {
            // Ensure quantity does not exceed available inventory
            $inventory = Inventory::find($id);
            if ($quantity <= $inventory->quantity) {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Quantity exceeds available stock.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart.']);
    }


    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cash_register.create')->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if ($cart) {
            $totalAmount = array_sum(array_map(function ($item) {
                return $item['quantity'] * $item['price'];
            }, $cart));

            $invoice = Invoice::create([
                'total_amount' => $totalAmount,
                'user_id' => auth()->id(),
            ]);

            foreach ($cart as $inventoryId => $details) {
                // Tambahkan barang keluar
                OutgoingItem::create([
                    'inventory_id' => $inventoryId,
                    'quantity' => $details['quantity'],
                ]);

                // Buat invoice item
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'inventory_id' => $inventoryId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'], // harga per item
                    'total' => $details['quantity'] * $details['price'], // total harga item
                ]);

                // Update inventory quantity
                $inventory = Inventory::find($inventoryId);
                $inventory->quantity -= $details['quantity'];
                $inventory->save();
            }

            session()->forget('cart');

            return redirect()->route('cash_register.show', $invoice->id);
        }

        return redirect()->route('cash_register.create')->with('error', 'Cart is empty');
    }





    public function showInvoice($id)
    {
        $invoice = Invoice::with('items.inventory')->findOrFail($id);
        return view('cash_register.show', compact('invoice'));
    }
}
