<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next, string $menuSlug): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        if ($user->canAccess($menuSlug)) {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
