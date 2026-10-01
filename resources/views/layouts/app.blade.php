<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dapur Nasi Biryani Berkah - Rasa Rempah Autentik Basmati')</title>
    <meta name="description" content="Sajian Nasi Biryani beras Basmati 1121 dengan 14 rempah utuh, kambing muda empuk, ayam bumbu gurih, dan teh tarik kapulaga. Pesan online cepat via WhatsApp & Web.">

    <!-- Google Fonts: Playfair Display (Serif) + Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        parchment: '#FAF7F2',
                        cream: '#F5EFEB',
                        charcoal: '#1C1917',
                        saffron: {
                            DEFAULT: '#D97706',
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                        },
                        terracotta: '#C2410C',
                        cardamom: '#15803D',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    @yield('styles')
</head>
<body class="bg-parchment text-charcoal font-sans antialiased selection:bg-amber-200 selection:text-amber-900 min-h-screen flex flex-col">

    <!-- Top Announcement Bar (Bite-style clean alert) -->
    <div class="bg-stone-900 text-stone-200 text-xs py-2 px-4 text-center font-medium tracking-wide flex items-center justify-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span>Dapur Sedang Buka • Dimasak Segar Setiap Hari 10:00 - 22:00 WIB • Melayani Dine-in, Takeaway & Delivery</span>
    </div>

    <!-- Sticky Main Navigation -->
    <header class="sticky top-0 z-40 bg-parchment/95 backdrop-blur-md border-b border-stone-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Dapur Biryani Berkah" class="w-12 h-12 object-contain group-hover:scale-105 transition-transform">
                <div>
                    <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight text-stone-900 block leading-tight">Dapur Biryani</span>
                    <span class="text-[11px] font-semibold text-amber-700 tracking-wider uppercase block">Berkah Nusantara</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-stone-700">
                <a href="{{ route('home') }}" class="hover:text-amber-700 transition-colors {{ request()->routeIs('home') ? 'text-amber-700 font-bold' : '' }}">Beranda</a>
                <a href="{{ route('menu.index') }}" class="hover:text-amber-700 transition-colors {{ request()->routeIs('menu.*') ? 'text-amber-700 font-bold' : '' }}">Daftar Menu</a>
                <a href="{{ route('order.track') }}" class="hover:text-amber-700 transition-colors {{ request()->routeIs('order.track') ? 'text-amber-700 font-bold' : '' }}">
                    <i class="fa-solid fa-location-dot text-amber-600 mr-1"></i>Lacak Pesanan
                </a>
            </nav>

            <!-- Actions (Cart Button & WA Quick Contact) -->
            <div class="flex items-center gap-3">
                <a href="https://wa.me/{{ \App\Models\StoreSetting::get('store_phone', '6281298765432') }}?text={{ rawurlencode('Halo Admin Dapur Biryani, saya ingin bertanya tentang menu dan pesanan.') }}" 
                   target="_blank" 
                   class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                    <i class="fa-brands fa-whatsapp text-sm text-emerald-600"></i>
                    <span>Tanya Admin</span>
                </a>

                <!-- Cart Drawer Trigger Button -->
                <button onclick="BiryaniCart.openDrawer()" class="relative btn-tactile flex items-center gap-2 px-4 py-2.5 rounded-xl bg-stone-900 hover:bg-amber-700 text-white font-bold text-sm shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-base"></i>
                    <span class="hidden sm:inline">Keranjang</span>
                    <!-- Count Badge -->
                    <span class="cart-count-badge hidden absolute -top-1.5 -right-1.5 bg-amber-500 text-stone-950 text-[11px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-parchment shadow-sm">
                        0
                    </span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Global Cart Drawer (Slide-Over Panel) -->
    <div id="cart-drawer" class="fixed inset-0 z-50 pointer-events-none overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div id="cart-drawer-backdrop" onclick="BiryaniCart.closeDrawer()" class="cart-drawer-backdrop absolute inset-0 bg-stone-900/60 backdrop-blur-sm opacity-0 transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div id="cart-drawer-panel" class="cart-drawer-panel w-screen max-w-md bg-stone-50 border-l border-stone-200 shadow-2xl flex flex-col translate-x-full">
                <!-- Header -->
                <div class="p-6 bg-white border-b border-stone-200 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                            <i class="fa-solid fa-basket-shopping text-sm"></i>
                        </div>
                        <h3 class="font-bold text-stone-900 text-lg">Keranjang Pesanan</h3>
                    </div>
                    <button onclick="BiryaniCart.closeDrawer()" class="p-2 text-stone-400 hover:text-stone-700 rounded-lg hover:bg-stone-100 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Item List Container -->
                <div class="flex-1 overflow-y-auto p-6 space-y-3">
                    <div id="cart-drawer-empty" class="text-center py-16 hidden">
                        <div class="w-20 h-20 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-300 text-3xl mb-4">
                            <i class="fa-solid fa-plate-wheat"></i>
                        </div>
                        <h4 class="font-bold text-stone-800 text-base">Keranjang Anda Masih Kosong</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-xs mx-auto">Yuk pilih Nasi Biryani hangat aromatik atau minuman segar khas kami sekarang!</p>
                        <a href="{{ route('menu.index') }}" onclick="BiryaniCart.closeDrawer()" class="mt-5 inline-block px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            Lihat Menu Pilihan
                        </a>
                    </div>
                    <div id="cart-drawer-items" class="space-y-3">
                        <!-- Populated by cart.js -->
                    </div>
                </div>

                <!-- Footer Summary & Checkout Button -->
                <div id="cart-drawer-footer" class="p-6 bg-white border-t border-stone-200 space-y-4">
                    <div class="flex justify-between items-center text-stone-600 text-sm">
                        <span>Perkiraan Total:</span>
                        <span id="cart-drawer-total" class="font-extrabold text-stone-950 text-xl text-amber-700">Rp 0</span>
                    </div>
                    <p class="text-[11px] text-stone-500">Harga belum termasuk ongkos kirim (jika memilih opsi Delivery).</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="BiryaniCart.clearCart()" class="w-full py-2.5 px-3 border border-stone-300 text-stone-600 hover:bg-stone-100 rounded-xl font-bold text-xs transition-colors">
                            Kosongkan
                        </button>
                        <a href="{{ route('checkout.index') }}" class="w-full py-2.5 px-3 bg-stone-900 hover:bg-amber-700 text-white rounded-xl font-bold text-xs text-center transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <span>Lanjut Checkout</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-stone-900 text-stone-300 pt-16 pb-12 mt-20 border-t border-stone-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-stone-800">
                <!-- Col 1: Store Bio -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Dapur Biryani Berkah" class="w-12 h-12 object-contain bg-white rounded-xl p-1 shadow-md">
                        <div>
                            <span class="font-serif text-2xl font-bold text-white block leading-tight">Dapur Nasi Biryani</span>
                            <span class="text-xs font-semibold text-amber-400 tracking-wider uppercase block">Berkah Nusantara</span>
                        </div>
                    </div>
                    <p class="text-stone-400 text-sm leading-relaxed max-w-md">
                        Mengolah beras Basmati impor kualitas 1121 dengan 14 racikan rempah utuh, minyak samin murni, dan potongan daging empuk slow-cooked. Menghadirkan kehangatan cita rasa autentik Timur Tengah yang cocok dengan lidah Nusantara.
                    </p>
                    <div class="pt-2 flex items-center gap-3 text-stone-400">
                        <span class="text-xs bg-stone-800 border border-stone-700 px-3 py-1 rounded-full"><i class="fa-solid fa-check text-emerald-400 mr-1.5"></i>100% Halal</span>
                        <span class="text-xs bg-stone-800 border border-stone-700 px-3 py-1 rounded-full"><i class="fa-solid fa-check text-emerald-400 mr-1.5"></i>Beras Basmati Asli</span>
                        <span class="text-xs bg-stone-800 border border-stone-700 px-3 py-1 rounded-full"><i class="fa-solid fa-check text-emerald-400 mr-1.5"></i>Tanpa Pengawet</span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm text-stone-400">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('menu.index') }}" class="hover:text-amber-400 transition-colors">Katalog Menu Biryani</a></li>
                        <li><a href="{{ route('order.track') }}" class="hover:text-amber-400 transition-colors">Lacak Status Pesanan</a></li>
                        <li><a href="{{ route('checkout.index') }}" class="hover:text-amber-400 transition-colors">Checkout Belanja</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-amber-400 transition-colors opacity-60">Akses Pengelola (Admin)</a></li>
                    </ul>
                </div>

                <!-- Col 3: Contact & Hours -->
                <div>
                    <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4">Lokasi & Kontak</h4>
                    <p class="text-stone-400 text-sm leading-relaxed mb-3">
                        <i class="fa-solid fa-location-dot text-amber-500 mr-2"></i>
                        {{ \App\Models\StoreSetting::get('store_address', 'Jl. Aroma Rempah No. 88, Tebet, Jakarta Selatan') }}
                    </p>
                    <p class="text-stone-400 text-sm mb-3">
                        <i class="fa-solid fa-clock text-amber-500 mr-2"></i>
                        {{ \App\Models\StoreSetting::get('store_open_hours', 'Setiap Hari: 10:00 - 22:00 WIB') }}
                    </p>
                    <p class="text-stone-400 text-sm">
                        <i class="fa-brands fa-whatsapp text-emerald-400 mr-2"></i>
                        +{{ \App\Models\StoreSetting::get('store_phone', '6281298765432') }}
                    </p>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
                <p>&copy; {{ date('Y') }} Dapur Nasi Biryani Berkah. Seluruh hak cipta dilindungi.</p>
                <p class="flex items-center gap-2">
                    <span>Sistem Pemesanan UMKM Berbasis Laravel 9 & GSAP</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- GSAP 3.12.5 and ScrollTrigger Library -->
    <script src="{{ asset('vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('vendor/gsap/ScrollTrigger.min.js') }}"></script>

    <!-- Client Scripts -->
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    <script src="{{ asset('assets/js/animations.js') }}"></script>

    @yield('scripts')
</body>
</html>
