<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'customer') {
            return redirect('/')->with('error', 'Akses hanya untuk pelanggan.');
        }

        return $next($request);
    }
}
