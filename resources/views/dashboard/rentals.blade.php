<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sewa - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar Admin -->
    <nav class="bg-blue-900 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl">Admin Panel</a>
            <div class="flex gap-4 text-sm font-semibold">
                <!-- MENU NAVIGASI ADMIN -->
                <a href="{{ route('dashboard') }}" class="hover:text-blue-300 opacity-70">Kelola Produk</a>
                <a href="{{ route('dashboard.rentals') }}" class="text-white border-b-2 border-white pb-1">Kelola Sewa</a>
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

    <div class="container mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Permintaan Sewa</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-4 border-b">Penyewa</th>
                            <th class="p-4 border-b">Barang</th>
                            <th class="p-4 border-b">Tanggal</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $rental->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $rental->user->email }}</div>
                            </td>
                            <td class="p-4">
                                {{ $rental->product->name }} <br>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $rental->amount }} Unit
                                </span>
                            </td>
                            <td class="p-4 text-sm">
                                Mulai: {{ $rental->start_date }} <br>
                                Kembali: {{ $rental->end_date }}
                            </td>
                            <td class="p-4">
                                @if($rental->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif($rental->status == 'approved')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Disetujui</span>
                                @elseif($rental->status == 'returned')
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">

                                <!-- Skenario 1: Kalau Status PENDING -->
                                <!-- Munculkan tombol Setujui & Tolak -->
                                @if($rental->status == 'pending')
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Setujui -->
                                    <form action="{{ route('rental.approve', $rental->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs font-bold">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Tombol Tolak -->
                                    <form action="{{ route('rental.reject', $rental->id) }}" method="POST" onsubmit="return confirm('Yakin tolak pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs font-bold">
                                            Tolak
                                        </button>
                                    </form>
                                </div>

                                <!-- Skenario 2: Kalau Status APPROVED (Sedang dipinjam) -->
                                <!-- Munculkan tombol Selesai/Kembalikan -->
                                @elseif($rental->status == 'approved')
                                <form action="{{ route('rental.return', $rental->id) }}" method="POST" onsubmit="return confirm('Barang sudah dikembalikan?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 text-xs font-bold">
                                        Tandai Selesai
                                    </button>
                                </form>

                                <!-- Skenario 3: Kalau Status RETURNED (Sudah balik) -->
                                @else
                                <span class="text-gray-400 text-xs italic">Transaksi Selesai</span>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

</body>

</html>