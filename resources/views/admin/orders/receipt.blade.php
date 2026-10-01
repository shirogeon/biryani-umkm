<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Nota Kasir - {{ $order->order_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            body { background: #fff !important; }
            .no-print { display: none !important; }
            #receipt-wrapper { box-shadow: none !important; border: none !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-stone-100 min-h-screen py-8 px-4 font-mono text-stone-900 antialiased">

    <!-- Action Bar (Hidden when printing) -->
    <div class="max-w-md mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-xs font-bold text-stone-600 hover:text-stone-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Detail Pesanan</span>
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition-colors">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Struk Nota</span>
        </button>
    </div>

    <!-- Thermal Receipt Card -->
    <div id="receipt-wrapper" class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-md border border-stone-200 text-xs space-y-5">
        
        <!-- Header -->
        <div class="text-center space-y-1 pb-4 border-b border-dashed border-stone-300">
            <h1 class="text-base font-extrabold uppercase tracking-wide">{{ $storeName }}</h1>
            <p class="text-[11px] text-stone-600">{{ $storeAddress }}</p>
            <p class="text-[11px] text-stone-600">WhatsApp: +{{ $storePhone }}</p>
            <p class="text-[10px] text-stone-400 uppercase pt-1">[SALINAN KASIR / DAPUR]</p>
        </div>

        <!-- Order Meta -->
        <div class="space-y-1 pb-4 border-b border-dashed border-stone-300">
            <div class="flex justify-between">
                <span>No. Pesanan:</span>
                <span class="font-bold">{{ $order->order_code }}</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu Dibuat:</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="flex justify-between">
                <span>Pemesan:</span>
                <span class="font-bold">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span>Telepon:</span>
                <span>{{ $order->customer_phone }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tipe Layanan:</span>
                <span class="font-bold uppercase">{{ $order->order_type_label }}</span>
            </div>
            <div class="flex justify-between">
                <span>Lokasi/Tujuan:</span>
                <span class="font-semibold text-right max-w-[200px] truncate">{{ $order->table_or_address }}</span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="space-y-2 pb-4 border-b border-dashed border-stone-300">
            <div class="flex justify-between font-bold pb-1 text-[11px] text-stone-500 uppercase">
                <span>Item</span>
                <span>Subtotal</span>
            </div>
            @foreach($order->items as $item)
            <div>
                <div class="flex justify-between">
                    <span class="font-bold">{{ $item->product_name }}</span>
                    <span>{{ $item->formatted_subtotal }}</span>
                </div>
                <div class="text-[10px] text-stone-500 flex justify-between">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    @if($item->notes)
                    <span class="italic text-amber-800 font-bold max-w-[150px] truncate">({{ $item->notes }})</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Calculation & Total -->
        <div class="space-y-1 pb-4 border-b border-dashed border-stone-300">
            <div class="flex justify-between">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @if($order->shipping_cost > 0)
            <div class="flex justify-between">
                <span>Ongkos Kirim:</span>
                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-sm pt-2 border-t border-stone-200">
                <span>TOTAL:</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
            <div class="flex justify-between text-[11px] text-stone-600 pt-1">
                <span>Metode Bayar:</span>
                <span class="font-bold uppercase">{{ $order->payment_method_label }}</span>
            </div>
            <div class="flex justify-between text-[11px] text-stone-600">
                <span>Status Bayar:</span>
                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- Notes if any -->
        @if($order->notes)
        <div class="p-2.5 bg-stone-50 border border-dashed border-stone-300 rounded text-[10px] text-stone-700 space-y-0.5">
            <span class="font-bold uppercase block">Catatan Dapur:</span>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <div class="text-center pt-2 text-[10px] text-stone-400">
            <p>Dicetak otomatis oleh Sistem Dapur Biryani Berkah</p>
        </div>

    </div>

</body>
</html>
