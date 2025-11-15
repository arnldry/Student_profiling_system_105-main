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

        // Check if RIASEC test is enabled
        if (!\Cache::get('test_riasec_enabled', true)) {
            return redirect()->route('student.testingdash')->with('error', 'The RIASEC test is currently disabled by the administrator.');
        }

        // Get current school year
        $currentSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();
        if (!$currentSchoolYear) {
            $currentSchoolYear = \App\Models\SchoolYear::where('archived', 0)
                ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
                ->first();
        }

        // Check if student has already taken this test in the current school year
        if ($currentSchoolYear) {
            $hasAdditionalInfo = \App\Models\AdditionalInformation::where('learner_id', $user->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->exists();

            if ($hasAdditionalInfo) {
                $existingResult = \App\Models\RiasecResult::where('user_id', $user->id)->exists();
                if ($existingResult) {
                    return redirect()->route('student.testingdash')->with('error', 'You have already taken the RIASEC test for this school year. You can retake it next school year.');
                }
            }
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





