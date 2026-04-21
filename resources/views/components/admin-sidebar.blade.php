{{-- Admin Sidebar Component --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-60 bg-white border-r border-neutral-100 transition-transform duration-300 lg:translate-x-0 flex flex-col">
    {{-- Logo --}}
    <div class="h-16 flex items-center px-5 border-b border-neutral-100">
        <a href="/admin" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
            </div>
            <span class="text-base font-bold text-primary-700">SR12 Sintia</span>
        </a>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 py-4 px-3 space-y-1">
        @php
        $adminNav = [
            ['label' => 'Dashboard', 'url' => '/admin', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z', 'active' => ['admin']],
            ['label' => 'Laporan Analisis', 'url' => '/admin/laporan', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z', 'active' => ['admin/laporan']],
            ['label' => 'Kelola Pesanan', 'url' => '/admin/pesanan', 'icon' => 'M3 4.5h18m-18 6.75h18m-18 6.75h18', 'active' => ['admin/pesanan', 'admin/produk-pesanan']],
            ['label' => 'Kelola Produk', 'url' => '/admin/produk', 'icon' => 'M21 7.5l-9-4.5-9 4.5m18 0v9l-9 4.5-9-4.5v-9m18 0l-9 4.5-9-4.5', 'active' => ['admin/produk']],
            ['label' => 'Manajemen User', 'url' => '/admin/users', 'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z', 'active' => ['admin/users']],
            ['label' => 'Pengaturan', 'url' => '/admin/pengaturan', 'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z', 'active' => ['admin/pengaturan']],
        ];
        @endphp

        @foreach($adminNav as $nav)
        @php
            $patterns = (array) $nav['active'];
            $isActive = false;
            foreach ($patterns as $pattern) {
                if (request()->is($pattern)) {
                    $isActive = true;
                    break;
                }
            }
        @endphp
        <a href="{{ $nav['url'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/></svg>
            {{ $nav['label'] }}
            @if($isActive)
            <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            @endif
        </a>
        @endforeach
    </nav>

    {{-- Bottom --}}
    <div class="p-3 border-t border-neutral-100 space-y-1">
        <form method="POST" action="/logout" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-primary-600 hover:bg-primary-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
            Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/30 lg:hidden" x-cloak></div>

<style>[x-cloak] { display: none !important; }</style>
