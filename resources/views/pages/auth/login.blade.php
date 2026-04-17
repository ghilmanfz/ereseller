@extends('layouts.app')
@section('title', 'Masuk')
@section('meta_description', 'Masuk ke akun SINTIA SR12 Anda untuk melanjutkan belanja produk skincare herbal favorit.')

@section('content')
<section class="relative min-h-[80vh] flex items-center justify-center py-16 bg-gradient-to-br from-primary-50/50 via-white to-primary-50/30 overflow-hidden">
    <div class="absolute top-20 left-10 w-72 h-72 bg-primary-200/15 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-64 h-64 bg-primary-300/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-md mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-primary-100 rounded-2xl flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
            </div>
            <h1 class="text-2xl font-serif font-bold text-neutral-900">Selamat Datang Kembali</h1>
            <p class="mt-2 text-sm text-neutral-500">Silakan masuk untuk melanjutkan<br>belanja produk SR12 favorit Anda.</p>
        </div>

        {{-- Login Card --}}
        <div class="card-elevated p-8">
            <h2 class="text-lg font-bold text-neutral-800 text-center font-sans">Masuk ke Akun</h2>
            <p class="text-xs text-neutral-400 text-center mt-1">Gunakan nomor WhatsApp aktif Anda untuk kemudahan verifikasi.</p>

            <form class="mt-6 space-y-5" action="#" method="POST">
                {{-- WhatsApp Number --}}
                <div>
                    <label for="whatsapp" class="block text-sm font-semibold text-neutral-700 mb-1.5">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        </div>
                        <input type="tel" id="whatsapp" name="whatsapp" class="input-field !pl-11" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-sm font-semibold text-neutral-700">Kata Sandi</label>
                        <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Lupa sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" id="password" name="password" class="input-field !pl-11 !pr-11" placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-neutral-400 hover:text-neutral-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-neutral-600">Tetap masuk selama 30 hari</span>
                </label>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full !py-3.5">
                    Masuk Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-6 flex items-center gap-4">
                <div class="flex-1 h-px bg-neutral-200"></div>
                <span class="text-xs text-neutral-400 uppercase font-medium">Atau Beralih</span>
                <div class="flex-1 h-px bg-neutral-200"></div>
            </div>

            <p class="text-center text-sm text-neutral-500">
                Belum bergabung sebagai distributor?<br>
                <a href="/register" class="font-semibold text-primary-600 hover:text-primary-700">Daftar di Sini</a>
            </p>
        </div>

        {{-- Quick Access Testing --}}
        <div class="mt-6 border-2 border-dashed border-neutral-300 rounded-2xl p-5 bg-white/60 backdrop-blur-sm">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.384-3.19A1.75 1.75 0 015 10.396V4.604a1.75 1.75 0 011.036-1.584l5.384-3.19a1.75 1.75 0 011.743.039l4.87 3.122A1.75 1.75 0 0119 4.636v5.728a1.75 1.75 0 01-.967 1.565l-4.87 3.122a1.75 1.75 0 01-1.743.039z"/></svg>
                    Dev Mode
                </span>
                <p class="text-xs font-semibold text-neutral-500">Quick Access Testing</p>
            </div>
            <p class="text-[11px] text-neutral-400 text-center mb-4">Pilih role untuk langsung masuk tanpa login (khusus testing frontend).</p>
            <div class="grid grid-cols-2 gap-3">
                <a href="/dev/login/admin" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border-2 border-neutral-200 hover:border-primary-400 hover:bg-primary-50/50 transition-all duration-200">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-neutral-800">Admin</p>
                        <p class="text-[10px] text-neutral-400">Dashboard & Kelola</p>
                    </div>
                </a>
                <a href="/dev/login/customer" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl border-2 border-neutral-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-200">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-neutral-800">Customer</p>
                        <p class="text-[10px] text-neutral-400">Belanja & Pesan</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Security Notice --}}
        <div class="mt-6 flex items-center justify-center gap-2 text-xs text-neutral-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            Data Anda terenkripsi dengan aman oleh sistem SR12 Parungpanjang.
        </div>
    </div>
</section>
@endsection
