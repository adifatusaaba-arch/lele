<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with(['items', 'payment', 'shipping'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items.product', 'payment', 'shipping']);

        $bankAccounts = [
            'BCA' => Setting::getValue('bank_bca', '1234567890 a.n. Dedi Kurniawan'),
            'BRI' => Setting::getValue('bank_bri', '098765432101234 a.n. Dedi Kurniawan'),
            'Mandiri' => Setting::getValue('bank_mandiri', '1330012345678 a.n. Dedi Kurniawan'),
        ];

        return view('customer.orders.show', compact('order', 'bankAccounts'));
    }

    public function uploadPayment(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (! in_array($order->status, ['pending', 'menunggu_pembayaran', 'menunggu_verifikasi'], true)) {
            return back()->with('error', 'Pesanan ini tidak memerlukan upload bukti pembayaran.');
        }

        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'proof_image.required' => 'Bukti transfer wajib diunggah.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $payment = $order->payment;
        if (! $payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($payment->proof_image && Storage::disk('public')->exists($payment->proof_image)) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $path = $request->file('proof_image')->store('payments', 'public');

        $payment->update([
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'] ?? null,
            'proof_image' => $path,
            'status' => 'menunggu_verifikasi',
            'rejection_reason' => null,
            'paid_at' => now(),
        ]);

        $order->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function confirmReceived(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'dikirim') {
            return back()->with('error', 'Hanya pesanan berstatus Dikirim yang dapat diselesaikan.');
        }

        $order->update([
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        if ($order->shipping) {
            $order->shipping->update([
                'status' => 'diterima',
                'delivered_at' => now(),
            ]);
        }

        return back()->with('success', 'Terima kasih! Pesanan telah selesai.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (! in_array($order->status, ['pending', 'menunggu_pembayaran'], true)) {
            return back()->with('error', 'Pesanan yang sudah dibayar atau diproses tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            $this->orderService->restoreStock($order);
            $order->update([
                'status' => 'dibatalkan',
                'cancelled_at' => now(),
            ]);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function invoice(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items', 'payment', 'shipping', 'user']);

        return view('orders.invoice', compact('order'));
    }
}
