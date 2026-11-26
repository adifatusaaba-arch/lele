<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        abort_unless($customer->isCustomer(), 404);
        $customer->load(['addresses', 'orders' => fn ($q) => $q->latest()]);

        return view('admin.customers.show', compact('customer'));
    }

    public function toggleStatus(User $customer): RedirectResponse
    {
        abort_unless($customer->isCustomer(), 404);
        $customer->update(['is_active' => ! $customer->is_active]);

        return back()->with('success', 'Status pelanggan berhasil diperbarui.');
    }
}
