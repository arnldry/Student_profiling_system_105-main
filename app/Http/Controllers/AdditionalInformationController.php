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


        // Determine current school year - must not be archived
        $currentSchoolYear = SchoolYear::where('is_archived', 0)
            ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
            ->first();

        // Get active curricula
        $curriculums = Curriculum::where('is_archived', 0)->get();

        return view('student.additional-info', compact('currentSchoolYear', 'curriculums'));
    }
    public function store(Request $request)
    {
        // Check if there's an active (non-archived) school year before processing
        $currentSchoolYear = SchoolYear::where('is_archived', 0)
            ->orderByRaw("CAST(SUBSTRING_INDEX(school_year, '-', 1) AS UNSIGNED) DESC")
            ->first();

        if (!$currentSchoolYear) {
            return back()->withInput()->withErrors(['school_year' => 'No active school year found. Please contact the administrator or guidance counselor.']);
        }

        // Check if there's an active curriculum before processing
        $activeCurricula = Curriculum::where('is_archived', 0)->exists();

        if (!$activeCurricula) {
            return back()->withInput()->withErrors(['curriculum' => 'No active curriculum found. Please contact the administrator or guidance counselor.']);
        }

        $validated = $request->validate([
            'school_year' => 'required|exists:school_years,id',
            'lrn' => 'required|string|min:11|max:12', // Allow 11-12 digits
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
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max

            // Parents & Guardians - All nullable, validation logic below
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

        // Custom validation: Make parent/guardian fields required only when living mode is selected
        $livingMode = $validated['living_mode'];

        if (in_array('Living with Father', $livingMode) && empty($validated['father_name'])) {
            return back()->withInput()->withErrors(['father_name' => 'Father name is required when "Living with Father" is selected.']);
        }

        if (in_array('Living with Mother', $livingMode) && empty($validated['mother_name'])) {
            return back()->withInput()->withErrors(['mother_name' => 'Mother name is required when "Living with Mother" is selected.']);
        }

        if (in_array('Living with Other Guardians', $livingMode) && empty($validated['guardian_name'])) {
            return back()->withInput()->withErrors(['guardian_name' => 'Guardian name is required when "Living with Other Guardians" is selected.']);
        }

        // Prevent duplicate LRN for the same learner
        if (AdditionalInformation::where('lrn', $validated['lrn'])
            ->where('learner_id', '!=', Auth::id())
            ->exists()) {
            return back()->withInput()->withErrors(['lrn' => 'LRN already exists for another student.']);
        }

        try {
            // Handle profile picture upload
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('profiles'), $filename);
                $profilePicturePath = 'profiles/' . $filename;
            }

            // Prepare data for saving
            $data = [
                'school_year_id' => $validated['school_year'],
                'learner_id' => Auth::id(),
                'lrn' => $validated['lrn'],
                'current_date' => now()->toDateString(),
                'sex' => $validated['sex'],
                'grade' => $validated['grade'],
                'curriculum' => $validated['curriculum'],
                'section' => $validated['section'],
                'living_mode' => $validated['living_mode'],
                'address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'birthday' => $validated['birthday'],
                'age' => $validated['age'],
                'religion' => $validated['religion'],
                'nationality' => $validated['nationality'],
                'fb_messenger' => $validated['fb_messenger'] ?? null,
                'disability' => $validated['disability'] ?? null,
                'profile_picture' => $profilePicturePath,

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
            ];

            // Check if user already has additional info for this school year
            $existingInfo = AdditionalInformation::where('learner_id', Auth::id())
                ->where('school_year_id', $validated['school_year'])
                ->first();

            if ($existingInfo) {
                // Update existing record (keep existing profile picture if not uploading new one)
                if (!$profilePicturePath) {
                    unset($data['profile_picture']);
                }
                $existingInfo->update($data);
            } else {
                // Create new record
                AdditionalInformation::create($data);
            }

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

    public function downloadProfilePicture()
    {
        $additionalInfo = AdditionalInformation::where('learner_id', Auth::id())->first();

        if (!$additionalInfo || !$additionalInfo->profile_picture) {
            return redirect()->back()->with('error', 'Profile picture not found.');
        }

        $path = storage_path('app/public/' . $additionalInfo->profile_picture);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($path);
    }

}