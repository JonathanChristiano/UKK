@php
$cart = session('cart', []);
$totalPerMalam = 0;
foreach($cart as $id => $details) {
$totalPerMalam += $details['price'] * $details['quantity'];
}
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- WAJIB ADA -->
    <title>Keranjang Sewa - Polar Outdoor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <div class="container mx-auto mt-6 px-4 md:px-6 mb-20">
        <h1 class="text-2xl md:text-3xl font-bold text-blue-900 mb-6 text-center md:text-left">Keranjang & Checkout</h1>

        <a href="{{ route('home') }}" class="text-blue-600 hover:underline mb-4 inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali Pilih Barang
        </a>

        @if(session('success')) <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm border border-green-200">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm border border-red-200">{{ session('error') }}</div> @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- DAFTAR BARANG -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Daftar Barang</h3>

                    @if(count($cart) > 0)
                    <div class="overflow-x-auto"> <!-- Scroll Horizontal di HP -->
                        <table class="w-full min-w-[500px]"> <!-- Min width biar ga gepeng -->
                            <thead>
                                <tr class="border-b text-left text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="pb-3 pl-2">Produk</th>
                                    <th class="pb-3">Harga/Malam</th>
                                    <th class="pb-3 text-center">Jml</th>
                                    <th class="pb-3 text-right pr-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($cart as $id => $details)
                                <tr class="group hover:bg-gray-50 transition">
                                    <td class="py-3 flex items-center gap-3 pl-2">
                                        <img src="{{ Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}" class="w-12 h-12 rounded object-cover border">
                                        <span class="font-semibold text-gray-700 text-sm">{{ $details['name'] }}</span>
                                    </td>
                                    <td class="py-3 text-sm text-gray-600">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('cart.update') }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $id }}"><input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
                                                <button class="w-6 h-6 bg-gray-200 rounded text-gray-600 text-xs hover:bg-gray-300" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                            </form>
                                            <span class="text-sm font-bold">{{ $details['quantity'] }}</span>
                                            <form action="{{ route('cart.update') }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $id }}"><input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                                <button class="w-6 h-6 bg-blue-100 text-blue-600 rounded text-xs hover:bg-blue-200">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right pr-2">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button class="text-red-500 text-xs hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-10">
                        <p class="text-gray-500 mb-4 text-sm">Keranjang kosong.</p>
                        <a href="{{ route('home') }}" class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm hover:bg-blue-700">Belanja Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- FORM CHECKOUT -->
            @if(count($cart) > 0)
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white p-5 rounded-xl shadow-md border border-gray-100 sticky top-20">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Checkout</h3>

                    <form action="{{ route('checkout.wa') }}" method="POST" target="_blank">
                        @csrf
                        <!-- Form Inputs (Sama seperti sebelumnya, hanya styling responsif) -->
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold text-gray-600 uppercase">Nama</label>
                                <input type="text" value="{{ Auth::user()->name }}" class="w-full border bg-gray-50 rounded p-2 text-sm text-gray-600" readonly>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600 uppercase">Alamat</label>
                                <textarea name="address" rows="2" required class="w-full border rounded p-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Alamat lengkap..."></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-600 uppercase">Jaminan</label>
                                <select name="guarantee" class="w-full border rounded p-2 text-sm bg-white">
                                    <option value="KTP Asli">KTP Asli</option>
                                    <option value="SIM Asli">SIM Asli</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div>
                                    <label class="text-xs font-bold text-gray-600">Tgl Mulai</label>
                                    <input type="date" name="start_date" id="start_date" required class="w-full border rounded p-2 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-600">Durasi</label>
                                    <div class="flex items-center">
                                        <input type="number" id="duration_input" value="1" min="1" class="w-full border rounded p-2 text-sm text-center font-bold text-blue-900">
                                        <span class="ml-2 text-xs text-gray-500">Malam</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-600">Kembali</label>
                                <input type="date" name="return_date" id="return_date" required class="w-full border bg-gray-50 rounded p-2 text-sm text-gray-500" readonly>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-blue-50 p-4 rounded-lg mt-5 border border-blue-100">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Harga/Malam:</span>
                                <span>Rp {{ number_format($totalPerMalam, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-blue-900 border-t border-blue-200 pt-2 mt-1">
                                <span>Total:</span>
                                <span id="total_bayar">Rp {{ number_format($totalPerMalam, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 shadow-lg transition flex justify-center items-center gap-2 text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            Pesan via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Script JS sama persis dengan sebelumnya (tidak perlu diubah) -->
    <script>
        // Copy script dari file cart.blade.php sebelumnya kesini
        // ... (Logika hitung tanggal) ...
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const durationInput = document.getElementById('duration_input');
            const returnDateInput = document.getElementById('return_date');
            const totalBayarTxt = document.getElementById('total_bayar');
            const hargaPerMalam = "{{ $totalPerMalam }}";

            function updateCalculation() {
                const startVal = startDateInput.value;
                let malam = parseInt(durationInput.value);
                if (!malam || malam < 1) malam = 1;

                if (startVal) {
                    const startDate = new Date(startVal);
                    const returnDate = new Date(startDate);
                    returnDate.setDate(startDate.getDate() + malam);

                    const year = returnDate.getFullYear();
                    const month = String(returnDate.getMonth() + 1).padStart(2, '0');
                    const day = String(returnDate.getDate()).padStart(2, '0');

                    returnDateInput.value = `${year}-${month}-${day}`;

                    const total = malam * hargaPerMalam;
                    totalBayarTxt.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
                }
            }

            if (startDateInput && durationInput) {
                startDateInput.addEventListener('input', updateCalculation);
                durationInput.addEventListener('input', updateCalculation);
                const today = new Date().toISOString().split('T')[0];
                startDateInput.setAttribute('min', today);
            }
        });
    </script>
</body>

</html>