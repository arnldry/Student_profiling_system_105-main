<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDatabase
{
    public function handle(Request $request, Closure $next)
    {
        $databaseName = config('database.connections.mysql.database');

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // If database doesn't exist, show recovery view
            return response()->view('system.recovery', compact('databaseName'));
        }

        return $next($request);
    }
}

