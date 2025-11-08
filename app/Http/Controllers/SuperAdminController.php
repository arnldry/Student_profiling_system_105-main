<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ArchivedStudentInformation;

class SuperAdminController extends Controller
{
    // Display the superadmin dashboard
    public function dashboard(){
        $adminCount = User::where('role', 'admin')->count();
        $studentCount = User::where('role', 'student')->count();

        // Determine active/current school year.
        // Prefer explicit is_active flag so unarchiving an older year won't make it the displayed current year.
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->orderBy('created_at', 'desc')->first();
        if (!$activeSchoolYear) {
            $activeSchoolYear = \App\Models\SchoolYear::where('archived', 0)
                ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
                ->first();
        }
        if (!$activeSchoolYear) {
            $activeSchoolYear = \App\Models\SchoolYear::latest()->first();
        }

        return view('superadmin.dashboard', compact('adminCount', 'studentCount', 'activeSchoolYear'));
    }

    //
    public function archivedFiles()
    {
        
        

        return view('superadmin.archived-files');
    }

    public function storeAccount(Request $request)
    {
        // ✅ Validate input
        $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-\']+$/u'],
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,student',
        'password' => [
            'required',
            'confirmed',
            function ($attribute, $value, $fail) {
                if (strlen($value) < 8) {
                    $fail('Password must be at least 8 characters long.');
                } elseif (!preg_match('/[A-Z]/', $value) || !preg_match('/[a-z]/', $value)) {
                    $fail('Password must contain both uppercase and lowercase letters.');
                } elseif (!preg_match('/[0-9]/', $value)) {
                    $fail('Password must include at least one number.');
                } elseif (!preg_match('/[\W_]/', $value)) {
                    $fail('Password must include at least one special character.');
                }
            },
        ],
    ], [
        'password.confirmed' => 'Passwords do not match.',
        'name.regex' => 'Name can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
    ]);


        // ✅ Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Account created successfully!');
    }




    // Display the admin accounts management page
     public function adminAccounts()
    {
        $users = User::where('role', 'admin')->get();
        return view('superadmin.admin-accounts', compact('users'));
    }

    // Display the student accounts management page
    public function studentAccounts()
    {
        $users = User::where('role', 'student')->get();
        return view('superadmin.student-accounts', compact('users'));
    }


    // Toggle user status (active/inactive)
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    // Update Admin Profile
    public function profile()
    {
        // You can pass any data if needed
        $user = auth()->user(); // Example: currently logged-in superadmin

        return view('superadmin.update-profile', compact('user'));
    }

    
    // Show edit form for admin account
    public function editAdminAccount($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.edit-profile', compact('user'));
    }


    // Handle account update
    public function updateAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-\']+$/u'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
                'regex:/^[\w.%+-]+@(gmail|yahoo)\.com$/i', // restrict to Gmail/Yahoo
            ],
            'role' => ['required', 'in:admin,student'],
        ], [
            'email.regex' => 'Only Gmail or Yahoo email addresses are allowed.',
            'name.regex' => 'Name can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }


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


    // SuperAdminController.php
    public function unarchive($schoolYearId)
    {
        // Delete archived records for that school year
        ArchivedStudentInformation::where('school_year_id', $schoolYearId)->delete();

        return redirect()->back()->with('success', 'Students have been restored successfully!');
    }

}
