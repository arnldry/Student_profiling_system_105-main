<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getRiasecStats()
    {
        // Placeholder for RIASEC stats
        return response()->json(['message' => 'RIASEC stats endpoint']);
    }

    public function getLifeValuesStats()
    {
        // Placeholder for Life Values stats
        return response()->json(['message' => 'Life Values stats endpoint']);
    }
}