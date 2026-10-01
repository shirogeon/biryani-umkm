@extends('layouts.app')

@section('title', 'Dapur Nasi Biryani Berkah - Rasa Rempah Autentik Basmati')

@section('content')
<!-- Hero Section: Editorial & Asymmetrical -->
<section class="relative min-h-[90vh] flex items-center pt-20 pb-12 overflow-hidden bg-parchment">
    
    <!-- Floating Spices for Parallax -->
    <div class="spice-float spice-parallax absolute top-1/4 left-[8%] w-12 h-12 opacity-80 z-20" data-speed="0.2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-amber-800 rotate-12">
            <!-- Anise-like shape -->
            <path d="M12 2L14 9L21 12L14 15L12 22L10 15L3 12L10 9L12 2Z" />
        </svg>
    </div>
    <div class="spice-float spice-parallax absolute bottom-1/4 right-[12%] w-16 h-16 opacity-60 z-20" data-speed="0.5">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-amber-900 -rotate-45">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 2V22M2 12H22M5 5L19 19M5 19L19 5" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
            
            <!-- Left: Bold Typography -->
            <div class="flex-1 text-center lg:text-left space-y-8">
                <h1 class="hero-text font-serif text-5xl sm:text-7xl lg:text-[5.5rem] font-bold text-stone-900 leading-[1.05] tracking-tight">
                    Nasi Biryani. <br>
                    <span class="text-amber-700 italic font-medium">Tanpa Kompromi.</span>
                </h1>
                <p class="hero-text text-lg sm:text-2xl text-stone-600 max-w-xl mx-auto lg:mx-0 font-sans font-light leading-relaxed">
                    Beras basmati bulir ekstra panjang. Dimasak dengan 14 racikan rempah utuh dan kaldu tulang perlahan, bukan bumbu instan.
                </p>
                <div class="hero-text pt-4">
                    <button onclick="BiryaniCart.openDrawer()" class="btn-tactile inline-flex items-center justify-center bg-stone-900 text-white px-10 py-5 rounded-full font-bold text-lg tracking-wide hover:bg-amber-700 transition-colors uppercase w-full sm:w-auto">
                        Mulai Pesan
                    </button>
                </div>
            </div>

            <!-- Right: Unconventional Platter Display -->
            <div class="flex-1 relative w-full max-w-lg mx-auto lg:max-w-none">
                <div class="hero-image-wrapper relative aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-stone-200">
                    <img src="https://images.unsplash.com/photo-1633945274405-b6c8069047b0?w=1200&auto=format&fit=crop&q=80" 
                         alt="Nasi Biryani Autentik" 
                         class="w-full h-full object-cover object-center scale-105"
                         id="hero-main-img">
                </div>
                <!-- Tactical detail badge -->
                <div class="hero-badge absolute -bottom-6 -left-6 sm:bottom-12 sm:-left-12 bg-white p-6 rounded-full shadow-2xl flex flex-col items-center justify-center w-32 h-32 border border-stone-100">
                    <span class="font-serif text-3xl font-bold text-amber-700">14</span>
                    <span class="text-[10px] uppercase tracking-widest font-bold text-stone-500 text-center leading-tight mt-1">Rempah<br>Utuh</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Pinned Storytelling Section (The "Bite" Scrolling Experience) -->
<section id="story-pin-section" class="relative bg-stone-900 text-stone-100 hidden lg:block overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-start h-screen">
        
        <!-- Pinned Visual (Left Side) -->
        <div class="w-1/2 h-full flex items-center justify-center relative pr-12">
            <div id="story-visual-container" class="relative w-full aspect-square rounded-[3rem] overflow-hidden">
                <img src="https://images.unsplash.com/photo-1596797038530-2c107229654b?w=800&auto=format&fit=crop" class="story-img absolute inset-0 w-full h-full object-cover z-30" alt="Beras Basmati">
                <img src="https://images.unsplash.com/photo-1544025162-8111142154ea?w=800&auto=format&fit=crop" class="story-img absolute inset-0 w-full h-full object-cover z-20 opacity-0" alt="Daging Kambing">
                <img src="https://images.unsplash.com/photo-1505253668822-42074d58a7c6?w=800&auto=format&fit=crop" class="story-img absolute inset-0 w-full h-full object-cover z-10 opacity-0" alt="Rempah Raita">
            </div>
        </div>

        <!-- Scrolling Text (Right Side) -->
        <div class="w-1/2 h-full overflow-hidden relative">
            <div id="story-text-scroll" class="pt-[40vh] pb-[60vh] space-y-[40vh]">
                
                <div class="story-text-block max-w-md">
                    <span class="font-mono text-amber-500 text-sm tracking-widest uppercase mb-4 block">01 / Bahan Baku</span>
                    <h3 class="font-serif text-5xl font-bold mb-6 leading-tight">Basmati bulir panjang ekstra.</h3>
                    <p class="text-stone-400 text-xl font-light leading-relaxed">
                        Kami hanya menggunakan beras Basmati tipe 1121. Menghasilkan tekstur yang ringan, terpisah, dan menyerap rasa tanpa menjadi lembek.
                    </p>
                </div>

                <div class="story-text-block max-w-md">
                    <span class="font-mono text-amber-500 text-sm tracking-widest uppercase mb-4 block">02 / Teknik Masak</span>
                    <h3 class="font-serif text-5xl font-bold mb-6 leading-tight">Dimasak api kecil tiga jam.</h3>
                    <p class="text-stone-400 text-xl font-light leading-relaxed">
                        Kambing dan ayam dimarinasi semalaman, kemudian direbus lambat bersama kaldu dan ghee. Serat daging lepas dari tulang dengan sendirinya.
                    </p>
                </div>

                <div class="story-text-block max-w-md">
                    <span class="font-mono text-amber-500 text-sm tracking-widest uppercase mb-4 block">03 / Pelengkap</span>
                    <h3 class="font-serif text-5xl font-bold mb-6 leading-tight">Kari Dalcha & Raita Yoghurt.</h3>
                    <p class="text-stone-400 text-xl font-light leading-relaxed">
                        Biryani tidak lengkap tanpa kuah kental kacang lentil (Dalcha) yang gurih dan kesegaran raita yoghurt timun dingin untuk menetralkan palet.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Mobile fallback for Storytelling -->
<section class="bg-stone-900 text-stone-100 py-24 lg:hidden space-y-20 px-6">
    <div class="space-y-6">
        <h3 class="font-serif text-4xl font-bold">Basmati bulir panjang ekstra.</h3>
        <p class="text-stone-400 text-lg font-light">Kami hanya menggunakan beras Basmati tipe 1121. Menghasilkan tekstur yang ringan, terpisah, dan menyerap rasa tanpa menjadi lembek.</p>
        <img src="https://images.unsplash.com/photo-1596797038530-2c107229654b?w=800&auto=format&fit=crop" class="w-full aspect-[4/3] rounded-3xl object-cover" alt="">
    </div>
    <div class="space-y-6">
        <h3 class="font-serif text-4xl font-bold">Dimasak api kecil tiga jam.</h3>
        <p class="text-stone-400 text-lg font-light">Kambing dan ayam dimarinasi semalaman, kemudian direbus lambat bersama kaldu dan ghee. Serat daging lepas dari tulang.</p>
        <img src="https://images.unsplash.com/photo-1544025162-8111142154ea?w=800&auto=format&fit=crop" class="w-full aspect-[4/3] rounded-3xl object-cover" alt="">
    </div>
</section>

<!-- Editorial Menu Showcase (No standard grids) -->
<section class="py-32 bg-cream border-t border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-20 text-center max-w-3xl mx-auto">
            <h2 class="font-serif text-5xl sm:text-6xl font-bold text-stone-900 tracking-tight">Katalog Menu</h2>
            <p class="mt-6 text-xl text-stone-600 font-light">Pesan untuk makan di tempat, bawa pulang, atau antar langsung ke alamat Anda.</p>
        </div>

        <div class="space-y-32">
            @foreach($featuredProducts->take(3) as $index => $product)
            <div class="flex flex-col {{ $index % 2 == 1 ? 'lg:flex-row-reverse' : 'lg:flex-row' }} items-center gap-12 lg:gap-24 showcase-item">
                
                <!-- Image -->
                <div class="w-full lg:w-1/2">
                    <div class="aspect-[4/3] overflow-hidden rounded-[2rem]">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Content -->
                <div class="w-full lg:w-1/2 space-y-8">
                    <span class="font-mono text-amber-700 text-xs tracking-widest uppercase block border-b border-amber-700/20 pb-4 inline-block">{{ $product->category->name ?? 'Signature' }}</span>
                    
                    <h3 class="font-serif text-4xl sm:text-5xl font-bold text-stone-900 leading-tight">
                        {{ $product->name }}
                    </h3>
                    
                    <p class="text-lg text-stone-600 font-light leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-8 pt-4">
                        <div class="font-sans">
                            <span class="block text-sm text-stone-500 uppercase tracking-widest mb-1">Harga per porsi</span>
                            <span class="font-bold text-2xl text-stone-900">{{ $product->formatted_price }}</span>
                        </div>
                        
                        <button onclick="BiryaniCart.addItem({
                            id: {{ $product->id }},
                            name: '{{ addslashes($product->name) }}',
                            price: {{ $product->price }},
                            image: '{{ $product->image_url }}',
                            portion_size: '{{ $product->portion_size }}'
                        })" class="btn-tactile bg-stone-900 text-white px-8 py-4 rounded-full font-bold text-sm tracking-wide hover:bg-amber-700 transition-colors uppercase w-full sm:w-auto text-center">
                            Tambah Pesanan
                        </button>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <div class="mt-32 text-center">
            <a href="{{ route('menu.index') }}" class="inline-block border-b-2 border-stone-900 pb-1 font-bold text-stone-900 text-lg hover:text-amber-700 hover:border-amber-700 transition-colors uppercase tracking-widest">
                Lihat Seluruh Menu &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Direct Contact / Final CTA -->
<section class="py-32 bg-stone-100">
    <div class="max-w-4xl mx-auto px-6 text-center space-y-12">
        <h2 class="font-serif text-4xl sm:text-6xl font-bold text-stone-900 leading-tight">
            Pesanan Partai Besar atau Katering Acara?
        </h2>
        <p class="text-xl text-stone-600 font-light max-w-2xl mx-auto">
            Kami melayani nampan biryani ukuran loyang untuk 4 hingga 20 orang. Hubungi kami secara langsung untuk mengatur jadwal pengiriman acara Anda.
        </p>
        <a href="https://wa.me/{{ \App\Models\StoreSetting::get('store_phone', '6281298765432') }}?text={{ rawurlencode('Halo Dapur Biryani, saya ingin pesan paket nampan untuk acara.') }}" 
           target="_blank" 
           class="btn-tactile inline-block bg-emerald-700 text-white px-10 py-5 rounded-full font-bold text-sm tracking-widest hover:bg-emerald-800 transition-colors uppercase">
            Hubungi via WhatsApp
        </a>
    </div>
</section>
@endsection
