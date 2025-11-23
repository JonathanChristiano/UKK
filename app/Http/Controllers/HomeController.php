<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // PENTING: Panggil Model Product

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data dari tabel products
        $products = Product::all();

        // 2. Kirim data tersebut ke tampilan 'welcome'
        // 'compact' itu cara cepat membungkus variabel $products untuk dikirim
        return view('welcome', compact('products'));
    }
}
