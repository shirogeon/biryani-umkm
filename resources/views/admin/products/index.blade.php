@extends('layouts.admin')

@section('page_title', 'Kelola Menu Biryani')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search and Category Filter -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:outline-none focus:border-amber-600">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-stone-400 text-xs"></i>
            </div>

            <select name="category_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 bg-stone-50 focus:outline-none focus:border-amber-600">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-bold transition-colors">
                Filter
            </button>
        </form>

        <!-- Add Product Button -->
        <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-600/20 flex items-center justify-center gap-2 transition-colors">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Menu Baru</span>
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-bold border-b border-stone-200">
                    <tr>
                        <th class="py-4 px-6">Menu</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Harga & Porsi</th>
                        <th class="py-4 px-6">Kepedasan</th>
                        <th class="py-4 px-6 text-center">Status Ketersediaan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <!-- Product Info & Image -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover bg-stone-100 flex-shrink-0 shadow-sm">
                                <div>
                                    <span class="font-bold text-stone-900 block text-sm">{{ $product->name }}</span>
                                    <span class="text-[11px] text-stone-500 line-clamp-1 max-w-xs">{{ $product->description }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-stone-100 text-stone-800 text-[11px] font-semibold">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>

                        <!-- Price -->
                        <td class="py-4 px-6">
                            <span class="font-bold text-stone-900 block text-sm">{{ $product->formatted_price }}</span>
                            <span class="text-[10px] text-stone-500 block">{{ $product->portion_size }}</span>
                        </td>

                        <!-- Spicy -->
                        <td class="py-4 px-6">
                            @if($product->spiciness_level > 0)
                            <span class="text-xs font-semibold text-stone-800">🌶️ Level {{ $product->spiciness_level }}</span>
                            @else
                            <span class="text-xs text-stone-400">Tidak Pedas</span>
                            @endif
                        </td>

                        <!-- Availability Toggle -->
                        <td class="py-4 px-6 text-center">
                            <button onclick="toggleAvailability({{ $product->id }}, this)" 
                                    class="px-3 py-1.5 rounded-full text-xs font-bold border transition-all {{ $product->is_available ? 'bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100' : 'bg-rose-50 text-rose-800 border-rose-300 hover:bg-rose-100' }}"
                                    title="Klik untuk ubah status ketersediaan">
                                {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                            </button>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-stone-600 hover:text-stone-900 rounded-lg hover:bg-stone-100 transition-colors inline-block" title="Edit Menu">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Menu">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-stone-400">Tidak ada menu yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-stone-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    function toggleAvailability(productId, btn) {
        fetch(`/admin/products/${productId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.is_available) {
                    btn.className = 'px-3 py-1.5 rounded-full text-xs font-bold border transition-all bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100';
                    btn.textContent = 'Tersedia';
                } else {
                    btn.className = 'px-3 py-1.5 rounded-full text-xs font-bold border transition-all bg-rose-50 text-rose-800 border-rose-300 hover:bg-rose-100';
                    btn.textContent = 'Habis';
                }
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
