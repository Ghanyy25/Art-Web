<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCuratorStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Jika User adalah Curator DAN Statusnya Pending
        if ($user && $user->role === 'curator' && $user->status === 'pending') {

            // 2. Izinkan akses ke halaman pending & logout (biar ga terjebak)
            if ($request->routeIs('curator.pending') || $request->routeIs('logout') || $request->routeIs('profile.*')) {
                return $next($request);
            }

            // 3. Jika mencoba akses halaman lain, lempar ke halaman pending
            return redirect()->route('curator.pending');
        }

        return $next($request);
    }
}
