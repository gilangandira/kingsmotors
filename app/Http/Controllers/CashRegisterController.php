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
    public function showCart()
    {
        return view('cash_register.create');
    }

    public function addToCart(Request $request)
    {
        $inventory = Inventory::where('barcode', $request->barcode)->orWhere('name', $request->name)->first();

        if ($inventory) {
            $cart = session()->get('cart', []);

            if (isset($cart[$inventory->id])) {
                $cart[$inventory->id]['quantity'] += $request->quantity;
            } else {
                $cart[$inventory->id] = [
                    'name' => $inventory->name,
                    'quantity' => $request->quantity,
                    'price' => $inventory->price,
                ];
            }

            session()->put('cart', $cart);

            return redirect()->route('cash_register.create')->with('success', 'Item added to cart');
        }

        return redirect()->route('cash_register.create')->with('error', 'Item not found');
    }
    public function removeFromCart($inventoryId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$inventoryId])) {
            unset($cart[$inventoryId]);
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
