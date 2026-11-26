<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $products = [
            [
                'category_slug' => 'lele-konsumsi',
                'name' => 'Lele Konsumsi Segar Standar',
                'slug' => 'lele-konsumsi-segar-standar',
                'description' => 'Lele Sangkuriang segar hasil panen kolam bersih dengan pakan pelet berkualitas. Tidak bau tanah, daging gurih dan lembut. Cocok untuk pecel lele dan masakan rumahan.',
                'price' => 24000,
                'unit' => 'kg',
                'fish_size' => '8-10 ekor/kg',
                'min_order' => 1,
                'stock' => 250,
                'is_featured' => true,
                'image' => '/images/lele-konsumsi.svg',
            ],
            [
                'category_slug' => 'lele-konsumsi',
                'name' => 'Lele Konsumsi Bersih Siap Goreng (Fillet/Gutted)',
                'slug' => 'lele-konsumsi-bersih-siap-goreng',
                'description' => 'Lele konsumsi yang sudah dibersihkan isi perut dan insangnya, dikemas higienis vakum per kg. Praktis langsung dibumbui dan digoreng.',
                'price' => 30000,
                'unit' => 'kg',
                'fish_size' => '7-9 ekor/kg',
                'min_order' => 1,
                'stock' => 120,
                'is_featured' => true,
                'image' => '/images/lele-konsumsi.svg',
            ],
            [
                'category_slug' => 'lele-konsumsi',
                'name' => 'Paket Lele Warung 10 Kg',
                'slug' => 'paket-lele-warung-10-kg',
                'description' => 'Harga grosir spesial untuk mitra warung pecel lele dan rumah makan. Kualitas seragam ukuran 8-9 ekor per kg.',
                'price' => 22500,
                'unit' => 'kg',
                'fish_size' => '8-9 ekor/kg',
                'min_order' => 10,
                'stock' => 500,
                'is_featured' => false,
                'image' => '/images/lele-konsumsi.svg',
            ],
            [
                'category_slug' => 'lele-hidup',
                'name' => 'Lele Sangkuriang Hidup Segar',
                'slug' => 'lele-sangkuriang-hidup-segar',
                'description' => 'Lele hidup langsung dari kolam karantina air mengalir. Dikemas dengan plastik tebal + oksigen murni agar tetap hidup sampai tujuan.',
                'price' => 26000,
                'unit' => 'kg',
                'fish_size' => '7-9 ekor/kg',
                'min_order' => 2,
                'stock' => 300,
                'is_featured' => true,
                'image' => '/images/lele-hidup.svg',
            ],
            [
                'category_slug' => 'lele-hidup',
                'name' => 'Lele Mutiara Hidup Grade A',
                'slug' => 'lele-mutiara-hidup-grade-a',
                'description' => 'Varietas lele Mutiara unggul dengan daging lebih padat dan tulang lebih kecil. Dijamin sehat dan lincah.',
                'price' => 28000,
                'unit' => 'kg',
                'fish_size' => '6-8 ekor/kg',
                'min_order' => 2,
                'stock' => 180,
                'is_featured' => false,
                'image' => '/images/lele-hidup.svg',
            ],
            [
                'category_slug' => 'bibit-lele',
                'name' => 'Bibit Lele Sangkuriang Ukuran 5-7 cm',
                'slug' => 'bibit-lele-sangkuriang-5-7-cm',
                'description' => 'Benih lele Sangkuriang hasil pemijahan indukan bersertifikat. Sudah lulus sortir ukuran seragam, aktif makan pelet halus.',
                'price' => 250,
                'unit' => 'ekor',
                'fish_size' => '5-7 cm',
                'min_order' => 100,
                'stock' => 15000,
                'is_featured' => true,
                'image' => '/images/bibit-lele.svg',
            ],
            [
                'category_slug' => 'bibit-lele',
                'name' => 'Bibit Lele Mutiara Ukuran 7-9 cm',
                'slug' => 'bibit-lele-mutiara-7-9-cm',
                'description' => 'Benih lele Mutiara ukuran siap tebar kolam terpal atau bioflok. Daya tahan tinggi terhadap perubahan cuaca dan pertumbuhan cepat.',
                'price' => 350,
                'unit' => 'ekor',
                'fish_size' => '7-9 cm',
                'min_order' => 100,
                'stock' => 10000,
                'is_featured' => true,
                'image' => '/images/bibit-lele.svg',
            ],
            [
                'category_slug' => 'bibit-lele',
                'name' => 'Bibit Lele Remaja Ukuran 9-12 cm',
                'slug' => 'bibit-lele-remaja-9-12-cm',
                'description' => 'Benih ukuran besar (sangkal) untuk panen lebih cepat (45-55 hari). Tingkat kelangsungan hidup tinggi di atas 95%.',
                'price' => 550,
                'unit' => 'ekor',
                'fish_size' => '9-12 cm',
                'min_order' => 50,
                'stock' => 5000,
                'is_featured' => false,
                'image' => '/images/bibit-lele.svg',
            ],
            [
                'category_slug' => 'lele-ukuran-besar',
                'name' => 'Lele Jumbo Bakar (3-4 Ekor/Kg)',
                'slug' => 'lele-jumbo-bakar-3-4-ekor-kg',
                'description' => 'Lele ukuran besar khusus menu lele bakar, mangut lele, dan penyetan jumbo. Daging tebal, lembut, dan tidak berlemak berlebih.',
                'price' => 32000,
                'unit' => 'kg',
                'fish_size' => '3-4 ekor/kg',
                'min_order' => 1,
                'stock' => 150,
                'is_featured' => true,
                'image' => '/images/lele-besar.svg',
            ],
            [
                'category_slug' => 'lele-ukuran-besar',
                'name' => 'Lele Babon Pemancingan (1-2 Kg/Ekor)',
                'slug' => 'lele-babon-pemancingan-1-2-kg',
                'description' => 'Lele induk/babon super besar dan bertenaga untuk kolam pemancingan galatama atau harian. Kondisi sehat dan agresif.',
                'price' => 45000,
                'unit' => 'kg',
                'fish_size' => '1-2 kg/ekor',
                'min_order' => 2,
                'stock' => 80,
                'is_featured' => false,
                'image' => '/images/lele-besar.svg',
            ],
        ];

        foreach ($products as $item) {
            $imagePath = $item['image'];
            $categoryId = $categories[$item['category_slug']] ?? null;
            unset($item['image'], $item['category_slug']);

            if (! $categoryId) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, [
                    'category_id' => $categoryId,
                    'is_active' => true,
                ])
            );

            if ($product->images()->count() === 0) {
                $product->images()->create([
                    'image_path' => $imagePath,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        }
    }
}
