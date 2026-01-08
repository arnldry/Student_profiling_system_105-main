<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;


class CurriculumController extends Controller
{
    // Show list of curricula
   public function index()
    {
        // Active curriculums
        $curriculums = Curriculum::where('is_archived', 0)->get();

        // Archived curriculums
        $archivedCurriculums = Curriculum::where('is_archived', 1)->get();

        return view('superadmin.curriculum', compact('curriculums', 'archivedCurriculums'));
    }

    // Show create form
    public function create()
    {
        return view('superadmin.curriculum.create');
    }

    // Store new curriculum
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:curriculum,name', // table name fixed
        ]);

        Curriculum::create([
            'name' => $request->name,
        ]);

        return redirect()->route('superadmin.curriculum')
            ->with('success', 'Curriculum added successfully!');
    }
   public function update(Request $request, $id)
    {
        $curriculum = Curriculum::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:curriculum,name,' . $curriculum->id,
        ]);

        $curriculum->update([
            'name' => $request->name,
        ]);

        return redirect()->route('superadmin.curriculum')
            ->with('success', 'Curriculum updated successfully!');
    }

    // Archive a curriculum
    public function archive($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->update(['is_archived' => 1]);

        return redirect()->route('superadmin.curriculum') // make sure your route name matches
                        ->with('success', 'Curriculum archived successfully!');
    }

    // Unarchive a curriculum
    public function unarchive($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->update(['is_archived' => 0]);

        return redirect()->route('superadmin.curriculum') // make sure your route name matches
                        ->with('success', 'Curriculum restored successfully!');
    }
}
