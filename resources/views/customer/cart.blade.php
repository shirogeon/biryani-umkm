@extends('layouts.app')

@section('title', 'Keranjang Belanja - Dapur Biryani Berkah')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">Keranjang Belanja Anda</h1>
            <p class="text-stone-600 text-sm mt-1">Periksa kembali pesanan Anda sebelum melanjutkan ke formulir pengiriman.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left 2 Cols: Cart Table -->
            <div class="lg:col-span-2 space-y-4">
                <div id="full-cart-empty" class="hidden text-center py-20 bg-white rounded-3xl border border-stone-200 p-8">
                    <div class="w-20 h-20 rounded-full bg-stone-100 flex items-center justify-center text-stone-300 text-3xl mx-auto mb-4">
                        <i class="fa-solid fa-plate-wheat"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-800">Keranjang Masih Kosong</h3>
                    <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">Anda belum menambahkan menu Biryani ke keranjang belanja.</p>
                    <a href="{{ route('menu.index') }}" class="btn-tactile mt-6 inline-block px-6 py-3 bg-stone-900 hover:bg-amber-700 text-white rounded-xl font-bold text-xs shadow-sm transition-colors">
                        Lihat Daftar Menu
                    </a>
                </div>

                <div id="full-cart-items" class="space-y-4">
                    <!-- Loaded dynamically via JS -->
                </div>
            </div>

            <!-- Right Col: Order Summary Box -->
            <div id="full-cart-summary" class="bg-white rounded-3xl border border-stone-200 p-6 space-y-6 shadow-sm sticky top-28">
                <h3 class="font-bold text-lg text-stone-900 border-b border-stone-100 pb-3">Ringkasan Pesanan</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-stone-600">
                        <span>Total Porsi</span>
                        <span id="full-cart-total-qty" class="font-bold text-stone-900">0</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Subtotal Makanan</span>
                        <span id="full-cart-subtotal" class="font-bold text-stone-900">Rp 0</span>
                    </div>
                    <p class="text-[11px] text-stone-500 pt-2 border-t border-stone-100">
                        *Ongkos kirim dihitung di halaman berikutnya jika Anda memilih metode pesan antar (Delivery).
                    </p>
                </div>

                <div class="pt-4 border-t border-stone-200 flex justify-between items-baseline">
                    <span class="font-bold text-stone-900 text-base">Total Estimasi</span>
                    <span id="full-cart-grandtotal" class="font-serif text-2xl font-bold text-amber-700">Rp 0</span>
                </div>

                <div class="space-y-2 pt-2">
                    <a href="{{ route('checkout.index') }}" class="btn-tactile w-full py-4 bg-stone-900 hover:bg-amber-700 text-white rounded-2xl font-bold text-sm text-center shadow-lg shadow-stone-900/10 flex items-center justify-center gap-2 transition-colors">
                        <span>Lanjut ke Formulir Checkout</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('menu.index') }}" class="w-full py-3 bg-white hover:bg-stone-50 text-stone-700 border border-stone-200 rounded-2xl font-semibold text-xs text-center block transition-colors">
                        Tambah Menu Lainnya
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function renderFullCartPage() {
        const cart = BiryaniCart.getCart();
        const itemsContainer = document.getElementById('full-cart-items');
        const emptyState = document.getElementById('full-cart-empty');
        const summaryBox = document.getElementById('full-cart-summary');
        const subtotalEl = document.getElementById('full-cart-subtotal');
        const grandtotalEl = document.getElementById('full-cart-grandtotal');
        const totalQtyEl = document.getElementById('full-cart-total-qty');

        if (cart.length === 0) {
            itemsContainer.innerHTML = '';
            emptyState.classList.remove('hidden');
            summaryBox.classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        summaryBox.classList.remove('hidden');

        let html = '';
        let totalQty = 0;

        cart.forEach(item => {
            totalQty += item.quantity;
            const subtotal = item.price * item.quantity;
            html += `
                <div class="p-5 bg-white rounded-3xl border border-stone-200 shadow-sm flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-2xl bg-stone-100 flex-shrink-0">
                        <div>
                            <h3 class="font-bold text-stone-900 text-base leading-tight">${item.name}</h3>
                            <p class="text-xs text-amber-700 font-semibold mt-1">${BiryaniCart.formatRupiah(item.price)}</p>
                            <span class="inline-block text-[10px] text-stone-500 bg-stone-100 px-2 py-0.5 rounded mt-1">${item.portion_size}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between w-full sm:w-auto sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-0 border-stone-100">
                        <!-- Qty Controls -->
                        <div class="inline-flex items-center rounded-xl border border-stone-200 bg-stone-50 overflow-hidden">
                            <button onclick="updateQty(${item.id}, ${item.quantity - 1})" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-200 transition-colors text-xs font-bold">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="w-10 text-center text-xs font-bold text-stone-900">${item.quantity}</span>
                            <button onclick="updateQty(${item.id}, ${item.quantity + 1})" class="w-8 h-8 flex items-center justify-center text-stone-600 hover:bg-stone-200 transition-colors text-xs font-bold">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <!-- Subtotal -->
                        <span class="font-bold text-stone-900 text-base min-w-[90px] text-right">${BiryaniCart.formatRupiah(subtotal)}</span>

                        <!-- Delete -->
                        <button onclick="deleteItem(${item.id})" class="p-2 text-stone-400 hover:text-rose-600 transition-colors" title="Hapus">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        itemsContainer.innerHTML = html;
        totalQtyEl.textContent = totalQty;
        subtotalEl.textContent = BiryaniCart.formatRupiah(BiryaniCart.getTotal());
        grandtotalEl.textContent = BiryaniCart.formatRupiah(BiryaniCart.getTotal());
    }

    function updateQty(id, qty) {
        BiryaniCart.updateQuantity(id, qty);
        renderFullCartPage();
    }

    function deleteItem(id) {
        BiryaniCart.removeItem(id);
        renderFullCartPage();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderFullCartPage();
    });
</script>
@endsection
