<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): View
    {
        $cart = $this->cartService->getCart();
        if ($cart) {
            $cart->load(['items.product.primaryImage', 'items.product.category']);
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        if (! $product->is_active) {
            return back()->with('error', 'Produk sedang tidak tersedia.');
        }

        if ($validated['quantity'] < $product->min_order) {
            return back()->with('error', "Minimal pembelian untuk {$product->name} adalah {$product->min_order} {$product->unit}.");
        }

        try {
            $this->cartService->addItem($product, (int) $validated['quantity']);
            return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $cart = $this->cartService->getCart();
        if (! $cart || $item->cart_id !== $cart->id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $this->cartService->updateQuantity($item, (int) $validated['quantity']);
            return back()->with('success', 'Jumlah produk berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(CartItem $item): RedirectResponse
    {
        $cart = $this->cartService->getCart();
        if (! $cart || $item->cart_id !== $cart->id) {
            abort(403);
        }

        $this->cartService->removeItem($item);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clearCart();
        return back()->with('success', 'Keranjang belanja dikosongkan.');
    }
}
