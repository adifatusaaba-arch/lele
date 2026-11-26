<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name', 'value' => 'LELE Pak Dedi', 'group' => 'general', 'label' => 'Nama Toko', 'type' => 'text'],
            ['key' => 'store_tagline', 'value' => 'Lele Segar, Sehat & Berkualitas Langsung dari Kolam', 'group' => 'general', 'label' => 'Tagline Toko', 'type' => 'text'],
            ['key' => 'store_phone', 'value' => '0812-3456-7890', 'group' => 'general', 'label' => 'Nomor WhatsApp / Telepon', 'type' => 'text'],
            ['key' => 'store_email', 'value' => 'admin@lelepakdedi.com', 'group' => 'general', 'label' => 'Email Toko', 'type' => 'email'],
            ['key' => 'store_address', 'value' => 'Jl. Raya Tambun Selatan No. 88, Kabupaten Bekasi, Jawa Barat 17510', 'group' => 'general', 'label' => 'Alamat Kolam & Toko', 'type' => 'textarea'],
            ['key' => 'bank_bca', 'value' => '1234567890 a.n. Dedi Kurniawan', 'group' => 'payment', 'label' => 'Rekening BCA', 'type' => 'text'],
            ['key' => 'bank_bri', 'value' => '098765432101234 a.n. Dedi Kurniawan', 'group' => 'payment', 'label' => 'Rekening BRI', 'type' => 'text'],
            ['key' => 'bank_mandiri', 'value' => '1330012345678 a.n. Dedi Kurniawan', 'group' => 'payment', 'label' => 'Rekening Mandiri', 'type' => 'text'],
            ['key' => 'shipping_kurir_toko_cost', 'value' => '15000', 'group' => 'shipping', 'label' => 'Ongkir Kurir Toko (Rp)', 'type' => 'number'],
            ['key' => 'shipping_ekspedisi_oksigen_cost', 'value' => '35000', 'group' => 'shipping', 'label' => 'Ongkir Ekspedisi Oksigen (Rp)', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
