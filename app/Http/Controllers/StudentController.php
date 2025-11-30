<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdditionalInformation;
use App\Models\ArchivedStudentInformation;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{

   public function __construct()
      {
          view()->composer('*', function ($view) {
              $hasAdditionalInfo = false;
              $additionalInfo = null;

              if (Auth::check() && Auth::user()->role === 'student') {
                  $hasAdditionalInfo = AdditionalInformation::where('learner_id', Auth::id())->exists();
                  if (!$hasAdditionalInfo) {
                      // Check archived if not in active
                      $hasAdditionalInfo = ArchivedStudentInformation::where('learner_id', Auth::id())->exists();
                  }
                  if ($hasAdditionalInfo) {
                      $additionalInfo = AdditionalInformation::where('learner_id', Auth::id())->latest()->first();
                      if (!$additionalInfo) {
                          // If not in active, get from archived
                          $additionalInfo = ArchivedStudentInformation::where('learner_id', Auth::id())->latest()->first();
                      }
                  }
              }

              $hasActiveCurricula = \App\Models\Curriculum::where('is_archived', 0)->exists();

              $view->with('hasAdditionalInfo', $hasAdditionalInfo);
              $view->with('additionalInfo', $additionalInfo);
              $view->with('hasActiveCurricula', $hasActiveCurricula);
          });
      }

    public function dashboard(){
        $user = auth()->user();

        // Get additional information for profile picture
        $additionalInfo = AdditionalInformation::where('learner_id', $user->id)->latest()->first();

        // Get RIASEC results for the table
        $riasecResults = \App\Models\RiasecResult::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $riasecResults = $riasecResults->map(function ($result, $index) use ($riasecResults) {
            $scores = $result->scores;
            if (!is_array($scores)) {
                $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
            }
            $result->decoded_scores = $scores;
            $result->top3 = collect($scores)->sortDesc()->take(3)->keys()->implode('');
            $result->take_number = $riasecResults->count() - $index; // Most recent is highest number
            return $result;
        });

        // Calculate total scores across all RIASEC tests
        $totalScores = [];
        if ($riasecResults->count() > 0) {
            $allScores = $riasecResults->pluck('decoded_scores');
            foreach (['R', 'I', 'A', 'S', 'E', 'C'] as $letter) {
                $totalScores[$letter] = $allScores->sum($letter);
            }
            $totalScores['total'] = array_sum($totalScores);
            $totalScores['average'] = count($totalScores) > 0 ? round($totalScores['total'] / 6, 2) : 0;
            $totalScores['top3'] = collect($totalScores)->except(['total', 'average'])->sortDesc()->take(3)->keys()->implode('');
        }

        // Get Life Values results for the table
        $lifeValuesResults = \App\Models\LifeValuesResult::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $lifeValuesResults = $lifeValuesResults->map(function ($result, $index) use ($lifeValuesResults) {
            $scores = $result->scores;
            if (!is_array($scores)) {
                $scores = is_string($scores) ? json_decode($scores, true) ?? [] : [];
            }
            $result->decoded_scores = $scores;
            $result->take_number = $lifeValuesResults->count() - $index; // Most recent is highest number
            return $result;
        });

        // Calculate total scores across all Life Values tests
        $totalLifeValuesScores = [];
        if ($lifeValuesResults->count() > 0) {
            $allLifeValuesScores = $lifeValuesResults->pluck('decoded_scores');
            foreach (range('A', 'N') as $letter) {
                $totalLifeValuesScores[$letter] = $allLifeValuesScores->sum($letter);
            }
            $totalLifeValuesScores['total'] = array_sum($totalLifeValuesScores);
            $totalLifeValuesScores['average'] = count($totalLifeValuesScores) > 0 ? round($totalLifeValuesScores['total'] / 14, 2) : 0;
            $totalLifeValuesScores['top3'] = collect($totalLifeValuesScores)->except(['total', 'average'])->sortDesc()->take(3)->keys()->implode('');
        }

        return view('student.dashboard', compact('riasecResults', 'lifeValuesResults', 'additionalInfo', 'totalScores', 'totalLifeValuesScores'));
    }

    public function testing(){
        $user = auth()->user(); 
        return view('student.testingdash');
    }

    public function riasec(){
        $user = auth()->user(); 
        return view('testing.riasec');
    }
    

    public function profile()
     {
         // You can pass any data if needed
         $user = auth()->user(); // Example: currently logged-in superadmin

         // Get additional information for profile picture
         $additionalInfo = AdditionalInformation::where('learner_id', $user->id)->latest()->first();
         if (!$additionalInfo) {
             $additionalInfo = ArchivedStudentInformation::where('learner_id', $user->id)->latest()->first();
         }
         if (!$additionalInfo) {
             $additionalInfo = ArchivedStudentInformation::where('learner_id', $user->id)->latest()->first();
         }

         return view('student.update-profile', compact('user', 'additionalInfo'));
     }
 
     // Handle profile update
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
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'], // 10MB max
        ], [
            'email.regex' => 'You must register with a Gmail or Yahoo email address.',
            'profile_picture.image' => 'The profile picture must be an image.',
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_picture.max' => 'The profile picture may not be greater than 10MB.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Get old profile picture path
            $oldAdditionalInfo = AdditionalInformation::where('learner_id', $user->id)->first();
            $oldProfilePicture = $oldAdditionalInfo ? $oldAdditionalInfo->profile_picture : null;

            $file = $request->file('profile_picture');
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profiles'), $filename);
            $profilePicturePath = 'profiles/' . $filename;

            // Update or create additional information record
            $additionalInfo = AdditionalInformation::updateOrCreate(
                ['learner_id' => $user->id],
                ['profile_picture' => $profilePicturePath]
            );

            // Delete old profile picture file if it exists and is different
            if ($oldProfilePicture && $oldProfilePicture !== $profilePicturePath && file_exists(public_path($oldProfilePicture))) {
                unlink(public_path($oldProfilePicture));
                \Log::info('Profile update: Deleted old profile picture for user ' . $user->id . ': ' . $oldProfilePicture);
            }

            \Log::info('Profile update: Updated profile picture for user ' . $user->id . ' from ' . ($oldProfilePicture ?? 'none') . ' to ' . $profilePicturePath);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function viewAdditionalInfo()
    {
        $user = auth()->user();
        $info = AdditionalInformation::where('learner_id', $user->id)->first();

        // If not found in active, check archived
        if (!$info) {
            $info = ArchivedStudentInformation::where('learner_id', $user->id)->first();
        }

        if (!$info) {
            return redirect()->route('student.additional-info')->with('error', 'Please submit your additional information first.');
        }


        $schoolYear = SchoolYear::find($info->school_year_id);
        $info->school_year_name = $schoolYear ? $schoolYear->school_year : 'N/A';

        if (is_string($info->living_mode)) {
            $info->living_mode = json_decode($info->living_mode, true);
        }

        // Add formatted dates for display
        $info->current_date_formatted = $info->current_date ? $info->current_date->format('F j, Y') : null;
        $info->birthday_formatted = $info->birthday ? $info->birthday->format('F j, Y') : null;

        return view('student.view-additional-info', compact('info', 'user'));
    }

    /**
     * Return additional information as JSON for the authenticated student
     */
    public function getAdditionalInfoJson()
    {
        $user = auth()->user();
        $info = AdditionalInformation::where('learner_id', $user->id)->first();

        // If not found in active, check archived
        if (!$info) {
            $info = ArchivedStudentInformation::where('learner_id', $user->id)->first();
        }

        if (!$info) {
            return response()->json(['error' => 'No student information found for the current user.']);
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
}
