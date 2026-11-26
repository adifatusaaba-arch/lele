<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lelepakdedi.com'],
            [
                'name' => 'Pak Dedi (Admin)',
                'phone' => '081234567890',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $customers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081298765432',
                'address' => [
                    'label' => 'Warung Pecel Lele Budi',
                    'recipient_name' => 'Budi Santoso',
                    'phone' => '081298765432',
                    'province' => 'Jawa Barat',
                    'city' => 'Kabupaten Bekasi',
                    'district' => 'Tambun Selatan',
                    'postal_code' => '17510',
                    'full_address' => 'Jl. Sultan Hasanudin No. 45, Tambun',
                    'notes' => 'Patokan depan masjid Al-Ikhlas',
                    'is_default' => true,
                ],
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'phone' => '085612345678',
                'address' => [
                    'label' => 'Rumah',
                    'recipient_name' => 'Siti Aminah',
                    'phone' => '085612345678',
                    'province' => 'DKI Jakarta',
                    'city' => 'Jakarta Timur',
                    'district' => 'Cakung',
                    'postal_code' => '13910',
                    'full_address' => 'Jl. Raya Bekasi KM 23 No. 12',
                    'notes' => null,
                    'is_default' => true,
                ],
            ],
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@example.com',
                'phone' => '087811223344',
                'address' => [
                    'label' => 'Kolam Budidaya Agus',
                    'recipient_name' => 'Agus Wijaya',
                    'phone' => '087811223344',
                    'province' => 'Jawa Barat',
                    'city' => 'Kabupaten Karawang',
                    'district' => 'Klari',
                    'postal_code' => '41371',
                    'full_address' => 'Dusun Krajan RT 02/04 Desa Anggadita',
                    'notes' => 'Kirim pagi hari',
                    'is_default' => true,
                ],
            ],
        ];

        foreach ($customers as $data) {
            $addressData = $data['address'];
            unset($data['address']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ])
            );

            if ($user->addresses()->count() === 0) {
                $user->addresses()->create($addressData);
            }
        }
    }
}
