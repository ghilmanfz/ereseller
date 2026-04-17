@extends('layouts.app')
@section('title', 'SR12 Exclusive Compact Powder - Sheer Pink')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-neutral-400 mb-6">
        <a href="/" class="hover:text-primary-600 transition-colors">Beranda</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <a href="/katalog" class="hover:text-primary-600 transition-colors">Katalog</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-neutral-700 font-medium">SR12 Exclusive Compact Powder</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-10 mb-16">
        {{-- Image Gallery --}}
        <div x-data="{ activeImg: 0 }">
            <div class="aspect-square bg-neutral-100 rounded-2xl overflow-hidden mb-4">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&h=600&fit=crop" alt="Product" class="w-full h-full object-cover">
            </div>
            <div class="flex gap-3">
                @for($i = 0; $i < 3; $i++)
                <button @click="activeImg = {{ $i }}" :class="activeImg === {{ $i }} ? 'ring-2 ring-primary-500' : 'ring-1 ring-neutral-200'" class="w-20 h-20 rounded-xl overflow-hidden transition-all">
                    <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                    </div>
                </button>
                @endfor
            </div>
            {{-- Trust Badges --}}
            <div class="mt-6 grid grid-cols-3 gap-3">
                @foreach([['100% ORIGINAL', 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'], ['BPOM RI VERIFIED', 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'], ['AMAN DIKIRIM', 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12']] as $badge)
                <div class="flex flex-col items-center gap-1.5 py-3">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge[1] }}"/></svg>
                    <span class="text-[10px] font-bold text-neutral-600 uppercase tracking-wider">{{ $badge[0] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Product Info --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="badge-primary text-xs">Kosmetik</span>
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    <span class="text-sm font-semibold text-neutral-700">4.8</span>
                    <span class="text-xs text-neutral-400">(125 ulasan)</span>
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl font-serif font-bold text-neutral-900 leading-tight">SR12 Exclusive Compact Powder – Sheer Pink</h1>

            <div class="mt-4 flex items-center gap-3">
                <span class="text-2xl font-bold text-primary-600">Rp 85.000</span>
                <span class="text-base text-neutral-400 line-through">Rp 95.000</span>
            </div>

            <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-neutral-50 rounded-lg text-sm text-neutral-600">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Stok Tersedia: 24 unit
            </div>

            <p class="mt-5 text-sm text-neutral-600 leading-relaxed">
                Bedak padat eksklusif dengan formula lembut yang membantu menyamarkan noda hitam dan garis halus di wajah. Memberikan hasil akhir yang matte dan natural, cocok untuk penggunaan sehari-hari maupun acara formal.
            </p>

            {{-- Accordions --}}
            <div class="mt-6 divide-y divide-neutral-200">
                @foreach([
                    ['title' => 'Manfaat Produk', 'items' => ['Melindungi kulit dari paparan sinar UV', 'Menyamarkan pori-pori dan noda hitam', 'Tekstur ringan dan tidak menyumbat pori', 'Hasil akhir matte tahan hingga 8 jam', 'Mengandung Vitamin E untuk menjaga kelembapan kulit']],
                    ['title' => 'Cara Penggunaan', 'items' => ['Bersihkan wajah terlebih dahulu', 'Gunakan spons/puff, ambil bedak secukupnya', 'Tepuk-tepuk ringan pada wajah', 'Ratakan ke seluruh wajah']],
                    ['title' => 'Komposisi Lengkap', 'items' => ['Talc, Mica, Titanium Dioxide, Vitamin E, Aloe Vera Extract, Chamomile Extract']],
                ] as $idx => $accordion)
                <div x-data="{ open: {{ $idx === 0 ? 'true' : 'false' }} }" class="py-4">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-semibold text-neutral-800">
                        {{ $accordion['title'] }}
                        <svg class="w-4 h-4 text-neutral-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-3 space-y-2">
                        @foreach($accordion['items'] as $item)
                        <div class="flex items-start gap-2 text-sm text-neutral-600">
                            <svg class="w-4 h-4 text-primary-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $item }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Add to Cart --}}
            <div class="mt-6 card p-5" x-data="{ qty: 1, price: 85000 }">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-semibold text-neutral-700">Pilih Jumlah</p>
                        <div class="flex items-center gap-3 mt-2">
                            <button @click="qty = Math.max(1, qty - 1)" class="w-9 h-9 rounded-lg border border-neutral-300 flex items-center justify-center text-neutral-600 hover:bg-neutral-50 transition-colors">−</button>
                            <span class="text-base font-semibold w-8 text-center" x-text="qty"></span>
                            <button @click="qty++" class="w-9 h-9 rounded-lg border border-neutral-300 flex items-center justify-center text-neutral-600 hover:bg-neutral-50 transition-colors">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-neutral-400 uppercase font-semibold">Subtotal</p>
                        <p class="text-xl font-bold text-neutral-800">Rp <span x-text="(qty * price).toLocaleString('id-ID')">85.000</span></p>
                    </div>
                </div>
                <button class="btn-primary w-full !py-3.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    Tambah ke Keranjang
                </button>
                <p class="text-xs text-neutral-400 text-center mt-2">*Admin akan memverifikasi pesanan Anda secara manual setelah pembayaran.</p>
            </div>

            {{-- WhatsApp Help --}}
            <div class="mt-4 card p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-neutral-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                <p class="text-sm text-neutral-600">Butuh bantuan atau konsultasi produk? <a href="#" class="text-primary-600 font-semibold hover:text-primary-700">Hubungi Admin via WhatsApp.</a></p>
            </div>
        </div>
    </div>

    {{-- Recommendations --}}
    <section class="border-t border-neutral-200 pt-12 pb-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-serif font-bold text-neutral-900">Rekomendasi Untuk Anda</h2>
            <a href="/katalog" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua Katalog</a>
        </div>
        @php
        $recs = [
            ['name' => 'Facial Wash Honey', 'category' => 'Skincare', 'price' => 45000, 'rating' => 4.7, 'stock' => 20, 'stock_status' => 'In-Stock', 'slug' => 'facial-wash-honey', 'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=500&fit=crop', 'description' => ''],
            ['name' => 'Sunscreen Pink', 'category' => 'Skincare', 'price' => 65000, 'rating' => 4.8, 'stock' => 12, 'stock_status' => 'In-Stock', 'slug' => 'sunscreen-pink', 'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=400&h=500&fit=crop', 'description' => ''],
            ['name' => 'Lip Care Cherry', 'category' => 'Cosmetic', 'price' => 25000, 'rating' => 4.5, 'stock' => 30, 'stock_status' => 'In-Stock', 'slug' => 'lip-care-cherry', 'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=500&fit=crop', 'description' => ''],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
            @foreach($recs as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
</div>
@endsection
