@extends('layouts.app')
@section('title', 'Lupa Password')

@section('content')
<section class="relative min-h-[70vh] flex items-center justify-center py-16 bg-gradient-to-br from-primary-50/50 via-white to-primary-50/30 overflow-hidden">
    <div class="relative z-10 w-full max-w-md mx-auto px-4">
        <div class="card-elevated p-8">
            <h1 class="text-xl font-bold text-neutral-800 text-center font-sans">Reset Kata Sandi</h1>
            <p class="text-sm text-neutral-500 text-center mt-2">Masukkan email terdaftar dan kata sandi baru Anda.</p>

            <form class="mt-6 space-y-4" method="POST" action="/lupa-password">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" class="input-field" placeholder="Contoh: nama@email.com" value="{{ old('email') }}" required>
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-neutral-700 mb-1.5">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" class="input-field" placeholder="Minimal 8 karakter" required>
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="input-field" placeholder="Ulangi kata sandi" required>
                </div>

                <button type="submit" class="btn-primary w-full !py-3">Reset Password</button>
            </form>

            <p class="text-center text-sm text-neutral-500 mt-5">
                Sudah ingat password? <a href="/login" class="font-semibold text-primary-600 hover:text-primary-700">Kembali ke Login</a>
            </p>
        </div>
    </div>
</section>
@endsection
