<?php

namespace App\Http\Controllers;

use App\Models\AdditionalInformation;
use App\Models\Curriculum;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdditionalInformationController extends Controller
{
    public function additionalInfo()
    {
        // $this->checkDatabase();

        // Determine current school year consistent with dashboards:
        // FIXED: look for is_active = 0 instead of 1
        $currentSchoolYear = SchoolYear::where('is_active', 0)->orderBy('created_at', 'desc')->first();
        if (! $currentSchoolYear) {
            $currentSchoolYear = SchoolYear::where('archived', 0)
                ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
                ->first();
        }
        if (! $currentSchoolYear) {
            $currentSchoolYear = SchoolYear::latest()->first();
        }

        // Get active curricula
        $curriculums = Curriculum::where('is_archived', 0)->get();

        return view('student.additional-info', compact('currentSchoolYear', 'curriculums'));
    }
    public function store(Request $request)
    {
        $this->checkDatabase();

        $validated = $request->validate([
            'school_year' => 'required|exists:school_years,id',
            'lrn' => 'required|string|size:12', // Added size validation
            'sex' => 'required|string',
            'grade' => 'required|string',
            'curriculum' => 'required|string',
            'section' => 'required|string',
            'living_mode' => 'required|array',
            'living_mode.*' => 'required|string',
            'address' => 'required|string',
            'contact_number' => 'required|string|size:11',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:1',
            'religion' => 'required|string',
            'nationality' => 'required|string',
            'fb_messenger' => 'nullable|string',
            'disability' => 'nullable|string', // ✅ ADD VALIDATION

            // Parents & Guardians
            'father_name' => 'nullable|string',
            'father_age' => 'nullable|integer|min:1',
            'father_occupation' => 'nullable|string',
            'father_place_work' => 'nullable|string',
            'father_contact' => 'nullable|string',
            'father_fb' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_age' => 'nullable|integer|min:1',
            'mother_occupation' => 'nullable|string',
            'mother_place_work' => 'nullable|string',
            'mother_contact' => 'nullable|string',
            'mother_fb' => 'nullable|string',
            
            'guardian_name' => 'nullable|string',
            'guardian_age' => 'nullable|integer|min:1',
            'guardian_occupation' => 'nullable|string',
            'guardian_place_work' => 'nullable|string',
            'guardian_contact' => 'nullable|string',
            'guardian_fb' => 'nullable|string',

            // Agreements
            'student_agreement_1' => 'accepted',
            'student_agreement_2' => 'accepted',
            'parent_agreement_1' => 'accepted',
            'parent_agreement_2' => 'accepted',
        ]);

        // Prevent duplicate LRN for the same learner
        if (AdditionalInformation::where('lrn', $validated['lrn'])
            ->where('learner_id', '!=', Auth::id())
            ->exists()) {
            return back()->withInput()->withErrors(['lrn' => 'LRN already exists for another student.']);
        }

        try {
            // Check if user already has additional info for this school year
            $existingInfo = AdditionalInformation::where('learner_id', Auth::id())
                ->where('school_year_id', $validated['school_year'])
                ->first();

            if ($existingInfo) {
                return back()->withInput()->withErrors(['unexpected' => 'You have already submitted additional information for this school year.']);
            }

            AdditionalInformation::create([
                'school_year_id' => $validated['school_year'],
                'learner_id' => Auth::id(),
                'lrn' => $validated['lrn'],
                'current_date' => now()->toDateString(),
                'sex' => $validated['sex'],
                'grade' => $validated['grade'],
                'curriculum' => $validated['curriculum'],
                'section' => $validated['section'],
                'living_mode' => $validated['living_mode'], // ✅ Let Eloquent handle the casting
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'birthday' => $validated['birthday'],
                'age' => $validated['age'],
                'religion' => $validated['religion'],
                'nationality' => $validated['nationality'],
                'fb_messenger' => $validated['fb_messenger'] ?? null,
                'disability' => $validated['disability'] ?? null, // ✅ ADD THIS FIELD

                'father_name' => $validated['father_name'] ?? null,
                'father_age' => $validated['father_age'] ?? null,
                'father_occupation' => $validated['father_occupation'] ?? null,
                'father_place_work' => $validated['father_place_work'] ?? null,
                'father_contact' => $validated['father_contact'] ?? null,
                'father_fb' => $validated['father_fb'] ?? null,

                'mother_name' => $validated['mother_name'] ?? null,
                'mother_age' => $validated['mother_age'] ?? null,
                'mother_occupation' => $validated['mother_occupation'] ?? null,
                'mother_place_work' => $validated['mother_place_work'] ?? null,
                'mother_contact' => $validated['mother_contact'] ?? null,
                'mother_fb' => $validated['mother_fb'] ?? null,

                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_age' => $validated['guardian_age'] ?? null,
                'guardian_occupation' => $validated['guardian_occupation'] ?? null,
                'guardian_place_work' => $validated['guardian_place_work'] ?? null,
                'guardian_contact' => $validated['guardian_contact'] ?? null,
                'guardian_fb' => $validated['guardian_fb'] ?? null,

                'student_agreement_1' => true,
                'student_agreement_2' => true,
                'parent_agreement_1' => true,
                'parent_agreement_2' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving additional info: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withInput()->withErrors(['unexpected' => 'Something went wrong while saving your information. Please try again.']);
        }

        return redirect()->route('student.dashboard')->with('success', 'Additional Information saved successfully!');
    }

    // Add this method to check LRN uniqueness
    public function checkLrn(Request $request)
    {
        $request->validate([
            'lrn' => 'required|string'
        ]);

        $exists = AdditionalInformation::where('lrn', $request->lrn)
            ->where('learner_id', '!=', Auth::id())
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Check if database and required tables exist, otherwise throw 404.
     */
    // protected function checkDatabase()
    // {
    //     try {
    //         DB::connection()->getPdo();
    //         $tables = DB::select("SHOW TABLES");
    //         $tableNames = array_map('current', $tables);
    //         if (!in_array('users', $tableNames) || !in_array('additional_informations', $tableNames) || !in_array('school_years', $tableNames) || !in_array('curricula', $tableNames)) {
    //             throw new NotFoundHttpException('Database or required tables not found.');
    //         }
    //     } catch (\Throwable $e) {
    //         throw new NotFoundHttpException('Database not found.');
    //     }
    // }
}