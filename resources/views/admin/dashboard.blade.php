@extends('layouts.admin')

@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-8">
    
    <!-- 1. KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Today's Revenue -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Pendapatan Hari Ini</span>
                <span class="text-2xl font-black text-stone-900 block">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>{{ $todayOrdersCount }} Pesanan Masuk Hari Ini</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-cash-register"></i>
            </div>
        </div>

        <!-- Card 2: Month's Revenue -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Pendapatan Bulan Ini</span>
                <span class="text-2xl font-black text-stone-900 block">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] text-stone-500 font-semibold">
                    Total {{ $monthOrdersCount }} Transaksi
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <!-- Card 3: Pending Orders (Kitchen alert) -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Menunggu Konfirmasi</span>
                <span class="text-2xl font-black text-amber-700 block">{{ $pendingOrdersCount }}</span>
                <span class="text-[11px] text-amber-800 font-semibold">
                    Perlu ditinjau kasir
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-900 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Card 4: Processing / Kitchen Active -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Sedang Dimasak</span>
                <span class="text-2xl font-black text-blue-700 block">{{ $processingOrdersCount }}</span>
                <span class="text-[11px] text-blue-800 font-semibold">
                    Aktif di dapur sekarang
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
        </div>

    </div>

    <!-- 2. Mid Section: 7-Day Trend & Top 5 Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left 7 Cols: 7-Day Trend Visual Bars -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <div>
                    <h3 class="font-bold text-base text-stone-900">Tren Penjualan 7 Hari Terakhir</h3>
                    <p class="text-xs text-stone-500">Performa omzet harian Dapur Biryani Berkah</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800">
                    Laporan Lengkap &rarr;
                </a>
            </div>

            <div class="space-y-4">
                @php
                    $maxRev = 0;
                    foreach($salesTrend as $day) {
                        if ($day['revenue'] > $maxRev) $maxRev = $day['revenue'];
                    }
                    if ($maxRev == 0) $maxRev = 1;
                @endphp

                @foreach($salesTrend as $day)
                @php $pct = round(($day['revenue'] / $maxRev) * 100); @endphp
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-stone-700">{{ $day['date'] }} ({{ $day['day_name'] }}) - {{ $day['orders'] }} Pesanan</span>
                        <span class="font-bold text-stone-900">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-stone-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-amber-600 h-full rounded-full transition-all duration-700" style="width: {{ max($pct, 4) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right 5 Cols: Top 5 Best Sellers -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <div>
                    <h3 class="font-bold text-base text-stone-900">Menu Terlaris</h3>
                    <p class="text-xs text-stone-500">Top 5 item paling banyak dipesan</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800">
                    Semua Menu
                </a>
            </div>

            <div class="divide-y divide-stone-100">
                @forelse($topProducts as $idx => $prod)
                <div class="py-3 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-[10px]">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <span class="font-bold text-stone-900 block">{{ $prod->product_name }}</span>
                            <span class="text-[11px] text-stone-500">{{ $prod->total_qty }} porsi terjual</span>
                        </div>
                    </div>
                    <span class="font-bold text-stone-900">Rp {{ number_format($prod->total_sales, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-xs text-stone-400 py-6 text-center">Belum ada data penjualan tercatat.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 3. Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-base text-stone-900">Pesanan Masuk Terbaru</h3>
                <p class="text-xs text-stone-500">Daftar transaksi yang baru saja diterima</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold transition-colors">
                Kelola Semua Pesanan &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-bold border-b border-stone-200">
                    <tr>
                        <th class="py-3.5 px-6">No. Pesanan</th>
                        <th class="py-3.5 px-6">Pelanggan</th>
                        <th class="py-3.5 px-6">Tipe & Tujuan</th>
                        <th class="py-3.5 px-6">Total Bayar</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-stone-900">
                            {{ $order->order_code }}
                            <span class="block text-[10px] text-stone-400 font-sans font-normal">{{ $order->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-stone-900 block">{{ $order->customer_name }}</span>
                            <span class="text-[11px] text-stone-500">{{ $order->customer_phone }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold text-stone-900 block">{{ $order->order_type_label }}</span>
                            <span class="text-[11px] text-stone-500 truncate max-w-[180px] block">{{ $order->table_or_address }}</span>
                        </td>
                        <td class="py-4 px-6 font-bold text-stone-900">
                            {{ $order->formatted_total }}
                            <span class="block text-[10px] text-stone-400 font-normal uppercase">{{ $order->payment_method_label }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 text-stone-600 hover:text-stone-900 rounded-lg hover:bg-stone-200 transition-colors inline-block" title="Rincian Pesanan">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="p-2 text-stone-600 hover:text-stone-900 rounded-lg hover:bg-stone-200 transition-colors inline-block" title="Cetak Nota">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-stone-400">Belum ada pesanan terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
