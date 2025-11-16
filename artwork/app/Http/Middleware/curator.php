<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Curator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'curator') {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
    }
}
