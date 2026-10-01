@extends('layouts.app')

@section('title', 'Pesanan Berhasil - ' . $order->order_code)

@section('content')
<div class="py-12 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Card -->
        <div class="bg-white rounded-3xl border border-stone-200 p-8 sm:p-12 shadow-sm text-center space-y-8">
            
            <!-- Checkmark Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                <i class="fa-solid fa-check"></i>
            </div>

            <div class="space-y-2">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block">Pesanan Berhasil Dibuat</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">Terima Kasih, {{ $order->customer_name }}!</h1>
                <p class="text-stone-600 text-sm max-w-lg mx-auto">
                    Pesanan Anda telah tercatat di sistem dapur kami dengan nomor referensi di bawah ini.
                </p>
            </div>

            <!-- Order Code Box -->
            <div class="p-6 rounded-2xl bg-amber-50/70 border border-amber-200/80 max-w-md mx-auto space-y-1">
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block">Kode Pesanan Anda</span>
                <div class="flex items-center justify-center gap-3">
                    <span class="font-mono text-2xl sm:text-3xl font-black text-amber-950 tracking-wider">{{ $order->order_code }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode pesanan disalin ke clipboard!');" class="p-2 text-amber-800 hover:text-amber-950 rounded-lg hover:bg-amber-100 transition-colors" title="Salin Kode">
                        <i class="fa-regular fa-copy text-base"></i>
                    </button>
                </div>
                <span class="text-[11px] text-stone-500 block pt-1">Simpan kode ini untuk melacak status proses pesanan Anda.</span>
            </div>

            <!-- Prominent WhatsApp Confirmation Button -->
            <div class="space-y-3 pt-2 max-w-md mx-auto">
                <a href="{{ $waUrl }}" target="_blank" class="btn-tactile w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2.5 transition-colors">
                    <i class="fa-brands fa-whatsapp text-xl"></i>
                    <span>Kirim Konfirmasi ke WhatsApp Kasir</span>
                </a>
                <p class="text-[11px] text-stone-500">
                    Klik tombol di atas untuk membuka pesan otomatis ke kasir dapur kami agar pesanan segera diverifikasi.
                </p>
            </div>

            <!-- Payment Details Box -->
            <div class="text-left border-t border-stone-100 pt-8 space-y-4">
                <h3 class="font-bold text-base text-stone-900">Instruksi Pembayaran: {{ $order->payment_method_label }}</h3>

                @if($order->payment_method === 'qris')
                <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 flex flex-col sm:flex-row items-center gap-6">
                    <div class="w-36 h-36 bg-white p-2 rounded-xl border border-stone-300 shadow-sm flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-qrcode text-7xl text-stone-800"></i>
                    </div>
                    <div class="space-y-2 text-xs text-stone-600">
                        <span class="font-bold text-stone-900 text-sm block">{{ $qrisMerchant }}</span>
                        <p>1. Buka aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay) atau Mobile Banking BCA/Mandiri/BRI.</p>
                        <p>2. Pindai QRIS kasir di resto atau transfer total tagihan sebesar <strong class="text-amber-700 font-extrabold text-sm">{{ $order->formatted_total }}</strong>.</p>
                        <p>3. Simpan dan kirim bukti tangkapan layar pembayaran ke WhatsApp kami.</p>
                    </div>
                </div>
                @elseif($order->payment_method === 'transfer')
                <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 space-y-2 text-xs text-stone-600">
                    <span class="font-bold text-stone-900 text-sm block">Transfer Bank Rekening Resmi</span>
                    <p class="text-sm font-semibold text-stone-800">{{ $bankInfo }}</p>
                    <p>Total yang harus ditransfer: <strong class="text-amber-700 font-extrabold text-sm">{{ $order->formatted_total }}</strong></p>
                    <p class="pt-1">Harap cantumkan kode pesanan <strong>{{ $order->order_code }}</strong> pada berita transfer.</p>
                </div>
                @else
                <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 text-xs text-stone-600 space-y-1">
                    <span class="font-bold text-stone-900 text-sm block">Bayar di Tempat (Tunai / Kasir)</span>
                    <p>Silakan siapkan uang pas sebesar <strong class="text-amber-700 font-extrabold text-sm">{{ $order->formatted_total }}</strong> saat pesanan disajikan di meja atau diterima dari kurir.</p>
                </div>
                @endif
            </div>

            <!-- Items Ordered Breakdown -->
            <div class="text-left border-t border-stone-100 pt-6 space-y-3">
                <h4 class="font-bold text-xs uppercase tracking-wider text-stone-500">Rincian Menu Dipesan</h4>
                <div class="divide-y divide-stone-100">
                    @foreach($order->items as $item)
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <div>
                            <span class="font-bold text-stone-900">{{ $item->product_name }}</span>
                            <span class="text-xs text-stone-500 ml-2">{{ $item->quantity }}x</span>
                            @if($item->notes)
                            <span class="block text-[11px] text-stone-400 italic">Catatan: {{ $item->notes }}</span>
                            @endif
                        </div>
                        <span class="font-bold text-stone-900">{{ $item->formatted_subtotal }}</span>
                    </div>
                    @endforeach

                    @if($order->shipping_cost > 0)
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-stone-600">Ongkos Kirim Flat</span>
                        <span class="font-bold text-stone-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="py-3 flex justify-between items-baseline text-base font-extrabold text-stone-950">
                        <span>Total Pembayaran</span>
                        <span class="font-serif text-xl text-amber-700">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="pt-6 border-t border-stone-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('order.track', ['code' => $order->order_code]) }}" class="py-3 px-4 bg-stone-900 hover:bg-stone-800 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-location-dot text-amber-500"></i>
                    <span>Lacak Status Pesanan</span>
                </a>
                <a href="{{ route('order.receipt', ['code' => $order->order_code]) }}" target="_blank" class="py-3 px-4 bg-white hover:bg-stone-100 text-stone-700 border border-stone-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-receipt text-stone-500"></i>
                    <span>Lihat & Cetak Nota Struk</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Clear local storage cart since order is placed
        BiryaniCart.clearCart();
    });
</script>
@endsection
