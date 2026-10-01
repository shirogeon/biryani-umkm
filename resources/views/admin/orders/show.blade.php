@extends('layouts.admin')

@section('page_title', 'Rincian Pesanan - ' . $order->order_code)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-stone-600 hover:text-stone-900 inline-flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ $waCustomerUrl }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-2 shadow-sm transition-colors">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Kirim Notifikasi WA ke Pelanggan</span>
            </a>
            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="px-4 py-2 bg-white hover:bg-stone-100 text-stone-700 border border-stone-300 rounded-xl font-bold text-xs flex items-center gap-2 shadow-sm transition-colors">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Struk Nota</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left 8 Cols: Order Items & Customer Notes -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Items Card -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm space-y-6">
                <h3 class="font-bold text-base text-stone-900 border-b border-stone-100 pb-3">Daftar Menu Dipesan</h3>

                <div class="divide-y divide-stone-100">
                    @foreach($order->items as $item)
                    <div class="py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-14 h-14 object-cover rounded-xl bg-stone-100 flex-shrink-0">
                            @else
                            <div class="w-14 h-14 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                <i class="fa-solid fa-bowl-rice"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-stone-900 text-sm">{{ $item->product_name }}</h4>
                                <span class="text-xs text-stone-500">{{ $item->quantity }} x {{ $item->formatted_price }}</span>
                                @if($item->notes)
                                <p class="text-[11px] text-amber-800 italic mt-0.5">Catatan: {{ $item->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="font-bold text-stone-900 text-sm">{{ $item->formatted_subtotal }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Price Calculations -->
                <div class="pt-4 border-t border-stone-200 space-y-2 text-xs">
                    <div class="flex justify-between text-stone-600">
                        <span>Subtotal Menu</span>
                        <span class="font-semibold text-stone-900">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between text-stone-600">
                        <span>Ongkos Kirim (Flat Delivery)</span>
                        <span class="font-semibold text-stone-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-black text-base text-stone-950 pt-2 border-t border-stone-100">
                        <span>Total Tagihan</span>
                        <span class="text-amber-700 font-serif text-lg">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Notes Card -->
            @if($order->notes)
            <div class="bg-amber-50/70 border border-amber-200 rounded-3xl p-6 shadow-sm space-y-1">
                <span class="text-[10px] font-bold text-amber-900 uppercase tracking-wider block">Catatan Tambahan dari Pembeli</span>
                <p class="text-xs text-amber-950 font-medium leading-relaxed">{{ $order->notes }}</p>
            </div>
            @endif

        </div>

        <!-- Right 4 Cols: Status Manager & Customer Info -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- 1. Change Status Form Card -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-stone-900 border-b border-stone-100 pb-2">Ubah Status Pesanan</h3>

                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="order_status" class="block text-[11px] font-bold text-stone-600 uppercase tracking-wider mb-1">Status Pengerjaan</label>
                        <select name="order_status" id="order_status" class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-semibold focus:outline-none focus:border-amber-600 bg-stone-50">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi (Pending)</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Sedang Dimasak (Processing)</option>
                            <option value="delivering" {{ $order->order_status === 'delivering' ? 'selected' : '' }}>Siap / Diantar (Delivering)</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="block text-[11px] font-bold text-stone-600 uppercase tracking-wider mb-1">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-semibold focus:outline-none focus:border-amber-600 bg-stone-50">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-stone-900 hover:bg-stone-800 text-white rounded-xl font-bold text-xs transition-colors">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- 2. Customer Profile Card -->
            <div class="bg-white rounded-3xl border border-stone-200 p-6 shadow-sm space-y-4 text-xs">
                <h3 class="font-bold text-sm text-stone-900 border-b border-stone-100 pb-2">Informasi Pemesan</h3>

                <div class="space-y-3">
                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase font-bold">Nama</span>
                        <span class="font-bold text-stone-900 text-sm">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase font-bold">No. WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="font-bold text-emerald-700 hover:underline flex items-center gap-1.5 mt-0.5">
                            <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                            <span>{{ $order->customer_phone }}</span>
                        </a>
                    </div>

                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase font-bold">Layanan</span>
                        <span class="font-semibold text-stone-900">{{ $order->order_type_label }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase font-bold">Tujuan / Lokasi</span>
                        <span class="font-semibold text-stone-900">{{ $order->table_or_address }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] text-stone-400 block uppercase font-bold">Metode Bayar</span>
                        <span class="font-semibold text-stone-900">{{ $order->payment_method_label }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Delete Order -->
            <div class="text-right">
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold p-1">
                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus Pesanan Ini
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
