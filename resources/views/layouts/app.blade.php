<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'SINTIA SR12 Distributor - Distributor Resmi SR12 Skincare Herbal Terpercaya di Parungpanjang. Belanja mudah, aman, dan terpercaya.')">
    <title>@yield('title', 'SINTIA SR12 Distributor') - Distributor Resmi SR12 Parungpanjang</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'><text y='32' font-size='32'>🌸</text></svg>">

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- Fallback assets while local Vite build is unavailable --}}
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
                                950: '#500724',
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

        .card { background: #fff; border: 1px solid #e5e5e5; border-radius: 1rem; }
        .card-elevated { background: #fff; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0, 0, 0, .08); }

        .badge { display: inline-flex; align-items: center; padding: .25rem .75rem; border-radius: 9999px; font-size: .75rem; font-weight: 600; }
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

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-fade-in-up { animation: fade-in-up .6s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-white flex flex-col">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">{{ session('error') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>
