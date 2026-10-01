@extends('layouts.app')

@section('title', 'Lacak Status Pesanan - Dapur Biryani Berkah')

@section('content')
<div class="py-12 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-10 space-y-3">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block">Pelacakan Mandiri</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">Lacak Status Pesanan</h1>
            <p class="text-stone-600 text-sm">
                Masukkan Kode Pesanan Anda (contoh: <code>BRY-20260929-XXXX</code>) atau Nomor WhatsApp yang digunakan saat memesan.
            </p>
        </div>

        <!-- Search Box -->
        <form action="{{ route('order.track') }}" method="GET" class="mb-10 max-w-lg mx-auto">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="query" value="{{ $search ?? '' }}" required placeholder="Masukkan Kode Pesanan atau No. HP..." class="w-full pl-10 pr-4 py-3.5 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none shadow-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-4 text-stone-400 text-sm"></i>
                </div>
                <button type="submit" class="btn-tactile px-6 py-3.5 bg-stone-900 hover:bg-amber-700 text-white rounded-2xl font-bold text-sm shadow-sm transition-colors flex items-center gap-2 flex-shrink-0">
                    <span>Cari</span>
                </button>
            </div>
        </form>

        @if(isset($search) && !$order)
        <!-- Not Found State -->
        <div class="bg-white rounded-3xl border border-stone-200 p-10 text-center space-y-4 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center text-2xl mx-auto">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h3 class="font-bold text-stone-900 text-lg">Pesanan Tidak Ditemukan</h3>
            <p class="text-xs text-stone-500 max-w-sm mx-auto">
                Tidak ada pesanan aktif yang cocok dengan kode <strong>"{{ $search }}"</strong>. Pastikan nomor yang Anda ketik sudah benar.
            </p>
        </div>
        @elseif($order)
        <!-- Order Tracking Card -->
        <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-10 shadow-sm space-y-8">
            
            <!-- Top Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-stone-100 gap-4">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider block">Kode Pesanan</span>
                    <span class="font-mono text-2xl font-bold text-stone-900">{{ $order->order_code }}</span>
                    <span class="text-xs text-stone-500 block mt-0.5">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="inline-block px-3.5 py-1.5 rounded-full text-xs font-bold border {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                    <span class="text-xs text-stone-500 block mt-1">{{ $order->order_type_label }}</span>
                </div>
            </div>

            <!-- Visual Status Timeline -->
            @if($order->order_status === 'cancelled')
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark text-lg text-rose-600"></i>
                <div>
                    <span class="font-bold block text-sm">Pesanan Dibatalkan</span>
                    <span>Pesanan ini telah dibatalkan oleh kasir atau pelanggan.</span>
                </div>
            </div>
            @else
            @php
                $steps = [
                    'pending' => ['label' => 'Menunggu Konfirmasi', 'desc' => 'Pesanan diterima oleh sistem kasir.'],
                    'processing' => ['label' => 'Sedang Dimasak', 'desc' => 'Dapur sedang meracik rempah & porsi Anda.'],
                    'delivering' => ['label' => $order->order_type === 'delivery' ? 'Sedang Diantar' : 'Siap Disajikan', 'desc' => $order->order_type === 'delivery' ? 'Kurir sedang dalam perjalanan ke lokasi.' : 'Siap di meja atau kasir.'],
                    'completed' => ['label' => 'Pesanan Selesai', 'desc' => 'Pesanan telah selesai dinikmati.'],
                ];

                $statusWeights = [
                    'pending' => 1,
                    'processing' => 2,
                    'delivering' => 3,
                    'completed' => 4,
                ];

                $currentWeight = $statusWeights[$order->order_status] ?? 1;
            @endphp

            <div class="relative pl-6 sm:pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-stone-200">
                @foreach($steps as $key => $step)
                @php
                    $stepWeight = $statusWeights[$key];
                    $isPassed = $currentWeight >= $stepWeight;
                    $isCurrent = $currentWeight === $stepWeight;
                @endphp
                <div class="relative group">
                    <!-- Bullet Icon -->
                    <div class="absolute -left-6 sm:-left-8 top-0.5 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $isCurrent ? 'bg-amber-600 text-white ring-4 ring-amber-100' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-stone-200 text-stone-500') }}">
                        @if($isPassed && !$isCurrent)
                            <i class="fa-solid fa-check text-[10px]"></i>
                        @else
                            {{ $stepWeight }}
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-sm {{ $isCurrent ? 'text-amber-800' : ($isPassed ? 'text-stone-900' : 'text-stone-400') }}">
                            {{ $step['label'] }}
                        </h4>
                        <p class="text-xs text-stone-500 mt-0.5">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Location / Table Detail -->
            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-1 text-xs text-stone-600">
                <span class="font-bold text-stone-900 block">Tujuan / Lokasi Penyerahan:</span>
                <p class="text-stone-800 font-semibold">{{ $order->table_or_address }}</p>
                <p>Nama Pemesan: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})</p>
            </div>

            <!-- Items List -->
            <div class="border-t border-stone-100 pt-6 space-y-3">
                <h4 class="font-bold text-xs uppercase tracking-wider text-stone-400">Rincian Menu</h4>
                <div class="divide-y divide-stone-100">
                    @foreach($order->items as $item)
                    <div class="py-2 flex justify-between text-xs sm:text-sm">
                        <span>{{ $item->product_name }} <strong class="text-stone-500">x{{ $item->quantity }}</strong></span>
                        <span class="font-bold text-stone-900">{{ $item->formatted_subtotal }}</span>
                    </div>
                    @endforeach
                    <div class="py-2.5 flex justify-between font-bold text-sm text-stone-950">
                        <span>Total Bayar</span>
                        <span class="font-serif text-base text-amber-700">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Query Button -->
            <div class="pt-4 border-t border-stone-100 flex flex-col sm:flex-row gap-3">
                <a href="https://wa.me/{{ \App\Models\StoreSetting::get('store_phone', '6281298765432') }}?text={{ rawurlencode('Halo Kasir Dapur Biryani, saya ingin menanyakan status pesanan saya dengan kode: ' . $order->order_code) }}" target="_blank" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs text-center flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Tanya Dapur via WhatsApp</span>
                </a>
                <a href="{{ route('order.receipt', ['code' => $order->order_code]) }}" target="_blank" class="w-full py-3 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-xl font-bold text-xs text-center flex items-center justify-center gap-2 transition-colors">
                    <i class="fa-solid fa-receipt text-stone-500"></i>
                    <span>Cetak Nota Struk</span>
                </a>
            </div>

        </div>
        @endif

    </div>
</div>
@endsection
