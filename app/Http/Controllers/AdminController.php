<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AdditionalInformation;
use App\Models\SchoolYear;
use App\Models\RiasecResult;
use App\Models\LifeValuesResult;
use App\Models\ActivityLog;
use App\Models\Curriculum;
use Carbon\Carbon;

class AdminController extends Controller
{
    /** -------------------------------
     *  DASHBOARD MAIN VIEW
     *  ------------------------------- */
    public function dashboard()
    {
        // Get all archived school year IDs
        $archivedSchoolYearIds = DB::table('archived_student_information')
            ->distinct()
            ->pluck('school_year_id');

        // Get active (non-archived) school years
        $activeSchoolYear = SchoolYear::where('is_active', 1)
            ->where('archived', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        // If no active, try latest non-archived
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::where('archived', 0)
                ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
                ->first();
        }

        // If everything is archived, set both to null and 0
        if (!$activeSchoolYear) {
            return view('admin.dashboard', [
                'studentCount' => 0,
                'activeSchoolYear' => null
            ]);
        }

        // Count students excluding archived school years
        $studentCount = DB::table('additional_information')
            ->whereNotIn('school_year_id', $archivedSchoolYearIds)
            ->distinct('learner_id')
            ->count('learner_id');

        return view('admin.dashboard', compact('studentCount', 'activeSchoolYear'));
    }

    /** -------------------------------
     *  ADMIN PROFILE VIEW
     *  ------------------------------- */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.update-profile', compact('user'));
    }

    /** -------------------------------
     *  UPDATE ADMIN PROFILE
     *  ------------------------------- */
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
                'unique:users,email,' . $user->id,
                'regex:/^[\w.%+-]+@(gmail|yahoo)\.com$/i',
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

    /** -------------------------------
     *  STUDENT PROFILE LIST
     *  ------------------------------- */
    public function studentProfile()
    {
        $activeSchoolYearIds = DB::table('additional_information')
            ->select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        $archivedSchoolYearIds = DB::table('archived_student_information')
            ->select('school_year_id')
            ->distinct()
            ->pluck('school_year_id');

        // If all school years are archived
        if ($activeSchoolYearIds->diff($archivedSchoolYearIds)->isEmpty()) {
            $users = collect(); // empty collection
        } else {
            $users = User::where('role', 'student')
                ->whereIn('id', function ($query) use ($activeSchoolYearIds, $archivedSchoolYearIds) {
                    $query->select('learner_id')
                        ->from('additional_information')
                        ->whereIn('school_year_id', $activeSchoolYearIds->diff($archivedSchoolYearIds));
                })
                ->get();
        }

        // Get active curriculums
        $curriculums = Curriculum::where('is_archived', 0)->get();

        return view('admin.student-profile', compact('users', 'curriculums'));
    }

    /** -------------------------------
     *  FETCH ADDITIONAL INFO FOR STUDENT
     *  ------------------------------- */
    public function getAdditionalInfo($id)
    {
        $info = AdditionalInformation::where('learner_id', $id)->first();

        if (!$info) {
            return response()->json(['error' => 'No student information found.']);
        }

        $schoolYear = SchoolYear::find($info->school_year_id);
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
     *  STUDENT CHART DATA (BAR CHART)
     *  ------------------------------- */
    public function getStudentChartData()
    {
        $archivedSchoolYearIds = DB::table('archived_student_information')
            ->distinct()
            ->pluck('school_year_id');

        // Get non-archived school years only
        $schoolYears = SchoolYear::whereNotIn('id', $archivedSchoolYearIds)
            ->orderBy('school_year')
            ->get();

        $maleData = [];
        $femaleData = [];
        $labels = [];

        foreach ($schoolYears as $sy) {
            $labels[] = $sy->school_year;
            $maleData[] = AdditionalInformation::where('school_year_id', $sy->id)
                ->where('sex', 'Male')
                ->count();
            $femaleData[] = AdditionalInformation::where('school_year_id', $sy->id)
                ->where('sex', 'Female')
                ->count();
        }

        return response()->json([
            'labels' => $labels,
            'maleData' => $maleData,
            'femaleData' => $femaleData
        ]);
    }

    /** -------------------------------
     *  CURRICULUM PIE CHART DATA
     *  ------------------------------- */
    public function getCurriculumChartData()
    {
        $archivedSchoolYearIds = DB::table('archived_student_information')
            ->distinct()
            ->pluck('school_year_id');

        $curriculumCounts = DB::table('additional_information')
            ->select('curriculum', DB::raw('COUNT(*) as total'))
            ->whereNotIn('school_year_id', $archivedSchoolYearIds)
            ->groupBy('curriculum')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        if ($curriculumCounts->isEmpty()) {
            return response()->json(['labels' => [], 'data' => []]);
        }

        return response()->json([
            'labels' => $curriculumCounts->pluck('curriculum'),
            'data' => $curriculumCounts->pluck('total')
        ]);
    }

    /** -------------------------------
      *  LIFE VALUES TEST RESULT VIEW
      *  ------------------------------- */
    public function getLifeValuesResult($id, $result_id = null)
    {
        $student = User::find($id);

        // Get all Life Values results for this student, ordered by oldest first (chronological order)
        $allResults = LifeValuesResult::where('user_id', $id)->orderBy('created_at', 'asc')->get();

        if ($allResults->isEmpty()) {
            return redirect()->back()->with('error', 'No Life Values result found for this student.');
        }

        // Determine which result to show
        if ($result_id) {
            $result = $allResults->find($result_id);
            if (!$result) {
                return redirect()->route('admin.student-life-values', $id)->with('error', 'Result not found.');
            }
        } else {
            $result = $allResults->last(); // Latest (newest)
        }

        $scores = $result->scores;
        if (!is_array($scores)) {
            $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
        }

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

        return view('testing.results.life-values-results', compact('scores', 'student', 'result', 'previousResult', 'previousScores', 'allResults', 'nextResult', 'prevResult', 'currentAttempt') + ['is_admin' => true]);
    }

    /** -------------------------------
      *  RIASEC TEST RESULT VIEW
      *  ------------------------------- */
    public function viewStudentRiasec($id, $result_id = null)
    {
        $student = User::find($id);

        // Get all RIASEC results for this student, ordered by oldest first (chronological order)
        $allResults = RiasecResult::where('user_id', $id)->orderBy('created_at', 'asc')->get();

        if ($allResults->isEmpty()) {
            return redirect()->back()->with('error', 'No RIASEC result found for this student.');
        }

        // Determine which result to show
        if ($result_id) {
            $result = $allResults->find($result_id);
            if (!$result) {
                return redirect()->route('admin.student-riasec', $id)->with('error', 'Result not found.');
            }
        } else {
            $result = $allResults->last(); // Latest (newest)
        }

        $scores = $result->scores;
        $top3 = collect($scores)->sortDesc()->take(3);

        $descriptions = [
            'R' => 'Realistic (Doers)',
            'I' => 'Investigative (Thinkers)',
            'A' => 'Artistic (Creators)',
            'S' => 'Social (Helpers)',
            'E' => 'Enterprising (Persuaders)',
            'C' => 'Conventional (Organizers)',
        ];

        // Get previous result for comparison (from the linked previous_result_id)
        $previousResult = $result->previous_result_id ? RiasecResult::find($result->previous_result_id) : null;
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
    
        return view('testing.results.riasec-result', compact('scores', 'top3', 'descriptions', 'student', 'result', 'previousResult', 'previousScores', 'previousTop3', 'allResults', 'nextResult', 'prevResult', 'currentAttempt') + ['is_admin' => true]);
    }

    /** -------------------------------
      *  DASHBOARD STATS API
      *  ------------------------------- */
    public function getDashboardStats()
    {
        $archivedSchoolYearIds = DB::table('archived_student_information')
            ->distinct()
            ->pluck('school_year_id');

        // If all are archived → show 0
        $hasActive = SchoolYear::whereNotIn('id', $archivedSchoolYearIds)->exists();
        if (!$hasActive) {
            return response()->json([
                'students' => 0,
                'riasec' => 0,
                'lifeValues' => 0
            ]);
        }

        $totalStudents = User::where('role', 'student')->count();
        $riasecCount = RiasecResult::distinct('user_id')->count('user_id');
        $lifeValuesCount = LifeValuesResult::distinct('user_id')->count('user_id');

        return response()->json([
            'students' => $totalStudents,
            'riasec' => $riasecCount,
            'lifeValues' => $lifeValuesCount,
        ]);
    }

    /** -------------------------------
      *  VIEW STUDENT TEST RESULTS LIST
      *  ------------------------------- */
    public function viewTestResults()
    {
        // Get students who have taken either RIASEC or Life Values tests
        $studentsWithTests = User::where('role', 'student')
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

        return view('admin.test-results', compact('studentsWithTests'));
    }
   /** -------------------------------
 *  UPDATE STUDENT ADDITIONAL INFO
 *  ------------------------------- */
public function updateStudentInfo(Request $request, $id)
{
        $validated = $request->validate([
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
    ]);

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

    // 🔹 Find or create the additional info record
    $info = \App\Models\AdditionalInformation::firstOrNew(['learner_id' => $id]);
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

        if ($oldValue != $newValue) {
            $changes[] = ucfirst(str_replace('_', ' ', $key)) . ": '{$oldValue}' → '{$newValue}'";
        }
    }

    if (!empty($changes)) {
        ActivityLog::create([
            'admin_id' => Auth::id(),
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
        $logs = ActivityLog::with(['admin', 'student', 'student.additionalInfo'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.activity-log', compact('logs'));
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
        $studentsWithRiasec = User::where('role', 'student')
            ->whereHas('riasecResults')
            ->with(['riasecResults' => function($query) {
                $query->latest()->first();
            }, 'additionalInfo'])
            ->get()
            ->map(function ($student) {
                $latestResult = $student->riasecResults->last();
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
        $studentsWithLifeValues = User::where('role', 'student')
            ->whereHas('lifeValuesResults')
            ->with(['lifeValuesResults' => function($query) {
                $query->latest()->first();
            }, 'additionalInfo'])
            ->get()
            ->map(function ($student) {
                $latestResult = $student->lifeValuesResults->last();
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

        return view('admin.manage-test', compact('riasecEnabled', 'lifeValuesEnabled', 'studentsWithRiasec', 'studentsWithLifeValues'));
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
        ActivityLog::create([
            'admin_id' => Auth::id(),
            'action' => 'Toggle Test Status',
            'description' => ucfirst(str_replace('_', ' ', $testType)) . ' test ' . ($enabled ? 'enabled' : 'disabled'),
        ]);

        return response()->json(['success' => true, 'message' => ucfirst(str_replace('_', ' ', $testType)) . ' test ' . ($enabled ? 'enabled' : 'disabled') . ' successfully']);
    }


}