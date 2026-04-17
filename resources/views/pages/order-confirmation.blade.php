@extends('layouts.app')
@section('title', 'Konfirmasi Pesanan')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Success Header --}}
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-primary-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="text-2xl font-serif font-bold text-neutral-900">Pesanan Anda Telah Diterima!</h1>
        <p class="mt-2 text-sm text-neutral-500">Silakan lakukan pembayaran agar pesanan Anda<br>dapat segera kami proses dan kirimkan.</p>
        <div class="mt-4 inline-flex items-center px-4 py-2 border border-primary-200 rounded-full bg-primary-50">
            <span class="text-sm font-semibold text-primary-700">ID PESANAN: SR12-240508-882I</span>
        </div>
    </div>

    {{-- Payment Instructions --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-neutral-800 font-sans">Instruksi Pembayaran</h2>
            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
        </div>
        <p class="text-sm text-neutral-500 mb-4">Pilih salah satu rekening di bawah ini</p>

        <div class="space-y-3">
            @foreach([
                ['bank' => 'BANK CENTRAL ASIA (BCA)', 'number' => '8420-112-998', 'name' => 'a.n. SINTIA SR12 DISTRIBUTOR', 'color' => 'bg-blue-600'],
                ['bank' => 'BANK MANDIRI', 'number' => '133-00-1234567-8', 'name' => 'a.n. SINTIA SR12 DISTRIBUTOR', 'color' => 'bg-yellow-500'],
            ] as $account)
            <div class="flex items-center justify-between p-4 border border-neutral-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $account['color'] }} rounded-lg flex items-center justify-center text-white text-xs font-bold">{{ substr($account['bank'], 0, 1) }}</div>
                    <div>
                        <p class="text-xs text-neutral-400 uppercase font-medium">{{ $account['bank'] }}</p>
                        <p class="text-lg font-bold text-neutral-800 tracking-wider">{{ $account['number'] }}</p>
                        <p class="text-xs text-neutral-500">{{ $account['name'] }}</p>
                    </div>
                </div>
                <button class="flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                    Salin
                </button>
            </div>
            @endforeach
        </div>

        {{-- Warning --}}
        <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <p class="text-sm font-bold text-amber-700 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                Penting Sebelum Transfer
            </p>
            <ul class="mt-2 space-y-1 text-sm text-amber-800">
                <li class="flex items-start gap-2"><span>•</span> Pastikan nominal transfer sesuai hingga <strong>3 digit terakhir</strong> (jika ada).</li>
                <li class="flex items-start gap-2"><span>•</span> Pembayaran harus dilakukan dalam waktu <strong>24 jam</strong> sebelum pesanan otomatis dibatalkan.</li>
                <li class="flex items-start gap-2"><span>•</span> Simpan bukti transfer Anda untuk berjaga-jaga jika diperlukan di kemudian hari.</li>
            </ul>
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="card p-6 mb-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Ringkasan Pesanan</h2>
        <div class="divide-y divide-neutral-100">
            @foreach([
                ['name' => 'Salimah Slim SR12 Herbal', 'qty' => 2, 'price' => 95000, 'total' => 190000],
                ['name' => 'Susu Kambing Gomilku Original', 'qty' => 1, 'price' => 147000, 'total' => 147000],
            ] as $item)
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-neutral-800">{{ $item['name'] }}</p>
                        <p class="text-xs text-neutral-400">{{ $item['qty'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-primary-600">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="mt-3 pt-3 border-t border-neutral-200 space-y-2 text-sm">
            <div class="flex justify-between text-neutral-500"><span>Subtotal Produk</span><span>Rp 337.000</span></div>
            <div class="flex justify-between text-neutral-500"><span>Biaya Pengiriman</span><span>Rp 15.000</span></div>
            <div class="flex justify-between font-bold text-neutral-800 text-lg pt-2 border-t border-neutral-200"><span>Total Pembayaran</span><span class="text-primary-600">Rp 352.000</span></div>
        </div>
    </div>

    {{-- Verification Info --}}
    <div class="card p-5 mb-6 flex items-start gap-3 bg-neutral-50">
        <svg class="w-5 h-5 text-neutral-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-bold text-neutral-800">Verifikasi Manual Admin</p>
            <p class="text-xs text-neutral-500 mt-1">Setelah Anda menekan tombol konfirmasi, Admin kami akan melakukan pengecekan mutasi bank secara manual. Proses ini biasanya memakan waktu <strong>10-30 menit</strong> pada jam operasional (08.00 - 17.00).</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <button class="btn-primary w-full !py-4 text-base">Saya Sudah Membayar</button>
    <a href="/katalog" class="flex items-center justify-center gap-2 mt-4 text-sm font-medium text-neutral-500 hover:text-primary-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke Katalog
    </a>
</div>
@endsection
