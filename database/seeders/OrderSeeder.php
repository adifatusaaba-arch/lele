<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::count() > 0) {
            return;
        }

        $budi = User::where('email', 'budi@example.com')->first();
        $siti = User::where('email', 'siti@example.com')->first();
        $agus = User::where('email', 'agus@example.com')->first();

        $leleKonsumsi = Product::where('slug', 'lele-konsumsi-segar-standar')->first();
        $leleHidup = Product::where('slug', 'lele-sangkuriang-hidup-segar')->first();
        $bibitLele = Product::where('slug', 'bibit-lele-sangkuriang-5-7-cm')->first();
        $leleJumbo = Product::where('slug', 'lele-jumbo-bakar-3-4-ekor-kg')->first();

        if (! $budi || ! $siti || ! $agus || ! $leleKonsumsi || ! $bibitLele) {
            return;
        }

        $samples = [
            [
                'user' => $budi,
                'order_number' => 'LPD-' . now()->subDays(5)->format('Ymd') . '-0001',
                'status' => 'selesai',
                'stock_deducted' => true,
                'payment_method' => 'transfer_bca',
                'payment_status' => 'verified',
                'shipping_method' => 'kurir_toko',
                'shipping_cost' => 15000,
                'shipping_status' => 'diterima',
                'created_at' => now()->subDays(5),
                'items' => [
                    ['product' => $leleKonsumsi, 'qty' => 15],
                    ['product' => $leleJumbo ?? $leleKonsumsi, 'qty' => 5],
                ],
            ],
            [
                'user' => $agus,
                'order_number' => 'LPD-' . now()->subDays(3)->format('Ymd') . '-0002',
                'status' => 'selesai',
                'stock_deducted' => true,
                'payment_method' => 'transfer_bri',
                'payment_status' => 'verified',
                'shipping_method' => 'ekspedisi_oksigen',
                'shipping_cost' => 35000,
                'shipping_status' => 'diterima',
                'created_at' => now()->subDays(3),
                'items' => [
                    ['product' => $bibitLele, 'qty' => 500],
                ],
            ],
            [
                'user' => $siti,
                'order_number' => 'LPD-' . now()->subDays(1)->format('Ymd') . '-0003',
                'status' => 'dikirim',
                'stock_deducted' => true,
                'payment_method' => 'qris',
                'payment_status' => 'verified',
                'shipping_method' => 'kurir_toko',
                'shipping_cost' => 15000,
                'shipping_status' => 'dikirim',
                'created_at' => now()->subDays(1),
                'items' => [
                    ['product' => $leleHidup ?? $leleKonsumsi, 'qty' => 6],
                ],
            ],
            [
                'user' => $budi,
                'order_number' => 'LPD-' . now()->format('Ymd') . '-0004',
                'status' => 'menunggu_verifikasi',
                'stock_deducted' => false,
                'payment_method' => 'transfer_bca',
                'payment_status' => 'menunggu_verifikasi',
                'shipping_method' => 'ambil_sendiri',
                'shipping_cost' => 0,
                'shipping_status' => 'pending',
                'created_at' => now()->subHours(2),
                'items' => [
                    ['product' => $leleKonsumsi, 'qty' => 10],
                ],
            ],
        ];

        foreach ($samples as $sample) {
            $address = $sample['user']->addresses()->first();
            $subtotal = 0;
            foreach ($sample['items'] as $row) {
                $subtotal += $row['product']->price * $row['qty'];
            }
            $total = $subtotal + $sample['shipping_cost'];

            $order = Order::create([
                'order_number' => $sample['order_number'],
                'user_id' => $sample['user']->id,
                'address_id' => $address?->id,
                'shipping_recipient' => $address?->recipient_name ?? $sample['user']->name,
                'shipping_phone' => $address?->phone ?? ($sample['user']->phone ?? '081234567890'),
                'shipping_address' => $address?->full_address ?? 'Jl. Raya Tambun No. 1',
                'shipping_city' => $address?->city ?? 'Bekasi',
                'shipping_province' => $address?->province ?? 'Jawa Barat',
                'shipping_postal_code' => $address?->postal_code ?? '17510',
                'subtotal' => $subtotal,
                'shipping_cost' => $sample['shipping_cost'],
                'total_amount' => $total,
                'status' => $sample['status'],
                'stock_deducted' => $sample['stock_deducted'],
                'paid_at' => $sample['payment_status'] === 'verified' ? $sample['created_at']->copy()->addHour() : null,
                'completed_at' => $sample['status'] === 'selesai' ? $sample['created_at']->copy()->addDay() : null,
                'created_at' => $sample['created_at'],
                'updated_at' => $sample['created_at'],
            ]);

            foreach ($sample['items'] as $row) {
                $product = $row['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'fish_size' => $product->fish_size,
                    'price' => $product->price,
                    'quantity' => $row['qty'],
                    'subtotal' => $product->price * $row['qty'],
                ]);
            }

            $order->payment()->create([
                'payment_method' => $sample['payment_method'],
                'amount' => $total,
                'bank_name' => strtoupper(str_replace('transfer_', '', $sample['payment_method'])),
                'account_name' => $sample['user']->name,
                'status' => $sample['payment_status'],
                'paid_at' => $sample['created_at']->copy()->addMinutes(30),
                'verified_at' => $sample['payment_status'] === 'verified' ? $sample['created_at']->copy()->addHour() : null,
            ]);

            $order->shipping()->create([
                'shipping_method' => $sample['shipping_method'],
                'courier_name' => $sample['shipping_method'] === 'kurir_toko' ? 'Kurir Kolam Pak Dedi' : ($sample['shipping_method'] === 'ekspedisi_oksigen' ? 'Kargo Ikan Express' : 'Ambil di Tempat'),
                'tracking_number' => $sample['shipping_status'] !== 'pending' ? 'TRK-' . strtoupper(substr(md5($order->order_number), 0, 8)) : null,
                'cost' => $sample['shipping_cost'],
                'estimated_delivery' => '1 Hari',
                'status' => $sample['shipping_status'],
                'shipped_at' => in_array($sample['shipping_status'], ['dikirim', 'diterima']) ? $sample['created_at']->copy()->addHours(3) : null,
                'delivered_at' => $sample['shipping_status'] === 'diterima' ? $sample['created_at']->copy()->addDay() : null,
            ]);
        }
    }
}
