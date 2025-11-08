<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LifeValuesResult;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LifeValuesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $hasResult = LifeValuesResult::where('user_id', $user->id)->exists();

        if ($hasResult) {
            return redirect()->route('testing.results.life-values-results');
        }

        return view('testing.life-values-inventory');
    }



    public function store(Request $request)
    {
        $user = Auth::user();

        // ✅ Store the test result
        $scores = $request->input('scores');

        LifeValuesResult::updateOrCreate(
            ['user_id' => $user->id],
            ['scores' => $scores]
        );

        return response()->json([
            'success' => true,
            'redirect' => route('testing.results.life-values-results'),
        ]);

    }

    public function result()
    {
        $user = Auth::user();

        $result = LifeValuesResult::where('user_id', $user->id)
        ->latest()
        ->first();

        // ✅ Since we have casts, no need for json_decode
        $scores = $result->scores;

        return view('testing.results.life-values-results', compact('scores', 'user', 'result'));
    }
        
    

}
