@extends('layouts.admin')

@section('page_title', 'Kelola Kategori Menu')

@section('content')
<div class="space-y-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left 4 Cols: Add Category Form -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
            <h3 class="font-bold text-base text-stone-900 border-b border-stone-100 pb-3">Tambah Kategori Baru</h3>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Nama Kategori *</label>
                    <input type="text" name="name" id="name" required placeholder="Contoh: Paket Nasi Box Hemat" class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:outline-none bg-stone-50">
                    @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="icon" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Ikon FontAwesome</label>
                    <input type="text" name="icon" id="icon" value="fa-bowl-rice" placeholder="fa-bowl-rice / fa-utensils" class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:outline-none bg-stone-50">
                </div>

                <div>
                    <label for="sort_order" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Urutan Tampil (Angka)</label>
                    <input type="number" name="sort_order" id="sort_order" value="0" class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:outline-none bg-stone-50">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                    <textarea name="description" id="description" rows="2" placeholder="Keterangan singkat tentang kelompok menu ini..." class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 focus:outline-none bg-stone-50"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-bold text-xs shadow-md shadow-amber-600/20 transition-colors">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Right 8 Cols: Categories Table -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-stone-100">
                <h3 class="font-bold text-base text-stone-900">Daftar Kategori Menu</h3>
                <p class="text-xs text-stone-500">Kelompok kategori yang tampil di navigasi pemesanan pelanggan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-bold border-b border-stone-200">
                        <tr>
                            <th class="py-3.5 px-6">Nama & Ikon</th>
                            <th class="py-3.5 px-6">Slug</th>
                            <th class="py-3.5 px-6 text-center">Jumlah Menu</th>
                            <th class="py-3.5 px-6 text-center">Urutan</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-stone-700">
                        @forelse($categories as $category)
                        <tr class="hover:bg-stone-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0">
                                        <i class="fa-solid {{ $category->icon ?? 'fa-bowl-rice' }}"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-stone-900 block">{{ $category->name }}</span>
                                        <span class="text-[11px] text-stone-500 line-clamp-1 max-w-xs">{{ $category->description }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-mono text-[11px] text-stone-500">
                                {{ $category->slug }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-stone-100 text-stone-800 font-bold text-xs">
                                    {{ $category->products_count }} Menu
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-stone-600">
                                {{ $category->sort_order }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Kategori">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-stone-400">Belum ada kategori yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
