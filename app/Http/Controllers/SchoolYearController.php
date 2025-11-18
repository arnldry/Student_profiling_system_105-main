<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolYear;

class SchoolYearController extends Controller
{
    public function schoolYear()
    {
        // Separate queries para sa Active at Archived
        $activeSchoolYears = SchoolYear::where('is_archived', false)->get();
        $archivedSchoolYears = SchoolYear::where('is_archived', true)->get();

        return view('superadmin.school-year', compact('activeSchoolYears', 'archivedSchoolYears'));
    }

    // Archive a school year
    public function archive($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);

        // Extract start year from the school year string, e.g., "2024-2025"
        [$startYear, $endYear] = explode('-', $schoolYear->school_year);

        $currentYear = date('Y');

        // // Allow archiving only if the end year is less than or equal to current year
        // if ((int)$endYear > $currentYear) {
        //     return redirect()->route('superadmin.school-year')
        //         ->with('error', 'Cannot archive a school year that is still ongoing!');
        // }

        $schoolYear->update(['is_archived' => true]);

        return redirect()->route('superadmin.school-year')
            ->with('success', 'School Year archived successfully!');
    }

    // Unarchive a school year
    public function unarchive($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $schoolYear->update(['is_archived' => false]);

        return redirect()->route('superadmin.school-year')
            ->with('success', 'School Year unarchived successfully!');
    }

    // Store new school year
    public function store(Request $request)
    {
        $request->validate([
            'school_year' => [
                'required',
                'string',
                'max:9',
                'unique:school_years,school_year',
                'regex:/^[0-9]{4}-[0-9]{4}$/'
            ],
        ], [
            'school_year.regex' => 'The school year format must be YYYY-YYYY (e.g., 2025-2026).',
        ]);

        [$start, $end] = explode('-', $request->school_year);
        $currentYear = date('Y');

        // // Prevent adding if current active school year hasn't ended
        $currentActive = SchoolYear::where('is_archived', false)->latest()->first();
        if ($currentActive) {
            [$activeStart, $activeEnd] = explode('-', $currentActive->school_year);
            if ((int)$activeEnd >= $currentYear) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Cannot add a new school year until the current school year ends!');
            }
        }

        // Rule: second year must be exactly start + 1 (no gaps like 2026-2028)
        if ((int)$end !== (int)$start + 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['school_year' => 'The school year must be consecutive (e.g., 2026-2027). Gaps like 2026-2028 are not allowed.']);
        }

        // Prevent adding a school year that starts before the current calendar year
        if ((int)$start < $currentYear) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['school_year' => "The school year cannot start before {$currentYear}."]);
        }

        SchoolYear::create([
            'school_year' => $request->school_year,
        ]);

        return redirect()->route('superadmin.school-year')
            ->with('success', 'School Year added successfully!');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'school_year' => 'required|string|max:9|unique:school_years,school_year,' . $id,
    //     ]);

    //     $schoolYear = SchoolYear::findOrFail($id);
    //     $schoolYear->update([
    //         'school_year' => $request->school_year,
    //     ]);

    //     return redirect()->route('superadmin.school-year.index')->with('success', 'School Year updated successfully!');
    // }

    // public function destroy($id)
    // {
    //     $schoolYear = SchoolYear::findOrFail($id);
    //     $schoolYear->delete();

    //     return redirect()->route('superadmin.school-year.index')->with('success', 'School Year deleted successfully!');
    // }
}
