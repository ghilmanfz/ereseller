{{-- Product Card Component --}}
{{-- Usage: @include('components.product-card', ['product' => $product]) --}}

@php
$product = $product ?? [
    'name' => 'Produk SR12',
    'category' => 'Skincare',
    'price' => 85000,
    'image' => null,
    'rating' => 4.8,
    'stock' => 24,
    'stock_status' => 'In-Stock', // In-Stock, Limited, Out-of-Stock
    'slug' => 'produk-sr12',
    'description' => '',
];

$stockColors = [
    'In-Stock' => 'bg-green-500',
    'Limited' => 'bg-amber-500',
    'Out-of-Stock' => 'bg-red-500',
];

$categoryColors = [
    'Skincare' => 'text-primary-600',
    'Bodycare' => 'text-primary-600',
    'Herbal' => 'text-green-600',
    'Cosmetic' => 'text-purple-600',
    'Health' => 'text-blue-600',
];
@endphp

<div class="card group overflow-hidden">
    {{-- Image --}}
    <div class="relative aspect-[4/5] bg-neutral-100 overflow-hidden">
        @if($product['image'])
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
                <svg class="w-16 h-16 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/>
                </svg>
            </div>
        @endif

        {{-- Stock Status Badge --}}
        <span class="absolute top-3 left-3 badge text-white text-[10px] {{ $stockColors[$product['stock_status']] ?? 'bg-green-500' }}">
            {{ $product['stock_status'] }}
        </span>

        {{-- Rating Badge --}}
        <div class="absolute top-3 right-3 flex items-center gap-1 bg-white/90 backdrop-blur-sm rounded-lg px-2 py-1 shadow-sm">
            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
            </svg>
            <span class="text-xs font-semibold text-neutral-700">{{ $product['rating'] }}</span>
        </div>
    </div>

    {{-- Info --}}
    <div class="p-4">
        <span class="text-[11px] font-bold uppercase tracking-wider {{ $categoryColors[$product['category']] ?? 'text-primary-600' }}">
            {{ $product['category'] }}
        </span>
        <h3 class="mt-1 font-sans text-sm font-semibold text-neutral-800 leading-snug line-clamp-1">
            {{ $product['name'] }}
        </h3>
        @if(!empty($product['description']))
        <p class="mt-1 text-xs text-neutral-400 line-clamp-2">{{ $product['description'] }}</p>
        @endif

        <div class="mt-3 flex items-end justify-between">
            <div>
                <p class="text-base font-bold text-primary-600">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                @if($product['stock_status'] !== 'Out-of-Stock')
                <p class="text-[11px] text-neutral-400 mt-0.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                    Stok Sisa: {{ $product['stock'] }} pcs
                </p>
                @else
                <p class="text-[11px] text-red-500 mt-0.5 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    Stok Habis
                </p>
                @endif
            </div>
        </div>

        {{-- Add to Cart Button --}}
        <a href="/produk/{{ $product['slug'] }}"
           class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-xs font-semibold rounded-lg hover:bg-primary-700 transition-all duration-200 {{ $product['stock_status'] === 'Out-of-Stock' ? 'opacity-50 pointer-events-none' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
            </svg>
            Tambah ke Keranjang
        </a>
    </div>
</div>
