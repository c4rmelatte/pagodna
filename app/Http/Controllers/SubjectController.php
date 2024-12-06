<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Curriculum;

class SubjectController extends Controller
{
    //*********************admin dashboard***************************
    public function index()
    {

        $subjects = Subject::latest()->get();
        $curriculums = Curriculum::latest()->get();

        return view('admin.pages.subject')
        ->with( 'subjects', $subjects)
        ->with( 'curriculums', $curriculums);

    }

    //**********************Subject**************************

    // public function createSubject(){
    //     $Subjects = Subject::all();
    //     return view('admin.pages.subject', compact('subjects'));
    // }

    public function store(Request $request)
    {
        Subject::create([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'curriculum_id' => $request->get('curriculum_id'),
            'description' => $request->get('description')
        ]);

        return redirect()->to('/admin/subject');
        // return redirect()->route('admin.pages.admin');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->to('/admin/subject');
    }

    public function editSubject($id)
    {
        $Subject = Subject::findOrFail($id);
        return view('admin.pages.updateSubject', compact('Subject'));
    }

    public function update(Request $request, $id)
    {
        $subjectId = Subject::findOrFail($id);

        $subjectId->update([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'curriculum_id' => $request->get('curriculum_id'),
            'description' => $request->get('description')
        ]);
        return redirect()->to('/admin/subject');
    }
}
