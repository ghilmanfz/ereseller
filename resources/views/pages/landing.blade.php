@extends('layouts.app')
@section('title', 'Beranda')
@section('meta_description', 'SINTIA SR12 Distributor Resmi - Skincare herbal terpercaya di Parungpanjang.')

@section('content')

{{-- HERO SECTION --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-white to-primary-50/30">
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-primary-300/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-100/80 backdrop-blur-sm rounded-full mb-6">
                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    <span class="text-xs font-semibold text-primary-700">Distributor Resmi SR12 Herbal Skincare</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-black text-neutral-900 leading-[1.1]">
                    Cantik Alami<br>dengan <span class="text-primary-600">Sentuhan<br>Herbal</span> Terbaik
                </h1>
                <p class="mt-6 text-base sm:text-lg text-neutral-500 leading-relaxed max-w-lg">
                    Temukan rahasia kulit sehat dan bercahaya dengan rangkaian produk SR12 yang telah teruji secara dermatologis dan bersertifikat BPOM.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="/katalog" class="btn-primary text-sm">Mulai Belanja Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="/katalog" class="btn-outline text-sm">Lihat Katalog</a>
                </div>
                <div class="mt-10 flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-300 to-primary-500 border-2 border-white flex items-center justify-center text-white text-[10px] font-bold">SA</div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 border-2 border-white flex items-center justify-center text-white text-[10px] font-bold">BR</div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-300 to-blue-500 border-2 border-white flex items-center justify-center text-white text-[10px] font-bold">CL</div>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-green-300 to-green-500 border-2 border-white flex items-center justify-center text-white text-[10px] font-bold">DW</div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-neutral-800">1,200+ Pelanggan Puas</p>
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-primary-900/10">
                    <div class="aspect-[4/5] bg-gradient-to-br from-primary-100 to-primary-200">
                        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&h=750&fit=crop" alt="SR12 Skincare" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="absolute -bottom-4 left-6 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3 animate-float">
                    <div class="w-11 h-11 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-neutral-800">100% Herbal</p>
                        <p class="text-xs text-neutral-400">BPOM Approved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TRUST INDICATORS --}}
<section class="py-12 border-b border-neutral-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-12 md:gap-20">
            @foreach([
                ['icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'label' => 'KEAMANAN TERJAMIN', 'color' => 'primary'],
                ['icon' => 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z', 'label' => 'BAHAN ALAMI', 'color' => 'green'],
                ['icon' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z', 'label' => 'HASIL NYATA', 'color' => 'purple'],
            ] as $trust)
            <div class="flex items-center gap-3 text-neutral-600">
                <div class="w-10 h-10 rounded-xl bg-{{ $trust['color'] }}-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $trust['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['icon'] }}"/></svg>
                </div>
                <span class="text-sm font-semibold">{{ $trust['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK TERLARIS --}}
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <span class="badge-primary text-xs mb-3 inline-block">Produk Terlaris</span>
                <h2 class="section-title">Pilihan Terbaik Untuk Kulitmu</h2>
                <p class="section-subtitle mt-2">Koleksi produk yang paling dicintai oleh pelanggan kami untuk hasil yang maksimal.</p>
            </div>
            <a href="/katalog" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1 shrink-0">
                Lihat Katalog Lengkap <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        @php
        $products = [
            ['name' => 'Acne Care Facial Wash', 'category' => 'Skincare', 'price' => 65000, 'rating' => 4.8, 'stock' => 24, 'stock_status' => 'In-Stock', 'slug' => 'acne-care-facial-wash', 'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=500&fit=crop', 'description' => 'Pembersih wajah untuk kulit berjerawat'],
            ['name' => 'Sunblock Cream SR12', 'category' => 'Skincare', 'price' => 85000, 'rating' => 4.9, 'stock' => 15, 'stock_status' => 'Limited', 'slug' => 'sunblock-cream-sr12', 'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=400&h=500&fit=crop', 'description' => 'Perlindungan UV dengan bahan alami'],
            ['name' => 'Deodorant Spray Premium', 'category' => 'Bodycare', 'price' => 40000, 'rating' => 4.6, 'stock' => 50, 'stock_status' => 'In-Stock', 'slug' => 'deodorant-spray-premium', 'image' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop', 'description' => 'Menghilangkan bau badan 24 jam'],
            ['name' => 'Kopi Radix SR12', 'category' => 'Health', 'price' => 120000, 'rating' => 4.8, 'stock' => 30, 'stock_status' => 'In-Stock', 'slug' => 'kopi-radix-sr12', 'image' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=500&fit=crop', 'description' => 'Kopi herbal untuk stamina'],
        ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

{{-- CARA MUDAH BELANJA --}}
<section class="py-16 md:py-24 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="section-title">Cara Mudah Belanja Di Sini</h2>
        <p class="section-subtitle mx-auto mt-3">Proses pemesanan yang simpel dan cepat untuk kenyamanan maksimal Anda.</p>
        <div class="mt-14 grid md:grid-cols-3 gap-8">
            @foreach([
                ['title' => 'Pilih Produk', 'desc' => 'Cari dan pilih produk SR12 favorit Anda dari katalog lengkap kami yang selalu terupdate.', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['title' => 'Konfirmasi & Bayar', 'desc' => 'Lakukan pembayaran via Transfer atau COD, lalu konfirmasi pesanan dalam hitungan detik.', 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z'],
                ['title' => 'Terima Pesanan', 'desc' => 'Duduk santai, pesanan Anda akan segera diproses dan dikirim atau siap diambil di toko.', 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12'],
            ] as $step)
            <div class="group">
                <div class="w-16 h-16 mx-auto bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary-600 transition-colors duration-300">
                    <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/></svg>
                </div>
                <h3 class="text-lg font-bold text-neutral-800 font-sans">{{ $step['title'] }}</h3>
                <p class="mt-2 text-sm text-neutral-500 max-w-xs mx-auto">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA SECTION --}}
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 p-10 md:p-16 text-center">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white leading-tight">
                    Bergabunglah Dengan Ribuan Reseller &<br class="hidden sm:block"> Konsumen Loyal SR12 Parungpanjang
                </h2>
                <p class="mt-4 text-primary-200 max-w-xl mx-auto text-sm md:text-base">Dapatkan informasi promo eksklusif, tips kecantikan harian, dan penawaran khusus langsung di genggaman Anda.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="/register" class="inline-flex items-center gap-2 px-7 py-3 bg-white text-primary-700 font-semibold rounded-full hover:bg-primary-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-sm">Daftar Sekarang</a>
                    <a href="/katalog" class="inline-flex items-center gap-2 px-7 py-3 border-2 border-white/40 text-white font-semibold rounded-full hover:bg-white/10 transition-all text-sm">Pelajari Produk</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
