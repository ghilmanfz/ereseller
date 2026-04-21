@extends('layouts.app')
@section('title', 'Pesanan Saya')

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
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-serif font-bold text-neutral-900">Riwayat Pesanan</h1>
        <p class="text-sm text-neutral-500 mt-1">Semua pesanan Anda ditampilkan di sini.</p>
    </div>

    <div class="card overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Kode Pesanan</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Tanggal</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Jumlah Item</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Total</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-neutral-50">
                                <td class="py-3 px-4 font-mono text-xs text-neutral-700">{{ $order->order_code }}</td>
                                <td class="py-3 px-4 text-neutral-600">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-4 text-neutral-600">{{ $order->items_count }} item</td>
                                <td class="py-3 px-4 font-semibold text-neutral-800">Rp {{ number_format((int) $order->total, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="badge bg-primary-50 text-primary-700">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="/pesanan/{{ $order->order_code }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-neutral-100">
                {{ $orders->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-sm text-neutral-500">Belum ada riwayat pesanan.</p>
                <a href="/katalog" class="inline-flex mt-4 btn-primary text-sm !py-2.5 !px-5">Mulai Belanja</a>
            </div>
        @endif
    </div>
</div>
@endsection
