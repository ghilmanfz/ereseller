@extends('layouts.app')
@section('title', 'Katalog Produk')
@section('meta_description', 'Katalog lengkap produk SR12 skincare herbal berkualitas. Temukan produk kecantikan alami terbaik.')

@section('content')
@php
$allProducts = [
    ['name' => 'SR12 Pinky Glow', 'category' => 'Skincare', 'price' => 150000, 'rating' => 4.8, 'stock' => 15, 'stock_status' => 'In-Stock', 'slug' => 'sr12-pinky-glow', 'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=500&fit=crop', 'description' => 'Krim pencerah wajah dengan efek glowing instan'],
    ['name' => 'Facial Wash Honey', 'category' => 'Skincare', 'price' => 65000, 'rating' => 4.9, 'stock' => 5, 'stock_status' => 'Limited', 'slug' => 'facial-wash-honey', 'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=400&h=500&fit=crop', 'description' => 'Pembersih wajah dengan madu alami'],
    ['name' => 'Body Lotion', 'category' => 'Bodycare', 'price' => 85000, 'rating' => 4.7, 'stock' => 24, 'stock_status' => 'In-Stock', 'slug' => 'body-lotion', 'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop', 'description' => 'Lotion tubuh dengan SPF 30 dan aroma mewah'],
    ['name' => 'Deodorant Spray', 'category' => 'Bodycare', 'price' => 45000, 'rating' => 4.6, 'stock' => 50, 'stock_status' => 'In-Stock', 'slug' => 'deodorant-spray', 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=500&fit=crop', 'description' => 'Menghilangkan bau badan 24 jam tanpa'],
    ['name' => 'Minyak Bulus SR12', 'category' => 'Herbal', 'price' => 95000, 'rating' => 4.8, 'stock' => 0, 'stock_status' => 'Out-of-Stock', 'slug' => 'minyak-bulus-sr12', 'image' => 'https://images.unsplash.com/photo-1617897903246-719242758050?w=400&h=500&fit=crop', 'description' => 'Minyak tradisional untuk berbagai masalah kulit'],
    ['name' => 'Lip Care Natural', 'category' => 'Cosmetic', 'price' => 25000, 'rating' => 4.5, 'stock' => 12, 'stock_status' => 'In-Stock', 'slug' => 'lip-care-natural', 'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=500&fit=crop', 'description' => 'Pelembab bibir alami untuk mengatasi bibir'],
    ['name' => 'SR12 Pinky Glow Cream', 'category' => 'Skincare', 'price' => 150000, 'rating' => 4.8, 'stock' => 15, 'stock_status' => 'In-Stock', 'slug' => 'sr12-pinky-glow-cream', 'image' => 'https://images.unsplash.com/photo-1570194065650-d99fb4b38b17?w=400&h=500&fit=crop', 'description' => 'Krim pencerah wajah dengan efek glowing'],
    ['name' => 'Facial Wash Honey Plus', 'category' => 'Skincare', 'price' => 65000, 'rating' => 4.9, 'stock' => 5, 'stock_status' => 'Limited', 'slug' => 'facial-wash-honey-plus', 'image' => 'https://images.unsplash.com/photo-1556228841-a3c527ebefe5?w=400&h=500&fit=crop', 'description' => 'Pembersih wajah dengan madu alami'],
    ['name' => 'Body Lotion SPF', 'category' => 'Bodycare', 'price' => 85000, 'rating' => 4.7, 'stock' => 24, 'stock_status' => 'In-Stock', 'slug' => 'body-lotion-spf', 'image' => 'https://images.unsplash.com/photo-1625772452859-1c03d5bf1137?w=400&h=500&fit=crop', 'description' => 'Lotion tubuh dengan SPF 30 dan aroma mewah'],
    ['name' => 'Deodoran Spray Plus', 'category' => 'Bodycare', 'price' => 45000, 'rating' => 4.6, 'stock' => 50, 'stock_status' => 'In-Stock', 'slug' => 'deodoran-spray-plus', 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400&h=500&fit=crop', 'description' => 'Menghilangkan bau badan 24 jam'],
];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar Filter --}}
        <aside class="w-full lg:w-64 shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="lg:hidden w-full flex items-center justify-between p-3 bg-neutral-50 rounded-xl mb-4 text-sm font-semibold text-neutral-700">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter Produk
                </span>
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>

            <div :class="{ 'hidden lg:block': !open }" class="space-y-6">
                <div class="flex items-center gap-2 text-sm font-bold text-neutral-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter Produk
                </div>
                <hr class="border-primary-500 w-10 border-2 rounded">

                {{-- Kategori --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-semibold text-neutral-700">
                        Kategori <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="mt-3 space-y-1">
                        @foreach(['Semua Produk', 'Skincare', 'Bodycare', 'Herbal & Suplemen', 'Kosmetik'] as $i => $cat)
                        <a href="#" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ $i === 0 ? 'bg-primary-50 text-primary-600 font-semibold' : 'text-neutral-600 hover:bg-neutral-50' }}">{{ $cat }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- Ketersediaan --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-semibold text-neutral-700">
                        Ketersediaan <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="mt-3 space-y-2">
                        @foreach([['Tersedia (Ready)', 'primary'], ['Stok Terbatas', 'neutral'], ['Pre-Order', 'neutral']] as $avail)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" {{ $avail[1] === 'primary' ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-neutral-600">{{ $avail[0] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Rentang Harga --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-semibold text-neutral-700">
                        Rentang Harga <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" class="mt-3">
                        <input type="range" min="0" max="500000" value="250000" class="w-full accent-primary-600">
                        <div class="flex justify-between text-xs text-neutral-400 mt-1"><span>Rp 0</span><span>Rp 500.000</span></div>
                    </div>
                </div>

                {{-- Help Box --}}
                <div class="mt-6 bg-primary-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-primary-700 uppercase">Butuh Bantuan?</p>
                    <p class="text-xs text-neutral-500 mt-1">Konsultasi gratis produk SR12 via WhatsApp distributor resmi.</p>
                    <a href="#" class="mt-3 inline-flex items-center justify-center w-full py-2 text-xs font-semibold text-primary-600 border border-primary-300 rounded-lg hover:bg-primary-100 transition-colors">Hubungi Admin</a>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-serif font-bold text-neutral-900">Katalog Produk</h1>
                    <p class="text-sm text-neutral-500 mt-1">Menampilkan 1-{{ count($allProducts) }} dari 48 produk kecantikan terbaik.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative flex-1 sm:w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" placeholder="Cari produk SR12..." class="input-field !pl-10 !py-2.5 text-sm">
                    </div>
                    <button class="flex items-center gap-2 px-4 py-2.5 border border-neutral-300 rounded-xl text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/></svg>
                        Urutkan
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5">
                @foreach($allProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex items-center justify-center gap-2">
                <button class="w-9 h-9 rounded-lg border border-neutral-200 flex items-center justify-center text-neutral-400 hover:border-neutral-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </button>
                <button class="w-9 h-9 rounded-lg bg-primary-600 text-white text-sm font-semibold">1</button>
                <button class="w-9 h-9 rounded-lg border border-neutral-200 text-neutral-600 text-sm font-medium hover:bg-neutral-50 transition-colors">2</button>
                <button class="w-9 h-9 rounded-lg border border-neutral-200 text-neutral-600 text-sm font-medium hover:bg-neutral-50 transition-colors">3</button>
                <span class="text-neutral-400 text-sm">...</span>
                <button class="w-9 h-9 rounded-lg border border-neutral-200 text-neutral-600 text-sm font-medium hover:bg-neutral-50 transition-colors">8</button>
                <button class="w-9 h-9 rounded-lg border border-neutral-200 flex items-center justify-center text-neutral-600 hover:border-neutral-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
