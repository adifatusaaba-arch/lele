<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart(bool $create = false): ?Cart
    {
        if (Auth::check()) {
            $query = Cart::where('user_id', Auth::id());
            return $create ? $query->firstOrCreate(['user_id' => Auth::id()]) : $query->first();
        }

        $sessionId = session()->getId();
        $query = Cart::where('session_id', $sessionId)->whereNull('user_id');

        return $create ? $query->firstOrCreate(['session_id' => $sessionId, 'user_id' => null]) : $query->first();
    }

    public function getItemCount(): int
    {
        $cart = $this->getCart();
        if (! $cart) {
            return 0;
        }

        return (int) $cart->items()->sum('quantity');
    }

    public function addItem(Product $product, int $quantity): CartItem
    {
        $cart = $this->getCart(true);

        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = ($item ? $item->quantity : 0) + $quantity;

        if ($newQuantity > $product->stock) {
            throw new \RuntimeException("Stok {$product->name} hanya tersisa {$product->stock} {$product->unit}.");
        }

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
            return $item->fresh();
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): CartItem
    {
        $product = $item->product;
        if (! $product || ! $product->is_active) {
            throw new \RuntimeException('Produk tidak tersedia.');
        }

        if ($quantity < $product->min_order) {
            throw new \RuntimeException("Minimal pembelian untuk {$product->name} adalah {$product->min_order} {$product->unit}.");
        }

        if ($quantity > $product->stock) {
            throw new \RuntimeException("Stok {$product->name} hanya tersisa {$product->stock} {$product->unit}.");
        }

        $item->update(['quantity' => $quantity]);

        return $item->fresh();
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
        }
    }
}
