@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan')

@section('content')
@php
    $oldFeaturedProductIds = old('featured_product_ids', $settings['featured_product_ids'] ?? '');
    $selectedFeaturedProductIds = collect(is_array($oldFeaturedProductIds)
        ? $oldFeaturedProductIds
        : explode(',', (string) $oldFeaturedProductIds))
        ->map(fn ($id) => (string) trim((string) $id))
        ->filter()
        ->all();
    $featuredMode = old('featured_products_mode', $settings['featured_products_mode'] ?? 'default');
@endphp

<div class="max-w-3xl">
    {{-- Success Toast --}}
    @if(session('success'))
        <div id="success-toast" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">&times;</button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('success-toast');
                if (toast) toast.remove();
            }, 4000);
        </script>
    @endif

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Pengaturan Toko
            @if(auth()->user()->role === 'owner')
            <span class="ml-2 text-xs font-normal text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Read Only</span>
            @endif
        </h2>
        <form id="settings-form" method="POST" action="/admin/pengaturan" enctype="multipart/form-data" class="space-y-5" @if(auth()->user()->role === 'owner') onsubmit="return false;" @endif>
            @csrf

            {{-- Store Name --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Toko</label>
                <input
                    type="text"
                    name="store_name"
                    value="{{ old('store_name', $settings['store_name']) }}"
                    class="input-field {{ $errors->has('store_name') ? 'border-red-500' : '' }}"
                    required>
                @if($errors->has('store_name'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('store_name') }}</p>
                @endif
            </div>

            {{-- Store WhatsApp --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">WhatsApp Toko</label>
                <input
                    type="text"
                    name="store_whatsapp"
                    value="{{ old('store_whatsapp', $settings['store_whatsapp']) }}"
                    class="input-field {{ $errors->has('store_whatsapp') ? 'border-red-500' : '' }}"
                    placeholder="Contoh: 082233334444"
                    required>
                @if($errors->has('store_whatsapp'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('store_whatsapp') }}</p>
                @endif
            </div>

            {{-- Pickup Address --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Alamat Pickup</label>
                <textarea
                    name="pickup_address"
                    rows="3"
                    class="input-field {{ $errors->has('pickup_address') ? 'border-red-500' : '' }}"
                    placeholder="Alamat toko untuk pickup pesanan"
                    required>{{ old('pickup_address', $settings['pickup_address']) }}</textarea>
                @if($errors->has('pickup_address'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('pickup_address') }}</p>
                @endif
            </div>

            {{-- Branding Section --}}
            <div class="pt-2 border-t border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-700 mb-4 mt-4">Branding</h3>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Logo Toko</label>
                    @if(! empty($settings['store_logo']))
                        <img src="{{ $settings['store_logo'] }}" alt="Logo toko saat ini" class="mb-3 w-16 h-16 rounded-xl object-cover border border-neutral-200">
                    @endif
                    <input
                        type="file"
                        name="store_logo"
                        accept="image/*"
                        class="input-field {{ $errors->has('store_logo') ? 'border-red-500' : '' }}">
                    <p class="text-xs text-neutral-500 mt-1.5">Unggah logo persegi agar tampil rapi di navbar, sidebar admin, dan footer. Maksimal 2 MB.</p>
                    @if($errors->has('store_logo'))
                        <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('store_logo') }}</p>
                    @endif
                </div>
            </div>

            {{-- Pickup Reminder Template --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Template Reminder Pickup</label>
                <textarea
                    name="pickup_reminder_template"
                    rows="4"
                    class="input-field {{ $errors->has('pickup_reminder_template') ? 'border-red-500' : '' }}"
                    placeholder="Pesan yang akan dikirim saat mengingatkan customer untuk pickup"
                    required>{{ old('pickup_reminder_template', $settings['pickup_reminder_template']) }}</textarea>
                <p class="text-xs text-neutral-500 mt-1.5">Gunakan {order_code} dan {customer_name} sebagai placeholder</p>
                @if($errors->has('pickup_reminder_template'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('pickup_reminder_template') }}</p>
                @endif
            </div>

            {{-- Landing Content Section --}}
            <div class="pt-2 border-t border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-700 mb-4 mt-4">Konten Landing Page</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Badge Hero</label>
                        <input type="text" name="landing_hero_badge" value="{{ old('landing_hero_badge', $settings['landing_hero_badge']) }}" class="input-field {{ $errors->has('landing_hero_badge') ? 'border-red-500' : '' }}">
                        @if($errors->has('landing_hero_badge'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_hero_badge') }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Judul Hero</label>
                        <input type="text" name="landing_hero_title" value="{{ old('landing_hero_title', $settings['landing_hero_title']) }}" class="input-field {{ $errors->has('landing_hero_title') ? 'border-red-500' : '' }}">
                        @if($errors->has('landing_hero_title'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_hero_title') }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Highlight Hero</label>
                        <input type="text" name="landing_hero_highlight" value="{{ old('landing_hero_highlight', $settings['landing_hero_highlight']) }}" class="input-field {{ $errors->has('landing_hero_highlight') ? 'border-red-500' : '' }}">
                        @if($errors->has('landing_hero_highlight'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_hero_highlight') }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Deskripsi Hero</label>
                        <textarea name="landing_hero_description" rows="3" class="input-field {{ $errors->has('landing_hero_description') ? 'border-red-500' : '' }}">{{ old('landing_hero_description', $settings['landing_hero_description']) }}</textarea>
                        @if($errors->has('landing_hero_description'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_hero_description') }}</p>@endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Teks Tombol Utama</label>
                            <input type="text" name="landing_primary_button_text" value="{{ old('landing_primary_button_text', $settings['landing_primary_button_text']) }}" class="input-field {{ $errors->has('landing_primary_button_text') ? 'border-red-500' : '' }}">
                            @if($errors->has('landing_primary_button_text'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_primary_button_text') }}</p>@endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Teks Tombol Kedua</label>
                            <input type="text" name="landing_secondary_button_text" value="{{ old('landing_secondary_button_text', $settings['landing_secondary_button_text']) }}" class="input-field {{ $errors->has('landing_secondary_button_text') ? 'border-red-500' : '' }}">
                            @if($errors->has('landing_secondary_button_text'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_secondary_button_text') }}</p>@endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Gambar Hero Landing</label>
                        @if(! empty($settings['landing_hero_image']))
                            <img src="{{ $settings['landing_hero_image'] }}" alt="Gambar hero landing saat ini" class="mb-3 w-32 h-20 rounded-xl object-cover border border-neutral-200">
                        @endif
                        <input type="file" name="landing_hero_image" accept="image/*" class="input-field {{ $errors->has('landing_hero_image') ? 'border-red-500' : '' }}">
                        <p class="text-xs text-neutral-500 mt-1.5">Gunakan gambar vertikal atau katalog produk yang jelas. Maksimal 2 MB.</p>
                        @if($errors->has('landing_hero_image'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_hero_image') }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Judul CTA</label>
                        <input type="text" name="landing_cta_title" value="{{ old('landing_cta_title', $settings['landing_cta_title']) }}" class="input-field {{ $errors->has('landing_cta_title') ? 'border-red-500' : '' }}">
                        @if($errors->has('landing_cta_title'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_cta_title') }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Deskripsi CTA</label>
                        <textarea name="landing_cta_description" rows="3" class="input-field {{ $errors->has('landing_cta_description') ? 'border-red-500' : '' }}">{{ old('landing_cta_description', $settings['landing_cta_description']) }}</textarea>
                        @if($errors->has('landing_cta_description'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_cta_description') }}</p>@endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Teks Tombol CTA Utama</label>
                            <input type="text" name="landing_cta_primary_button_text" value="{{ old('landing_cta_primary_button_text', $settings['landing_cta_primary_button_text']) }}" class="input-field {{ $errors->has('landing_cta_primary_button_text') ? 'border-red-500' : '' }}">
                            @if($errors->has('landing_cta_primary_button_text'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_cta_primary_button_text') }}</p>@endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Teks Tombol CTA Kedua</label>
                            <input type="text" name="landing_cta_secondary_button_text" value="{{ old('landing_cta_secondary_button_text', $settings['landing_cta_secondary_button_text']) }}" class="input-field {{ $errors->has('landing_cta_secondary_button_text') ? 'border-red-500' : '' }}">
                            @if($errors->has('landing_cta_secondary_button_text'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('landing_cta_secondary_button_text') }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Products Section --}}
            <div class="pt-2 border-t border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-700 mb-4 mt-4">Produk Terlaris Landing Page</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Mode Produk</label>
                        <select name="featured_products_mode" class="input-field {{ $errors->has('featured_products_mode') ? 'border-red-500' : '' }}">
                            <option value="default" @selected($featuredMode === 'default')>Default - otomatis berdasarkan rating</option>
                            <option value="manual" @selected($featuredMode === 'manual')>Manual - pilih produk sendiri</option>
                        </select>
                        @if($errors->has('featured_products_mode'))<p class="text-xs text-red-600 mt-1.5">{{ $errors->first('featured_products_mode') }}</p>@endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs text-neutral-500">Pilih maksimal 4 produk aktif. Jika mode manual aktif tetapi pilihan kosong, landing page memakai produk default.</p>
                        @forelse($activeProducts as $product)
                            <label class="flex items-start gap-3 p-3 border border-neutral-200 rounded-xl hover:bg-neutral-50">
                                <input
                                    type="checkbox"
                                    name="featured_product_ids[]"
                                    value="{{ $product->id }}"
                                    class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
                                    @checked(in_array((string) $product->id, $selectedFeaturedProductIds, true))>
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold text-neutral-800">{{ $product->name }}</span>
                                    <span class="block text-xs text-neutral-500">Stok: {{ $product->stock }} &middot; Rp {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-neutral-500 p-3 border border-dashed border-neutral-200 rounded-xl">Belum ada produk aktif untuk dipilih.</p>
                        @endforelse
                        @if($errors->has('featured_product_ids'))
                            <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('featured_product_ids') }}</p>
                        @endif
                        @if($errors->has('featured_product_ids.*'))
                            <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('featured_product_ids.*') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Methods Section --}}
            <div class="pt-2 border-t border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-700 mb-4 mt-4">Data Pembayaran</h3>
            </div>

            {{-- Bank Account Number --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">No. Rekening Bank</label>
                <input
                    type="text"
                    name="bank_account_number"
                    value="{{ old('bank_account_number', $settings['bank_account_number']) }}"
                    class="input-field {{ $errors->has('bank_account_number') ? 'border-red-500' : '' }}"
                    placeholder="Contoh: 1234567890 atau atas nama Bank">
                @if($errors->has('bank_account_number'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('bank_account_number') }}</p>
                @endif
            </div>

            {{-- E-Wallet Account --}}
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor E-Wallet (OVO/GoPay/Dana)</label>
                <input
                    type="text"
                    name="ewallet_number"
                    value="{{ old('ewallet_number', $settings['ewallet_number']) }}"
                    class="input-field {{ $errors->has('ewallet_number') ? 'border-red-500' : '' }}"
                    placeholder="Contoh: 081234567890">
                @if($errors->has('ewallet_number'))
                    <p class="text-xs text-red-600 mt-1.5">{{ $errors->first('ewallet_number') }}</p>
                @endif
            </div>

            {{-- Form Actions --}}
            @if(auth()->user()->role === 'admin')
            <div class="flex gap-2 pt-4 border-t border-neutral-200">
                <button type="submit" class="btn-primary">
                    <span id="submit-text">Simpan Pengaturan</span>
                </button>
                <button type="reset" class="btn-outline">Reset</button>
            </div>
            @else
            <div class="flex gap-2 pt-4 border-t border-neutral-200">
                <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Anda hanya memiliki akses baca. Pengaturan tidak dapat diubah.
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
// Track unsaved changes
const form = document.getElementById('settings-form');
let isFormDirty = false;

form?.addEventListener('change', () => {
    isFormDirty = true;
});

form?.addEventListener('submit', () => {
    isFormDirty = false;
});

// Warn on unsaved changes
window.addEventListener('beforeunload', (e) => {
    if (isFormDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Submit feedback
form?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submit-text');
    const originalText = 'Simpan Pengaturan';

    if (! submitBtn || ! submitText) {
        return;
    }

    submitBtn.disabled = true;
    submitText.textContent = 'Menyimpan...';

    setTimeout(() => {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    }, 2000);
});
</script>
@endsection
