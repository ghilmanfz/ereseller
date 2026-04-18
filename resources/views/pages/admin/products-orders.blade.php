@extends('layouts.admin')
@section('title', 'Produk dan Pesanan')
@section('page_title', 'Produk dan Pesanan')

@section('content')
<div x-data="{
    selectedOrder: null,
    openDropdown: null,
    editModal: false,
    deleteModal: false,
    addProductModal: false,
    editProductModal: false,
    restockProductModal: false,
    reduceProductModal: false,
    deleteProductModal: false,
    openProductDropdown: null,
    categoryModal: false,
    stockHistoryModal: false,
    editingOrder: null,
    editingProduct: null,
    orders: [
        {id:'ORD-2024-001', name:'Anisa Rahma', date:'24 Okt 2024', total:350000, status:'Verifikasi', wa:'0812-3456-7890', items:[{qty:2,name:'SR12 Facial Wash',price:130000},{qty:1,name:'Acne Peel Solution',price:120000}], ongkir:15000, pengiriman:'Dikirim (J&T)'},
        {id:'ORD-2024-002', name:'Budi Santoso', date:'24 Okt 2024', total:125000, status:'Diproses', wa:'0813-1234-5678', items:[{qty:1,name:'SR12 Lip Care Cherry',price:25000},{qty:1,name:'SR12 Suncare Lotion',price:85000}], ongkir:15000, pengiriman:'Dikirim (SiCepat)'},
        {id:'ORD-2024-003', name:'Citra Lestari', date:'23 Okt 2024', total:850000, status:'Selesai', wa:'0814-9876-5432', items:[{qty:5,name:'SR12 Lightening Lotion',price:475000},{qty:3,name:'SR12 Acne Peel',price:360000}], ongkir:15000, pengiriman:'Ambil di Toko'},
        {id:'ORD-2024-004', name:'Dewi Ayu', date:'23 Okt 2024', total:210000, status:'Pending', wa:'0815-5555-4444', items:[{qty:2,name:'SR12 Exclusive Facial Wash',price:130000},{qty:1,name:'SR12 Lip Care',price:25000}], ongkir:15000, pengiriman:'Dikirim (JNE)'},
        {id:'ORD-2024-005', name:'Eko Prasetyo', date:'22 Okt 2024', total:450000, status:'Dikirim', wa:'0816-7777-8888', items:[{qty:3,name:'SR12 Suncare Lotion',price:255000},{qty:2,name:'SR12 Facial Wash',price:130000}], ongkir:15000, pengiriman:'Dikirim (J&T)'},
    ],
    formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); },
    viewOrder(order) { this.selectedOrder = order; this.openDropdown = null; },
    openEdit(order) { this.editingOrder = {...order}; this.editModal = true; this.openDropdown = null; },
    openDelete(order) { this.editingOrder = {...order}; this.deleteModal = true; this.openDropdown = null; },
}" @click.away="openDropdown = null">

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Pesanan', 'value' => '1,284', 'change' => '+12% dari bulan lalu', 'positive' => true, 'color' => 'primary'],
        ['label' => 'Perlu Verifikasi', 'value' => '18', 'change' => '+5 dari bulan lalu', 'positive' => false, 'color' => 'amber'],
        ['label' => 'Stok Menipis', 'value' => '4 Produk', 'change' => '-2% dari bulan lalu', 'positive' => false, 'color' => 'red'],
        ['label' => 'Pendapatan (Bulan Ini)', 'value' => 'Rp 42.5M', 'change' => '+18% dari bulan lalu', 'positive' => true, 'color' => 'green'],
    ] as $stat)
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-xs text-neutral-500">{{ $stat['label'] }}</p>
            <p class="text-2xl font-bold text-neutral-900 mt-1">{{ $stat['value'] }}</p>
            <p class="text-xs mt-1 {{ $stat['positive'] ? 'text-green-600' : 'text-red-500' }}">↗ {{ $stat['change'] }}</p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
        </div>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Orders Table --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-base font-bold text-neutral-800 font-sans">Kelola Pesanan Masuk</h2>
                    <p class="text-xs text-neutral-500 mt-0.5">Pantau dan verifikasi pembayaran dari pelanggan secara real-time.</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" placeholder="Cari ID Pesanan..." class="pl-9 pr-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 w-44">
                    </div>
                    <button class="flex items-center gap-1.5 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                        Filter
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200">
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">ID Pesanan</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Pelanggan</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Tanggal</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Total</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        <template x-for="order in orders" :key="order.id">
                        <tr class="hover:bg-neutral-50 transition-colors" :class="selectedOrder && selectedOrder.id === order.id ? 'bg-primary-50/50' : ''">
                            <td class="py-3 px-2 font-mono text-xs text-neutral-700" x-text="order.id"></td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center text-white text-[9px] font-bold" x-text="order.name.substring(0,2).toUpperCase()"></div>
                                    <span class="font-medium text-neutral-800" x-text="order.name"></span>
                                </div>
                            </td>
                            <td class="py-3 px-2 text-neutral-500" x-text="order.date"></td>
                            <td class="py-3 px-2 font-semibold" x-text="formatRp(order.total)"></td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                    :class="{
                                        'bg-amber-100 text-amber-700': order.status === 'Verifikasi',
                                        'bg-blue-100 text-blue-700': order.status === 'Diproses',
                                        'bg-green-100 text-green-700': order.status === 'Selesai',
                                        'bg-neutral-100 text-neutral-600': order.status === 'Pending',
                                        'bg-purple-100 text-purple-700': order.status === 'Dikirim',
                                        'bg-red-100 text-red-700': order.status === 'Dibatalkan'
                                    }" x-text="order.status"></span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    {{-- Eye icon - view detail --}}
                                    <button @click="viewOrder(order)" class="p-1.5 transition-colors" :class="selectedOrder && selectedOrder.id === order.id ? 'text-primary-600' : 'text-neutral-400 hover:text-primary-600'" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                    {{-- Three dot dropdown --}}
                                    <div class="relative">
                                        <button @click.stop="openDropdown = openDropdown === order.id ? null : order.id" class="p-1.5 text-neutral-400 hover:text-neutral-600 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                        </button>
                                        {{-- Dropdown menu --}}
                                        <div x-show="openDropdown === order.id" x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             @click.outside="openDropdown = null"
                                             class="absolute right-0 top-full mt-1 w-36 bg-white rounded-xl shadow-lg border border-neutral-200 py-1 z-20">
                                            <button @click="openEdit(order)" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                Edit
                                            </button>
                                            <button @click="openDelete(order)" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stok Produk --}}
        <div class="card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-base font-bold text-neutral-800 font-sans">Stok Produk SR12</h2>
                    <p class="text-xs text-neutral-500 mt-0.5">Kelola ketersediaan produk dan perbarui informasi stok.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="stockHistoryModal = true" class="flex items-center gap-1.5 px-3 py-2 border border-neutral-200 rounded-xl text-xs font-semibold text-neutral-600 hover:bg-neutral-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Stok
                    </button>
                    <button @click="categoryModal = true" class="flex items-center gap-1.5 px-3 py-2 border border-neutral-200 rounded-xl text-xs font-semibold text-neutral-600 hover:bg-neutral-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                        Kelola Kategori
                    </button>
                    <button @click="addProductModal = true" class="btn-primary text-sm !py-2">+ Tambah Produk</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-200">
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Produk</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Kategori</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Harga</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Stok</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Status</th>
                            <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach([
                            ['name' => 'SR12 Exclusive Facial Wash', 'cat' => 'Skincare', 'price' => 65000, 'stock' => 45, 'status' => 'Tersedia'],
                            ['name' => 'SR12 Suncare Lotion SPF 30', 'cat' => 'Bodycare', 'price' => 85000, 'stock' => 8, 'status' => 'Stok Menipis'],
                            ['name' => 'SR12 Acne Peel Solution', 'cat' => 'Skincare', 'price' => 120000, 'stock' => 22, 'status' => 'Tersedia'],
                            ['name' => 'SR12 Lip Care Cherry', 'cat' => 'Lipcare', 'price' => 25000, 'stock' => 5, 'status' => 'Stok Menipis'],
                            ['name' => 'SR12 Lightening Body Lotion', 'cat' => 'Bodycare', 'price' => 95000, 'stock' => 15, 'status' => 'Tersedia'],
                        ] as $prod)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-neutral-100 rounded-lg overflow-hidden shrink-0">
                                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=80&h=80&fit=crop" class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-medium text-neutral-800">{{ $prod['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-2"><span class="badge bg-neutral-100 text-neutral-600 text-xs">{{ $prod['cat'] }}</span></td>
                            <td class="py-3 px-2 text-neutral-700">Rp {{ number_format($prod['price'], 0, ',', '.') }}</td>
                            <td class="py-3 px-2 {{ $prod['stock'] <= 10 ? 'text-red-500 font-semibold' : 'text-neutral-700' }}">{{ $prod['stock'] }} pcs</td>
                            <td class="py-3 px-2">
                                <span class="{{ $prod['status'] === 'Tersedia' ? 'badge-success' : 'badge-error' }} text-xs">{{ $prod['status'] }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="relative" x-data="{ prodId: '{{ $loop->index }}' }">
                                    <button @click.stop="openProductDropdown = openProductDropdown === prodId ? null : prodId" class="p-1.5 text-neutral-400 hover:text-neutral-600 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                                    </button>
                                    <div x-show="openProductDropdown === prodId" x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         @click.outside="openProductDropdown = null"
                                         class="absolute right-0 top-full mt-1 w-44 bg-white rounded-xl shadow-lg border border-neutral-200 py-1 z-20">
                                        <button @click="editingProduct = {name:'{{ $prod['name'] }}', cat:'{{ $prod['cat'] }}', price:{{ $prod['price'] }}, stock:{{ $prod['stock'] }}}; editProductModal = true; openProductDropdown = null" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            Edit Produk
                                        </button>
                                        <button @click="editingProduct = {name:'{{ $prod['name'] }}', stock:{{ $prod['stock'] }}}; restockProductModal = true; openProductDropdown = null" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Tambah Stok
                                        </button>
                                        <button @click="editingProduct = {name:'{{ $prod['name'] }}', stock:{{ $prod['stock'] }}}; reduceProductModal = true; openProductDropdown = null" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Kurangi Stok
                                        </button>
                                        <hr class="my-1 border-neutral-100">
                                        <button @click="editingProduct = {name:'{{ $prod['name'] }}'}; deleteProductModal = true; openProductDropdown = null" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            Hapus Produk
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Order Detail Sidebar --}}
    <div class="lg:col-span-1">
        {{-- Empty State --}}
        <div x-show="!selectedOrder" class="card p-8 sticky top-24 text-center">
            <div class="w-16 h-16 mx-auto bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-neutral-600">Pilih Pesanan</p>
            <p class="text-xs text-neutral-400 mt-1">Klik ikon <span class="inline-block align-middle"><svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span> pada tabel untuk melihat detail.</p>
        </div>

        {{-- Detail Card --}}
        <div x-show="selectedOrder" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="card p-5 sticky top-24">
            <div class="flex items-center justify-between mb-4">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold"
                    :class="{
                        'bg-amber-100 text-amber-700': selectedOrder?.status === 'Verifikasi',
                        'bg-blue-100 text-blue-700': selectedOrder?.status === 'Diproses',
                        'bg-green-100 text-green-700': selectedOrder?.status === 'Selesai',
                        'bg-neutral-100 text-neutral-600': selectedOrder?.status === 'Pending',
                        'bg-purple-100 text-purple-700': selectedOrder?.status === 'Dikirim',
                        'bg-red-100 text-red-700': selectedOrder?.status === 'Dibatalkan'
                    }" x-text="selectedOrder?.status"></span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-neutral-400 font-mono" x-text="selectedOrder?.id"></span>
                    <button @click="selectedOrder = null" class="p-1 text-neutral-400 hover:text-neutral-600 transition-colors" title="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <h3 class="text-base font-bold text-neutral-800 font-sans">Detail Pesanan</h3>
            <p class="text-xs text-neutral-500 mt-0.5">Diterima pada <span x-text="selectedOrder?.date"></span></p>

            <div class="mt-4 flex items-center gap-3 p-3 bg-neutral-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center text-white text-xs font-bold" x-text="selectedOrder?.name?.substring(0,2).toUpperCase()"></div>
                <div>
                    <p class="text-sm font-semibold text-neutral-800" x-text="selectedOrder?.name"></p>
                    <p class="text-xs text-neutral-400" x-text="'WA: ' + (selectedOrder?.wa || '-')"></p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs font-bold text-neutral-500 uppercase mb-2">Daftar Produk</p>
                <div class="space-y-2 text-sm">
                    <template x-for="item in (selectedOrder?.items || [])" :key="item.name">
                        <div class="flex justify-between">
                            <span class="text-neutral-600" x-text="item.qty + 'x ' + item.name"></span>
                            <span class="font-medium" x-text="formatRp(item.price)"></span>
                        </div>
                    </template>
                    <div class="flex justify-between text-neutral-500">
                        <span x-text="'Ongkir (' + (selectedOrder?.pengiriman || '') + ')'"></span>
                        <span x-text="formatRp(selectedOrder?.ongkir || 0)"></span>
                    </div>
                    <hr class="border-neutral-200">
                    <div class="flex justify-between font-bold">
                        <span>Total Bayar</span>
                        <span class="text-primary-600" x-text="formatRp(selectedOrder?.total || 0)"></span>
                    </div>
                </div>
            </div>

            <template x-if="selectedOrder?.status === 'Verifikasi' || selectedOrder?.status === 'Pending'">
                <button class="btn-primary w-full mt-5 text-sm !py-3">Konfirmasi Pembayaran</button>
            </template>
            <div class="mt-3 flex gap-2">
                <button class="flex-1 py-2 text-sm font-medium text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Lihat Bukti</button>
                <button @click="openEdit(selectedOrder)" class="flex-1 py-2 text-sm font-medium text-blue-600 border border-blue-200 rounded-xl hover:bg-blue-50 transition-colors">Edit</button>
            </div>

            <div class="mt-4 p-3 bg-amber-50 rounded-xl flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                <p class="text-xs text-amber-800"><strong>Penting:</strong> Selalu periksa mutasi rekening sebelum melakukan konfirmasi pembayaran manual.</p>
            </div>
        </div>
    </div>
</div>

{{-- ======== EDIT MODAL ======== --}}
<div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="editModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editModal = false"></div>
    <div x-show="editModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-neutral-800">Edit Pesanan</h3>
            <button @click="editModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">ID Pesanan</label>
                <input type="text" :value="editingOrder?.id" disabled class="w-full px-4 py-2.5 bg-neutral-100 border border-neutral-200 rounded-xl text-sm text-neutral-500 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Nama Pelanggan</label>
                <input type="text" x-model="editingOrder.name" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">No. WhatsApp</label>
                    <input type="text" x-model="editingOrder.wa" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select x-model="editingOrder.status" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="Pending">Pending</option>
                        <option value="Verifikasi">Verifikasi</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Dikirim">Dikirim</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Metode Pengiriman</label>
                <input type="text" x-model="editingOrder.pengiriman" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button @click="editModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="let i = orders.findIndex(o => o.id === editingOrder.id); if(i>=0){ orders[i] = {...editingOrder}; if(selectedOrder?.id === editingOrder.id) selectedOrder = {...editingOrder}; } editModal = false;" class="flex-1 btn-primary text-sm !py-2.5">Simpan Perubahan</button>
        </div>
    </div>
</div>

{{-- ======== DELETE MODAL ======== --}}
<div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="deleteModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteModal = false"></div>
    <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 text-center">
        <div class="w-14 h-14 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        </div>
        <h3 class="text-lg font-bold text-neutral-800 mb-1">Hapus Pesanan?</h3>
        <p class="text-sm text-neutral-500 mb-1">Pesanan <span class="font-mono font-semibold text-neutral-700" x-text="editingOrder?.id"></span></p>
        <p class="text-xs text-neutral-400 mb-6">Tindakan ini tidak dapat dibatalkan. Data pesanan akan dihapus permanen.</p>
        <div class="flex gap-3">
            <button @click="deleteModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="orders = orders.filter(o => o.id !== editingOrder.id); if(selectedOrder?.id === editingOrder.id) selectedOrder = null; deleteModal = false;" class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- ======== TAMBAH PRODUK MODAL ======== --}}
<div x-show="addProductModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="addProductModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="addProductModal = false"></div>
    <div x-show="addProductModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 z-10 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5 sticky top-0 bg-white pb-2 z-10">
            <h3 class="text-lg font-bold text-neutral-800">Tambah Produk Baru</h3>
            <button @click="addProductModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <div class="space-y-5">
            {{-- Foto Produk --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Foto Produk</label>
                <div class="flex gap-3 flex-wrap">
                    {{-- Add photo button --}}
                    <button class="w-20 h-20 rounded-xl border-2 border-dashed border-neutral-300 hover:border-primary-400 flex flex-col items-center justify-center text-neutral-400 hover:text-primary-500 transition-colors shrink-0 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span class="text-[9px] font-semibold mt-0.5">Tambah</span>
                    </button>
                </div>
                <p class="text-[11px] text-neutral-400 mt-1.5">Foto pertama menjadi foto utama. Maks 5 foto, JPG/PNG, maks 2MB per foto.</p>
            </div>

            <hr class="border-neutral-100">

            {{-- Info Dasar --}}
            <div>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Informasi Dasar</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Nama Produk</label>
                        <input type="text" placeholder="Contoh: SR12 Exclusive Facial Wash" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Kategori</label>
                            <select class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Pilih Kategori</option>
                                <option value="Skincare">Skincare</option>
                                <option value="Bodycare">Bodycare</option>
                                <option value="Lipcare">Lipcare</option>
                                <option value="Haircare">Haircare</option>
                                <option value="Herbal">Herbal</option>
                                <option value="Nutrition">Nutrition</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Harga (Rp)</label>
                            <input type="number" placeholder="65000" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Stok Awal</label>
                            <input type="number" placeholder="50" min="0" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Berat (gram)</label>
                            <input type="number" placeholder="100" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Harga Coret (Rp)</label>
                            <input type="number" placeholder="Opsional" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-neutral-100">

            {{-- Detail Produk --}}
            <div>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Detail Produk</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Deskripsi Produk</label>
                        <textarea rows="3" placeholder="Deskripsi singkat tentang produk, keunggulan, dan target penggunaan..." class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Manfaat Produk</label>
                        <textarea rows="4" placeholder="Tulis setiap manfaat di baris baru, contoh:&#10;- Melindungi kulit dari paparan sinar UV&#10;- Menyamarkan pori-pori dan noda hitam" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Cara Penggunaan</label>
                        <textarea rows="4" placeholder="Tulis setiap langkah di baris baru, contoh:&#10;1. Bersihkan wajah terlebih dahulu&#10;2. Gunakan spons/puff, ambil bedak secukupnya" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Komposisi Lengkap</label>
                        <textarea rows="2" placeholder="Contoh: Talc, Mica, Titanium Dioxide, Vitamin E, Aloe Vera Extract..." class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6 sticky bottom-0 bg-white pt-3 z-10">
            <button @click="addProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="addProductModal = false" class="flex-1 btn-primary text-sm !py-2.5">Simpan Produk</button>
        </div>
    </div>
</div>

{{-- ======== EDIT PRODUK MODAL ======== --}}
<div x-show="editProductModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="editProductModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="editProductModal = false"></div>
    <div x-show="editProductModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 z-10 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5 sticky top-0 bg-white pb-2 z-10">
            <h3 class="text-lg font-bold text-neutral-800">Edit Produk</h3>
            <button @click="editProductModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        <div class="space-y-5">
            {{-- Foto Produk --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Foto Produk</label>
                <div class="flex gap-3 flex-wrap">
                    {{-- Existing photo 1 --}}
                    <div class="relative group w-20 h-20 rounded-xl overflow-hidden border-2 border-neutral-200 shrink-0">
                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=160&h=160&fit=crop" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1">
                            <button class="p-1 bg-white/90 rounded-lg hover:bg-white transition-colors" title="Ganti Foto">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </button>
                            <button class="p-1 bg-white/90 rounded-lg hover:bg-white transition-colors" title="Hapus Foto">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                        <span class="absolute top-1 left-1 bg-primary-500 text-white text-[8px] font-bold px-1 py-0.5 rounded">UTAMA</span>
                    </div>
                    {{-- Existing photo 2 --}}
                    <div class="relative group w-20 h-20 rounded-xl overflow-hidden border-2 border-neutral-200 shrink-0">
                        <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=160&h=160&fit=crop" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1">
                            <button class="p-1 bg-white/90 rounded-lg hover:bg-white transition-colors" title="Ganti Foto">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </button>
                            <button class="p-1 bg-white/90 rounded-lg hover:bg-white transition-colors" title="Hapus Foto">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    </div>
                    {{-- Add photo button --}}
                    <button class="w-20 h-20 rounded-xl border-2 border-dashed border-neutral-300 hover:border-primary-400 flex flex-col items-center justify-center text-neutral-400 hover:text-primary-500 transition-colors shrink-0 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span class="text-[9px] font-semibold mt-0.5">Tambah</span>
                    </button>
                </div>
                <p class="text-[11px] text-neutral-400 mt-1.5">Hover foto untuk ganti/hapus. Foto pertama menjadi foto utama. Maks 5 foto, JPG/PNG, maks 2MB.</p>
            </div>

            <hr class="border-neutral-100">

            {{-- Info Dasar --}}
            <div>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Informasi Dasar</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Nama Produk</label>
                        <input type="text" x-model="editingProduct.name" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Kategori</label>
                            <select x-model="editingProduct.cat" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="Skincare">Skincare</option>
                                <option value="Bodycare">Bodycare</option>
                                <option value="Lipcare">Lipcare</option>
                                <option value="Haircare">Haircare</option>
                                <option value="Herbal">Herbal</option>
                                <option value="Nutrition">Nutrition</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Harga (Rp)</label>
                            <input type="number" x-model="editingProduct.price" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Stok</label>
                            <input type="number" x-model="editingProduct.stock" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Berat (gram)</label>
                            <input type="number" value="100" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Harga Coret (Rp)</label>
                            <input type="number" placeholder="Opsional" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-neutral-100">

            {{-- Detail Produk --}}
            <div>
                <p class="text-xs font-bold text-neutral-500 uppercase tracking-wider mb-3">Detail Produk</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Deskripsi Produk</label>
                        <textarea rows="3" placeholder="Deskripsi singkat tentang produk, keunggulan, dan target penggunaan..." class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">Bedak padat eksklusif dengan formula lembut yang membantu menyamarkan noda hitam dan garis halus di wajah. Memberikan hasil akhir yang matte dan natural.</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Manfaat Produk</label>
                        <textarea rows="4" placeholder="Tulis setiap manfaat di baris baru, contoh:&#10;- Melindungi kulit dari paparan sinar UV&#10;- Menyamarkan pori-pori dan noda hitam" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">- Melindungi kulit dari paparan sinar UV
- Menyamarkan pori-pori dan noda hitam
- Tekstur ringan dan tidak menyumbat pori
- Hasil akhir matte tahan hingga 8 jam
- Mengandung Vitamin E untuk menjaga kelembapan kulit</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Cara Penggunaan</label>
                        <textarea rows="4" placeholder="Tulis setiap langkah di baris baru, contoh:&#10;1. Bersihkan wajah terlebih dahulu&#10;2. Gunakan spons/puff, ambil bedak secukupnya" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">1. Bersihkan wajah terlebih dahulu
2. Gunakan spons/puff, ambil bedak secukupnya
3. Tepuk-tepuk ringan pada wajah
4. Ratakan ke seluruh wajah</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Komposisi Lengkap</label>
                        <textarea rows="2" placeholder="Contoh: Talc, Mica, Titanium Dioxide, Vitamin E, Aloe Vera Extract..." class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">Talc, Mica, Titanium Dioxide, Vitamin E, Aloe Vera Extract, Chamomile Extract</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6 sticky bottom-0 bg-white pt-3 z-10">
            <button @click="editProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="editProductModal = false" class="flex-1 btn-primary text-sm !py-2.5">Simpan Perubahan</button>
        </div>
    </div>
</div>

{{-- ======== RESTOCK PRODUK MODAL ======== --}}
<div x-show="restockProductModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="restockProductModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="restockProductModal = false"></div>
    <div x-show="restockProductModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-neutral-800">Tambah Stok</h3>
            <button @click="restockProductModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-3 bg-neutral-50 rounded-xl mb-4">
            <p class="text-xs text-neutral-500">Produk</p>
            <p class="text-sm font-semibold text-neutral-800" x-text="editingProduct?.name"></p>
            <p class="text-xs text-neutral-400 mt-0.5">Stok saat ini: <span class="font-semibold" x-text="editingProduct?.stock + ' pcs'"></span></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Jumlah Ditambahkan</label>
            <input type="number" value="10" min="1" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div class="flex gap-3 mt-6">
            <button @click="restockProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="restockProductModal = false" class="flex-1 btn-primary text-sm !py-2.5">Tambah Stok</button>
        </div>
    </div>
</div>

{{-- ======== DELETE PRODUK MODAL ======== --}}
<div x-show="deleteProductModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="deleteProductModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="deleteProductModal = false"></div>
    <div x-show="deleteProductModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 text-center">
        <div class="w-14 h-14 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        </div>
        <h3 class="text-lg font-bold text-neutral-800 mb-1">Hapus Produk?</h3>
        <p class="text-sm text-neutral-600 mb-1 font-semibold" x-text="editingProduct?.name"></p>
        <p class="text-xs text-neutral-400 mb-6">Produk akan dihapus permanen dari sistem. Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button @click="deleteProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="deleteProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- ======== KURANGI STOK MODAL ======== --}}
<div x-show="reduceProductModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="reduceProductModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="reduceProductModal = false"></div>
    <div x-show="reduceProductModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-neutral-800">Kurangi Stok</h3>
            <button @click="reduceProductModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="p-3 bg-neutral-50 rounded-xl mb-4">
            <p class="text-xs text-neutral-500">Produk</p>
            <p class="text-sm font-semibold text-neutral-800" x-text="editingProduct?.name"></p>
            <p class="text-xs text-neutral-400 mt-0.5">Stok saat ini: <span class="font-semibold" x-text="editingProduct?.stock + ' pcs'"></span></p>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Jumlah Dikurangi</label>
                <input type="number" value="1" min="1" class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Alasan Pengurangan</label>
                <select class="w-full px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option>Produk rusak / kedaluwarsa</option>
                    <option>Koreksi stok manual</option>
                    <option>Retur dari pelanggan</option>
                    <option>Penjualan offline</option>
                    <option>Lainnya</option>
                </select>
            </div>
        </div>
        <div class="mt-4 p-3 bg-amber-50 rounded-xl flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
            <p class="text-xs text-amber-800">Pastikan jumlah pengurangan tidak melebihi stok yang tersedia.</p>
        </div>
        <div class="flex gap-3 mt-5">
            <button @click="reduceProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">Batal</button>
            <button @click="reduceProductModal = false" class="flex-1 py-2.5 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition-colors">Kurangi Stok</button>
        </div>
    </div>
</div>

{{-- ======== KELOLA KATEGORI MODAL ======== --}}
<div x-show="categoryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="categoryModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="categoryModal = false"></div>
    <div x-show="categoryModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-neutral-800">Kelola Kategori</h3>
            <button @click="categoryModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        {{-- Tambah Kategori Baru --}}
        <div class="flex gap-2 mb-5">
            <input type="text" placeholder="Nama kategori baru..." class="flex-1 px-4 py-2.5 bg-white border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            <button class="px-4 py-2.5 btn-primary text-sm !rounded-xl shrink-0">Tambah</button>
        </div>

        {{-- Daftar Kategori --}}
        <div class="space-y-2">
            @foreach([
                ['name' => 'Skincare', 'count' => 12, 'color' => 'bg-pink-100 text-pink-700'],
                ['name' => 'Bodycare', 'count' => 8, 'color' => 'bg-blue-100 text-blue-700'],
                ['name' => 'Lipcare', 'count' => 5, 'color' => 'bg-red-100 text-red-700'],
                ['name' => 'Haircare', 'count' => 4, 'color' => 'bg-purple-100 text-purple-700'],
                ['name' => 'Herbal', 'count' => 6, 'color' => 'bg-green-100 text-green-700'],
                ['name' => 'Nutrition', 'count' => 3, 'color' => 'bg-amber-100 text-amber-700'],
            ] as $cat)
            <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl group hover:bg-neutral-100 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $cat['color'] }}">{{ $cat['name'] }}</span>
                    <span class="text-xs text-neutral-400">{{ $cat['count'] }} produk</span>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="p-1 text-neutral-400 hover:text-blue-600 transition-colors" title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    </button>
                    <button class="p-1 text-neutral-400 hover:text-red-500 transition-colors" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 p-3 bg-primary-50 rounded-xl">
            <p class="text-xs text-primary-700"><span class="font-semibold">Total:</span> 6 kategori aktif &middot; 38 produk terdaftar</p>
        </div>
    </div>
</div>

{{-- ======== RIWAYAT STOK MODAL ======== --}}
<div x-show="stockHistoryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="stockHistoryModal = false">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="stockHistoryModal = false"></div>
    <div x-show="stockHistoryModal" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 z-10 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-bold text-neutral-800">Riwayat Perubahan Stok</h3>
                <p class="text-xs text-neutral-500 mt-0.5">Log semua perubahan stok produk.</p>
            </div>
            <button @click="stockHistoryModal = false" class="p-1.5 text-neutral-400 hover:text-neutral-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>

        {{-- Filter --}}
        <div class="flex flex-wrap gap-2 mb-4">
            <select class="px-3 py-1.5 border border-neutral-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option>Semua Produk</option>
                <option>SR12 Exclusive Facial Wash</option>
                <option>SR12 Suncare Lotion SPF 30</option>
                <option>SR12 Acne Peel Solution</option>
                <option>SR12 Lip Care Cherry</option>
                <option>SR12 Lightening Body Lotion</option>
            </select>
            <select class="px-3 py-1.5 border border-neutral-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option>Semua Tipe</option>
                <option>Tambah Stok</option>
                <option>Kurangi Stok</option>
                <option>Penjualan</option>
            </select>
        </div>

        {{-- Log Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Tanggal</th>
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Produk</th>
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Tipe</th>
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Jumlah</th>
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Stok Akhir</th>
                        <th class="text-left py-2.5 px-2 text-[10px] font-semibold text-neutral-500 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach([
                        ['date' => '18 Apr 2026, 09:15', 'product' => 'SR12 Facial Wash', 'type' => 'Penjualan', 'qty' => '-2', 'after' => '43', 'note' => 'Order #ORD-2024-006', 'color' => 'text-red-500'],
                        ['date' => '17 Apr 2026, 14:30', 'product' => 'SR12 Suncare Lotion', 'type' => 'Tambah Stok', 'qty' => '+20', 'after' => '28', 'note' => 'Restock dari supplier', 'color' => 'text-green-600'],
                        ['date' => '17 Apr 2026, 10:00', 'product' => 'SR12 Lip Care Cherry', 'type' => 'Penjualan', 'qty' => '-1', 'after' => '4', 'note' => 'Order #ORD-2024-005', 'color' => 'text-red-500'],
                        ['date' => '16 Apr 2026, 16:45', 'product' => 'SR12 Acne Peel', 'type' => 'Kurangi Stok', 'qty' => '-3', 'after' => '19', 'note' => 'Produk kedaluwarsa', 'color' => 'text-amber-500'],
                        ['date' => '16 Apr 2026, 09:00', 'product' => 'SR12 Lightening Lotion', 'type' => 'Tambah Stok', 'qty' => '+10', 'after' => '25', 'note' => 'Restock dari supplier', 'color' => 'text-green-600'],
                        ['date' => '15 Apr 2026, 11:20', 'product' => 'SR12 Facial Wash', 'type' => 'Penjualan', 'qty' => '-5', 'after' => '45', 'note' => 'Order #ORD-2024-003', 'color' => 'text-red-500'],
                        ['date' => '15 Apr 2026, 08:00', 'product' => 'SR12 Suncare Lotion', 'type' => 'Kurangi Stok', 'qty' => '-2', 'after' => '8', 'note' => 'Koreksi stok manual', 'color' => 'text-amber-500'],
                        ['date' => '14 Apr 2026, 15:30', 'product' => 'SR12 Lip Care Cherry', 'type' => 'Penjualan', 'qty' => '-3', 'after' => '5', 'note' => 'Order #ORD-2024-002', 'color' => 'text-red-500'],
                    ] as $log)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="py-2.5 px-2 text-xs text-neutral-500 whitespace-nowrap">{{ $log['date'] }}</td>
                        <td class="py-2.5 px-2 text-xs font-medium text-neutral-800">{{ $log['product'] }}</td>
                        <td class="py-2.5 px-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                                {{ $log['type'] === 'Tambah Stok' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log['type'] === 'Kurangi Stok' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $log['type'] === 'Penjualan' ? 'bg-red-100 text-red-700' : '' }}
                            ">{{ $log['type'] }}</span>
                        </td>
                        <td class="py-2.5 px-2 text-xs font-bold {{ $log['color'] }}">{{ $log['qty'] }}</td>
                        <td class="py-2.5 px-2 text-xs font-semibold text-neutral-700">{{ $log['after'] }} pcs</td>
                        <td class="py-2.5 px-2 text-xs text-neutral-500">{{ $log['note'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-3 gap-3 mt-5">
            <div class="p-3 bg-green-50 rounded-xl text-center">
                <p class="text-lg font-bold text-green-700">+30</p>
                <p class="text-[10px] text-green-600 font-medium">Total Masuk</p>
            </div>
            <div class="p-3 bg-red-50 rounded-xl text-center">
                <p class="text-lg font-bold text-red-600">-16</p>
                <p class="text-[10px] text-red-500 font-medium">Total Keluar</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl text-center">
                <p class="text-lg font-bold text-blue-700">8</p>
                <p class="text-[10px] text-blue-600 font-medium">Transaksi</p>
            </div>
        </div>
    </div>
</div>

</div>{{-- end Alpine wrapper --}}
@endsection
