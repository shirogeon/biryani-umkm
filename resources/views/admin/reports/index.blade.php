@extends('layouts.admin')

@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    
    <!-- Filter Bar & Date Picker -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label for="start_date" class="text-xs font-bold text-stone-600">Dari:</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-3 py-2 rounded-xl border border-stone-300 text-xs font-semibold focus:outline-none focus:border-amber-600 bg-stone-50">
            </div>

            <div class="flex items-center gap-2">
                <label for="end_date" class="text-xs font-bold text-stone-600">Sampai:</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-3 py-2 rounded-xl border border-stone-300 text-xs font-semibold focus:outline-none focus:border-amber-600 bg-stone-50">
            </div>

            <button type="submit" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold transition-colors">
                Tampilkan Laporan
            </button>
        </form>

        <button onclick="window.print()" class="px-4 py-2 bg-white hover:bg-stone-100 text-stone-700 border border-stone-300 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition-colors">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Revenue -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Pendapatan</span>
            <span class="text-2xl font-black text-amber-700 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            <span class="text-[11px] text-stone-400">Pada rentang tanggal yang dipilih</span>
        </div>

        <!-- Total Orders -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Total Transaksi Selesai</span>
            <span class="text-2xl font-black text-stone-900 block">{{ $totalOrdersCount }} Pesanan</span>
            <span class="text-[11px] text-stone-400">Order yang berhasil diproses</span>
        </div>

        <!-- Average Order Value (AOV) -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Rata-rata Transaksi</span>
            <span class="text-2xl font-black text-stone-900 block">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</span>
            <span class="text-[11px] text-stone-400">Nilai belanja per pelanggan</span>
        </div>

        <!-- Total Portions Sold -->
        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-sm space-y-1">
            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider block">Porsi Terjual</span>
            <span class="text-2xl font-black text-stone-900 block">{{ $totalItemsSold }} Porsi</span>
            <span class="text-[11px] text-stone-400">Total item makanan & minuman</span>
        </div>

    </div>

    <!-- Orders Detail Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex items-center justify-between">
            <h3 class="font-bold text-base text-stone-900">Rincian Transaksi Penjualan</h3>
            <span class="text-xs text-stone-500">{{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-bold border-b border-stone-200">
                    <tr>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6">No. Pesanan</th>
                        <th class="py-3.5 px-6">Nama Pelanggan</th>
                        <th class="py-3.5 px-6">Layanan</th>
                        <th class="py-3.5 px-6">Metode Bayar</th>
                        <th class="py-3.5 px-6 text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <td class="py-3.5 px-6 font-medium text-stone-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3.5 px-6 font-mono font-bold text-stone-900">
                            {{ $order->order_code }}
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-stone-900">
                            {{ $order->customer_name }}
                        </td>
                        <td class="py-3.5 px-6">
                            {{ $order->order_type_label }}
                        </td>
                        <td class="py-3.5 px-6 uppercase font-medium">
                            {{ $order->payment_method_label }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-bold text-stone-900">
                            {{ $order->formatted_total }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-stone-400">
                            Tidak ada transaksi pada periode tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
