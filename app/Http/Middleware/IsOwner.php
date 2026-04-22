<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsOwner
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(in_array(auth()->user()?->role, ['admin', 'owner'], true), 403);

        return $next($request);
    }
}
