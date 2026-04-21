@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('content')
@php
    $statusLabels = [
        'pending_payment' => 'Menunggu Pembayaran',
        'payment_submitted' => 'Menunggu Verifikasi Admin',
        'awaiting_shipment_cod' => 'Menunggu Pengiriman (COD)',
        'ready_for_pickup' => 'Siap Diambil',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $isPickup = $order->shipping_method === 'pickup';

    if ($isPickup) {
        $timelineBase = [
            ['rank' => 1, 'key' => 'order_created', 'title' => 'Pesanan Dibuat', 'desc' => 'Pesanan pickup sudah masuk ke sistem.'],
            ['rank' => 2, 'key' => 'pending_payment', 'title' => 'Menunggu Pembayaran', 'desc' => 'Lakukan pembayaran online atau pilih bayar di toko saat ambil.'],
            ['rank' => 3, 'key' => 'ready_for_pickup', 'title' => 'Siap Diambil', 'desc' => 'Pesanan sudah disiapkan dan siap diambil di toko.'],
            ['rank' => 4, 'key' => 'completed', 'title' => 'Selesai', 'desc' => 'Pesanan sudah diambil oleh pelanggan.'],
        ];
    } else {
        $timelineBase = [
            ['rank' => 1, 'key' => 'order_created', 'title' => 'Pesanan Dibuat', 'desc' => 'Pesanan berhasil dibuat dan tercatat.'],
            ['rank' => 2, 'key' => 'pending_payment', 'title' => 'Menunggu Pembayaran', 'desc' => 'Khusus transfer/e-wallet: menunggu konfirmasi pembayaran.'],
            ['rank' => 3, 'key' => 'processing', 'title' => 'Pesanan Diproses', 'desc' => 'Tim gudang menyiapkan pesanan Anda.'],
            ['rank' => 4, 'key' => 'shipped', 'title' => 'Pesanan Dikirim', 'desc' => 'Pesanan sudah dikirim ke alamat tujuan.'],
            ['rank' => 5, 'key' => 'completed', 'title' => 'Selesai', 'desc' => 'Pesanan diterima pelanggan.'],
        ];
    }

    if ($isPickup) {
        $currentStatusRank = match ($order->status) {
            'pending_payment' => 2,
            'ready_for_pickup' => 3,
            'completed' => 4,
            default => 1,
        };
    } else {
        if ($order->status === 'awaiting_shipment_cod') {
            $currentStatusRank = 3;
        } else {
            $currentStatusRank = match ($order->status) {
                'pending_payment' => 2,
                'payment_submitted' => 2,
                'processing' => 3,
                'shipped' => 4,
                'completed' => 5,
                default => 1,
            };
        }
    }

    $stepTimes = [
        'order_created' => $order->created_at,
        'pending_payment' => $order->created_at,
        'processing' => $order->processing_at ?? $order->paid_at,
        'shipped' => $order->shipped_at,
        'ready_for_pickup' => $order->ready_for_pickup_at,
        'completed' => $order->completed_at,
    ];
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif font-bold text-neutral-900">Detail Pelacakan</h1>
            <div class="flex items-center gap-3 mt-1">
                <p class="text-sm text-neutral-500">ID Pesanan: <span class="font-semibold text-neutral-800">{{ $order->order_code }}</span></p>
                <span class="badge bg-amber-100 text-amber-700 text-xs">{{ $statusLabels[$order->status] ?? 'Diproses' }}</span>
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

                    <div class="space-y-6">
                        @foreach($timelineBase as $idx => $step)
                        @php
                            $stepRank = $step['rank'];
                            $done = $stepRank < $currentStatusRank;
                            $isCurrent = $stepRank === $currentStatusRank;
                            $time = $stepTimes[$step['key']] ?? null;
                        @endphp
                        <div class="relative flex gap-4">
                            <div class="shrink-0 z-10">
                                @if($done)
                                <div class="w-4 h-4 rounded-full bg-primary-600 border-2 border-white shadow-sm flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                @elseif($isCurrent)
                                <div class="w-4 h-4 rounded-full bg-white border-2 border-primary-500 shadow-sm animate-pulse-soft"></div>
                                @else
                                <div class="w-4 h-4 rounded-full bg-white border-2 border-neutral-300"></div>
                                @endif
                            </div>
                            <div class="pb-1 -mt-0.5">
                                <p class="text-sm font-semibold {{ ! $done && ! $isCurrent ? 'text-neutral-400' : ($isCurrent ? 'text-primary-600' : 'text-primary-700') }}">{{ $step['title'] }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5 leading-relaxed">{{ $step['desc'] }}</p>
                                <p class="text-[11px] text-neutral-400 mt-1">{{ $time ? $time->format('d M Y, H:i') : '-' }}</p>
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
                    @if($isPickup)
                        <p class="text-xs text-neutral-500">Metode: Ambil di toko</p>
                        <div class="mt-2 px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-neutral-700">Lokasi: Toko SR12 Parungpanjang</div>
                    @else
                        <p class="text-xs text-neutral-500">Metode: Kirim ke alamat</p>
                        <div class="mt-2 flex items-center gap-2">
                            <div class="px-3 py-1.5 bg-white rounded-lg text-xs font-mono text-neutral-700">Resi: {{ $order->tracking_number ?? '-' }}</div>
                        </div>
                    @endif
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
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-neutral-100 rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-neutral-800">{{ $item->product_name }}</p>
                                <p class="text-xs text-neutral-400">{{ $item->quantity }} x Rp {{ number_format((int) $item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-primary-600">Rp {{ number_format((int) $item->line_total, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-neutral-200 space-y-2 text-sm">
                    <div class="flex justify-between text-neutral-500"><span>Subtotal Produk</span><span>Rp {{ number_format((int) $order->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-neutral-500"><span>Biaya Pengiriman</span><span>Rp {{ number_format((int) $order->shipping_cost, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between font-bold text-primary-600 pt-2 border-t border-neutral-200"><span>Total Pesanan</span><span>Rp {{ number_format((int) $order->total, 0, ',', '.') }}</span></div>
                </div>
            </div>

            {{-- Payment & Address --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="card p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        <p class="text-xs font-bold text-neutral-700 uppercase">Pembayaran</p>
                    </div>
                    <p class="text-sm font-semibold text-neutral-800">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
                    <p class="text-xs text-neutral-500 mt-1">Status pembayaran: {{ $statusLabels[$order->status] ?? $order->status }}.</p>
                </div>
                <div class="card p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <p class="text-xs font-bold text-neutral-700 uppercase">Alamat Tujuan</p>
                    </div>
                    <p class="text-sm font-semibold text-neutral-800">{{ $order->recipient_name }}</p>
                    <p class="text-xs text-neutral-500 mt-1">{{ $order->recipient_whatsapp }}<br>{{ $order->recipient_address }}</p>
                </div>
            </div>

            @if(in_array($order->status, ['shipped', 'ready_for_pickup'], true))
            <form method="POST" action="/pesanan/{{ $order->order_code }}/selesai" class="card p-4">
                @csrf
                <button type="submit" class="btn-primary w-full">Konfirmasi Barang Sudah Diterima</button>
            </form>
            @endif

            {{-- Help --}}
            <div class="card p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-neutral-800">Butuh bantuan pesanan ini?</p>
                        <p class="text-xs text-neutral-500">Hubungi Admin Sintia SR12 via WhatsApp</p>
                    </div>
                </div>
                <a href="{{ $storeWhatsappLink }}" target="_blank" rel="noopener" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                    Hubungi Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
