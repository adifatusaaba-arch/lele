<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lele Konsumsi',
                'slug' => 'lele-konsumsi',
                'description' => 'Ikan lele segar siap olah dan siap masak untuk kebutuhan rumah tangga, warung pecel lele, dan restoran.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Lele Hidup',
                'slug' => 'lele-hidup',
                'description' => 'Ikan lele sehat dan lincah dalam kondisi hidup dengan kemasan beroksigen untuk kesegaran maksimal.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Bibit Lele',
                'slug' => 'bibit-lele',
                'description' => 'Benih ikan lele Sangkuriang dan Mutiara berkualitas unggul, tahan penyakit, dan cepat besar untuk budidaya.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Lele Ukuran Besar',
                'slug' => 'lele-ukuran-besar',
                'description' => 'Ikan lele jumbo pilihan dengan daging tebal, cocok untuk olahan lele bakar, mangut, atau pemancingan.',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
