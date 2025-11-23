<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Cek Login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login dulu.');
        }

        // 2. Validasi Input
        $request->validate([
            'start_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:start_date',
            'address' => 'required',
            'guarantee' => 'required',
        ]);

        $cart = session()->get('cart');

        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // 3. Hitung Durasi
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->return_date);

        $durasi = $startDate->diffInDays($endDate);
        if ($durasi == 0) $durasi = 1;

        // 4. Susun Pesan WhatsApp (Menggunakan variable PHP)
        $nama = Auth::user()->name;
        $alamat = $request->address;
        $jaminan = $request->guarantee;
        $tglAmbil = $startDate->format('d M Y');
        $tglKembali = $endDate->format('d M Y');

        $waMessage  = "Halo Admin Polar Outdoor, saya ingin menyewa alat.\n\n";
        $waMessage .= "*DATA PENYEWA:*\n";
        $waMessage .= "👤 Nama: $nama\n";
        $waMessage .= "🏠 Alamat: $alamat\n";
        $waMessage .= "🆔 Jaminan: $jaminan\n\n";

        $waMessage .= "*DETAIL SEWA:*\n";
        $waMessage .= "📅 Ambil: $tglAmbil\n";
        $waMessage .= "📅 Kembali: $tglKembali (Jam 21.00)\n";
        $waMessage .= "⏳ Durasi: $durasi Malam\n\n";
        $waMessage .= "*DAFTAR BARANG:*\n";

        $totalHarga = 0;

        // 5. Loop Simpan & Tambah Teks
        foreach ($cart as $id => $details) {
            $product = Product::find($id);

            if ($product->stock < $details['quantity']) {
                return redirect()->back()->with('error', 'Stok ' . $product->name . ' tidak cukup!');
            }

            Rental::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'amount' => $details['quantity'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending',
            ]);

            $subtotal = $details['price'] * $details['quantity'] * $durasi;
            $totalHarga += $subtotal;

            $waMessage .= "- " . $details['name'] . " (" . $details['quantity'] . " pcs) = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $waMessage .= "\n*TOTAL BAYAR: Rp " . number_format($totalHarga, 0, ',', '.') . "*\n";
        $waMessage .= "\nMohon info pembayarannya. Terima kasih!";

        // 6. Hapus Keranjang
        session()->forget('cart');

        // 7. Redirect ke WhatsApp (METODE API LENGKAP)
        // Ini metode paling aman untuk kirim teks panjang
        $noHpAdmin = '6285335456551';

        $query = http_build_query([
            'phone' => $noHpAdmin,
            'text' => $waMessage,
        ]);

        return redirect()->away("https://api.whatsapp.com/send?" . $query);
    }

    // Fungsi store lama (biarkan kosong/hapus tidak apa-apa)
    public function store(Request $request)
    {
        // ...
    }
}
