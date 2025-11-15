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

        // Check if Life Values test is enabled
        if (!\Cache::get('test_life_values_enabled', true)) {
            return redirect()->route('student.testingdash')->with('error', 'The Life Values Inventory test is currently disabled by the administrator.');
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
                $existingResult = \App\Models\LifeValuesResult::where('user_id', $user->id)->exists();
                if ($existingResult) {
                    return redirect()->route('student.testingdash')->with('error', 'You have already taken the Life Values Inventory test for this school year. You can retake it next school year.');
                }
            }
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
