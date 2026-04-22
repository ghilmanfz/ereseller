@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('page_title', 'Kelola Produk')

@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-neutral-800 font-sans">Daftar Produk</h2>
            @if(auth()->user()->role === 'admin')
            <button type="button" onclick="openProductModal()" class="btn-primary text-sm">Tambah Produk</button>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Foto</th>
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
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover border border-neutral-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-neutral-100 flex items-center justify-center border border-neutral-200">
                                    <svg class="w-6 h-6 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-medium text-neutral-800">{{ $product->name }}</p>
                            <p class="text-xs text-neutral-400">{{ $product->slug }}</p>
                            @if($product->description)
                                <p class="text-xs text-neutral-500 mt-0.5 line-clamp-1 max-w-xs">{{ $product->description }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-neutral-600">{{ $product->category?->name ?? '-' }}</td>
                        <td class="py-3 px-2 text-neutral-700">Rp {{ number_format((int) $product->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-2 text-neutral-700">{{ $product->stock }}</td>
                        <td class="py-3 px-2">
                            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-warning' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="py-3 px-2">
                            @if(auth()->user()->role === 'admin')
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
                            @else
                            <span class="text-xs text-neutral-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>

{{-- Product Modal --}}
<div id="product-modal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-neutral-200 p-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-neutral-800" id="modal-title">Tambah Produk</h3>
            <button type="button" onclick="closeProductModal()" class="text-neutral-400 hover:text-neutral-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="product-form" method="POST" action="/admin/produk" class="p-4 space-y-4" enctype="multipart/form-data">
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
                <label class="text-xs font-semibold text-neutral-600">Foto Produk</label>
                <div class="mt-1 space-y-2">
                    <div id="image-preview-container" class="hidden">
                        <img id="image-preview" src="" alt="Preview" class="w-full h-40 object-cover rounded-lg border border-neutral-200">
                        <p class="text-[11px] text-neutral-400 mt-1">Foto saat ini. Upload foto baru untuk mengganti.</p>
                    </div>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-neutral-300 rounded-lg cursor-pointer hover:bg-neutral-50 transition-colors">
                        <svg class="w-8 h-8 text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        <span class="text-xs text-neutral-500">Klik untuk upload foto</span>
                        <span class="text-[11px] text-neutral-400 mt-1">JPG, PNG, WEBP (maks. 2MB)</span>
                        <input type="file" name="image" id="product_image" accept="image/*" class="hidden" onchange="previewNewImage(this)">
                    </label>
                    <div id="image-upload-feedback" class="hidden text-xs rounded-lg px-3 py-2"></div>
                    <div id="new-image-preview-container" class="hidden">
                        <img id="new-image-preview" src="" alt="Preview baru" class="w-full h-40 object-cover rounded-lg border border-primary-200">
                        <p class="text-[11px] text-primary-600 mt-1">Foto baru yang akan diupload.</p>
                    </div>
                </div>
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

    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');
    const newPreviewContainer = document.getElementById('new-image-preview-container');
    const feedback = document.getElementById('image-upload-feedback');
    const fileInput = document.getElementById('product_image');

    fileInput.value = '';
    newPreviewContainer.classList.add('hidden');
    feedback.className = 'hidden text-xs rounded-lg px-3 py-2';
    feedback.textContent = '';

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
        document.getElementById('product_description').value = product.description || '';
        if (product.image_url) {
            preview.src = product.image_url;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }
    } else {
        // Add mode
        title.textContent = 'Tambah Produk';
        method.value = 'POST';
        productId.value = '';
        form.action = '/admin/produk';
        form.reset();
        previewContainer.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProductModal() {
    const modal = document.getElementById('product-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function previewNewImage(input) {
    const newContainer = document.getElementById('new-image-preview-container');
    const newPrev = document.getElementById('new-image-preview');
    const feedback = document.getElementById('image-upload-feedback');

    feedback.className = 'hidden text-xs rounded-lg px-3 py-2';
    feedback.textContent = '';

    if (!input.files || !input.files[0]) {
        newContainer.classList.add('hidden');
        return;
    }

    const file = input.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    const maxSize = 2 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
        newContainer.classList.add('hidden');
        input.value = '';
        feedback.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        feedback.textContent = 'Gagal memuat gambar: format file tidak didukung.';
        return;
    }

    if (file.size > maxSize) {
        newContainer.classList.add('hidden');
        input.value = '';
        feedback.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        feedback.textContent = 'Gagal memuat gambar: ukuran file melebihi 2MB.';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        newPrev.src = e.target.result;
        newContainer.classList.remove('hidden');
        feedback.className = 'text-xs rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
        feedback.textContent = `Gambar berhasil dimuat: ${file.name}`;
    };
    reader.onerror = () => {
        newContainer.classList.add('hidden');
        input.value = '';
        feedback.className = 'text-xs rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        feedback.textContent = 'Gagal memuat gambar. Silakan coba lagi.';
    };
    reader.readAsDataURL(file);
}

// Close modal when clicking outside
document.getElementById('product-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeProductModal();
});
</script>
@endsection
