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

        return view('admin.archived-files-data', compact('archivedSchoolYears'));
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
        $student = ArchivedStudentInformation::with('user')->find($id);

        if (!$student) {
            return response()->json(['error' => 'Archived student not found.'], 404);
        }

        return response()->json($student);
    }
}
