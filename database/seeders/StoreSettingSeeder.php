<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'store_name', 'value' => 'Dapur Nasi Biryani Berkah', 'group' => 'general'],
            ['key' => 'store_tagline', 'value' => 'Rasa Rempah Autentik, Beras Basmati Premium Pilihan', 'group' => 'general'],
            ['key' => 'store_phone', 'value' => '6281298765432', 'group' => 'contact'],
            ['key' => 'store_address', 'value' => 'Jl. Aroma Rempah No. 88, Tebet, Jakarta Selatan', 'group' => 'contact'],
            ['key' => 'store_open_hours', 'value' => 'Setiap Hari: 10:00 - 22:00 WIB', 'group' => 'general'],
            ['key' => 'bank_name', 'value' => 'Bank Central Asia (BCA)', 'group' => 'payment'],
            ['key' => 'bank_account_number', 'value' => '8735019281', 'group' => 'payment'],
            ['key' => 'bank_account_holder', 'value' => 'Dapur Biryani Berkah', 'group' => 'payment'],
            ['key' => 'qris_merchant_name', 'value' => 'DAPUR BIRYANI BERKAH QRIS', 'group' => 'payment'],
            ['key' => 'shipping_flat_rate', 'value' => '10000', 'group' => 'delivery'],
        ];

        foreach ($settings as $item) {
            StoreSetting::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
