<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdditionalInformation;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{

   public function __construct()
    {
        view()->composer('*', function ($view) {
            $hasAdditionalInfo = false;

            if (Auth::check() && Auth::user()->role === 'student') {
                $hasAdditionalInfo = AdditionalInformation::where('learner_id', Auth::id())->exists();
            }

            $view->with('hasAdditionalInfo', $hasAdditionalInfo);
        });
    }

    public function dashboard(){
        $user = auth()->user();

        // Get RIASEC results for the table
        $riasecResults = \App\Models\RiasecResult::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $riasecResults = $riasecResults->map(function ($result, $index) use ($riasecResults) {
            $scores = $result->scores;
            if (!is_array($scores)) {
                $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
            }
            $result->decoded_scores = $scores;
            $result->top3 = collect($scores)->sortDesc()->take(3)->keys()->implode('');
            $result->take_number = $riasecResults->count() - $index; // Most recent is highest number
            return $result;
        });

        // Get Life Values results for the table
        $lifeValuesResults = \App\Models\LifeValuesResult::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $lifeValuesResults = $lifeValuesResults->map(function ($result, $index) use ($lifeValuesResults) {
            $scores = $result->scores;
            if (!is_array($scores)) {
                $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
            }
            $result->decoded_scores = $scores;
            $result->take_number = $lifeValuesResults->count() - $index; // Most recent is highest number
            return $result;
        });

        return view('student.dashboard', compact('riasecResults', 'lifeValuesResults'));
    }

    public function testing(){
        $user = auth()->user(); 
        return view('student.testingdash');
    }

    public function riasec(){
        $user = auth()->user(); 
        return view('testing.riasec');
    }
    

    public function profile()
     {
         // You can pass any data if needed
         $user = auth()->user(); // Example: currently logged-in superadmin
 
         return view('student.update-profile', compact('user'));
     }
 
     // Handle profile update
    // Handle profile update
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id, // exclude current user
                'regex:/^[\w.%+-]+@(gmail|yahoo)\.com$/i', // restrict to Gmail or Yahoo
            ],
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'email.regex' => 'You must register with a Gmail or Yahoo email address.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
