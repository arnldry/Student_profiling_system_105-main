<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RiasecResult;
use App\Models\User;


class RiasecController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        
        $hasResult = RiasecResult::where('user_id', $user->id)->exists();

        if ($hasResult) {
            return redirect()->route('testing.results.riasec-result');
        }
        return view('testing.riasec');
    
    }

public function store(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $result = RiasecResult::create([
        'user_id' => $user->id,
        'code' => $request->input('code'),
        'scores' => $request->input('scores'),
    ]);

    return response()->json([
        'success' => true,
        'user_id' => $user->id,
        'redirect' => route('testing.results.riasec-result')
    ]);
}

  public function result()
{
    $user = Auth::user();

    $result = RiasecResult::where('user_id', $user->id)
        ->latest()
        ->first();

    $scores = $result ? $result->scores : [];
    $top3 = collect($scores)->sortDesc()->take(3);

    $descriptions = [
        'R' => 'Realistic (Doers)',
        'I' => 'Investigative (Thinkers)',
        'A' => 'Artistic (Creators)',
        'S' => 'Social (Helpers)',
        'E' => 'Enterprising (Persuaders)',
        'C' => 'Conventional (Organizers)',
    ];

    return view('testing.results.riasec-result', compact('scores', 'top3', 'descriptions', 'user', 'result'));
}





}





