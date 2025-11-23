<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN PUBLIK (Bisa diakses siapa saja)
// ====================================================
Route::get('/', [HomeController::class, 'index'])->name('home');


// ====================================================
// 2. HALAMAN TAMU (Hanya untuk yang BELUM Login)
// ====================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// ====================================================
// 3. HALAMAN AUTH (User Biasa & Admin bisa akses)
// ====================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard User (Riwayat Sewa)
    Route::get('/my-rentals', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // --- FITUR KERANJANG (CART) ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/update-cart', [CartController::class, 'updateCart'])->name('cart.update'); // <--- PENTING UTK UBAH JUMLAH
    Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

    // --- CHECKOUT WHATSAPP ---
    Route::post('/checkout-wa', [RentalController::class, 'checkout'])->name('checkout.wa');

    // --- PROSES RENTAL LANGSUNG (Backup jika pakai form lama) ---
    Route::post('/rental/store', [RentalController::class, 'store'])->name('rental.store');
});


// ====================================================
// 4. HALAMAN ADMIN (Hanya Role Admin)
// ====================================================
Route::middleware(['auth', 'is_admin'])->group(function () {

    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::get('/dashboard/{id}/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/{id}', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::delete('/dashboard/{id}', [DashboardController::class, 'destroy'])->name('dashboard.destroy');

    // Kelola Sewa (Approval)
    Route::get('/dashboard/rentals', [DashboardController::class, 'rentals'])->name('dashboard.rentals');

    // Aksi Sewa
    Route::patch('/dashboard/rentals/{id}/approve', [DashboardController::class, 'approveRental'])->name('rental.approve');
    Route::patch('/dashboard/rentals/{id}/return', [DashboardController::class, 'returnRental'])->name('rental.return');
    Route::delete('/dashboard/rentals/{id}/reject', [DashboardController::class, 'rejectRental'])->name('rental.reject');
});
