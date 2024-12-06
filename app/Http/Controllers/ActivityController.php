<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignSubject; 
use App\Models\Subject; 

class ActivityController extends Controller
{
    public function index()
    {
        // Get user session data
        $userID = session('userID');
        $user = session('user');
        $userDepartment = session('userDepartment');
        $userPosition = session('userPosition');

        // Ensure the user is logged in and has assigned subjects
        if ($userPosition !== 'program_heads' && !$userID) {
            return redirect()->back()->with('alert', 'Unauthorized access.');
        }

        // Fetch assigned subjects for the logged-in user
        $assignedSubjects = AssignSubject::where('prof_id', $userID) 
                                         ->with('subject') 
                                         ->get();

        // Prepare data to be passed to the view
        $subjects = $assignedSubjects->map(function ($assignedSubject) {
            return $assignedSubject->subject->name; 
        });

        // Return view with the assigned subjects and other session data
        return view('professor.pages.subjects', [
            'subjects' => $subjects, 
            'user' => $user,
            'userDepartment' => $userDepartment,
            'userPosition' => $userPosition,
        ]);
    }
}
