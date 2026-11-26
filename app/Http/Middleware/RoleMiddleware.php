<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda sedang dinonaktifkan. Hubungi admin.');
        }

        if (! empty($roles) && ! in_array($user->role, $roles, true)) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
