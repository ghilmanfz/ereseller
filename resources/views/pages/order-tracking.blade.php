@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif font-bold text-neutral-900">Detail Pelacakan</h1>
            <div class="flex items-center gap-3 mt-1">
                <p class="text-sm text-neutral-500">ID Pesanan: <span class="font-semibold text-neutral-800">SR12-2024-0582</span></p>
                <span class="badge bg-amber-100 text-amber-700 text-xs">Sedang Dikemas</span>
            </div>
        </div>
        <a href="/katalog" class="btn-outline text-sm !py-2">Beli Lagi</a>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- Timeline --}}
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h2 class="text-sm font-bold text-neutral-800 font-sans flex items-center gap-2 mb-6">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    Progres Pengiriman
                </h2>

                <div class="relative ml-4">
                    {{-- Vertical Line --}}
                    <div class="absolute left-[7px] top-2 bottom-16 w-0.5 bg-gradient-to-b from-primary-500 via-primary-500 to-neutral-200"></div>

                    @php
                    $timeline = [
                        ['title' => 'Pesanan Diterima', 'desc' => 'Pesanan Anda telah masuk ke sistem dan menunggu verifikasi pembayaran.', 'time' => '12 OKT 2024, 09:15', 'done' => true],
                        ['title' => 'Pembayaran Terverifikasi', 'desc' => 'Pembayaran telah berhasil diverifikasi oleh admin SINTIA SR12.', 'time' => '12 OKT 2024, 10:30', 'done' => true],
                        ['title' => 'Pesanan Diproses', 'desc' => 'Tim gudang sedang menyiapkan produk-produk pilihan Anda.', 'time' => '12 OKT 2024, 14:00', 'done' => true],
                        ['title' => 'Sedang Dikemas', 'desc' => 'Produk sedang dalam proses pengepakan standar keamanan SR12.', 'time' => '12 OKT 2024, 16:45', 'done' => 'current'],
                        ['title' => 'Siap Dikirim', 'desc' => 'Pesanan siap diserahkan ke kurir J&T Express.', 'time' => '', 'done' => false],
                    ];
                    @endphp

                    <div class="space-y-6">
                        @foreach($timeline as $step)
                        <div class="relative flex gap-4">
                            <div class="shrink-0 z-10">
                                @if($step['done'] === true)
                                <div class="w-4 h-4 rounded-full bg-primary-600 border-2 border-white shadow-sm flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                @elseif($step['done'] === 'current')
                                <div class="w-4 h-4 rounded-full bg-white border-2 border-primary-500 shadow-sm animate-pulse-soft"></div>
                                @else
                                <div class="w-4 h-4 rounded-full bg-white border-2 border-neutral-300"></div>
                                @endif
                            </div>
                            <div class="pb-1 -mt-0.5">
                                <p class="text-sm font-semibold {{ $step['done'] === false ? 'text-neutral-400' : ($step['done'] === 'current' ? 'text-primary-600' : 'text-primary-700') }}">{{ $step['title'] }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5 leading-relaxed">{{ $step['desc'] }}</p>
                                @if($step['time'])
                                <p class="text-[11px] text-neutral-400 mt-1">{{ $step['time'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="mt-6 p-4 bg-primary-50 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        <p class="text-sm font-bold text-neutral-800">Info Pengiriman</p>
                    </div>
                    <p class="text-xs text-neutral-500">Dikirim via J&T Express</p>
                    <div class="mt-2 flex items-center gap-2">
                        <div class="px-3 py-1.5 bg-white rounded-lg text-xs font-mono text-neutral-700">Resi: JP3458920111</div>
                        <button class="text-xs font-semibold text-primary-600 hover:text-primary-700">Salin</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="card p-6">
                <h2 class="text-sm font-bold text-neutral-800 font-sans flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    Rincian Produk
                </h2>
                <div class="divide-y divide-neutral-100">
                    @foreach([
                        ['name' => 'SR12 Skin Tightening Cream', 'variant' => 'Pot 30gr', 'qty' => 1, 'price' => 155000],
                        ['name' => 'SR12 Herbal Facial Wash', 'variant' => 'Honey 100ml', 'qty' => 2, 'price' => 85000],
                        ['name' => 'SR12 Lip Care Cherry', 'variant' => 'Stick 5gr', 'qty' => 1, 'price' => 5000],
                    ] as $item)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-neutral-100 rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-neutral-800">{{ $item['name'] }}</p>
                                <p class="text-xs text-neutral-400">{{ $item['variant'] }} · {{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-primary-600">Rp {{ number_format($item['qty'] * $item['price'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-200 space-y-2 text-sm">
                    <div class="flex justify-between text-neutral-500"><span>Subtotal Produk</span><span>Rp 330.000</span></div>
                    <div class="flex justify-between text-neutral-500"><span>Biaya Pengiriman</span><span>Rp 15.000</span></div>
                    <div class="flex justify-between font-bold text-primary-600 pt-2 border-t border-neutral-200"><span>Total Pesanan</span><span>Rp 345.000</span></div>
                </div>
            </div>

            {{-- Payment & Address --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="card p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        <p class="text-xs font-bold text-neutral-700 uppercase">Pembayaran</p>
                    </div>
                    <p class="text-sm font-semibold text-neutral-800">Transfer Bank BCA</p>
                    <p class="text-xs text-neutral-500 mt-1">Pembayaran telah diterima dan dikonfirmasi pada 12 Oktober 2024.</p>
                </div>
                <div class="card p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <p class="text-xs font-bold text-neutral-700 uppercase">Alamat Tujuan</p>
                    </div>
                    <p class="text-sm font-semibold text-neutral-800">Siska Amelia</p>
                    <p class="text-xs text-neutral-500 mt-1">0812-3456-7890<br>Perumahan Green Park No. 42, Parungpanjang, Bogor, Jawa Barat</p>
                </div>
            </div>

            {{-- Help --}}
            <div class="card p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-neutral-800">Butuh bantuan pesanan ini?</p>
                        <p class="text-xs text-neutral-500">Hubungi Admin Sintia SR12 via WhatsApp</p>
                    </div>
                </div>
                <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                    Hubungi Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
