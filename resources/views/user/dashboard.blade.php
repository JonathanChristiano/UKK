<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Sewa Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

    <!-- Navbar User -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Polar Outdoor" class="h-12 w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            </a>
            <div class="flex items-center gap-4">
                <span class="text-gray-700">Halo, {{ Auth::user()->name }}</span>
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline text-sm">Sewa Lagi</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10 px-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Penyewaan Saya</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($rentals->isEmpty())
            <div class="p-10 text-center">
                <p class="text-gray-500 text-lg">Kamu belum pernah menyewa alat apapun.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Mulai Menyewa</a>
            </div>
            @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="p-4 border-b text-blue-900 font-semibold">Barang</th>
                                <th class="p-4 border-b text-blue-900 font-semibold">Tanggal Pinjam</th>
                                <th class="p-4 border-b text-blue-900 font-semibold">Tanggal Kembali</th>
                                <th class="p-4 border-b text-blue-900 font-semibold">Jumlah</th>
                                <th class="p-4 border-b text-blue-900 font-semibold">Total Harga</th>
                                <th class="p-4 border-b text-blue-900 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="p-4 flex items-center gap-3">
                                    <img src="{{ Str::startsWith($rental->product->image, 'http') ? $rental->product->image : asset('storage/' . $rental->product->image) }}"
                                        class="w-12 h-12 rounded object-cover border">
                                    <span class="font-bold text-gray-700">{{ $rental->product->name }}</span>
                                </td>
                                <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}</td>
                                <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</td>
                                <td class="p-4 text-gray-600">{{ $rental->amount }} Unit</td>
                                <td class="p-4 font-bold text-green-600">
                                    <!-- Hitung Total Harga: Harga x Jumlah x Durasi Hari -->
                                    @php
                                    $durasi = \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date));
                                    $total = $rental->product->price * $rental->amount * ($durasi ?: 1);
                                    @endphp
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    @if($rental->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                                    @elseif($rental->status == 'approved')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Disetujui</span>
                                    @elseif($rental->status == 'returned')
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                                    @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

</body>

</html>