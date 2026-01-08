<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\AdditionalInformation;
use App\Models\ArchivedStudentInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivedStudentDataController extends Controller
{
    /**
     * Show active school years (with students in additional_information)
     */
    public function studentData()
    {
        $activeSchoolYears = SchoolYear::whereIn('id', function ($query) {
            $query->select('school_year_id')
                  ->from('additional_information')
                  ->distinct();
        })
        ->orderBy('id', 'desc')
        ->get();

        return view('superadmin.archived-student-data', compact('activeSchoolYears'));
    }

    /**
     * Archive students for a specific school year
     */
    public function archive($schoolYearId)
    {
        DB::transaction(function () use ($schoolYearId) {
            $students = AdditionalInformation::where('school_year_id', $schoolYearId)->get();

            foreach ($students as $student) {
                ArchivedStudentInformation::create([
                    'school_year_id'     => $schoolYearId,
                    'learner_id'         => $student->learner_id,
                    'lrn'                => $student->lrn,
                    'sex'                => $student->sex,
                    'grade'              => $student->grade,
                    'curriculum'         => $student->curriculum,
                    'section'            => $student->section,
                    'disability'         => $student->disability,
                    'living_mode'        => $student->living_mode,
                    'address'            => $student->address,
                    'contact_number'     => $student->contact_number,
                    'birthday'           => $student->birthday,
                    'age'                => $student->age,
                    'religion'           => $student->religion,
                    'nationality'        => $student->nationality,
                    'fb_messenger'       => $student->fb_messenger,
                    'current_date'       => $student->current_date,
                    'profile_picture'    => $student->profile_picture,
                    'father_name'        => $student->father_name,
                    'father_age'         => $student->father_age,
                    'father_occupation'  => $student->father_occupation,
                    'father_place_work'  => $student->father_place_work,
                    'father_contact'     => $student->father_contact,
                    'father_fb'          => $student->father_fb,
                    'mother_name'        => $student->mother_name,
                    'mother_age'         => $student->mother_age,
                    'mother_occupation'  => $student->mother_occupation,
                    'mother_place_work'  => $student->mother_place_work,
                    'mother_contact'     => $student->mother_contact,
                    'mother_fb'          => $student->mother_fb,
                    'guardian_name'      => $student->guardian_name,
                    'guardian_age'       => $student->guardian_age,
                    'guardian_occupation' => $student->guardian_occupation,
                    'guardian_place_work' => $student->guardian_place_work,
                    'guardian_contact'   => $student->guardian_contact,
                    'guardian_fb'        => $student->guardian_fb,
                    'guardian_relationship' => $student->guardian_relationship,
                    'student_agreement_1' => $student->student_agreement_1,
                    'student_agreement_2' => $student->student_agreement_2,
                    'parent_agreement_1' => $student->parent_agreement_1,
                    'parent_agreement_2' => $student->parent_agreement_2,
                ]);
            }

            // Delete the original records from additional_information
            AdditionalInformation::where('school_year_id', $schoolYearId)->delete();
        });

        return redirect()->route('superadmin.archived-student-data')
                         ->with('success', 'Student data archived successfully!');
    }

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

        return view('superadmin.archived-files', compact('archivedSchoolYears'));
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
        return view('superadmin.archived-student-list', compact('schoolYear', 'archivedStudents'));
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

        // Add school_year_name to the response
        $studentData = $student->toArray();
        $studentData['school_year_name'] = $student->schoolYear ? $student->schoolYear->school_year : null;

        // Add formatted current date
        $studentData['current_date_formatted'] = $student->current_date ? \Carbon\Carbon::parse($student->current_date)->format('M d, Y') : null;

        return response()->json($studentData);
    }
}
