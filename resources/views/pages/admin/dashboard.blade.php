@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

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

    $trendSeries = collect($trendValues)->map(fn ($value) => (int) $value)->values();
    $trendCount = max($trendSeries->count(), 1);
    $chartWidth = 560;
    $chartHeight = 200;
    $maxTrend = max((int) ($trendSeries->max() ?? 0), 1);
    $xStep = $trendCount > 1 ? $chartWidth / ($trendCount - 1) : 0;

    $chartPoints = $trendSeries->map(function (int $value, int $index) use ($trendCount, $xStep, $chartWidth, $chartHeight, $maxTrend): array {
        $x = $trendCount > 1 ? $index * $xStep : $chartWidth / 2;
        $y = $chartHeight - (($value / $maxTrend) * ($chartHeight - 12)) - 6;

        return [
            'x' => round($x, 2),
            'y' => round($y, 2),
            'value' => $value,
        ];
    });

    $linePath = $chartPoints
        ->map(fn (array $point, int $index) => ($index === 0 ? 'M' : 'L').$point['x'].','.$point['y'])
        ->implode(' ');
    $areaPath = $linePath !== ''
        ? $linePath.' L '.$chartWidth.','.$chartHeight.' L 0,'.$chartHeight.' Z'
        : '';

    $yAxisTicks = [
        number_format($maxTrend, 0, ',', '.'),
        number_format((int) round($maxTrend * 0.67), 0, ',', '.'),
        number_format((int) round($maxTrend * 0.33), 0, ',', '.'),
        '0',
    ];

    $labelIndexes = collect([0, (int) floor(($trendCount - 1) * 0.25), (int) floor(($trendCount - 1) * 0.5), (int) floor(($trendCount - 1) * 0.75), $trendCount - 1])
        ->filter(fn (int $i) => $i >= 0)
        ->unique()
        ->values();

    $xLabels = $labelIndexes->map(function (int $i) use ($trendLabels): string {
        return $trendLabels[$i] ?? '';
    })->all();
@endphp

<div class="space-y-6">
    <form method="GET" action="/admin" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="text-xs font-semibold text-neutral-600">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $filters['from'] }}" class="input-field mt-1">
            </div>
            <div>
                <label class="text-xs font-semibold text-neutral-600">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $filters['to'] }}" class="input-field mt-1">
            </div>
            <div>
                <label class="text-xs font-semibold text-neutral-600">Metode Bayar</label>
                <select name="payment" class="input-field mt-1">
                    <option value="">Semua</option>
                    <option value="transfer" {{ $filters['payment'] === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="ewallet" {{ $filters['payment'] === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="cod" {{ $filters['payment'] === 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="pay_at_store" {{ $filters['payment'] === 'pay_at_store' ? 'selected' : '' }}>Bayar di Toko</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-neutral-600">Status Pesanan</label>
                <select name="status" class="input-field mt-1">
                    <option value="">Semua</option>
                    @foreach($statusLabels as $status => $label)
                        <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm w-full">Terapkan</button>
                <a href="/admin" class="btn-outline text-sm w-full">Reset</a>
            </div>
        </div>
    </form>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="card p-5">
            <p class="text-xs text-neutral-500">User Aktif</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($activeUsers) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-neutral-500">Total Pesanan (Filter)</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($orderCount) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-neutral-500">Pendapatan (Filter)</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-neutral-500">AOV (Filter)</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">Rp {{ number_format($aov, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-neutral-800 font-sans">Grafik Trend Penjualan</h3>
            <span class="text-xs text-neutral-500">Sesuai filter dashboard</span>
        </div>

        @if($chartPoints->isEmpty())
            <p class="text-sm text-neutral-500">Belum ada data penjualan pada rentang ini.</p>
        @else
            <div class="relative">
                <div class="flex">
                    <div class="flex flex-col justify-between text-[11px] text-neutral-400 w-14 shrink-0 pr-2 pb-6" style="height: 220px;">
                        @foreach($yAxisTicks as $tick)
                            <span>Rp {{ $tick }}</span>
                        @endforeach
                    </div>

                    <div class="flex-1 relative" style="height: 220px;">
                        <svg class="w-full h-full" viewBox="0 0 560 200" preserveAspectRatio="none" fill="none">
                            <line x1="0" y1="0" x2="560" y2="0" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="67" x2="560" y2="67" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="133" x2="560" y2="133" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="200" x2="560" y2="200" stroke="#f3f4f6" stroke-width="0.5"/>

                            @if($areaPath !== '')
                                <path d="{{ $areaPath }}" fill="url(#dashAreaFill)" />
                                <path d="{{ $linePath }}" stroke="url(#dashLineFill)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif

                            <defs>
                                <linearGradient id="dashLineFill" x1="0" y1="0" x2="560" y2="0">
                                    <stop stop-color="#f9a8d4"/>
                                    <stop offset="1" stop-color="#db2777"/>
                                </linearGradient>
                                <linearGradient id="dashAreaFill" x1="280" y1="0" x2="280" y2="200">
                                    <stop stop-color="#fbcfe8" stop-opacity="0.5"/>
                                    <stop offset="1" stop-color="#fdf2f8" stop-opacity="0.05"/>
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0">
                            @foreach($chartPoints as $index => $point)
                                <div class="absolute" style="left: {{ ($point['x'] / 560) * 100 }}%; top: {{ ($point['y'] / 200) * 100 }}%; transform: translate(-50%, -50%);">
                                    <div class="relative group">
                                        <div class="w-3 h-3 rounded-full bg-white border-2 border-primary-500 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer shadow-md"></div>
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-800 text-white text-[11px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl z-10">
                                            <p class="font-semibold">Rp {{ number_format($point['value'], 0, ',', '.') }}</p>
                                            <p class="text-neutral-400 text-[10px]">{{ $trendLabels[$index] ?? '' }}</p>
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-neutral-800"></div>
                                        </div>
                                        <div class="absolute inset-0 -m-3"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex ml-14">
                    <div class="flex-1 flex justify-between text-[11px] text-neutral-400 mt-1.5">
                        @foreach($xLabels as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="card p-5">
            <h3 class="text-sm font-bold text-neutral-800 font-sans">Komposisi Role</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-neutral-600">Admin</span>
                    <span class="font-semibold text-neutral-900">{{ $roleCounts['admin'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-600">Owner</span>
                    <span class="font-semibold text-neutral-900">{{ $roleCounts['owner'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-neutral-600">Customer</span>
                    <span class="font-semibold text-neutral-900">{{ $roleCounts['customer'] }}</span>
                </div>
            </div>
        </div>

        <div class="card p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-neutral-800 font-sans">Pesanan Terbaru (Sesuai Filter)</h3>
                <a href="/admin/pesanan" class="text-xs font-semibold text-primary-600">Kelola Pesanan</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200">
                            <th class="text-left py-2 text-xs text-neutral-500">Kode</th>
                            <th class="text-left py-2 text-xs text-neutral-500">Pelanggan</th>
                            <th class="text-left py-2 text-xs text-neutral-500">Total</th>
                            <th class="text-left py-2 text-xs text-neutral-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="py-2 text-xs font-mono text-neutral-700">{{ $order->order_code }}</td>
                                <td class="py-2 text-neutral-700">{{ $order->recipient_name }}</td>
                                <td class="py-2 text-neutral-700">Rp {{ number_format((int) $order->total, 0, ',', '.') }}</td>
                                <td class="py-2">
                                    <span class="badge bg-primary-50 text-primary-700">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-xs text-neutral-500">Tidak ada data pesanan pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-neutral-800 font-sans">Stok Menipis</h3>
            <a href="/admin/produk?status=low_stock" class="text-xs font-semibold text-primary-600">Lihat Produk</a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($lowStockProducts as $item)
                <div class="p-3 rounded-lg border border-neutral-200 bg-white">
                    <p class="text-sm font-semibold text-neutral-800">{{ $item->name }}</p>
                    <p class="text-xs text-neutral-500 mt-1">Stok: {{ $item->stock }}</p>
                </div>
            @empty
                <p class="text-sm text-neutral-500">Semua stok aman.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
