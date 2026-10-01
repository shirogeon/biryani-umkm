@extends('layouts.admin')

@section('page_title', 'Pengaturan Profil UMKM')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-sm">
        
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Profil Toko -->
            <div class="space-y-4">
                <h3 class="font-bold text-base text-stone-900 border-b border-stone-100 pb-2">Informasi Umum UMKM</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="store_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Nama Brand Resto *</label>
                        <input type="text" name="store_name" id="store_name" required value="{{ old('store_name', $settings['store_name'] ?? 'Dapur Nasi Biryani Berkah') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>

                    <div>
                        <label for="store_tagline" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Tagline Slogan</label>
                        <input type="text" name="store_tagline" id="store_tagline" value="{{ old('store_tagline', $settings['store_tagline'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>
                </div>

                <div>
                    <label for="store_open_hours" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Jam Operasional Layanan</label>
                    <input type="text" name="store_open_hours" id="store_open_hours" value="{{ old('store_open_hours', $settings['store_open_hours'] ?? 'Setiap Hari: 10:00 - 22:00 WIB') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                </div>

                <div>
                    <label for="store_address" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Alamat Lengkap Resto *</label>
                    <textarea name="store_address" id="store_address" rows="2" required class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Kontak & Notifikasi WhatsApp -->
            <div class="space-y-4">
                <h3 class="font-bold text-base text-stone-900 border-b border-stone-100 pb-2">Nomor WhatsApp Official & Pengiriman</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="store_phone" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">No. WhatsApp Kasir/Dapur *</label>
                        <input type="text" name="store_phone" id="store_phone" required value="{{ old('store_phone', $settings['store_phone'] ?? '6281298765432') }}" placeholder="6281234567890" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                        <span class="text-[10px] text-stone-400 mt-1 block">Gunakan awalan 62 tanpa tanda + atau 0 di depan.</span>
                    </div>

                    <div>
                        <label for="shipping_flat_rate" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Ongkos Kirim Flat Delivery (Rp) *</label>
                        <input type="number" name="shipping_flat_rate" id="shipping_flat_rate" required min="0" value="{{ old('shipping_flat_rate', $settings['shipping_flat_rate'] ?? 10000) }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>
                </div>
            </div>

            <!-- Section 3: Informasi Rekening & QRIS -->
            <div class="space-y-4">
                <h3 class="font-bold text-base text-stone-900 border-b border-stone-100 pb-2">Rekening Bank & QRIS</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="bank_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Nama Bank</label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $settings['bank_name'] ?? 'BCA') }}" placeholder="Bank Central Asia (BCA)" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>

                    <div>
                        <label for="bank_account_number" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number'] ?? '8735019281') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>

                    <div>
                        <label for="bank_account_holder" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Atas Nama (Pemilik)</label>
                        <input type="text" name="bank_account_holder" id="bank_account_holder" value="{{ old('bank_account_holder', $settings['bank_account_holder'] ?? 'Dapur Biryani Berkah') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                    </div>
                </div>

                <div>
                    <label for="qris_merchant_name" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">Nama Merchant QRIS</label>
                    <input type="text" name="qris_merchant_name" id="qris_merchant_name" value="{{ old('qris_merchant_name', $settings['qris_merchant_name'] ?? 'DAPUR BIRYANI BERKAH QRIS') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-xs font-medium focus:ring-1 focus:ring-amber-500 bg-stone-50">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-stone-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-stone-900 hover:bg-stone-800 text-white rounded-xl font-bold text-xs shadow-md transition-colors">
                    Simpan Seluruh Pengaturan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
