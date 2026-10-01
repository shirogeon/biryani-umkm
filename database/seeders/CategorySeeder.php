<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Nasi Biryani Utama',
                'slug' => 'nasi-biryani-utama',
                'description' => 'Porsi personal dengan beras basmati aromatik premium dan daging empuk bumbu rempah melimpah.',
                'icon' => 'fa-bowl-rice',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Paket Loyang Keluarga',
                'slug' => 'paket-loyang-keluarga',
                'description' => 'Sajian porsi besar loyang pas untuk dinikmati bersama keluarga atau teman (4-5 orang).',
                'icon' => 'fa-users',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Side Dish & Pelengkap',
                'slug' => 'side-dish-pelengkap',
                'description' => 'Camilan khas dan saus penyegar pelengkap kenikmatan nasi biryani.',
                'icon' => 'fa-utensils',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Minuman Khas & Segar',
                'slug' => 'minuman-khas-segar',
                'description' => 'Minuman pereda dahaga dengan aroma kapulaga, susu creamy, dan kesegaran limau.',
                'icon' => 'fa-glass-water',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
