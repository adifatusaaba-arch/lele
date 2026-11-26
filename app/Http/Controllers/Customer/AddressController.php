<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = Auth::user()->addresses()->orderByDesc('is_default')->latest()->get();
        return view('customer.addresses.index', compact('addresses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'full_address' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $isDefault = $request->boolean('is_default') || $user->addresses()->count() === 0;

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create(array_merge($validated, ['is_default' => $isDefault]));

        return back()->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'full_address' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address->update(array_merge($validated, [
            'is_default' => $request->boolean('is_default', $address->is_default),
        ]));

        return back()->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === Auth::id(), 403);
        $address->delete();

        return back()->with('success', 'Alamat pengiriman berhasil dihapus.');
    }
}
