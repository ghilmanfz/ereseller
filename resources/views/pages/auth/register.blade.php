@extends('layouts.app')
@section('title', 'Daftar')
@section('meta_description', 'Daftar sebagai reseller atau konsumen SINTIA SR12 Parungpanjang.')

@section('content')
<section class="relative min-h-[80vh] flex items-center justify-center py-16 bg-gradient-to-br from-primary-50/50 via-white to-primary-50/30 overflow-hidden">
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-200/15 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-64 h-64 bg-primary-300/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-md mx-auto px-4">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-primary-100 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
            </div>
            <h1 class="text-2xl font-serif font-bold text-neutral-900">Bergabung Bersama Kami</h1>
            <p class="mt-2 text-sm text-neutral-500">Daftar untuk mulai belanja produk SR12<br>dengan harga distributor terbaik.</p>
        </div>

        <div class="card-elevated p-8">
            <h2 class="text-lg font-bold text-neutral-800 text-center font-sans">Buat Akun Baru</h2>
            <p class="text-xs text-neutral-400 text-center mt-1">Isi data berikut untuk mendaftar.</p>

            <form class="mt-6 space-y-5" action="#" method="POST">
                <div>
                    <label for="name" class="block text-sm font-semibold text-neutral-700 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                        <input type="text" id="name" name="name" class="input-field !pl-11" placeholder="Contoh: Siti Aminah">
                    </div>
                </div>

                <div>
                    <label for="reg_whatsapp" class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        </div>
                        <input type="tel" id="reg_whatsapp" name="whatsapp" class="input-field !pl-11" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-neutral-700 mb-1.5">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="2" class="input-field resize-none" placeholder="Jl. Raya Parungpanjang No. ..."></textarea>
                </div>

                <div x-data="{ show: false }">
                    <label for="reg_password" class="block text-sm font-semibold text-neutral-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" id="reg_password" name="password" class="input-field !pl-11 !pr-11" placeholder="Minimal 8 karakter">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-neutral-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" name="terms" class="w-4 h-4 mt-0.5 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-neutral-600">Saya menyetujui <a href="#" class="text-primary-600 font-semibold hover:text-primary-700">Syarat & Ketentuan</a> SINTIA SR12</span>
                </label>

                <button type="submit" class="btn-primary w-full !py-3.5">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="my-6 flex items-center gap-4">
                <div class="flex-1 h-px bg-neutral-200"></div>
                <span class="text-xs text-neutral-400 uppercase font-medium">Atau</span>
                <div class="flex-1 h-px bg-neutral-200"></div>
            </div>

            <p class="text-center text-sm text-neutral-500">
                Sudah punya akun? <a href="/login" class="font-semibold text-primary-600 hover:text-primary-700">Masuk di Sini</a>
            </p>
        </div>
    </div>
</section>
@endsection
