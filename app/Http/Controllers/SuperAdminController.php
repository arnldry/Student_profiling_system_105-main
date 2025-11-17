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

    /** -------------------------------
      *  MANAGE TEST VIEW
      *  ------------------------------- */
    public function manageTest()
    {
        // Get current test status from cache or config
        $riasecEnabled = \Cache::get('test_riasec_enabled', true);
        $lifeValuesEnabled = \Cache::get('test_life_values_enabled', true);

        // Get students who have taken RIASEC test
        $studentsWithRiasec = \App\Models\User::where('role', 'student')
            ->whereHas('riasecResults')
            ->with(['riasecResults', 'additionalInfo'])
            ->get()
            ->map(function ($student) {
                $latestResult = $student->riasecResults->sortByDesc('created_at')->first();
                $info = $student->additionalInfo;
                $lrn = $info ? $info->lrn : null;
                $grade = $info ? $info->grade : null;
                $section = $info ? $info->section : null;
                $curriculum = $info ? $info->curriculum : null;

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'lrn' => $lrn,
                    'grade' => $grade,
                    'section' => $section,
                    'curriculum' => $curriculum,
                    'last_taken' => $latestResult ? $latestResult->created_at->format('Y-m-d H:i') : null,
                    'can_retake' => $latestResult ? (!$latestResult->admin_reopened && $latestResult->created_at < now()->subYear()) : false,
                    'admin_reopened' => $latestResult ? $latestResult->admin_reopened : false,
                ];
            });

        // Get students who have taken Life Values test
        $studentsWithLifeValues = \App\Models\User::where('role', 'student')
            ->whereHas('lifeValuesResults')
            ->with(['lifeValuesResults', 'additionalInfo'])
            ->get()
            ->map(function ($student) {
                $latestResult = $student->lifeValuesResults->sortByDesc('created_at')->first();
                $info = $student->additionalInfo;
                $lrn = $info ? $info->lrn : null;
                $grade = $info ? $info->grade : null;
                $section = $info ? $info->section : null;
                $curriculum = $info ? $info->curriculum : null;

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'lrn' => $lrn,
                    'grade' => $grade,
                    'section' => $section,
                    'curriculum' => $curriculum,
                    'last_taken' => $latestResult ? $latestResult->created_at->format('Y-m-d H:i') : null,
                    'can_retake' => $latestResult ? (!$latestResult->admin_reopened && $latestResult->created_at < now()->subYear()) : false,
                    'admin_reopened' => $latestResult ? $latestResult->admin_reopened : false,
                ];
            });

        return view('superadmin.manage-test', compact('riasecEnabled', 'lifeValuesEnabled', 'studentsWithRiasec', 'studentsWithLifeValues'));
    }

    /** -------------------------------
      *  TOGGLE TEST STATUS
      *  ------------------------------- */
    public function toggleTest(Request $request)
    {
        $request->validate([
            'test_type' => 'required|in:riasec,life_values',
            'enabled' => 'required|boolean'
        ]);

        $testType = $request->test_type;
        $enabled = $request->enabled;

        // Store in cache (you could also store in database)
        $cacheKey = 'test_' . str_replace('_', '_', $testType) . '_enabled';
        \Cache::put($cacheKey, $enabled, now()->addDays(365)); // Store for 1 year

        // Log the activity
        \App\Models\ActivityLog::create([
            'admin_id' => \Auth::id(),
            'action' => 'Toggle Test Status',
            'description' => ucfirst(str_replace('_', ' ', $testType)) . ' test ' . ($enabled ? 'enabled' : 'disabled'),
        ]);

        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $testType)) . ' test ' . ($enabled ? 'enabled' : 'disabled') . ' successfully']);
    }

    /** -------------------------------
      *  STUDENT PROFILE LIST
      *  ------------------------------- */
    public function studentProfile()
    {
        $activeSchoolYearIds = \App\Models\AdditionalInformation::select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        $archivedSchoolYearIds = \App\Models\ArchivedStudentInformation::select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        // If all school years are archived
        if ($activeSchoolYearIds->diff($archivedSchoolYearIds)->isEmpty()) {
            $users = collect(); // empty collection
        } else {
            $users = \App\Models\User::where('role', 'student')
                ->whereIn('id', function ($query) use ($activeSchoolYearIds, $archivedSchoolYearIds) {
                    $query->select('learner_id')
                        ->from('additional_information')
                        ->whereIn('school_year_id', $activeSchoolYearIds->diff($archivedSchoolYearIds));
                })
                ->get();
        }

        // Get active curriculums
        $curriculums = \App\Models\Curriculum::where('is_archived', 0)->get();

        return view('superadmin.student-profile', compact('users', 'curriculums'));
    }

    /** -------------------------------
      *  FETCH ADDITIONAL INFO FOR STUDENT
      *  ------------------------------- */
    public function getAdditionalInfo($id)
    {
        $info = \App\Models\AdditionalInformation::where('learner_id', $id)->first();

        if (!$info) {
            return response()->json(['error' => 'No student information found.']);
        }

        $schoolYear = \App\Models\SchoolYear::find($info->school_year_id);
        $info->school_year_name = $schoolYear ? $schoolYear->school_year : 'N/A';

        if (is_string($info->living_mode)) {
            $info->living_mode = json_decode($info->living_mode, true);
        }

        // Add formatted dates for display
        $info->current_date_formatted = $info->current_date ? $info->current_date->format('F j, Y') : null;
        $info->birthday_formatted = $info->birthday ? $info->birthday->format('F j, Y') : null;

        // Add agreement status
        $info->agreements = [
            'student_agreement_1' => $info->student_agreement_1 ?? false,
            'student_agreement_2' => $info->student_agreement_2 ?? false,
            'parent_agreement_1' => $info->parent_agreement_1 ?? false,
            'parent_agreement_2' => $info->parent_agreement_2 ?? false,
        ];

        return response()->json($info);
    }

    /** -------------------------------
      *  VIEW STUDENT TEST RESULTS LIST
      *  ------------------------------- */
    public function viewTestResults()
    {
        // Get students who have taken either RIASEC or Life Values tests
        $studentsWithTests = \App\Models\User::where('role', 'student')
            ->where(function ($query) {
                $query->whereHas('riasecResults')
                      ->orWhereHas('lifeValuesResults');
            })
            ->with(['riasecResults', 'lifeValuesResults', 'additionalInfo'])
            ->get()
            ->map(function ($student) {
                $riasecResult = $student->riasecResults->last();
                $lifeValuesResult = $student->lifeValuesResults->last();

                $lrn = $student->additionalInformation ? $student->additionalInformation->lrn : null;



                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'lrn' => $lrn,
                    'email' => $student->email,
                    'has_riasec' => $riasecResult ? true : false,
                    'riasec_date' => $riasecResult ? $riasecResult->created_at->now()->format('Y-m-d H:i') : null,
                    'has_life_values' => $lifeValuesResult ? true : false,
                    'life_values_date' => $lifeValuesResult ? $lifeValuesResult->created_at->format('Y-m-d H:i') : null,
                    'additional_info' => $student->additionalInformation,
                ];
            });

        return view('superadmin.test-results', compact('studentsWithTests'));
    }

    /** -------------------------------
      *  LIFE VALUES TEST RESULT VIEW
      *  ------------------------------- */
    public function getLifeValuesResult($id, $result_id = null)
    {
        $student = \App\Models\User::find($id);

        // Get all Life Values results for this student, ordered by oldest first (chronological order)
        $allResults = \App\Models\LifeValuesResult::where('user_id', $id)->orderBy('created_at', 'asc')->get();

        if ($allResults->isEmpty()) {
            return redirect()->back()->with('error', 'No Life Values result found for this student.');
        }

        // Determine which result to show
        if ($result_id) {
            $result = $allResults->find($result_id);
            if (!$result) {
                return redirect()->route('superadmin.student-life-values', $id)->with('error', 'Result not found.');
            }
        } else {
            $result = $allResults->last(); // Latest (newest)
        }

        $scores = $result->scores;
        if (!is_array($scores)) {
            $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
        }

        // Get previous result for comparison (from the linked previous_result_id)
        $previousResult = $result->previous_result_id ? \App\Models\LifeValuesResult::find($result->previous_result_id) : null;
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

        return view('testing.results.life-values-results', compact('scores', 'student', 'result', 'previousResult', 'previousScores', 'allResults', 'nextResult', 'prevResult', 'currentAttempt', 'is_latest') + ['is_admin' => true]);
    }

    /** -------------------------------
      *  RIASEC TEST RESULT VIEW
      *  ------------------------------- */
    public function viewStudentRiasec($id, $result_id = null)
    {
        $student = \App\Models\User::find($id);

        // Get all RIASEC results for this student, ordered by oldest first (chronological order)
        $allResults = \App\Models\RiasecResult::where('user_id', $id)->orderBy('created_at', 'asc')->get();

        if ($allResults->isEmpty()) {
            return redirect()->back()->with('error', 'No RIASEC result found for this student.');
        }

        // Determine which result to show
        if ($result_id) {
            $result = $allResults->find($result_id);
            if (!$result) {
                return redirect()->route('superadmin.student-riasec', $id)->with('error', 'Result not found.');
            }
        } else {
            $result = $allResults->last(); // Latest (newest)
        }

        $scores = $result->scores;
        $top3 = collect($scores)->sortDesc()->take(3);
        $totalScore = array_sum($scores);
        $averageScore = count($scores) > 0 ? round($totalScore / count($scores), 2) : 0;

        $descriptions = [
            'R' => 'Realistic (Doers)',
            'I' => 'Investigative (Thinkers)',
            'A' => 'Artistic (Creators)',
            'S' => 'Social (Helpers)',
            'E' => 'Enterprising (Persuaders)',
            'C' => 'Conventional (Organizers)',
        ];

        // Get previous result for comparison (from the linked previous_result_id)
        $previousResult = $result->previous_result_id ? \App\Models\RiasecResult::find($result->previous_result_id) : null;
        $previousScores = $previousResult ? $previousResult->scores : [];
        $previousTop3 = collect($previousScores)->sortDesc()->take(3);

        // Navigation: find current index in allResults (chronological order: 0 = oldest, last = newest)
        $currentIndex = $allResults->search(function ($item) use ($result) {
            return $item->id == $result->id;
        });

        // Next = newer result (higher index), Previous = older result (lower index)
        $nextResult = $currentIndex < $allResults->count() - 1 ? $allResults[$currentIndex + 1] : null;
        $prevResult = $currentIndex > 0 ? $allResults[$currentIndex - 1] : null;

        // Calculate attempt number (1 = oldest/initial, higher numbers = more recent retakes)
        $currentAttempt = $currentIndex + 1;

        // Check if this is the latest result
        $is_latest = ($result->id == $allResults->last()->id);

        return view('testing.results.riasec-result', compact('scores', 'top3', 'descriptions', 'student', 'result', 'previousResult', 'previousScores', 'previousTop3', 'allResults', 'nextResult', 'prevResult', 'currentAttempt', 'is_latest') + ['is_admin' => true]);
    }

    /** -------------------------------
      *  UPDATE STUDENT ADDITIONAL INFO
      *  ------------------------------- */
    public function updateStudentInfo(Request $request, $id)
    {
        $baseRules = [
            // Basic Info
            'student_name' => 'required|string|max:255|regex:/^[A-Za-zÑñ\s\-\']+$/u',
            'lrn' => 'required|string|min:11|max:12',
            'sex' => 'required|string|max:10',
            'grade' => 'required|string|max:50',
            'curriculum' => 'required|string|max:100',
            'section' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|size:11',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:1',
            'religion' => 'required|string|max:100',
            'nationality' => 'required|string|max:100',
            'fb_messenger' => 'nullable|string|max:255',
            'disability' => 'nullable|string|max:255',
            'living_mode' => 'required|array',
            'living_mode.*' => 'required|string',

            // Mother
            'mother_name' => 'nullable|string|max:255',
            'mother_age' => 'nullable|integer',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_place_work' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:50',
            'mother_fb' => 'nullable|string|max:255',

            // Father
            'father_name' => 'nullable|string|max:255',
            'father_age' => 'nullable|integer',
            'father_occupation' => 'nullable|string|max:255',
            'father_place_work' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:50',
            'father_fb' => 'nullable|string|max:255',

            // Guardian
            'guardian_name' => 'nullable|string|max:255',
            'guardian_age' => 'nullable|integer',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_place_work' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'guardian_fb' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',

            // Profile photo (optional) - allow admin to change student profile image
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

            // First validate base rules (parent/guardian fields nullable here)
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $baseRules);
            $validator->validate();
            $validated = $validator->validated();

            // No conditional requirements for parent/guardian fields

        // 🔹 Get old values for logging
        $user = \App\Models\User::find($id);
        $oldUserName = $user ? $user->name : null;
        $info = \App\Models\AdditionalInformation::where('learner_id', $id)->first();
        $oldInfo = $info ? $info->toArray() : [];

        // 🔹 Update student name in users table (if provided)
        if (!empty($validated['student_name'])) {
            if ($user) {
                $user->name = $validated['student_name'];
                $user->save();
            }
            unset($validated['student_name']); // remove from $validated so it won't go into AdditionalInformation
        }

        // Handle profile picture upload separately (so $validated can be filled into model)
        if ($request->hasFile('profile_picture')) {
            try {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-\_]/', '_', $file->getClientOriginalName());
                $file->move(public_path('profiles'), $filename);
                $validated['profile_picture'] = 'profiles/' . $filename;
            } catch (\Exception $e) {
                // If upload fails, ignore and proceed without changing the photo
                unset($validated['profile_picture']);
            }
        }

        // 🔹 Find or create the additional info record
        $info = \App\Models\AdditionalInformation::firstOrNew(['learner_id' => $id]);
        if (!$info->exists) {
            $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->where('archived', 0)->first();
            if ($activeSchoolYear) {
                $info->school_year_id = $activeSchoolYear->id;
            }
        }
        $info->fill($validated);
        $info->save();

        // 🔹 Log the activity
        $newInfo = $info->fresh()->toArray();
        $newUserName = $user ? $user->fresh()->name : null;

        $changes = [];
        if ($oldUserName !== $newUserName) {
            $changes[] = "Student Name: '{$oldUserName}' → '{$newUserName}'";
        }

        foreach ($validated as $key => $value) {
            // Skip birthday as it's not important for activity logging
            if ($key === 'birthday') {
                continue;
            }

            $oldValue = $oldInfo[$key] ?? null;
            $newValue = $value;

            // Convert arrays to string for logging
            if (is_array($oldValue)) {
                $oldValue = implode(', ', $oldValue);
            }
            if (is_array($newValue)) {
                $newValue = implode(', ', $newValue);
            }

            if ($oldValue != $newValue) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . ": '{$oldValue}' → '{$newValue}'";
            }
        }

        if (!empty($changes)) {
            \App\Models\ActivityLog::create([
                'admin_id' => \Auth::id(),
                'action' => 'Updated Student Information',
                'description' => 'Updated information for student: ' . ($user ? $user->name : 'ID: ' . $id) . '. Changes: ' . implode(', ', $changes),
                'student_id' => $id,
                'old_values' => array_merge(['user_name' => $oldUserName], $oldInfo),
                'new_values' => array_merge(['user_name' => $newUserName], $newInfo),
                ]);
            }

            // 🔹 Redirect back with confirmation
            return redirect()->back()->with('success', 'Student information updated successfully!');
    }

    /** -------------------------------
      *  ACTIVITY LOG VIEW
      *  ------------------------------- */
    public function activityLog()
    {
        $logs = \App\Models\ActivityLog::with(['admin', 'student', 'student.additionalInfo'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('superadmin.activity-log', compact('logs'));
    }

}
