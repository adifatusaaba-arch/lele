<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): View
    {
        $query = Payment::with(['order.user', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment): RedirectResponse
    {
        try {
            DB::transaction(function () use ($payment) {
                $order = Order::where('id', $payment->order_id)->lockForUpdate()->firstOrFail();

                $this->orderService->deductStock($order);

                $payment->update([
                    'status' => 'verified',
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                    'rejection_reason' => null,
                ]);

                $order->update([
                    'status' => 'dibayar',
                    'paid_at' => now(),
                ]);
            });

            return back()->with('success', 'Pembayaran berhasil diverifikasi & stok produk telah dikurangi.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $payment->order->update([
                'status' => 'menunggu_pembayaran',
            ]);
        });

        return back()->with('success', 'Pembayaran ditolak. Pelanggan dapat mengunggah ulang bukti transfer.');
    }
}
