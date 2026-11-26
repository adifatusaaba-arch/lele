<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getShippingCost(string $method): float
    {
        return match ($method) {
            'kurir_toko' => (float) Setting::getValue('shipping_kurir_toko_cost', 15000),
            'ekspedisi_oksigen' => (float) Setting::getValue('shipping_ekspedisi_oksigen_cost', 35000),
            'ambil_sendiri' => 0.0,
            default => 0.0,
        };
    }

    public function createOrderFromCart(
        User $user,
        Cart $cart,
        Address $address,
        string $shippingMethod,
        string $paymentMethod,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($user, $cart, $address, $shippingMethod, $paymentMethod, $notes) {
            $cart->load('items.product');

            if ($cart->items->isEmpty()) {
                throw new \RuntimeException('Keranjang belanja Anda masih kosong.');
            }

            $subtotal = 0;
            $preparedItems = [];

            foreach ($cart->items as $cartItem) {
                $product = Product::where('id', $cartItem->product_id)->lockForUpdate()->first();

                if (! $product || ! $product->is_active) {
                    throw new \RuntimeException("Produk {$cartItem->product?->name} sudah tidak tersedia.");
                }

                if ($cartItem->quantity < $product->min_order) {
                    throw new \RuntimeException("Minimal pembelian {$product->name} adalah {$product->min_order} {$product->unit}.");
                }

                if ($cartItem->quantity > $product->stock) {
                    throw new \RuntimeException("Stok {$product->name} tidak mencukupi (tersisa {$product->stock} {$product->unit}).");
                }

                $lineSubtotal = (float) $product->price * $cartItem->quantity;
                $subtotal += $lineSubtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $shippingCost = $this->getShippingCost($shippingMethod);
            $totalAmount = $subtotal + $shippingCost;
            $isCod = $paymentMethod === 'cod';

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'shipping_recipient' => $address->recipient_name,
                'shipping_phone' => $address->phone,
                'shipping_address' => $address->full_address . ', Kec. ' . $address->district,
                'shipping_city' => $address->city,
                'shipping_province' => $address->province,
                'shipping_postal_code' => $address->postal_code,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => $isCod ? 'diproses' : 'menunggu_pembayaran',
                'stock_deducted' => false,
                'notes' => $notes,
            ]);

            foreach ($preparedItems as $row) {
                /** @var Product $product */
                $product = $row['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'fish_size' => $product->fish_size,
                    'price' => $product->price,
                    'quantity' => $row['quantity'],
                    'subtotal' => $row['subtotal'],
                ]);
            }

            $bankName = match ($paymentMethod) {
                'transfer_bca' => 'BCA',
                'transfer_bri' => 'BRI',
                'transfer_mandiri' => 'Mandiri',
                'qris' => 'QRIS',
                default => 'COD',
            };

            $order->payment()->create([
                'payment_method' => $paymentMethod,
                'amount' => $totalAmount,
                'bank_name' => $bankName,
                'status' => $isCod ? 'pending' : 'pending',
            ]);

            $shippingInfo = Shipping::METHODS[$shippingMethod] ?? ['label' => $shippingMethod, 'estimate' => '1 Hari'];

            $order->shipping()->create([
                'shipping_method' => $shippingMethod,
                'courier_name' => $shippingInfo['label'],
                'cost' => $shippingCost,
                'estimated_delivery' => $shippingInfo['estimate'],
                'status' => $isCod ? 'disiapkan' : 'pending',
            ]);

            if ($isCod) {
                $this->deductStock($order);
            }

            $cart->items()->delete();

            return $order->load(['items', 'payment', 'shipping']);
        });
    }

    public function deductStock(Order $order): void
    {
        if ($order->stock_deducted) {
            return;
        }

        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }

            $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
            if (! $product) {
                throw new \RuntimeException("Produk {$item->product_name} tidak ditemukan.");
            }

            if ($product->stock < $item->quantity) {
                throw new \RuntimeException("Stok {$product->name} tidak mencukupi untuk konfirmasi pesanan (tersisa {$product->stock} {$product->unit}).");
            }

            $product->decrement('stock', $item->quantity);
        }

        $order->update(['stock_deducted' => true]);
    }

    public function restoreStock(Order $order): void
    {
        if (! $order->stock_deducted) {
            return;
        }

        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }

            $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        $order->update(['stock_deducted' => false]);
    }
}
