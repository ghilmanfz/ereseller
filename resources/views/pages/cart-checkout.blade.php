@extends('layouts.app')
@section('title', 'Keranjang Belanja')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ shipping: 'delivery', payment: 'transfer' }">
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Cart Items --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-serif font-bold text-neutral-900">Keranjang Belanja</h1>
                        <p class="text-sm text-neutral-500 mt-0.5">Anda memiliki 3 item dalam keranjang</p>
                    </div>
                    <a href="/katalog" class="text-sm font-semibold text-primary-600 hover:text-primary-700">+ Tambah Produk</a>
                </div>

                <div class="divide-y divide-neutral-100">
                    @php
                    $cartItems = [
                        ['name' => 'SR12 Facial Wash Honey', 'category' => 'Skincare', 'price' => 65000, 'qty' => 2, 'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&h=100&fit=crop'],
                        ['name' => 'SR12 Brightening Day Cream', 'category' => 'Skincare', 'price' => 125000, 'qty' => 1, 'image' => 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?w=100&h=100&fit=crop'],
                        ['name' => 'SR12 Lip Care Cherry', 'category' => 'Bodycare', 'price' => 25000, 'qty' => 3, 'image' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=100&h=100&fit=crop'],
                    ];
                    @endphp
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4 py-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-neutral-100 shrink-0">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="badge-primary text-[10px]">{{ $item['category'] }}</span>
                            <h3 class="text-sm font-semibold text-neutral-800 mt-0.5">{{ $item['name'] }}</h3>
                            <p class="text-sm font-bold text-primary-600 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 rounded-lg border border-neutral-300 flex items-center justify-center text-neutral-600 hover:bg-neutral-50 text-sm">−</button>
                            <span class="w-8 text-center text-sm font-semibold">{{ $item['qty'] }}</span>
                            <button class="w-8 h-8 rounded-lg border border-neutral-300 flex items-center justify-center text-neutral-600 hover:bg-neutral-50 text-sm">+</button>
                        </div>
                        <button class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Options --}}
            <div class="card p-6">
                <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Opsi Pengiriman</h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    <button @click="shipping = 'pickup'" :class="shipping === 'pickup' ? 'border-primary-500 bg-primary-50' : 'border-neutral-200 hover:border-neutral-300'" class="flex items-start gap-3 p-4 rounded-xl border-2 transition-all text-left">
                        <svg class="w-5 h-5 text-neutral-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-neutral-800">Ambil di Toko</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Ambil langsung di Toko SR12 Parungpanjang. Gratis biaya pengiriman.</p>
                        </div>
                    </button>
                    <button @click="shipping = 'delivery'" :class="shipping === 'delivery' ? 'border-primary-500 bg-primary-50' : 'border-neutral-200 hover:border-neutral-300'" class="flex items-start gap-3 p-4 rounded-xl border-2 transition-all text-left">
                        <svg class="w-5 h-5 text-primary-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-neutral-800">Kirim ke Alamat</p>
                            <p class="text-xs text-neutral-500 mt-0.5">Kurir kami akan mengantar sampai depan rumah Anda (Khusus area Parungpanjang).</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Recipient Info --}}
            <div class="card p-6">
                <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Informasi Penerima</h2>
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" class="input-field" placeholder="Contoh: Siti Aminah">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor WhatsApp</label>
                        <input type="tel" class="input-field" placeholder="08123456789">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Alamat Lengkap</label>
                    <textarea rows="3" class="input-field resize-none" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, dan patokan terdekat..."></textarea>
                </div>
            </div>

            {{-- Payment --}}
            <div class="card p-6">
                <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Pilih Pembayaran</h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    <button @click="payment = 'transfer'" :class="payment === 'transfer' ? 'border-primary-500 bg-primary-50' : 'border-neutral-200 hover:border-neutral-300'" class="flex flex-col items-center gap-2 p-5 rounded-xl border-2 transition-all">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        <span class="text-sm font-semibold text-neutral-800">Transfer Bank</span>
                    </button>
                    <button @click="payment = 'cod'" :class="payment === 'cod' ? 'border-primary-500 bg-primary-50' : 'border-neutral-200 hover:border-neutral-300'" class="flex flex-col items-center gap-2 p-5 rounded-xl border-2 transition-all">
                        <svg class="w-8 h-8 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        <span class="text-sm font-semibold text-neutral-800">Bayar di Tempat (COD)</span>
                    </button>
                </div>
                <div class="mt-4 flex items-start gap-2 p-3 bg-blue-50 rounded-xl">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    <p class="text-xs text-blue-700">Setelah melakukan pesanan, Anda perlu mentransfer ke rekening yang tertera di halaman konfirmasi dan menekan tombol konfirmasi pembayaran.</p>
                </div>
            </div>
        </div>

        {{-- Order Summary Sidebar --}}
        <div class="lg:col-span-1">
            <div class="card p-6 sticky top-24">
                <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-neutral-600"><span>Subtotal (3 Produk)</span><span class="font-medium">Rp 330.000</span></div>
                    <div class="flex justify-between text-neutral-600"><span>Biaya Pengiriman</span><span class="font-medium">Rp 15.000</span></div>
                    <hr class="border-neutral-200">
                    <div class="flex justify-between font-bold text-neutral-800"><span>Total Tagihan</span><span class="text-xl text-primary-600">Rp 345.000</span></div>
                </div>
                <a href="/pesanan/SR12-240508-882I/konfirmasi" class="btn-primary w-full mt-6 !py-3.5">Buat Pesanan Sekarang</a>
                <p class="text-[11px] text-neutral-400 text-center mt-3">Dengan mengklik tombol di atas, Anda menyetujui Syarat & Ketentuan TOKO SKINCARE SINTIA DISTRIBUTOR SR12.</p>

                {{-- Promo Code --}}
                <div class="mt-6 pt-5 border-t border-neutral-200">
                    <p class="text-xs font-bold text-neutral-600 uppercase mb-2">Punya Kode Promo?</p>
                    <div class="flex gap-2">
                        <input type="text" class="input-field !py-2 text-sm flex-1" placeholder="Kode Voucher">
                        <button class="px-4 py-2 border border-primary-500 text-primary-600 text-sm font-semibold rounded-xl hover:bg-primary-50 transition-colors">Terapkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
