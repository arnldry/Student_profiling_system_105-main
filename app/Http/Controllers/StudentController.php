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
        return view('student.dashboard');
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
