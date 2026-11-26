<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Shipping;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();
        if (! $cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        $cart->load(['items.product.primaryImage']);
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();

        $shippingMethods = [
            'kurir_toko' => [
                'label' => Shipping::METHODS['kurir_toko']['label'],
                'cost' => (float) Setting::getValue('shipping_kurir_toko_cost', 15000),
                'estimate' => Shipping::METHODS['kurir_toko']['estimate'],
            ],
            'ekspedisi_oksigen' => [
                'label' => Shipping::METHODS['ekspedisi_oksigen']['label'],
                'cost' => (float) Setting::getValue('shipping_ekspedisi_oksigen_cost', 35000),
                'estimate' => Shipping::METHODS['ekspedisi_oksigen']['estimate'],
            ],
            'ambil_sendiri' => [
                'label' => Shipping::METHODS['ambil_sendiri']['label'],
                'cost' => 0,
                'estimate' => Shipping::METHODS['ambil_sendiri']['estimate'],
            ],
        ];

        $paymentMethods = Payment::METHODS;

        return view('customer.checkout', compact('cart', 'addresses', 'shippingMethods', 'paymentMethods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cartService->getCart();
        if (! $cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $rules = [
            'address_id' => ['nullable', 'exists:addresses,id'],
            'shipping_method' => ['required', 'in:kurir_toko,ekspedisi_oksigen,ambil_sendiri'],
            'payment_method' => ['required', 'in:transfer_bca,transfer_bri,transfer_mandiri,qris,cod'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];

        if (! $request->filled('address_id')) {
            $rules = array_merge($rules, [
                'recipient_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:25'],
                'province' => ['required', 'string', 'max:100'],
                'city' => ['required', 'string', 'max:100'],
                'district' => ['required', 'string', 'max:100'],
                'postal_code' => ['required', 'string', 'max:10'],
                'full_address' => ['required', 'string'],
            ]);
        }

        $validated = $request->validate($rules);
        $user = Auth::user();

        if ($request->filled('address_id')) {
            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $address = $user->addresses()->create([
                'label' => 'Alamat Checkout',
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'postal_code' => $validated['postal_code'],
                'full_address' => $validated['full_address'],
                'is_default' => $user->addresses()->count() === 0,
            ]);
        }

        try {
            $order = $this->orderService->createOrderFromCart(
                $user,
                $cart,
                $address,
                $validated['shipping_method'],
                $validated['payment_method'],
                $validated['notes'] ?? null
            );

            return redirect()->route('customer.orders.show', $order)
                ->with('success', "Pesanan {$order->order_number} berhasil dibuat!");
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
