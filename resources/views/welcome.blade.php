<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kamping - Polar Outdoor</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .slider-item {
            display: none;
            transition: opacity 1s ease-in-out;
            opacity: 0;
        }

        .slider-item.active {
            display: block;
            opacity: 1;
        }

        .aspect-1-1 {
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }
    </style>
</head>

<body class="text-black">

    <!-- 1. NAVBAR RESPONSIF -->
    <nav class="bg-white/90 backdrop-blur-md shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-3 relative">
            <div class="flex justify-between items-center">

                <!-- LOGO ADMIN -->
                <a href="{{ route('home') }}" class="flex items-center z-50">
                    <img src="{{ asset('img/logo.png') }}" alt="Polar Outdoor" class="h-10 md:h-12 w-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span style="display:none;" class="font-bold text-xl md:text-2xl text-blue-900">Polar Outdoor</span>
                </a>

                <!-- === MENU DESKTOP (Hanya muncul di layar besar) === -->
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="#home" class="text-gray-700 hover:text-blue-700 font-medium transition">Home</a>
                    <a href="#product" class="text-gray-700 hover:text-blue-700 font-medium transition">Product</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-700 font-medium transition">Contact Us!</a>

                    @auth
                    <div class="flex items-center gap-4">
                        <a href="{{ route('cart.index') }}" class="relative text-2xl hover:scale-110 transition mr-2" title="Lihat Keranjang">
                            🛒
                            @if(session('cart') && count(session('cart')) > 0)
                            <span class="bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center absolute -top-2 -right-2 shadow-sm">
                                {{ count(session('cart')) }}
                            </span>
                            @endif
                        </a>

                        <span class="text-blue-900 font-bold text-sm">Hi, {{ Auth::user()->name }}!</span>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded hover:bg-blue-200">Admin</a>
                        @else
                        <a href="{{ route('user.dashboard') }}" class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded hover:bg-yellow-200">Riwayat</a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="text-red-500 text-sm font-bold hover:underline ml-2">Logout</button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-800">Register</a>
                    @endauth
                </div>

                <!-- === TOMBOL HAMBURGER (Hanya muncul di HP) === -->
                <div class="md:hidden flex items-center z-50">
                    <button id="mobile-menu-btn" class="text-gray-700 focus:outline-none p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- === MENU MOBILE (Muncul saat tombol hamburger diklik) === -->
            <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-lg border-t border-gray-100 flex flex-col p-4 space-y-4 transition-all duration-300 ease-in-out">
                <a href="#home" class="text-gray-700 hover:text-blue-700 font-medium text-lg border-b pb-2">Home</a>
                <a href="#product" class="text-gray-700 hover:text-blue-700 font-medium text-lg border-b pb-2">Product</a>
                <a href="#contact" class="text-gray-700 hover:text-blue-700 font-medium text-lg border-b pb-2">Contact Us!</a>

                @auth
                <div class="pt-2 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-900 font-bold">Hi, {{ Auth::user()->name }}</span>
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-full">
                            🛒 <span class="text-sm font-bold">{{ session('cart') ? count(session('cart')) : 0 }} Item</span>
                        </a>
                    </div>

                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="text-center block w-full bg-blue-100 text-blue-800 px-4 py-2 rounded font-bold">Admin Panel</a>
                    @else
                    <a href="{{ route('user.dashboard') }}" class="text-center block w-full bg-yellow-100 text-yellow-800 px-4 py-2 rounded font-bold">Riwayat Sewa</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button class="w-full text-center text-red-600 font-bold border border-red-200 py-2 rounded hover:bg-red-50">Logout</button>
                    </form>
                </div>
                @else
                <div class="pt-2 flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="text-center text-gray-700 font-medium border border-gray-300 py-2 rounded">Login</a>
                    <a href="{{ route('register') }}" class="text-center bg-blue-700 text-white py-2 rounded font-bold shadow-md">Register</a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 2. SLIDER -->
    <section id="home" class="relative w-full h-[60vh] md:h-[90vh] overflow-hidden">
        <div id="image-slider" class="relative w-full h-full">
            <div class="slider-item active absolute inset-0 w-full h-full bg-cover bg-center"
                style="background-image: url('https://placehold.co/1920x1080/a0c4ff/333?text=Polar+Outdoor+Rental')">
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                    <h1 class="text-white text-3xl md:text-6xl font-bold text-center drop-shadow-lg px-4">Sewa Peralatan Kamping<br>Mudah & Cepat</h1>
                </div>
            </div>
            <div class="slider-item absolute inset-0 w-full h-full bg-cover bg-center"
                style="background-image: url('https://placehold.co/1920x1080/b2f7ef/333?text=Siap+Mendaki')">
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                    <h1 class="text-white text-3xl md:text-6xl font-bold text-center drop-shadow-lg px-4">Kualitas Terbaik<br>Harga Bersahabat</h1>
                </div>
            </div>
            <div class="slider-item absolute inset-0 w-full h-full bg-cover bg-center"
                style="background-image: url('https://placehold.co/1920x1080/90e0ef/333?text=Jelajahi+Alam')">
                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                    <h1 class="text-white text-3xl md:text-6xl font-bold text-center drop-shadow-lg px-4">Mulai Petualanganmu<br>Sekarang!</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. PRODUK -->
    <section id="product" class="py-20 bg-biru-muda">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">Our Product</h2>

            <!-- Pesan Sukses Melayang -->
            @if(session('success'))
            <div class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded shadow-lg animate-bounce z-50 font-bold">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 flex flex-col h-full">
                    <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}" class="w-full h-auto aspect-1-1">

                    <div class="p-6 flex flex-col grow">
                        <h3 class="text-xl md:text-2xl font-bold mb-2 text-gray-800">{{ $product->name }}</h3>
                        <p class="text-lg md:text-xl font-semibold text-blue-700 mb-1">Rp {{ number_format($product->price, 0, ',', '.') }} / malam</p>
                        <p class="text-sm text-gray-600 mb-4">Stok Tersisa: {{ $product->stock }}</p>

                        <div class="mt-auto">
                            @if($product->stock > 0)
                            <a href="{{ route('cart.add', $product->id) }}" class="block w-full text-center bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-800 transition shadow-md">
                                + Tambah ke Keranjang
                            </a>
                            @else
                            <button disabled class="block w-full text-center bg-gray-300 text-gray-500 font-bold py-3 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. CONTACT US -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">Contact Us!</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <!-- Alamat -->
                    <div class="flex items-start space-x-4">
                        <div><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Alamat</h3>
                            <a href="https://maps.app.goo.gl/Ug8jSsJtpD4YKuMZ7" target="_blank" class="text-gray-600 hover:text-blue-700 hover:underline text-left block">
                                Jl. Selomas Tim. VI No.272, Panggung Lor, Kec. Semarang Utara, Kota Semarang
                            </a>
                        </div>
                    </div>
                    <!-- Nomer HP -->
                    <div class="flex items-start space-x-4">
                        <div><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Nomer HP</h3>
                            <a href="https://wa.me/6285335456551" target="_blank" class="text-blue-600 hover:underline text-lg">+62 853-3545-6551</a>
                        </div>
                    </div>
                    <!-- Instagram -->
                    <div class="flex items-start space-x-4">
                        <div><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 8.25c0-1.036.84-1.875 1.875-1.875h3.375c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-12.75c-1.036 0-1.875-.84-1.875-1.875V8.25c0-1.036.84-1.875 1.875-1.875h3.375M16.5 6.375c0-1.036-.84-1.875-1.875-1.875h-3.375c-1.036 0-1.875.84-1.875 1.875v1.875h7.125V6.375z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6.375a1.875 1.875 0 00-3.75 0V8.25h3.75V6.375zM9 14.25c0-1.036.84-1.875 1.875-1.875h1.875c1.036 0 1.875.84 1.875 1.875v1.875c0 1.035-.84 1.875-1.875 1.875h-1.875C9.84 18 9 17.16 9 16.125v-1.875z" />
                            </svg></div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Instagram</h3>
                            <a href="https://www.instagram.com/polar.outdoor?igsh=MXVtcXpwZWY2cXRwdA==" target="_blank" class="text-blue-600 hover:underline">@polar.outdoor</a>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-300 rounded-lg shadow-md overflow-hidden h-64 md:h-96">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.365793710519!2d110.40371887499703!3d-6.9661309930342755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70f4b63ac3f723%3A0x4027a76e352bae0!2sJl.%20Selomas%20Tim.%20VI%20No.272%2C%20Panggung%20Lor%2C%20Kec.%20Semarang%20Utara%2C%20Kota%20Semarang%2C%20Jawa%20Tengah%2050177!5e0!3m2!1sid!2sid!4v1700634567890!5m2!1sid!2sid"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" title="Lokasi Polar Outdoor">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-10">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Polar Outdoor Rental. Dibuat untuk Proyek Kompetensi.</p>
        </div>
    </footer>

    <!-- Script Slider & Mobile Menu -->
    <script>
        // Slider
        const sliderItems = document.querySelectorAll('.slider-item');
        let currentSlide = 0;
        setInterval(() => {
            sliderItems.forEach(item => item.classList.remove('active'));
            sliderItems[currentSlide].classList.add('active');
            currentSlide = (currentSlide + 1) % sliderItems.length;
        }, 4000);

        // === SCRIPT UNTUK TOMBOL HAMBURGER ===
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>

</html>