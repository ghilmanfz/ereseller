@extends('layouts.admin')
@section('title', 'Laporan Analisis')
@section('page_title', 'Laporan Analisis')

@section('content')
{{-- Filters --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <div class="flex items-center gap-2 px-4 py-2 bg-white border border-neutral-200 rounded-xl text-sm text-neutral-700">
        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        1 Mei 2024 - 31 Mei 2024
    </div>
    <button class="flex items-center gap-1.5 px-4 py-2 border border-neutral-200 rounded-xl text-sm font-medium text-neutral-700 hover:bg-neutral-50">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
        Filter Lanjutan
    </button>
    <div class="ml-auto flex items-center gap-2">
        <span class="text-xs text-neutral-500">Ekspor Laporan:</span>
        <button class="flex items-center gap-1 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            PDF
        </button>
        <button class="flex items-center gap-1 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 0v.75"/></svg>
            Excel
        </button>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Pendapatan', 'value' => 'Rp 48.250.000', 'change' => '+12.5%', 'positive' => true],
        ['label' => 'Jumlah Pesanan', 'value' => '1,248', 'change' => '+8.2%', 'positive' => true],
        ['label' => 'Rata-rata Order (AOV)', 'value' => 'Rp 38.600', 'change' => '-2.4%', 'positive' => false],
    ] as $stat)
    <div class="card p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
        </div>
        <div>
            <p class="text-xs text-neutral-500">{{ $stat['label'] }}</p>
            <p class="text-xl font-bold text-neutral-900 mt-1">{{ $stat['value'] }}</p>
            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $stat['positive'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $stat['change'] }}</span>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart --}}
<div class="card p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-neutral-800 font-sans">Tren Penjualan Bulanan</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Grafik pendapatan kotor berdasarkan konfirmasi pesanan</p>
        </div>
        <div class="flex items-center gap-1 bg-neutral-100 rounded-lg p-0.5">
            <button class="px-3 py-1.5 text-xs font-medium rounded-md text-neutral-600 hover:bg-white transition-all">Harian</button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-md text-neutral-600 hover:bg-white transition-all">Mingguan</button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-md bg-white shadow-sm text-neutral-800">Bulanan</button>
        </div>
    </div>

    {{-- Chart Container --}}
    <div class="relative">
        <div class="flex">
            {{-- Y-axis --}}
            <div class="flex flex-col justify-between text-[11px] text-neutral-400 w-12 shrink-0 pr-2 pb-6" style="height: 260px;">
                <span>Rp 12M</span>
                <span>Rp 9M</span>
                <span>Rp 6M</span>
                <span>Rp 3M</span>
                <span>Rp 0M</span>
            </div>

            {{-- SVG Chart --}}
            <div class="flex-1 relative" style="height: 260px;">
                <svg class="w-full h-full" viewBox="0 0 600 240" preserveAspectRatio="none" fill="none">
                    {{-- Horizontal grid lines --}}
                    <line x1="0" y1="0" x2="600" y2="0" stroke="#f3f4f6" stroke-width="0.5"/>
                    <line x1="0" y1="60" x2="600" y2="60" stroke="#f3f4f6" stroke-width="0.5"/>
                    <line x1="0" y1="120" x2="600" y2="120" stroke="#f3f4f6" stroke-width="0.5"/>
                    <line x1="0" y1="180" x2="600" y2="180" stroke="#f3f4f6" stroke-width="0.5"/>
                    <line x1="0" y1="240" x2="600" y2="240" stroke="#f3f4f6" stroke-width="0.5"/>

                    {{-- Area fill --}}
                    <path d="M0,235 L50,230 L100,225 L150,218 L200,210 L250,198 L300,180 L350,158 L400,132 L450,105 L500,80 L550,55 L600,35 L600,240 L0,240 Z"
                          fill="url(#analyticsAreaFill)" />

                    {{-- Line --}}
                    <path d="M0,235 L50,230 L100,225 L150,218 L200,210 L250,198 L300,180 L350,158 L400,132 L450,105 L500,80 L550,55 L600,35"
                          stroke="url(#analyticsLineFill)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

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

                {{-- Interactive data points --}}
                @php
                $aPoints = [
                    ['x' => 0, 'y' => 235, 'date' => '01 Mei', 'val' => 'Rp 500K'],
                    ['x' => 50, 'y' => 230, 'date' => '03 Mei', 'val' => 'Rp 800K'],
                    ['x' => 100, 'y' => 225, 'date' => '05 Mei', 'val' => 'Rp 1.2M'],
                    ['x' => 150, 'y' => 218, 'date' => '07 Mei', 'val' => 'Rp 1.8M'],
                    ['x' => 200, 'y' => 210, 'date' => '10 Mei', 'val' => 'Rp 2.5M'],
                    ['x' => 250, 'y' => 198, 'date' => '12 Mei', 'val' => 'Rp 3.5M'],
                    ['x' => 300, 'y' => 180, 'date' => '15 Mei', 'val' => 'Rp 5.0M'],
                    ['x' => 350, 'y' => 158, 'date' => '17 Mei', 'val' => 'Rp 6.8M'],
                    ['x' => 400, 'y' => 132, 'date' => '20 Mei', 'val' => 'Rp 8.9M'],
                    ['x' => 450, 'y' => 105, 'date' => '22 Mei', 'val' => 'Rp 9.5M'],
                    ['x' => 500, 'y' => 80, 'date' => '25 Mei', 'val' => 'Rp 10.5M'],
                    ['x' => 550, 'y' => 55, 'date' => '28 Mei', 'val' => 'Rp 11.2M'],
                    ['x' => 600, 'y' => 35, 'date' => '30 Mei', 'val' => 'Rp 12.0M'],
                ];
                @endphp
                <div class="absolute inset-0">
                    @foreach($aPoints as $pt)
                    <div class="absolute" style="left: {{ ($pt['x'] / 600) * 100 }}%; top: {{ ($pt['y'] / 240) * 100 }}%; transform: translate(-50%, -50%);">
                        <div class="relative group">
                            <div class="w-3 h-3 rounded-full bg-white border-2 border-primary-500 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer shadow-md"></div>
                            {{-- Tooltip --}}
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-800 text-white text-[11px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl z-10">
                                <p class="font-semibold">{{ $pt['val'] }}</p>
                                <p class="text-neutral-400 text-[10px]">{{ $pt['date'] }} 2024</p>
                                <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-neutral-800"></div>
                            </div>
                            <div class="absolute inset-0 -m-3"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- X-axis labels --}}
        <div class="flex ml-12">
            <div class="flex-1 flex justify-between text-[11px] text-neutral-400 mt-1.5">
                <span>01 Mei</span>
                <span>05 Mei</span>
                <span>10 Mei</span>
                <span>15 Mei</span>
                <span>20 Mei</span>
                <span>25 Mei</span>
                <span>30 Mei</span>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    {{-- Produk Terlaris --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-neutral-800 font-sans flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                Produk Terlaris
            </h3>
            <a href="#" class="text-xs font-semibold text-primary-600">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @foreach([
                ['name' => 'SR12 Exclusive Compact Powder', 'sold' => 452, 'revenue' => '12.5M', 'pct' => 90],
                ['name' => 'SR12 Lip Cream Matte Rose', 'sold' => 389, 'revenue' => '8.2M', 'pct' => 77],
                ['name' => 'SR12 Whitening Body Lotion', 'sold' => 310, 'revenue' => '6.4M', 'pct' => 62],
                ['name' => 'SR12 Sun Care Lotion SPF 30', 'sold' => 245, 'revenue' => '4.1M', 'pct' => 49],
                ['name' => 'SR12 Facial Wash Green Tea', 'sold' => 198, 'revenue' => '3.5M', 'pct' => 39],
            ] as $item)
            <div class="group">
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-neutral-700">{{ $item['name'] }}</span>
                    <span class="text-neutral-500">{{ $item['sold'] }} terjual</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-2 bg-neutral-100 rounded-full overflow-hidden relative">
                        <div class="h-full bg-gradient-to-r from-primary-400 to-primary-600 rounded-full transition-all duration-500" style="width: {{ $item['pct'] }}%"></div>
                        {{-- Progress bar tooltip --}}
                        <div class="absolute bottom-full mb-1.5 px-2.5 py-1.5 bg-neutral-800 text-white text-[10px] rounded-md opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-lg z-10" style="left: {{ $item['pct'] }}%; transform: translateX(-50%);">
                            {{ $item['pct'] }}% • Rp {{ $item['revenue'] }}
                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[3px] border-r-[3px] border-t-[3px] border-transparent border-t-neutral-800"></div>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-neutral-600 w-14 text-right">Rp {{ $item['revenue'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Stok Menipis --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-neutral-800 font-sans flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                Stok Menipis
            </h3>
            <a href="/admin/produk-pesanan" class="text-xs font-semibold text-primary-600">Kelola Stok</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-2 text-xs font-semibold text-neutral-500">ID Produk</th>
                        <th class="text-left py-2 text-xs font-semibold text-neutral-500">Nama Produk</th>
                        <th class="text-center py-2 text-xs font-semibold text-neutral-500">Sisa</th>
                        <th class="text-right py-2 text-xs font-semibold text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach([
                        ['id' => 'SKU-092', 'name' => 'Acne Peel Treatment', 'cat' => 'TREATMENT', 'left' => 4, 'critical' => true],
                        ['id' => 'SKU-115', 'name' => 'Manjakani SR12 Butir', 'cat' => 'HERBAL', 'left' => 8, 'critical' => false],
                        ['id' => 'SKU-044', 'name' => 'Krim Malam Gold', 'cat' => 'CREAM', 'left' => 2, 'critical' => true],
                        ['id' => 'SKU-087', 'name' => 'Serum Brightening', 'cat' => 'SERUM', 'left' => 9, 'critical' => false],
                        ['id' => 'SKU-201', 'name' => 'Gomilku Susu Kambing', 'cat' => 'NUTRITION', 'left' => 3, 'critical' => true],
                    ] as $item)
                    <tr>
                        <td class="py-2.5 font-mono text-xs text-neutral-500">{{ $item['id'] }}</td>
                        <td class="py-2.5">
                            <p class="text-sm font-medium text-neutral-800">{{ $item['name'] }}</p>
                            <p class="text-[10px] text-neutral-400 uppercase">{{ $item['cat'] }}</p>
                        </td>
                        <td class="py-2.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $item['critical'] ? 'bg-red-100 text-red-600' : 'text-neutral-600' }}">{{ $item['left'] }}</span>
                        </td>
                        <td class="py-2.5 text-right">
                            <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Restock</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Transactions Table --}}
<div class="card p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-neutral-800 font-sans">Detail Transaksi Terbaru</h2>
            <p class="text-xs text-neutral-500 mt-0.5">Menampilkan 50 transaksi terakhir untuk audit manual</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 text-neutral-400 hover:text-neutral-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg></button>
            <button class="flex items-center gap-1 px-3 py-2 border border-neutral-200 rounded-lg text-xs font-medium text-neutral-700 hover:bg-neutral-50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                Filter
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-neutral-200">
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">ID Transaksi</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Pelanggan</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Tanggal</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Jenis</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Total</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                    <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach([
                    ['id' => 'TRX-99281', 'name' => 'Siti Aminah', 'date' => '30 Mei 2024', 'type' => 'Grosir', 'total' => 450000, 'status' => 'Selesai'],
                    ['id' => 'TRX-99280', 'name' => 'Budi Santoso', 'date' => '30 Mei 2024', 'type' => 'Eceran', 'total' => 125000, 'status' => 'Diproses'],
                    ['id' => 'TRX-99279', 'name' => 'Rina Wijaya', 'date' => '29 Mei 2024', 'type' => 'Grosir', 'total' => 890000, 'status' => 'Selesai'],
                    ['id' => 'TRX-99278', 'name' => 'Andi Pratama', 'date' => '29 Mei 2024', 'type' => 'Eceran', 'total' => 210000, 'status' => 'Dibatalkan'],
                    ['id' => 'TRX-99277', 'name' => 'Dewi Lestari', 'date' => '28 Mei 2024', 'type' => 'Grosir', 'total' => 560000, 'status' => 'Selesai'],
                ] as $trx)
                @php
                    $sClass = ['Selesai' => 'status-selesai', 'Diproses' => 'status-diproses', 'Dibatalkan' => 'status-dibatalkan'];
                @endphp
                <tr class="hover:bg-neutral-50 transition-colors">
                    <td class="py-3 px-2 font-mono text-xs text-primary-600 font-semibold">{{ $trx['id'] }}</td>
                    <td class="py-3 px-2 font-medium text-neutral-800">{{ $trx['name'] }}</td>
                    <td class="py-3 px-2 text-neutral-500">{{ $trx['date'] }}</td>
                    <td class="py-3 px-2 text-neutral-500">{{ $trx['type'] }}</td>
                    <td class="py-3 px-2 font-semibold">Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                    <td class="py-3 px-2"><span class="{{ $sClass[$trx['status']] ?? 'badge' }}">{{ $trx['status'] }}</span></td>
                    <td class="py-3 px-2">
                        <button class="p-1.5 text-neutral-400 hover:text-primary-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex items-center justify-between">
        <p class="text-xs text-neutral-500">Menampilkan 1-5 dari 1248 transaksi</p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg border border-neutral-200 flex items-center justify-center text-neutral-400"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></button>
            <button class="w-8 h-8 rounded-lg bg-primary-600 text-white text-xs font-semibold">1</button>
            <button class="w-8 h-8 rounded-lg border border-neutral-200 text-neutral-600 text-xs font-medium hover:bg-neutral-50">2</button>
            <button class="w-8 h-8 rounded-lg border border-neutral-200 text-neutral-600 text-xs font-medium hover:bg-neutral-50">3</button>
            <button class="w-8 h-8 rounded-lg border border-neutral-200 flex items-center justify-center text-neutral-600"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></button>
        </div>
    </div>
</div>
@endsection
