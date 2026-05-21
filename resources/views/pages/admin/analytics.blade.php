@extends('layouts.admin')
@section('title', 'Laporan Analisis')
@section('page_title', 'Laporan Analisis')

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
    $chartWidth = 600;
    $chartHeight = 240;
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
        number_format((int) round($maxTrend * 0.75), 0, ',', '.'),
        number_format((int) round($maxTrend * 0.5), 0, ',', '.'),
        number_format((int) round($maxTrend * 0.25), 0, ',', '.'),
        '0',
    ];

    $labelIndexes = collect([0, (int) floor(($trendCount - 1) * 0.2), (int) floor(($trendCount - 1) * 0.4), (int) floor(($trendCount - 1) * 0.6), (int) floor(($trendCount - 1) * 0.8), $trendCount - 1])
        ->filter(fn (int $i) => $i >= 0)
        ->unique()
        ->values();

    $xLabels = $labelIndexes->map(function (int $i) use ($trendLabels): string {
        return $trendLabels[$i] ?? '';
    })->all();
@endphp

<div class="space-y-6">
    <form method="GET" action="/admin/laporan" class="card p-4">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div>
                <label class="text-xs font-semibold text-neutral-600">Dari</label>
                <input type="date" name="from" value="{{ $filters['from'] }}" class="input-field mt-1">
            </div>
            <div>
                <label class="text-xs font-semibold text-neutral-600">Sampai</label>
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
                <label class="text-xs font-semibold text-neutral-600">Status</label>
                <select name="status" class="input-field mt-1">
                    <option value="">Semua</option>
                    @foreach($statusLabels as $status => $label)
                        <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-neutral-600">Cari</label>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Kode / nama / whatsapp" class="input-field mt-1">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary text-sm w-full">Filter</button>
                <a href="/admin/laporan" class="btn-outline text-sm w-full">Reset</a>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-neutral-200 flex items-center gap-2">
            <span class="text-xs text-neutral-500">Export:</span>
            <a href="{{ route('admin.analytics.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="px-3 py-1.5 text-xs font-semibold border border-neutral-300 rounded-lg hover:bg-neutral-50">CSV</a>
            <a href="{{ route('admin.analytics.export', array_merge(request()->query(), ['format' => 'excel'])) }}" class="px-3 py-1.5 text-xs font-semibold border border-neutral-300 rounded-lg hover:bg-neutral-50">Excel</a>
        </div>
    </form>

    <div class="grid md:grid-cols-3 gap-4">
        <div class="card p-5">
            <p class="text-xs text-neutral-500">Total Pendapatan</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-neutral-500">Jumlah Pesanan</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">{{ number_format($orders) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-neutral-500">Rata-rata Order (AOV)</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">Rp {{ number_format($aov, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="text-sm font-bold text-neutral-800 font-sans mb-4">Tren Penjualan Harian</h3>
        @if($chartPoints->isEmpty())
            <p class="text-sm text-neutral-500">Belum ada data tren untuk rentang tanggal ini.</p>
        @else
            <div class="relative">
                <div class="flex">
                    <div class="flex flex-col justify-between text-[11px] text-neutral-400 w-14 shrink-0 pr-2 pb-6" style="height: 260px;">
                        @foreach($yAxisTicks as $tick)
                            <span>Rp {{ $tick }}</span>
                        @endforeach
                    </div>

                    <div class="flex-1 relative" style="height: 260px;">
                        <svg class="w-full h-full" viewBox="0 0 600 240" preserveAspectRatio="none" fill="none">
                            <line x1="0" y1="0" x2="600" y2="0" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="60" x2="600" y2="60" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="120" x2="600" y2="120" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="180" x2="600" y2="180" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="240" x2="600" y2="240" stroke="#f3f4f6" stroke-width="0.5"/>

                            @if($areaPath !== '')
                                <path d="{{ $areaPath }}" fill="url(#analyticsAreaFill)" />
                                <path d="{{ $linePath }}" stroke="url(#analyticsLineFill)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif

                            <defs>
                                <linearGradient id="analyticsLineFill" x1="0" y1="0" x2="600" y2="0">
                                    <stop stop-color="#f9a8d4"/>
                                    <stop offset="1" stop-color="#db2777"/>
                                </linearGradient>
                                <linearGradient id="analyticsAreaFill" x1="300" y1="0" x2="300" y2="240">
                                    <stop stop-color="#fbcfe8" stop-opacity="0.5"/>
                                    <stop offset="1" stop-color="#fdf2f8" stop-opacity="0.05"/>
                                </linearGradient>
                            </defs>
                        </svg>

                        <div class="absolute inset-0">
                            @foreach($chartPoints as $index => $point)
                                <div class="absolute" style="left: {{ ($point['x'] / 600) * 100 }}%; top: {{ ($point['y'] / 240) * 100 }}%; transform: translate(-50%, -50%);">
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

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-neutral-800 font-sans">Produk Terlaris</h3>
                <a href="/admin/produk" class="text-xs font-semibold text-primary-600">Kelola Produk</a>
            </div>
            <div class="space-y-3">
                @forelse($topProducts as $item)
                    <a href="/admin/produk?q={{ urlencode($item->product_name) }}" class="block p-3 rounded-lg border border-neutral-200 hover:border-primary-300 hover:bg-primary-50/30 transition-colors">
                        <p class="text-sm font-semibold text-neutral-800">{{ $item->product_name }}</p>
                        <div class="mt-1 flex items-center justify-between text-xs text-neutral-500">
                            <span>{{ (int) $item->sold }} terjual</span>
                            <span>Rp {{ number_format((int) $item->revenue, 0, ',', '.') }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-neutral-500">Belum ada data produk terlaris pada filter ini.</p>
                @endforelse
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-neutral-800 font-sans">Produk Stok Menipis</h3>
                <a href="/admin/produk?status=low_stock" class="text-xs font-semibold text-primary-600">Restock</a>
            </div>
            <div class="space-y-3">
                @forelse($lowStockProducts as $product)
                    <a href="/admin/produk?q={{ urlencode($product->name) }}" class="block p-3 rounded-lg border border-neutral-200 hover:border-primary-300 hover:bg-primary-50/30 transition-colors">
                        <p class="text-sm font-semibold text-neutral-800">{{ $product->name }}</p>
                        <p class="text-xs text-neutral-500 mt-1">Sisa stok: {{ $product->stock }}</p>
                    </a>
                @empty
                    <p class="text-sm text-neutral-500">Tidak ada produk yang stoknya menipis.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="text-sm font-bold text-neutral-800 font-sans mb-4">Detail Transaksi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-2 text-xs text-neutral-500">Kode</th>
                        <th class="text-left py-2 text-xs text-neutral-500">Pelanggan</th>
                        <th class="text-left py-2 text-xs text-neutral-500">Tanggal</th>
                        <th class="text-left py-2 text-xs text-neutral-500">Pembayaran</th>
                        <th class="text-left py-2 text-xs text-neutral-500">Total</th>
                        <th class="text-left py-2 text-xs text-neutral-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($transactions as $trx)
                        <tr>
                            <td class="py-2 text-xs font-mono text-neutral-700">{{ $trx->order_code }}</td>
                            <td class="py-2 text-neutral-700">{{ $trx->recipient_name }}</td>
                            <td class="py-2 text-neutral-500">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td class="py-2 text-neutral-500">{{ strtoupper($trx->payment_method) }}</td>
                            <td class="py-2 text-neutral-700">Rp {{ number_format((int) $trx->total, 0, ',', '.') }}</td>
                            <td class="py-2"><span class="badge bg-primary-50 text-primary-700">{{ $statusLabels[$trx->status] ?? $trx->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-xs text-neutral-500">Tidak ada transaksi sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->count() > 0)
            <div class="mt-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
