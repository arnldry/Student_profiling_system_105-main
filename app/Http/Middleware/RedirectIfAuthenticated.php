<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;

                return match ($role) {
                    'superadmin' => redirect()->route('superadmin.dashboard'),
                    'admin'      => redirect()->route('admin.dashboard'),
                    'student'    => redirect()->route('student.dashboard'),
                    default      => redirect()->route('landing'),
                };
            }
        }

        return $next($request);
    }
}
