<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $catUtama = Category::where('slug', 'nasi-biryani-utama')->first();
        $catLoyang = Category::where('slug', 'paket-loyang-keluarga')->first();
        $catSide = Category::where('slug', 'side-dish-pelengkap')->first();
        $catMinuman = Category::where('slug', 'minuman-khas-segar')->first();

        $products = [
            // Utama
            [
                'category_id' => $catUtama->id,
                'name' => 'Nasi Biryani Ayam Rempah Khas',
                'slug' => 'nasi-biryani-ayam-rempah-khas',
                'description' => 'Beras Basmati impor aromatik berpadu dengan potongan paha ayam bumbu kuning rempah gurih, acar timun raita segar, dan emping renyah.',
                'spiciness_level' => 2,
                'price' => 32000,
                'portion_size' => '1 Porsi',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $catUtama->id,
                'name' => 'Nasi Biryani Kambing Muda Spesial',
                'slug' => 'nasi-biryani-kambing-muda-spesial',
                'description' => 'Menu signature! Daging kambing muda empuk tanpa aroma prengus dimasak slow-cooked hingga meresap sempurna dengan saffron dan rempah kapulaga.',
                'spiciness_level' => 3,
                'price' => 48000,
                'portion_size' => '1 Porsi',
                'image' => 'https://images.unsplash.com/photo-1633945274405-b6c8069047b0?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $catUtama->id,
                'name' => 'Nasi Biryani Daging Sapi Karahi',
                'slug' => 'nasi-biryani-daging-sapi-karahi',
                'description' => 'Daging sapi sandung lamur empuk berbalut rempah kari kental khas, dipadukan nasi basmati harum butter ghee.',
                'spiciness_level' => 2,
                'price' => 42000,
                'portion_size' => '1 Porsi',
                'image' => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $catUtama->id,
                'name' => 'Nasi Biryani Telur & Kentang Rempah',
                'slug' => 'nasi-biryani-telur-kentang-rempah',
                'description' => 'Pilihan vegetarian-friendly dengan telur rebus bumbu masala gurih dan kentang empuk kaya bumbu.',
                'spiciness_level' => 1,
                'price' => 25000,
                'portion_size' => '1 Porsi',
                'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => false,
            ],

            // Paket Loyang
            [
                'category_id' => $catLoyang->id,
                'name' => 'Paket Loyang Biryani Ayam (Porsi 4-5 Orang)',
                'slug' => 'paket-loyang-biryani-ayam-keluarga',
                'description' => 'Sajian loyang besar dengan 4 potong paha ayam bumbu besar, telur rebus rempah, kuah kari gulai, sambal pedas, dan raita yoghurt jumbo.',
                'spiciness_level' => 2,
                'price' => 135000,
                'portion_size' => 'Loyang (4-5 Porsi)',
                'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $catLoyang->id,
                'name' => 'Paket Loyang Biryani Kambing Sultan (Porsi 4-5 Orang)',
                'slug' => 'paket-loyang-biryani-kambing-sultan',
                'description' => 'Hidangan pesta istimewa dengan potongan iga kambing muda bakar rempah, kismis, kacang mede sangrai, dan kuah dalcha spesial.',
                'spiciness_level' => 3,
                'price' => 195000,
                'portion_size' => 'Loyang (4-5 Porsi)',
                'image' => 'https://images.unsplash.com/photo-1633945274405-b6c8069047b0?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],

            // Side Dish
            [
                'category_id' => $catSide->id,
                'name' => 'Samosa Daging Sapi Renyah (Isi 3 pcs)',
                'slug' => 'samosa-daging-sapi-renyah',
                'description' => 'Pastri segitiga super garing berisi cincangan daging sapi bumbu rempah kari dan kentang harum. Disajikan dengan cocolan mint chutney.',
                'spiciness_level' => 1,
                'price' => 18000,
                'portion_size' => '3 Pcs',
                'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $catSide->id,
                'name' => 'Raita Mentimun Yoghurt Segar',
                'slug' => 'raita-mentimun-yoghurt-segar',
                'description' => 'Saus yoghurt dingin segar dengan cacahan mentimun, jintan sangrai, dan daun mint pembersih langit-langit lidah.',
                'spiciness_level' => 0,
                'price' => 10000,
                'portion_size' => '1 Porsi',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $catSide->id,
                'name' => 'Extra Sambal Bawang Rempah Pedas',
                'slug' => 'extra-sambal-bawang-rempah-pedas',
                'description' => 'Sambal cabe rawit goreng berpadu rempah aromatik pedas nampol untuk penggila pedas sejati.',
                'spiciness_level' => 3,
                'price' => 5000,
                'portion_size' => '1 Cup',
                'image' => 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => false,
            ],

            // Minuman
            [
                'category_id' => $catMinuman->id,
                'name' => 'Teh Tarik Rempah Kapulaga Dingin',
                'slug' => 'teh-tarik-rempah-kapulaga-dingin',
                'description' => 'Seduhan teh hitam pekat ditarik berbusa dengan susu kental manis dan sentuhan aroma kapulaga hangat.',
                'spiciness_level' => 0,
                'price' => 12000,
                'portion_size' => 'Gelas Jumbo',
                'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Es Limau Selasih Daun Mint',
                'slug' => 'es-limau-selasih-daun-mint',
                'description' => 'Perasan jeruk limau kasturi murni dengan bulir biji selasih kenyal dan daun mint dingin penyegar setelah makan biryani.',
                'spiciness_level' => 0,
                'price' => 10000,
                'portion_size' => 'Gelas Jumbo',
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $catMinuman->id,
                'name' => 'Mango Lassi Yoghurt Manis',
                'slug' => 'mango-lassi-yoghurt-manis',
                'description' => 'Smoothie yoghurt kental dengan puree buah mangga gedong matang manis dan lembut.',
                'spiciness_level' => 0,
                'price' => 15000,
                'portion_size' => 'Gelas Jumbo',
                'image' => 'https://images.unsplash.com/photo-1528736235302-52922df5c122?w=700&auto=format&fit=crop&q=80',
                'is_available' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }
    }
}
