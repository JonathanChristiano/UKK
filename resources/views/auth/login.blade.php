<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KempingYuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="justify-center items-center">
            <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Login</h2>
            <img src="{{ asset('img/logo.png') }}" alt="Polar Outdoor" class="w-48 items-center mx-auto mb-6">
        </div>

        <!-- Pesan Error -->
        @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Pesan Sukses -->
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf <!-- Wajib ada di Laravel untuk keamanan Form -->

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 rounded-lg hover:bg-blue-800 transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-600 text-sm mt-4">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar disini</a>
        </p>
        <p class="text-center mt-2">
            <a href="{{ route('home') }}" class="text-gray-500 text-xs hover:underline">Kembali ke Home</a>
        </p>
    </div>

</body>

</html>