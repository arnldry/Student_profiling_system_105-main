<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaticAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() || $request->session()->has('static_admin')) {
            return $next($request);
        }

        // Block anyone else
        abort(404);
    }
}
