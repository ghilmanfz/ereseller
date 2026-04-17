<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SR12 Sintia</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'><text y='32' font-size='32'>🌸</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-50 flex" x-data="{ sidebarOpen: true }">
    {{-- Sidebar --}}
    @include('components.admin-sidebar')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col min-w-0" :class="sidebarOpen ? 'lg:ml-60' : ''">
        {{-- Top Bar --}}
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-neutral-100 h-16 flex items-center px-6 gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-neutral-600 hover:text-primary-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            <h1 class="text-lg font-bold text-neutral-800 font-sans">@yield('page_title', 'Dashboard')</h1>

            <div class="flex-1"></div>

            <div class="relative hidden sm:block">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" placeholder="Cari pesanan atau stok..." class="pl-10 pr-4 py-2 bg-neutral-50 border border-neutral-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-64">
            </div>

            <button class="relative p-2 text-neutral-500 hover:text-primary-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-primary-600 rounded-full"></span>
            </button>

            <div class="flex items-center gap-3 ml-2">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-neutral-800">Admin Sintia</p>
                    <p class="text-xs text-neutral-400">Super Admin</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-bold">AS</div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-neutral-100 text-center">
            <p class="text-xs text-neutral-400">&copy; {{ date('Y') }} SR12 Sintia Distributor Parungpanjang. Powered by SR12 Eco-System.</p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
