@extends('layouts.app')

@section('title', 'Checkout Pesanan - Dapur Biryani Berkah')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-500 hover:text-stone-900 transition-colors mb-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Keranjang</span>
            </a>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">Formulir Pemesanan</h1>
            <p class="text-stone-600 text-sm mt-1">Lengkapi data pemesan dan pilih metode pembayaran untuk menyelesaikan pesanan Anda.</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Mohon periksa kembali data Anda:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <!-- Hidden container for items input (populated by JS from LocalStorage) -->
            <div id="checkout-hidden-items"></div>

            <!-- Left 7 Cols: Customer Details Form -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- 1. Customer Identity -->
                <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 space-y-5 shadow-sm">
                    <h2 class="font-bold text-lg text-stone-900 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center">1</span>
                        <span>Identitas Pemesan</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name') }}" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50/50">
                        </div>
                        <div>
                            <label for="customer_phone" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif *</label>
                            <input type="tel" name="customer_phone" id="customer_phone" required value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50/50">
                            <span class="text-[11px] text-stone-400 mt-1 block">Rincian pesanan dan update status akan dikirimkan ke nomor ini.</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Order Type (Dine-in / Takeaway / Delivery) -->
                <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 space-y-5 shadow-sm">
                    <h2 class="font-bold text-lg text-stone-900 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center">2</span>
                        <span>Tipe Layanan Pesanan</span>
                    </h2>

                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="dine_in" checked onchange="toggleOrderType(this.value)" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 text-center peer-checked:border-amber-600 peer-checked:bg-amber-50/50 hover:bg-stone-50 transition-all space-y-1.5">
                                <i class="fa-solid fa-utensils text-xl text-stone-600 peer-checked:text-amber-700 block"></i>
                                <span class="font-bold text-xs sm:text-sm text-stone-900 block">Dine-in</span>
                                <span class="text-[10px] text-stone-500 block">Makan di Tempat</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="takeaway" onchange="toggleOrderType(this.value)" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 text-center peer-checked:border-amber-600 peer-checked:bg-amber-50/50 hover:bg-stone-50 transition-all space-y-1.5">
                                <i class="fa-solid fa-box-archive text-xl text-stone-600 peer-checked:text-amber-700 block"></i>
                                <span class="font-bold text-xs sm:text-sm text-stone-900 block">Takeaway</span>
                                <span class="text-[10px] text-stone-500 block">Bawa Pulang</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="delivery" onchange="toggleOrderType(this.value)" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 text-center peer-checked:border-amber-600 peer-checked:bg-amber-50/50 hover:bg-stone-50 transition-all space-y-1.5">
                                <i class="fa-solid fa-motorcycle text-xl text-stone-600 peer-checked:text-amber-700 block"></i>
                                <span class="font-bold text-xs sm:text-sm text-stone-900 block">Delivery</span>
                                <span class="text-[10px] text-stone-500 block">Pesan Antar</span>
                            </div>
                        </label>
                    </div>

                    <!-- Dynamic Input based on order type -->
                    <div id="location-input-container">
                        <label id="location-label" for="table_or_address" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                            Nomor Meja Resto *
                        </label>
                        <input type="text" name="table_or_address" id="table_or_address" required value="{{ old('table_or_address') }}" placeholder="Contoh: Meja No. 05" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50/50">
                    </div>

                    <!-- Extra Notes -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                            Catatan Khusus (Opsional)
                        </label>
                        <textarea name="notes" id="notes" rows="2" placeholder="Contoh: Sambal dipisah, acar raita diperbanyak, sendok garpu 2 set..." class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50/50">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 space-y-5 shadow-sm">
                    <h2 class="font-bold text-lg text-stone-900 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center">3</span>
                        <span>Metode Pembayaran</span>
                    </h2>

                    <div class="space-y-3">
                        <!-- QRIS -->
                        <label class="cursor-pointer block">
                            <input type="radio" name="payment_method" value="qris" checked class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 peer-checked:border-amber-600 peer-checked:bg-amber-50/40 hover:bg-stone-50 transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-stone-900 text-white flex items-center justify-center text-sm font-bold">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">QRIS / E-Wallet</span>
                                        <span class="text-xs text-stone-500 block">GoPay, OVO, Dana, ShopeePay & Mobile Banking</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-1 rounded-full">Praktis</span>
                            </div>
                        </label>

                        <!-- Transfer Bank -->
                        <label class="cursor-pointer block">
                            <input type="radio" name="payment_method" value="transfer" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 peer-checked:border-amber-600 peer-checked:bg-amber-50/40 hover:bg-stone-50 transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                                        <i class="fa-solid fa-building-columns"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">Transfer Bank (BCA)</span>
                                        <span class="text-xs text-stone-500 block">Rekening: {{ $bankAccount }} a.n {{ $bankHolder }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Bayar di Kasir / COD -->
                        <label class="cursor-pointer block">
                            <input type="radio" name="payment_method" value="cod" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-stone-200 peer-checked:border-amber-600 peer-checked:bg-amber-50/40 hover:bg-stone-50 transition-all flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-sm text-stone-900 block">Bayar Tunai di Kasir / COD</span>
                                        <span class="text-xs text-stone-500 block">Bayar langsung saat makanan dihidangkan atau diterima</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Right 5 Cols: Live Order Summary -->
            <div class="lg:col-span-5 sticky top-28 space-y-6">
                <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-7 shadow-sm space-y-6">
                    <h3 class="font-bold text-lg text-stone-900 pb-3 border-b border-stone-100 flex items-center justify-between">
                        <span>Pesanan Anda</span>
                        <span id="summary-items-count" class="text-xs font-semibold text-stone-500">0 Menu</span>
                    </h3>

                    <!-- Cart Items Display -->
                    <div id="checkout-summary-list" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        <!-- Populated by JS -->
                    </div>

                    <!-- Price Calculations -->
                    <div class="space-y-3 pt-4 border-t border-stone-100 text-sm">
                        <div class="flex justify-between text-stone-600">
                            <span>Subtotal Menu</span>
                            <span id="checkout-subtotal" class="font-bold text-stone-900">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-stone-600">
                            <span>Ongkos Kirim</span>
                            <span id="checkout-shipping" class="font-bold text-stone-900">Rp 0</span>
                        </div>
                        <div class="pt-3 border-t border-stone-200 flex justify-between items-baseline">
                            <span class="font-bold text-stone-900 text-base">Total Bayar</span>
                            <span id="checkout-grandtotal" class="font-serif text-2xl font-bold text-amber-700">Rp 0</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btn-submit-order" class="btn-tactile w-full py-4 bg-stone-900 hover:bg-amber-700 text-white rounded-2xl font-bold text-sm text-center shadow-lg shadow-stone-900/10 flex items-center justify-center gap-2 transition-colors">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Konfirmasi & Buat Pesanan</span>
                    </button>

                    <p class="text-[11px] text-stone-500 text-center leading-relaxed">
                        Dengan menekan tombol di atas, Anda akan dialihkan ke halaman instruksi pembayaran dan link WhatsApp resmi untuk konfirmasi langsung ke kasir.
                    </p>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    const flatShippingRate = {{ (float) $shippingFlatRate }};
    let currentOrderType = 'dine_in';

    function toggleOrderType(type) {
        currentOrderType = type;
        const label = document.getElementById('location-label');
        const input = document.getElementById('table_or_address');

        if (type === 'dine_in') {
            label.textContent = 'Nomor Meja Resto *';
            input.placeholder = 'Contoh: Meja No. 04';
        } else if (type === 'takeaway') {
            label.textContent = 'Jam Pengambilan *';
            input.placeholder = 'Contoh: Pukul 12:30 WIB';
        } else if (type === 'delivery') {
            label.textContent = 'Alamat Lengkap Pengiriman *';
            input.placeholder = 'Contoh: Jl. Tebet Timur Raya No. 45, RT 02 RW 03 (Dekat Masjid Al-Huda)';
        }

        renderCheckoutSummary();
    }

    function renderCheckoutSummary() {
        const cart = BiryaniCart.getCart();
        const listContainer = document.getElementById('checkout-summary-list');
        const hiddenItemsContainer = document.getElementById('checkout-hidden-items');
        const countEl = document.getElementById('summary-items-count');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const shippingEl = document.getElementById('checkout-shipping');
        const grandtotalEl = document.getElementById('checkout-grandtotal');
        const submitBtn = document.getElementById('btn-submit-order');

        if (cart.length === 0) {
            listContainer.innerHTML = '<p class="text-xs text-stone-500 text-center py-4">Keranjang belanja kosong. Silakan pilih menu terlebih dahulu.</p>';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

        let listHtml = '';
        let hiddenHtml = '';
        let subtotal = 0;
        let count = 0;

        cart.forEach((item, index) => {
            const itemSubtotal = item.price * item.quantity;
            subtotal += itemSubtotal;
            count += item.quantity;

            listHtml += `
                <div class="flex items-center justify-between text-xs py-2 border-b border-stone-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="${item.image}" alt="${item.name}" class="w-10 h-10 object-cover rounded-lg bg-stone-100 flex-shrink-0">
                        <div>
                            <span class="font-bold text-stone-900 block">${item.name}</span>
                            <span class="text-[10px] text-stone-500">${item.quantity}x @ ${BiryaniCart.formatRupiah(item.price)}</span>
                        </div>
                    </div>
                    <span class="font-bold text-stone-900">${BiryaniCart.formatRupiah(itemSubtotal)}</span>
                </div>
            `;

            // Prepare POST inputs for Laravel Request
            hiddenHtml += `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][notes]" value="${item.notes || ''}">
            `;
        });

        const shippingCost = currentOrderType === 'delivery' ? flatShippingRate : 0;
        const grandTotal = subtotal + shippingCost;

        listContainer.innerHTML = listHtml;
        hiddenItemsContainer.innerHTML = hiddenHtml;
        countEl.textContent = `${count} Menu`;
        subtotalEl.textContent = BiryaniCart.formatRupiah(subtotal);
        shippingEl.textContent = shippingCost > 0 ? BiryaniCart.formatRupiah(shippingCost) : 'Gratis (Rp 0)';
        grandtotalEl.textContent = BiryaniCart.formatRupiah(grandTotal);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCheckoutSummary();

        // Clear cart after successful form submission
        document.getElementById('checkout-form').addEventListener('submit', () => {
            // Keep copy in session/server, cart will be emptied when landing on success page
        });
    });
</script>
@endsection
