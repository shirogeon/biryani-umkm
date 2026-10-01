@extends('layouts.admin')

@section('page_title', 'Kelola Pesanan Pelanggan')

@section('content')
<div class="space-y-6">
    
    <!-- Top Filter Header -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
        <!-- Status Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-bold">
            <a href="{{ route('admin.orders.index', ['status' => 'all', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status', 'all') === 'all' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                Menunggu Konfirmasi ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status') === 'processing' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                Sedang Dimasak ({{ $counts['processing'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivering', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status') === 'delivering' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                Siap / Diantar ({{ $counts['delivering'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status') === 'completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                Selesai ({{ $counts['completed'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled', 'search' => request('search')]) }}" class="px-4 py-2.5 rounded-xl transition-all {{ request('status') === 'cancelled' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                Dibatalkan ({{ $counts['cancelled'] }})
            </a>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nomor order, nama pelanggan, atau no. HP..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:outline-none focus:border-amber-600">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-stone-400 text-xs"></i>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold transition-colors">
                Cari Pesanan
            </button>
            @if(request('search'))
            <a href="{{ route('admin.orders.index', ['status' => request('status', 'all')]) }}" class="px-4 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-xs font-semibold flex items-center gap-1">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-bold border-b border-stone-200">
                    <tr>
                        <th class="py-4 px-6">No. Pesanan</th>
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Layanan</th>
                        <th class="py-4 px-6">Rincian Menu</th>
                        <th class="py-4 px-6">Total Tagihan</th>
                        <th class="py-4 px-6">Status Pesanan</th>
                        <th class="py-4 px-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <!-- Order Code -->
                        <td class="py-4 px-6 font-mono font-bold text-stone-900">
                            {{ $order->order_code }}
                            <span class="block text-[10px] text-stone-400 font-sans font-normal">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </td>

                        <!-- Customer Info -->
                        <td class="py-4 px-6">
                            <span class="font-bold text-stone-900 block">{{ $order->customer_name }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="text-[11px] text-emerald-700 hover:underline flex items-center gap-1 mt-0.5">
                                <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                                <span>{{ $order->customer_phone }}</span>
                            </a>
                        </td>

                        <!-- Service Type -->
                        <td class="py-4 px-6">
                            <span class="font-semibold text-stone-900 block">{{ $order->order_type_label }}</span>
                            <span class="text-[11px] text-stone-500 truncate max-w-[160px] block" title="{{ $order->table_or_address }}">
                                {{ $order->table_or_address }}
                            </span>
                        </td>

                        <!-- Items Summary -->
                        <td class="py-4 px-6">
                            <span class="font-bold text-stone-900 block">{{ $order->items->sum('quantity') }} Porsi</span>
                            <span class="text-[11px] text-stone-500 block truncate max-w-[180px]">
                                {{ $order->items->pluck('product_name')->implode(', ') }}
                            </span>
                        </td>

                        <!-- Total -->
                        <td class="py-4 px-6">
                            <span class="font-bold text-stone-900 block">{{ $order->formatted_total }}</span>
                            <span class="text-[10px] uppercase font-semibold {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                                {{ $order->payment_method_label }} ({{ $order->payment_status }})
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-stone-900 hover:bg-stone-800 text-white rounded-lg font-bold text-[11px] transition-colors inline-flex items-center gap-1.5">
                                <span>Rincian</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="p-1.5 text-stone-500 hover:text-stone-900 hover:bg-stone-100 rounded-lg inline-block" title="Cetak Nota">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-stone-400">
                            Tidak ada data pesanan yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-stone-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
