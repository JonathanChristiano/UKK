<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // =======================================================================
    // BAGIAN 1: MANAJEMEN PRODUK (CRUD)
    // =======================================================================

    public function index()
    {
        $products = Product::all();
        return view('dashboard.index', compact('products'));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('dashboard.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('dashboard')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Produk berhasil dihapus!');
    }

    // =======================================================================
    // BAGIAN 2: MANAJEMEN SEWA (RENTAL & STOK OTOMATIS)
    // =======================================================================

    public function rentals()
    {
        $rentals = Rental::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.rentals', compact('rentals'));
    }

    public function approveRental($id)
    {
        $rental = Rental::findOrFail($id);
        $product = $rental->product;

        if ($rental->amount > $product->stock) {
            return redirect()->back()->with('error', 'Gagal menyetujui! Stok barang tidak cukup.');
        }

        $product->decrement('stock', $rental->amount);

        $rental->status = 'approved';
        $rental->save();

        return redirect()->back()->with('success', 'Penyewaan disetujui! Stok barang otomatis berkurang.');
    }

    public function rejectRental($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();

        return redirect()->back()->with('success', 'Permintaan ditolak dan data dihapus.');
    }

    public function returnRental($id)
    {
        $rental = Rental::findOrFail($id);
        $product = $rental->product;

        $product->increment('stock', $rental->amount);

        $rental->status = 'returned';
        $rental->save();

        return redirect()->back()->with('success', 'Barang telah dikembalikan. Stok otomatis ditambahkan kembali.');
    }
}
