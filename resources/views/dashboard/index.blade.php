<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar Admin (Updated) -->
    <nav class="bg-blue-900 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="font-bold text-xl">Admin Panel</a>

            <!-- MENU NAVIGASI ADMIN (BARU) -->
            <div class="flex gap-4 text-sm font-semibold">
                <a href="{{ route('dashboard') }}" class="text-white border-b-2 border-white pb-1">Kelola Produk</a>
                <a href="{{ route('dashboard.rentals') }}" class="hover:text-blue-300 opacity-70">Kelola Sewa</a>
            </div>

            <div class="flex items-center gap-4">
                <span>Halo, {{ Auth::user()->name }}</span>
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white text-sm">Lihat Website</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-red-500 px-3 py-1 rounded text-sm hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10 px-4 pb-20">
        <!-- Pesan Sukses -->
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Kelola Produk</h1>
            <a href="{{ route('dashboard.create') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                + Tambah Produk
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-4 border-b">Gambar</th>
                            <th class="p-4 border-b">Nama Produk</th>
                            <th class="p-4 border-b">Harga</th>
                            <th class="p-4 border-b">Stok</th>
                            <th class="p-4 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-4">
                                <!-- Logika Gambar -->
                                <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                    alt="img" class="w-16 h-16 object-cover rounded border">
                            </td>
                            <td class="p-4 font-bold">{{ $product->name }}</td>
                            <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $product->stock }} Unit
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('dashboard.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <!-- Tombol Hapus (Pakai Form agar aman) -->
                                    <form action="{{ route('dashboard.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

</body>

</html>