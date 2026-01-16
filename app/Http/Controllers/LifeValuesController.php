<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LifeValuesResult;
use App\Models\User;
use App\Models\ActivityLog;
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

        // Get the latest Life Values result for the user
        $latestResult = LifeValuesResult::where('user_id', $user->id)->latest()->first();

        // If no result, allow taking the test
        if (!$latestResult) {
            return view('testing.life-values-inventory');
        }

        // Check if eligible for retake: 1 year passed or admin reopened
        $oneYearAgo = now()->subYear();
        $canRetake = $latestResult->created_at < $oneYearAgo || $latestResult->admin_reopened;

        if (!$canRetake) {
            $nextEligibleDate = $latestResult->created_at->addYear()->format('F d, Y');
            return redirect()->route('student.testingdash')->with('error', 'You have already taken this test. You can take it again next year. Or ask guidance for permission to take it sooner.');
        }

        return view('testing.life-values-inventory');
    }



    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Get the latest result to check if this is a retake
        $latestResult = LifeValuesResult::where('user_id', $user->id)->latest()->first();

        $isRetake = $latestResult ? true : false;
        $previousResultId = $latestResult ? $latestResult->id : null;

        $result = LifeValuesResult::create([
            'user_id' => $user->id,
            'scores' => $request->input('scores'),
            'is_retake' => $isRetake,
            'previous_result_id' => $previousResultId,
        ]);

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'redirect' => route('testing.results.life-values-results')
        ]);
    }

    public function result($result_id = null)
    {
        $user = Auth::user();

        // Get all results for the user, ordered by oldest first (chronological order)
        $allResults = LifeValuesResult::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($allResults->isEmpty()) {
            return redirect()->route('student.testingdash')->with('error', 'No Life Values results found.');
        }

        // Determine which result to show
        if ($result_id) {
            $result = $allResults->find($result_id);
            if (!$result) {
                return redirect()->route('testing.results.life-values-results')->with('error', 'Result not found.');
            }
        } else {
            $result = $allResults->last(); // Latest (newest)
        }

        $scores = $result->scores;
        if (!is_array($scores)) {
            $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
        }

        $top5 = collect($scores)->sortDesc()->take(5);

        // Get previous result for comparison (from the linked previous_result_id)
        $previousResult = $result->previous_result_id ? LifeValuesResult::find($result->previous_result_id) : null;
        $previousScores = $previousResult ? $previousResult->scores : [];
        if (!is_array($previousScores)) {
            $previousScores = is_string($previousScores) ? json_decode($previousScores, true) ?? [] : [];
        }

        // Navigation: find current index in allResults (chronological order: 0 = oldest, last = newest)
        $currentIndex = $allResults->search(function ($item) use ($result) {
            return $item->id == $result->id;
        });

        $currentAttempt = $currentIndex + 1;

        // Next = newer result (higher index), Previous = older result (lower index)
        $nextResult = $currentIndex < $allResults->count() - 1 ? $allResults[$currentIndex + 1] : null;
        $prevResult = $currentIndex > 0 ? $allResults[$currentIndex - 1] : null;

        // Check if this is the latest result
        $is_latest = ($result->id == $allResults->last()->id);

        $student = $user;
return view('testing.results.life-values-results', compact('scores', 'top5', 'user', 'result', 'previousResult', 'previousScores', 'allResults', 'nextResult', 'prevResult', 'currentAttempt', 'student', 'is_latest'));
}

public function reopenForStudent($userId)
{
    try {
        // Validate that the student exists
        $student = User::find($userId);
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        $latestResult = LifeValuesResult::where('user_id', $userId)->latest()->first();

        if ($latestResult) {
            $latestResult->admin_reopened = true;
            $latestResult->save();

            // Check if admin is authenticated
            $adminId = Auth::id();
            if (!$adminId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not authenticated.'
                ], 401);
            }

            // Log the activity
            ActivityLog::create([
                'admin_id' => $adminId,
                'action' => 'Allowed Life Values Retake',
                'description' => 'Allowed student ' . $student->name . ' to retake the Life Values test',
                'student_id' => $userId,
            ]);
        }

        // Always return JSON for this AJAX endpoint
        return response()->json([
            'success' => true,
            'message' => 'Life Values test has been reopened for the student.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to allow retake. Please try again.'
        ], 500);
    }
}
}
