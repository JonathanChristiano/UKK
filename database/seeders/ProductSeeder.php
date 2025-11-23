<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun ADMIN
        User::create([
            'name' => 'Admin Rental',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Passwordnya 'password'
            'role' => 'admin',
        ]);

        // 2. Buat Akun USER BIASA
        User::create([
            'name' => 'Pelanggan Setia',
            'email' => 'user@user.com',
            'password' => Hash::make('password'), // Passwordnya 'password'
            'role' => 'user',
        ]);

        // 3. Isi Data PRODUK
        $products = [
            [
                'name' => 'Tenda Dome (4 Org)',
                'price' => 75000,
                'stock' => 8,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Tenda+Dome',
                'description' => 'Tenda kapasitas 4 orang, tahan hujan ringan.',
            ],
            [
                'name' => 'Sleeping Bag',
                'price' => 20000,
                'stock' => 15,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Sleeping+Bag',
                'description' => 'Hangat dan nyaman untuk suhu pegunungan.',
            ],
            [
                'name' => 'Kompor Portable Mini',
                'price' => 15000,
                'stock' => 20,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Kompor+Portable',
                'description' => 'Praktis dibawa, menggunakan gas kaleng.',
            ],
            [
                'name' => 'Carrier (60L)',
                'price' => 40000,
                'stock' => 5,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Carrier',
                'description' => 'Tas gunung kapasitas besar, backsystem nyaman.',
            ],
            [
                'name' => 'Matras Gulung',
                'price' => 5000,
                'stock' => 30,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Matras',
                'description' => 'Alas tidur standar pendaki.',
            ],
            [
                'name' => 'Lampu Tenda LED',
                'price' => 10000,
                'stock' => 12,
                'image' => 'https://placehold.co/400x400/E0F7FA/333?text=Lampu+Tenda',
                'description' => 'Penerangan terang dengan baterai awet.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
