<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Dapur Biryani Berkah')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        parchment: '#FAF7F2',
                        charcoal: '#1C1917',
                        saffron: {
                            DEFAULT: '#D97706',
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            600: '#D97706',
                            700: '#B45309',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- GSAP for Admin Transitions -->
    <script src="{{ asset('vendor/gsap/gsap.min.js') }}"></script>

    @yield('styles')
</head>
<body class="bg-stone-100 text-stone-900 font-sans antialiased min-h-screen flex">

    <!-- Mobile Sidebar Backdrop -->
    <div id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 z-40 bg-stone-900/60 backdrop-blur-sm hidden md:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-stone-900 text-stone-300 flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0 md:static md:inset-auto">
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-stone-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Biryani Admin" class="w-10 h-10 object-contain bg-white rounded-lg p-1 shadow-md">
                    <div>
                        <span class="font-bold text-white text-base block leading-tight">Biryani Admin</span>
                        <span class="text-[10px] text-amber-400 font-semibold tracking-wider uppercase block">Panel Kasir & Dapur</span>
                    </div>
                </a>
                <button onclick="toggleMobileSidebar()" class="md:hidden text-stone-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Nav Links -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <i class="fa-solid fa-chart-pie text-sm w-4"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-receipt text-sm w-4"></i>
                        <span>Kelola Pesanan</span>
                    </div>
                    @php $pendingCount = \App\Models\Order::where('order_status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                    <span class="bg-amber-500 text-stone-950 text-[10px] font-extrabold px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.products*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <i class="fa-solid fa-utensils text-sm w-4"></i>
                    <span>Kelola Menu Biryani</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <i class="fa-solid fa-tags text-sm w-4"></i>
                    <span>Kategori Menu</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <i class="fa-solid fa-file-invoice-dollar text-sm w-4"></i>
                    <span>Laporan Penjualan</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-amber-600 text-white font-bold' : 'hover:bg-stone-800 hover:text-white text-stone-400' }}">
                    <i class="fa-solid fa-sliders text-sm w-4"></i>
                    <span>Pengaturan Toko</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer (User Info & Logout) -->
        <div class="p-4 border-t border-stone-800 space-y-2">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-lg bg-stone-800/80 hover:bg-stone-800 text-[11px] font-semibold text-stone-300 transition-colors">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-amber-400"></i>
                    <span>Buka Tampilan Pembeli</span>
                </span>
            </a>

            <div class="pt-2 flex items-center justify-between text-xs px-2">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-stone-700 flex items-center justify-center font-bold text-white text-[10px]">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="font-bold text-white truncate max-w-[100px]">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-stone-400 hover:text-rose-400 p-1.5 transition-colors" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-stone-200 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleMobileSidebar()" class="md:hidden p-2 rounded-xl text-stone-600 hover:bg-stone-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="font-bold text-stone-900 text-lg sm:text-xl truncate">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-xs font-bold text-stone-900 block">{{ date('d F Y') }}</span>
                    <span class="text-[10px] text-stone-500 font-medium">Zona Waktu: Asia/Jakarta (WIB)</span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="p-2.5 rounded-xl bg-amber-50 text-amber-800 hover:bg-amber-100 transition-colors relative" title="Pesanan">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @if($pendingCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-600"></span>
                    @endif
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-8 pt-6">
            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif
        </div>

        <!-- Body Content -->
        <main class="flex-1 p-4 sm:p-8 pt-0">
            @yield('content')
        </main>

    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
