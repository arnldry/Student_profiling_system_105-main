<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchivedStudentInformation;
use App\Models\SchoolYear;

class AdminArchivedStudentController extends Controller
{
    // Show archived school years
    /**
     * Show all archived school years (buttons)
     */
    public function archivedFiles()
    {
        $archivedSchoolYears = SchoolYear::whereIn('id', function ($query) {
            $query->select('school_year_id')
                  ->from('archived_student_information')
                  ->distinct();
        })
        ->orderBy('id', 'desc')
        ->get();

        // Get active curriculums for the edit modal
        $curriculums = \App\Models\Curriculum::where('is_archived', 0)->get();

        return view('admin.archived-files-data', compact('archivedSchoolYears', 'curriculums'));
    }

    /**
     * Return archived students for a given school year
     * Can be used for Blade view or AJAX
     */
    public function showArchivedStudents($schoolYearId, Request $request)
    {
        $archivedStudents = ArchivedStudentInformation::where('school_year_id', $schoolYearId)
            ->with('user') // eager load user for name
            ->orderBy('lrn', 'asc')
            ->get();

        if ($request->ajax()) {
            return response()->json($archivedStudents);
        }

        $schoolYear = SchoolYear::findOrFail($schoolYearId);
        return view('admin.archived-student-list', compact('schoolYear', 'archivedStudents'));
    }


    /**
     * Return single archived student (for SweetAlert)
     */
    public function getArchivedStudent($id)
    {
        $student = ArchivedStudentInformation::with('user', 'schoolYear')->find($id);

        if (!$student) {
            return response()->json(['error' => 'Archived student not found.'], 404);
        }

        // Add school_year_name for easier access in JS
        $student->school_year_name = $student->schoolYear->school_year ?? '-';

        // Add formatted dates for display
        $student->current_date_formatted = $student->current_date ? $student->current_date->format('F j, Y') : null;

        // Add agreement status
        $student->agreements = [
            'student_agreement_1' => $student->student_agreement_1 ?? false,
            'student_agreement_2' => $student->student_agreement_2 ?? false,
            'parent_agreement_1' => $student->parent_agreement_1 ?? false,
            'parent_agreement_2' => $student->parent_agreement_2 ?? false,
        ];

        return response()->json($student);
    }

    /**
     * Update archived student information
     */
    public function updateArchivedStudentInfo(Request $request, $id)
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

            // Profile photo (optional) - allow admin to change archived student profile image
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ];

        // First validate base rules (parent/guardian fields nullable here)
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $baseRules);
        $validator->validate();
        $validated = $validator->validated();

        // Custom validation: Make parent/guardian fields required only when living mode is selected
        $livingMode = $validated['living_mode'];

        if (in_array('Living with Father', $livingMode) && empty($validated['father_name'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Father name is required when "Living with Father" is selected.'
                ], 422);
            } else {
                return back()->withInput()->withErrors(['father_name' => 'Father name is required when "Living with Father" is selected.']);
            }
        }

        if (in_array('Living with Mother', $livingMode) && empty($validated['mother_name'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mother name is required when "Living with Mother" is selected.'
                ], 422);
            } else {
                return back()->withInput()->withErrors(['mother_name' => 'Mother name is required when "Living with Mother" is selected.']);
            }
        }

        if (in_array('Living with Other Guardians', $livingMode) && empty($validated['guardian_name'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guardian name is required when "Living with Other Guardians" is selected.'
                ], 422);
            } else {
                return back()->withInput()->withErrors(['guardian_name' => 'Guardian name is required when "Living with Other Guardians" is selected.']);
            }
        }

        // 🔹 Get old values for logging
        $archivedStudent = ArchivedStudentInformation::find($id);
        if (!$archivedStudent) {
            return back()->with('error', 'Archived student not found.');
        }

        $user = $archivedStudent->user;
        $oldUserName = $user ? $user->name : null;
        $oldInfo = $archivedStudent->toArray();

        // 🔹 Update student name in users table (if provided)
        if (!empty($validated['student_name'])) {
            if ($user) {
                $user->name = $validated['student_name'];
                $user->save();
            }
            unset($validated['student_name']); // remove from $validated so it won't go into ArchivedStudentInformation
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

        // 🔹 Update the archived student info
        $archivedStudent->fill($validated);
        $archivedStudent->save();

        // 🔹 Log the activity
        $newInfo = $archivedStudent->fresh()->toArray();
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
                'admin_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'Updated Archived Student Information',
                'description' => 'Updated information for archived student: ' . ($user ? $user->name : 'ID: ' . $id) . '. Changes: ' . implode(', ', $changes),
                'student_id' => $user ? $user->id : null,
                'old_values' => array_merge(['user_name' => $oldUserName], $oldInfo),
                'new_values' => array_merge(['user_name' => $newUserName], $newInfo),
            ]);
        }

        // 🔹 Redirect back with confirmation or return JSON for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Archived student information updated successfully!'
            ]);
        } else {
            return redirect()->back()->with('success', 'Archived student information updated successfully!');
        }
    }
}
