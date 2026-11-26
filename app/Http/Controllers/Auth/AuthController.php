<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $sessionId = $request->session()->getId();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan.']);
        }

        $request->session()->regenerate();
        $this->mergeGuestCart($sessionId, $user);

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Berhasil masuk. Selamat berbelanja di LELE Pak Dedi!');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor WhatsApp/HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $sessionId = $request->session()->getId();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'customer',
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->mergeGuestCart($sessionId, $user);

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Selamat datang di LELE Pak Dedi.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }

    private function mergeGuestCart(string $sessionId, User $user): void
    {
        $guestCart = Cart::with('items')->where('session_id', $sessionId)->whereNull('user_id')->first();
        if (! $guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()->where('product_id', $guestItem->product_id)->first();
            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        $guestCart->delete();
    }
}
