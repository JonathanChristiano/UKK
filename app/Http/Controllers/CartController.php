<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('user.cart', compact('cart'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        // PASTIKAN BAGIAN INI ADA
        return redirect()->back()->with('success', 'Produk masuk keranjang!');
    }

    // === FUNGSI BARU: UPDATE JUMLAH ===
    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');

            // Update jumlah
            $cart[$request->id]["quantity"] = $request->quantity;

            // Simpan kembali ke session
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Jumlah berhasil diubah');
        }
    }
    // ==================================

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Produk dihapus');
        }
    }
}
