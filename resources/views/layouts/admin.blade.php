<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SR12 Sintia</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'><text y='32' font-size='32'>🌸</text></svg>">
    <script>
        tailwind = {
            config: {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                            serif: ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
                        },
                        colors: {
                            primary: {
                                50: '#fdf2f8',
                                100: '#fce7f3',
                                200: '#fbcfe8',
                                300: '#f9a8d4',
                                400: '#f472b6',
                                500: '#ec4899',
                                600: '#db2777',
                                700: '#be185d',
                                800: '#9d174d',
                                900: '#831843',
                            },
                        },
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap');

        [x-cloak] { display: none !important; }

        body { font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', ui-serif, Georgia, serif; }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: 9999px;
            background: #db2777;
            color: #fff;
            font-weight: 600;
            transition: all .2s ease;
            box-shadow: 0 10px 15px -3px rgb(219 39 119 / .25), 0 4px 6px -4px rgb(219 39 119 / .25);
        }
        .btn-primary:hover { background: #be185d; transform: translateY(-2px); }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: 9999px;
            border: 2px solid #db2777;
            color: #db2777;
            font-weight: 600;
            transition: all .2s ease;
        }
        .btn-outline:hover { background: #fdf2f8; transform: translateY(-2px); }

        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: .25rem .75rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-primary { background: #fce7f3; color: #be185d; }
        .badge-success { background: #f0fdf4; color: #15803d; }
        .badge-warning { background: #fffbeb; color: #b45309; }
        .badge-error { background: #fef2f2; color: #b91c1c; }

        .input-field {
            width: 100%;
            padding: .75rem 1rem;
            border: 1px solid #d4d4d4;
            border-radius: .75rem;
            font-size: .875rem;
            outline: none;
            transition: all .2s ease;
        }
        .input-field:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgb(236 72 153 / .2);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

            <button class="relative p-2 text-neutral-500 hover:text-primary-600 transition-colors" onclick="toggleNotificationPanel()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                <span id="notif-badge" class="absolute top-1 right-1 w-2 h-2 bg-primary-600 rounded-full"></span>
            </button>

            <div class="flex items-center gap-3 ml-2">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-neutral-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-neutral-400">{{ auth()->user()->role === 'owner' ? 'Owner (View Only)' : 'Admin' }}</p>
                </div>
                <div class="w-9 h-9 rounded-full {{ auth()->user()->role === 'owner' ? 'bg-gradient-to-br from-amber-400 to-amber-600' : 'bg-gradient-to-br from-primary-400 to-primary-600' }} flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @if(auth()->user()->role === 'owner')
            <div class="mb-4 flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span><strong>Mode Owner (View Only)</strong> — Anda hanya dapat melihat data. Semua aksi perubahan dinonaktifkan.</span>
            </div>
            @endif
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-neutral-100 text-center">
            <p class="text-xs text-neutral-400">&copy; {{ date('Y') }} SR12 Sintia Distributor Parungpanjang. Powered by SR12 Eco-System.</p>
        </footer>
    </div>

    {{-- Notification Panel --}}
    <div id="notification-panel" class="hidden fixed top-20 right-6 w-96 max-h-96 bg-white rounded-xl shadow-lg border border-neutral-200 z-50 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-neutral-100 flex items-center justify-between">
            <h3 class="font-bold text-neutral-800">Notifikasi Aktivitas</h3>
            <button onclick="toggleNotificationPanel()" class="text-neutral-400 hover:text-neutral-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="notification-list" class="flex-1 overflow-y-auto space-y-2 p-3">
            <div class="text-center py-6 text-neutral-400 text-sm">Memuat notifikasi...</div>
        </div>
    </div>

    {{-- Notification Backdrop --}}
    <div id="notif-backdrop" class="hidden fixed inset-0 z-40" onclick="toggleNotificationPanel()"></div>

    @stack('scripts')

    <script>
        let notifPanel = null;
        let notifBackdrop = null;

        function toggleNotificationPanel() {
            if (!notifPanel) {
                notifPanel = document.getElementById('notification-panel');
                notifBackdrop = document.getElementById('notif-backdrop');
            }

            const isHidden = notifPanel.classList.contains('hidden');
            if (isHidden) {
                notifPanel.classList.remove('hidden');
                notifBackdrop.classList.remove('hidden');
                loadNotifications();
            } else {
                notifPanel.classList.add('hidden');
                notifBackdrop.classList.add('hidden');
            }
        }

        function loadNotifications() {
            fetch('/admin/notifications')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('notification-list');
                    const badge = document.getElementById('notif-badge');

                    if (data.notifications.length === 0) {
                        list.innerHTML = '<div class="text-center py-6 text-neutral-400 text-sm">Tidak ada notifikasi</div>';
                        badge.classList.add('hidden');
                        return;
                    }

                    badge.classList.toggle('hidden', data.unread_count === 0);

                    list.innerHTML = data.notifications.map(notif => `
                        <a href="/admin/pesanan#order-${notif.order_id}" class="block p-3 rounded-lg bg-neutral-50 hover:bg-neutral-100 border border-neutral-200 hover:border-primary-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-800">${notif.title}</p>
                                    <p class="text-xs text-neutral-600 mt-0.5">${notif.message}</p>
                                    <p class="text-xs text-neutral-400 mt-1">${new Date(notif.timestamp).toLocaleString('id-ID')}</p>
                                </div>
                                <div class="w-2 h-2 rounded-full mt-1 flex-shrink-0 ${getNotifColor(notif.type)}"></div>
                            </div>
                        </a>
                    `).join('');
                })
                .catch(err => console.error('Error loading notifications:', err));
        }

        function getNotifColor(type) {
            const colors = {
                'payment_pending': 'bg-amber-500',
                'new_order': 'bg-blue-500',
                'ready_pickup': 'bg-green-500',
            };
            return colors[type] || 'bg-neutral-400';
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', loadNotifications);

        // Refresh notifications setiap 30 detik
        setInterval(loadNotifications, 30000);

        // Close panel when clicking outside
        document.addEventListener('click', function(e) {
            const panel = document.getElementById('notification-panel');
            const btn = document.querySelector('[onclick="toggleNotificationPanel()"]');
            if (panel && !panel.classList.contains('hidden') && !panel.contains(e.target) && !btn.contains(e.target)) {
                toggleNotificationPanel();
            }
        });
    </script>
</body>
</html>
