@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
@endif
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-neutral-800 font-sans">Daftar User</h2>
            <div class="flex items-center gap-2">
                <form method="GET" action="/admin/users" class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ $currentQuery ?? '' }}" placeholder="Cari user..." class="input-field !py-2 !w-44">
                    <button type="submit" class="btn-outline text-sm !py-2 !px-4">Cari</button>
                </form>
                @if(auth()->user()->role === 'admin')
                <button type="button" onclick="openUserModal()" class="btn-primary text-sm">Tambah User</button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="p-3 rounded-xl bg-primary-50 border border-primary-100">
                <p class="text-xs text-neutral-500">Admin</p>
                <p class="text-lg font-bold text-primary-700">{{ $adminCount }}</p>
            </div>
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-100">
                <p class="text-xs text-neutral-500">Owner</p>
                <p class="text-lg font-bold text-amber-700">{{ $ownerCount ?? 0 }}</p>
            </div>
            <div class="p-3 rounded-xl bg-blue-50 border border-blue-100">
                <p class="text-xs text-neutral-500">Customer</p>
                <p class="text-lg font-bold text-blue-700">{{ $customerCount }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Nama</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Email</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">WhatsApp</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Role</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-neutral-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($users as $user)
                    <tr>
                        <td class="py-3 px-2 font-medium text-neutral-800">{{ $user->name }}</td>
                        <td class="py-3 px-2 text-xs text-neutral-400">{{ $user->email }}</td>
                        <td class="py-3 px-2 text-neutral-600">{{ $user->whatsapp }}</td>
                        <td class="py-3 px-2">
                            <span class="badge {{ $user->role === 'admin' ? 'badge-primary' : ($user->role === 'owner' ? 'badge-warning' : 'badge-success') }}">{{ strtoupper($user->role) }}</span>
                        </td>
                        <td class="py-3 px-2">
                            @if(auth()->user()->role === 'admin')
                            <div class="flex items-center gap-2">
                                <button type="button" onclick='openUserModal({{ json_encode($user) }})' class="px-2 py-1 text-xs border border-neutral-300 rounded-lg hover:bg-neutral-50">Edit</button>
                                <form method="POST" action="/admin/users/{{ $user->id }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-xs border border-red-300 text-red-700 rounded-lg hover:bg-red-50">Hapus</button>
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

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Info</h2>
        <div class="space-y-3 text-sm text-neutral-600">
            <p>✓ Total Aktif: <strong class="text-neutral-800">{{ $adminCount + ($ownerCount ?? 0) + $customerCount }}</strong></p>
            <p>✓ Admin: <strong class="text-primary-700">{{ $adminCount }}</strong></p>
            <p>✓ Owner: <strong class="text-amber-700">{{ $ownerCount ?? 0 }}</strong></p>
            <p>✓ Customer: <strong class="text-blue-700">{{ $customerCount }}</strong></p>
        </div>
    </div>
</div>

{{-- User Modal --}}
<div id="user-modal" class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-neutral-200 p-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-neutral-800" id="modal-title">Tambah User</h3>
            <button type="button" onclick="closeUserModal()" class="text-neutral-400 hover:text-neutral-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="user-form" method="POST" action="/admin/users" class="p-4 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <input type="hidden" name="user_id" id="user_id">

            <div>
                <label class="text-xs font-semibold text-neutral-600">Nama</label>
                <input type="text" name="name" id="user_name" class="input-field mt-1" required>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Email</label>
                <input type="email" name="email" id="user_email" class="input-field mt-1" required>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">WhatsApp</label>
                <input type="text" name="whatsapp" id="user_whatsapp" class="input-field mt-1" required>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Alamat</label>
                <textarea name="address" id="user_address" rows="2" class="input-field mt-1" required></textarea>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Role</label>
                <select name="role" id="user_role" class="input-field mt-1" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                    <option value="owner">Owner</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600">Password <span id="password-note"></span></label>
                <input type="password" name="password" id="user_password" class="input-field mt-1">
                <p class="text-xs text-neutral-500 mt-1" id="password-help">Biarkan kosong untuk tidak mengubah password</p>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit" class="btn-primary flex-1 text-sm">Simpan</button>
                <button type="button" onclick="closeUserModal()" class="btn-outline flex-1 text-sm">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal(user = null) {
    const modal = document.getElementById('user-modal');
    const form = document.getElementById('user-form');
    const title = document.getElementById('modal-title');
    const method = document.getElementById('form-method');
    const userId = document.getElementById('user_id');
    const passwordField = document.getElementById('user_password');
    const passwordNote = document.getElementById('password-note');
    const passwordHelp = document.getElementById('password-help');

    if (user) {
        // Edit mode
        title.textContent = 'Edit User';
        method.value = 'PATCH';
        userId.value = user.id;
        form.action = `/admin/users/${user.id}`;
        document.getElementById('user_name').value = user.name;
        document.getElementById('user_email').value = user.email;
        document.getElementById('user_whatsapp').value = user.whatsapp;
        document.getElementById('user_address').value = user.address || '';
        document.getElementById('user_role').value = user.role;
        passwordField.required = false;
        passwordNote.textContent = '';
        passwordHelp.textContent = 'Biarkan kosong untuk tidak mengubah password';
    } else {
        // Add mode
        title.textContent = 'Tambah User';
        method.value = 'POST';
        userId.value = '';
        form.action = '/admin/users';
        form.reset();
        passwordField.required = true;
        passwordNote.textContent = '*';
        passwordHelp.textContent = 'Password minimal 8 karakter';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUserModal() {
    const modal = document.getElementById('user-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('user-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeUserModal();
});
</script>
@endsection
