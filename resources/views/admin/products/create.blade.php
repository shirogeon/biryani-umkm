@extends('layouts.admin')

@section('page_title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div class="pb-2">
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-stone-600 hover:text-stone-900 inline-flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Menu</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Kategori Menu *</label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Product Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Nama Menu *</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Contoh: Nasi Biryani Kambing Bakar Spesial" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">
                @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Price & Portion & Spicy Level -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Harga (Rp) *</label>
                    <input type="number" name="price" id="price" required min="0" value="{{ old('price') }}" placeholder="Contoh: 45000" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">
                    @error('price') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="portion_size" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Porsi / Ukuran</label>
                    <input type="text" name="portion_size" id="portion_size" value="{{ old('portion_size', '1 Porsi') }}" placeholder="Contoh: 1 Porsi / Loyang (4 org)" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">
                </div>

                <div>
                    <label for="spiciness_level" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Level Kepedasan</label>
                    <select name="spiciness_level" id="spiciness_level" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">
                        <option value="0" {{ old('spiciness_level') == '0' ? 'selected' : '' }}>0 - Tidak Pedas</option>
                        <option value="1" {{ old('spiciness_level', '1') == '1' ? 'selected' : '' }}>1 - Pedas Gurih Lembut</option>
                        <option value="2" {{ old('spiciness_level') == '2' ? 'selected' : '' }}>2 - Pedas Sedang Mantap</option>
                        <option value="3" {{ old('spiciness_level') == '3' ? 'selected' : '' }}>3 - Pedas Rempah Nampol</option>
                        <option value="4" {{ old('spiciness_level') == '4' ? 'selected' : '' }}>4 - Ekstra Pedas</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">Deskripsi Rasa & Rempah</label>
                <textarea name="description" id="description" rows="3" placeholder="Tuliskan racikan bumbu, keempukan daging, atau keunggulan menu..." class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none bg-stone-50">{{ old('description') }}</textarea>
                @error('description') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Image Options (File upload or URL) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-200">
                <div>
                    <label for="image_file" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Unggah Berkas Foto</label>
                    <input type="file" name="image_file" id="image_file" accept="image/*" class="w-full text-xs text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stone-900 file:text-white hover:file:bg-amber-700">
                    <span class="text-[10px] text-stone-400 mt-1 block">JPG, PNG, atau WebP maksimal 2MB.</span>
                </div>
                <div>
                    <label for="image_url" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Atau Gunakan Tautan Foto (URL)</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/..." class="w-full px-3 py-2 rounded-xl border border-stone-300 text-xs font-medium focus:outline-none focus:border-amber-600 bg-white">
                </div>
            </div>

            <!-- Checkboxes (Availability & Featured) -->
            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" checked class="rounded border-stone-300 text-amber-600 focus:ring-0">
                    <span class="text-xs font-bold text-stone-800">Status Menu: Tersedia (Ready Stok)</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-stone-300 text-amber-600 focus:ring-0">
                    <span class="text-xs font-bold text-stone-800">Jadikan Menu Rekomendasi (Featured)</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-300 text-stone-700 font-bold text-xs hover:bg-stone-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl font-bold text-xs shadow-md shadow-amber-600/20 transition-colors">
                    Simpan Menu Baru
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
