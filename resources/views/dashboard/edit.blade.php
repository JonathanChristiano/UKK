<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto mt-10 px-4 max-w-2xl">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Produk: {{ $product->name }}</h2>

            <!-- Form Update (Method POST dengan @method('PUT')) -->
            <form action="{{ route('dashboard.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- PENTING: Mengubah POST menjadi PUT untuk update -->

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nama Produk</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ $product->stock }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Gambar Produk (Kosongkan jika tidak ingin ganti)</label>
                    <input type="file" name="image" class="w-full border rounded px-3 py-2 focus:outline-blue-500" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini: {{ $product->image }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 focus:outline-blue-500">{{ $product->description }}</textarea>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline py-2">Batal</a>
                    <button type="submit" class="bg-blue-900 text-white font-bold px-6 py-2 rounded hover:bg-blue-800">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>