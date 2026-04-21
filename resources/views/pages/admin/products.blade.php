@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('page_title', 'Kelola Produk')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-neutral-800 font-sans">Daftar Produk</h2>
            <button type="button" onclick="openProductModal()" class="btn-primary text-sm">Tambah Produk</button>
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
                    @foreach($products as $product)
                    <tr>
                        <td class="py-3 px-2">
                            <p class="font-medium text-neutral-800">{{ $product->name }}</p>
                            <p class="text-xs text-neutral-400">{{ $product->slug }}</p>
                        </td>
                        <td class="py-3 px-2 text-neutral-600">{{ $product->category?->name ?? '-' }}</td>
                        <td class="py-3 px-2 text-neutral-700">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-neutral-700">{{ $product->stock }}</td>
                        <td class="py-3 px-2">
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-warning' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick='openProductModal({{ json_encode($product) }})' class="px-2 py-1 text-xs border border-neutral-300 rounded-lg hover:bg-neutral-50">Edit</button>
                                <form method="POST" action="/admin/produk/{{ $product->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                                    <input type="hidden" name="name" value="{{ $product->name }}">
                                    <input type="hidden" name="price" value="{{ (int) $product->price }}">
                                    <input type="hidden" name="stock" value="{{ $product->stock + 1 }}">
                                    <input type="hidden" name="description" value="{{ $product->description }}">
                                    <input type="hidden" name="image_url" value="{{ $product->image_url }}">
                                    <button class="px-2 py-1 text-xs border border-neutral-300 rounded-lg hover:bg-neutral-50">+ Stok</button>
                                </form>
                                <form method="POST" action="/admin/produk/{{ $product->id }}/toggle-status">
                                    @csrf
                                    <button class="px-2 py-1 text-xs border border-primary-300 text-primary-700 rounded-lg hover:bg-primary-50">Toggle</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Kategori Produk</h2>
        <div class="space-y-2">
            @foreach($categories as $cat)
                <div class="p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                    <p class="text-sm font-medium text-neutral-800">{{ $cat->name }}</p>
                    <p class="text-xs text-neutral-500">{{ $cat->products_count ?? 0 }} produk</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Product Modal --}}
<div id="product-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-neutral-200 p-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-neutral-800" id="modal-title">Tambah Produk</h3>
            <button type="button" onclick="closeProductModal()" class="text-neutral-400 hover:text-neutral-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="product-form" method="POST" action="/admin/produk" class="p-4 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="product_id" id="product_id">

            <div>
                <label class="text-xs font-semibold text-neutral-600">Nama Produk</label>
                <input type="text" name="name" id="product_name" class="input-field mt-1" required>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Kategori</label>
                <select name="category_id" id="product_category" class="input-field mt-1" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-semibold text-neutral-600">Harga</label>
                    <input type="number" name="price" id="product_price" class="input-field mt-1" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-neutral-600">Stok</label>
                    <input type="number" name="stock" id="product_stock" class="input-field mt-1" required>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Image URL</label>
                <input type="url" name="image_url" id="product_image_url" class="input-field mt-1">
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Deskripsi</label>
                <textarea name="description" id="product_description" rows="3" class="input-field mt-1"></textarea>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit" class="btn-primary flex-1 text-sm">Simpan</button>
                <button type="button" onclick="closeProductModal()" class="btn-outline flex-1 text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openProductModal(product = null) {
    const modal = document.getElementById('product-modal');
    const form = document.getElementById('product-form');
    const title = document.getElementById('modal-title');
    const method = document.getElementById('form-method');
    const productId = document.getElementById('product_id');

    if (product) {
        // Edit mode
        title.textContent = 'Edit Produk';
        method.value = 'PATCH';
        productId.value = product.id;
        form.action = `/admin/produk/${product.id}`;
        document.getElementById('product_name').value = product.name;
        document.getElementById('product_category').value = product.category_id;
        document.getElementById('product_price').value = product.price;
        document.getElementById('product_stock').value = product.stock;
        document.getElementById('product_image_url').value = product.image_url || '';
        document.getElementById('product_description').value = product.description || '';
    } else {
        // Add mode
        title.textContent = 'Tambah Produk';
        method.value = 'POST';
        productId.value = '';
        form.action = '/admin/produk';
        form.reset();
    }

    modal.classList.remove('hidden');
}

function closeProductModal() {
    document.getElementById('product-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('product-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeProductModal();
});
</script>
@endsection
