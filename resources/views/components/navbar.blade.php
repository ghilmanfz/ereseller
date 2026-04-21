{{-- Navbar Component --}}
@php
    $currentUser = auth()->user();
    $isCustomer = $currentUser && $currentUser->role === 'customer';
    $cartCount = $isCustomer
        ? $currentUser->cart()->withCount('items')->first()?->items_count ?? 0
        : 0;
@endphp
<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-neutral-100" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/25 group-hover:shadow-primary-500/40 transition-shadow">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-primary-700 tracking-tight">SINTIA SR12</span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-medium {{ request()->is('/') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">Beranda</a>
                <a href="/katalog" class="text-sm font-medium {{ request()->is('katalog*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">Katalog</a>
                @if($isCustomer)
                <a href="/pesanan" class="text-sm font-medium {{ request()->is('pesanan*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">Pesanan Saya</a>
                <a href="/profil" class="text-sm font-medium {{ request()->is('profil') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">Profil</a>
                @endif
            </div>

            {{-- Right Section --}}
            <div class="hidden md:flex items-center gap-3">
                @if($isCustomer)
                {{-- Cart & Avatar (logged in as customer) --}}
                <a href="/keranjang" class="relative p-2 text-neutral-600 hover:text-primary-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-primary-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                </a>
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-bold ml-1">
                    {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                </div>
                <form action="/logout" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="p-2 text-neutral-400 hover:text-red-500 transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
                @elseif($currentUser && $currentUser->role === 'admin')
                <a href="/admin" class="btn-primary text-sm !py-2 !px-5">Dashboard Admin</a>
                <form action="/logout" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="p-2 text-neutral-400 hover:text-red-500 transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
                @else
                <a href="/login" class="btn-primary text-sm !py-2 !px-5">Login / Daftar</a>
                @endif
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-neutral-600 hover:text-primary-600 transition-colors">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t border-neutral-100 shadow-lg">
        <div class="px-4 py-4 space-y-3">
            <a href="/" class="block py-2 text-sm font-medium {{ request()->is('/') ? 'text-primary-600' : 'text-neutral-600' }}">Beranda</a>
            <a href="/katalog" class="block py-2 text-sm font-medium {{ request()->is('katalog*') ? 'text-primary-600' : 'text-neutral-600' }}">Katalog</a>
            @if($isCustomer)
            <a href="/pesanan" class="block py-2 text-sm font-medium {{ request()->is('pesanan*') ? 'text-primary-600' : 'text-neutral-600' }}">Pesanan Saya</a>
            <a href="/profil" class="block py-2 text-sm font-medium {{ request()->is('profil') ? 'text-primary-600' : 'text-neutral-600' }}">Profil</a>
            <a href="/keranjang" class="block py-2 text-sm font-medium text-neutral-600">Keranjang</a>
            <hr class="border-neutral-100">
            <form action="/logout" method="POST" class="block">
                @csrf
                <button type="submit" class="block w-full text-center py-2.5 text-sm font-semibold text-red-500 border border-red-200 rounded-xl">Keluar</button>
            </form>
            @elseif($currentUser && $currentUser->role === 'admin')
            <a href="/admin" class="block py-2 text-sm font-medium text-neutral-600">Dashboard Admin</a>
            <hr class="border-neutral-100">
            <form action="/logout" method="POST" class="block">
                @csrf
                <button type="submit" class="block w-full text-center py-2.5 text-sm font-semibold text-red-500 border border-red-200 rounded-xl">Keluar</button>
            </form>
            @else
            <hr class="border-neutral-100">
            <a href="/login" class="block w-full text-center btn-primary text-sm">Login / Daftar</a>
            @endif
        </div>
    </div>
</nav>

<style>
    [x-cloak] { display: none !important; }
</style>
