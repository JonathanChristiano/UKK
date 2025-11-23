@auth
<!-- Keranjang -->
<a href="{{ route('cart.index') }}" class="relative text-xl hover:scale-110 transition flex items-center gap-2 text-gray-700">
    🛒
    <span class="md:hidden text-sm font-semibold">Keranjang</span>
    @if(session('cart') && count(session('cart')) > 0)
    <span class="bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center absolute top-0 right-0 md:-top-2 md:-right-2 shadow-sm">
        {{ count(session('cart')) }}
    </span>
    @endif
</a>

<!-- Nama & Dashboard -->
<div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
    <span class="text-blue-900 font-bold text-sm">Hi, {{ Auth::user()->name }}</span>

    @if(Auth::user()->role === 'admin')
    <a href="{{ route('dashboard') }}" class="text-center text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded hover:bg-blue-200">Admin Panel</a>
    @else
    <a href="{{ route('user.dashboard') }}" class="text-center text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded hover:bg-yellow-200">Riwayat</a>
    @endif

    <form action="{{ route('logout') }}" method="POST" class="inline text-center md:text-left">
        @csrf
        <button class="text-red-500 text-sm font-bold hover:underline">Logout</button>
    </form>
</div>
@else
<div class="flex flex-col md:flex-row gap-3">
    <a href="{{ route('login') }}" class="text-center text-gray-700 font-medium py-2">Login</a>
    <a href="{{ route('register') }}" class="text-center bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 shadow-md transition text-sm font-bold">Register</a>
</div>
@endauth