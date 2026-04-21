@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div>
        <h1 class="text-2xl font-serif font-bold text-neutral-900">Profil Saya</h1>
        <p class="text-sm text-neutral-500 mt-1">Perbarui data akun, nomor telepon, dan kata sandi Anda.</p>
    </div>

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Data Profil</h2>
        <form method="POST" action="/profil" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-field" required>
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor WhatsApp</label>
                <input type="tel" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" class="input-field" required>
                @error('whatsapp')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field" required>
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Alamat</label>
                <textarea name="address" rows="3" class="input-field" required>{{ old('address', $user->address) }}</textarea>
                @error('address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary">Simpan Profil</button>
        </form>
    </div>

    <div class="card p-6">
        <h2 class="text-base font-bold text-neutral-800 font-sans mb-4">Ganti Kata Sandi</h2>
        <form method="POST" action="/profil/password" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="input-field" required>
                @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password" class="input-field" required>
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="input-field" required>
            </div>

            <button type="submit" class="btn-primary">Simpan Kata Sandi</button>
        </form>
    </div>
</div>
@endsection
