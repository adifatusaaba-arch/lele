<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'payment', 'shipping']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('shipping_recipient', 'like', "%{$q}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $statuses = Order::STATUS_LABELS;

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'payment.verifier', 'shipping']);
        $statuses = Order::STATUS_LABELS;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::STATUS_LABELS))],
        ]);

        $newStatus = $validated['status'];

        try {
            DB::transaction(function () use ($order, $newStatus) {
                $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();

                if (in_array($newStatus, ['dibayar', 'diproses', 'dikirim', 'selesai'], true)) {
                    $this->orderService->deductStock($lockedOrder);
                }

                if ($newStatus === 'dibatalkan') {
                    $this->orderService->restoreStock($lockedOrder);
                }

                $updates = ['status' => $newStatus];
                if ($newStatus === 'dibayar' && ! $lockedOrder->paid_at) {
                    $updates['paid_at'] = now();
                }
                if ($newStatus === 'selesai' && ! $lockedOrder->completed_at) {
                    $updates['completed_at'] = now();
                }
                if ($newStatus === 'dibatalkan' && ! $lockedOrder->cancelled_at) {
                    $updates['cancelled_at'] = now();
                }

                $lockedOrder->update($updates);
            });

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Order $order): View
    {
        $order->load(['items', 'payment', 'shipping', 'user']);
        return view('orders.invoice', compact('order'));
    }
}
