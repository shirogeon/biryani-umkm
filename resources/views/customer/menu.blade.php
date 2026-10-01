@extends('layouts.app')

@section('title', 'Katalog Menu Nasi Biryani - Dapur Biryani Berkah')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="max-w-3xl mb-10 space-y-3">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block">Daftar Menu Pilihan</span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold text-stone-900 leading-tight">
                Pilihan Nasi Biryani, Paket Loyang & Minuman Rempah
            </h1>
            <p class="text-stone-600 text-sm sm:text-base">
                Pilih sajian favorit Anda, sesuaikan jumlah porsi dan catatan khusus, lalu pesan secara mudah.
            </p>
        </div>

        <!-- Filter Bar & Search -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 pb-6 border-b border-stone-200">
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
                <button onclick="filterCategory('all')" class="cat-pill active px-4 py-2 rounded-xl text-xs font-bold transition-all bg-stone-900 text-white shadow-sm" data-category="all">
                    Semua Menu
                </button>
                @foreach($categories as $cat)
                <button onclick="filterCategory('{{ $cat->slug }}')" class="cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-800" data-category="{{ $cat->slug }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>

            <!-- Search Input -->
            <div class="relative w-full md:w-72">
                <input type="text" id="menu-search" oninput="searchMenu(this.value)" placeholder="Cari menu biryani..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-stone-300 bg-white text-xs font-medium focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-stone-400 text-xs"></i>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($categories as $category)
                @foreach($category->products as $product)
                <div class="menu-item-card bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md hover:border-amber-400 transition-all flex flex-col justify-between group" 
                     data-category="{{ $category->slug }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-desc="{{ strtolower($product->description) }}">
                    
                    <!-- Image -->
                    <div class="relative aspect-square overflow-hidden bg-stone-100">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($product->spiciness_level > 0)
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-full text-[11px] font-bold text-stone-800 shadow-sm border border-stone-200/80">
                            🌶️ Level {{ $product->spiciness_level }}
                        </div>
                        @endif
                        <div class="absolute bottom-3 right-3 bg-stone-900/80 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-full text-[10px] font-semibold">
                            {{ $product->portion_size }}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block mb-1">
                                {{ $category->name }}
                            </span>
                            <h3 class="font-bold text-stone-900 text-base leading-snug line-clamp-1 group-hover:text-amber-700 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-stone-500 line-clamp-2 mt-1.5 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Price & Action -->
                        <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-stone-400 block uppercase font-medium">Harga</span>
                                <span class="font-extrabold text-stone-900 text-base">{{ $product->formatted_price }}</span>
                            </div>
                            <button onclick="BiryaniCart.addItem({
                                id: {{ $product->id }},
                                name: '{{ addslashes($product->name) }}',
                                price: {{ $product->price }},
                                image: '{{ $product->image_url }}',
                                portion_size: '{{ $product->portion_size }}'
                            })" class="btn-tactile px-3.5 py-2 rounded-xl bg-stone-900 hover:bg-amber-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition-colors">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>

        <!-- Empty Filter State -->
        <div id="no-menu-found" class="hidden text-center py-20 bg-white rounded-3xl border border-stone-200 mt-6">
            <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 text-2xl mx-auto mb-3">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="font-bold text-stone-800 text-base">Menu Tidak Ditemukan</h4>
            <p class="text-xs text-stone-500 mt-1">Coba kata kunci lain atau pilih kategori Semua Menu.</p>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterCategory(categorySlug) {
        // Toggle pill buttons
        document.querySelectorAll('.cat-pill').forEach(btn => {
            if (btn.getAttribute('data-category') === categorySlug) {
                btn.className = 'cat-pill active px-4 py-2 rounded-xl text-xs font-bold transition-all bg-stone-900 text-white shadow-sm';
            } else {
                btn.className = 'cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-800';
            }
        });

        // Filter cards
        const cards = document.querySelectorAll('.menu-item-card');
        let visibleCount = 0;

        cards.forEach(card => {
            if (categorySlug === 'all' || card.getAttribute('data-category') === categorySlug) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('no-menu-found').classList.toggle('hidden', visibleCount > 0);
    }

    function searchMenu(query) {
        query = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-item-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const desc = card.getAttribute('data-desc');

            if (name.includes(query) || desc.includes(query)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('no-menu-found').classList.toggle('hidden', visibleCount > 0);
    }
</script>
@endsection
