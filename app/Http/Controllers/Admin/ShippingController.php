<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Shipping::with(['order.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shippings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.shippings.index', compact('shippings'));
    }

    public function update(Request $request, Shipping $shipping): RedirectResponse
    {
        $validated = $request->validate([
            'courier_name' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:pending,disiapkan,dikirim,diterima'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $updates = $validated;
        if ($validated['status'] === 'dikirim' && ! $shipping->shipped_at) {
            $updates['shipped_at'] = now();
            $shipping->order?->update(['status' => 'dikirim']);
        }
        if ($validated['status'] === 'diterima' && ! $shipping->delivered_at) {
            $updates['delivered_at'] = now();
            $shipping->order?->update(['status' => 'selesai', 'completed_at' => now()]);
        }

        $shipping->update($updates);

        return back()->with('success', 'Data pengiriman berhasil diperbarui.');
    }
}
