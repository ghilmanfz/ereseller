<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(auth()->user()?->role === 'admin', 403);

        return $next($request);
    }
}
