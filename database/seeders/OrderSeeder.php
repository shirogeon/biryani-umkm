<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $pAyam = Product::where('slug', 'nasi-biryani-ayam-rempah-khas')->first();
        $pKambing = Product::where('slug', 'nasi-biryani-kambing-muda-spesial')->first();
        $pLoyang = Product::where('slug', 'paket-loyang-biryani-ayam-keluarga')->first();
        $pTeh = Product::where('slug', 'teh-tarik-rempah-kapulaga-dingin')->first();
        $pSamosa = Product::where('slug', 'samosa-daging-sapi-renyah')->first();

        // 1. Completed order (Dine-in)
        $o1 = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Ahmad Fauzi',
            'customer_phone' => '081288889901',
            'order_type' => 'dine_in',
            'table_or_address' => 'Meja No. 04',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'total_amount' => $pKambing->price + $pTeh->price,
            'shipping_cost' => 0,
            'notes' => 'Tingkat pedas sedang',
            'completed_at' => now()->subHours(2),
        ]);
        $o1->items()->create(['product_id' => $pKambing->id, 'product_name' => $pKambing->name, 'price' => $pKambing->price, 'quantity' => 1, 'subtotal' => $pKambing->price]);
        $o1->items()->create(['product_id' => $pTeh->id, 'product_name' => $pTeh->name, 'price' => $pTeh->price, 'quantity' => 1, 'subtotal' => $pTeh->price]);

        // 2. Processing order (Kitchen)
        $o2 = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Siti Rahmawati',
            'customer_phone' => '085711223344',
            'order_type' => 'delivery',
            'table_or_address' => 'Jl. Tebet Timur Dalam III No. 15, Jakarta Selatan',
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'total_amount' => $pLoyang->price + 10000,
            'shipping_cost' => 10000,
            'notes' => 'Tolong sambal dipisah ya',
        ]);
        $o2->items()->create(['product_id' => $pLoyang->id, 'product_name' => $pLoyang->name, 'price' => $pLoyang->price, 'quantity' => 1, 'subtotal' => $pLoyang->price]);

        // 3. Pending order (Takeaway)
        $o3 = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => 'Hendra Pratama',
            'customer_phone' => '087899887766',
            'order_type' => 'takeaway',
            'table_or_address' => 'Diambil jam 13:00 WIB',
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'total_amount' => ($pAyam->price * 2) + $pSamosa->price,
            'shipping_cost' => 0,
            'notes' => 'Acar raita diperbanyak',
        ]);
        $o3->items()->create(['product_id' => $pAyam->id, 'product_name' => $pAyam->name, 'price' => $pAyam->price, 'quantity' => 2, 'subtotal' => $pAyam->price * 2]);
        $o3->items()->create(['product_id' => $pSamosa->id, 'product_name' => $pSamosa->name, 'price' => $pSamosa->price, 'quantity' => 1, 'subtotal' => $pSamosa->price]);
    }
}
