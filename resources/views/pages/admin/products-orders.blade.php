@extends('layouts.admin')
@section('title', 'Produk dan Pesanan')
@section('page_title', 'Produk dan Pesanan')

@section('content')
@php
    $statusLabels = [
        'pending_payment' => 'Menunggu Pembayaran',
        'payment_submitted' => 'Menunggu Verifikasi',
        'awaiting_shipment_cod' => 'Menunggu Pengiriman (COD)',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'ready_for_pickup' => 'Siap Diambil',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $paymentLabels = [
        'transfer' => 'Transfer / VA',
        'ewallet' => 'E-Wallet',
        'cod' => 'COD',
        'pay_at_store' => 'Bayar di Toko',
    ];
@endphp

<div class="space-y-6">
    <div class="card p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <div>
                <h2 class="text-base font-bold text-neutral-800 font-sans">Kelola Pesanan Masuk</h2>
                <p class="text-xs text-neutral-500 mt-0.5">Pantau, verifikasi pembayaran, dan reminder pickup pelanggan secara real-time.</p>
            </div>
            <form method="GET" action="/admin/produk-pesanan" class="flex items-center gap-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="q" value="{{ $currentQuery ?? '' }}" placeholder="Cari ID Pesanan..." class="pl-9 pr-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-44">
                </div>
                <select name="payment" class="px-3 py-2 bg-neutral-50 border border-neutral-200 rounded-lg text-sm">
                    <option value="">Semua Bayar</option>
                    <option value="transfer" {{ ($currentPayment ?? '') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="ewallet" {{ ($currentPayment ?? '') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="cod" {{ ($currentPayment ?? '') === 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="pay_at_store" {{ ($currentPayment ?? '') === 'pay_at_store' ? 'selected' : '' }}>Bayar di Toko</option>
                </select>
                <button type="submit" class="flex items-center gap-1.5 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">ID Pesanan</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Pelanggan</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Tanggal</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Metode Bayar</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Bukti Bayar</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Pengiriman</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="py-3 px-2">
                                <p class="font-mono text-xs text-neutral-700">{{ $order->order_code }}</p>
                                <p class="text-[11px] text-neutral-400">Rp {{ number_format((int) $order->total, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-medium text-neutral-800">{{ $order->recipient_name }}</p>
                                <p class="text-xs text-neutral-500">{{ $order->recipient_whatsapp }}</p>
                            </td>
                            <td class="py-3 px-2 text-neutral-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3 px-2">
                                <span class="badge bg-neutral-100 text-neutral-700">{{ $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method) }}</span>
                            </td>
                            <td class="py-3 px-2">
                                @if($order->payment_proof_path)
                                    <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" class="inline-flex items-center gap-2">
                                        <img src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti {{ $order->order_code }}" class="w-10 h-10 rounded-lg object-cover border border-neutral-200">
                                        <span class="text-xs font-semibold text-primary-600">Lihat</span>
                                    </a>
                                @elseif(in_array($order->payment_method, ['cod', 'pay_at_store'], true))
                                    <span class="text-xs text-neutral-500">Tidak perlu</span>
                                @else
                                    <span class="text-xs text-amber-600">Belum upload</span>
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-xs font-medium text-neutral-700">{{ $order->shipping_method === 'pickup' ? 'Pickup' : 'Delivery' }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold
                                    {{ $order->status === 'payment_submitted' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status === 'awaiting_shipment_cod' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status === 'ready_for_pickup' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-sky-100 text-sky-700' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ in_array($order->status, ['pending_payment', 'shipped', 'cancelled'], true) ? 'bg-neutral-100 text-neutral-700' : '' }}
                                ">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex flex-col gap-2">
                                    @if(in_array($order->payment_method, ['transfer', 'ewallet'], true) && $order->status === 'payment_submitted')
                                        <form method="POST" action="/admin/pesanan/{{ $order->id }}/verifikasi-pembayaran">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700">Verifikasi Pembayaran</button>
                                        </form>
                                    @endif

                                    @if($order->shipping_method === 'pickup' && $order->status === 'ready_for_pickup')
                                        <form method="POST" action="/admin/pesanan/{{ $order->id }}/reminder-pickup">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold border border-primary-300 text-primary-700 rounded-lg hover:bg-primary-50">Kirim Reminder Pickup</button>
                                        </form>
                                        @if($order->pickup_ready_reminded_at)
                                            <span class="text-[11px] text-neutral-500">Terakhir reminder: {{ $order->pickup_ready_reminded_at->format('d M Y H:i') }}</span>
                                        @endif
                                    @endif

                                    @if($order->payment_method === 'cod')
                                        <span class="text-[11px] text-blue-600 font-semibold">Masuk antrian COD</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-sm text-neutral-500">Belum ada data pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
