@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
{{-- Date Filter --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <div class="flex items-center gap-2 px-4 py-2 bg-white border border-neutral-200 rounded-xl text-sm text-neutral-700">
        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        May 1, 2024 - May 31, 2024
    </div>
    <button class="btn-primary text-sm !py-2 !px-5">Terapkan Filter</button>
    <div class="ml-auto">
        <button class="p-2 text-neutral-500 hover:text-neutral-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 3.375V7.313"/></svg>
        </button>
    </div>
</div>

{{-- Main 3-Column Layout --}}
<div class="grid lg:grid-cols-[280px_1fr_240px] gap-5">

    {{-- ========== LEFT COLUMN ========== --}}
    <div class="space-y-5">
        {{-- User Management --}}
        <div class="card p-6">
            <p class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-5">User Management</p>

            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-neutral-500">Active Users</span>
                <span class="text-3xl font-bold text-neutral-900">1,402</span>
            </div>

            <div class="flex items-center justify-between mb-6">
                <span class="text-sm text-neutral-500">Role Counts</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border border-primary-300 text-primary-700">12 Roles</span>
            </div>

            <button class="btn-primary w-full text-sm !py-2.5 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                Tambah User
            </button>
            <button class="w-full py-2.5 text-sm font-semibold text-neutral-700 border border-neutral-300 rounded-full hover:bg-neutral-50 transition-colors">
                Atur Role Akses
            </button>
        </div>

        {{-- Stok Menipis --}}
        <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-5 text-white">
            <p class="text-xs font-bold uppercase tracking-wider mb-4 text-white/90">Stok Menipis</p>
            <div class="space-y-4">
                @foreach([
                    ['name' => 'Compact Powder', 'left' => '4 units left', 'color' => 'bg-red-400'],
                    ['name' => 'Lip Cream Rose', 'left' => '6 units left', 'color' => 'bg-amber-400'],
                ] as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></span>
                        <div>
                            <p class="text-sm font-semibold text-primary-100">{{ $item['name'] }}</p>
                            <p class="text-[11px] text-white/60">{{ $item['left'] }}</p>
                        </div>
                    </div>
                    <a href="#" class="text-xs font-semibold text-white/80 hover:text-white transition-colors">Restock</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ========== CENTER COLUMN ========== --}}
    <div class="space-y-5">
        {{-- Stats Row --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach([
                ['label' => 'TOTAL REVENUE', 'value' => 'Rp 48.2M', 'change' => '+12.5% vs LW', 'positive' => true],
                ['label' => 'TOTAL ORDERS', 'value' => '1,248', 'change' => '+8.2% vs LW', 'positive' => true],
                ['label' => 'AOV', 'value' => 'Rp 38.6k', 'change' => '-2.4% vs LW', 'positive' => false],
            ] as $stat)
            <div class="card p-5">
                <p class="text-xs font-semibold text-neutral-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-neutral-900 mt-2">{{ $stat['value'] }}</p>
                <p class="text-xs mt-1 {{ $stat['positive'] ? 'text-green-600' : 'text-red-500' }}">{{ $stat['change'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Sales Trend Chart --}}
        <div class="card p-6" x-data="salesChart()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-neutral-800 font-sans">Sales Trend Analysis</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">Gross revenue performance across all regions</p>
                </div>
                <div class="flex items-center gap-1 bg-neutral-100 rounded-lg p-0.5">
                    <button class="px-3 py-1.5 text-xs font-medium rounded-md text-neutral-600 hover:bg-white transition-all">Weekly</button>
                    <button class="px-3 py-1.5 text-xs font-medium rounded-md bg-white shadow-sm text-neutral-800">Monthly</button>
                </div>
            </div>

            {{-- Chart Container --}}
            <div class="relative">
                <div class="flex">
                    {{-- Y-axis --}}
                    <div class="flex flex-col justify-between text-[11px] text-neutral-400 w-10 shrink-0 pr-2 pb-6" style="height: 220px;">
                        <span>Rp 9M</span>
                        <span>Rp 6M</span>
                        <span>Rp 3M</span>
                        <span>Rp 0M</span>
                    </div>

                    {{-- SVG Chart --}}
                    <div class="flex-1 relative" style="height: 220px;">
                        <svg class="w-full h-full" viewBox="0 0 560 200" preserveAspectRatio="none" fill="none">
                            {{-- Horizontal grid lines --}}
                            <line x1="0" y1="0" x2="560" y2="0" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="67" x2="560" y2="67" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="133" x2="560" y2="133" stroke="#f3f4f6" stroke-width="0.5"/>
                            <line x1="0" y1="200" x2="560" y2="200" stroke="#f3f4f6" stroke-width="0.5"/>

                            {{-- Area fill --}}
                            <path d="M0,195 L47,190 L93,185 L140,178 L187,168 L233,155 L280,140 L327,118 L373,95 L420,70 L467,48 L513,32 L560,22 L560,200 L0,200 Z"
                                  fill="url(#dashAreaFill)" />

                            {{-- Line --}}
                            <path d="M0,195 L47,190 L93,185 L140,178 L187,168 L233,155 L280,140 L327,118 L373,95 L420,70 L467,48 L513,32 L560,22"
                                  stroke="url(#dashLineFill)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

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

                        {{-- Interactive data points layer --}}
                        @php
                        $points = [
                            ['x' => 0, 'y' => 195, 'date' => '01 Mei', 'val' => 'Rp 450K'],
                            ['x' => 47, 'y' => 190, 'date' => '03 Mei', 'val' => 'Rp 900K'],
                            ['x' => 93, 'y' => 185, 'date' => '05 Mei', 'val' => 'Rp 1.4M'],
                            ['x' => 140, 'y' => 178, 'date' => '08 Mei', 'val' => 'Rp 2.0M'],
                            ['x' => 187, 'y' => 168, 'date' => '10 Mei', 'val' => 'Rp 2.9M'],
                            ['x' => 233, 'y' => 155, 'date' => '13 Mei', 'val' => 'Rp 4.0M'],
                            ['x' => 280, 'y' => 140, 'date' => '15 Mei', 'val' => 'Rp 5.4M'],
                            ['x' => 327, 'y' => 118, 'date' => '18 Mei', 'val' => 'Rp 5.8M'],
                            ['x' => 373, 'y' => 95, 'date' => '20 Mei', 'val' => 'Rp 6.5M'],
                            ['x' => 420, 'y' => 70, 'date' => '23 Mei', 'val' => 'Rp 7.2M'],
                            ['x' => 467, 'y' => 48, 'date' => '25 Mei', 'val' => 'Rp 8.0M'],
                            ['x' => 513, 'y' => 32, 'date' => '28 Mei', 'val' => 'Rp 8.6M'],
                            ['x' => 560, 'y' => 22, 'date' => '30 Mei', 'val' => 'Rp 9.1M'],
                        ];
                        @endphp
                        <div class="absolute inset-0">
                            @foreach($points as $i => $pt)
                            <div class="absolute" style="left: {{ ($pt['x'] / 560) * 100 }}%; top: {{ ($pt['y'] / 200) * 100 }}%; transform: translate(-50%, -50%);">
                                <div class="relative group">
                                    <div class="w-3 h-3 rounded-full bg-white border-2 border-primary-500 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer shadow-md"></div>
                                    {{-- Tooltip --}}
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-800 text-white text-[11px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl z-10">
                                        <p class="font-semibold">{{ $pt['val'] }}</p>
                                        <p class="text-neutral-400 text-[10px]">{{ $pt['date'] }} 2024</p>
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-neutral-800"></div>
                                    </div>
                                    {{-- Hover area (larger clickable target) --}}
                                    <div class="absolute inset-0 -m-3"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- X-axis labels --}}
                <div class="flex ml-10">
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

        <script>
        function salesChart() {
            return { activePoint: null };
        }
        </script>
    </div>

    {{-- ========== RIGHT COLUMN ========== --}}
    <div class="space-y-5">
        {{-- Quick Actions --}}
        <div class="card p-5">
            <p class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-4">Quick Actions</p>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Export Reports', 'desc' => 'CSV, XLSX, PDF', 'icon' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3', 'color' => 'bg-blue-50 text-blue-600'],
                    ['label' => 'Tambah Stock', 'desc' => 'Menambahkan Stok Produk', 'icon' => 'M12 4.5v15m7.5-7.5h-15', 'color' => 'bg-primary-50 text-primary-600'],
                    ['label' => 'Verifikasi Pesanan', 'desc' => 'Verify payments', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-green-50 text-green-600'],
                ] as $action)
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-neutral-50 transition-colors group">
                    <div class="w-10 h-10 rounded-xl {{ $action['color'] }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-neutral-800">{{ $action['label'] }}</p>
                        <p class="text-[11px] text-neutral-400 truncate">{{ $action['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- System Activity --}}
        <div class="card p-5">
            <p class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-4">System Activity</p>
            <div class="space-y-4">
                @foreach([
                    ['label' => 'New user registered', 'time' => '2 mins ago', 'color' => 'bg-blue-500'],
                    ['label' => 'Stock alert resolved', 'time' => '45 mins ago', 'color' => 'bg-primary-500'],
                    ['label' => 'Order #ORD-2024-005 shipped', 'time' => '1 hour ago', 'color' => 'bg-green-500'],
                    ['label' => 'Payment verified for #ORD-2024-004', 'time' => '2 hours ago', 'color' => 'bg-amber-500'],
                ] as $activity)
                <div class="flex items-start gap-3">
                    <span class="w-3 h-3 rounded-full {{ $activity['color'] }} shrink-0 mt-0.5"></span>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-neutral-700 leading-snug">{{ $activity['label'] }}</p>
                        <p class="text-[11px] text-neutral-400">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
