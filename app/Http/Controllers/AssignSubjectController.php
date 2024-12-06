<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Department;
use App\Models\Courses;
use App\Models\Subject;
use App\Models\User;
use App\Models\Curriculum;
use App\Models\AssignSubject;
// use Illuminate\Support\Facades\DB;

class AssignSubjectController extends Controller
{

    public function index(AssignSubject $assignSubject, Subject $subject, Department $department, Courses $course, Curriculum $curriculum)
    {
        // Get the currently logged-in user
        $userID = session('userID');
        $userPosition = session('userPosition');
        
        // Ensure the user is a program head
        if ($userPosition !== 'program_heads') {
            return redirect()->back()->with('alert', 'Unauthorized access.');
        }
    
        
        // Get the subjects, sections, and assigned subjects
        $subjects = Subject::latest()->get();
        $section = Section::latest()->get();
        $department = Department::latest()->get();
        $course = Courses::latest()->get();
        $curriculum = Curriculum::latest()->get();


        $assignedSubjects = AssignSubject::with(['subject', 'professor'])->get();
    
        // Fetch professors who belong to the same department as the program head
        $professors = User::where('position', 'professors')->latest()->get();

    
        return view('programhead.pages.assignsubject', [
            'professorID' => $userID,
            'professors' => $professors,
            'departments' => $department,
            'curriculums' => $curriculum,
            'courses' => $course,
            'subjects' => $subjects,
            'section' => $section,
            'assigned_subjects' => $assignedSubjects
        ]);
    }
    



    // assign prof to a specific subject only thos who have matching department of the programhead
    public function assign_subject(Request $request)
    {
        
        $userPosition = session('userPosition');
        if ($userPosition !== 'program_heads') {
            return redirect()->back()->with('alert', 'Unauthorized access.');
        }

        $fields = $request->validate([
            'curriculum_id' => 'required',
            'subject_id' => 'required',
            'department_id' => 'required',
            'prof_id' => 'required',
            'course_id' => 'required',
            'section_id' => 'required',
            'assigned_by' => 'required',

        ]);
        
        AssignSubject::create($fields);
        return back();
    }

//     public function update(Request $request, $id)
//     {
//         // $name = $request->get('name');
//         // $description = $request->get('description');
        
//         $assignedSubjects = AssignSubject::findOrFail($id);

//         $assignedSubjects->update([
//             'subject_id' => $request->get('subject_id'),
//             'prof_id' => $request->get('prof_id'),
//         ]);
        
//         $assignedSubjects->save();
//         return Redirect::to('/programhead/assignsubject');
//     }

//     public function destroy(AssignSubject $assignedSubject)
// {
//     // Delete the assigned subject
//     $assignedSubject->delete();

//     // Redirect back to the assign subject page
//     return Redirect::to('/programhead/assignsubject');
// }


}

