@extends('layouts.admin')
@section('title', 'Kelola Kategori')
@section('page_title', 'Kelola Kategori')

@section('content')
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Daftar Kategori --}}
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-neutral-800 font-sans">Daftar Kategori</h2>
            <span class="text-xs text-neutral-500">{{ $categories->count() }} kategori</span>
        </div>

        @if($categories->isEmpty())
        <div class="text-center py-10 text-neutral-400 text-sm">Belum ada kategori. Tambahkan kategori pertama.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Nama</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Slug</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Jumlah Produk</th>
                        @if(auth()->user()->role === 'admin')
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($categories as $category)
                    <tr class="hover:bg-neutral-50">
                        <td class="py-3 px-2 font-medium text-neutral-800">{{ $category->name }}</td>
                        <td class="py-3 px-2 text-xs text-neutral-400 font-mono">{{ $category->slug }}</td>
                        <td class="py-3 px-2">
                            <span class="badge bg-primary-50 text-primary-700">{{ $category->products_count }} produk</span>
                        </td>
                        @if(auth()->user()->role === 'admin')
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                    class="px-2 py-1 text-xs border border-neutral-300 rounded-lg hover:bg-neutral-50">
                                    Edit
                                </button>
                                @if($category->products_count === 0)
                                <form method="POST" action="/admin/kategori/{{ $category->id }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-xs border border-red-300 text-red-700 rounded-lg hover:bg-red-50">Hapus</button>
                                </form>
                                @else
                                <span class="text-xs text-neutral-400">Ada produk</span>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Form Tambah Kategori --}}
    @if(auth()->user()->role === 'admin')
    <div class="space-y-5">
        <div class="card p-6">
            <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Tambah Kategori</h2>
            <form method="POST" action="/admin/kategori" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-neutral-600 mb-1">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        placeholder="Contoh: Skincare, Bodycare..."
                        class="input-field {{ $errors->has('name') ? 'border-red-400' : '' }}"
                        required>
                    @if($errors->has('name'))
                        <p class="text-xs text-red-600 mt-1">{{ $errors->first('name') }}</p>
                    @endif
                </div>
                <button type="submit" class="btn-primary w-full text-sm !py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Kategori
                </button>
            </form>
        </div>

        <div class="card p-5 bg-primary-50 border-primary-100">
            <h3 class="text-xs font-bold text-primary-700 uppercase tracking-wide mb-2">Info</h3>
            <ul class="text-xs text-neutral-600 space-y-1.5">
                <li class="flex items-start gap-1.5"><svg class="w-3.5 h-3.5 text-primary-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>Kategori akan muncul di filter katalog pelanggan.</li>
                <li class="flex items-start gap-1.5"><svg class="w-3.5 h-3.5 text-primary-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>Kategori tidak bisa dihapus jika masih ada produk di dalamnya.</li>
                <li class="flex items-start gap-1.5"><svg class="w-3.5 h-3.5 text-primary-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>Slug akan dibuat otomatis dari nama kategori.</li>
            </ul>
        </div>
    </div>
    @endif
</div>

{{-- Edit Category Modal --}}
<div id="edit-modal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-neutral-800">Edit Kategori</h3>
            <button type="button" onclick="closeEditModal()" class="text-neutral-400 hover:text-neutral-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="edit-form" method="POST" action="" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-semibold text-neutral-600 mb-1">Nama Kategori</label>
                <input type="text" name="name" id="edit-name" class="input-field" required>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary flex-1 text-sm !py-2.5">Simpan</button>
                <button type="button" onclick="closeEditModal()" class="btn-outline flex-1 text-sm !py-2.5">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-form').action = `/admin/kategori/${id}`;
    document.getElementById('edit-modal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
document.getElementById('edit-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
