<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDatabase
{
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::select('SELECT 1');
        } catch (\Exception $e) {
            // If database doesn't exist or connection fails, return 404
            abort(404);
        }

        return $next($request);
    }
}

